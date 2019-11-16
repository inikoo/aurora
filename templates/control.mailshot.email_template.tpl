{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 February 2019 at 12:54:51 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}


<div style="padding:15px 20px;border-bottom:1px solid #ccc;position: relative" class="control_panel">


    {t}Email subject{/t}:

    <input style="margin-left:10px;width:500px" maxlength="70" id="email_template_subject" value="{$email_template->get('Email Template Subject')}" placeholder="{t}Email subject{/t}">


    <span id="change_template" onclick="set_email_template_as_selecting_blueprints({$email_template_key})" class="button  " style="border:1px solid #ccc;padding:5px 10px;margin-left:40px"
          title="{t}Start again{/t}, ({t}Saved templates lists{/t}) ">
            <i class="fa fa-eraser padding_right_5" aria-hidden="true"></i> {t}Start over{/t} <small>({t}Change template{/t})</small>
        </span>

    <div id="email_template_info" class="{if isset($direct_email)}hide{/if}" style="position: absolute;right: 20px;top:2px;line-height: 20px;text-align: right;width:400px;height: 50px">
        {include file="email_template.control.info.tpl" data=$email_template->get('Published Info')}
    </div>

    <div style="clear:both"></div>


</div>
