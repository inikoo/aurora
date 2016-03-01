<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 March 2016 at 10:42:07 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/


function highlightkeyword($str, $search) {
	$highlightcolor = "#daa732";
	$occurrences = substr_count(strtolower($str), strtolower($search));
	$newstring = $str;
	$match = array();

	for ($i=0;$i<$occurrences;$i++) {
		$match[$i] = stripos($str, $search, $i);
		$match[$i] = substr($str, $match[$i], strlen($search));
		$newstring = str_replace($match[$i], '[#]'.$match[$i].'[@]', strip_tags($newstring));
	}

	$newstring = str_replace('[#]', '<mark>', $newstring);
	$newstring = str_replace('[@]', '</mark>', $newstring);
	return $newstring;

}


?>