<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 18:54:57 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$email_tracking = $state['_object'];


$sql=sprintf('select `Email Tracking Email Copy Subject` from `Email Tracking Email Copy` where `Email Tracking Email Copy Key`=%d ',$email_tracking->id);
if ($result=$db->query($sql)) {
    if ($row = $result->fetch()) {


        $html='<div style="margin:auto;width: 700px;background-color: #fff;margin-top:20px;"><div style="border:1px solid #ccc;padding:10px 20px"><span class="discreet">'._('Subject').':</span> '.$row['Email Tracking Email Copy Subject'].'</div><div class="__email_text" style="border:1px solid #ccc;border-top:none"></div></div>';

        $html.='<script>var request = $.ajax({
  url: "/ar_email_template.php",
  method: "POST",
  data: { tipo : "email_text", key : '.$email_tracking->id.' },
  dataType: "html"
});
 
request.done(function( email_text ) {
  $( ".__email_text" ).html( email_text );
});
 
request.fail(function( jqXHR, textStatus ) {
  alert( "Request failed: " + textStatus );
});</script>';

    }else{
        $published_email_template = get_object('Published_Email_Template',$email_tracking->get('Email Tracking Published Email Template Key'));



        $html='<div style="margin:auto;width: 700px;background-color: #fff;margin-top:20px;"> <div class="very_discreet italic" style="margin-bottom: 2px">(Not actual copy archived, showing template)</div><div style="border:1px solid #ccc;padding:10px 20px"><span class="discreet">'._('Subject').':</span> '.$published_email_template->get('Published Email Template Subject').'</div><div class="__email_text" style="border:1px solid #ccc;border-top:none"></div></div>';


        $html.='<script>var request = $.ajax({
  url: "/ar_email_template.php",
  method: "POST",
  data: { tipo : "template_text", key : '.$email_tracking->get('Email Tracking Published Email Template Key').' },
  dataType: "html"
});
 
request.done(function( email_text ) {
  $( ".__email_text" ).html( email_text );
});
 
request.fail(function( jqXHR, textStatus ) {
  alert( "Request failed: " + textStatus );
});</script>';



    }
}

