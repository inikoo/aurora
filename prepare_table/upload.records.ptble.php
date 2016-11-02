<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 March 2016 at 14:59:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case 'upload':


        $upload = get_object('Upload', $parameters['parent_key']);

        switch ($upload->get('Upload Object')) {
            case 'employee':
                $table
                              = '  `Upload Record Dimension` as R  left join `Upload File Dimension` F on (F.`Upload File Key`=`Upload Record Upload File Key`)  left join `Staff Dimension` O on (O.`Staff Key`=R.`Upload Record Object Key`) ';
                $object_field = ' `Staff Alias` as object_name ';
                $where        = sprintf(
                    " where  `Upload Record Upload Key`=%d ", $parameters['parent_key']
                );

                break;
            case 'supplier_part':
            case 'supplier_parts':
                $table
                       = '  `Upload Record Dimension` as R  left join `Upload File Dimension` F on (F.`Upload File Key`=`Upload Record Upload File Key`)  left join `Supplier Part Dimension` O on (O.`Supplier Part Key`=R.`Upload Record Object Key`)  left join `Supplier Part Deleted Dimension` OD on (OD.`Supplier Part Deleted Key`=R.`Upload Record Object Key`) ';
                $object_field
                       = ' `Supplier Part Reference` as object_name,`Supplier Part Deleted Reference` as object_auxiliar_name,CONCAT("supplier/",`Supplier Part Supplier Key`,"/part/",`Upload Record Object Key`) as link ';
                $where = sprintf(
                    " where  `Upload Record Upload Key`=%d ", $parameters['parent_key']
                );

                break;
            case 'part':
                $table
                       = '  `Upload Record Dimension` as R  left join `Upload File Dimension` F on (F.`Upload File Key`=`Upload Record Upload File Key`)  left join `Part Dimension` O on (O.`Part SKU`=R.`Upload Record Object Key`)  left join `Part Deleted Dimension` OD on (OD.`Part Deleted Key`=R.`Upload Record Object Key`) ';
                $object_field
                       = ' `Part Reference` as object_name,`Part Deleted Reference` as object_auxiliar_name,CONCAT("part/",`Upload Record Object Key`) as link ';
                $where = sprintf(
                    " where  `Upload Record Upload Key`=%d ", $parameters['parent_key']
                );

                break;

            case 'product':
                $table
                       = '  `Upload Record Dimension` as R  left join `Upload File Dimension` F on (F.`Upload File Key`=`Upload Record Upload File Key`)  left join `Product Dimension` O on (O.`Product ID`=R.`Upload Record Object Key`)  ';
                $object_field
                       = ' `Product Code` as object_name,"" as object_auxiliar_name,CONCAT("products/",`Product Store Key`,"/",`Upload Record Object Key`) as link ';
                $where = sprintf(
                    " where  `Upload Record Upload Key`=%d ", $parameters['parent_key']
                );

                break;

            default:
                exit('object not suported '.$upload->get('Upload Object'));
                break;
        }


        break;


    default:
        exit('parent not suported');
        break;
}


$wheref = '';
if ($parameters['f_field'] == 'object_name' and $f_value != '') {
    $wheref .= " and  object_name like '".addslashes($f_value)."%'    ";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'row') {
    $order = '`Upload Record Upload File Key`,`Upload Record Row Index`';
} elseif ($order == 'status') {
    $order = '`Upload Record Status`';
} elseif ($order == 'state') {
    $order = '`Upload Record State`';
} elseif ($order == 'date') {
    $order = '`Upload Record Date`';
} elseif ($order == 'object_name') {
    $order = 'object_name';
} elseif ($order == 'msg') {
    $order = '`Upload Record Message Code`';
} else {
    $order = '`Upload Record Key`';
}


$sql_totals = "select count(*) as num from $table  $where  ";

//print $sql_totals;
$fields
    = " $object_field,
`Upload Record Key`,
`Upload Record Row Index`,
`Upload File Name`,
`Upload Record Status`,
`Upload Record State`,
`Upload Record Date`,
`Upload Record Message Code`,
`Upload Record Message Metadata`,
`Upload Record Object Key`

";

?>
