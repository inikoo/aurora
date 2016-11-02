<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 24 February 2016 at 16:00:34 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function parse_materials($value, $editor = '') {

    if ($editor == '') {
        $editor = array(
            'Author Name'  => '',
            'Author Alias' => '',
            'Author Type'  => '',
            'Author Key'   => '',
            'User Key'     => 0,
            'Date'         => gmdate('Y-m-d H:i:s')
        );
    }

    include_once 'class.Material.php';


    //if($value==$this->data['Part Unit Materials'])
    //   return;

    $materials = array();

    $_materials = preg_split('/\s*,\s*/', $value);
    // print_r($_materials);
    $sum_ratios = 0;

    foreach ($_materials as $material) {
        $material = _trim($material);
        $material = preg_replace('/\s*\.$/', '', $material);
        $ratio    = 0;
        if (preg_match('/\s*\(.+\s*\%\s*\)$/', $material, $match)) {
            $_percentage = $match[0];
            $_percentage = preg_replace('/^\s*\(/', '', $_percentage);
            $_percentage = preg_replace('/s*\%\s*\)$/', '', $_percentage);
            $_percentage = floatval($_percentage);
            if (is_float($_percentage) and $_percentage > 0) {
                $material = preg_replace('/\s*\(.+\s*\%\s*\)$/', '', $material);
                $ratio    = $_percentage / 100;

            } else {
                $ratio = 0;
            }

            if ($material != '') {

                $sum_ratios += $ratio;
                if (array_key_exists(strtolower($material), $materials)) {
                    $materials[strtolower($material)]['ratio'] += $ratio;
                } else {
                    $materials[strtolower($material)]
                        = array(
                        'name'        => $material,
                        'ratio'       => $ratio,
                        'may_contain' => 'No',
                        'id'          => ''
                    );
                }
            }
        } else {
            if (preg_match('/^\s*\(\+\/\-.+\)$/', $material, $match)) {

                $material = preg_replace('/^\s*\(\+\/\-/', '', $material);
                $material = preg_replace('/\)$/', '', $material);
                $material = _trim($material);
                if ($material != '') {
                    $materials[strtolower($material)]
                        = array(
                        'name'        => $material,
                        'ratio'       => '',
                        'may_contain' => 'Yes',
                        'id'          => ''
                    );
                }
            } else {

                $materials[strtolower($material)] = array(
                    'name'        => $material,
                    'ratio'       => '',
                    'may_contain' => 'No',
                    'id'          => ''
                );

            }
        }


    }

    if ($sum_ratios > 1) {
        foreach ($materials as $key => $material) {
            $materials[$key]['ratio'] = $materials[$key]['ratio'] / $sum_ratios;
        }
    }


    foreach ($materials as $key => $_value) {
        $material_data = array(
            'Material Name' => $_value['name'],
            'editor'        => $editor
        );

        $material = new Material('find create', $material_data);

        //print_r($material_data);
        if ($material->id) {

            $materials[$key]['id'] = $material->id;
        }


    }
    $materials = array_values($materials);


    return $materials;

}


?>
