<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 23:58:03 GMT, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


function get_invalid_message($type) {

	$messages=array();
	switch ($type) {
	case 'bigint_unsigned':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '18446744073709551615'),

		);
		break;
	case 'int_unsigned':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '4294967295'),

		);
		break;
	case 'mediumint_unsigned':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '16777215'),

		);
		break;
	case 'smallint_unsigned':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '65535'),

		);
		break;
	case 'tinyint_unsigned':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '255'),

		);
		break;
	case 'bigint':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '9223372036854775807'),
			'too_small'=>sprintf(_('The minimum value is %s'), '-9223372036854775808'),

		);
		break;
	case 'int':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '2147483647'),
			'too_small'=>sprintf(_('The minimum value is %s'), '-2147483648'),

		);
		break;
	case 'mediumint':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '8388607'),
			'too_small'=>sprintf(_('The minimum value is %s'), '-8388608'),

		);
		break;
	case 'smallint':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '32767'),
			'too_small'=>sprintf(_('The minimum value is %s'), '-32768'),

		);
		break;
	case 'tinyint':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '127'),
			'too_small'=>sprintf(_('The minimum value is %s'), '-128'),

		);
		break;

	}

	return $messages;

}


?>
