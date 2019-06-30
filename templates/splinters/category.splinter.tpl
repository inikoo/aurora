{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 April 2018 at 20:06:39 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<span onclick="$(this).closest('.category_wrap').addClass('sortable-disabled');$(this).closest('.wrap').find('.item_overlay').removeClass('hide');$(this).closest('.wrap').find('.edit').addClass('hide');$(this).addClass('hide')" class="fa-stack button edit_icon"
      style="font-size:12.5px;position:absolute;z-index: 1005;right:10px;top:10px;;">
        <i style="color:cornflowerblue;" class="fas fa-circle fa-stack-2x "></i>
        <i class="fas fa-pencil fa-stack-1x fa-inverse"></i>
    </span>

{if $category_data.item_type=='Guest'}
<span onclick="$(this).closest('.wrap').remove();$('#save_button',window.parent.document).addClass('save button changed valid')" class="fa-stack button edit_icon" style="font-size:12.5px;position:absolute;z-index: 1005;right:10px;top:40px;">
        <i style="color:red;" class="fas fa-circle fa-stack-2x "></i>
        <i class="fas fa-trash-alt fa-stack-1x fa-inverse"></i>
    </span>
{/if}

<div class="category_block" style="position:relative" data-category_key="{$category_data.category_key}" data-category_webpage_key="{$category_data.webpage_key}" data-item_type="{$category_data.item_type}"
     data-link="{$category_data.link}" data-webpage_code="{$category_data.webpage_code}">
    <div class="item_header_text">{$category_data.header_text|strip_tags}</div>
    <div  style="position: relative;top:-2px;left:3px" class="wrap_to_center ">
        <img src="{if $category_data.image_website==''}https://via.placeholder.com/150x120{else}EcomB2B/{$category_data.image_website}{/if}"
             data-image_mobile_website="{if $category_data.image_mobile_website==''}https://via.placeholder.com/150x120{else}{$category_data.image_mobile_website}{/if}"
             data-image_website="{if $category_data.image_website==''}https://via.placeholder.com/150x120{else}{$category_data.image_website}{/if}"
             data-src="{if $category_data.image_src==''}https://via.placeholder.com/150x120{else}{$category_data.image_src}{/if}"/>
    </div>
</div>
<div class="category_block item_overlay hide">
    <div class="item_overlay_item_header_text " style="text-align: center;padding-top:9px" contenteditable="true">{$category_data.header_text|strip_tags}</div>
    <div class="button_container">
        <div class="flex-item category_code">{$category_data.category_code}</div>
        <div class="flex-item "><span class="number_products">{$category_data.number_products}</span> <i class="fa fa-cube" aria-hidden="true"></i></div>
        <div class="flex-item" style="border:none;width:40px"><i style="position: relative;top:-12px;margin-left: 5px" class="fa fa-window-close button close_category_block"></i></div>
    </div>
    <div class="button_container">
        <div class="flex-item full move_to_other_section button">{t}Move to other section{/t}</div>
    </div>
    <div class="button_container">
        <div class="flex-item full change_category_image button">



            <input style="display:none" type="file"  name="category_categories_category"  id="file_upload_{$category_data.category_key}" class="image_upload_from_iframe hide"
                   data-parent="Webpage"  data-parent_key="{$webpage->id}"  data-parent_object_scope="Item"   data-metadata='{ "block":"category_categories", "scope":"category", "scope_key":"{$category_data.category_key}"}'   data-options='' data-response_type="webpage" />

            <label for="file_upload_{$category_data.category_key}">
                <i class="fa  fa-image fa-fw button" aria-hidden="true"></i> {t}Change image{/t}
            </label>





        </div>
    </div>
</div>