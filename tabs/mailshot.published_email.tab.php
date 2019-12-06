<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  11 May 2018 at 09:36:13 CEST, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/


$email_campaign = $state['_object'];


$email_template = get_object('Email_Template',$email_campaign->get('Email Campaign Email Template Key'));

$published_email_template = get_object('Published_Email_Template',$email_template->get('Email Template Published Email Key'));







$html='<div style="margin:auto;width: 700px;background-color: #fff;margin-top:20px;"> <div class="very_discreet italic" style="margin-bottom: 2px">(Not actual copy archived, showing template)</div><div style="border:1px solid #ccc;padding:10px 20px"><span class="discreet">'._('Subject').':</span> '.$published_email_template->get('Published Email Template Subject').'</div><div class="__email_text" style="border:1px solid #ccc;border-top:none"></div></div>';


$html.='<script>var request = $.ajax({
  url: "/ar_email_template.php",
  method: "POST",
  data: { tipo : "template_text", key : '.$published_email_template->id.' },
  dataType: "html"
});
 
request.done(function( email_text ) {
  $( ".__email_text" ).html( email_text );
});
 
request.fail(function( jqXHR, textStatus ) {
  alert( "Request failed: " + textStatus );
});</script>';


