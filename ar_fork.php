<?php

/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 30 December 2015 at 16:01:40 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2013 Inikoo

 Version 3.0
*/

require_once 'common.php';
require_once 'utils/ar_common.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'msg'   => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$tipo = $_REQUEST['tipo'];


switch ($tipo) {

    case('get_process_bar'):
        $data = prepare_values(
            $_REQUEST, array(
                'fork_key'  => array('type' => 'key'),
                'tag'       => array('type' => 'string'),
                'extra_key' => array(
                    'type'     => 'key',
                    'optional' => true
                )
            )
        );


        get_process_bar($data, $db);
        break;

    default:
        $response = array(
            'state' => 404,
            'resp'  => 'Operation not found'
        );
        echo json_encode($response);

}

function get_process_bar($data, $db) {

    $fork_key = $data['fork_key'];
    $sql      = sprintf(
        "SELECT `Fork Result`,`Fork Scheduled Date`,`Fork Start Date`,`Fork State`,`Fork Type`,`Fork Operations Done`,`Fork Operations No Changed`,`Fork Operations Errors`,`Fork Operations Total Operations` FROM `Fork Dimension` WHERE `Fork Key`=%d ",
        $fork_key
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            $result_extra_data = array();

            if ($row['Fork State'] == 'In Process') {
                $msg = number(
                        $row['Fork Operations Done'] + $row['Fork Operations Errors'] + $row['Fork Operations No Changed']
                    ).'/'.$row['Fork Operations Total Operations'];
            } elseif ($row['Fork State'] == 'Queued') {
                $msg = _('Queued');
            } elseif ($row['Fork State'] == 'Finished' or $row['Fork State'] == 'Cancelled') {
                $msg = '';
                if ($row['Fork Type'] == 'import') {

                    include_once 'class.ImportedRecords.php';
                    $imported_records = new ImportedRecords(
                        'id', $data['extra_key']
                    );

                    $result_extra_data = array(
                        'finished_date'  => $imported_records->get(
                            'Finish Date'
                        ),
                        'cancelled_date' => $imported_records->get(
                            'Cancelled Date'
                        ),
                        'start_date'     => $imported_records->get(
                            'Start Date'
                        ),

                        'finished_list_link'         => '<a href="list.php?id='.$imported_records->get(
                                'Imported Records Subject List Key'
                            ).'">'.$imported_records->get(
                                'Imported Records Subject List Name'
                            ).'</a>',
                        'finished_records_done'      => $imported_records->get(
                            'Imported'
                        ),
                        'finished_records_ignored'   => $imported_records->get(
                            'Ignored'
                        ),
                        'finished_records_error'     => $imported_records->get(
                            'Errors'
                        ),
                        'finished_records_cancelled' => $imported_records->get(
                            'Cancelled'
                        ),
                        'finished_state'             => $imported_records->data['Imported Records State']

                    );
                }


            } else {
                $msg = '';
            }

            $result_info = number($row['Fork Operations Done']);

            switch ($data['tag']) {
                case 'customers':
                    $result_info .= ' '.ngettext(
                            'customer', 'customers', $row['Fork Operations Done']
                        );
                    break;
                case 'orders':
                    $result_info .= ' '.ngettext(
                            'order', 'orders', $row['Fork Operations Done']
                        );
                    break;
                case 'invoices':
                    $result_info .= ' '.ngettext(
                            'invoice', 'invoices', $row['Fork Operations Done']
                        );
                    break;
                case 'dn':
                    $result_info .= ' '.ngettext(
                            'delivery note', 'delivery notes', $row['Fork Operations Done']
                        );
                    break;
                case 'parts':
                    $result_info .= ' '.ngettext(
                            'part', 'parts', $row['Fork Operations Done']
                        );
                    break;
                case 'products':
                    $result_info .= ' '.ngettext(
                            'product', 'products', $row['Fork Operations Done']
                        );
                    break;
                case 'families':
                    $result_info .= ' '.ngettext(
                            'family', 'families', $row['Fork Operations Done']
                        );
                    break;
                case 'departments':
                    $result_info .= ' '.ngettext(
                            'department', 'departments', $row['Fork Operations Done']
                        );
                    break;
                case 'pages':
                    $result_info .= ' '.ngettext(
                            'page', 'pages', $row['Fork Operations Done']
                        );
                    break;
                default:
                    $result_info .= ' '.ngettext(
                            'record', 'records', $row['Fork Operations Done']
                        );

            }

            if (percentage(
                    $row['Fork Operations Done'], $row['Fork Operations Total Operations']
                ) == '100.0%'
            ) {
                $download_info = '<i class="fa fa-spinner fa-spin"></i>'._(
                        'Saving'
                    );
            } else {
                $download_info = percentage(
                    $row['Fork Operations Done'], $row['Fork Operations Total Operations']
                );
            }

            $response = array(
                'state'             => 200,
                'fork_key'          => $fork_key,
                'fork_state'        => $row['Fork State'],
                'done'              => number($row['Fork Operations Done']),
                'no_changed'        => number(
                    $row['Fork Operations No Changed']
                ),
                'errors'            => number($row['Fork Operations Errors']),
                'total'             => number(
                    $row['Fork Operations Total Operations']
                ),
                'todo'              => number(
                    $row['Fork Operations Total Operations'] - $row['Fork Operations Done']
                ),
                'result'            => $row['Fork Result'],
                'msg'               => $msg,
                'progress'          => sprintf(
                    '%s/%s (%s)', number($row['Fork Operations Done']), number($row['Fork Operations Total Operations']), percentage(
                        $row['Fork Operations Done'], $row['Fork Operations Total Operations']
                    )
                ),
                'percentage'        => percentage(
                    $row['Fork Operations Done'], $row['Fork Operations Total Operations']
                ),
                'download_info'     => $download_info,
                'tag'               => $data['tag'],
                'result_info'       => $result_info,
                'result_extra_data' => $result_extra_data

            );


            echo json_encode($response);


        } else {
            $response = array(
                'state' => 400,

            );
            echo json_encode($response);
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


?>
