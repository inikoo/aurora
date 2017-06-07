{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 15:12:20 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}





<iframe id="preview" style="width:100%;height: 750px" frameBorder="0" src="/webpage.header.php?website_key={$website->id}&theme={$theme}"></iframe>


<div style="padding:20px">

    <i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i>
    <span data-data='{ "object": "website_header", "key":"{$website->id}"}' onClick="reset_object(this)" class="delete_object disabled "> {t}Reset header{/t} <i class="fa fa-recycle  "></i></span>

</div>
