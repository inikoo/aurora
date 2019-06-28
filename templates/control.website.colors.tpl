{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2018 at 13:23:29 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}




<div style="padding:10px  20px  0px 0px  ; min-height: 30px;border-bottom:1px solid #ccc " class="control_panel">

    <span class="hide"><i class="fa fa-toggle-on" aria-hidden="true"></i> {t}Logged in{/t}</span>


    <i class="far hide fa-expand-alt button" onclick="toggle_showcase(this)" ></i>

    <input style="display:none" type="file" name="favicon" id="update_image_favicon" class="image_upload"   data-parent="Website"  data-parent_key="{$website->id}"  data-parent_object_scope="Favicon"  data-metadata=''    data-options=""  data-response_type="website" />
    <label style="cursor: pointer" for="update_image_favicon">

        <img id="favicon"     style="margin-left:30px;height: 20px;width: 20px;vertical-align:middle" src="{if empty($settings['favicon'])}/art/favicon_empty.png{else}{$settings['favicon']}{/if}">  <span style=";margin-left: 5px;margin-right:30px;">Favicon</span>
    </label>



    <span id="save_button" class="" style="float:right" onClick="$('#preview')[0].contentWindow.save_styles()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>


</div>



<iframe id="preview" style="width:100%;height: 1000px" frameBorder="0" src="/webpage.colors.php?&website_key={$website->id}&theme={$theme}"></iframe>

<script>


    function add_header_text(){

        $('#preview')[0].contentWindow.add_header_text()

    }

    function add_search_text(){

        $('#preview')[0].contentWindow.add_search_text()

    }



function toggle_showcase(element){



    if($(element).hasClass('fa-expand-alt')){
        $(element).removeClass('fa-expand-alt').addClass('fa-compress-alt')
        $('#object_showcase').addClass('hide')
    }else{
        $(element).addClass('fa-expand-alt').removeClass('fa-compress-alt')
        $('#object_showcase').removeClass('hide')
    }

}


</script>