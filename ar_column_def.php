<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 September 2015 18:51:58 GMT+88 Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

require_once 'common.php';


switch ($_REQUEST['table']) {
case 'customers':
	
	?>
	[{
    "name": "id",
    "label": "Id",
    "editable": false,
   "cell": Backgrid.IntegerCell.extend({
      "orderSeparator": ''
    })
}
, {
    "name": "name",
    "label": "Name",
    "editable": true,
    "cell": "string"
}]


	<?php

	break;
}


?>
