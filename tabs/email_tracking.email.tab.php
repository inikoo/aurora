<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 18:54:57 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$email_tracking = $state['_object'];



$published_email_template = get_object('Published_Email_Template',$email_tracking->get('Email Tracking Published Email Template Key'));



$html='<div style="margin:auto;width: 700px;background-color: #fff;margin-top:20px;"><div style="border:1px solid #ccc;padding:10px 20px"><span class="discreet">'._('Subject').':</span> '.$published_email_template->get('Published Email Template Subject').'</div><div style="border:1px solid #ccc;border-top:none">'.$published_email_template->get('Published Email Template HTML').'</div></div>';




?>
