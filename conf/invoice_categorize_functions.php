<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:6 August 2018 at 13:21:57 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


function get_categorize_invoices_functions() {

    $categorize_invoices_functions                  = array();
    $categorize_invoices_functions['in_level_type'] = function ($data, $aux) {

        $aux = json_decode($aux, true);

        if (in_array($data["Invoice Customer Level Type"], $aux)) {
            return true;
        } else {
            return false;
        }
    };

    $categorize_invoices_functions['not_in_level_type'] = function ($data, $aux) {
        $aux = json_decode($aux, true);

        if (!in_array($data["Invoice Customer Level Type"], $aux)) {
            return true;
        } else {
            return false;
        }
    };
    $categorize_invoices_functions['in_counties']       = function ($data, $aux) {
        $aux = json_decode($aux, true);

        if (in_array($data["Invoice Customer Level Type"], $aux)) {
            return true;
        } else {
            return false;
        }
    };
    $categorize_invoices_functions['not_in_counties']   = function ($data, $aux) {
        $aux = json_decode($aux, true);

        if (!in_array($data["Invoice Customer Level Type"], $aux)) {
            return true;
        } else {
            return false;
        }
    };


    $categorize_invoices_functions['country']        = function ($data, $aux) {


        if ($data["Invoice Address Country 2 Alpha Code"] == $aux) {
            return true;
        } else {
            return false;
        }
    };
    $categorize_invoices_functions['not_in_country'] = function ($data, $aux) {


        if ($data["Invoice Address Country 2 Alpha Code"] != $aux) {
            return true;
        } else {
            return false;
        }
    };

    $categorize_invoices_functions['store'] = function ($data, $aux) {


        if ($data["Invoice Store Key"] == $aux) {
            return true;
        } else {
            return false;
        }
    };

    $categorize_invoices_functions['store_type'] = function ($data, $aux) {

        $aux = json_decode($aux, true);


        if (in_array($data["Store Type"], $aux)) {
            return true;
        } else {
            return false;
        }
    };

    $categorize_invoices_functions['stores']        = function ($data, $aux) {

        $aux = json_decode($aux, true);

        if (in_array($data["Invoice Store Key"], $aux)) {
            return true;
        } else {
            return false;
        }
    };
    $categorize_invoices_functions['not_in_stores'] = function ($data, $aux) {

        $aux = json_decode($aux, true);


        if (!in_array($data["Invoice Store Key"], $aux)) {
            return true;
        } else {
            return false;
        }
    };

    $categorize_invoices_functions['poll_options'] = function ($data, $aux, $db) {


        $found = false;

        $sql  = "select count(*) as num from `Customer Poll Fact`  where  `Customer Poll Customer Key`=?   and `Customer Poll Query Option Key` in (?)  ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $data["Invoice Customer Key"],
                preg_replace('[^0-9\s\,]', '', $aux)
            )
        );
        if ($row = $stmt->fetch()) {
            if ($row['num'] > 0) {
                $found = true;

            }
        }

        return $found;

    };

    $categorize_invoices_functions['external_invoicer'] = function ($data, $aux) {


        if ($data["Invoice External Invoicer Key"] == $aux) {
            return true;
        } else {
            return false;
        }
    };

    $categorize_invoices_functions['other'] = function ($data, $aux) {

        return true;

    };

    return $categorize_invoices_functions;
}

