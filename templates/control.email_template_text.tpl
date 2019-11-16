{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 February 2019 at 13:02:20 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}


<div style="padding:15px 20px;border-bottom:1px solid #ccc;position: relative" class="control_panel">


    {t}Email subject{/t}:

    <input style="margin-left:10px;width:500px" maxlength="70" id="email_template_subject" value="{$email_template->get('Email Template Subject')}" placeholder="{t}Email subject{/t}">


    <div  style="float:right;margin-right: 40px"><span id="email_template_text_save"  class="save " onclick="save_email_template_text()">{t}Save{/t} <i class="fa fa-cloud"></i></span></div>

    <div style="clear:both"></div>


</div>

<script>


</script>