<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 December 2015 at 13:03:12 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function authorize_file_view($db,$user, $public, $subject, $subject_key) {

	if ($public=='Yes') {
		return true;
	}


	switch ($subject) {
	case 'Staff':

		if ($user->can_view('staff')) {
			return true;
		}


		if ( $user->get('User Type')=='Staff' and $user->get('User Parent Key')==$subject_key) {
			return true;
		}





		break;
	case 'Supplier':


		if ($user->can_view('suppliers')) {
			return true;
		}


		if ( $user->get('User Type')=='Agent') {

			$found=0;
			$sql=sprintf('select count(*) as num from `Agent Supplier Bridge` where `Agent Supplier Agent Key`=%d and `Agent Supplier Supplier Key`=%d ',
				$user->get('User Parent Key'),
				$subject_key
			);
			if ($result=$db->query($sql)) {
				if ($row = $result->fetch()) {
					$found=$row['num'];
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}
			
			if($found>0){
			    return true;
			}

		}









		break;
	default:
		return false;
		break;
	}


	return false;
}


?>
