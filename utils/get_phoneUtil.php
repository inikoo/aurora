<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 January 2016 at 11:18:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

function get_phoneUtil() {

    include_once 'external_libs/libphonenumber/MetadataSourceInterface.php';
    include_once 'external_libs/libphonenumber/PhoneNumberUtil.php';
    include_once 'external_libs/libphonenumber/CountryCodeToRegionCodeMap.php';
    include_once 'external_libs/libphonenumber/MetadataLoaderInterface.php';
    include_once 'external_libs/libphonenumber/MultiFileMetadataSourceImpl.php';
    include_once 'external_libs/libphonenumber/MetadataLoaderInterface.php';
    include_once 'external_libs/libphonenumber/DefaultMetadataLoader.php';
    include_once 'external_libs/libphonenumber/PhoneNumber.php';
    include_once 'external_libs/libphonenumber/Matcher.php';
    include_once 'external_libs/libphonenumber/PhoneMetadata.php';
    include_once 'external_libs/libphonenumber/PhoneNumberDesc.php';
    include_once 'external_libs/libphonenumber/NumberFormat.php';
    include_once 'external_libs/libphonenumber/CountryCodeSource.php';
    include_once 'external_libs/libphonenumber/ValidationResult.php';
    include_once 'external_libs/libphonenumber/PhoneNumberFormat.php';
    include_once 'external_libs/libphonenumber/NumberParseException.php';


    return \libphonenumber\PhoneNumberUtil::getInstance();

}

?>