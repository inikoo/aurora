{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2018 at 13:23:29 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}




<div style="padding:20px;min-height: 30px;border-bottom:1px solid #ccc " class="control_panel">

    <span class="hide"><i class="fa fa-toggle-on" aria-hidden="true"></i> {t}Logged in{/t}</span>


    <span id="save_button" class="" style="float:right" onClick="$('#preview')[0].contentWindow.save_header()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>


</div>



<iframe id="preview" style="width:100%;height: 750px" frameBorder="0" src="/webpage.colors.php?&website_key={$website->id}&theme={$theme}"></iframe>
