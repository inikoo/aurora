{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 June 2018 at 18:12:05 GMT+8,  Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div style="padding:15px 20px;border-bottom:1px solid #ccc;position: relative" class="control_panel">




    <i id="email_template_text_button" class="fa fa-fw fa-text-height button  {if $email_template->get('Email Template Type')=='Text'}hide{/if} {if $email_template->get('Email Template Text')==''}error very_discreet{/if} " aria-hidden="true" title="{t}Text version{/t}"></i>
    <i id="email_template_html_button" class="fab fa-fw fa-html5 hide button" aria-hidden="true" title="{t}HTML version{/t}"></i>


    <input style="margin-left:20px;width:500px" maxlength="70" id="compose_email_subject" value="{$email_template->get('Email Template Subject')}" placeholder="{t}Email subject{/t}">






    <div id="email_template_info" class="{if isset($direct_email)}hide{/if}" style="position: absolute;right: 20px;top:2px;line-height: 20px;text-align: right;width:400px;height: 50px">
        {include file="email_template.control.info.tpl" data=$email_template->get('Published Info')}
    </div>

    <div style="clear:both"></div>

</div>

<script>



</script>