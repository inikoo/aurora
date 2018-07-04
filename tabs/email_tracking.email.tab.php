<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 18:54:57 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$email_tracking = $state['_object'];


$sql=sprintf('select `Email Tracking Email Copy Subject`, `Email Tracking Email Copy Body` from `Email Tracking Email Copy` where `Email Tracking Email Copy Key`=%d ',$email_tracking->id);
if ($result=$db->query($sql)) {
    if ($row = $result->fetch()) {



        $html='<div style="margin:auto;width: 700px;background-color: #fff;margin-top:20px;"><div style="border:1px solid #ccc;padding:10px 20px"><span class="discreet">'._('Subject').':</span> '.$row['Email Tracking Email Copy Subject'].'</div><div style="border:1px solid #ccc;border-top:none">'.$row['Email Tracking Email Copy Body'].'</div></div>';

    }else{
        $published_email_template = get_object('Published_Email_Template',$email_tracking->get('Email Tracking Published Email Template Key'));



        $html='<div style="margin:auto;width: 700px;background-color: #fff;margin-top:20px;"> <div class="very_discreet italic" style="margin-bottom: 2px">(Not actual copy archived, showing template)</div><div style="border:1px solid #ccc;padding:10px 20px"><span class="discreet">'._('Subject').':</span> '.$published_email_template->get('Published Email Template Subject').'</div><div style="border:1px solid #ccc;border-top:none">'.$published_email_template->get('Published Email Template HTML').'</div></div>';

    }
}else {
	print_r($error_info=$db->errorInfo());
	print "$sql\n";
	exit;
}






?>
