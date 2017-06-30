<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 January 2016 at 11:18:00 GMT+8
 Copyright (c) 2016, Inikoo

 Version 3

*/


use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;
use CommerceGuys\Intl\Country\CountryRepository;


//use CommerceGuys\Addressing\Formatter\DefaultFormatter;
//use CommerceGuys\Addressing\Formatter\PostalLabelFormatter;
//use CommerceGuys\Addressing\Model\Address;
//use CommerceGuys\Addressing\Repository\AddressFormatRepository;
//use CommerceGuys\Addressing\Repository\CountryRepository;
//use CommerceGuys\Addressing\Repository\SubdivisionRepository;


spl_autoload_register(
    function ($className) {
        //include_once 'external_libs/CommerceGuys/Addressing/AddressFormat/AddressFormatRepository.php';
        //include_once 'external_libs/CommerceGuys/Addressing/AddressFormat/AddressFormatRepositoryInterface.php';

        if (!preg_match('/CommerceGuys/', $className)) {
            return;
        }

        $className = str_replace("_", "\\", $className);
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className).'.php';


        include_once 'external_libs/'.$fileName;


    }
);


function get_countries_form_data($locale = 'en_GB') {
    $countryRepository = new CountryRepository;

    $countries_data = array();

    foreach ($countryRepository->getAll($locale) as $_country) {
        $countries_data[$_country->getCountryCode()]=array(
            '2alpha'=>$_country->getCountryCode(),
            'name'=>$_country->getName(),
        );
    }

    return $countries_data;
}


function get_address_format($country_code) {


    //require_once 'external_libs/CommerceGuys/Enum/AbstractEnum.php';
    //require_once 'external_libs/CommerceGuys/Addressing/Model/FormatStringTrait.php';
    //require_once 'external_libs/CommerceGuys/Addressing/Model/AddressFormatInterface.php';
    //require_once 'external_libs/CommerceGuys/Addressing/Model/AddressFormatEntityInterface.php';
    //require_once 'external_libs/CommerceGuys/Addressing/Model/AddressFormat.php';
    //require_once 'external_libs/CommerceGuys/Addressing/Repository/AddressFormatRepositoryInterface.php';
    //require_once 'external_libs/CommerceGuys/Addressing/Repository/DefinitionTranslatorTrait.php';
    //require_once 'external_libs/CommerceGuys/Addressing/Repository/AddressFormatRepository.php';
    //require_once 'external_libs/CommerceGuys/Addressing/Enum/AddressField.php';


    $AddressFormatRepository_definitionPath = 'external_libs/CommerceGuys/Addressing/resources/address_format/';
    $addressFormatRepository                = new AddressFormatRepository($AddressFormatRepository_definitionPath);


    return $addressFormatRepository->get($country_code);
}


function get_address_subdivisions($country_code, $locale = null) {

/*
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
*/
   // $subdivisionRepository_definitionPath = 'external_libs/CommerceGuys/Addressing/resources/subdivision/';
    $subdivisionRepository                = new SubdivisionRepository();

    return $subdivisionRepository->getAll([$country_code]);

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


    $AddressFormatRepository_definitionPath = 'external_libs/CommerceGuys/Addressing/resources/address_format/';
    $addressFormatRepository                = new AddressFormatRepository(
        $AddressFormatRepository_definitionPath
    );

    $countryRepository_definitionPath = 'external_libs/CommerceGuys/Intl/resources/country/';

    $countryRepository                    = new CountryRepository($countryRepository_definitionPath);
    $subdivisionRepository_definitionPath = 'external_libs/CommerceGuys/Addressing/resources/subdivision/';
    $subdivisionRepository                = new SubdivisionRepository($subdivisionRepository_definitionPath);
    $formatter                            = new DefaultFormatter(
        $addressFormatRepository, $countryRepository, $subdivisionRepository, $locale, array(
                                    'html_tag'        => 'div',
                                    'html_attributes' => array('class' => "adr")
                                )
    );
    $postal_label                         = new PostalLabelFormatter(
        $addressFormatRepository, $countryRepository, $subdivisionRepository, $origin_country, $locale
    );


    $address = new Address();

    return array(
        $address,
        $formatter,
        $postal_label
    );

}


