<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 December 2015 at 13:03:12 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function authorize_file_view($user, $public, $subject, $subject_key) {
	switch ($subject) {
	case 'Staff':
		if ($public=='No') {
			if (!$user->can_view('staff')) {
				if ($user->get('User Parent Key')!=$subject_key)
					return false;
			}
		}

		break;
	default:
		return false;
		break;
	}
	return true;
}


?>
