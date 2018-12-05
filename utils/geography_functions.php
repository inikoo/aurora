<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 25 May 2014 08:32:00 CEST, Malaga Spain

 Version 2.0
*/

function translate_country_name($country) {

    switch ($country) {
        case 'Spain':
            return _('Spain');
            break;
        case 'Germany':
            return _('Germany');
            break;
        case 'United Kingdom':
            return _('United Kingdom');
            break;
        case 'France':
            return _('France');
            break;
        case 'Italy':
            return _('Italy');
            break;
        case 'Poland':
            return _('Poland');
            break;
        case 'Portugal':
            return _('Portugal');
            break;
        case 'Unknown':
            return _('Unknown');
            break;
        default:
            return $country;
    }


}


function get_countries_EC_Fiscal_VAT_area($db) {
    $countries_EC_Fiscal_VAT_area = array();
    $sql                          = sprintf(
        "SELECT `Country 2 Alpha Code`  FROM kbase.`Country Dimension` WHERE `EC Fiscal VAT Area`='Yes'"
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $countries_EC_Fiscal_VAT_area[] = $row['Country 2 Alpha Code'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    return $countries_EC_Fiscal_VAT_area;
}


function gbr_pretty_format_post_code($postcode) {


    if (preg_match('/^([A-Za-z][A-Ha-hJ-Yj-y]?[0-9][A-Za-z0-9]? ?[0-9][A-Za-z]{2}|[Gg][Ii][Rr] ?0[Aa]{2})$/', $postcode)) {
        $postcode = preg_replace('/\s/', '', strtoupper($postcode));
        $postcode = preg_replace('/\-/', '', strtoupper($postcode));
        $postcode = substr_replace($postcode,' ',-3,0);


    }

    return $postcode;

}


?>
