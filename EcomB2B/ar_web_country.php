<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 26 May 2022 14:51:47 Turkey Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */



if(empty( $_SERVER["HTTP_CF_IPCOUNTRY"])  or $_SERVER["HTTP_CF_IPCOUNTRY"]=='XX' or  $_SERVER["HTTP_CF_IPCOUNTRY"]=='T1' ){
    $country_code='GB';
}else{
    $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];

}


print json_encode(
    ['country'=>$country_code]
);