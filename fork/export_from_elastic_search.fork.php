<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  09 June 2020  22:52::24  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

include_once 'utils/send_zqm_message.class.php';
require_once 'vendor/autoload.php';

use Elasticsearch\ClientBuilder;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

function fork_export_from_elastic_search($job) {

    global $account, $db;// remove the global $db and $account is removed


    if (!$_data = get_fork_metadata($job)) {
        return false;
    }

    /**
     * @var $account \Account
     * @var $db      \PDO
     */
    $account   = $_data[0];
    $db        = $_data[1];
    $fork_data = $_data[2];

    // print_r($fork_data);

    $inikoo_account_code = $account->get('Account Code');


    $output_type = $fork_data['output'];

    $ws_key       = $fork_data['ws_key'];
    $download_key = $fork_data['download_key'];


    $creator     = 'aurora.systems';
    $title       = _('Report');
    $subject     = _('Report');
    $description = '';
    $keywords    = '';
    $category    = '';


    $output_filename = 'export_'.$inikoo_account_code.'_'.$fork_data['tipo'].'_'.$download_key;

    $columns_no_resize = array();

    $sql = "update `Download Dimension` set `Download State`='In Process',`Download Filename`=? where `Download Key`=?  ";


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $output_filename.'.'.$output_type,
            $download_key
        )
    );


    $client = ClientBuilder::create()->setHosts(get_elasticsearch_hosts())
        ->setApiKey(ES_KEY1,ES_KEY2)
        ->setSSLVerification(ES_SSL)
        ->build();

    $params = [
        'index'  => strtolower('au_part_isf_'.strtolower(DNS_ACCOUNT_CODE)),
        'size'   => 10,
        'scroll' => '5s'
    ];


    $params['body']['query'] = [

        'bool' => [


            'filter' => [
                "term" => [
                    $fork_data['parameters']['parent'] => [
                        'value' => $fork_data['parameters']['parent_key'],
                    ]
                ]
            ]
        ]


    ];


    $response = $client->search($params);


    $number_rows = $response['hits']['total']['value'];


    $sockets = get_zqm_message_sockets();

    foreach ($sockets as $socket) {
        $socket->send(
            json_encode(
                array(
                    'channel'      => $ws_key,
                    'progress_bar' => array(
                        array(
                            'id'            => 'download_'.$download_key,
                            'state'         => 'In Process',
                            'progress_info' => percentage(0, $number_rows),
                            'progress'      => sprintf('%s/%s (%s)', number(0), number($number_rows), percentage(0, $number_rows)),
                            'percentage'    => percentage(0, $number_rows),

                        )

                    ),


                )
            )
        );
    }

    $objPHPExcel = new Spreadsheet();
    Cell::setValueBinder(new AdvancedValueBinder());


    $objPHPExcel->getProperties()->setCreator($creator)->setLastModifiedBy($creator)->setTitle($title)->setSubject($subject)->setDescription($description)->setKeywords($keywords)->setCategory(
        $category
    );

    $row_index = 1;

    if (empty($fork_data['fields']) or $fork_data['fields'] == '') {

        $sql = sprintf("update `Download Dimension` set `Download State`='Error' where `Download Key`=%d  ", $download_key);
        $db->exec($sql);

        return 1;
    }




    $char_index = 1;
    foreach ($fork_data['fields'] as $_key) {



        if (isset($fork_data['field_set'][$_key]['labels'])) {

            foreach ($fork_data['field_set'][$_key]['labels'] as $label) {
                $char = number2alpha($char_index);
                $objPHPExcel->getActiveSheet()->setCellValue($char.$row_index, strip_tags($label));
                $char_index++;
            }


        } else {



            $char = number2alpha($char_index);
            $objPHPExcel->getActiveSheet()->setCellValue(
                $char.$row_index, strip_tags($fork_data['field_set'][$_key]['label'])
            );
            $char_index++;
        }


    }

    $row_index++;
    $show_feedback = (float)microtime(true) + .250;


    foreach ($response['hits']['hits'] as $hit) {

        list($objPHPExcel,$row_index)=process_hit($hit['_source'], $row_index, $objPHPExcel,$fork_data['fields'], $fork_data['field_set']);
        $show_feedback = show_feedback($db, $show_feedback, $sockets, $row_index, $number_rows, $download_key, $ws_key);


    }


    while (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {

        foreach ($response['hits']['hits'] as $hit) {

            list($objPHPExcel,$row_index)=process_hit($hit['_source'], $row_index, $objPHPExcel, $fork_data['fields'],$fork_data['field_set']);

            $show_feedback = show_feedback($db, $show_feedback, $sockets, $row_index, $number_rows, $download_key, $ws_key);
        }

        $scroll_id = $response['_scroll_id'];


        $response = $client->scroll(
            [
                'scroll_id' => $scroll_id,
                'scroll'    => '5s'
            ]
        );


    }



    try {
        $sheet = $objPHPExcel->getActiveSheet();

        $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);

        foreach ($cellIterator as $cell) {

            // print_r($cell->getColumn());
            if (in_array($cell->getColumn(), $columns_no_resize)) {
                $sheet->getColumnDimension($cell->getColumn())->setWidth(250);
            } else {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);

            }

        }


    } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {

    }


    $objPHPExcel->getActiveSheet()->freezePane('A2');


    $download_path = 'tmp/';

    switch ($output_type) {

        case('csv'):
            $output_file = $download_path.$output_filename.'.'.$output_type;
            IOFactory::createWriter($objPHPExcel, 'Csv')->setDelimiter(',')->setEnclosure('')->setLineEnding("\r\n")->setSheetIndex(0)->save($output_file);
            break;
        case('xlsx'):
            $output_file = $download_path.$output_filename.'.'.$output_type;
            IOFactory::createWriter($objPHPExcel, 'Xlsx')->save($output_file);
            break;
        case('xls'):
            $output_file = $download_path.$output_filename.'.'.$output_type;
            IOFactory::createWriter($objPHPExcel, 'Xls')->save($output_file);
            break;
        case('pdf'):
            $output_file = $download_path.$output_filename.'.'.$output_type;

            $objPHPExcel->getActiveSheet()->setShowGridLines(false);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
            IOFactory::createWriter($objPHPExcel, 'Pdf')->save($output_file);
            break;
        default:

            return true;

    }


    $sql = "update `Download Dimension` set `Download State`='Finish' , `Download Data`=?   where `Download Key`=? ";


    $db->prepare($sql)->execute(
        array(
            file_get_contents($output_file),
            $download_key
        )
    );


    foreach ($sockets as $socket) {
        $socket->send(
            json_encode(
                array(
                    'channel'      => $ws_key,
                    'progress_bar' => array(
                        array(
                            'id'            => 'download_'.$download_key,
                            'state'         => 'Finish',
                            'download_key'  => $download_key,
                            'progress_info' => _('Done'),
                            'progress'      => sprintf('%s/%s (%s)', number($number_rows), number($number_rows), percentage($number_rows, $number_rows)),
                            'percentage'    => percentage($number_rows, $number_rows),

                        )

                    ),


                )
            )
        );
    }


    unlink($output_file);

    return false;
}


