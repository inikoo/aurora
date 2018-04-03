{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 July 2017 at 03:08:39 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.tpl"}
<style>

    #image_control_panel{
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        padding: 10px 10px 10px 10px;
        z-index: 3000;
    }
    #image_control_panel td{
        padding-bottom: 10px;
    }

    div.blk_images figure {
        margin:0px

    }

    div.blk_images {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    div.blk_images figcaption{
        font-family: "Ubuntu", Helvetica, Arial, sans-serif;
        color:#999

    }

    .label{
        padding-right: 20px;
    }

    .caption_align i{
        padding-right: 10px;cursor: pointer;
    }

    figcaption.caption_left{
        text-align: left;padding-left:5px

    }
    figcaption.caption_right{
        text-align: right;padding-right:5px
    }
    figcaption.caption_center{
        text-align: center;

    }
    figcaption.caption_hide{

    }

    .success{
        color:#26A65B;
    }




    #simple_line_icons_control_center {
        z-index: 3000;
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 10px 20px 20px 20px
    }

    #simple_line_icons_control_center i {
        padding: 2px;
        font-size: 110%;
        cursor: pointer
    }

    .text_blocks {
        display: flex;
        flex-direction: row;
    }



    .text_template_2 > div,
    .text_template_3 > div,
    .text_template_4 > div {
        flex: 1;
    }


    .text_template_12 > div:nth-child(1) {
        width: calc(100% / 3 * 1);
    }

    .text_template_12 > div:nth-child(2) {
        width: calc(100% / 3 * 2);
    }

    .text_template_21 > div:nth-child(1) {
        width: calc(100% / 3 * 2);
    }

    .text_template_21 > div:nth-child(2) {
        width: calc(100% / 3 * 1);
    }

    .text_template_31 > div:nth-child(1) {
        width: calc(100% / 4 * 3);
    }

    .text_template_31 > div:nth-child(2) {
        width: calc(100% / 4 * 1);
    }

    .text_template_13 > div:nth-child(1) {
        width: calc(100% / 4 * 1);
    }

    .text_template_13 > div:nth-child(2) {
        width: calc(100% / 4 * 3);
    }

    .text_template_211 > div:nth-child(1) {
        width: calc(100% / 4 * 2);
    }

    .text_template_211 > div:nth-child(2) {
        width: calc(100% / 4 * 1);
    }

    .text_template_211 > div:nth-child(3) {
        width: calc(100% / 4 * 1);
    }


</style>

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

</div>



<div id="image_control_panel" class="hide">
    <div style="text-align: right;margin-bottom: 10px;padding-right: 5px">
        <i class="fa fa-window-close button" onclick="update_image()"></i>
    </div>

    <table>
        <tr>
            <td class="label">{t}Image{/t}</td>
            <td class="image_control_panel_upload_td">
                <input style="display:none" type="file" name="images" id="update_images_block_image" class="image_upload" />
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
        <tr class="caption_tr">
            <td class="label">{t}Caption{/t}</td>
            <td class="caption_align">
                <i class="fa fa-align-left super_discreet caption_left" display_class="caption_left" aria-hidden="true"></i>
                <i class="fa fa-align-center super_discreet caption_center" display_class="caption_center" aria-hidden="true"></i>
                <i class="fa fa-align-right super_discreet caption_right" display_class="caption_right" aria-hidden="true"></i>
                <i class="fa fa-ban error super_discreet caption_hide" display_class="caption_hide" aria-hidden="true"></i>
            </td>
        </tr>
        <tr>
            <td class="label"></td><td><span onclick="delete_image()" class="button unselectable"><i class="fa fa-trash"></i> {t}Delete{/t}</span></td>
        </tr>
    </table>



</div>



<body xmlns="http://www.w3.org/1999/html">

<div class="wrapper_boxed">


    <div id="blocks" class="site_wrapper" data-webpage_key="{$webpage->id}">
        {foreach from=$content.blocks item=$block key=key}
            {include file="{$theme}/blk.{$block.type}.{$theme}.tpl" data=$block key=$key  }



        {/foreach}
    </div>

