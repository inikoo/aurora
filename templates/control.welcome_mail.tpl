{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 September 2016 at 13:58:35 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div style="padding:15px 20px;border-bottom:1px solid #ccc;position: relative" class="control_panel">





    <span id="change_template" onclick="change_view(state.request + '&tab=transactional.email_blueprints')" class="button" style="border:1px solid #ccc;padding:5px 10px;margin-right:40px">
        {$change_template_label} <span style="font-style: italic" class="discreet small">({t}Change template{/t})</span>
    </span>


    <i id="email_template_text_button" class="fa fa-fw fa-text-height button {if $email_template->get('Email Template Text')==''}error very_discreet{/if} " aria-hidden="true" title="{t}Text version{/t}""></i>
    <i id="email_template_html_button" class="fa fa-fw fa-html5 hide button" aria-hidden="true" title="{t}HTML version{/t}""></i>


    <input style="margin-left:20px;width:500px" maxlength="70" id="email_template_subject" value="{$email_template->get('Email Template Subject')}" placeholder="{t}Email subject{/t}">

    <div id="email_template_info" class="" style="position: absolute;right: 20px;top:2px;line-height: 20px;text-align: right;">
        {include file="email_template.control.info.tpl" data=$email_template->get('Published Info')}
    </div>

    <div style="clear:both"></div>

</div>

<script>



</script>