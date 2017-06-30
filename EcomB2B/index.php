<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 June 2017 at 12:08:30 GMT+7, Phuket, Thailand

  Copyright (c) 2017, Inikoo

  Version 2.0
*/


include_once 'common.php';

if ($logged_in ) {

    $webpage_key = $website->get_system_webpage_key('home.sys');
    $template='homepage.'.$theme.'.tpl';


}else{
    $webpage_key = $website->get_system_webpage_key('home_logout.sys');
    $template=$theme.'/homepage_logout.'.$theme.'.'.$website->get('Website Type').'.tpl';
}

include_once 'webpage.php';



?>