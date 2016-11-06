<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 January 2016 at 17:00:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

function preferred_countries($country_code) {
    include 'conf/preferred_countries.php';
    if (isset($preferred_countries_data[$country_code])) {
        $preferred_countries = $preferred_countries_data[$country_code];
        array_unshift($preferred_countries, $country_code);

        return $preferred_countries;
    } else {
        return array($country_code);
    }
}


?>
