<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   21 May 2020  13:16::53  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/


include_once 'utils/send_zqm_message.class.php';

function fork_orders_control_panel($job) {

    global $account, $db;// remove the global $db and $account is removed


    require_once 'vendor/autoload.php';
    require_once 'utils/common.pick_aid.php';

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

    print_r($fork_data);

    $download_key      = $fork_data['download_key'];
    $user_key          = $fork_data['user_key'];
    $number_deliveries = count($fork_data['delivery_notes_keys']);

    $merged_filename = uniqid('pick_aids_'.DNS_ACCOUNT_CODE.'_', true).'.pdf';

    $sql = "update `Download Dimension` set `Download State`='In Process',`Download Filename`=? where `Download Key`=? ";

    $db->prepare($sql)->execute(
        array(
            $merged_filename,
            $download_key
        )
    );


    $sockets = get_zqm_message_sockets();

    foreach ($sockets as $socket) {
        $socket->send(
            json_encode(
                array(
                    'channel'      => 'real_time.'.strtolower($account->get('Account Code')).'.'.$user_key,
                    'progress_bar' => array(
                        array(
                            'id'            => 'download_'.$download_key,
                            'state'         => 'In Process',
                            'progress_info' => percentage(0, $number_deliveries),
                            'progress'      => sprintf('%s/%s (%s)', number(0), number($number_deliveries), percentage(0, $number_deliveries)),
                            'percentage'    => percentage(0, $number_deliveries),

                        )

                    ),


                )
            )
        );
    }

    $show_feedback = (float)microtime(true) + .400;


    $smarty               = new Smarty();
    $smarty->caching_type = 'redis';
    $base                 = '';
    $smarty->setTemplateDir($base.'templates');
    $smarty->setCompileDir($base.'server_files/smarty/templates_c');
    $smarty->setCacheDir($base.'server_files/smarty/cache');
    $smarty->setConfigDir($base.'server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    if ($fork_data['type'] == 'picking_aid_with_labels') {
        $type = 'with_labels';
    } else {
        $type = 'normal';
    }

    $filenames = [];
    $contador  = 1;
    foreach ($fork_data['delivery_notes_keys'] as $_key => $id) {
        $mpdf          = get_pick_aid_mpdf($type);
        $delivery_note = get_object('DeliveryNote', $id);
        if (!$delivery_note->id) {
            continue;
        }

        $mpdf->SetTitle(_('Picking Aid').' '.$delivery_note->data['Delivery Note ID']);
        $mpdf->SetAuthor('Aurora systems');

        $html = get_picking_aid_html_for_pdf($type, $delivery_note, $db, $smarty, $account);
        $mpdf->WriteHTML($html);

        $filename    = 'tmp/'.uniqid('pick_aid_'.DNS_ACCOUNT_CODE.'_'.$delivery_note->id, true).'.pdf';
        $filenames[] = $filename;
        $mpdf->Output($filename, 'F');

        if (microtime(true) > $show_feedback) {

            $sql  = "select `Download State` from  `Download Dimension` where `Download Key`=?";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $download_key
                )
            );
            while ($row = $stmt->fetch()) {
                if ($row['Download State'] == 'Cancelled') {
                    return 1;
                }
            }


            foreach ($sockets as $socket) {
                $socket->send(
                    json_encode(
                        array(
                            'channel'      => 'real_time.'.strtolower($account->get('Account Code')).'.'.$user_key,
                            'progress_bar' => array(
                                array(
                                    'id'            => 'download_'.$download_key,
                                    'state'         => 'In Process',
                                    'progress_info' => percentage($contador, $number_deliveries),
                                    'progress'      => sprintf('%s/%s (%s)', number($contador), number($number_deliveries), percentage($contador, $number_deliveries)),
                                    'percentage'    => percentage($contador, $number_deliveries),
                                )
                            ),
                        )
                    )
                );

            }
            $show_feedback = (float)microtime(true) + .400;

            $contador++;

        }


    }



    mergePDFFiles($type, $filenames, 'tmp/'.$merged_filename);

    $sql = "update `Download Dimension` set `Download State`='Finish' , `Download Data`=?  where `Download Key`=? ";
    $db->prepare($sql)->execute(
        array(
            file_get_contents('tmp/'.$merged_filename),
            $download_key
        )
    );


    foreach ($sockets as $socket) {
        $socket->send(
            json_encode(
                array(
                    'channel'      => 'real_time.'.strtolower($account->get('Account Code')).'.'.$user_key,
                    'progress_bar' => array(
                        array(
                            'id'            => 'download_'.$download_key,
                            'state'         => 'Finish',
                            'download_key'  => $download_key,
                            'progress_info' => _('Done'),
                            'progress'      => sprintf('%s/%s (%s)', number($contador), number($contador), percentage($contador, $contador)),
                            'percentage'    => percentage($contador, $contador),
                        )
                    ),
                )
            )
        );
    }

    foreach ($filenames as $tmp_files) {
        unlink($tmp_files);
    }

    unlink( 'tmp/'.$merged_filename);

    return false;
}


function mergePDFFiles($type, array $filenames, $outFile, $title = '', $author = '', $subject = '') {
    $mpdf = get_pick_aid_mpdf($type);
    $mpdf->SetTitle($title);
    $mpdf->SetAuthor($author);
    $mpdf->SetSubject($subject);
    if ($filenames) {

        for ($i = 0; $i < count($filenames); $i++) {
            $curFile = $filenames[$i];
            if (file_exists($curFile)) {
                $pageCount = $mpdf->SetSourceFile($curFile);
                for ($p = 1; $p <= $pageCount; $p++) {
                    $tplId = $mpdf->ImportPage($p);
                    $wh    = $mpdf->getTemplateSize($tplId);
                    if (($p == 1)) {
                        $mpdf->state = 0;
                        $mpdf->UseTemplate($tplId);
                    } else {
                        $mpdf->state = 1;
                        $mpdf->AddPage($wh['w'] > $wh['h'] ? 'L' : 'P');
                        $mpdf->UseTemplate($tplId);
                    }
                }
            }
        }
    }
    $mpdf->Output($outFile, 'F');
    unset($mpdf);
}