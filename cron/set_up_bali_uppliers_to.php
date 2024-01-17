<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 01-09-2019 14:01:13 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
include_once 'class.Agent.php';
include_once 'class.SupplierPart.php';
include_once 'class.Part.php';
include_once 'class.Image.php';


$editor = array(
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Script)',
    'Author Alias' => 'System (Script)',
    'v'            => 3


);




$account=get_object('Account',1);
$account->editor=$editor;
$suppliers_to_copy='76,18,142,262,321,264,260,237,16';

$sql = sprintf('select * from  dw.`Supplier Dimension` left join dw.`Agent Supplier Bridge`  on (`Agent Supplier Supplier Key`=`Supplier Key`)  where `Agent Supplier Agent Key`=6');


$stmt2 = $db->prepare($sql);
$stmt2->execute(

);
while ($row2 = $stmt2->fetch()) {



    $editor['Date'] = gmdate('Y-m-d H:i:s');

    $supplier_data = array(
        'Supplier Products Origin Country Code' => $row2['Supplier Products Origin Country Code'],
        'Supplier Code'                         => $row2['Supplier Code'],
        'Supplier Name'                         => $row2['Supplier Name'],
        'Supplier Main Plain Email'             => $row2['Supplier Main Plain Email'],
        'Supplier Company Name'                 => $row2['Supplier Company Name'],
        'Supplier Main Contact Name'            => $row2['Supplier Main Contact Name'],
        'Supplier Default Incoterm'             => $row2['Supplier Default Incoterm'],
        'Supplier Default Currency Code'        => $row2['Supplier Default Currency Code'],

        'Supplier Contact Address addressLine1'       => $row2['Supplier Contact Address Line 1'],
        'Supplier Contact Address addressLine2'       => $row2['Supplier Contact Address Line 2'],
        'Supplier Contact Address sortingCode'        => $row2['Supplier Contact Address Sorting Code'],
        'Supplier Contact Address postalCode'         => $row2['Supplier Contact Address Postal Code'],
        'Supplier Contact Address dependentLocality'  => $row2['Supplier Contact Address Dependent Locality'],
        'Supplier Contact Address locality'           => $row2['Supplier Contact Address Locality'],
        'Supplier Contact Address administrativeArea' => $row2['Supplier Contact Address Administrative Area'],
        'Supplier Contact Address country'            => $row2['Supplier Contact Address Country 2 Alpha Code'],
        'Supplier Order Public ID Format'             => $row2['Supplier Code'].'%05d',

        'editor' => $editor

    );


    $supplier = $account->create_supplier($supplier_data);

    $supplier->editor=$editor;

    print "    ".$supplier->get('Code')."\n";


    if ($row2['Supplier Main Plain Telephone'] != '') {
        $supplier->update(array('Supplier Main Plain Telephone' => $row2['Supplier Main Plain Telephone']), 'no_history');
    }

    if ($row2['Supplier Main Plain Mobile'] != '') {
        $supplier->update(array('Supplier Main Plain Mobile' => $row2['Supplier Main Plain Mobile']), 'no_history');
    }



    $sql = sprintf(
        'select * from dw.`Supplier Part Dimension` left join dw.`Part Dimension` on (`Part SKU`=`Supplier Part Part SKU`) left join dw.`Category Dimension` on (`Category Key`=`Part Family Category Key`) where  `Part Status` in ("In Use","In Process") and  `Supplier Part Supplier Key`=?   '
    );


    $stmt4 = $db->prepare($sql);
    $stmt4->execute(
        array($row2['Supplier Key'])
    );
    while ($row4 = $stmt4->fetch()) {
        $editor['Date'] = gmdate('Y-m-d H:i:s');

        $supplier_part_data = array(
            'Supplier Part Reference'                     => $row4['Supplier Part Reference'],
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
            'Supplier Part Unit Cost'                     => $row4['Supplier Part Unit Cost'],
            'Supplier Part Unit Extra Cost Percentage'    => $row4['Supplier Part Unit Extra Cost Percentage'],
            'Part Part Unit Price'                        => $row4['Part Unit Price']*19735,
            'Part Part Unit RRP'                          => $row4['Part Unit RRP']*19735,
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

        //print_r($supplier_part_data);


        $supplier_part = $supplier->create_supplier_part_record($supplier_part_data, 'No');


        if (is_object($supplier_part) and $supplier->new_object  ) {

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


                if ($row4['Part Status'] == 'Not In Use') {
                    $supplier_part->part->update_status('Not In Use', 'no_history');

                }

                if ($row4['Part Status'] == 'Discontinuing') {
                    $supplier_part->part->update_status('Discontinuing', 'no_history');

                }


            }

            $supplier->update_supplier_parts();

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