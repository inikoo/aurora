<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 01-09-2019 14:01:13 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';
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


$counter = 0;

$sql  = sprintf('select * from sk.`Supplier Dimension` where `Supplier Type`="Free"  ');
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {


    $editor['Date'] = gmdate('Y-m-d H:i:s');
    $supplier_data  = array(
        'Supplier Products Origin Country Code' => $row['Supplier Products Origin Country Code'],
        'Supplier Code'                         => $row['Supplier Code'],
        'Supplier Name'                         => $row['Supplier Name'],
        'Supplier Main Plain Email'             => $row['Supplier Main Plain Email'],
        'Supplier Company Name'                 => $row['Supplier Company Name'],
        'Supplier Main Contact Name'            => $row['Supplier Main Contact Name'],
        'Supplier Default Incoterm'             => $row['Supplier Default Incoterm'],
        'Supplier Default Currency Code'        => $row['Supplier Default Currency Code'],

        'Supplier Contact Address addressLine1'       => $row['Supplier Contact Address Line 1'],
        'Supplier Contact Address addressLine2'       => $row['Supplier Contact Address Line 2'],
        'Supplier Contact Address sortingCode'        => $row['Supplier Contact Address Sorting Code'],
        'Supplier Contact Address postalCode'         => $row['Supplier Contact Address Postal Code'],
        'Supplier Contact Address dependentLocality'  => $row['Supplier Contact Address Dependent Locality'],
        'Supplier Contact Address locality'           => $row['Supplier Contact Address Locality'],
        'Supplier Contact Address administrativeArea' => $row['Supplier Contact Address Administrative Area'],
        'Supplier Contact Address country'            => $row['Supplier Contact Address Country 2 Alpha Code'],
        'editor'                                      => $editor

    );

    $supplier = $account->create_supplier($supplier_data);

    $supplier_key = $supplier->id;

    // print $supplier->get('Code')."\n";

    if ($row['Supplier Main Plain Telephone'] != '') {
        $supplier->update(array('Supplier Main Plain Telephone' => $row['Supplier Main Plain Telephone']), 'no_history');
    }


    $sql = sprintf(
        'select * from sk.`Supplier Part Dimension` left join sk.`Part Dimension` on (`Part SKU`=`Supplier Part Part SKU`) left join sk.`Category Dimension` on (`Category Key`=`Part Family Category Key`) where   `Supplier Part Supplier Key`=?  order by `Part SKU`  '


    );


    $stmt2 = $db->prepare($sql);
    $stmt2->execute(
        array($row['Supplier Key'])
    );
    while ($row2 = $stmt2->fetch()) {


        $editor['Date'] = gmdate('Y-m-d H:i:s');

        $supplier = get_object('Supplier', $supplier_key);

        $supplier->editor = $editor;

        $supplier_part_data = array(
            'Supplier Part Reference'                     => $row2['Supplier Part Reference'],
            'Supplier Part Description'                   => $row2['Supplier Part Description'],
            'Part Family Category Code'                   => $row2['Category Code'],
            'Part Reference'                              => $row2['Part Reference'],
            'Part Unit Label'                             => $row2['Part Unit Label'],
            'Part Units Per Package'                      => $row2['Part Units Per Package'],
            'Part Package Description'                    => $row2['Part Package Description'],
            'Part SKO Barcode'                            => $row2['Part SKO Barcode'],
            'Supplier Part Carton Barcode'                => $row2['Supplier Part Carton Barcode'],
            'Supplier Part Packages Per Carton'           => $row2['Supplier Part Packages Per Carton'],
            'Part Recommended Packages Per Selling Outer' => $row2['Part Recommended Packages Per Selling Outer'],
            'Supplier Part Status'                        => $row2['Supplier Part Status'],
            'Supplier Part On Demand'                     => $row2['Supplier Part On Demand'],
            'Supplier Part Minimum Carton Order'          => $row2['Supplier Part Minimum Carton Order'],
            'Supplier Part Average Delivery Days'         => $row2['Supplier Part Average Delivery Days'],
            'Supplier Part Carton CBM'                    => $row2['Supplier Part Carton CBM'],
            'Supplier Part Unit Cost'                     => $row2['Supplier Part Unit Cost'],
            'Supplier Part Unit Extra Cost Percentage'    => $row2['Supplier Part Unit Extra Cost Percentage'],
            'Part Part Unit Price'                        => $row2['Part Unit Price'],
            'Part Part Unit RRP'                          => $row2['Part Unit RRP'],
            'Part Recommended Product Unit Name'          => $row2['Part Recommended Product Unit Name'],
            'Part Barcode'                                => $row2['Part Barcode Number'],
            'Part Part Unit Weight'                       => $row2['Part Unit Weight'],
            'Part Part Unit Dimensions'                   => get_dimensions($row2['Part Unit Dimensions']),
            'Part Part Package Weight'                    => $row2['Part Package Weight'],
            'Part Part Package Dimensions'                => get_dimensions($row2['Part Package Dimensions']),
            'Part Part Materials'                         => get_materials($row2['Part Materials']),
            'Part Part Origin Country Code'               => $row2['Part Origin Country Code'],
            'Part Part Tariff Code'                       => $row2['Part Tariff Code'],
            'Part Part Duty Rate'                         => $row2['Part Duty Rate'],
            'Part Part HTSUS Code'                        => $row2['Part HTSUS Code'],
            'Part Part UN Number'                         => $row2['Part UN Number'],
            'Part Part UN Class'                          => $row2['Part UN Class'],
            'Part Part Packing Group'                     => $row2['Part Packing Group'],
            'Part Part Proper Shipping Name'              => $row2['Part Proper Shipping Name'],
            'Part Part Hazard Identification Number'      => $row2['Part Hazard Identification Number'],
            'editor'                                      => $editor
        );


        $supplier_part = $supplier->create_supplier_part_record($supplier_part_data, 'Yes');


        //  print_r($supplier_part);


        if (is_object($supplier_part)) {

            print $supplier_part->get('Supplier Part Reference')."\n";

            $sql = sprintf('select * from sk.`Image Dimension` left join sk.`Image Subject Bridge` on (`Image Key`=`Image Subject Image Key`)  where `Image Subject Object`="Part" and  `Image Subject Object Key`=?  ');


            $stmt3 = $db->prepare($sql);
            $stmt3->execute(
                array($row2['Supplier Part Part SKU'])
            );
            while ($row3 = $stmt3->fetch()) {
                $editor['Date'] = gmdate('Y-m-d H:i:s');

                $tmp_file = '/tmp/_image_'.$row3['Image Key'].'.'.$row3['Image File Format'];

                file_put_contents($tmp_file, $row3['Image Data']);



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

                unlink($tmp_file);


            }

            if ($row2['Part Status'] == 'Not In Use') {
                $supplier_part->part->update_status('Not In Use', 'no_history');

            }

            if ($row2['Part Status'] == 'Discontinuing') {
                $supplier_part->part->update_status('Discontinuing', 'no_history');

            }

        }


    }


}


