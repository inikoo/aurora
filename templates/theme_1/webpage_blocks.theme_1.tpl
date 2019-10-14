{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 July 2017 at 03:08:39 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.tpl"}



<body xmlns="http://www.w3.org/1999/html">


<style>


</style>

<div id="aux">



    <div id="text_block_style" class="hide object_control_panel element_for_color element_for_margins" style="padding: 0px;z-index: 3001;">



        <div class="handle" style="border-bottom: 1px solid #ccc;width: 100%;line-height: 30px;height: 30px">
            <i class="fa fa-window-close button padding_left_10" onclick="$('#text_block_style').addClass('hide')"></i>
        </div>
<div style="padding: 20px">
        <table >

            <tr>
                <td class="label">{t}Margin{/t}</td>
                <td class="margins_container unselectable margin" data-scope="margin">
                    <input data-margin="top" class=" edit_margin top" value=""  placeholder="0"><input data-margin="bottom" class=" edit_margin bottom" value="" style="" placeholder="0">
                    <input data-margin="left" class=" edit_margin left" value="" style="" placeholder="0"><input data-margin="right" class=" edit_margin right" value="" style="" placeholder="0">

                    <i class="fa fa-plus-circle padding_left_10 like_button up_margins"></i>
                    <i class="fa fa-minus-circle padding_left_5 like_button down_margins"></i>

                </td>
            </tr>

            <tr>
                <td class="label">{t}Padding{/t}</td>
                <td class="margins_container unselectable padding" data-scope="padding">
                    <input data-margin="top" class=" edit_margin top" value=""  placeholder="0"><input data-margin="bottom" class=" edit_margin bottom" value="" style="" placeholder="0">
                    <input data-margin="left" class=" edit_margin left" value="" style="" placeholder="0"><input data-margin="right" class=" edit_margin right" value="" style="" placeholder="0">

                    <i class="fa fa-plus-circle padding_left_10 like_button up_margins"></i>
                    <i class="fa fa-minus-circle padding_left_5 like_button down_margins"></i>


                </td>
            </tr>
            <tr>
                <td class="label">{t}Border{/t}</td>
                <td class="margins_container unselectable border border-width" data-scope="border">
                    <input data-margin="top-width" class=" edit_margin top" value=""  placeholder="0"><input data-margin="bottom-width" class=" edit_margin bottom" value="" style="" placeholder="0">
                    <input data-margin="left-width" class=" edit_margin left" value="" style="" placeholder="0"><input data-margin="right-width" class=" edit_margin right" value="" style="" placeholder="0">

                    <i class="fa fa-plus-circle padding_left_10 like_button up_margins"></i>
                    <i class="fa fa-minus-circle padding_left_5 like_button down_margins"></i>

                        <span data-scope="border-color" style="position: relative;top:-1.5px" class="fa-stack color_picker scope_border-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>

                </td>
            </tr>

            <tr>
                <td class="label">{t}Text{/t}</td>
                <td>
                     <span data-scope="color" class="fa-stack color_picker scope_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                </td>
            </tr>
            <tr>
                <td id="" class="label">{t}Background{/t}</td>
                <td>
                    <span data-scope="background-color" class="fa-stack color_picker scope_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>
                </td>
            </tr>


        </table>


</div>




    </div>

    <div id="color_picker_dialog" style="position:absolute;z-index: 6000" class="hide">
        <input type='text'  class="hide"  />

    </div>


    <div id="panel_txt_control" class="hide">
        <div class="panel_txt_control" style="">
            <i class="fa fa-expand" title="{t}Padding{/t}"></i> <input size="2" style="height: 16px;" value="20">
            <i onclick="delete_panel_text(this)" class="far fa-trash-alt padding_left_10 like_button" title="{t}Delete{/t}"></i>

            <i onclick="close_panel_text(this)" class="fa fa-window-close button" style="float: right;margin-top:6px" title="{t}Close text edit mode{/t}"></i>

        </div>
    </div>

    <div id="template_1" class="hide">
<span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/1240x250" alt="" data-width="1240" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

    </div>

    <div id="template_2" class="hide">
<span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/610x250" alt="" data-width="610" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/610x250" alt=""  data-width="610" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

    </div>


    <div id="template_3" class="hide">
<span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/400x250" alt=""  data-width="400" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/400x250" alt=""  data-width="400" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/400x250" alt=""  data-width="400" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    </div>



    <div id="template_4" class="hide">
<span class="image"   >
        <figure>
            <img class="button" src="https://placehold.it/300x250" alt=""  data-width="300" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250" alt="" data-width="300" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250" alt="" data-width="300" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250" alt="" data-width="300" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    </div>


    <div id="template_12" class="hide">
<span class="image" >
        <figure>
            <img class="button" src="https://placehold.it/400x250" data-width="400" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/800x250" data-width="800"  alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

    </div>

    <div id="template_21" class="hide">
<span class="image" >
        <figure>
            <img class="button" src="https://placehold.it/800x250" data-width="800"  alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/400x250" data-width="400"  alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

    </div>


    <div id="template_13" class="hide">
<span class="image"  >
        <figure>
            <img class="button" src="https://placehold.it/310x250" data-width="310"  alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/910x250"  data-width="910" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>


    </div>



    <div id="template_31" class="hide">
<span class="image"  >
        <figure>
            <img class="button" src="https://placehold.it/910x250"  data-width="910" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/310x250"  data-width="310" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>


    </div>



    <div id="template_211" class="hide">
<span class="image"  >
        <figure>
            <img class="button" src="https://placehold.it/600x250"  data-width="600" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250"  data-width="300" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250"  data-width="300" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

    </div>



    <div id="text_template_1" class="hide">
        <div class="text_block"><h1>Title</h1>When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also
            electronics typesetting, remaining
            essentially believable.
        </div>

    </div>
    <div id="text_template_2" class="hide">
        <div class="text_block"><h1>Title</h1>When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also electronics typesetting,
            remaining essentially believable.
        </div>
        <div class="text_block"><h1>Title</h1>When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also electronics typesetting,
            remaining essentially believable.
        </div>

    </div>
    <div id="text_template_3" class="hide">

        <div class="text_block"><h1>Title</h1>When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also electronics typesetting,
            remaining essentially believable.
        </div>
        <div class="text_block"><h1>Title</h1>When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also electronics typesetting,
            remaining essentially believable.
        </div>
        <div class="text_block"><h1>Title</h1>When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also electronics typesetting,
            remaining essentially believable.
        </div>


    </div>
    <div id="text_template_4" class="hide">
        <div class="text_block"><h1>Title</h1>When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also electronics typesetting,
            remaining essentially believable.
        </div>
        <div class="text_block"><h1>Title</h1>When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also electronics typesetting,
            remaining essentially believable.
        </div>
        <div class="text_block"><h1>Title</h1>When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also electronics typesetting,
            remaining essentially believable.
        </div>
        <div class="text_block"><h1>Title</h1>When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also electronics typesetting,
            remaining essentially believable.
        </div>

    </div>


    <div class="hide">
        <div id="image_layout_1">
        <span class=" image">
            <figure>
                <img class="button" src="https://placehold.it/300x250" alt="" display_class="caption_left">
                <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
            </figure>
        </span>
        </div>


    </div>


    <div id="simple_line_icons_control_center" class="input_container  hide   " style="">

        <div style="margin-bottom:5px"><i onClick="$(this).closest('div').addClass('hide')" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i></div>


        <div>{t}Contact{/t}</div>

        <div>

            <i icon="icon-phone" aria-hidden="true" class="icon-phone"></i>
            <i icon="icon-call-in" aria-hidden="true" class="icon-call-in"></i>
            <i icon="icon-speech" aria-hidden="true" class="icon-speech"></i>
            <i icon="icon-bubbles" aria-hidden="true" class="icon-bubbles"></i>
            <i icon="icon-printer" aria-hidden="true" class="icon-printer"></i>
            <i icon="icon-microphone" aria-hidden="true" class="icon-microphone"></i>
            <i icon="icon-earphones" aria-hidden="true" class="icon-earphones"></i>
            <i icon="icon-earphones-alt" aria-hidden="true" class="icon-earphones-alt"></i>
            <i icon="icon-social-facebook" aria-hidden="true" class="icon-social-facebook"></i>
            <i icon="icon-question" aria-hidden="true" class="icon-question"></i>
            <i icon="icon-info" aria-hidden="true" class="icon-info"></i>
            <i icon="icon-envelope" aria-hidden="true" class="icon-envelope"></i>
            <i icon="icon-support" aria-hidden="true" class="icon-support"></i>
            <i icon="icon-volume-1" aria-hidden="true" class="icon-volume-1"></i>


        </div>

        <div>{t}Store{/t}</div>
        <div>
            <i icon="icon-wallet" aria-hidden="true" class="icon-wallet"></i>
            <i icon="icon-calculator" aria-hidden="true" class="icon-calculator"></i>
            <i icon="icon-home" aria-hidden="true" class="icon-home"></i>
            <i icon="icon-login" aria-hidden="true" class="icon-login"></i>
            <i icon="icon-logout" aria-hidden="true" class="icon-logout"></i>
            <i icon="icon-directions" aria-hidden="true" class="icon-directions"></i>
            <i icon="icon-map" aria-hidden="true" class="icon-map"></i>
            <i icon="icon-compass" aria-hidden="true" class="icon-compass"></i>
            <i icon="icon-cursor" aria-hidden="true" class="icon-cursor"></i>
            <i icon="icon-trophy" aria-hidden="true" class="icon-trophy"></i>
            <i icon="icon-tag" aria-hidden="true" class="icon-tag"></i>
            <i icon="icon-bulb" aria-hidden="true" class="icon-bulb"></i>

            <i icon="icon-present" aria-hidden="true" class="icon-present"></i>
            <i icon="icon-handbag" aria-hidden="true" class="icon-handbag"></i>
            <i icon="icon-globe" aria-hidden="true" class="icon-globe"></i>
            <i icon="icon-drawer" aria-hidden="true" class="icon-drawer"></i>
            <i icon="icon-basket" aria-hidden="true" class="icon-basket"></i>
            <i icon="icon-bag" aria-hidden="true" class="icon-bag"></i>
            <i icon="icon-credit-card" aria-hidden="true" class="icon-credit-card"></i>
            <i icon="icon-paypal" aria-hidden="true" class="icon-paypal"></i>
            <i icon="icon-social-dropbox" aria-hidden="true" class="icon-social-dropbox"></i>

        </div>
        <div>{t}Other{/t}</div>
        <div>
            <i icon="icon-cup" aria-hidden="true" class="icon-cup"></i>
            <i icon="icon-emotsmile" aria-hidden="true" class="icon-emotsmile"></i>
            <i icon="icon-layers" aria-hidden="true" class="icon-layers"></i>
            <i icon="icon-plus" aria-hidden="true" class="icon-plus"></i>
            <i icon="icon-minus" aria-hidden="true" class="icon-minus"></i>
            <i icon="icon-close" aria-hidden="true" class="icon-close"></i>
            <i icon="icon-exclamation" aria-hidden="true" class="icon-exclamation"></i>
            <i icon="icon-event" aria-hidden="true" class="icon-event"></i>
            <i icon="icon-plane" aria-hidden="true" class="icon-plane"></i>
            <i icon="icon-mustache" aria-hidden="true" class="icon-mustache"></i>
            <i icon="icon-chemistry" aria-hidden="true" class="icon-chemistry"></i>
            <i icon="icon-speedometer" aria-hidden="true" class="icon-speedometer"></i>
            <i icon="icon-pin" aria-hidden="true" class="icon-pin"></i>
            <i icon="icon-umbrella" aria-hidden="true" class="icon-umbrella"></i>
            <i icon="icon-rocket" aria-hidden="true" class="icon-rocket"></i>
            <i icon="icon-graph" aria-hidden="true" class="icon-graph"></i>
            <i icon="icon-like" aria-hidden="true" class="icon-like"></i>
            <i icon="icon-settings" aria-hidden="true" class="icon-settings"></i>
            <i icon="icon-lock" aria-hidden="true" class="icon-lock"></i>
            <i icon="icon-star" aria-hidden="true" class="icon-star"></i>
            <i icon="icon-heart" aria-hidden="true" class="icon-heart"></i>

        </div>

    </div>

    <div id="video_control_panel" class="hide object_control_panel" style="width: 470px">
        <div style="margin-bottom: 10px;padding-right: 5px">
            <i class="fa fa-window-close button" onclick="close_video_control_panel()"></i>
        </div>

        <table>

            <tr>
                <td class="label"><i style="color:red" class="fab fa-youtube padding_right_5" title="Youtube"></i> {t}Video Id{/t}</td><td><input class="video_link" style="width: 200px" placeholder="M7lc1UVf-VE">  <i onClick="update_video()" class="fa fa-check link_button button padding_left_5"></i> </td>
            </tr>

            <tr>
                <td class="label"></td><td><span onclick="delete_video()" class="button unselectable"><i class="fa fa-trash"></i> {t}Delete{/t}</span></td>
            </tr>
        </table>



    </div>


    <div id="image_control_panel" class="hide object_control_panel">
        <div style="margin-bottom: 10px;padding-right: 5px">
            <i class="fa fa-window-close button" onclick="update_image()"></i>
        </div>

        <table>
            <tr>
                <td class="label">{t}Image{/t}</td>
                <td class="image_control_panel_upload_td">
                    <input style="display:none" type="file" name="images" id="update_images_block_image" class="image_upload_from_iframe"

                           data-parent="Webpage" data-parent_key="{$webpage->id}" data-parent_object_scope="Image" data-metadata='{ "block":"button"}'  data-options='{ "min_width":"1240","min_height":"750"}'  data-response_type="webpage"

                    />
                    <label style="font-weight: normal;cursor: pointer;width:100%"  for="update_images_block_image">
                        {t}Upload image{/t} <span class="image_size"></span> <i class="hide fa fa-check success" aria-hidden="true"></i>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="label">{t}Tooltip{/t}</td><td><input class="image_tooltip" style="width: 200px" placeholder="tooltip"></td>
            </tr>
            <tr>
                <td class="label">{t}Link{/t}</td><td><input class="image_link" style="width: 200px" placeholder="https://"></td>
            </tr>

            <tbody class="caption_tr">
            <tr>
                <td class="label">{t}Caption{/t}</td><td><input class="image_caption" style="width: 200px" placeholder="{t}caption{/t}"></td>
            </tr>
            <tr >
                <td class="label">{t}Caption style{/t}</td>
                <td class="caption_align">
                    <i class="fa fa-align-left super_discreet caption_left" display_class="caption_left" aria-hidden="true"></i>
                    <i class="fa fa-align-center super_discreet caption_center" display_class="caption_center" aria-hidden="true"></i>
                    <i class="fa fa-align-right super_discreet caption_right" display_class="caption_right" aria-hidden="true"></i>
                    <i class="fa fa-ban error super_discreet caption_hide" display_class="caption_hide" aria-hidden="true"></i>
                </td>
            </tr>

            </tbody>
            <tr>
                <td class="label"></td><td><span onclick="delete_image()" class="button unselectable"><i class="fa fa-trash"></i> {t}Delete{/t}</span></td>
            </tr>
        </table>



    </div>

</div>

<div class="wrapper_boxed">
    <div  class="site_wrapper" data-webpage_key="{$webpage->id}">


        {if $navigation.show}
        <div class="navigation top_body" >
            {foreach from=$navigation.breadcrumbs item=$breadcrumb name=breadcrumbs}
                <span class="breadcrumbs">{$breadcrumb.label} {if !$smarty.foreach.breadcrumbs.last}<i class="fas padding_left_10 padding_right_10 fa-angle-double-right"></i>{/if}</span>

            {/foreach}
            <div style="" class="nav"><i class="fas fa-arrow-left"></i>  <i style="" class="fas fa-arrow-right next"></i></div>
            <div style="clear:both"></div>
        </div>
        {/if}

        {if isset($discounts) and count($discounts.deals)>0 }

            <div class="discounts top_body" >
            {foreach from=$discounts.deals item=deal_data }
            <div class="discount_card" data-key="{$deal_data.key}" >
                <div class="discount_icon" style="">{$deal_data.icon}</div>
                <span contenteditable="true" class="discount_name">{$deal_data.name}</span>
                {if  $deal_data.until!=''}<small class="padding_left_10"><span id="_offer_valid_until" class="website_localized_label" contenteditable="true">{if !empty($labels._offer_valid_until)}{$labels._offer_valid_until}{else}{t}Valid until{/t}{/if}</span>: {$deal_data.until_formatted}{/if}</small>
                <br/>
                <span contenteditable="true" class="discount_term">{$deal_data.term}</span>
                <span contenteditable="true" class="discount_allowance">{$deal_data.allowance}</span>
            </div>
            {/foreach}<div style="clear:both"></div>
            </div>
        {/if}


        <div id="blocks" data-webpage_key="{$webpage->id}">
        {foreach from=$content.blocks item=$block key=key}
            {include file="{$theme}/blk.{$block.type}.{$theme}.tpl" data=$block key=$key  }
        {/foreach}
        </div>
    </div>

</div>


<script>


    $(document).delegate('a', 'click', function (e) {

        return false
    })


    $("form").on('submit', function (e) {

        e.preventDefault();
        e.returnValue = false;

    });




    {foreach from=$content.blocks item=$block key=key}
    {if $block.type=='one_pack' or  $block.type=='two_pack'   }
    set_up_froala_editor('block_{$key}_editor')
    {elseif $block.type=='thanks'}

    set_up_froala_editor('thanks_text_{$key}');
    {elseif $block.type=='product'}

    set_up_froala_editor('_product_description');

    {elseif $block.type=='favourites'}

    set_up_froala_editor('block_{$key}_with_items_editor');
    set_up_froala_editor('block_{$key}_no_items_editor');

    {elseif $block.type=='text'}


    $("#block_{$key} .text_block").each(function () {

        console.log($(this))
        set_up_froala_editor('block_{$key}_'+$(this).data('text_block_key')+'_editor')
    });

    {elseif $block.type=='static_banner'}

    create_static_banner('{$key}')

    {elseif $block.type=='images'}

        set_up_images('{$key}')
    {elseif $block.type=='products'}

    $( "#block_{$key} .products " ).sortable({
        cancel: ".sortable-disabled",

        update:function (event, ui) {
            $('#save_button',window.parent.document).addClass('save button changed valid')



        },

    })

    {elseif $block.type=='category_products'}

    var product_sort_index=[];
    $('#block_{$key} .products .type_product').each(function (i, product) {
        $(product).uniqueId()
        product_sort_index.push($(product).attr('id'))
    })



    $( "#block_{$key} .products " ).sortable({
        cancel: ".sortable-disabled",

        update:function (event, ui) {
            $('#save_button',window.parent.document).addClass('save button changed valid')

            if($('.products').data('sort')!='Manual'){



                var new_product_sort_index=[];
                $('#block_{$key} .products .type_product').each(function (i, product) {

                    new_product_sort_index.push($(product).attr('id'))
                })

                //console.log(product_sort_index)

                //console.log(new_product_sort_index)

                if(! product_sort_index.every(function(v,i) { return v === new_product_sort_index[i]})){
                    console.log('changed sort')

                    $('.products').data('sort','Manual')
                    parent.category_products_change_sort_to_manual()

                }


            }

        },

    })





    {elseif $block.type=='blackboard'}

        set_up_blackboard('{$key}')
        {foreach from=$block.images item=image}


    setTimeout(function(){
        set_up_blackboard_image('{$image.id}')
    }, 1000);



    $('#{$image.id} img ').on('load', function(){

        set_up_blackboard_image('{$image.id}')

    });

        {/foreach}
        {foreach from=$block.texts item=text}
            set_up_blackboard_text('{$text.id}')
        {/foreach}

    {/if}

    {/foreach}


    document.addEventListener("paste", function (e) {
        e.preventDefault();
        var text = e.clipboardData.getData("text/plain");
        document.execCommand("insertHTML", false, text);
    });


    $(document).ready(function () {
        resize_banners();
    });

    $(window).resize(function () {
        resize_banners();

    });

    function resize_banners() {
        $('.iframe').each(function (i, obj) {
            $(this).css({
                height: $(this).width() * $(this).attr('h') / $(this).attr('w')})
        });
    }


    $(document).on('click', '.simple_line_item_icon', function (e) {


        $('#simple_line_icons_control_center').removeClass('hide').offset({
            top: $(this).offset().top - 69, left: $(this).offset().left + $(this).width()
        }).data('item', $(this))


    })

    $('#simple_line_icons_control_center').on('click', 'i', function (e) {

        //console.log($('#icons_control_center').data('item'))


        var input_container = $('#simple_line_icons_control_center')
        var icon = input_container.data('item')

        icon.removeClass(function (index, className) {


            return (className.match(/\bicon-\S+/g) || []).join(' ');
        }).addClass($(this).attr('icon'))


        icon.attr('icon', $(this).attr('icon'))


        input_container.addClass('hide')


        $('#save_button', window.parent.document).addClass('save button changed valid')


    })


    function move_block(pre, post) {

        if (post > pre) {
            $('#blocks ._block:eq(' + pre + ')').insertAfter('#blocks ._block:eq(' + post + ')');
        } else {
            $('#blocks ._block:eq(' + pre + ')').insertBefore('#blocks ._block:eq(' + post + ')');
        }
    }


    function save() {

        if (!$('#save_button', window.parent.document).hasClass('save')) {
            return;
        }

        $('#save_button', window.parent.document).find('i').addClass('fa-spinner fa-spin')


        content_data = { };

        var poll_position=[];
        var blocks = []
        var labels = {

        };

        var  poll_labels = { };

        var discounts_data= { };



        $('.discount_card').each(function (i, obj) {


            $('.website_localized_label', obj).each(function (i, obj2) {





                    labels[$(obj2).attr('id')] = $(obj2).html()




            })

            discounts_data[$(obj).data('key')]={
                'name':$(obj).find('.discount_name').html(),
                'term':$(obj).find('.discount_term').html(),
                'allowance':$(obj).find('.discount_allowance').html()

            }
        })


        console.log(labels)



        $('._block').each(function (i, obj) {



            switch ($(obj).attr('block')) {

                case 'reviews':

                    content_data = {

                    };


                    $('[contenteditable=true]', obj).each(function (i, obj2) {


                        content_data[$(obj2).attr('id')] = $(obj2).html()



                    })



                    blocks.push({
                        type: 'reviews',
                        label: '{t}Reviews{/t}',
                        icon: 'fa-comment-smile',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        html: $(obj).html()

                    })


                    break;

                case 'unsubscribe':

                    content_data = {

                    };


                    $('[contenteditable=true]', obj).each(function (i, obj2) {


                            content_data[$(obj2).attr('id')] = $(obj2).html()



                    })



                    blocks.push({
                        type: 'unsubscribe',
                        label: '{t}Unsubscribe{/t}',
                        icon: 'fa-comment-slash',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data

                    })


                    break;

                case 'reset_password':


                    content_data = {

                    };


                    $('[contenteditable=true]', obj).each(function (i, obj2) {


                            content_data[$(obj2).attr('id')] = $(obj2).html()



                    })



                    $('.label_field').each(function (i, obj) {
                        content_data[$(obj).attr('id')] = $(obj).val()
                    })

                    $('.tooltip', obj).each(function (i, obj2) {
                        if ($(obj2).attr('id') != undefined) content_data[$(obj2).attr('id')] = $(obj2).html()
                    })


                    $('.website_localized_label', obj).each(function (i, obj2) {
                        if ($(obj2).val() != '') {
                            labels[$(obj2).attr('id')] = $(obj2).val()


                        }

                    })


                    blocks.push({
                        type: 'reset_password',
                        label: '{t}Reset password{/t}',
                        icon: 'fa-lock-open-alt',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data

                    })


                    break;


                case 'profile':


                    content_data = {

                    };


                    $('[contenteditable=true]', obj).each(function (i, obj2) {

                        if ($(obj2).hasClass('poll_query_label')) {
                            poll_labels[$(obj2).data('query_key')] = base64_url_encode($(obj2).html())
                        } else {
                            content_data[$(obj2).attr('id')] = $(obj2).html()
                        }


                    })

                    $('.poll_query_label', obj).each(function (i, obj2) {
                        poll_position.push($(obj2).data('query_key'))
                    })


                    $('.register_field', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).attr('placeholder')
                    })


                    $('.tooltip', obj).each(function (i, obj2) {
                        if ($(obj2).attr('id') != undefined) content_data[$(obj2).attr('id')] = $(obj2).html()
                    })


                    $('.website_localized_label', obj).each(function (i, obj2) {
                        if ($(obj2).val() != '') {
                            labels[$(obj2).attr('id')] = $(obj2).val()


                        }

                    })


                    blocks.push({
                        type: 'profile',
                        label: '{t}Profile{/t}',
                        icon: 'fa-user',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data

                    })


                    break;


                case 'checkout':


                    var content_data = {

                    }


                    $('[contenteditable=true]', obj).each(function (i, obj2) {

                        if ($(obj2).hasClass('website_localized_label')) {
                            labels[$(obj2).attr('id')] = $(obj2).html()
                        } else {
                            content_data[$(obj2).attr('id')] = $(obj2).html()
                        }


                    })


                    content_data['_credit_card_number'] = $('#_credit_card_number').val()
                    content_data['_credit_card_ccv'] = $('#_credit_card_ccv').val()
                    content_data['_credit_card_expiration_date_month_label'] = $('#_credit_card_expiration_date_month_label').val()
                    content_data['_credit_card_expiration_date_year_label'] = $('#_credit_card_expiration_date_year_label').val()


                    blocks.push({
                        type: 'checkout',
                        label: '{t}Checkout{/t}',
                        icon: 'fa-credit-card',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data

                    })

                    break;


                case 'thanks':

                    blocks.push({
                        type: 'thanks',
                        label: '{t}Thanks{/t}',
                        icon: 'fa-thumbs-up',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        text: $(obj).find('.thanks_text').froalaEditor('html.get')

                    })


                    break;

                case 'favourites':


                    var content_data = {
                        'with_items': $(obj).find('.with_items').froalaEditor('html.get'), 'no_items': $(obj).find('.no_items').froalaEditor('html.get')
                    }


                    blocks.push({
                        type: 'favourites',
                        label: '{t}Favourites{/t}',
                        icon: 'fa-heart',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data

                    })


                    break;

                case 'register':


                    var content_data = {

                    }





                    $('[contenteditable=true]', obj).each(function (i, obj2) {


                        if ($(obj2).hasClass('poll_query_label')) {
                            poll_labels[$(obj2).data('query_key')] = base64_url_encode($(obj2).html())
                        } else {
                            content_data[$(obj2).attr('id')] = $(obj2).html()
                        }

                    })


                    $('.poll_query_label', obj).each(function (i, obj2) {
                        poll_position.push($(obj2).data('query_key'))
                    })





                    $('.register_field', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).attr('placeholder')
                    })


                    $('.tooltip', obj).each(function (i, obj2) {
                        if ($(obj2).attr('id') != undefined) content_data[$(obj2).attr('id')] = $(obj2).html()
                    })

                    $('.website_localized_label', obj).each(function (i, obj2) {
                        if ($(obj2).val() != '') {
                            content_data[$(obj2).attr('id')] = $(obj2).val()


                        }

                    })


                    blocks.push({
                        type: 'register',
                        label: '{t}Registration form{/t}',
                        icon: 'fa-registered',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data,


                    })


                    break;


                case 'login':


                    var content_data = {

                    }

                    $('[contenteditable=true]', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).html()
                    })


                    $('.register_field', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).attr('placeholder')
                    })


                    $('.tooltip', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).html()
                    })

                    blocks.push({
                        type: 'login',
                        label: '{t}Login{/t}',
                        icon: 'fa-sign-in-alt',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data

                    })


                    break;

                case 'product':


                    var img = $(obj).find('.main_image')

                    var gallery = []


                    $('.gallery figure', obj).each(function (i, gallery_image) {


                        gallery.push({
                            src: $(gallery_image).data('src'), caption: $(gallery_image).data('caption'), key: $(gallery_image).data('key'),

                            width: $(gallery_image).data('width'), height: $(gallery_image).data('height'), image_website: $(gallery_image).data('image_website'),

                        })

                    });
                    text_block=$(obj).find('.product_description_block')

                    if ($(text_block).hasClass('fr-box')) {
                        var text = $(text_block).froalaEditor('html.get')
                    } else {
                        var text = $(text_block).html()
                    }

                    blocks.push({
                        type: 'product',
                        label: '{t}Product{/t}',
                        icon: 'fa-cube',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        text: text,


                        image: {
                            'key': $(img).data('key'), 'src': $(img).data('src'), 'caption': $(img).data('caption'),

                            'width': $(img).data('width'), 'height': $(img).data('height'), 'image_website': $(img).data('image_website'),
                        },


                        other_images: gallery,

                    })

                    break;


                case 'see_also':

                    var items = []
                    $('.wrap  ', obj).each(function (j, item) {


                        var img = $(item).find('.wrap_to_center img')




                        if ($(item).data('type') == 'category') {

                            items.push({
                                type: $(item).data('type'),
                                category_key: $(item).find('.category_block').data('item_key'),
                                webpage_key: $(item).find('.category_block').data('webpage_key'),
                                link: $(item).find('.category_block').data('link'),
                                webpage_code: $(item).find('.category_block').data('webpage_code'),


                                header_text: $(item).find('.item_header_text').html(),
                                image_src: img.data('src'),
                                image_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_website')),
                                image_mobile_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_mobile_website')),


                                category_code: $(item).find('.category_code').html(),
                                number_products: $(item).find('.number_products').html(),

                            })

                        } else if ($(item).data('type') == 'product') {

                            items.push({
                                type: $(item).data('type'),
                                product_id: $(item).find('.category_block').data('item_key'),
                                webpage_key: $(item).find('.category_block').data('webpage_key'),
                                link: $(item).find('.category_block').data('link'),
                                webpage_code: $(item).find('.category_block').data('webpage_code'),


                                header_text: $(item).find('.item_header_text').html(),
                                image_src: img.data('src'),
                                image_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_website')),
                                image_mobile_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_mobile_website')),


                                product_code: $(item).find('.product_code').html(),
                                product_web_state: $(item).find('.product_web_state').data('product_web_state'),

                            })

                        }


                    })

                    blocks.push({
                        type: 'see_also',
                        label: '{t}See also{/t}',
                        icon: 'fa-link',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        items: items,
                        auto: $(obj).find('.see_also').data('auto'),
                        auto_scope: $(obj).find('.see_also').data('auto_scope'),
                        auto_last_updated: $(obj).find('.see_also').data('auto_last_updated'),
                        auto_items: $(obj).find('.see_also').children().length,
                        show_title: ($(obj).find('.products_title').hasClass('hide') ? false : true),
                        title: $(obj).find('.products_title').html(),


                    })

                    break;


                case 'products':

                    var items = []
                    $('.wrap  ', obj).each(function (j, item) {


                        var img = $(item).find('.wrap_to_center img')

                        var txt = $(item).find('.product_header_text')

                        if ($(item).find('.panel_txt_control').hasClass('hide')) {
                            var header_text = txt.html()
                        } else {
                            var header_text = txt.froalaEditor('html.get')
                        }


                        items.push({
                            type: $(item).data('type'),
                            product_id: $(item).find('.product_block').data('product_id'),
                            web_state: $(item).find('.product_block').data('web_state'),
                            price: $(item).find('.product_block').data('price'),
                            rrp: $(item).find('.product_block').data('rrp'),
                            code: $(item).find('.product_block').data('code'),
                            name: $(item).find('.product_block').data('name'),
                            link: $(item).find('.product_block').data('link'),
                            webpage_code: $(item).find('.product_block').data('webpage_code'),
                            webpage_key: $(item).find('.product_block').data('webpage_key'),
                            out_of_stock_class: $(item).find('.product_block').data('out_of_stock_class'),
                            out_of_stock_label: $(item).find('.product_block').data('out_of_stock_label'),

                            sort_code: $(item).data('sort_code'),
                            sort_name: $(item).data('sort_name'),

                            image_src: img.data('src'),
                            image_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_website')),
                            image_mobile_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_mobile_website')),

                            header_text: header_text


                        })


                    })


                    blocks.push({
                        type: 'products',
                        label: '{t}Products{/t}',
                        icon: 'fa-window-restore',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        sort: $(obj).find('.products').data('sort'),
                        item_headers: ($(obj).find('.products').hasClass('no_items_header') ? false : true),
                        show_title: ($(obj).find('.products_title').hasClass('hide') ? false : true),
                        title: $(obj).find('.products_title').html(),
                        items: items
                    })
                    break;

                case 'category_products':

                    var items = []
                    $('.wrap  ', obj).each(function (j, item) {


                        switch ($(item).data('type')) {
                            case 'product':

                                var img = $(item).find('.wrap_to_center img')

                                var txt = $(item).find('.product_header_text')

                                if ($(item).find('.panel_txt_control').hasClass('hide')) {
                                    var header_text = txt.html()
                                } else {
                                    var header_text = txt.froalaEditor('html.get')
                                }


                                header_text = $.trim(header_text)
                                // console.log(header_text)
                                items.push({
                                    type: $(item).data('type'),
                                    product_id: $(item).find('.product_block').data('product_id'),
                                    web_state: $(item).find('.product_block').data('web_state'),
                                    price: $(item).find('.product_block').data('price'),
                                    rrp: $(item).find('.product_block').data('rrp'),
                                    code: $(item).find('.product_block').data('code'),
                                    name: $(item).find('.product_block').data('name'),
                                    link: $(item).find('.product_block').data('link'),
                                    webpage_code: $(item).find('.product_block').data('webpage_code'),
                                    webpage_key: $(item).find('.product_block').data('webpage_key'),
                                    out_of_stock_class: $(item).find('.product_block').data('out_of_stock_class'),
                                    out_of_stock_label: $(item).find('.product_block').data('out_of_stock_label'),

                                    sort_code: $(item).data('sort_code'),
                                    sort_name: $(item).data('sort_name'),

                                    image_src: img.data('src'),
                                    image_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_website')),
                                    image_mobile_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_mobile_website')),

                                    header_text: header_text


                                })
                                break;
                            case 'video':


                                var video = $(item).find('.video')
                                if (video.attr('video_id') != '') {
                                    items.push({
                                        type: $(item).data('type'), video_id: video.attr('video_id'), size_class: video.attr('size_class')


                                    })

                                }


                                break;
                            case 'image':


                                var img = $(item).find('img')
                                items.push({
                                    type: $(item).data('type'),

                                    image_src: img.data('src'), image_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_website')),

                                    link: img.attr('link'), title: img.attr('alt'), size_class: img.attr('size_class'),


                                })
                                break;
                            case 'text':

                                var txt = $(item).find('.txt')

                                if ($(item).find('.panel_txt_control').hasClass('hide')) {
                                    var text = txt.html()
                                } else {
                                    var text = txt.froalaEditor('html.get')
                                }


                                items.push({
                                    type: $(item).data('type'), text: text, padding: txt.data('padding'), size_class: txt.attr('size_class'),


                                })
                                break;
                        }


                    })


                    blocks.push({
                        type: 'category_products',
                        label: '{t}Family{/t}',
                        icon: 'fa-cubes',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        sort: $(obj).find('.products').data('sort'),
                        item_headers: ($(obj).find('.products').hasClass('no_items_header') ? false : true),
                        items: items
                    })
                    break;
                case 'category_categories':

                    var sections = []
                    $('.section  ', obj).each(function (i, section) {

                        var items = []
                        $('.category_wrap  ', section).each(function (j, item) {


                            switch ($(item).data('type')) {
                                case 'category':

                                    var img = $(item).find('.wrap_to_center img')

                                    items.push({
                                        type: $(item).data('type'),
                                        category_key: $(item).find('.category_block').data('category_key'),
                                        webpage_key: $(item).find('.category_block').data('category_webpage_key'),
                                        item_type: $(item).find('.category_block').data('item_type'),
                                        link: $(item).find('.category_block').data('link'),
                                        webpage_code: $(item).find('.category_block').data('webpage_code'),


                                        header_text: $(item).find('.item_header_text').html(),
                                        image_src: img.data('src'),
                                        image_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_website')),
                                        image_mobile_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_mobile_website')),


                                        category_code: $(item).find('.category_code').html(),
                                        number_products: $(item).find('.number_products').html(),

                                    })
                                    break;
                                case 'image':


                                    var img = $(item).find('img')
                                    items.push({
                                        type: $(item).data('type'),

                                        image_src: img.data('src'), image_website: (img.attr('src') != 'EcomB2B/' + img.data('image_website') ? '' : img.data('image_website')),

                                        link: img.attr('link'), title: img.attr('alt'), size_class: img.attr('size_class'),


                                    })
                                    break;
                                case 'text':

                                    var txt = $(item).find('.txt')

                                    if ($(item).find('.panel_txt_control').hasClass('hide')) {
                                        var text = txt.html()
                                    } else {
                                        var text = txt.froalaEditor('html.get')
                                    }


                                    items.push({
                                        type: $(item).data('type'), text: text, padding: txt.data('padding'), size_class: txt.attr('size_class'),


                                    })
                                    break;
                            }


                        })


                        sections.push({
                            type: ($(section).hasClass('anchor') ? 'anchor' : 'non_anchor'),
                            title: ($(section).hasClass('anchor') ? '' : $(section).find('.title').html()),
                            subtitle: ($(section).hasClass('anchor') ? '' : $(section).find('.sub_title').html()),
                            items: items

                        })
                    });

                    blocks.push({
                        type: 'category_categories',
                        label: '{t}Department{/t}',
                        icon: 'fa-th',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        sections: sections
                    })

                    break;
                case 'blackboard':
                    var images = []
                    var texts = []

                    $('.blackboard_image ', obj).each(function (i, image_block) {

                        var img = $(image_block).find('img')

                            if(img.length && img.height()>0 && img.width()>0 ){
                                console.log(img)

                                images.push({
                                    id: $(image_block).attr('id'),

                                    src: img.data('src'), image_website: ((img.attr('src') != 'EcomB2B/' + img.data('image_website') || img.width() != img.data('width')) ? '' : img.data('image_website')),

                                    link: img.attr('link'), title: img.attr('alt'), width: img.width(), height: img.height(), top: img.offset().top - $(obj).offset().top - $(obj).attr('top_margin'), left: img.offset().left
                                })

                            }



                    });

                    $('.blackboard_text ', obj).each(function (i, text_block) {

                        if ($(text_block).hasClass('froala_on')) {
                            var text = $(text_block).froalaEditor('html.get')
                        } else {
                            var text = $(text_block).html()
                        }


                        var _text = ''
                        $(text).each(function (index) {
                            if (!$(this).is(':empty')) {

                                _text = _text + $(this).clone().wrap('<p>').parent().html();
                            }
                        });

                        //console.log($(text))

                        texts.push({
                            id: $(text_block).attr('id'),
                            text: _text,
                            width: $(text_block).width(),
                            height: $(text_block).height(),
                            top: $(text_block).offset().top - $(obj).offset().top - $(obj).attr('top_margin'),
                            left: $(text_block).offset().left
                        })
                    });


                    blocks.push({
                        type: 'blackboard',
                        label: '{t}Blackboard{/t}',
                        icon: 'fa-image',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        height: $('.blackboard').height(),
                        images: images,
                        texts: texts
                    })
                    break;

                case 'text':


                    var text_blocks = []






                    $('.text_block', obj).each(function (i, text_block) {


                        styles={
                            'margin-top':$(text_block).css('margin-top'),
                            'margin-bottom':$(text_block).css('margin-bottom'),
                            'margin-left':$(text_block).css('margin-left'),
                            'margin-right':$(text_block).css('margin-right'),
                            'padding-top':$(text_block).css('padding-top'),
                            'padding-bottom':$(text_block).css('padding-bottom'),
                            'padding-left':$(text_block).css('padding-left'),
                            'padding-right':$(text_block).css('padding-right'),
                            'border-top-width':$(text_block).css('border-top-width'),
                            'border-bottom-width':$(text_block).css('border-bottom-width'),
                            'border-left-width':$(text_block).css('border-left-width'),
                            'border-right-width':$(text_block).css('border-right-width'),
                            'background-color':$(text_block).css('background-color'),
                            'color':$(text_block).css('color'),
                            'border-color':$(text_block).css('border-color'),
                        }


                        var text = $(text_block).froalaEditor('html.get')
                        text_blocks.push({
                            text: text,
                            styles: styles
                        })

                    });

                    blocks.push({
                        type: 'text', label: '{t}Text{/t}', icon: 'fa-font', show: ($(obj).hasClass('hide') ? 0 : 1), top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),

                        template: $(obj).find('.text_blocks').data('template'), text_blocks: text_blocks,

                    })

                    break;

                case 'images':
                    var images = []

                    $('.blk_images .image', obj).each(function (i, col) {

                        var img = $(col).find('img')

                        _col = {
                            src: img.attr('src'), link: img.attr('link'), title: img.attr('alt'), caption_class: img.attr('display_class'), caption: $(col).find('figcaption').html(), width: img.data('width')
                        }


                        //  console.log(_col)

                        images.push(_col)

                    });


                    blocks.push({
                        type: 'images', label: '{t}Images{/t}', icon: 'fa-photo', show: ($(obj).hasClass('hide') ? 0 : 1), top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),

                        images: images, template: $(obj).find('.blk_images').attr('template'),
                    })
                    break;

                case 'not_found':


                    content_data = {

                    };


                    $('[contenteditable=true]', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).html()
                    })

                    blocks.push({
                        type: 'not_found', label: '{t}Not found{/t}', icon: 'fa-times-octagon', show: ($(obj).hasClass('hide') ? 0 : 1), top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),


                        labels: content_data,

                    })

                    break;

                case 'offline':


                    content_data = {

                    };


                    $('[contenteditable=true]', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).html()
                    })

                    blocks.push({
                        type: 'offline', label: '{t}Offline page{/t}', icon: 'fa-ban', show: ($(obj).hasClass('hide') ? 0 : 1), top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),


                        labels: content_data,

                    })

                    break;
                case 'in_process':


                    content_data = {

                    };


                    $('[contenteditable=true]', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).html()
                    })

                    blocks.push({
                        type: 'in_process', label: '{t}Under construction{/t}', icon: 'fa-seedling', show: ($(obj).hasClass('hide') ? 0 : 1), top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),


                        labels: content_data,

                    })

                    break;

                case 'basket':


                    var content_data = {
                        type: 'basket', label: '{t}Basket{/t}', icon: 'fa-basket', show: 1,

                        top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),

                    }

                    $('[contenteditable=true]').each(function (i, obj2) {

                        if ($(obj2).hasClass('website_localized_label')) {

                            labels[$(obj2).attr('id')] = $(obj2).html()
                        } else {
                            content_data[$(obj2).attr('id')] = $(obj2).html()
                        }


                    })


                    content_data['_voucher'] = $('#_voucher').val()
                    content_data['_special_instructions'] = $('#_special_instructions').val()


                    blocks.push(content_data)


                    break;


                case 'iframe':


                    blocks.push({
                        type: 'iframe',
                        label: 'iFrame',
                        icon: 'fa-window-restore',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        height: $(obj).attr('h'),
                        src: $(obj).find('iframe').attr('src').replace(/(^\w+:|^)\/\//, ''),
                        height_mobile: $(obj).attr('h_mobile'),
                        src_mobile: $(obj).attr('src_mobile').replace(/(^\w+:|^)\/\//, '')
                    })

                    //console.log($(obj))

                    break;


                case 'button':


                    blocks.push({
                        type: 'button', label: '{t}Button{/t}', icon: 'fa-hand-pointer', show: ($(obj).hasClass('hide') ? 0 : 1),

                        title: $(obj).find('._title').html(), text: $(obj).find('._text').html(), button_label: $(obj).find('._button').html(),

                        link: $(obj).find('._button').attr('link'),

                        bg_color: '',


                        bg_image: $(obj).find('.button_block').attr('button_bg'),

                        text_color: '', button_bg_color: '', button_text_color: '',


                    })


                    break;
                case 'telephone':

                    blocks.push({
                        type: 'telephone', label: '{t}Telephone{/t}', icon: 'fa-phone', show: ($(obj).hasClass('hide') ? 0 : 1),

                        _title: $(obj).find('._title').html(), _text: $(obj).find('._text').html(), _telephone: $(obj).find('._telephone').html(),

                    })

                    break;



                case 'static_banner':

                    blocks.push({
                        type: 'static_banner',
                        label: '{t}Header{/t}',
                        icon: 'fa-header',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        _top_text_left: 'customize',
                        _top_text_right: 'your own',
                        _title: 'Chic &amp; Unique Header',
                        _text: 'in easy peasy steps :)',
                        link: '',
                        bg_image: '',


                    })

                    break;


                    break;

                case 'image':

                    blocks.push({
                        type: 'image',
                        label: '{t}Image{/t}',
                        icon: 'fa-image',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        tooltip: $(obj).find('img').attr('title'),
                        link: $(obj).find('img').attr('link'),
                        src: $(obj).find('img').attr('src')
                    })

                    break;

                case 'six_pack':

                    var columns = []


                    $('._col', obj).each(function (i, col) {


                        var _col = []
                        $('._row', col).each(function (j, row) {

                            var _row = {
                                icon: $(row).find('.six_pack_icon').attr('icon'), title: $(row).find('.six_pack_title').html(), text: $(row).find('.six_pack_text').html(),

                            }

                            _col.push(_row)


                        });

                        columns.push(_col)

                    });


                    blocks.push({
                        type: 'six_pack', label: '{t}Siz-Pack{/t}', icon: 'fa-th-large', show: ($(obj).hasClass('hide') ? 0 : 1), columns: columns

                    })

                    break;

                case 'counter':

                    var columns = []


                    $('._counter', obj).each(function (i, col) {


                        _col = {
                            label: $(col).find('h4').html(), number: $(col).attr('number'), link: $(col).attr('link')
                        }


                        columns.push(_col)

                    });


                    blocks.push({
                        type: 'counter', label: '{t}Counter{/t}', icon: 'fa-sort-numeric-down', show: ($(obj).hasClass('hide') ? 0 : 1), columns: columns

                    })

                    break;

                case 'three_pack':

                    var columns = []


                    $('._three_pack', obj).each(function (i, col) {


                        _col = {
                            icon: $(col).find('._icon').attr('icon'), title: $(col).find('._title').html(), text: $(col).find('._text').html(),
                        }


                        columns.push(_col)

                    });


                    blocks.push({
                        type: 'three_pack',
                        label: '{t}Three-Pack{/t}',
                        icon: 'fa-bars fa-rotate-90',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        title: ($(obj).find('._main_title').html()),
                        subtitle: ($(obj).find('._main_subtitle').html()),
                        columns: columns

                    })

                    break;

                case 'two_pack':

                    var text = $(obj).find('._text').froalaEditor('html.get')


                    blocks.push({
                        type: 'two_pack',
                        label: '{t}Two-Pack{/t}',
                        icon: 'fa-pause',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        _image: $(obj).find('._image').attr('src'),
                        _image_key: $(obj).find('._image').attr('image_key'),
                        _image_tooltip: $(obj).find('._image_tooltip').attr('tooltip'),

                        _title: $(obj).find('._title').html(),
                        _subtitle: $(obj).find('._subtitle').html(),
                        _text: text
                    })

                    break;

                case 'one_pack':


                    var text = $(obj).find('._text').froalaEditor('html.get')


                    blocks.push({
                        type: 'one_pack', label: '{t}One-Pack{/t}', icon: 'fa-minus', show: ($(obj).hasClass('hide') ? 0 : 1),


                        _title: $(obj).find('._title').html(), _subtitle: $(obj).find('._subtitle').html(), _text: text
                    })

                    break;


                case 'two_one':

                    var columns = []


                    $('._two_one', obj).each(function (i, col) {


                        _col = {
                            type: $(col).attr('type'),

                            _title: $(col).find('._title').html(), _text: $(col).find('._text').html(),
                        }


                        columns.push(_col)

                    });


                    blocks.push({
                        type: 'two_one', label: '{t}Two-One{/t}', icon: 'fa-window-maximize fa-rotate-90', show: ($(obj).hasClass('hide') ? 0 : 1),

                        columns: columns

                    })

                    console.log(columns)

                    break;

                case 'map':

                    blocks.push({
                        type: 'map', label: '{t}Map{/t}', icon: 'fa-map-marker-alt', show: ($(obj).hasClass('hide') ? 0 : 1),

                        src: $(obj).find('iframe').attr('_src'), top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),
                    })




            }

        });


        content_data.blocks = blocks


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'save_webpage_content')
        ajaxData.append("key", '{$webpage->id}')
        ajaxData.append("content_data", JSON.stringify(content_data))
        ajaxData.append("labels", JSON.stringify(labels))
        ajaxData.append("poll_labels", JSON.stringify(poll_labels))
        ajaxData.append("poll_position", JSON.stringify(poll_position))
        ajaxData.append("discounts_data", JSON.stringify(discounts_data))







        $.ajax({
            url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {

                if (data.state == '200') {

                    $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "OK"
                    });
                }


            }, error: function () {

            }
        });


    }


    $(document).on('input paste', '[contenteditable=true]', function (e) {
        $('#save_button', window.parent.document).addClass('save button changed valid')
    });


    var droppedFiles = false;




    function create_static_banner() {


        var slider = new MasterSlider();


        slider.setup("masterslider", {
            width: 1300, height: 768, minHeight: 0,

            fullwidth: true, space: 5
            //autoHeight:true,
            //view:"mask"

            //space           : 0,
            //start           : 1,
            //grabCursor      : false,
            //swipe           : false,
            //mouse           : false,
            //keyboard        : false,
            //layout          : "fullwidth",
            //wheel           : false,
            //autoplay        : false,
            //instantStartLayers:false,
            //loop            : false,
            //shuffle         : false,
            //preload         : 0,
            //heightLimit     : true,
            //autoHeight      : false,
            //smoothHeight    : true,
            //endPause        : false,
            //overPause       : false,
            //fillMode        : "fill",
            //centerControls  : true,
            //startOnAppear   : false,
            //layersMode      : "center",
            //autofillTarget  : "",
            //hideLayers      : false,
            //fullscreenMargin: 0,
            //speed           : 20,
            //dir             : "h",
            //parallaxMode    : 'swipe',
            //view            : "basic"
        });
        slider.control('arrows');
        slider.control('bullets', {
            autohide: false, dir: "v", align: "top"
        });
        MSScrollParallax.setup(slider, 66, 69, true);

    }




    function set_up_froala_editor(key) {



        $('#' + key).froalaEditor({
            iconsTemplate: 'font_awesome_5',

            toolbarInline: true,
            charCounterCount: false,
            toolbarButtons: [ 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsMD: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsSM: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsXS: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            defaultImageDisplay: 'inline',
            fontSize: ['8', '10', '12', '14','16', '18', '30', '60', '96'],
            fontFamily: {
                '{$website->get('Website Text Font')}': 'Default',
                'Arial,Helvetica,sans-serif': 'Arial',
                'Impact,Charcoal,sans-serif': 'Impact',
                'Tahoma,Geneva,sans-serif': 'Tahoma'
            },
            zIndex: 1000,
            pastePlain: true,
            imageUploadURL: '/ar_upload.php',
            imageUploadParams: {
                tipo: 'upload_images', parent: 'webpage', parent_key: $('#blocks').data('webpage_key'),   parent_object_scope: 'Froala',    parent_object_scope: JSON.stringify({ scope: 'block', block_key: key}), response_type: 'froala'

            },
            imageUploadMethod: 'POST',
            imageMaxSize: 5 * 1024 * 1024,
            imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'],
        }).on('froalaEditor.contentChanged', function (e, editor, keyupEvent) {
            $('#save_button', window.parent.document).addClass('save button changed valid')
        });




    }



    $(document).on('click', '._image_tooltip', function (e) {

        if ($('#image_tooltip_edit').hasClass('hide')) {

            $('#image_tooltip_edit').removeClass('hide').offset({
                top: $(this).offset().top - 30, left: $(this).offset().left + $(this).width() + 10
            }).data('element', $(this)).find('input').val($(this).attr('tooltip')).focus()
        } else {
            set_image_tooltip()
        }

    })

    function set_image_tooltip() {

        value = $('#image_tooltip_edit').find('input').val()
        $('#image_tooltip_edit').addClass('hide').data('element').attr('tooltip', value)

        if (value == '') {
            $('#image_tooltip_edit').data('element').removeClass('fa-comment-alt').addClass('fa-comment')
        } else {
            $('#image_tooltip_edit').data('element').addClass('fa-comment-alt').removeClass('fa-comment')

        }
        $('#save_button', window.parent.document).addClass('save button changed valid')


    }

    $("form").on('submit', function (e) {
        e.preventDefault();
        e.returnValue = false;
    });


    $(document).on('click', '.blk_images .image img', function (e) {
        open_image_control_panel(this,'images');
    })

    $(document).on('click', '.blackboard  img', function (e) {
        open_image_control_panel(this,'blackboard');
    })

    $(document).on('click', '  .video', function (e) {
        open_video_control_panel(this);
    })


    $(document).on('click', '#image_control_panel .caption_align i', function (e) {


        $('#image_control_panel').find('.caption_align i').addClass('super_discreet').removeClass('selected')
        $(this).removeClass('super_discreet').addClass('selected')

        element = $('#image_control_panel').data('element');

        $(element).attr('display_class', $(this).attr('display_class'))

        $(element).closest('figure').find('figcaption').removeClass('caption_left caption_right caption_center caption_hide').addClass($(this).attr('display_class'))
        console.log($(element))

        $('#save_button', window.parent.document).addClass('save button changed valid')

    })


    function open_image_control_panel(element,type) {



        if (!$('#image_control_panel').hasClass('hide')) {
            return
        }



        var block_key=$(element).closest('_block').data('block_key');

        var image_options={ }
        if(type=='images'){
            image_options['set_width']=$(element).data('width');

            $('#image_control_panel .caption_tr').removeClass('hide')
            $('#update_images_block_image').attr('name','images')
            $('#image_control_panel').find('.image_caption').val($(element).closest('figure').find('figcaption').html())


        }else if(type=='category_categories'){

            $('#update_images_block_image').attr('name','category_categories')
            $('#image_control_panel .caption_tr').addClass('hide')
            height=220

            switch($(element).attr('size_class')){
                case 'panel_1':
                    $('#image_control_panel .image_size').html('(226x'+height+')')
                    image_options['fit_to_canvas']='226x'+height+''

                    break;
                case 'panel_2':
                    $('#image_control_panel .image_size').html('(470x'+height+')')
                    image_options['fit_to_canvas']='470x'+height+''

                    break;
                case 'panel_3':
                    $('#image_control_panel .image_size').html('(714x'+height+')')
                    image_options['fit_to_canvas']='714x'+height+''

                    break;
                case 'panel_4':
                    $('#image_control_panel .image_size').html('(958x'+height+')')
                    image_options['fit_to_canvas']='958x'+height+''

                    break;
                case 'panel_5':
                    $('#image_control_panel .image_size').html('(1202x'+height+')')
                    image_options['fit_to_canvas']='1202x'+height+''

                    break;
            }



        }else if(type=='category_products'){

            $('#update_images_block_image').attr('name','category_products')
            $('#image_control_panel .caption_tr').addClass('hide')

            var height=$(element).data('height');
            switch($(element).attr('size_class')){
                case 'panel_1':
                    $('#image_control_panel .image_size').html('(226x'+height+')')
                    image_options['fit_to_canvas']='226x'+height+''

                    break;
                case 'panel_2':
                    $('#image_control_panel .image_size').html('(470x'+height+')')
                    image_options['fit_to_canvas']='470x'+height+''

                    break;
                case 'panel_3':
                    $('#image_control_panel .image_size').html('(714x'+height+')')
                    image_options['fit_to_canvas']='714x'+height+''

                    break;
                case 'panel_4':
                    $('#image_control_panel .image_size').html('(958x'+height+')')
                    image_options['fit_to_canvas']='958x'+height+''

                    break;
                case 'panel_5':
                    $('#image_control_panel .image_size').html('(1202x'+height+')')
                    image_options['fit_to_canvas']='1202x'+height+''

                    break;
            }



        }else{

            $('#image_control_panel .caption_tr').addClass('hide')


            $('#update_images_block_image').attr('name','blackboard_image')


        }


// top: .25 * ($(element).offset().top + $(element).height()) / 2

        $('#image_control_panel').removeClass('hide').offset({
            top:  $(element).offset().top, left: $(element).offset().left
        }).addClass('in_use').data('element', $(element))



        console.log($( '#blocks' ).width())
        console.log( $('#image_control_panel').offset().left+$('#image_control_panel').width())

        if($('#image_control_panel').offset().left+$('#image_control_panel').width()>$( '#blocks' ).width()){
            $('#image_control_panel').offset({
               left: $('#image_control_panel').offset().left-($('#image_control_panel').offset().left+$('#image_control_panel').width()-$( '#blocks' ).width())
            })
        }



        $('#image_control_panel').find('.image_control_panel_upload_td input').attr('block_key',block_key).data('options',image_options)


        $('#image_control_panel').find('.image_tooltip').val($(element).attr('alt'))
        $('#image_control_panel').find('.image_link').val($(element).attr('link'))





        $('#image_control_panel').attr('old_image_src', $(element).attr('src'))

        $('#image_control_panel').find('.caption_align i').addClass('super_discreet').removeClass('selected')
        $('#image_control_panel').find('.caption_align i.' + $(element).attr('display_class')).removeClass('super_discreet').addClass('selected')

        $('#image_control_panel').find('.image_upload_from_iframe').data('img', $(element))



    }





    function open_video_control_panel(element) {

        console.log(element)


        if (!$('#video_control_panel').hasClass('hide')) {
            return
        }







// top: .25 * ($(element).offset().top + $(element).height()) / 2

        $('#video_control_panel').removeClass('hide').offset({
            top:  $(element).offset().top+40, left: $(element).offset().left
        }).addClass('in_use').data('element', $(element))


        if($('#video_control_panel').offset().left+$('#video_control_panel').width()>$( '#blocks' ).width()){
            $('#video_control_panel').offset({
                left: $('#video_control_panel').offset().left-($('#video_control_panel').offset().left+$('#video_control_panel').width()-$( '#blocks' ).width())
            })
        }


console.log($(element))

        console.log($(element).attr('video_id'))

        $('#video_control_panel').find('.video_link').val($(element).attr('video_id'))


    }


    function close_image_control_panel() {


        var image = $('#image_control_panel').data('element')

        image.attr('src', $('#image_control_panel').attr('old_image_src'))


        $('#image_control_panel').addClass('hide')

    }


    function close_video_control_panel(){
        $('#video_control_panel').addClass('hide')
    }


    function update_image() {

        // var   image=  $('.blk_images .image:nth-child('+$('#image_control_panel').attr('image_index')+') img')

        var image = $('#image_control_panel').data('element');

        image.attr('alt', $('#image_control_panel').find('.image_tooltip').val())
        image.attr('link', $('#image_control_panel').find('.image_link').val())

        var caption_class = $('#image_control_panel').find('.caption_align i.selected').attr('display_class')
        image.attr('display_class', caption_class)

        image.closest('figure').find('figcaption').removeClass('caption_left caption_right caption_center caption_hide').addClass(caption_class)
        image.closest('figure').find('figcaption').html($('#image_control_panel').find('.image_caption').val())

        $('#image_control_panel').addClass('hide')
        $('#save_button', window.parent.document).addClass('save button changed valid')


    }

    function update_video(){

        var video = $('#video_control_panel').data('element');

        var video_link=$('#video_control_panel').find('.video_link').val()


        video.removeClass('empty')

        video.attr('video_id', video_link)


        video.html('<iframe width="470" height="330" frameborder="0" allowfullscreen="" src="https://www.youtube.com/embed/'+video_link+'?rel=0&amp;controls=0&amp;showinfo=0"></iframe><div class="block_video" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>')



        close_video_control_panel();
    }



    function  change_image_template(block_key, template) {

        var image_blocks= $('#block_'+block_key).find('.blk_images')
        image_blocks.html( $('#template_' + template).html() )
        image_blocks.attr('template',template)


    }

    function change_text_template(block_key, template) {


        var text_blocks= $('#block_'+block_key).find('.text_blocks')

        var old_template=text_blocks.data('template')

        if(old_template==template)return;

        if(template=='12' || template=='21' || template=='13' || template=='31'){
            var _template='2';
        }else if(template=='211'){
            var _template='3';
        }else{
            var _template=template;
        }

        console.log(block_key)
        console.log(template)
        console.log(_template)

        text_blocks.data('template',template).html($('#text_template_'+_template).html())

        text_blocks.removeClass('text_template_'+old_template)
        text_blocks.addClass('text_template_'+template)
        if(template=='1'){
            text_blocks.find('div:nth-child(1)').attr('id','block_'+block_key+'_0_editor')
            set_up_froala_editor('block_'+block_key+'_0_editor')
        }else if(template=='2' || template=='12' || template=='21'  || template=='13' || template=='31'){
            text_blocks.find('div:nth-child(1)').attr('id','block_'+block_key+'_0_editor')
            set_up_froala_editor('block_'+block_key+'_0_editor')
            text_blocks.find('div:nth-child(2)').attr('id','block_'+block_key+'_1_editor')
            set_up_froala_editor('block_'+block_key+'_1_editor')
        } else if(template=='3' || template=='211'){
            text_blocks.find('div:nth-child(1)').attr('id','block_'+block_key+'_0_editor')
            set_up_froala_editor('block_'+block_key+'_0_editor')
            text_blocks.find('div:nth-child(2)').attr('id','block_'+block_key+'_1_editor')
            set_up_froala_editor('block_'+block_key+'_1_editor')
            text_blocks.find('div:nth-child(3)').attr('id','block_'+block_key+'_2_editor')
            set_up_froala_editor('block_'+block_key+'_2_editor')
        }else if(template=='4'){
            text_blocks.find('div:nth-child(1)').attr('id','block_'+block_key+'_0_editor')
            set_up_froala_editor('block_'+block_key+'_0_editor')
            text_blocks.find('div:nth-child(2)').attr('id','block_'+block_key+'_1_editor')
            set_up_froala_editor('block_'+block_key+'_1_editor')
            text_blocks.find('div:nth-child(3)').attr('id','block_'+block_key+'_2_editor')
            set_up_froala_editor('block_'+block_key+'_2_editor')
            text_blocks.find('div:nth-child(4)').attr('id','block_'+block_key+'_3_editor')
            set_up_froala_editor('block_'+block_key+'_3_editor')
        }

        //template_equal_cols

        // $('#block_'+block_key+'_editor').froalaEditor('html.set', $('#text_template_'+template).html());


    }



    function set_up_blackboard(key){

        $('#blackboard_'+key).resizable(
            {
                minHeight:20,
                minWidth:1240,
                maxWidth:1240,
                stop: function (event, ui) {
                    $('#save_button',window.parent.document).addClass('save button changed valid')
                }
            }
        );



    }


    function add_image_to_blackboard(key){


        var datetime = new Date();
        var id='blackboard_image_'+datetime.getTime();

        $('<div id='+id+' class="blackboard_image" style="width:200px;" ></div>').appendTo($('#blackboard_'+key));

        $('<img  title="" link="" alt="" src="/art/nopic_trimmed.jpg" style="width: 200px;"   data-image_website="" data-src="/art/nopic_trimmed.jpg" data-width="200" >').on('load',function (evt) {
            set_up_blackboard_image(id)
        }).appendTo($('#'+id)  );

    }


    function add_text_to_blackboard(key){

        console.log('adding text')

        var datetime = new Date();
        var id='blackboard_text_'+datetime.getTime();


        text = $('<div  id='+id+' class="blackboard_text" style="position:absolute;width:150px;height:150px;" ><h1>Bla bla</h1><p>bla bla bla.</p></div>').appendTo($('#blackboard_'+key));


        set_up_blackboard_text(id)

    }



    function set_up_images(key){
        $('#block_'+key+' .blk_images').sortable({
            cancel: 'figcaption,input,textarea,button,select,option,[contenteditable]',
            stop: function (event, ui) {

                $('#save_button',window.parent.document).addClass('save button changed valid')


            }
        })






    }

    function delete_image(){
        console.log($('#image_control_panel').data('element'))

        if($('#image_control_panel').data('element').hasClass('panel')){
            $('#image_control_panel').data('element').closest('.wrap').remove()

        }else{
            $('#image_control_panel').data('element').remove()

        }
        $('#save_button',window.parent.document).addClass('save button changed valid')
        close_image_control_panel()
    }

    function delete_video(){


        $('#video_control_panel').data('element').closest('.wrap').remove()


        close_video_control_panel()
    }



    function set_up_blackboard_image(img_id){



        $('#'+img_id).find('img').resizable({
            containment: $('#'+img_id).closest('.blackboard'),
            aspectRatio:true,
            stop: function (event, ui) {
                $('#save_button',window.parent.document).addClass('save button changed valid')
            }

        });


        $('#'+img_id).draggable(
            {
                containment: $('#'+img_id).closest('.blackboard'),
                scroll: false,
                start: function(event, ui) {
                    isDraggingMedia = true;
                },
                stop: function (event, ui) {
                    isDraggingMedia = false;
                    $('#save_button',window.parent.document).addClass('save button changed valid')


                }
            }

        )




    }


    function set_up_blackboard_text(text_id){

        $('#'+text_id).resizable({
            containment: $('#'+text_id).closest('.blackboard'),
            stop: function (event, ui) {
                $('#save_button',window.parent.document).addClass('save button changed valid')


            }

        });


        $('#'+text_id).draggable(
            {
                containment: $('#'+text_id).closest('.blackboard'),
                scroll: false,
                start: function(event, ui) {
                },
                stop: function (event, ui) {
                    $('#save_button',window.parent.document).addClass('save button changed valid')


                }
            }

        )




    }
    $(document).on( "dblclick", ".blackboard_text", function() {

      if($(this).hasClass('froala_on')){
          return;
      }




        $(this).draggable( 'destroy' ).resizable('destroy').addClass('editing froala_on')



        set_up_froala_editor($(this).attr('id'))

        parent.open_blackboard_text_edit_view(
            $(this).closest('._block').data('block_key'),
            $(this).attr('id')

        )
    })

    function exit_blackboard_text_edit(id){


        $('#'+id).removeClass('editing froala_on').froalaEditor('destroy')


       set_up_blackboard_text(id)



    }

    function delete_blackboard_text_edit(id){


        $('#'+id).froalaEditor('destroy').remove()





    }

    // category_categories


    function toggle_view_category_categories(block_key,view){
        if(view=='backstage') {

            $('#category_sections_'+block_key+' .item_overlay').removeClass('hide')

        }else{
            $('#category_sections_'+block_key+' .item_overlay').addClass('hide')
          //  $('.panel_controls').addClass('hide')
        }

       // $('#add_item_dialog').addClass('hide')

    }

    function add_category_categories_section(block_key){
        var new_section=$('<div class="section non_anchor"><div class="page_break"><span class="section_header title items_view" contenteditable="true" field="title">{t}Section title{/t}</span> <i onclick="show_add_category_to_category_categories_section(this)" style="margin-top:9px;margin-left:15px" class="fa fa-plus button" title="{t}Add category to this section{/t}"></i><span class="section_header sub_title items_view" contenteditable="true" field="subtitle">{t}Section subtitle{/t}</span></div><div class="section_items connectedSortable"></div></div>')



        new_section.insertAfter('#category_sections_'+block_key+' .section.anchor')

        $('<tr><td class="_title button">{t}Section title{/t}</td></tr>').prependTo('#sections_list_tbody')


    }



    function show_edit_category_categories_section() {

    }

    function move_category_categories_sections(block_key,pre,post){
        if (post > pre) {

            $('#category_sections_'+block_key+' .non_anchor:eq(' + pre + ')').insertAfter('#category_sections_'+block_key+' .non_anchor:eq(' + post + ')');


            $('#sections_list_tbody tr:eq(' + pre + ')').insertAfter('#sections_list_tbody tr:eq(' + post + ')');




        } else {


            $('#category_sections_'+block_key+' .non_anchor:eq(' + pre + ')').insertBefore('#category_sections_'+block_key+' .non_anchor:eq(' + post + ')');
            $('#sections_list_tbody tr:eq(' + pre + ')').insertBefore('#sections_list_tbody tr:eq(' + post + ')');


        }
    }

    function delete_category_categories_section(block_key,index){

        var section=$('#category_sections_'+block_key+' .non_anchor:eq(' + index + ')')




        $('#category_sections_'+block_key+' .non_anchor:eq(' + index + ') .category_wrap').each(function (i, category_wrap) {
            $('#category_sections_'+block_key+' .anchor .section_items').append($(category_wrap))
        })

        section.remove();

    }

    $( ".section_items" ).sortable({
        connectWith: ".connectedSortable",
        cancel: ".sortable-disabled",
        stop: function (event, ui) {
            $('#save_button',window.parent.document).addClass('save button changed valid')
        }
    })



    $(document).on( "click", ".wrap .txt", function() {

        edit_panel_text($(this).closest('.wrap'))

    })


    $(document).on( "click", ".product_header_text ", function() {

        console.log('xxx')

        $(this).closest('.wrap').addClass('sortable-disabled').find('.panel_txt_control').removeClass('hide')

        $(this).uniqueId()

       // panel.find('.panel_txt_control').removeClass('hide')


        set_up_froala_editor($(this).attr('id'))

    })





    $(document).on("input propertychange", ".item_overlay_item_header_text", function () {

console.log('caca')

        $(this).closest('.wrap').find('.category_block .item_header_text').html($(this).html())

    })



    $(document).on( "click", ".close_category_block", function() {

        $(this).closest('.wrap').addClass('sortable-disabled')

        $(this).closest('.wrap').find('.edit_icon').removeClass('hide')


        //var title=$(this).closest('.item_overlay').find('.item_header_text').html()
        //$(this).closest('.wrap').find('.category_block .item_header_text').html(title)


        $(this).closest('.wrap').removeClass('sortable-disabled')
        $(this).closest('.wrap').find('.edit').removeClass('hide')

        $(this).closest('.item_overlay').addClass('hide')

    })

    $(document).on('click', '.category_wrap img.panel', function (e) {
        open_image_control_panel(this,'category_categories');
    })

    $(document).on('click', '.product_wrap img.panel', function (e) {
        open_image_control_panel(this,'category_products');
    })


    $(document).on( "click", ".section_items .move_to_other_section", function() {

        $('#sections_list_tbody tr').removeClass('hide')

        if($(this).closest('.section').hasClass('non_anchor')){
            var index=$(this).closest('.section').index()-1

            $('#sections_list_tbody tr:eq('+index+') ').addClass('hide')

        }




        $('#sections_list').removeClass('hide').offset({
            left: $(this).offset().left,top: $(this).offset().top
        }).data('element',this)






    })

    $(document).on("click", "#sections_list td.button", function () {


        var block_key = $('#sections_list').data('block_key')


        var element = $('#sections_list').data('element')

        var index = $(this).closest('tr').index()

        if (!$(this).closest('tr').hasClass('anchor')) {
            index = index + 1
        }

        console.log(index)
        $('#category_sections_' + block_key + ' .section:eq(' + index + ') .section_items').append(element.closest('.category_wrap'))
        $('#sections_list').addClass('hide')


        $(element).closest('.item_overlay').addClass('hide')

    })

    $(document).on("input propertychange", ".section .title", function () {
        var index = $(this).closest('.section').index()-1

        parent.category_categories_section_title_changed(index,$(this).html())
        $('#sections_list_tbody tr:eq('+index+') ._title').html($(this).html())

        console.log(index)

    })

    function add_guest_to_category_categories(block_key,section_index,category_element){
        $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').append($(category_element))
    }

    function add_product_to_products_block(block_key,product_element){
        $('#block_'+block_key+' .products').append(product_element)
    }


    function add_item_to_see_also(block_key,item){
        $('#block_'+block_key+' .see_also').append(item)

    }

    function add_panel(block_key,type,size,scope,scope_metadata){


        
        if(scope=='category_categories'){
           var item_class='category_wrap wrap'
            height=220;
        }else if(scope=='category_products'){
            var item_class='product_wrap wrap type_'+type
            height=scope_metadata;
        }

        console.log(type)
        console.log(size)

        if(type=='text'){
            switch(size){
                case 1:
                    panel=$('<div class="'+item_class+'" data-type="text"><div style="padding:20px" size_class="panel_1" data-padding="20" class="txt panel_1">bla bla</div></div>')

                    break;
                case 2:
                    panel=$('<div class="'+item_class+'" data-type="text"><div style="padding:20px"  size_class="panel_2" data-padding="20" class="txt panel_2">bla bla</div></div>')

                    break;
                case 3:
                    panel=$('<div class="'+item_class+'" data-type="text"><div style="padding:20px" size_class="panel_3"  data-padding="20" class="txt panel_3">bla bla</div></div>')

                    break;
                case 4:
                    panel=$('<div class="'+item_class+'" data-type="text"><div style="padding:20px" size_class="panel_4"  data-padding="20" class="txt panel_4">bla bla</div></div>')

                    break;
                case 5:
                    panel=$('<div class="'+item_class+'" data-type="text"><div style="padding:20px"  size_class="panel_5" data-padding="20" class="txt panel_5">bla bla</div></div>');;

                    break;

            }




        }
        else if(type=='image'){

            switch(size){
                case 1:
                    panel=$('<div class="'+item_class+'" data-type="image"><img class="panel panel_1" size_class="panel_1" alt="" link="" data-image_website="" data-height="'+height+'" data-src="https://via.placeholder.com/226x'+height+'" src="https://via.placeholder.com/226x'+height+'"  /></div>')

                    break;
                case 2:
                    panel=$('<div class="'+item_class+'" data-type="image"><img class="panel  panel_2"  size_class="panel_2" alt="" link="" data-image_website=""  data-height="'+height+'" data-src="https://via.placeholder.com/470x'+height+'"  src="https://via.placeholder.com/470x'+height+'"  /></div>')

                    break;
                case 3:
                    panel=$('<div class="'+item_class+'" data-type="image""><img class="panel panel_3"   size_class="panel_3" alt="" link="" data-image_website=""  data-height="'+height+'" data-src="https://via.placeholder.com/714x'+height+'"  src="https://via.placeholder.com/714x'+height+'"  /></div>')

                    break;
                case 4:
                    panel=$('<div class="'+item_class+'" data-type="image"><img class="panel  panel_4"   size_class="panel_4" alt="" link="" data-image_website=""  data-height="'+height+'" data-src="https://via.placeholder.com/958x'+height+'"  src="https://via.placeholder.com/958x'+height+'"  /></div>')

                    break;
                case 5:
                    panel=$('<div class="'+item_class+'" data-type="image"><img class="panel  panel_5"   size_class="panel_5" alt="" link=""  data-image_website="" data-height="'+height+'"  data-src="https://via.placeholder.com/1202x'+height+'"  src="https://via.placeholder.com/1202x'+height+'"  /></div>')

                    break;


            }


        } else if(type=='video'){
            panel=$('<div class="'+item_class+'" data-type="video"><div  size_class="panel_2" class="video  empty panel_2" video_id="" ></div></div>')

        }

        if(scope=='category_categories'){
            $('#category_sections_'+block_key+' .section:eq('+scope_metadata+') .section_items').prepend(panel)

        }else if(scope=='category_products'){
            $('#block_'+block_key+' .products').prepend(panel)

        }


        if(type=='text'){
            $( "#panel_txt_control .panel_txt_control" ).clone().prependTo(panel);
            edit_panel_text(panel)
        }else if(type=='video'){
            open_video_control_panel(panel.find('.video'));
        }





        $('#save_button',window.parent.document).addClass('save button changed valid')

    }

    function edit_panel_text(panel) {

        panel.find('.txt').uniqueId()

        panel.find('.panel_txt_control').removeClass('hide')

        var panel_id = panel.addClass('sortable-disabled').find('.txt').attr('id');

        //console.log(panel_id)

        set_up_froala_editor(panel_id)

    }

    function close_panel_text(element) {

        $(element).closest('.panel_txt_control').addClass('hide').closest('.wrap').removeClass('sortable-disabled').find('.txt').froalaEditor('destroy')

        $(element).closest('.wrap').find('.txt').addClass('fr-view')

    }


    function close_product_header_text(element){
        $(element).closest('.panel_txt_control').addClass('hide').closest('.wrap').removeClass('sortable-disabled').find('.product_header_text').froalaEditor('destroy')

        $(element).closest('.wrap').find('.product_header_text').addClass('fr-view')
    }

    function delete_panel_text(element){
        $(element).closest('.wrap').remove();
    }


    function update_product_item_headers(block_key,value){

        var product_wrap=$('#block_'+block_key).find('.products')

        if(value=='on'){
            product_wrap.removeClass('no_items_header')
            $('#block_'+block_key+' .delete_product').css({ 'top':'90px'})


        }else{
            product_wrap.addClass('no_items_header')
            $('#block_'+block_key+' .delete_product').css({ 'top':'50px'})


        }

    }

    function update_category_products_item_headers(block_key,value){

        var product_wrap=$('#block_'+block_key).find('.products')

       // console.log(value)

        if(value=='on'){
            product_wrap.removeClass('no_items_header')

            $('#block_'+block_key+' img.panel').each(function (i, img) {
                $(img).data('height',330)

                if($(img).attr('src')=='https://via.placeholder.com/470x290'){
                    $(img).attr('src','https://via.placeholder.com/470x330')
                }

                if($(img).attr('src')=='https://via.placeholder.com/226x290'){
                    $(img).attr('src','https://via.placeholder.com/226x330')
                }
                if($(img).data('src')=='https://via.placeholder.com/226x290'){
                    $(img).data('src','https://via.placeholder.com/226x330')
                }

                if($(img).attr('src')=='https://via.placeholder.com/470x290'){
                    $(img).attr('src','https://via.placeholder.com/470x330')
                }
                if($(img).data('src')=='https://via.placeholder.com/470x290'){
                    $(img).data('src','https://via.placeholder.com/470x330')
                }

                if($(img).attr('src')=='https://via.placeholder.com/714x290'){
                    $(img).attr('src','https://via.placeholder.com/714x330')
                }
                if($(img).data('src')=='https://via.placeholder.com/714x290'){
                    $(img).data('src','https://via.placeholder.com/714x330')
                }

                if($(img).attr('src')=='https://via.placeholder.com/958x290'){
                    $(img).attr('src','https://via.placeholder.com/958x330')
                }
                if($(img).data('src')=='https://via.placeholder.com/1202x290'){
                    $(img).data('src','https://via.placeholder.com/1202x330')
                }

            })

        }else{
            product_wrap.addClass('no_items_header')

            $('#block_'+block_key+' img.panel').each(function (i, img) {



                $(img).data('height',290)


                if($(img).attr('src')=='https://via.placeholder.com/226x330'){
                    $(img).attr('src','https://via.placeholder.com/226x290')
                }
                if($(img).data('src')=='https://via.placeholder.com/226x330'){
                    $(img).data('src','https://via.placeholder.com/226x290')
                }

                if($(img).attr('src')=='https://via.placeholder.com/470x330'){
                    $(img).attr('src','https://via.placeholder.com/470x290')
                }
                if($(img).data('src')=='https://via.placeholder.com/470x330'){
                    $(img).data('src','https://via.placeholder.com/470x290')
                }

                if($(img).attr('src')=='https://via.placeholder.com/714x330'){
                    $(img).attr('src','https://via.placeholder.com/714x290')
                }
                if($(img).data('src')=='https://via.placeholder.com/714x330'){
                    $(img).data('src','https://via.placeholder.com/714x290')
                }

                if($(img).attr('src')=='https://via.placeholder.com/958x330'){
                    $(img).attr('src','https://via.placeholder.com/958x290')
                }
                if($(img).data('src')=='https://via.placeholder.com/1202x330'){
                    $(img).data('src','https://via.placeholder.com/1202x290')
                }


            })

        }

    }

    function sort_category_products_items(block_key,type){


        $('#block_'+block_key+' .products').data('sort',type)

        var panel_index={

        }


        if(type!='Manual') {


            $('#block_' + block_key + ' .products .product_wrap:not(.type_product)').each(function (i, panel) {

                $(panel).uniqueId()
                panel_index[$(panel).attr('id')] = $(panel).index()


            })





            if (type == 'Code') {

                $('#block_' + block_key + ' .products .product_wrap').sort(sort_li).appendTo('#block_' + block_key + ' .products ');

                function sort_li(a, b) {
                    return ($(b).data('sort_code')) < ($(a).data('sort_code')) ? 1 : -1;
                }
            } else if (type == 'Code_desc') {

                $('#block_' + block_key + ' .products .product_wrap').sort(sort_li).appendTo('#block_' + block_key + ' .products ');

                function sort_li(a, b) {
                    return ($(b).data('sort_code')) > ($(a).data('sort_code')) ? 1 : -1;
                }
            } else if (type == 'Name') {

                $('#block_' + block_key + ' .products .product_wrap').sort(sort_li).appendTo('#block_' + block_key + ' .products ');

                function sort_li(a, b) {
                    return ($(b).data('sort_name')) < ($(a).data('sort_name')) ? 1 : -1;
                }
            }


            $.each(panel_index, function (panel_id, index) {

                $('#' + panel_id).insertAfter('#block_' + block_key + ' .products .product_wrap:eq(' + index + ')');

            });

            product_sort_index=[]
            $('#block_'+block_key+' .products .type_product').each(function (i, product) {
                product_sort_index.push($(product).attr('id'))
            })

        }

        $('#save_button',window.parent.document).addClass('save button changed valid')

    }

    function remove_product_from_products(element){

        $(element).closest('.wrap').remove()
        $('#save_button',window.parent.document).addClass('save button changed valid')


    }

    function toggle_block_title(block_key,value){
        if(value){
            $('#block_'+block_key+' .products_title').removeClass('hide')
        }else{
            $('#block_'+block_key+' .products_title').addClass('hide')

        }
        $('#save_button',window.parent.document).addClass('save button changed valid')


    }


    function toggle_see_also_auto(block_key,value){

        $('#block_'+block_key+' .see_also').data('auto',value)


        if(value){
            $( "#block_"+block_key+" .see_also " ).sortable("destroy").addClass('no_edit')



            $( "#block_"+block_key+" .item_overlay " ).addClass('hide')

        }else{




            $( "#block_"+block_key+" .see_also " ).removeClass('no_edit').sortable({
                cancel: ".sortable-disabled,.edit_icon",
                update:function (event, ui) {
                    $('#save_button',window.parent.document).addClass('save button changed valid')



                },

            })

        }
        $('#save_button',window.parent.document).addClass('save button changed valid')
    }



    function refresh_see_also(block_key,items,auto_items,auto_last_updated){

        $('#block_'+block_key+' .see_also').html(items).data('auto_items',auto_items).data('auto',true).data('auto_last_updated',auto_last_updated)

        $('#save_button',window.parent.document).addClass('save button changed valid')


    }



</script>

</body>

</html>

