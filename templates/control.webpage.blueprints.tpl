{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 July 2017 at 12:56:11 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div style="padding:20px;border-bottom:1px solid #ccc" class="control_panel">

    <span  onclick="change_view(state.request + '&subtab=webpage.email_template')"  class="button {if !$show_back_button}hide{/if}"  style="border:1px solid #ccc;padding:5px 10px;margin-left:20px"   >
         <i class="fa fa-arrow-left" aria-hidden="true"></i> {t}Email editor{/t}</span>
    <span id="create_text_only_email_template"  class=" {if $show_back_button}hide{/if}"     >
        {t}Choose a HTML email template{/t} or <span class="marked_link">set the email as text only</span></span>


    <div style="clear:both"></div>

</div>

