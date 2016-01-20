<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 January 2016 at 11:18:00 GMT+8
 Copyright (c) 2016, Inikoo

 Version 3

*/




function get_address_fields_repo() {

	include_once 'external_libs/addressing/Model/FormatStringTrait.php';
	include_once 'external_libs/addressing/Model/AddressFormatInterface.php';
	include_once 'external_libs/addressing/Model/AddressFormatEntityInterface.php';
	include_once 'external_libs/addressing/Model/AddressFormat.php';
	include_once 'external_libs/addressing/Repository/AddressFormatRepositoryInterface.php';
	include_once 'external_libs/addressing/Repository/DefinitionTranslatorTrait.php';
	include_once 'external_libs/addressing/Repository/SubdivisionRepositoryInterface.php';

	include_once 'external_libs/addressing/Repository/AddressFormatRepository.php';
	include_once 'external_libs/addressing/Repository/SubdivisionRepository.php';

	use CommerceGuys\Addressing\Repository\AddressFormatRepository;
	use CommerceGuys\Addressing\Repository\SubdivisionRepository;


	$AddressFormatRepository_definitionPath='external_libs/addressing/resources/address_format/';



	return new AddressFormatRepository($AddressFormatRepository_definitionPath);

}

function get_address_fields_repo() {

	include_once 'external_libs/addressing/Model/FormatStringTrait.php';
	include_once 'external_libs/addressing/Model/AddressFormatInterface.php';
	include_once 'external_libs/addressing/Model/AddressFormatEntityInterface.php';
	include_once 'external_libs/addressing/Model/AddressFormat.php';
	include_once 'external_libs/addressing/Repository/AddressFormatRepositoryInterface.php';
	include_once 'external_libs/addressing/Repository/DefinitionTranslatorTrait.php';
	include_once 'external_libs/addressing/Repository/SubdivisionRepositoryInterface.php';

	include_once 'external_libs/addressing/Repository/AddressFormatRepository.php';
	include_once 'external_libs/addressing/Repository/SubdivisionRepository.php';

	use CommerceGuys\Addressing\Repository\AddressFormatRepository;
	use CommerceGuys\Addressing\Repository\SubdivisionRepository;


	$AddressFormatRepository_definitionPath='external_libs/addressing/resources/address_format/';



	return new $subdivisionRepository = new SubdivisionRepository();

}


?>
