{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 March 2017 at 15:18:02 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div class="name_and_categories" style="border: none">
    <span class="strong"><span class="Supplier_Part_Reference margin_right_20">{$supplier_part->get('Supplier Part Reference')}</span>  <span
                class="Supplier_Part_Description">{$supplier_part->get('Supplier Part Description')}</span>   </span>

    <div style="float: right;padding-right: 10px">

        <span class="button" onclick="change_view('/supplier/{$supplier->id}')"> <i class="fa fa-hand-holding-box padding_right_5"></i><span class="link">{$supplier->get('Code')}</span></span>
    </div>

    <div style="clear:both">
    </div>
</div>


<div class="asset_container" style="border: none">
    <div class="block picture" style="float:left;border: none">
        <div class="data_container">
            {assign "image_key" $part->get_main_image_key()}
            <div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}">
                <img src="/{if $image_key}image.php?id={$image_key}&amp;s=270x270{else}art/nopic.png{/if}">
            </div>
            {include file='upload_main_image.tpl' object='Part' parent_object_scope="Marketing" key=$part->id class="{if $image_key!=''}hide{/if}"}
        </div>
        <div style="clear:both">
        </div>
    </div>
    <div style="float: left;">
        <div class="block carton_sko_units " style="width: 540px">


            <table class="overview">
                <tr>
                    <td>{t}Cost{/t}</td>
                    <td class="aright">{$supplier_part->get('Unit Cost')}</td>
                </tr>

                <tr>
                    <td>{t}Current landed cost{/t}</td>
                    <td class="aright">{$supplier_part->get('Unit Delivered Cost')}</td>
                </tr>
                <tr>
                    <td>{t}Minimum order (cartons){/t}</td>
                    <td class="aright Supplier_Part_Minimum_Carton_Order">{$supplier_part->get('Minimum Carton Order')}</td>
                </tr>
            </table>


        </div>
        <div class="block info">
            <div id="overviews">
                <div class="hide" style="margin-bottom: 5px">

                        <span class="Supplier_Part_Units_Per_Package  "
                              title="{t}Units per package{/t}">{$supplier_part->get('Supplier Part Units Per Package')}</span>
                    <i class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i> [<span
                            class="Packages_Per_Carton"
                            title="{t}Packages per carton{/t}">{$supplier_part->get('Supplier Part Packages Per Carton')}</span>]
                    <span class="discreet Supplier_Part_Units_Per_Carton padding_left_10"
                          title="{t}Units per carton{/t}">{$supplier_part->get('Units Per Carton')}</span>
                </div>
                <table class="overview">

                    <tr class="main">
                        <td><span class="Average_Delivery">{$supplier_part->get('Average Delivery')}</span></td>
                        <td class="aright highlight Status">{$supplier_part->get('Status')} </td>
                    </tr>

                </table>
                <div id="part_data" style="padding-top:10px;clear:both">
                    <table class="overview with_title">
                        <tr class="top">
                            <td>{t}Part{/t} <span class="Part_Reference  padding_left_10"
                                >{$part->get('Reference')}</span>
                            </td>
                            <td class="aright"> {$part->get('Status')} </td>
                        </tr>
                        <tr class="main {if $part->get('Part Status')=='Not In Use'}hide{/if} ">
                            <td>{$part->get('Available Forecast')}</td>
                            <td class="aright highlight"> {$part->get('Current On Hand Stock')} {$part->get('Stock Status Icon')} </td>
                        </tr>
                    </table>
                </div>

                {assign "next_deliveries" $supplier_part->get_next_deliveries_data()}


                <div class="{if $next_deliveries|@count==0}hide{/if} " style="padding-top:10px;clear:both">
                    <table class="overview with_title">
                        <tr class="top">
                            <td colspan="3">{t}Next deliveries{/t}</td>
                        </tr>
                        {foreach from=$next_deliveries item=next_delivery }
                            <tr class="main ">
                                <td>{$next_delivery.formatted_link}</td>
                                <td>{$next_delivery.formatted_state}</td>
                                <td class="aright highlight">{$next_delivery.qty}</td>
                            </tr>
                        {/foreach}
                    </table>
                </div>


            </div>
        </div>

        <div>
            <table class="overview  ">
                <tr class=" units_data">

                    <td>
                        <i class="fal fa-fw fa-stop-circle" title="{t}Unit{/t}"></i>
                    </td>
                    <td>

                    </td>

                    <td>


                        <i class="fal fa-barcode-alt fa-fw padding_right_5"></i>


                        <span class="Part_Barcode_Number" data-label_no_set="{t}Not set{/t}">{if $part->get('Part Barcode Number')==''}<span
                                    class="discreet italic">{t}Not set{/t}</span>{else}{$part->get('Part Barcode Number')}{/if}</span>
                        {if $part->get('Part Barcode Key')}
                            <i class="discreet_on_hover button fal fa-external-link-square" onClick="change_view('inventory/barcode/{$part->get('Part Barcode Key')}')"></i>
                        {/if}

                    </td>
                    <td>

                    <span class="pdf_label_container">
                    <img class="button pdf_link left_pdf_label_mark top_pdf_label_mark" onclick="download_pdf_from_ui($('.pdf_asset_dialog.unit'),'part',{$part->id},'unit')"
                         style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif">
                    <i onclick="show_pdf_settings_dialog(this,'part',{$part->id},'unit')" title="{t}PDF unit label settings{/t}" class="far very_discreet fa-sliders-h-square button"></i>
                    </span>


                    </td>


                    <td style="text-align: right">
                        <span class="Unit_Weight">{$part->get('Unit Weight')}</span>
                    </td>
                    <td style="text-align: right">
                        <span class="Unit_Dimensions">{$part->get('Unit Dimensions')}</span>
                    </td>

                </tr>
                <tr class="sko_data">

                    <td>
                        <i class="fal fa-box fa-fw" title="{t}SKO{/t}"></i>
                    </td>
                    <td style="padding-left: 4px">
                        <span class="discreet" title="{t}Units per SKO{/t}"><i class="fal fa-fwx fa-stop-circle very_discreet" style="font-size: 80%;margin-right: 1px"></i><i class="fal fa-fws very_discreet fa-times"
                                                                                                                                                                               style="position: relative;top:1px;margin-right: 3px"></i>{$part->get('Units Per Package')}</span>
                    </td>

                    <td>

                        <i class="fas fa-barcode-alt fa-fw padding_right_5"></i>
                        <span class="Part_SKO_Barcode" data-label_no_set="{t}Not set{/t}">{if $part->get('Part SKO Barcode')==''}<span
                                    class="discreet italic">{t}Not set{/t}</span>{else}{$part->get('Part SKO Barcode')}{/if}</span>

                    </td>
                    <td>
                      <span class="pdf_label_container">
                    <img class="button pdf_link left_pdf_label_mark top_pdf_label_mark" onclick="download_pdf_from_ui($('.pdf_asset_dialog.sko'),'part',{$part->id},'sko')"
                         style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif">
                    <i onclick="show_pdf_settings_dialog(this,'part',{$part->id},'sko')" title="{t}PDF SKO label settings{/t}" class="far very_discreet fa-sliders-h-square button"></i>
                    </span>
                    </td>

                    <td style="text-align: right">
                        <span class="Package_Weight">{$part->get('Package Weight')}</span>
                    </td>

                    <td style="text-align: right">
                        <span class="Package_Dimensions">{$part->get('Package Dimensions')}</span>
                    </td>

                </tr>
                <tr class="carton_data">

                    <td>
                        <i class="fal fa-pallet fa-fw" title="{t}Carton{/t}"></i>
                    </td>
                    <td style="padding-left: 4px">
                        <span class="discreet" title="{t}Units per carton{/t}"><i class="fal fa-fwx fa-stop-circle very_discreet" style="font-size: 80%;margin-right: 1px"></i><i class="fal fa-fws very_discreet fa-times"
                                                                                                                                                                                  style="position: relative;top:1px;margin-right: 3px"></i>{$part->get('Units Per Carton')} </span>
                        <span class="discreet" title="{t}SKOs per carton{/t}">({$part->get('SKOs per Carton')})</span>

                    </td>
                    <td>


                        <span class="Part_Carton_Barcode" data-label_no_set="{t}Not set{/t}">{if $part->get('Part Carton Barcode')==''}<span
                                    class="discreet error italic">{t}Not set{/t}</span>{else}{$part->get('Part Carton Barcode')}{/if}</span>


                    </td>

                    <td>

                            <span class="pdf_label_container">
                    <img class="button pdf_link left_pdf_label_mark top_pdf_label_mark" onclick="download_pdf_from_ui($('.pdf_asset_dialog.carton'),'supplier_part',{$supplier_part->id},'carton')"
                         style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif">
                    <i onclick="show_pdf_settings_dialog(this,'supplier_part',{$supplier_part->id},'carton')" title="{t}PDF carton label settings{/t}" class="far very_discreet fa-sliders-h-square button"></i>
                    </span>

                    </td>

                    <td style="text-align: right">
                        <span class="Carton_Weight " title="{t}Carton gross weight{/t}">{$part->get('Carton Weight')}</span>
                    </td>
                    <td style="text-align: right">
                        <span class="Carton_CBM" title="{t}Carton CBM{/t}">{$part->get('Carton CBM')}</span>
                    </td>
                </tr>

            </table>

            {include file="pdf_asset_dialog.tpl" asset='part' type='unit'}
            {include file="pdf_asset_dialog.tpl" asset='part' type='sko'}
            {include file="pdf_asset_dialog.tpl" asset='supplier_part' type='carton'}


        </div>
        <div style="clear:both">
        </div>


        <div style="clear:both">
        </div>
    </div>
</div>

<div style="clear:both">
</div>