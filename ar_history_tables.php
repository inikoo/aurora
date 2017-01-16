<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2009
 Refurbished: 6 October 2015 at 09:41:00 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


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
    case 'object_history':
        object_history(get_table_parameters(), $db, $user);
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


function object_history($_data, $db, $user) {

   // print_r($_data);

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;


    $adata = array();


    foreach ($db->query($sql) as $data) {


        $data['History Details'] = trim($data['History Details']);
        //print_r($data);

        if ($data['History Details'] == '') {
            $note = nl2br($data['History Abstract']);
        } else {
            $note = nl2br($data['History Abstract']).' <i  class="fa fa-angle-double-down fw button" id="history_details_button_'.$data['History Key'].'" onclick="show_history_details('
                .$data['History Key'].')"></i> <div  id="history_details_'.$data['History Key'].'" class="history_details hide">'.nl2br(
                    $data['History Details']
                ).'</div>';
        }


        if ($data['Type'] == 'Notes') {

            if ($data['Deletable'] == 'Yes') {
                $edit = sprintf(
                    '<i history_key="%d" id="history_note_edit_button_%d" class="fa fa-pencil very_discreet button fw note_buttons" alt="%s" ></i>', $data['History Key'], $data['History Key'],
                    _('Edit')
                );
            } else {
                $edit = sprintf(
                    '<i history_key="%d" id="undo_strikethrough_button_%d" class="fa strikethrough_button fa-undo very_discreet button fw %s" alt="%s" ></i>', $data['History Key'],
                    $data['History Key'], ($data['Strikethrough'] == 'Yes' ? '' : 'hide'), _('unstrikethrough')
                );
                $edit .= sprintf(
                    '<i history_key="%d" id="strikethrough_button_%d"  class="fa strikethrough_button fa-strikethrough very_discreet button fw %s" alt="%s" ></i>', $data['History Key'],
                    $data['History Key'], ($data['Strikethrough'] == 'Yes' ? 'hide' : ''),

                    _('strikethrough')
                );


            }
            $note = sprintf(
                '<span id="history_note_%d" class="%s">%s</span> %s <span id="history_note_msg_%d"></span>', $data['History Key'], ($data['Strikethrough'] == 'Yes' ? 'strikethrough' : ''), $note,
                $edit, $data['History Key']
            );
        }


        //$objeto=$data['Direct Object'];
        $objeto = $data['History Details'];

        if ($data['Subject'] == 'Customer') {
            $author = _('Customer');
        } else {
            $author = $data['Author Name'];
        }


        $adata[] = array(
            'id'     => (integer)$data['History Key'],
            'date'   => $data['History Date'],
            'date'   => strftime(
                "%a %e %b %Y %H:%M %Z ", strtotime($data['History Date']." +00:00")
            ),
            'time'   => strftime(
                "%H:%M %Z", strtotime($data['History Date']." +00:00")
            ),
            'objeto' => $objeto,
            'note'   => $note,
            'author' => $author,


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