$sql  = sprintf('select * from sk.`Agent Dimension` ');
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {
    $editor['Date'] = gmdate('Y-m-d H:i:s');
    $agent_data     = array(
        'Agent Products Origin Country Code' => $row['Agent Products Origin Country Code'],
        'Agent Code'                         => $row['Agent Code'],
        'Agent Name'                         => $row['Agent Name'],
        'Agent Main Plain Email'             => $row['Agent Main Plain Email'],
        'Agent Company Name'                 => $row['Agent Company Name'],
        'Agent Main Contact Name'            => $row['Agent Main Contact Name'],
        'Agent Default Incoterm'             => $row['Agent Default Incoterm'],
        'Agent Default Currency Code'        => $row['Agent Default Currency Code'],

        'Agent Contact Address addressLine1'       => $row['Agent Contact Address Line 1'],
        'Agent Contact Address addressLine2'       => $row['Agent Contact Address Line 2'],
        'Agent Contact Address sortingCode'        => $row['Agent Contact Address Sorting Code'],
        'Agent Contact Address postalCode'         => $row['Agent Contact Address Postal Code'],
        'Agent Contact Address dependentLocality'  => $row['Agent Contact Address Dependent Locality'],
        'Agent Contact Address locality'           => $row['Agent Contact Address Locality'],
        'Agent Contact Address administrativeArea' => $row['Agent Contact Address Administrative Area'],
        'Agent Contact Address country'            => $row['Agent Contact Address Country 2 Alpha Code'],
        'editor'                                   => $editor

    );

    $agent = $account->create_agent($agent_data);
    if ($row['Agent Main Plain Telephone'] != '') {
        $agent->update(array('Agent Main Plain Telephone' => $row['Agent Main Plain Telephone']), 'no_history');
    }


    $sql = sprintf('select * from sk.`Supplier Dimension` left join sk.`Agent Supplier Bridge` on (`Agent Supplier Supplier Key`=`Supplier Key`) where `Agent Supplier Agent Key`=? ');


    $stmt2 = $db->prepare($sql);
    $stmt2->execute(
        array($row['Agent Key'])
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
            'editor'                                      => $editor

        );


        $supplier = $agent->create_supplier($supplier_data);


        if ($row2['Supplier Main Plain Telephone'] != '') {
            $supplier->update(array('Supplier Main Plain Telephone' => $row2['Supplier Main Plain Telephone']), 'no_history');
        }

        if ($row2['Supplier Main Plain Mobile'] != '') {
            $supplier->update(array('Supplier Main Plain Mobile' => $row2['Supplier Main Plain Mobile']), 'no_history');
        }


        $sql = sprintf('select * from sk.`Supplier Part Dimension` left join sk.`Part Dimension` on (`Part SKU`=`Supplier Part Part SKU`) left join sk.`Category Dimension` on (`Category Key`=`Part Family Category Key`) where  `Supplier Part Supplier Key`=?   ');


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
                'Part Part Unit Price'                        => $row4['Part Unit Price'],
                'Part Part Unit RRP'                          => $row4['Part Unit RRP'],
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


            if (is_object($supplier_part)) {

                $sql = sprintf('select * from sk.`Image Dimension` left join sk.`Image Subject Bridge` on (`Image Key`=`Image Subject Image Key`)  where `Image Subject Object`="Part" and  `Image Subject Object Key`=?  ');


                $stmt3 = $db->prepare($sql);
                $stmt3->execute(
                    array($row4['Supplier Part Part SKU'])
                );
                while ($row3 = $stmt3->fetch()) {
                    $editor['Date'] = gmdate('Y-m-d H:i:s');

                    $tmp_file = '/tmp/_image_'.$row3['Image Key'].'.'.$row3['Image File Format'];

                    file_put_contents($tmp_file, $row3['Image Data']);


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

                    unlink($tmp_file);

                    if ($row4['Part Status'] == 'Not In Use') {
                        $supplier_part->part->update_status('Not In Use', 'no_history');

                    }

                    if ($row4['Part Status'] == 'Discontinuing') {
                        $supplier_part->part->update_status('Discontinuing', 'no_history');

                    }


                }

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