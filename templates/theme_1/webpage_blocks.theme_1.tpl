{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 July 2017 at 03:08:39 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.tpl"}

<body xmlns="http://www.w3.org/1999/html" data-fel="{$smarty.const.FROALA_EDITOR_KEY}"  data-default_font="{$website->get('Website Text Font')}"  >

<div id="aux">



    <div id="text_block_style" class="hide object_control_panel element_for_color element_for_margins" style="padding: 0;z-index: 3001;">



        <div class="handle" style="border-bottom: 1px solid #ccc;width: 100%;line-height: 30px;height: 30px">
            <i class="fa fa-window-close button padding_left_10" onclick="$('#text_block_style').addClass('hide')"></i>
        </div>
<div style="padding: 20px">
        <table >

            <tr>
                <td class="label">{t}Margin{/t}</td>
                <td class="margins_container unselectable margin" data-scope="margin">
                    <input data-margin="top" class="edit_block_margin edit_block_input top" value=""  placeholder="0"><input data-margin="bottom" class="edit_block_margin edit_block_input bottom" value="" placeholder="0">
                    <input data-margin="left" class=" edit_margin left" value="" placeholder="0"><input data-margin="right" class=" edit_margin right" value="" placeholder="0">

                    <i class="fa fa-plus-circle padding_left_10 like_button up_margins"></i>
                    <i class="fa fa-minus-circle padding_left_5 like_button down_margins"></i>

                </td>
            </tr>

            <tr>
                <td class="label">{t}Padding{/t}</td>
                <td class="margins_container unselectable padding" data-scope="padding">
                    <input data-margin="top" class="edit_block_margin edit_block_input top" value=""  placeholder="0"><input data-margin="bottom" class="edit_block_margin edit_block_input bottom" value="" placeholder="0">
                    <input data-margin="left" class=" edit_margin left" value="" placeholder="0"><input data-margin="right" class=" edit_margin right" value="" placeholder="0">

                    <i class="fa fa-plus-circle padding_left_10 like_button up_margins"></i>
                    <i class="fa fa-minus-circle padding_left_5 like_button down_margins"></i>


                </td>
            </tr>
            <tr>
                <td class="label">{t}Border{/t}</td>
                <td class="margins_container unselectable border border-width" data-scope="border">
                    <input data-margin="top-width" class="edit_block_margin edit_block_input top" value=""  placeholder="0"><input data-margin="bottom-width" class="edit_block_margin edit_block_input bottom" value="" placeholder="0">
                    <input data-margin="left-width" class=" edit_margin left" value="" placeholder="0"><input data-margin="right-width" class=" edit_margin right" value="" placeholder="0">

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
        <div class="panel_txt_control" >
            <i class="fa fa-expand" title="{t}Padding{/t}"></i> <input size="2" style="height: 16px;" value="20">
            <i onclick="delete_panel_text(this)" class="far fa-trash-alt padding_left_10 like_button" title="{t}Delete{/t}"></i>

            <i onclick="close_panel_text(this)" class="fa fa-window-close button" style="float: right;margin-top:6px" title="{t}Close text edit mode{/t}"></i>

        </div>
    </div>

    <div id="template_1" class="hide">
<span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/1240x250.png" alt="" data-width="1240" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

    </div>

    <div id="template_2" class="hide">
<span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/610x250.png" alt="" data-width="610" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/610x250.png" alt=""  data-width="610" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

    </div>


    <div id="template_3" class="hide">
<span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/400x250.png" alt=""  data-width="400" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/400x250.png" alt=""  data-width="400" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/400x250.png" alt=""  data-width="400" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    </div>



    <div id="template_4" class="hide">
<span class="image"   >
        <figure>
            <img class="button" src="https://via.placeholder.com/300x250.png" alt=""  data-width="300" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/300x250.png" alt="" data-width="300" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/300x250.png" alt="" data-width="300" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/300x250.png" alt="" data-width="300" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    </div>


    <div id="template_12" class="hide">
<span class="image" >
        <figure>
            <img class="button" src="https://via.placeholder.com/400x250.png" data-width="400" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/800x250.png" data-width="800"  alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

    </div>

    <div id="template_21" class="hide">
<span class="image" >
        <figure>
            <img class="button" src="https://via.placeholder.com/800x250.png" data-width="800"  alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/400x250.png" data-width="400"  alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

    </div>


    <div id="template_13" class="hide">
<span class="image"  >
        <figure>
            <img class="button" src="https://via.placeholder.com/310x250.png" data-width="310"  alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/910x250.png"  data-width="910" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>


    </div>



    <div id="template_31" class="hide">
<span class="image"  >
        <figure>
            <img class="button" src="https://via.placeholder.com/910x250.png"  data-width="910" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/310x250.png"  data-width="310" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>


    </div>



    <div id="template_211" class="hide">
<span class="image"  >
        <figure>
            <img class="button" src="https://via.placeholder.com/600x250.png"  data-width="600" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/300x250.png"  data-width="300" alt="" display_class="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
        <span class=" image">
        <figure>
            <img class="button" src="https://via.placeholder.com/300x250.png"  data-width="300" alt="" display_class="caption_left">
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
                <img class="button" src="https://via.placeholder.com/300x250.png" alt="" display_class="caption_left">
                <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
            </figure>
        </span>
        </div>


    </div>


    <div id="simple_line_icons_control_center" class="input_container  hide   " >

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
            <div class="nav"><i class="fas fa-arrow-left"></i>  <i class="fas fa-arrow-right next"></i></div>
            <div style="clear:both"></div>
        </div>
        {/if}

        {if isset($discounts) and count($discounts.deals)>0 }

            <div class="discounts top_body" >
            {foreach from=$discounts.deals item=deal_data }
            <div class="discount_card" data-key="{$deal_data.key}" >
                <div class="discount_icon" >{$deal_data.icon}</div>
                <span  class="discount_name">{$deal_data.name}</span>
                {if  $deal_data.until!=''}<small class="padding_left_10"><span id="_offer_valid_until" class="website_localized_label" contenteditable="true">{if !empty($labels._offer_valid_until)}{$labels._offer_valid_until}{else}{t}Valid until{/t}{/if}</span>: {$deal_data.until_formatted}{/if}</small>
                <br/>
                <span class="discount_term">{$deal_data.term}</span>
                <span  class="discount_allowance">{$deal_data.allowance}</span>
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
    {elseif $block.type=='custom_design_products'}

    set_up_froala_editor('block_{$key}_with_items_editor');
    set_up_froala_editor('block_{$key}_no_items_editor');
    {elseif $block.type=='customer_discounts'}

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


        $('._block').each(function (i, obj) {
        console.log($(obj).attr('block'))

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


                    labels = {

                    };


                    $('[contenteditable=true]', obj).each(function (i, obj2) {

                        if ($(obj2).hasClass('poll_query_label')) {
                            poll_labels[$(obj2).data('query_key')] = base64_url_encode($(obj2).html())
                        } else {
                            labels[$(obj2).attr('id')] = $(obj2).html()
                        }


                    })

                    $('.poll_query_label', obj).each(function (i, obj2) {
                        poll_position.push($(obj2).data('query_key'))
                    })


                    $('.register_field', obj).each(function (i, obj2) {
                        labels[$(obj2).attr('id')] = $(obj2).attr('placeholder')
                    })


                    $('.tooltip', obj).each(function (i, obj2) {
                        if ($(obj2).attr('id') != undefined) labels[$(obj2).attr('id')] = $(obj2).html()
                    })


                    $('.website_localized_label', obj).each(function (i, obj2) {
                        if ($(obj2).val() != '') {
                            labels[$(obj2).attr('id')] = $(obj2).val()


                        }

                    })

                    console.log(labels)


                    blocks.push({
                        type: 'profile',
                        label: '{t}Profile{/t}',
                        icon: 'fa-user',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: labels

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
                        text: $(obj).find('.thanks_text').data('editor').html.get()

                    })


                    break;
                case 'favourites':

                    var content_data = {
                        'with_items': $(obj).find('.with_items').data('editor').html.get(), 'no_items': $(obj).find('.no_items').data('editor').html.get()
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
                case 'custom_design_products':

                    var content_data = {
                        'with_items': $(obj).find('.with_items').data('editor').html.get(), 'no_items': $(obj).find('.no_items').data('editor').html.get()
                    }

                    blocks.push({
                        type: 'custom_design_products',
                        label: '{t}Custom Design Products{/t}',
                        icon: 'fa-user-shield',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data

                    })
                    break;
                case 'customer_discounts':

                    var content_data = {
                        'with_items': $(obj).find('.with_items').data('editor').html.get(), 'no_items': $(obj).find('.no_items').data('editor').html.get()
                    }

                    blocks.push({
                        type: 'customer_discounts',
                        label: '{t}Customer discounts{/t}',
                        icon: 'fa-user-tag',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data

                    })
                    break;
                case 'portfolio':

                    var content_data = {
                    }

                    $('[contenteditable=true]', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).html()
                    })

                    blocks.push({
                        type: 'portfolio',
                        label: '{t}Portfolio{/t}',
                        icon: 'fa-store-alt',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data
                    })
                    break;
                case 'catalogue':

                    var content_data = {
                    }

                    $('[contenteditable=true]', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).html()
                    })

                    blocks.push({
                        type: 'catalogue',
                        label: '{t}Catalogue{/t}',
                        icon: 'fa-apple-crate',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data
                    })
                    break;
                case 'clients':

                    var content_data = {
                    }

                    $('[contenteditable=true]', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).html()
                    })

                    $('.new_client_field', obj).each(function (i, obj2) {
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
                        type: 'clients',
                        label: '{t}Clients{/t}',
                        icon: 'fa-user',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data
                    })
                    break;
                case 'clients_orders':

                    var content_data = {
                    }

                    $('[contenteditable=true]', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).html()
                    })



                    $('.website_localized_label', obj).each(function (i, obj2) {
                        if ($(obj2).val() != '') {
                            labels[$(obj2).attr('id')] = $(obj2).val()


                        }

                    })

                    blocks.push({
                        type: 'clients_orders',
                        label: "{t}Clients's orders{/t}",
                        icon: 'fa-shopping-cart',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        labels: content_data
                    })
                    break;
                case 'top_up':

                    var content_data = {
                    }

                    $('[contenteditable=true]', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).html()
                    })

                    blocks.push({
                        type: 'top_up',
                        label: '{t}Top up{/t}',
                        icon: 'fa-piggy-bank',
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

                    var text='';
                    if ($(text_block).hasClass('fr-box')) {
                         text = $(text_block).data('editor').html.get()

                    } else {
                         text = $(text_block).html()
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
                        overwrite_title: $(obj).find('#overwrite_title').html(),


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
                            var header_text = txt.data('editor').html.get()
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
                                    var header_text = txt.data('editor').html.get()
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
                                    var text = txt.data('editor').html.get()
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
                        top_margin: $(obj).attr('left_margin'),
                        bottom_margin: $(obj).attr('right_margin'),
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
                                        var text = txt.data('editor').html.get()
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
                            var text = $(text_block).data('editor').html.get()
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


                        var text = $(text_block).data('editor').html.get()
                        text_blocks.push({
                            text: text,
                            styles: styles
                        })

                    });

                    blocks.push({
                        type: 'text', label: '{t}Text{/t}', icon: 'fa-font', show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                        left_margin: $(obj).attr('left_margin'),
                        right_margin: $(obj).attr('right_margin'),
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

                case 'client_basket':


                    var content_data = {
                        type: 'client_basket', label: '{t}Client Basket{/t}', icon: 'fa-basket', show: 1,

                        top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),

                    }

                    $('[contenteditable=true]').each(function (i, obj2) {

                        if ($(obj2).hasClass('website_localized_label')) {

                            labels[$(obj2).attr('id')] = $(obj2).html()
                        } else {
                            content_data[$(obj2).attr('id')] = $(obj2).html()
                        }


                    })

                    content_data['_special_instructions'] = $('#_special_instructions').val()


                    blocks.push(content_data)


                    break;
                case 'client_order_new':


                    var content_data = {
                        type: 'client_order_new', label: '{t}New order{/t}', icon: 'fa-seedling', show: 1,

                        top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),

                    }

                    $('[contenteditable=true]').each(function (i, obj2) {

                        if ($(obj2).hasClass('website_localized_label')) {

                            labels[$(obj2).attr('id')] = $(obj2).html()
                        } else {
                            content_data[$(obj2).attr('id')] = $(obj2).html()
                        }


                    })



                    blocks.push(content_data)


                    break;
                case 'client_order':


                    var content_data = {
                        type: 'client_order', label: '{t}Client order{/t}', icon: 'fa-shopping-cart', show: 1,

                        top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),

                    }

                    $('[contenteditable=true]').each(function (i, obj2) {

                        if ($(obj2).hasClass('website_localized_label')) {

                            labels[$(obj2).attr('id')] = $(obj2).html()
                        } else {
                            content_data[$(obj2).attr('id')] = $(obj2).html()
                        }


                    })



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
                case 'code':


                    blocks.push({
                        type: 'code',
                        label: '{t}Code{/t}',
                        icon: 'fa-code',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        src: $(obj).find('textarea.desktop').val(),
                        mobile_src: $(obj).find('textarea.mobile').val()
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

                    var text = $(obj).find('._text').data('editor').html.get()


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


                    var text = $(obj).find('._text').data('editor').html.get()


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
                    break;
                case 'launching':

                    var content_data = {
                    }

                    $('[contenteditable=true]', obj).each(function (i, obj2) {
                        content_data[$(obj2).attr('id')] = $(obj2).html()
                    })

                    blocks.push({
                        type: 'launching',
                        label: '{t}Launching website{/t}',
                        icon: 'fa-rocket',
                        show: 1,
                        image: $('.big_img').data('img'),
                        labels: content_data
                    })
                    break;



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


    function add_category_categories_section(block_key){
        var new_section=$('<div class="section non_anchor"><div class="page_break"><span class="section_header title items_view" contenteditable="true" field="title">{t}Section title{/t}</span> <i onclick="show_add_category_to_category_categories_section(this)" style="margin-top:9px;margin-left:15px" class="fa fa-plus button" title="{t}Add category to this section{/t}"></i><span class="section_header sub_title items_view" contenteditable="true" field="subtitle">{t}Section subtitle{/t}</span></div><div class="section_items connectedSortable"></div></div>')



        new_section.insertAfter('#category_sections_'+block_key+' .section.anchor')

        $('<tr><td class="_title button">{t}Section title{/t}</td></tr>').prependTo('#sections_list_tbody')


    }

















</script>

</body>

</html>