</div>


<script>







    {foreach from=$content.blocks item=$block key=key}
    {if $block.type=='one_pack' or  $block.type=='two_pack'   }
    set_up_froala_editor('block_{$key}_editor')

    {elseif $block.type=='text'}


    $("#block_{$key} .text_block").each(function () {

        console.log($(this))
        set_up_froala_editor('block_{$key}_'+$(this).data('text_block_key')+'_editor')
    });

    {elseif $block.type=='static_banner'}

    create_static_banner('{$key}')

    {elseif $block.type=='images'}

    set_up_images('{$key}')





    {elseif $block.type=='blackboard'}

    set_up_blackboard('{$key}')

    {foreach from=$block.images item=image}

    set_up_blackboard_image('{$image.id}')

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


        content_data = {};

        var blocks = []
        var labels = {

        };


        $('._block').each(function (i, obj) {


            console.log($(obj).attr('block'))

            switch ($(obj).attr('block')) {

                case 'category_categories':

                    var sections = []
                    $('.section  ', obj).each(function (i, section) {

                        var items = []
                        $('.category_wrap  ', section).each(function (j, item) {


                            switch ($(item).data('type')){
                                case 'category':

                                    var img=$(item).find('.wrap_to_center img')






                                    items.push({
                                        type: $(item).data('type'),
                                        category_key: $(item).find('.category_block').data('category_key'),
                                        webpage_key: $(item).find('.category_block').data('category_webpage_key'),
                                        item_type: $(item).find('.category_block').data('item_type'),
                                        link:$(item).find('.category_block').data('link'),
                                        webpage_code:$(item).find('.category_block').data('webpage_code'),


                                        header_text: $(item).find('.item_header_text').html(),
                                        image_src:img.data('src'),
                                        image_website: ( img.attr('src')!='EcomB2B/'+img.data('image_website') ?'': img.data('image_website')),
                                        image_mobile_website: ( img.attr('src')!='EcomB2B/'+img.data('image_website') ?'': img.data('image_mobile_website')),



                                        category_code: $(item).find('.category_code').html(),
                                        number_products: $(item).find('.number_products').html(),

                                    })
                                    break;
                                case 'image':



                                    var img=$(item).find('img')
                                    items.push({
                                        type: $(item).data('type'),

                                        image_src:img.data('src'),
                                        image_website: ( img.attr('src')!='EcomB2B/'+img.data('image_website') ?'': img.data('image_website')),

                                        link: img.attr('link'),
                                        title: img.attr('alt'),
                                        size_class: img.attr('size_class'),


                                    })
                                    break;
                                case 'text':

                                    var txt=$(item).find('.txt')

                                    if($(item).find('.panel_txt_control').hasClass('hide')){
                                        var text=txt.html()
                                    }else{
                                        var text=txt.froalaEditor('html.get')
                                    }



                                   items.push({
                                        type: $(item).data('type'),
                                        text: text,
                                        padding:txt.data('padding'),
                                        size_class: txt.attr('size_class'),



                                    })
                                    break;
                            }



                        })


                        sections.push({
                            type: ($(section).hasClass('anchor')?'anchor':'non_anchor'),
                            title: ($(section).hasClass('anchor')?'':$(section).find('.title').html()),
                            subtitle: ($(section).hasClass('anchor')?'':$(section).find('.sub_title').html()),
                            items:items

                        })
                    });

                    blocks.push({
                        type: 'category_categories', label: '{t}Categories{/t} ({t}Sections{/t})', icon: 'fa-th', show: ($(obj).hasClass('hide') ? 0 : 1), top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),
                        sections: sections
                    })

                    break;
                case 'blackboard':
                    var images = []
                    var texts = []

                    $('.blackboard_image ', obj).each(function (i, image_block) {

                        var img = $(image_block).find('img')




                        images.push({
                            id:$(image_block).attr('id'),

                            src:img.data('src'),
                            image_website: ( (img.attr('src')!='EcomB2B/'+img.data('image_website') || img.width()!=img.data('width')   )?'': img.data('image_website')),

                            link: img.attr('link'),
                            title: img.attr('alt'),
                            width:img.width(),
                            height:img.height(),
                            top:img.position().top,
                            left:img.offset().left
                        })
                    });

                    $('.blackboard_text ', obj).each(function (i, text_block) {

                       if($(text_block).hasClass('froala_on')){
                           var text=$(text_block).froalaEditor('html.get')
                       }else{
                           var text=$(text_block).html()
                       }


                     var   _text=''
                        $(text).each(function( index ) {
                            if(!$( this ).is(':empty')){

                                _text=_text+ $(this).clone().wrap('<p>').parent().html();
                            }
                            });

                       //console.log($(text))

                        texts.push({
                            id:$(text_block).attr('id'),
                            text: _text,
                            width:$(text_block).width(),
                            height:$(text_block).height(),
                            top:$(text_block).position().top,
                            left:$(text_block).offset().left
                        })
                    });


                    blocks.push({
                        type: 'blackboard', label: '{t}Blackboard{/t}', icon: 'fa-image', show: ($(obj).hasClass('hide') ? 0 : 1), top_margin: $(obj).attr('top_margin'), bottom_margin: $(obj).attr('bottom_margin'),
                        height: $('.blackboard').height(),
                        images: images,
                        texts: texts
                    })
                    break;

                case 'text':





                    var text_blocks = []


                    $('.text_block', obj).each(function (i, text_block) {

                        var text=$(text_block).froalaEditor('html.get')

                        //console.log(text_block)
                        //console.log(text)

                        text_blocks.push({ text: text})

                    });



;


                    blocks.push({
                        type: 'text',
                        label: '{t}Text{/t}',
                        icon: 'fa-font',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),

                        template: $(obj).find('.text_blocks').data('template'),
                        text_blocks: text_blocks,

                    })

                    break;

                case 'images':
                    var images = []

                    $('.blk_images .image', obj).each(function (i, col) {

                        var img = $(col).find('img')

                        _col = {
                            src: img.attr('src'),
                            link: img.attr('link'),
                            title: img.attr('alt'),
                            caption_class: img.attr('display_class'),
                            caption: $(col).find('figcaption').html(),
                            width:img.data('width')
                        }


                        //  console.log(_col)

                        images.push(_col)

                    });


                    blocks.push({
                        type: 'images',
                        label: '{t}Images{/t}',
                        icon: 'fa-photo',
                        show: ($(obj).hasClass('hide') ? 0 : 1),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),

                        images: images,
                        template:$(obj).find('.blk_images').attr('template'),
                    })
                    break;


                case 'basket':


                    var content_data = {
                        type: 'basket', label: '{t}Basket{/t}', icon: 'fa-basket', show: 1,


                    }

                    $('[contenteditable=true]').each(function (i, obj) {

                        if ($(obj).hasClass('website_localized_label')) {
                            labels[$(obj).attr('id')] = $(obj).html()
                        } else {
                            content_data[$(obj).attr('id')] = $(obj).html()
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
                        type: 'counter', label: '{t}Counter{/t}', icon: 'fa-sort-numeric-asc', show: ($(obj).hasClass('hide') ? 0 : 1), columns: columns

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

                case 'telephone':

                    blocks.push({
                        type: 'telephone', label: '{t}Telephone{/t}', icon: 'fa-phone', show: ($(obj).hasClass('hide') ? 0 : 1),

                        _title: $(obj).find('._title').html(), _text: $(obj).find('._text').html(), _telephone: $(obj).find('._telephone').html(),

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

                        src: $(obj).find('iframe').attr('_src'),
                        top_margin: $(obj).attr('top_margin'),
                        bottom_margin: $(obj).attr('bottom_margin'),
                    })




            }

        });


        content_data.blocks = blocks

        console.log(content_data)

      // return;

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'save_webpage_content')
        ajaxData.append("key", '{$webpage->id}')
        ajaxData.append("content_data", JSON.stringify(content_data))
        ajaxData.append("labels", JSON.stringify(labels))


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




    $(document).on('change', '.image_upload', function (e) {


        var ajaxData = new FormData();

        //var ajaxData = new FormData( );
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                ajaxData.append('files', file);
                return false;
            });
        }


        $.each($(this).prop("files"), function (i, file) {
            ajaxData.append("files[" + i + "]", file);
            return false;
        });


        ajaxData.append("tipo", 'upload_images')
        ajaxData.append("parent", 'webpage')
        ajaxData.append("parent_key", '{$webpage->id}')
        ajaxData.append("options", JSON.stringify($(this).data('options')))
        ajaxData.append("response_type", 'webpage')

        var element = $(this)

        $.ajax({
            url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {

            }, success: function (data) {


              console.log(element.attr('name'))

                if (data.state == '200') {

                    $('#save_button', window.parent.document).addClass('save button changed valid')


                    if (element.attr('name') == 'two_pack') {


                        $(element).closest('.one_half').find('img').attr('src', data.image_src).attr('image_key', data.img_key)


                    } else if (element.attr('name') == 'images') {

                        //$('#image_control_panel').attr('img_src',data.image_src)
                        var img_element = $('#image_control_panel').find('.image_upload').data('img')


                        $(img_element).attr('src', data.image_src);


                    }else if (element.attr('name') == 'category_categories') {

                        var img_element = $('#image_control_panel').find('.image_upload').data('img')
                        $(img_element).attr('src', data.image_src);
                        $(img_element).data('src', data.image_src);


                    }else if(element.attr('name') =='category_categories_category'){
                        var img_element = element.closest('.category_wrap').find('.wrap_to_center img')

                        console.log(img_element)

                        $(img_element).attr('src', data.image_src);
                        $(img_element).data('src', data.image_src);


                    }else if (element.attr('name') == 'blackboard_image') {

                        //$('#image_control_panel').attr('img_src',data.image_src)
                        var img_element = $('#image_control_panel').find('.image_upload').data('img')

                        console.log(img_element)

                        $(img_element).resizable('destroy')
                        $(img_element).closest('.blackboard_image').draggable('destroy')

                        old_height= $(img_element).height()
                        old_width= $(img_element).width()

                        ratio=old_width/old_height

                        console.log(ratio)
                        console.log(data.ratio)

                        if(ratio<data.ratio){
                            width=old_width
                            height=width/data.ratio

                        }else{
                            height=old_height
                            width=data.ratio*height
                        }

                        $(img_element).height(height)
                        $(img_element).width(width)

                        $(img_element).closest('.blackboard_image').height(height)
                        $(img_element).closest('.blackboard_image').width(width)

                        $(img_element).attr('src', data.image_src);
                        $(img_element).data('src', data.image_src);


                        set_up_blackboard_image( $(img_element).closest('.blackboard_image').attr('id'))

                        update_image()



                    }


                    //$('#save_button', window.parent.document).addClass('save button changed valid')

                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "{t}OK{/t}"
                    });
                }

                element.val('')

            }, error: function () {

            }
        });


    });


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

