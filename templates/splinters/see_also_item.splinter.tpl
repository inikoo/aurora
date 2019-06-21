{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 April 2018 at 14:58:29 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<div class="category_wrap wrap" data-type="{$item_data.type}">

     <span onclick="$(this).closest('.wrap').addClass('sortable-disabled');$(this).closest('.wrap').find('.item_overlay').removeClass('hide');$(this).closest('.wrap').find('.edit_icon').addClass('hide')" class="fa-stack button edit_icon"
           style="font-size:12.5px;position:absolute;z-index: 1005;right:10px;top:10px;;">
        <i style="color:cornflowerblue;" class="fas fa-circle fa-stack-2x "></i>
        <i class="fas fa-pencil fa-stack-1x fa-inverse"></i>
    </span>

    <span onclick="$(this).closest('.wrap').remove();$('#save_button',window.parent.document).addClass('save button changed valid')" class="fa-stack button edit_icon" style="font-size:12.5px;position:absolute;z-index: 1005;right:10px;top:40px;">
        <i style="color:red;" class="fas fa-circle fa-stack-2x "></i>
        <i class="fas fa-trash-alt fa-stack-1x fa-inverse"></i>
    </span>


    <div class="category_block" style="position:relative"
         {if $item_data.type=='category'}
            data-item_key="{$item_data.category_key}"
         {elseif $item_data.type=='product'}
             data-item_key="{$item_data.product_id}"
         {/if}
         data-webpage_key="{$item_data.webpage_key}"
         data-link="{$item_data.link}"
         data-webpage_code="{$item_data.webpage_code}"
    >
        <div class="item_header_text">{$item_data.header_text|strip_tags}</div>
        <div style="position: relative;top:-2px;left:3px" class="wrap_to_center ">
            <img src="{if $item_data.image_website==''}https://via.placeholder.com/150x120{else}{$item_data.image_website}{/if}"
                 data-image_mobile_website="{if $item_data.image_mobile_website==''}https://via.placeholder.com/150x120{else}{$item_data.image_mobile_website}{/if}"
                 data-image_website="{if $item_data.image_website==''}https://via.placeholder.com/150x120{else}{$item_data.image_website}{/if}"
                 data-src="{if $item_data.image_src==''}https://via.placeholder.com/150x120{else}{$item_data.image_src}{/if}"/>
        </div>
    </div>
    {if $item_data.type=='category'}
    <div class="category_block item_overlay hide">
        <div class="item_overlay_item_header_text  " style="text-align: center" contenteditable="true">{$item_data.header_text|strip_tags}</div>
        <div class="button_container">
            <div class="flex-item category_code">{$item_data.category_code}</div>
            <div class="flex-item "><span class="number_products">{$item_data.number_products}</span> <i class="fa fa-cube" aria-hidden="true"></i></div>
            <div class="flex-item" style="border:none;width:40px"><i style="position: relative;top:-12px;margin-left: 5px" class="fa fa-window-close button close_category_block"></i></div>
        </div>
        <div class="button_container">
            <div onclick="$(this).closest('.wrap').remove();$('#save_button',window.parent.document).addClass('save button changed valid')" class="flex-item full delete button">{t}Delete{/t}</div>
        </div>
        <div class="button_container">
            <div class="flex-item full change_category_image button">
                <form method="post" _action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                    <input type="file" name="category_categories_category" id="file_upload_{$item_data.category_key}" class="image_upload hide" multiple
                           data-options='{ "scope":"category", "scope_key":"{$item_data.category_key}"}'/>
                    <label for="file_upload_{$item_data.category_key}">
                        <i class="fa  fa-image fa-fw button" aria-hidden="true"></i> {t}Change image{/t}
                    </label>
                </form>
            </div>
        </div>
    </div>
    {elseif $item_data.type=='product'}
        <div class="category_block item_overlay hide">
            <div class="item_overlay_item_header_text  " contenteditable="true">{$item_data.header_text|strip_tags}</div>
            <div class="button_container">
                <div class="flex-item product_code">{$item_data.product_code}</div>
                <div class="flex-item product_web_state " data-product_web_state="{$item_data.product_web_state}"></div>
                <div class="flex-item" style="border:none;width:40px"><i style="position: relative;top:-12px;margin-left: 5px" class="fa fa-window-close button close_category_block"></i></div>
            </div>
            <div class="button_container">
                <div onclick="$(this).closest('.wrap').remove();$('#save_button',window.parent.document).addClass('save button changed valid')" class="flex-item full delete button">{t}Delete{/t}</div>
            </div>
            <div class="button_container">
                <div class="flex-item full change_category_image button">
                    <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                        <input type="file" name="category_categories_category" id="file_upload_{$item_data.product_id}" class="image_upload hide" multiple
                               data-options='{ "scope":"category", "scope_key":"{$item_data.product_id}"}'/>
                        <label for="file_upload_{$item_data.product_id}">
                            <i class="fa  fa-image fa-fw button" aria-hidden="true"></i> {t}Change image{/t}
                        </label>
                    </form>
                </div>
            </div>
        </div>

    {/if}


</div>

