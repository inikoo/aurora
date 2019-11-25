{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 April 2018 at 23:51:55 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div id="category_products_add_panel_dialog" class="hide" style="width:300px;position:absolute;border:1px solid #ccc;background-color:white;padding:20px;z-index: 1005">
    <div style="margin-bottom:5px"><i onClick="$('#category_products_add_panel_dialog').addClass('hide')" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i></div>
    <div class="add_panel" style="display:flex;">
        <div class="flex-item button" data-size="1">1x</div>
        <div class="flex-item button" data-size="2">2x</div>
        <div class="flex-item button" data-size="3">3x</div>
        <div class="flex-item button" data-size="4">4x</div>
        <div class="flex-item button" data-size="5">5x</div>
    </div>
</div>


<div id="category_products_items_showcase" class="hide" style="z-index: 2000;background-color: #fff;padding:20px;border:1px solid #ccc;width: 300px;position: absolute;">
    <div style="margin-bottom:5px"><i onClick="$('#category_products_items_showcase').addClass('hide')" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i></div>

    <table style="width:100%;border-bottom: 1px solid #ccc;margin-top: 10px">
        <tr>
            <td onclick="open_add_panel_dialog('text')" style="border-top: 1px solid #ccc" class=" button"><span><i class="fa fa-font fa-fw " style="margin-right: 50px" aria-hidden="true"></i> {t}Text{/t}</span></td>
        </tr>
        <tr>
            <td onclick="open_add_panel_dialog('image')" style="border-top: 1px solid #ccc" class=" button"><span><i class="fa fa-camera fa-fw " style="margin-right: 50px" aria-hidden="true"></i> {t}Image{/t}</span></td>
        </tr>
        <tr>
            <td onclick="category_products_add_panel()" style="border-top: 1px solid #ccc" class=" button"><span><i class="fa fa-video fa-fw " style="margin-right: 50px" aria-hidden="true"></i> {t}Video{/t} (YouTube)</span></td>
        </tr>

    </table>

</div>




<div id="category_products_items_sort_options" class="hide" style="z-index: 2000;background-color: #fff;padding:20px;border:1px solid #ccc;width: 300px;position: absolute;">
    <div style="margin-bottom:5px"><i onClick="$('#category_products_items_sort_options').addClass('hide')" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i></div>

    <table style="width:100%;border-bottom: 1px solid #ccc;margin-top: 10px">
        <tr>
            <td onclick="change_category_products_items_sort('Manual')" style="border-top: 1px solid #ccc" class=" button"><span><i class="far fa-hand-heart  fa-fw " style="margin-right: 50px" aria-hidden="true"></i> <span class="label">{t}Hand picked sort{/t}</span></span></td>
        </tr>
        <tr>
            <td onclick="change_category_products_items_sort('Code')" style="border-top: 1px solid #ccc" class=" button"><span><i class="far fa-sort-numeric-down fa-fw " style="margin-right: 50px" aria-hidden="true"></i> <span class="label">{t}Code{/t} (1-9)</span></span></td>
        </tr>
        <tr>
            <td onclick="change_category_products_items_sort('Code_desc')" style="border-top: 1px solid #ccc" class=" button"><span><i class="fa fa-sort-numeric-up fa-fw " style="margin-right: 50px" aria-hidden="true"></i> <span class="label">{t}Code{/t} (9-1)</span></span></td>
        </tr>
        <tr>
            <td onclick="change_category_products_items_sort('Name')" style="border-top: 1px solid #ccc" class=" button"><span><i class="fa fa-sort-alpha-down  fa-fw " style="margin-right: 50px" aria-hidden="true"></i> <span class="label">{t}Name{/t} (a-z)</span></span></td>
        </tr>



    </table>

</div>


<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div id="edit_mode_main_{$key}" class="main" style="float:left;margin-right:20px;min-width: 200px;">


        <i class="toggle_view_category_products fa-fw fal fa-cogs   button" title="{t}Backstage view{/t}" title_alt="{t}Display view{/t}" style="position: relative;left:-12px;bottom:1.05px"></i>


        <span style="margin-left:50px">{t}Margin{/t}:</span>
        <input data-margin="top" class="edit_block_margin edit_block_input top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}" placeholder="0"><input data-margin="bottom" class="edit_block_margin edit_block_input bottom"
                                                                                                                                                      value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}"
                                                                                                                                                      placeholder="0">


        <span id='toggle_category_products_item_headers' onclick="toggle_category_products_item_headers(this)" class="padding_left_20 unselectable button"><i class="fa {if $block.item_headers}fa-toggle-on{else}fa-toggle-off{/if}"></i> {t}Header{/t}</span>
        <span onclick="show_category_products_items_showcase(this)" class="padding_left_20 unselectable button"><i class="fa fa-plus"></i> {t}Item{/t}</span>

        <span   onclick="show_category_products_items_sort_options(this)"  class="padding_left_20 unselectable button category_products_items_sort_labels">
            {if $block.sort=='Manual'}<i class="far fa-hand-heart"></i> <span>{t}Hand picked sort{/t}</span>{elseif $block.sort=='Code'}<i class="far fa-sort-numeric-down"></i> <span title="{t}Sort by code 0-9 (ascending){/t}">{t}Code{/t}</span>{elseif $block.sort=='Code_desc'}<i class="far fa-sort-numeric-up"></i> <span title="{t}Sort by code 9-0 (descending){/t}">{t}Code{/t}</span>{elseif $block.sort=='Name'}<i class="far fa-sort-alpha-up"></i> <span title="{t}Sort by name a-z (ascending){/t}">{t}Name{/t}</span>{/if}
        </span>

        {*
        // todo: option to put the new products first
        <span onclick="show_category_products_items_showcase(this)" class="hide padding_left_20 unselectable button {if $block.new_first}very_discreet{/if}"> <i class="fas fa-seedling"></i> {t}New first{/t}</span>
        *}

        <span onclick="show_category_products_items_showcase(this)" class="hide padding_left_20 unselectable button"> <i class="fas fa-ban"></i> {t}No stock last{/t}</span>






    <div style="clear: both"></div>
    </div>