function get_address_form_data($country_code,$locale='en_GB') {


    $address_data = get_address_format($country_code);

    $address_format = $address_data->getFormat();
    $address_format = preg_replace('/,|-|\//', ' ', $address_format);

    $address_format = trim(preg_replace('/\%|givenName|familyName|organization|ÅLAND|GIBRALTAR|GUERNSEY|JERSEY|SINGAPORE /', '', $address_format));
    $address_format = trim($address_format);
    $address_format = preg_replace('/ /', '_', $address_format);
    $address_format = preg_replace('/\_+/', '_', $address_format);

    $address_format = explode("\n", $address_format);
    $address_format = array_filter($address_format);


    $used_fields = $address_data->getUsedFields();

    if (($key = array_search('organization', $used_fields)) !== false) {
        unset($used_fields[$key]);
    }
    if (($key = array_search('givenName', $used_fields)) !== false) {
        unset($used_fields[$key]);
    }
    if (($key = array_search('familyName', $used_fields)) !== false) {
        unset($used_fields[$key]);
    }


    $address_labels = array(
        'administrativeArea' => $address_data->getAdministrativeAreaType(),
        'locality'           => $address_data->getLocalityType(),
        'dependentLocality'  => $address_data->getDependentLocalityType(),
        'postalCode'         => $address_data->getPostalCodeType(),

    );

    putenv('LC_ALL='.$locale.'.UTF-8');
    setlocale(LC_ALL, $locale.'.UTF-8');
    bindtextdomain("inikoo", "./locales");
    textdomain("inikoo");

    switch ($address_labels['administrativeArea']){

        case 'state':
            $address_labels['administrativeArea']=_('state');
            break;
        case 'province':
            $address_labels['administrativeArea']=_('province');
            break;
        case 'island':
            $address_labels['administrativeArea']=_('island');
            break;
        case 'parish':
            $address_labels['administrativeArea']=_('parish');
            break;
        case 'department':
            $address_labels['administrativeArea']=_('department');
            break;
        case 'county':
            $address_labels['administrativeArea']=_('county');
            break;
        case 'area':
            $address_labels['administrativeArea']=_('area');
            break;
        case 'prefecture':
            $address_labels['administrativeArea']=_('prefecture');
            break;
        case 'district':
            $address_labels['administrativeArea']=_('district');
            break;
        case 'oblast':
            $address_labels['administrativeArea']=_('oblast');
            break;
        case 'emirate':
            $address_labels['administrativeArea']=_('emirate');
            break;
    }

    switch ($address_labels['locality']){

        case 'city':
            $address_labels['locality']=_('city');
            break;
        case 'suburb':
            $address_labels['locality']=_('suburb');
            break;
        case 'district':
            $address_labels['locality']=_('district');
            break;
        case 'post_town':
            $address_labels['locality']=_('post town');
            break;

    }


    switch ($address_labels['dependentLocality']){

        case 'neighborhood':
            $address_labels['dependentLocality']=_('neighborhood');
            break;
        case 'district':
            $address_labels['dependentLocality']=_('district');
            break;
        case 'townland':
            $address_labels['dependentLocality']=_('townland');
            break;
        case 'village_township':
            $address_labels['dependentLocality']=_('village/township');
            break;
        case 'suburb':
            $address_labels['dependentLocality']=_('suburb');
            break;
    }

    switch ($address_labels['postalCode']){

        case 'postal':
            $address_labels['postalCode']=_('postal code');
            break;
    }


    return array(
        $address_format,
        $address_labels,
        $used_fields
    );
}

?>
