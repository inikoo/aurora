<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:5:22 pm Sunday, 1 Nov 2021 (MYT) Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
include_once 'class.Agent.php';
include_once 'class.SupplierPart.php';
include_once 'class.Part.php';
include_once 'class.Image.php';
include_once 'utils/currency_functions.php';


$editor = array(
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Robot',
    'Author Alias' => 'Robot',
    'v'            => 3


);


switch (DNS_ACCOUNT_CODE) {

    //case 'AW':
    //    $supplier = get_object('Supplier', 6707);
    //    $slave_id=1;
    //    break;
    case 'AWEU':
        $supplier = get_object('Supplier', 2);
        $slave_id=2;
        break;
    default:
        exit();
}


$account         = get_object('Account', 1);
$account->editor = $editor;


$editor['Date'] = gmdate('Y-m-d H:i:s');

$supplier->editor=$editor;

$sql = sprintf(
    'select * from dw.`Supplier Part Dimension` 
    left join dw.`Part Dimension` on (`Part Main Supplier Part Key`=`Supplier Part Key`) 
    left join dw.`Category Dimension` on (`Category Key`=`Part Family Category Key`)  
    left join dw.`Product Part Bridge`  ON (`Part SKU`=`Product Part Part SKU`)
    left join dw.`Product Dimension` P  ON (`Product ID`=`Product Part Product ID`)


where  `Product Customer Key` is null and  P.`Product ID` is not null and `Product Status`in ("Active")   and `Product Store Key`=1    order by `Part Reference`     '
);

