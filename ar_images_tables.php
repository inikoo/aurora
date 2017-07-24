<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Crated: 2 March 2016 at 11:06:36 GMT+8, Yiwu , China
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
    case 'images':
        images(get_table_parameters(), $db, $user);
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


function images($_data, $db, $user) {

    include_once 'utils/natural_language.php';

    $rtext_label = 'image';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    //print_r($_data);


  //  print $sql;

    $adata = array();

    $ordinal_formatter = new \NumberFormatter(
        "en-GB", \NumberFormatter::ORDINAL
    );

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            if ($data['Image Subject Is Public'] == 'Yes') {
                $visibility = sprintf(
                    '<i title="%s" class="fa fa-eye"></i>', _('Public')
                );
            } else {
                $visibility = sprintf(
                    '<i title="%s" class="fa fa-eye-slash"></i>', _('Private')
                );
            }


            $operations = '';

            if ($data['Image Subject Order'] > 1) {


                $operations .= sprintf(
                    '<div style="margin-bottom:10px"><span class="button" id="set_as_principal_image_button_%d" onClick="set_as_principal(%d)"><i class="fa fa-trophy"></i> %s</span></div>',
                    $data['Image Subject Key'], $data['Image Subject Key'], _('Set as first')
                );

            }


          //  $operations .= sprintf(
          //      '<span class="button" id="edit_image_%d" onClick="change_view(\'image/%d\')"><i class="fa fa-pencil"></i> %s</span><br>', $data['Image Subject Key'], $data['Image Subject Key'], _('Edit')
          //  );

            $operations .= sprintf(
                '<span class="button" id="delete_image_button_%d" onClick="delete_image(%d)"><i class="fa fa-trash"></i> %s</span>', $data['Image Subject Key'], $data['Image Subject Key'], _('Delete')
            );

            switch ($data['Image Subject Object Image Scope']){
                case 'SKO':

                    $object_image_scope=_('SKO image');
                    break;

                case 'Default':

                    switch ($data['Image Subject Object']){
                        case 'Part':
                        case 'Product':

                        $object_image_scope=_('Marketing');
                            break;


                        default:
                            $object_image_scope=$data['Image Subject Object Image Scope'];

                    }


                    break;
                default:
                    $object_image_scope=$data['Image Subject Object Image Scope'];

            }



            if($data['Image Subject Object']=='Part'){
                $object_image_scope=sprintf('<span id="edit_image_scope_%d" class="button" onclick="edit_image_scope(%d)" >%s</span>', $data['Image Subject Key'], $data['Image Subject Key'],$object_image_scope);

            }



            if($data['Image Subject Image Caption']!=''){
                $caption='<span class="caption">'.$data['Image Subject Image Caption'].'</span>';
            }else{
                $caption='<i class="fa fa-plus" aria-hidden="true"></i><span class="caption"></span>';
            }


            $caption=sprintf('<span id="edit_image_caption_%d" class="button" onclick="edit_image_caption(%d)" >%s</span>', $data['Image Subject Key'], $data['Image Subject Key'],$caption);


            $adata[] = array(
                'id'          => (integer)$data['Image Subject Key'],
                'image'       => sprintf(
                    '<div class="tint"><img style="max-width:100px;height-width:50px" src="/image_root.php?id=%d&size=small" title="%s" /></div>', $data['Image Key'], $data['Image Filename']
                ),

                'preview'       => sprintf(
                    '<a href="/image_root.php?id=%d&size=original" data-type="image" data-fancybox="group" data-caption="%s">
                    <img  src="/image_root.php?id=%d&size=small"  style="max-width:100px;height-width:50px"  />
                 </a>',
                    $data['Image Key'],   $data['Image Subject Image Caption'],$data['Image Key']
                ),

                'filename'    => $data['Image Filename'],
                'caption'     => $caption,
                'size'        => file_size($data['Image File Size']),
                'dimensions'  => $data['Image Width'].'x'.$data['Image Height'],
                'operations'  => $operations,
                'visibility'  => $visibility,
                'image_order' => $ordinal_formatter->format($data['Image Subject Order']),
                 'object_image_scope'  => $object_image_scope
                //'type'=>$type,
                //'file_type'=>$file_type,
                //'file'=>sprintf('<a href="/attachment.php?id=%d" download><i class="fa fa-download"></i></a>  <a href="/attachment.php?id=%d" >%s</a>' , $data['Image Subject Key'], $data['Image Subject Key'], $data['Image File Original Name']),
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
