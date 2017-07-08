{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:6 July 2017 at 18:28:52 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
    <span style="font-size: 85%;font-style: italic" class="discreet  {if !$data.editing}invisible{/if} ">({t}Unpublished version on editor{/t})  <span class=" edited_date">{$data.edited_date}</span></span><br>
    <span class=" {if !$data.published}invisible{/if}" >{t}Published{/t}  <span  class="small published_date discreet">{$data.published_date}</span></span>
