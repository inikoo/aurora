<?php
/*
 File: user.php

 UI index page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/

include_once 'common.php';


switch ($user->data['User Type']) {
case 'Administrator':
	header('Location: admin_user.php');
	break;
case 'Warehouse':
	header('Location: warehouse_user.php?id='.$user->id);
	break;
case 'Staff':
	header('Location: staff_user.php?id='.$user->id);
	break;
case 'Customer':
	header('Location: site_user.php?id='.$user->id);
	break;
case 'Supplier':
	header('Location: supplier_user.php?id='.$user->id);
	break;
}

?>
