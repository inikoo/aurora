<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 September 2016 at 13:52:10 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include('keyring/dns.php');


include_once('external_libs/bee.io/BeeFree.php');

include_once('external_libs/bee.io/BeeFree.php');


$beefree = new BeeFree($bee_io_id, $bee_io_key);
$result  = $beefree->getCredentials();


$smarty->assign('bee_token', json_encode($result));


$html = $smarty->fetch('email_template.tpl');


?>