$stmt4 = $db->prepare($sql);
$stmt4->execute();
while ($row4 = $stmt4->fetch()) {



    $go=false;
    $sql="select count(*) as num from `Part Dimension` where `Part Status` in ('In Use','Discontinuing') and `Part Reference`=? ";
    $stmtx = $db->prepare($sql);
    $stmtx->execute(
        [
            $row4['Part Reference']
        ]
    );
    while ($rowx = $stmtx->fetch()) {
        if($rowx['num']>0){
            $go=true;
        }
    }

    if($go) {

        print $row4['Part Reference']."\n";

        $editor['Date'] = gmdate('Y-m-d H:i:s');


        $product_properties = $row4['Product Properties'];

        if ($product_properties == '') {
            $product_properties = [];
        } else {
            $product_properties = json_decode($row4['Product Properties'], true);
        }

        if (!$product_properties) {
            $product_properties = [];
        }


        $exchange = currency_conversion(
            $db,
            'GBP',
            $account->get('Account Currency Code'),
            '- 1440 minutes'
        );


        if (!empty($product_properties['slaves'][$slave_id])) {
            $supplier_part_data = array(
                'Supplier Part Carton Barcode'       => $row4['Supplier Part Carton Barcode'],
                'Supplier Part Packages Per Carton'  => $row4['Supplier Part Packages Per Carton'],
                'Supplier Part Carton CBM'           => $row4['Supplier Part Carton CBM'],
                'Supplier Part Unit Cost'            => $row4['Product Price'] / $row4['Product Units Per Case'] * .55,
                'Supplier Part Minimum Carton Order' => $row4['Supplier Part Minimum Carton Order'],
                'editor'                             => $editor
            );

            //print_r($supplier_part_data);

            $supplier_part = get_object('SupplierPart', $product_properties['slaves'][$slave_id]);
            if ($supplier_part->id) {
                $supplier_part->editor = $editor;
                $supplier_part->update($supplier_part_data);

                if ($supplier_part->updated) {
                    print 'Updating '.$supplier_part->get('Reference')."\n";
                }
            }
        } else {
            $supplier_part_data = array(
                'Supplier Part Reference'                     => $row4['Part Reference'],
                'Supplier Part Description'                   => $row4['Supplier Part Description'],
                'Part Family Category Code'                   => $row4['Category Code'],
                'Part Reference'                              => $row4['Part Reference'],
                'Part Unit Label'                             => $row4['Part Unit Label'],
                'Part Units Per Package'                      => $row4['Part Units Per Package'],
                'Part Package Description'                    => $row4['Part Package Description'],
                'Part SKO Barcode'                            => $row4['Part SKO Barcode'],
                'Supplier Part Carton Barcode'                => $row4['Supplier Part Carton Barcode'],
                'Supplier Part Packages Per Carton'           => $row4['Supplier Part Packages Per Carton'],
                'Part Recommended Packages Per Selling Outer' => $row4['Part Recommended Packages Per Selling Outer'],
                'Supplier Part Status'                        => $row4['Supplier Part Status'],
                'Supplier Part On Demand'                     => $row4['Supplier Part On Demand'],
                'Supplier Part Minimum Carton Order'          => $row4['Supplier Part Minimum Carton Order'],
                'Supplier Part Average Delivery Days'         => $row4['Supplier Part Average Delivery Days'],
                'Supplier Part Carton CBM'                    => $row4['Supplier Part Carton CBM'],
                'Supplier Part Unit Cost'                     => $row4['Product Price'] / $row4['Product Units Per Case'] * .55,
                'Supplier Part Unit Extra Cost Percentage'    => $row4['Supplier Part Unit Extra Cost Percentage'],
                'Part Part Unit Price'                        => $row4['Part Unit Price'] * $exchange,
                'Part Part Unit RRP'                          => $row4['Part Unit RRP'] * $exchange,
                'Part Recommended Product Unit Name'          => $row4['Part Recommended Product Unit Name'],
                'Part Barcode'                                => $row4['Part Barcode Number'],
                'Part Part Unit Weight'                       => $row4['Part Unit Weight'],
                'Part Part Unit Dimensions'                   => get_dimensions($row4['Part Unit Dimensions']),
                'Part Part Package Weight'                    => $row4['Part Package Weight'],
                'Part Part Package Dimensions'                => get_dimensions($row4['Part Package Dimensions']),
                'Part Part Materials'                         => get_materials($row4['Part Materials']),
                'Part Part Origin Country Code'               => $row4['Part Origin Country Code'],
                'Part Part Tariff Code'                       => $row4['Part Tariff Code'],
                'Part Part Duty Rate'                         => $row4['Part Duty Rate'],
                'Part Part HTSUS Code'                        => $row4['Part HTSUS Code'],
                'Part Part UN Number'                         => $row4['Part UN Number'],
                'Part Part UN Class'                          => $row4['Part UN Class'],
                'Part Part Packing Group'                     => $row4['Part Packing Group'],
                'Part Part Proper Shipping Name'              => $row4['Part Proper Shipping Name'],
                'Part Part Hazard Identification Number'      => $row4['Part Hazard Identification Number'],
                'editor'                                      => $editor
            );


            $supplier_part = $supplier->create_supplier_part_record($supplier_part_data, 'Yes');

            if ($supplier_part) {
                $supplier->update_supplier_parts();

                $supplier_part->fast_update_json_field(
                    'Supplier Part Properties',
                    'master_link',
                    json_encode(
                        [
                            'account'    => 1,
                            'product_id' => $row4['Product ID']
                        ]
                    )
                );
                $product_properties['slaves'][$slave_id] = $supplier_part->id;


                $sql = sprintf('update dw.`Product Dimension` set `Product Properties`= ? where `Product ID`=?  ');
                $db->prepare($sql)->execute(
                    array(
                        json_encode($product_properties),
                        $row4['Product ID']
                    )
                );


                print 'New -> '.$supplier_part_data['Supplier Part Reference']."\n";
            } else {
                if ($supplier->error_code == 'duplicate_supplier_part_reference') {
                    $sql = 'SELECT `Supplier Part Key`  FROM `Supplier Part Dimension` WHERE `Supplier Part Reference`=? AND `Supplier Part Supplier Key`=?  ';


                    $stmt5 = $db->prepare($sql);
                    $stmt5->execute(
                        array(
                            $supplier_part_data['Supplier Part Reference'],
                            $supplier->id
                        )
                    );
                    while ($row5 = $stmt5->fetch()) {
                        $product_properties['slaves'][$slave_id] = $row5['Supplier Part Key'];


                        $sql = sprintf('update dw.`Product Dimension` set `Product Properties`= ? where `Product ID`=?  ');
                        $db->prepare($sql)->execute(
                            array(
                                json_encode($product_properties),
                                $row4['Product ID']
                            )
                        );
                    }
                } else {
                    print 'Error creating '.$supplier_part_data['Supplier Part Reference'].' '.$supplier->msg." ".$supplier->error_code."  \n";
                }
            }
        }


        if (isset($supplier_part) and is_object($supplier_part)  ) {
            $sql = sprintf('select * from dw.`Image Dimension` left join dw.`Image Subject Bridge` on (`Image Key`=`Image Subject Image Key`)  where `Image Subject Object`="Part" and  `Image Subject Object Key`=?  ');


            $stmt3 = $db->prepare($sql);
            $stmt3->execute(
                array($row4['Supplier Part Part SKU'])
            );
            while ($row3 = $stmt3->fetch()) {
                $file = preg_replace('/^img/', '/data/img/AW', $row3['Image Path']);

                $tmp_file = 'server_files/tmp/_image_'.$row3['Image Key'].'.'.$row3['Image File Format'];


                copy($file, $tmp_file);


                $image_data                  = array(
                    'Upload Data'                      => array(
                        'tmp_name' => $tmp_file,
                        'type'     => $row3['Image File Format']
                    ),
                    'Image Filename'                   => $row3['Image Filename'],
                    'Image Subject Object Image Scope' => 'Default',


                );
                $supplier_part->part->editor = $editor;


                $image = $supplier_part->part->add_image($image_data, 'no_history');
                /*
                if ($row4['Part Status'] == 'Not In Use') {
                    $supplier_part->part->update_status('Not In Use', 'no_history');

                }

                if ($row4['Part Status'] == 'Discontinuing') {
                    $supplier_part->part->update_status('Discontinuing', 'no_history');

                }

                */
            }
        }
    }

}


