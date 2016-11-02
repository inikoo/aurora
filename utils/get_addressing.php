<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 January 2016 at 11:18:00 GMT+8
 Copyright (c) 2016, Inikoo

 Version 3

*/


use CommerceGuys\Addressing\Formatter\DefaultFormatter;
use CommerceGuys\Addressing\Formatter\PostalLabelFormatter;
use CommerceGuys\Addressing\Model\Address;
use CommerceGuys\Addressing\Repository\AddressFormatRepository;
use CommerceGuys\Addressing\Repository\CountryRepository;
use CommerceGuys\Addressing\Repository\SubdivisionRepository;


function get_address_format($country_code) {


    require_once 'external_libs/CommerceGuys/Enum/AbstractEnum.php';


    require_once 'external_libs/CommerceGuys/Addressing/Model/FormatStringTrait.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/AddressFormatInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/AddressFormatEntityInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/AddressFormat.php';
    require_once 'external_libs/CommerceGuys/Addressing/Repository/AddressFormatRepositoryInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Repository/DefinitionTranslatorTrait.php';

    require_once 'external_libs/CommerceGuys/Addressing/Repository/AddressFormatRepository.php';

    require_once 'external_libs/CommerceGuys/Addressing/Enum/AddressField.php';


    $AddressFormatRepository_definitionPath
                             = 'external_libs/CommerceGuys/Addressing/resources/address_format/';
    $addressFormatRepository = new AddressFormatRepository(
        $AddressFormatRepository_definitionPath
    );


    return $addressFormatRepository->get($country_code, 'es');
}


function get_address_subdivisions($country_code, $locale = null) {


    require_once 'external_libs/CommerceGuys/Enum/AbstractEnum.php';
    require_once 'external_libs/CommerceGuys/Collections/Collection.php';
    require_once 'external_libs/CommerceGuys/Collections/Selectable.php';
    require_once 'external_libs/CommerceGuys/Collections/AbstractLazyCollection.php';
    require_once 'external_libs/CommerceGuys/Collections/ArrayCollection.php';
    require_once 'external_libs/CommerceGuys/Addressing/Collection/LazySubdivisionCollection.php';
    require_once 'external_libs/CommerceGuys/Addressing/Enum/PatternType.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/SubdivisionInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/SubdivisionEntityInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/FormatStringTrait.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/AddressFormatInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/AddressFormatEntityInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/Subdivision.php';
    require_once 'external_libs/CommerceGuys/Addressing/Repository/AddressFormatRepositoryInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Repository/DefinitionTranslatorTrait.php';
    require_once 'external_libs/CommerceGuys/Addressing/Repository/SubdivisionRepositoryInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Repository/SubdivisionRepository.php';

    $subdivisionRepository_definitionPath
                           = 'external_libs/CommerceGuys/Addressing/resources/subdivision/';
    $subdivisionRepository = new SubdivisionRepository(
        $subdivisionRepository_definitionPath
    );

    return $subdivisionRepository->getAll($country_code, 0, $locale);

}


function get_address_formatter($origin_country = null, $locale = null) {

    //require_once 'external_libs/CommerceGuys/Enum/PatternType.php';
    require_once 'external_libs/CommerceGuys/Enum/AbstractEnum.php';

    require_once 'external_libs/CommerceGuys/Addressing/Enum/AddressField.php';

    require_once 'external_libs/CommerceGuys/Addressing/Repository/DefinitionTranslatorTrait.php';

    require_once 'external_libs/CommerceGuys/Addressing/Repository/AddressFormatRepositoryInterface.php';

    require_once 'external_libs/CommerceGuys/Addressing/Model/FormatStringTrait.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/AddressFormatInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/AddressFormatEntityInterface.php';

    require_once 'external_libs/CommerceGuys/Addressing/Model/AddressFormat.php';

    require_once 'external_libs/CommerceGuys/Addressing/Repository/AddressFormatRepository.php';
    require_once 'external_libs/CommerceGuys/Addressing/Repository/DefinitionTranslatorTrait.php';

    require_once 'external_libs/CommerceGuys/Enum/AbstractEnum.php';
    require_once 'external_libs/CommerceGuys/Collections/Collection.php';
    require_once 'external_libs/CommerceGuys/Collections/Selectable.php';
    require_once 'external_libs/CommerceGuys/Collections/AbstractLazyCollection.php';
    require_once 'external_libs/CommerceGuys/Collections/ArrayCollection.php';

    require_once 'external_libs/CommerceGuys/Addressing/Enum/PatternType.php';


    require_once 'external_libs/CommerceGuys/Addressing/Model/SubdivisionInterface.php';

    require_once 'external_libs/CommerceGuys/Addressing/Model/SubdivisionEntityInterface.php';

    require_once 'external_libs/CommerceGuys/Addressing/Repository/SubdivisionRepositoryInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Repository/SubdivisionRepository.php';
    require_once 'external_libs/CommerceGuys/Intl/Exception/ExceptionInterface.php';

    require_once 'external_libs/CommerceGuys/Intl/Exception/InvalidArgumentException.php';

    require_once 'external_libs/CommerceGuys/Intl/Exception/UnknownLocaleException.php';
    require_once 'external_libs/CommerceGuys/Intl/LocaleResolverTrait.php';

    require_once 'external_libs/CommerceGuys/Intl/Country/CountryRepositoryInterface.php';
    require_once 'external_libs/CommerceGuys/Intl/Country/CountryRepository.php';

    require_once 'external_libs/CommerceGuys/Addressing/Repository/CountryRepositoryInterface.php';


    require_once 'external_libs/CommerceGuys/Addressing/Repository/CountryRepositoryInterface.php';

    require_once 'external_libs/CommerceGuys/Addressing/Repository/CountryRepository.php';


    require_once 'external_libs/CommerceGuys/Addressing/Model/Subdivision.php';


    require_once 'external_libs/CommerceGuys/Addressing/Formatter/FormatterInterface.php';

    require_once 'external_libs/CommerceGuys/Addressing/Formatter/DefaultFormatter.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/AddressInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/ImmutableAddressInterface.php';
    require_once 'external_libs/CommerceGuys/Addressing/Model/Address.php';

    require_once 'external_libs/CommerceGuys/Addressing/Formatter/PostalLabelFormatterInterface.php';

    require_once 'external_libs/CommerceGuys/Addressing/Formatter/PostalLabelFormatter.php';


    $AddressFormatRepository_definitionPath
                             = 'external_libs/CommerceGuys/Addressing/resources/address_format/';
    $addressFormatRepository = new AddressFormatRepository(
        $AddressFormatRepository_definitionPath
    );

    $countryRepository_definitionPath
        = 'external_libs/CommerceGuys/Intl/resources/country/';

    $countryRepository     = new CountryRepository(
        $countryRepository_definitionPath
    );
    $subdivisionRepository_definitionPath
                           = 'external_libs/CommerceGuys/Addressing/resources/subdivision/';
    $subdivisionRepository = new SubdivisionRepository(
        $subdivisionRepository_definitionPath
    );
    $formatter             = new DefaultFormatter(
        $addressFormatRepository, $countryRepository, $subdivisionRepository, $locale, array(
            'html_tag'        => 'div',
            'html_attributes' => array('class' => "adr")
        )
    );
    $postal_label          = new PostalLabelFormatter(
        $addressFormatRepository, $countryRepository, $subdivisionRepository, $origin_country, $locale
    );


    $address = new Address();

    return array(
        $address,
        $formatter,
        $postal_label
    );

}


?>
