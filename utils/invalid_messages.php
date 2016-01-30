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
	case 'float_unsigned':
		$messages=array(
			'not_numeric'=>_('Value has to be a number'),
			'negative'=>_('Value has to be a positive'),
			'empty'=>_("Value can't be empty"),
		);
		break;
	case 'bigint_unsigned':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>_('Value has to be a positive'),
			'too_big'=>sprintf(_('The maximum value is %s'), '18446744073709551615'),
			'empty'=>_("Value can't be empty"),
		);
		break;
	case 'int_unsigned':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '4294967295'),
			'empty'=>_("Value can't be empty"),

		);
		break;
	case 'mediumint_unsigned':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '16777215'),
			'empty'=>_("Value can't be empty"),

		);
		break;
	case 'smallint_unsigned':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '65535'),
			'empty'=>_("Value can't be empty"),

		);
		break;
	case 'tinyint_unsigned':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '255'),
			'empty'=>_("Value can't be empty"),

		);
		break;
	case 'bigint':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '9223372036854775807'),
			'too_small'=>sprintf(_('The minimum value is %s'), '-9223372036854775808'),
			'empty'=>_("Value can't be empty"),

		);
		break;
	case 'int':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '2147483647'),
			'too_small'=>sprintf(_('The minimum value is %s'), '-2147483648'),
			'empty'=>_("Value can't be empty"),

		);
		break;
	case 'mediumint':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '8388607'),
			'too_small'=>sprintf(_('The minimum value is %s'), '-8388608'),
			'empty'=>_("Value can't be empty"),

		);
		break;
	case 'smallint':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '32767'),
			'too_small'=>sprintf(_('The minimum value is %s'), '-32768'),
			'empty'=>_("Value can't be empty"),

		);
		break;
	case 'tinyint':
		$messages=array(
			'not_integer'=>sprintf(_('Value has to be a positive integer. e.g. %s'), rand(5, 25)),
			'negative'=>sprintf(_('Value has to be a positive')),
			'too_big'=>sprintf(_('The maximum value is %s'), '127'),
			'too_small'=>sprintf(_('The minimum value is %s'), '-128'),
			'empty'=>_("Value can't be empty"),

		);
		break;
	case 'date':
		$messages=array(
			'invalid'=>_('Invalid date'),
			'empty'=>_("Value can't be empty"),

		);
		break;
	case 'string':
		$messages=array(
			'invalid'=>_('Invalid value'),
			'empty'=>_("Value can't be empty"),
		);
		break;
	case 'password':
		$messages=array(
			'invalid'=>_('Invalid password'),
			'short'=>sprintf(_('Password has to be at least %d characters'), 6),
		);
		break;
	case 'password_with_confirmation':
		$messages=array(
			'invalid'=>_('Invalid password'),
			'short'=>sprintf(_('Password has to be at least %d characters'), 6),
			'not_match'=>_("Passwords don't match"),

		);
		break;
	case 'password_with_confirmation_paranoid':
		$messages=array(
			'incorrect'=>_('Incorrect password'),
			'invalid'=>_('Invalid password'),
			'short'=>sprintf(_('Password has to be at least %d characters'), 6),
			'not_match'=>_("Passwords don't match"),

		);
		break;
	case 'pin':
		$messages=array(
			'invalid'=>_('Invalid value'),
			'short'=>sprintf(_('PIN has to be at least %d characters'), 4),
		);
		break;
	case 'pin_paranoid':
		$messages=array(
			'incorrect'=>_('Incorrect password'),
			'invalid'=>_('Invalid value'),
			'short'=>sprintf(_('PIN has to be at least %d characters'), 4),
		);
		break;
	case 'email':
		$messages=array(
			'invalid'=>_('Invalid email'),
			'empty'=>_('Please provide an email'),
		);
		
	case 'address':
		$messages=array(
			'missing_fields'=>_('Missing fields'),
			'missing_field'=>_('Missing field'),
			'missing_recipient'=>_('Please provide a recipient'),
			'missing_addressLine1'=>_('Please provide the address first line'),
			'missing_postalCode'=>_('Please provide a postal code'),

		);	
		break;
	case 'telephone':
		$messages=array(
			'invalid'=>_('Invalid number'),
			'empty'=>_('Please provide a number'),
			'short'=>_('Number to short'),
			'long'=>_('Number to long'),
			'invalid_code'=>_('Invalid contry code'),
		);
		break;
	case 'working_hours':
		$messages=array(
			'invalid_time'=>_('Invalid time'),
			'same_start_end'=>_('Same start & finish time'),
			'end_less_start'=>_("finish < start"),
			'break_ends_after_end'=>_('Break ends after finish'),
			'break_before_start'=>_('Break before start'),
			'wrong_break'=>_('Break ends next day'),
			'invalid_break_duration'=>_('Invalid break duration'),
		);

		break;
	case 'salary':
		$messages=array(
			'invalid'=>_('Invalid amount'),
		);
	}

	return $messages;

}


?>
