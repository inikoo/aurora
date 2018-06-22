{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 June 2018 at 16:05:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div style="padding:15px 20px;border-bottom:1px solid #ccc;position: relative" class="control_panel">







    <i id="email_template_text_button" class="fa fa-fw fa-text-height button  {if $email_template->get('Email Template Type')=='Text'}hide{/if} {if $email_template->get('Email Template Text')==''}error very_discreet{/if} " aria-hidden="true" title="{t}Text version{/t}"></i>
    <i id="email_template_html_button" class="fab fa-fw fa-html5 hide button" aria-hidden="true" title="{t}HTML version{/t}"></i>


    <input style="margin-left:20px;width:500px" maxlength="70" id="email_template_subject" value="{$email_template->get('Email Template Subject')}" placeholder="{t}Email subject{/t}">

        <span id="change_template" onclick="change_view(state.request + '&tab=prospects.base_templates')" class="button  {if $email_template->get('Email Template Type')=='Text'}hide{/if}" style="border:1px solid #ccc;padding:5px 10px;margin-left:40px">
      <i class="fa fa-eraser padding_right_5" aria-hidden="true"></i> {t}Start over{/t}
    </span>

   





    <div style="clear:both"></div>

</div>

