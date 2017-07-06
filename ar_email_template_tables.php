<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Crated: 5 July 2017 at 18:06:18 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

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
    case 'email_blueprints':
        email_blueprints(get_table_parameters(), $db, $user);
        break;
    case 'email_blueprints':
        email_blueprints(get_table_parameters(), $db, $user);
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


function email_blueprints($_data, $db, $user) {

    include_once 'utils/natural_language.php';

    $rtext_label = 'email template';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


  //  print $sql;

    $adata = array();



    if ($result = $db->query($sql)) {

        foreach ($result as $data) {



            $operations = '';




                $operations .= sprintf(
                    '<span class="button" onClick="select_blueprint(this,%d)"><i class="fa fa-trophy fa-fw"></i> %s</span>',
                     $data['Email Blueprint Key'], _('Use me')
                );




            $operations .= sprintf(
                '<i style="margin-left:40px" class="fa fa-lock discreet button fa-fw padding_right_5" aria-hidden="true"  onClick="unlock_delete_blueprint(this)" ></i>  <span class="super_discreet" id="delete_blueprint_button_%d" onClick="delete_blueprint(this,%d)"> %s <i class="fa fa-trash"></i></span>', $data['Email Blueprint Key'], $data['Email Blueprint Key'], _('Delete')
            );



            if($data['Email Blueprint Image Key']){
                $image=sprintf('<div class="tint"><img style="max-width:100px;height-width:50px" src="/image_root.php?id=%d&size=small" /></div>', $data['Email Blueprint Image Key']);

            }else{
                $image='<span style="font-style: italic" class="disabled">'._('Preview not available').'</span>';

            }

            if($data['Staff Alias']!=''){
                $author=sprintf('<span>%s</span>',$data['Staff Alias']);
            }else{
                $author=sprintf('<span class="discreet">%s</span>',_('Anonymous'));
            }


            $adata[] = array(
                'id'          => (integer)$data['Email Blueprint Key'],
                'image'       => $image,
                'author'       => $author,
                'name'    => $data['Email Blueprint Name'],
                'date'           => strftime("%a %e %b %Y", strtotime($data['Email Blueprint Created'].' +0:00')),
                'operations'  => $operations,

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


?>