function get_materials($data) {
    if ($data == '') {
        return '';
    }

    $materials_data = json_decode($data, true);

    $materials = '';
    foreach ($materials_data as $material_data) {

        if ($material_data['may_contain'] == 'Yes') {
            $may_contain_tag = '±';
        } else {
            $may_contain_tag = '';
        }

        $materials .= sprintf(
            ', %s%s', $may_contain_tag, $material_data['name']
        );

        if ($material_data['ratio'] > 0) {
            $materials .= sprintf(
                ' (%s)', percentage($material_data['ratio'], 1)
            );
        }
    }

    $materials = preg_replace('/^\, /', '', $materials);


    return $materials;

}

function get_dimensions($data) {

    if ($data == '') {
        return '';
    }

    $data = json_decode($data, true);


    include_once 'utils/units_functions.php';
    switch ($data['type']) {
        case 'Rectangular':
            $dimensions = number(
                    convert_units(
                        $data['l'], 'm', $data['units']
                    )
                ).'x'.number(
                    convert_units(
                        $data['w'], 'm', $data['units']
                    )
                ).'x'.number(
                    convert_units(
                        $data['h'], 'm', $data['units']
                    )
                ).' ('.$data['units'].')';
            break;
        case 'Sheet':
            $dimensions = number(
                    convert_units(
                        $data['l'], 'm', $data['units']
                    )
                ).'x'.number(
                    convert_units(
                        $data['w'], 'm', $data['units']
                    )
                ).' ('.$data['units'].')';
            break;
        case 'Cilinder':
            $dimensions = number(
                    convert_units(
                        $data['h'], 'm', $data['units']
                    )
                ).'x'.number(
                    convert_units(
                        $data['w'], 'm', $data['units']
                    )
                ).' ('.$data['units'].')';
            break;
        case 'Sphere':
            $dimensions = 'D:'.number(
                    convert_units(
                        $data['h'], 'm', $data['units']
                    )
                ).' ('.$data['units'].')';

            break;

        case 'String':
            $dimensions = 'L.'.number(
                    convert_units(
                        $data['l'], 'm', $data['units']
                    )
                ).' ('.$data['units'].')';

            break;

        default:
            $dimensions = '';
    }

    return $dimensions;
}