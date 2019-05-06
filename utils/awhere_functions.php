<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 December 2015 at 16:57:07 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/




function extract_product_groups($str, $store_key = 0, $q_prod_name = 'OTF.`Product Code` like', $q_prod_id = 'OTF.`Product ID`', $q_group_id = 'OTF.`Product Family Key` in',
    $q_department_id = 'OTF.`Product Department Key`  in'
) {


    if ($str == '') {
        return '';
    }
    $where       = '';
    $where_g     = '';
    $where_d     = '';
    $use_product = false;


    $department_names = array();
    $department_ids   = array();

    if (preg_match_all('/d\([a-z0-9\-\,]*\)/i', $str, $matches)) {


        foreach ($matches[0] as $match) {

            $_groups = preg_replace(
                '/\)$/i', '', preg_replace('/^d\(/i', '', $match)
            );
            $_groups = preg_split('/\s*,\s*/i', $_groups);

            foreach ($_groups as $group) {
                //$use_product=true;
                if (is_numeric($group)) {
                    $department_ids[$group] = $group;
                } else {
                    $department_names[$group] = prepare_mysql($group);

                }

            }
        }
        $str = preg_replace('/d\([a-z0-9\-\,]*\)/i', '', $str);
    }
    if (count($department_names) > 0) {
        if ($store_key and is_numeric($store_key)) {
            $store_where = ' and `Product Department Store Key`='.$store_key;
        } else {
            $store_where = '';
        }
        $sql = sprintf(
            "SELECT `Product Department Key` FROM `Product Department Dimension` WHERE `Product Department Code` IN (%s) %s ", join(',', $department_names), $store_where
        );
        $res = mysql_query($sql);

        while ($row = mysql_fetch_assoc($res)) {
            $department_ids[$row['Product Department Key']]
                = $row['Product Department Key'];
        }

    }

    if (count($department_ids) > 0) {
        $where_d = 'or '.$q_department_id.' ('.join(',', $department_ids).') ';
        //   $use_product=true;
    }


    $family_names = array();
    $family_ids   = array();

    if (preg_match_all('/f\([a-z0-9\-\,]*\)/i', $str, $matches)) {

        foreach ($matches[0] as $match) {

            $_groups = preg_replace(
                '/\)$/i', '', preg_replace('/^f\(/i', '', $match)
            );
            $_groups = preg_split('/\s*,\s*/i', $_groups);

            foreach ($_groups as $group) {
                //$use_product=true;
                if (is_numeric($group)) {
                    $family_ids[$group] = $group;
                } else {
                    $family_names[$group] = prepare_mysql($group);

                }

            }
        }
        $str = preg_replace('/f\([a-z0-9\-\,]*\)/i', '', $str);
    }


    if (count($family_names) > 0) {
        if ($store_key and is_numeric($store_key)) {
            $store_where = ' and `Product Family Store Key`='.$store_key;
        } else {
            $store_where = '';
        }
        $sql = sprintf(
            "SELECT `Product Family Key` FROM `Product Family Dimension` WHERE `Product Family Code` IN (%s) %s ", join(',', $family_names), $store_where
        );
        $res = mysql_query($sql);

        while ($row = mysql_fetch_assoc($res)) {
            $family_ids[$row['Product Family Key']]
                = $row['Product Family Key'];
        }

    }

    if (count($family_ids) > 0) {
        $where_g = 'or '.$q_group_id.' ('.join(',', $family_ids).') ';
        // $use_product=true;
    }
    //print_r($family_ids);


    $products = preg_split('/\s*,\s*/i', $str);

    $where_p = '';
    foreach ($products as $product) {
        if ($product != '') {
            $product = addslashes($product);
            if (is_numeric($product)) {
                $where_p .= " or $q_prod_id  '$product'";
            } else {
                $where_p .= " or $q_prod_name  '$product'";
            }
        }
    }


    $where = preg_replace('/^\s*or\s*/i', '', $where_d.$where_g.$where_p);


    return array(
        '('.$where.')',
        $use_product
    );

}


?>
