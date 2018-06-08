<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 11:30:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/object_functions.php';


if (!$user->can_view('stores')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'subject_send_emails':
        subject_send_emails(get_table_parameters(), $db, $user);
        break;
    case 'email_tracking_events':
        email_tracking_events(get_table_parameters(), $db, $user);
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function subject_send_emails($_data, $db, $user) {

    $rtext_label = 'email';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    $parent = get_object($_data['parameters']['parent'], $_data['parameters']['parent_key']);

    // print $sql;
    //'Ready','Send to SES','Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Error'


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Email Tracking State']) {
                case 'Ready':
                    $state = _('Ready to send');
                    break;
                case 'Sent to SES':
                    $state = _('Sending');
                    break;

                    break;
                case 'Delivered':
                    $state = _('Delivered');
                    break;
                case 'Opened':
                    $state = _('Opened');
                    break;
                case 'Clicked':
                    $state = _('Clicked');
                    break;
                case 'Error':
                    $state = '<span class="warning">'._('Error').'</span>';
                    break;
                case 'Hard Bounce':
                    $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '._('Bounced').'</span>';
                    break;
                case 'Soft Bounce':
                    $state = '<span class="warning"><i class="fa fa-exclamation-triangle"></i>  '._('Probable bounce').'</span>';
                    break;
                case 'Spam':
                    $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '._('Mark as spam').'</span>';
                    break;
                default:
                    $state = $data['Email Tracking State'];
            }


            $subject = sprintf('<span class="link" onclick="change_view(\'%s/%d/%d/email/%d\')"  >%s</span>', strtolower($parent->get_object_name()).'s', $parent->get('Store Key'), $parent->id, $data['Email Tracking Key'], $data['Published Email Template Subject']);


            $adata[] = array(
                'id'      => (integer)$data['Email Tracking Key'],
                'state'   => $state,
                'subject' => $subject,
                'date'    => strftime("%a, %e %b %Y %R", strtotime($data['Email Tracking Created Date']." +00:00")),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function email_tracking_events($_data, $db, $user) {

    $rtext_label = 'event';

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;


    $adata = array();


    foreach ($db->query($sql) as $data) {


        switch ($data['Email Tracking Event Type']) {
            case 'Opened':
                $event=_('Opened');
               $_data=json_decode($data['data'],true);

                $note=$_data['userAgent'].' '.$_data['ipAddress'];
                $note='';
                break;
            default:

                $event = $data['Email Tracking Event Type'];
                $note  = '';
        }

        $adata[] = array(
            'id'   => (integer)$data['Email Tracking Event Key'],
            'date' => strftime(
                "%a %e %b %Y %H:%M %Z ", strtotime($data['Email Tracking Event Date']." +00:00")
            ),

            'note'  => $note,
            'event' => $event,


        );

    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


?>
