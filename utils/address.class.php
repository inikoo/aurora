<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  24 November 2019  17:11::03  +0100, Mijas Costa, Spain
 Copyright (c) 2019, Inikoo

 Version 3.1
*/

use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;

/**
 * Class address_fields
 */
class address_fields {

    private $country_code;
    private $locale;
    private $address_Format;

    function __construct($country_code, $locale = 'en_GB') {

        $this->country_code = $country_code;
        $this->locale       = $locale;


    }

    /**
     * @return array
     */
    public function get_address_format() {

        $addressFormatRepository = new AddressFormatRepository();
        $this->address_Format    = $addressFormatRepository->get($this->country_code);

        $address_format = $this->address_Format->getFormat();
        $address_format = preg_replace('/[,\-/]/', ' ', $address_format);
        $address_format = trim(preg_replace('/%|givenName|familyName|organization|ÅLAND|GIBRALTAR|GUERNSEY|JERSEY|SINGAPORE /', '', $address_format));
        $address_format = trim($address_format);
        $address_format = preg_replace('/ /', '_', $address_format);
        $address_format = preg_replace('/_+/', '_', $address_format);
        $address_format = explode("\n", $address_format);

        return array_filter($address_format);

    }

}
