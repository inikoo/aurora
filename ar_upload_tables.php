<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 March 2016 at 14:54:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';

require_once 'class.Upload.php';


/*
if (!$user->can_view('staff')) {
	echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
	exit;
}
*/


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
    case 'records':
        records(get_table_parameters(), $db, $user);
        break;
    case 'uploads':
        uploads(get_table_parameters(), $db, $user);
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


function records($_data, $db, $user) {


    $rtext_label = 'record';

    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Upload Record State']) {
                case 'InProcess':
                    $state = '<i class="fa fa-clock-o padding_left_10"></i>';
                    $msg   = _('In process');
                    break;
                case 'OK':
                    $state
                         = '<i class="fa success fa-check padding_left_10"></i>';
                    $msg = sprintf(
                        '<span class="link" onClick="change_view(\'%s%d\')">%s</span>', $link, $data['Upload Record Object Key'], $data['object_name']
                    );

                    break;
                case 'Warning':
                    $state
                         = '<i class="fa warning fa-check padding_left_10"></i>';
                    $msg = sprintf(
                        '<span class="link">%s</span>', $data['object_name']
                    );

                    break;
                case 'Cancelled':
                    $state
                         = '<i class="fa discret fa-ban padding_left_10"></i>';
                    $msg = _('Cancelled');

                    break;
                case 'Error':
                    $state
                         = '<i class="fa error fa-exclamation-circle padding_left_10"></i>';
                    $msg = '';

                    switch ($data['Upload Record Message Code']) {
                        case 'missing_required_fields':
                            $msg = _('Missing required fields').': ';

                            $fields  = json_decode(
                                $data['Upload Record Message Metadata']
                            );
                            $_fields = '';
                            foreach ($fields as $field) {
                                $_fields .= _($field).', ';
                            }
                            $msg .= ' '.preg_replace('/\, $/', '', $_fields);

                            break;
                        case 'missing_required_fields':
                            $msg    = _('Missing required field').': ';
                            $fields = json_decode(
                                $data['Upload Record Message Metadata']
                            );

                            foreach ($fields as $field) {
                                $msg .= _($field);
                            }
                            break;
                        case 'duplicated_field':
                        default:
                            $msg = _('Duplicated unique field');
                            break;
                    }


                    break;
                default:
                    $state = '';
                    $msg   = '';

                    break;
            }


            $adata[] = array(
                'id'    => (integer)$data['Upload Record Key'],
                'row'   => sprintf(
                    '<span title="%s">%s</span>', $data['Upload File Name'], number($data['Upload Record Row Index'])
                ),
                'date'  => (($data['Upload Record Date'] == '')
                    ? ''
                    : strftime(
                        "%c", strtotime($data['Upload Record Date'].' +0:00')
                    )),
                'state' => $state,
                'msg'   => $state.' '.$msg
            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
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


function uploads($_data, $db, $user) {


    $rtext_label = 'upload';

    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Upload State']) {
                case 'InProcess':
                case 'Uploaded':
                    $state = _('In process');
                    break;
                case 'Finished':
                    $state = _('Finished');


                    break;
                case 'Cancelled':
                    $state = _('Cancelled');

                    break;

                default:
                    $state = $data['Upload State'];

                    break;
            }


            $adata[] = array(
                'id'          => (integer)$data['Upload Key'],
                'formated_id' => sprintf('%04d', $data['Upload Key']),
                'date'        => (($data['Upload Created'] == '')
                    ? ''
                    : strftime(
                        "%c", strtotime($data['Upload Created'].' +0:00')
                    )),
                'state'       => $state,
                'ok'          => number($data['Upload OK']),
                'warnings'    => number($data['Upload Warnings']),
                'errors'      => number($data['Upload Errors'])

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
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


?>