console.log($('#' + key).html())
        $('#' + key).froalaEditor({
            toolbarInline: true,
            charCounterCount: false,
            toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsMD: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsSM: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsXS: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            defaultImageDisplay: 'inline',
            zIndex: 1000,
            pastePlain: true,
            imageUploadURL: '/ar_upload.php',
            imageUploadParams: {
                tipo: 'upload_images', parent: 'old_page', parent_key: $('#blocks').data('webpage_key'), parent_object_scope: JSON.stringify({
                    scope: 'block', block_key: key

                }), response_type: 'froala'

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

        }else if(type=='category_categories'){

            $('#update_images_block_image').attr('name','category_categories')
            $('#image_control_panel .caption_tr').addClass('hide')


            switch($(element).attr('size_class')){
                case 'panel_1':
                    $('#image_control_panel .image_size').html('(226x220)')
                    image_options['fit_to_canvas']='226x220'

                    break;
                case 'panel_2':
                    $('#image_control_panel .image_size').html('(470x220)')
                    image_options['fit_to_canvas']='470x220'

                    break;
                case 'panel_3':
                    $('#image_control_panel .image_size').html('(714x220)')
                    image_options['fit_to_canvas']='714x220'

                    break;
                case 'panel_4':
                    $('#image_control_panel .image_size').html('(958x220)')
                    image_options['fit_to_canvas']='958x220'

                    break;
                case 'panel_5':
                    $('#image_control_panel .image_size').html('(1202x220)')
                    image_options['fit_to_canvas']='1202x220'

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

        console.log($(element).attr('alt'))


        $('#image_control_panel').find('.image_control_panel_upload_td input').attr('block_key',block_key).data('options',image_options)


        $('#image_control_panel').find('.image_tooltip').val($(element).attr('alt'))
        $('#image_control_panel').find('.image_link').val($(element).attr('link'))
        $('#image_control_panel').attr('old_image_src', $(element).attr('src'))

        $('#image_control_panel').find('.caption_align i').addClass('super_discreet').removeClass('selected')
        $('#image_control_panel').find('.caption_align i.' + $(element).attr('display_class')).removeClass('super_discreet').addClass('selected')

        $('#image_control_panel').find('.image_upload').data('img', $(element))


    }

    function close_image_control_panel() {


        var image = $('#image_control_panel').data('element')

        image.attr('src', $('#image_control_panel').attr('old_image_src'))


        $('#image_control_panel').addClass('hide')

    }

    function update_image() {

        // var   image=  $('.blk_images .image:nth-child('+$('#image_control_panel').attr('image_index')+') img')

        var image = $('#image_control_panel').data('element');

        image.attr('alt', $('#image_control_panel').find('.image_tooltip').val())
        image.attr('link', $('#image_control_panel').find('.image_link').val())

        var caption_class = $('#image_control_panel').find('.caption_align i.selected').attr('display_class')
        image.attr('display_class', caption_class)

        image.closest('figure').find('figcaption').removeClass('caption_left caption_right caption_center caption_hide').addClass(caption_class)

        $('#image_control_panel').addClass('hide')
        $('#save_button', window.parent.document).addClass('save button changed valid')


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


        text = $('<div  id='+id+' class="blackboard_text" style="position:absolute;width:150px;height:100px;text-align:center" ><h1>Bla bla</h1><p>bla bla bla</p></div>').appendTo($('#blackboard_'+key));


        set_up_blackboard_text(id)

    }



    function set_up_images(key){

        $('#block_'+key+' .blk_images').sortable({
            stop: function (event, ui) {

                $('#save_button',window.parent.document).addClass('save button changed valid')


            }
        })

    }

    function delete_image(){
        console.log($('#image_control_panel').data('element'))

        if($('#image_control_panel').data('element').hasClass('panel')){
            $('#image_control_panel').data('element').closest('.category_wrap').remove()

        }else{
            $('#image_control_panel').data('element').remove()

        }

        close_image_control_panel()
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


    $(document).on( "click", ".category_block .wrap_to_center", function() {
        $(this).closest('.category_wrap').addClass('sortable-disabled')
      $(this).closest('.category_block').next('.item_overlay').removeClass('hide')

    })
    $(document).on( "click", ".category_wrap .txt", function() {

        edit_panel_text($(this).closest('.category_wrap'))

    })


    $(document).on( "click", ".close_category_block", function() {
        $(this).closest('.category_wrap').addClass('sortable-disabled')


        var title=$(this).closest('.item_overlay').find('.item_header_text').html()

        $(this).closest('.category_wrap').find('.category_block .item_header_text').html(title)
        $(this).closest('.category_wrap').removeClass('sortable-disabled')


        $(this).closest('.item_overlay').addClass('hide')

    })

    $(document).on('click', '.category_wrap img.panel', function (e) {
        open_image_control_panel(this,'category_categories');
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

    function add_category_categories_section(block_key,section_index,category_element){
        $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').append($(category_element))
    }

    function category_categories_add_panel(block_key,section_index,type,size){




        if(type=='text'){
            switch(size){
                case 1:
                    panel=$('<div class="category_wrap" data-type="text"><div style="padding:20px" size_class="panel_1" data-padding="20" class="txt panel_1">bla bla</div></div>')
                    $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').prepend(panel)

                    break;
                case 2:
                    panel=$('<div class="category_wrap" data-type="text"><div style="padding:20px"  size_class="panel_2" data-padding="20" class="txt panel_2">bla bla</div></div>')
                    $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').prepend(panel)

                    break;
                case 3:
                    panel=$('<div class="category_wrap" data-type="text"><div style="padding:20px" size_class="panel_3"  data-padding="20" class="txt panel_3">bla bla</div></div>')
                    $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').prepend(panel)

                    break;
                case 4:
                    panel=$('<div class="category_wrap" data-type="text"><div style="padding:20px" size_class="panel_4"  data-padding="20" class="txt panel_4">bla bla</div></div>')
                    $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').prepend(panel)

                    break;
                case 5:
                    panel=$('<div class="category_wrap" data-type="text"><div style="padding:20px"  size_class="panel_5" data-padding="20" class="txt panel_5">bla bla</div></div>');;
                    $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').prepend(panel)

                    break;

            }

          //  console.log( panel.find('.txt').uniqueId())

            $( "#panel_txt_control .panel_txt_control" ).clone().prependTo(panel);





            edit_panel_text(panel)

        }else if(type=='image'){

            switch(size){
                case 1:
                    panel=$('<div class="category_wrap" data-type="image"><img class="panel panel_1" size_class="panel_1" alt="" link="" data-image_website=""  data-src="http://via.placeholder.com/226x220" src="http://via.placeholder.com/226x220"  /></div>')
                    $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').prepend(panel)

                    break;
                case 2:
                    panel=$('<div class="category_wrap" data-type="image"><img class="panel  panel_2"  size_class="panel_2" alt="" link="" data-image_website=""  data-src="http://via.placeholder.com/470x220"  src="http://via.placeholder.com/470x220"  /></div>')
                    $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').prepend(panel)

                    break;
                case 3:
                    panel=$('<div class="category_wrap data-type="image""><img class="panel panel_3"   size_class="panel_3" alt="" link="" data-image_website=""  data-src="http://via.placeholder.com/714x220"  src="http://via.placeholder.com/714x220"  /></div>')
                    $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').prepend(panel)

                    break;
                case 4:
                    panel=$('<div class="category_wrap" data-type="image"><img class="panel  panel_4"   size_class="panel_4" alt="" link="" data-image_website=""  data-src="http://via.placeholder.com/958x220"  src="http://via.placeholder.com/958x220"  /></div>')
                    $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').prepend(panel)

                    break;
                case 5:
                    panel=$('<div class="category_wrap" data-type="image"><img class="panel  panel_5"   size_class="panel_5" alt="" link=""  data-image_website=""  data-src="http://via.placeholder.com/1202x220"  src="http://via.placeholder.com/1202x220"  /></div>')
                    $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').prepend(panel)

                    break;


            }


        }
        $('#save_button',window.parent.document).addClass('save button changed valid')

    }

    function edit_panel_text(panel) {

        panel.find('.txt').uniqueId()

        panel.find('.panel_txt_control').removeClass('hide')

        var panel_id = panel.addClass('sortable-disabled').find('.txt').attr('id');
        set_up_froala_editor(panel_id)

    }

    function close_panel_text(element) {

        $(element).closest('.panel_txt_control').addClass('hide').closest('.category_wrap').removeClass('sortable-disabled').find('.txt').froalaEditor('destroy')

        $(element).closest('.category_wrap').find('.txt').addClass('fr-view')

    }

</script>


</body>

</html>

