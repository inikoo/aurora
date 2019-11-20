<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:49:27 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

use ReallySimpleJWT\Token;
require '../keyring/dns.php';
require '../keyring/screenshots.dns.php';

require '../vendor/autoload.php';

$payload = array(
    'uid'=>DNS_ACCOUNT_CODE
);


$token = Token::customPayload($payload, SCREENSHOTS_KEY);

print $token;