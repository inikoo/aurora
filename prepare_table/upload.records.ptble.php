<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
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
            case 'production_part':
                $table
                       = '  `Upload Record Dimension` as R  left join `Upload File Dimension` F on (F.`Upload File Key`=`Upload Record Upload File Key`)  left join `Supplier Part Dimension` O on (O.`Supplier Part Key`=R.`Upload Record Object Key`)  left join `Supplier Part Deleted Dimension` OD on (OD.`Supplier Part Deleted Key`=R.`Upload Record Object Key`) ';
                $object_field
                       = ' `Supplier Part Reference` as object_name,`Supplier Part Deleted Reference` as object_auxiliar_name,CONCAT("production/",`Supplier Part Supplier Key`,"/part/",`Upload Record Object Key`) as link ';
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
            case 'location':
                $table
                       = '  `Upload Record Dimension` as R  left join `Upload File Dimension` F on (F.`Upload File Key`=`Upload Record Upload File Key`)  left join `Location Dimension` O on (O.`Location Key`=R.`Upload Record Object Key`)  left join `Location Deleted Dimension` OD on (OD.`Location Deleted Key`=R.`Upload Record Object Key`) ';
                $object_field
                       = ' `Location Code` as object_name,`Location Deleted Code` as object_auxiliar_name,CONCAT("locations/",`Location Warehouse Key`,"/",`Upload Record Object Key`) as link ';
                $where = sprintf(
                    " where  `Upload Record Upload Key`=%d ", $parameters['parent_key']
                );

                break;
            case 'warehouse_area':
                $table
                       = '  `Upload Record Dimension` as R  left join `Upload File Dimension` F on (F.`Upload File Key`=`Upload Record Upload File Key`)  left join `Warehouse Area Dimension` O on (O.`Warehouse Area Key`=R.`Upload Record Object Key`) ';
                $object_field
                       = ' `Warehouse Area Code` as object_name,"" as object_auxiliar_name,CONCAT("warehouse/",`Warehouse Area Warehouse Key`,"/areas/",`Upload Record Object Key`) as link ';
                $where = sprintf(
                    " where  `Upload Record Upload Key`=%d ", $parameters['parent_key']
                );

                break;
            case 'prospect':
                $table
                       = '  `Upload Record Dimension` as R  left join `Upload File Dimension` F on (F.`Upload File Key`=`Upload Record Upload File Key`)  left join `Prospect Dimension` O on (O.`Prospect Key`=R.`Upload Record Object Key`) ';
                $object_field
                       = ' `Prospect Name` as object_name,"" as object_auxiliar_name,CONCAT("prospects/",`Prospect Store Key`,"/",`Upload Record Object Key`) as link ';
                $where = sprintf(
                    " where  `Upload Record Upload Key`=%d ", $parameters['parent_key']
                );

                break;
            case 'fulfilment_asset':
                $table
                       = '  `Upload Record Dimension` as R  left join `Upload File Dimension` F on (F.`Upload File Key`=`Upload Record Upload File Key`)  left join `Fulfilment Asset Dimension` O on (O.`Fulfilment Asset Key`=R.`Upload Record Object Key`) ';
                $object_field
                       = ' `Fulfilment Asset Key` as object_name,"" as object_auxiliar_name,CONCAT("fa/",`Upload Record Object Key`) as link ';
                $where = sprintf(
                    " where  `Upload Record Upload Key`=%d ", $parameters['parent_key']
                );

                break;
            default:
                exit('object not supported in upload_records.ptble.php '.$upload->get('Upload Object'));
                break;
        }


        break;


    default:
        exit('parent not supported');
}


$wheref = '';
if ($parameters['f_field'] == 'object_name' and $f_value != '') {
    $wheref .= " and  object_name like '".addslashes($f_value)."%'    ";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'row') {
    $order = '`Upload Record Row Index`';
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


