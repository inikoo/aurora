{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 February 2018 at 19:12:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div style="padding:20px;border-bottom:1px solid #ccc" class="control_panel">

    <span  onclick="change_view(state.request + '&subtab=email_campaign.email_template')"  class="button {if !$show_back_button}hide{/if}"  style="border:1px solid #ccc;padding:5px 10px;margin-left:20px"   >
         <i class="fa fa-arrow-left" aria-hidden="true"></i> {t}Email editor{/t}</span>
    <span id="create_text_only_email_template"  class=" {if $show_back_button}hide{/if}"     >
        {t}Choose a HTML email template{/t}</span>


    <div style="clear:both"></div>

</div>

