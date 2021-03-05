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

    <div style="clear:both">
    </div>
</div>
<div class="sticky_notes">
{include file="sticky_note.tpl" value=$production_part->get('Sticky Note') object="Supplier_Part" key="{$production_part->id}" field="Supplier_Part_Sticky_Note"  }
</div>
<div class="asset_container">
    <div class="block picture">
        <div class="data_container">
            {assign "image_key" $part->get_main_image_key()}
            <div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}">
                <img alt="" src="/{if $image_key}image.php?id={$image_key}&amp;s=270x270{else}art/nopic.png{/if}">
            </div>
            {include file='upload_main_image.tpl' object='Part' parent_object_scope="Marketing" key=$part->id class="{if $image_key!=''}hide{/if}"}
        </div>
        <div style="clear:both">
        </div>
    </div>

    <div class="block info">
        <div id="overviews">
            <div style="margin-bottom: 5px">


                <div class="packing_info">{if $production_part->get('Supplier Part Units Per Package')==1}{t}Packed individually{/t}{else}{t}Packed in{/t} {$production_part->get('Supplier Part Units Per Package')}{/if}</div>
                <div class="carton_info small" style="padding-top:5px;font-size: small ">
                    {if $production_part->get('Supplier Part Packages Per Carton')>1}
                        {t}Packs per carton{/t}: {$production_part->get('Packages Per Carton')}
                    {/if}
                </div>




            </div>
            <table class="overview" >


                <tr >
                    <td>{t}Cost{/t}</td>
                    <td class="aright">{$production_part->get('Unit Delivered Cost')}</td>
                </tr>
            </table>






        </div>
    </div>
    <div class="block info" style="margin-right: 30px;float: right">
        <div id="overviews">

            <div class="part_data" style="padding-top:10px;clear:both">
                <table class="overview with_title">
                    <tr class="top">
                        <td><i class="fa fa-box" title="{t}Part{/t}"></i> <span class="Part_Reference button padding_left_10"
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

            <table class="overview" >

                <tr class="main">
                    <td><span class="Status_Icon">{$production_part->get('Status Icon')}</span> <span title="{t}Production time{/t}" class="Average_Delivery">{$production_part->get('Average Delivery')}</span></td>
                    <td class="aright"><span class="very_discreet">{t}Can to make up{/t}:</span>  <span class="highlight Available_to_Make_up">{$production_part->get('Available to Make up')}</span></td>
                </tr>

            </table>


            <div id="purchase_orders_data" style="padding-top:10px;clear:both">
                <table class="overview with_title">
                    <tr class="top">
                        <td colspan="3">{t}Production queue{/t}</td>
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