</div>
<script>


    function toggle_category_products_item_headers(element) {

        var icon = $(element).find('i');
        if (icon.hasClass('fa-toggle-on')) {
            icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')
            var value = 'off';
        } else {
            icon.removeClass('fa-toggle-off').addClass('fa-toggle-on')
            var value = 'on';

        }
        $('#preview')[0].contentWindow.update_category_products_item_headers('{$key}', value)

    }


    function show_category_products_items_showcase(element) {

        if ($('#category_products_items_showcase').hasClass('hide')) {
            $('#category_products_items_showcase').removeClass('hide').offset({
                left: $(element).offset().left
            })
        } else {
            $('#category_products_items_showcase').addClass('hide')

        }


    }


    function open_add_panel_dialog(type) {


        $('#category_products_add_panel_dialog').removeClass('hide').offset({
            left: $('#category_products_items_showcase').offset().left, top: $('#category_products_items_showcase').offset().top
        }).data('type', type)

        $('#category_products_items_showcase').addClass('hide')

    }

    function show_category_products_items_sort_options(element){
        if($('#category_products_items_sort_options').hasClass('hide')){
            $('#category_products_items_sort_options').removeClass('hide').offset({
                left: $(element).offset().left, top: $(element).offset().top
            })

        }else{
            $('#category_products_items_sort_options').addClass('hide')
        }
    }


    $(document).on('click', '#category_products_add_panel_dialog .add_panel div', function (e) {


        var type = $('#category_products_add_panel_dialog').data('type')
        var size = $(this).data('size')


        if ($('#toggle_category_products_item_headers i').hasClass('fa-toggle-on')) {
            var height = 330
        } else {
            var height = 290
        }

        $('#preview')[0].contentWindow.add_panel('{$key}', type, size, 'category_products', height)

        $('#category_products_items_showcase').addClass('hide')
        $('#category_products_add_panel_dialog').addClass('hide')


    })


    function category_products_add_panel() {

        var size=2
        if ($('#toggle_category_products_item_headers i').hasClass('fa-toggle-on')) {
            var height = 330
        } else {
            var height = 290
        }

        $('#preview')[0].contentWindow.add_panel('{$key}', 'video', size, 'category_products', height)

        $('#category_products_items_showcase').addClass('hide')


    }

    function change_category_products_items_sort(type){


        $('#category_products_items_sort_options').addClass('hide')

        console.log(type)

        switch (type){
            case 'Manual':
                $('.category_products_items_sort_labels').html('<i class="far fa-hand-heart"></i> <span>{t}Hand picked sort{/t}</span>')
                break;
            case 'Code':
                $('.category_products_items_sort_labels').html('<i class="far fa-sort-numeric-down"></i> <span title="{t}Sort by code 0-9 (ascending){/t}">{t}Code{/t}</span>')
                break;
            case 'Code_desc':
                $('.category_products_items_sort_labels').html('<i class="far fa-sort-numeric-up"></i> <span title="{t}Sort by code 9-0 (descending){/t}">{t}Code{/t}</span>')
                break;
            case 'Name':
                $('.category_products_items_sort_labels').html('<i class="far fa-sort-alpha-up"></i> <span title="{t}Sort by name a-z (ascending){/t}">{t}Name{/t}</span>')
                break;
        }

        $('#preview')[0].contentWindow.sort_category_products_items('{$key}', type)


    }

    function category_products_change_sort_to_manual(){
        $('.category_products_items_sort_labels').html('<i class="far fa-hand-heart"></i> <span>{t}Hand picked sort{/t}</span>')

    }

</script>