{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2018 at 18:06:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}







<div id="products_add_product_dialog" class="hide" style="width:300px;position:absolute;border:1px solid #ccc;background-color:white;padding:20px;z-index: 1002">
    <div style="margin-bottom:5px">  <i  onClick="close_products_add_product_dialog()" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i>  </div>
    <table class="edit_container" >
        <tr>
            <td>


                <input id="add_product" type="hidden" class=" input_field" value="" has_been_valid="0"/>
                <input id="add_product_dropdown_select_label" field="add_product" style="width:200px" scope="product_webpages" parent="website" data-metadata='{ "parent_category_key":"{$webpage->get('Webpage Scope Key')}"}'

                       parent_key="{$website->id}" class=" dropdown_select" value="" has_been_valid="0" placeholder="{t}Product code{/t}" action="add_product_to_webpage"

                />
                <span id="add_product_msg" class="msg"></span>
                <div id="add_product_results_container" class="search_results_container hide" style="position: relative;left:-430px" >

                    <table id="add_product_results" >

                        <tr class="hide" id="add_product_search_result_template" field="" value="" formatted_value="" onClick="select_dropdown_add_product_to_products_webpage_block(this)">
                            <td class="code"></td>
                            <td style="width:85%" class="label"></td>

                        </tr>
                    </table>

                </div>
                <script>
                    $("#add_product_dropdown_select_label").on("input propertychange", function (evt) {

                        var delay = 100;
                        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
                        delayed_on_change_dropdown_select_field($(this), delay)
                    });
                </script>


            </td>


        </tr>
    </table>


</div>




<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div id="edit_mode_main_{$key}" class="main" style="float:left;margin-right:20px;min-width: 200px;">


        <i class="toggle_view_category_products fa-fw fal fa-cogs   button" title="{t}Backstage view{/t}" title_alt="{t}Display view{/t}" style="position: relative;left:-12px;bottom:1.05px"></i>


        <span style="margin-left:50px">{t}Margin{/t}:</span>
        <input data-margin="top" class=" edit_margin top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}" placeholder="0"><input data-margin="bottom" class=" edit_margin bottom"
                                                                                                                                                      value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}"
                                                                                                                                                      placeholder="0">


        <span id='toggle_products_title' onclick="toggle_block_title(this)" class="padding_left_20 unselectable button"><i class="fa {if $block.show_title}fa-toggle-on{else}fa-toggle-off{/if}"></i> {t}Title{/t}</span>

        <span class="hide" id='toggle_category_products_item_headers' onclick="toggle_products_item_headers(this)" class="padding_left_20 unselectable button" title="{t}Product's headers{/t}"><i class="fa {if $block.item_headers}fa-toggle-on{else}fa-toggle-off{/if}"></i> {t}headers{/t}</span>

        <span id='open_add_product_dialog' onclick="open_products_add_product_dialog(this)" class="padding_left_20 unselectable button"><i class="fa fa-plus"></i> {t}Product{/t}</span>






    <div style="clear: both"></div>
    </div>
</div>

{*
for scripts look webpage_preview.tpl
*}