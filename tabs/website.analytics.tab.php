<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Sun 07 April 2019 09:47:09 MYT, Cyberjaya, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/

$website=$state['_object'];



if($state['_object']->get('Website Status')=='InProcess'){

    $html='<div style="clear: both;margin-top:50px;margin-left: 40px"><span style="margin:10px 0px;padding:10px;border:1px solid #ccc" data-referer="/website/'.$website->id.'/analytics" data-website_key="'.$website->id.'" onclick="launch_website(this)" class="save changed valid">'._('Launch website').' <i class="fa fa-fw fa-rocket save changed valid"></i></span></div>';

    return;
}

$smarty->assign('website',$state['_object']);


$html = $smarty->fetch('dashboard/website.analytics.dbard.tpl');
