{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:15 April 2018 at 13:47:05 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div id="block_{$key}"  class="  {if !$data.show}hide{/if}"  style="padding-top:80px;padding-bottom:100px"  >

    <div class="page_not_found">
        <p >{t}You are not logged in{/t}</p>
        <div class="clear separator"></div>
        <a href="login.sys" class="real_button"><i class="fa fa-sign-in padding_left_20"></i> <span> {if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</</span></a>

    </div>

</div>

