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


    <a id="link_to_live_webpage" target="_blank"  class="{if $webpage->get('Webpage State')=='Offline'}invisible{/if}"  href="{$webpage->get('URL')}" ><i class="fa fa-external-link" aria-hidden="true"  style="float:right;margin-left:20px;position:relative;top:2px"></i>   </a>


    <span id="save_button"  data-store_key="{$webpage->get('Store Key')}" class="unselectable" style="float:right;" onClick="$('#preview')[0].contentWindow.save()">{t}Publish{/t} <i class="fal fa-rocket  " aria-hidden="true"></i></span>

    {if isset($control_template)}
        {include file=$control_template content=$content}

    {/if}




    <div style="clear:both"></div>

</div>


<div id="text_layout_ideas" class="hide">
    <div style="text-align: right;margin-bottom: 10px;padding-right: 5px">
        <i class="fa fa-window-close button" onclick="$('#text_layout_ideas').addClass('hide')"></i>
    </div>

    <div class="options">
        <img class="button" template="1" src="/art/images_layout_1.png">
        <img class="button" template="2" src="/art/images_layout_2.png">
        <img class="button" template="3" src="/art/images_layout_3.png">
        <img class="button" template="4" src="/art/images_layout_4.png">
        <img class="button" template="12" src="/art/images_layout_12.png">
        <img class="button" template="21" src="/art/images_layout_21.png">
        <img class="button" template="13" src="/art/images_layout_13.png">
        <img class="button" template="31" src="/art/images_layout_31.png">
        <img class="button" template="211" src="/art/images_layout_211.png">


    </div>

</div>

<iframe id="preview" style="width:100%;height: 900px;" frameBorder="1" src="/webpage.php?webpage_key={$webpage->id}&theme={$theme}"></iframe>

