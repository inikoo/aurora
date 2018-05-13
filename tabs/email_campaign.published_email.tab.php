<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 May 2018 at 09:36:13 CEST
 Copyright (c) 2018, Inikoo

 Version 3

*/


$email_campaign = $state['_object'];


$email_template = get_object('Email_Template',$email_campaign->get('Email Campaign Email Template Key'));

$published_email_template = get_object('Published_Email_Template',$email_template->get('Email Template Published Email Key'));



$html='<div style="margin:auto;width: 700px;background-color: #fff;margin-top:20px;"><div style="border:1px solid #ccc;padding:10px 20px"><span class="discreet">'._('Subject').':</span> '.$published_email_template->get('Published Email Template Subject').'</div><div style="border:1px solid #ccc;border-top:none">'.$published_email_template->get('Published Email Template HTML').'</div></div>';




?>
