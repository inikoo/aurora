{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2017 at 19:41:41 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div style="padding:20px 20px 10px 20px;border-bottom:1px solid #ccc;" class="control_panel">

    <span id="save_button" class="" style="float:right;" onClick="$('#preview')[0].contentWindow.save()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>


    {if isset($control_template)}
        {include file=$control_template content=$content}

    {/if}




    <div style="clear:both"></div>

</div>



<iframe id="preview" style="width:100%;height: 900px" frameBorder="0" src="/webpage.php?webpage_key={$webpage->id}&theme={$theme}"></iframe>

