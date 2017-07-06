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



<span id="registration_form"  onclick="change_view(state.request + '&tab=webpage.preview')"  class="button"  style="border:1px solid #ccc;padding:5px 10px"   >
        <i class="fa fa-registered" aria-hidden="true" title="{t}Registration form{/t}"></i>
</span>

    <span  onclick="change_view(state.request + '&tab=email_template')"  class="button {if !$show_back_button}hide{/if}"  style="border:1px solid #ccc;padding:5px 10px;margin-left:20px"   >
         <i class="fa fa-arrow-left" aria-hidden="true"></i> {t}Go back{/t}
</span>



    <div style="clear:both"></div>

</div>