function process_hit($hit_data, $row_index, $objPHPExcel, $fields,$field_set) {


    $char_index = 1;
    foreach ($fields as $field_key) {




        $value=$hit_data[$field_set[$field_key]['name']];

        $char = number2alpha($char_index);


        $type = (empty($field_set[$field_key]['type']) ? '' : $field_set[$field_key]['type']);


        if ($type == 'html') {
            $_value = $value;
        } else {


            $value = str_replace("\xc2\xa0", ' ', $value);


            $_value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5);

        }


        if ($type == 'text') {

            $objPHPExcel->getActiveSheet()->setCellValueExplicit($char.$row_index, $_value, DataType::TYPE_STRING);


        } else {
            $objPHPExcel->getActiveSheet()->setCellValue($char.$row_index, $_value);
        }


        $char_index++;
    }
    $row_index++;


    return [$objPHPExcel,$row_index];

}


function show_feedback($db, $show_feedback, $sockets, $row_index, $number_rows, $download_key, $ws_key) {

    if (microtime(true) > $show_feedback) {


        $sql = "select `Download State` from  `Download Dimension` where `Download Key`=?";


        $db->prepare($sql)->execute(
            array(
                $download_key
            )
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['Download State'] == 'Cancelled') {
                    return 1;
                }
            }
        }

        foreach ($sockets as $socket) {
            $socket->send(
                json_encode(
                    array(
                        'channel'      => $ws_key,
                        'progress_bar' => array(
                            array(
                                'id'            => 'download_'.$download_key,
                                'state'         => 'In Process',
                                'progress_info' => percentage($row_index, $number_rows),
                                'progress'      => sprintf('%s/%s (%s)', number($row_index), number($number_rows), percentage($row_index, $number_rows)),
                                'percentage'    => percentage($row_index, $number_rows),

                            )

                        ),


                    )
                )
            );
        }

        $show_feedback= (float)microtime(true) + .400;


    }

    return $show_feedback;
}

