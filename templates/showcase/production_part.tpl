{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 September 2017 at 15:19:13 GMT+8, Kuala Lumpur, , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div class="name_and_categories">
    <span class="strong"><span class="Supplier_Part_Description">{$production_part->get('Supplier Part Description')}</span></span>
    <ul class="tags Categories" style="float:right">
        {foreach from=$part->get_category_data() item=item key=key}
            <li><span class="button" onclick="change_view('category/{$item.category_key}')"
                      title="{$item.label}">{$item.code}</span></li>
        {/foreach}
    </ul>
    <div style="clear:both">
    </div>
</div>
{include file="sticky_note.tpl" value=$production_part->get('Sticky Note') object="Supplier_Part" key="{$production_part->id}" field="Supplier_Part_Sticky_Note"  }

<div class="asset_container">
    <div class="block picture">
        <div class="data_container">
            {assign "image_key" $part->get_main_image_key()}
            <div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}">
                <img alt="" src="/{if $image_key}image_root.php?id={$image_key}&amp;size=small{else}art/nopic.png{/if}">
            </div>
            {include file='upload_main_image.tpl' object='Part' key=$part->id class="{if $image_key!=''}hide{/if}"}
        </div>
        <div style="clear:both">
        </div>
    </div>
    <div class="block carton_sko_units">
        <table border="0">
            <tr class="carton">
                <td class=" canvas">
                    <canvas width="80" height="80">
                    </canvas>
                </td>
                <td class="info">
                    <div>
                        {t}Carton{/t}
                    </div>
                    <div>
                        <span class="Carton_Weight padding_right_5">{$production_part->get('Carton Weight')}</span> <span
                                class="Carton_CBM">{$production_part->get('Carton CBM')}</span>
                    </div>
                    <div>
                    </div>
                    <div>
                        <span class="Carton_Cost">{$production_part->get('Carton Cost')}</span> <span
                                class="Carton_Delivered_Cost_container discreet {if $production_part->get('Supplier Part Currency Code')==$account->get('Account Currency') and !$production_part->get('Supplier Part Carton Extra Cost')>0  }hide{/if}">(<span
                                    class="Carton_Delivered_Cost">{$production_part->get('Carton Delivered Cost')}</span>)</span>
                    </div>
                </td>
            </tr>
            <tr class="sko">
                <td class="canvas">
                    <canvas width="80" height="80">
                    </canvas>
                </td>
                <td class="info">
                    <div>
                        {t}SKO{/t} (<span
                                class="Packages_Per_Carton">{$production_part->get('Supplier Part Packages Per Carton')}</span> {t}per carton{/t}
                        )
                    </div>
                    <div>
                        <span class="SKO_Barcode">{$production_part->get('SKO Barcode')}</span>
                    </div>
                    <div>
                        <span class="SKO_Weight padding_right_5">{$production_part->get('SKO Weight')}</span> <span
                                class="SKO_Dimensions">{$production_part->get('SKO Dimensions')}</span>
                    </div>
                    <div>
                        <span class="SKO_Cost">{$production_part->get('SKO Cost')}</span> <span
                                class="SKO_Delivered_Cost_container discreet {if $production_part->get('Supplier Part Currency Code')==$account->get('Account Currency') and !$production_part->get('Supplier Part SKO Extra Cost')>0  }hide{/if}">(<span
                                    class="SKO_Delivered_Cost">{$production_part->get('SKO Delivered Cost')}</span>)</span>
                    </div>
                </td>
            </tr>
            <tr class="unit">
                <td class="canvas">
                    <canvas width="80" height="80">
                    </canvas>
                </td>
                <td class="info">
                    <div>
                        {t}Unit{/t} (<span
                                class="Supplier_Part_Units_Per_Package">{$production_part->get('Supplier Part Units Per Package')}</span> {t}per SKO{/t}
                        )
                    </div>
                    <div>
                        <span class="Unit_CBM">{$production_part->get('Unit Dimensions')}</span>
                    </div>
                    <div>
                        <span class="Unit_Weight">{$production_part->get('Unit Weight')}</span>
                    </div>
                    <div>
                        <span class="Unit_Cost_Amount">{$production_part->get('Unit Cost Amount')}</span> <span
                                class="Unit_Delivered_Cost_container discreet {if $production_part->get('Supplier Part Currency Code')==$account->get('Account Currency') and !$production_part->get('Supplier Part Unit Extra Cost')>0  }hide{/if}">(<span
                                    class="Unit_Delivered_Cost">{$production_part->get('Unit Delivered Cost')}</span>)</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="block info">
        <div id="overviews">
            <div style="margin-bottom: 5px">

                        <span class="Supplier_Part_Units_Per_Package  "
                              title="{t}Units per package{/t}">{$production_part->get('Supplier Part Units Per Package')}</span>
                    <i class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i> [<span
                            class="Packages_Per_Carton"
                            title="{t}Packages per carton{/t}">{$production_part->get('Supplier Part Packages Per Carton')}</span>]
                    <span class="discreet Supplier_Part_Units_Per_Carton padding_left_10"
                          title="{t}Units per carton{/t}">{$production_part->get('Units Per Carton')}</span>
            </div>
            <table border="0" class="overview" style="">

                <tr class="main">
                    <td><span class="Average_Delivery">{$production_part->get('Average Delivery')}</span></td>
                    <td class="aright highlight Status">{$production_part->get('Status')} </td>
                </tr>
                <tr class="">
                    <td>{t}Current landed cost{/t}</td>
                    <td class="aright">{$production_part->get('Unit Delivered Cost')}</td>
                </tr>
            </table>
            <div id="part_data" style="padding-top:10px;clear:both">
                <table border="0" class="overview with_title">
                    <tr class="top">
                        <td>{t}Part{/t} <span class="Part_Reference button padding_left_10"
                                              onclick="change_view('part/{$part->id}')">{$part->get('Reference')}</span>
                        </td>
                        <td class="aright"> {$part->get('Status')} </td>
                    </tr>
                    <tr class="main {if $part->get('Part Status')=='Not In Use'}hide{/if} ">
                        <td>{$part->get('Available Forecast')}</td>
                        <td class="aright highlight"> {$part->get('Current On Hand Stock')} {$part->get('Stock Status Icon')} </td>
                    </tr>
                </table>
            </div>


            <div id="purchase_orders_data" style="padding-top:10px;clear:both">
                <table border="0" class="overview with_title">
                    <tr class="top">
                        <td colspan="3">{t}Next deliveries{/t}</td>
                    </tr>
                    {foreach from=$production_part->get_next_deliveries_data() item=next_delivery }
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
    <div style="clear:both">
    </div>
</div>
