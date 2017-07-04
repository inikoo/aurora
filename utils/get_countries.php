<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 July 2017 at 13:26:56 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


use CommerceGuys\Intl\Country\CountryRepository;

function get_countries($locale='en_FB'){




$countryRepository = new CountryRepository;

$countries_data = array();


foreach ($countryRepository->getAll($locale) as $_country) {


    $countries_data[$_country->getCountryCode()] = array(
        '2alpha' => $_country->getCountryCode(),
        'name'   => $_country->getName(),
    );
}

return $countries_data;

}

?>
