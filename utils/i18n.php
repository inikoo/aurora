<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 November 2015 at 14:08:50 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function set_locale($locale) {




	if (!function_exists('_')) {
		function _($str) {
			return $str;
		}


		function gettext($str) {
			return $str;
		}


		function ngettext($str) {
			return $str;
		}


		function bindtextdomain() {};
		function bind_textdomain_codeset() {};
		function textdomain() {};

	}
	putenv('LC_MESSAGES='.$locale);

	if (defined('LC_MESSAGES'))
		setlocale(LC_MESSAGES, $locale);
	else
		setlocale(LC_ALL, $locale);
	bindtextdomain("inikoo", "./locale");
	textdomain("inikoo");
	bind_textdomain_codeset("inikoo", 'UTF-8'); //This was the missing piece.



setlocale(LC_TIME, $locale);

}


?>
