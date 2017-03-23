{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 March 2017 at 15:18:02 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if $part->get('Part Status')=='Discontinuing' or $part->get('Part Status')=='Not In Use' }
<div class="alert alert-error alert-title" style="text-align: center;margin-bottom: 0px">
    {t}This product is not longer required by client{/t}
</div>
{/if}

<div class="name_and_categories">
    <span class="strong"><span class="Supplier_Part_Unit_Description">{$part->get('Part Unit Description')}</span> <span
                class="Store_Product_Price">{$part->get('Price')}</span> </span>

    <div style="clear:both">
    </div>
</div>
<div class="asset_container">
    <div class="block picture">
        <div class="data_container">
            {assign "image_key" $part->get_main_image_key()}
            <div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}">
                <img src="/{if $image_key}image_root.php?id={$image_key}&amp;size=small{else}art/nopic.png{/if}">
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
                        <span class="Carton_Weight padding_right_5">{$supplier_part->get('Carton Weight')}</span> <span
                                class="Carton_CBM">{$supplier_part->get('Carton CBM')}</span>
                    </div>
                    <div>
                    </div>
                    <div>
                        <span class="Carton_Cost">{$supplier_part->get('Carton Cost')}</span> <span
                                class="Carton_Delivered_Cost_container discreet {if $supplier_part->get('Supplier Part Currency Code')==$account->get('Account Currency') and !$supplier_part->get('Supplier Part Carton Extra Cost')>0  }hide{/if}">(<span
                                    class="Carton_Delivered_Cost">{$supplier_part->get('Carton Delivered Cost')}</span>)</span>
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
                                class="Packages_Per_Carton">{$supplier_part->get('Supplier Part Packages Per Carton')}</span> {t}per carton{/t}
                        )
                    </div>
                    <div>
                        <span class="SKO_Barcode">{$supplier_part->get('SKO Barcode')}</span>
                    </div>
                    <div>
                        <span class="SKO_Weight padding_right_5">{$supplier_part->get('SKO Weight')}</span> <span
                                class="SKO_Dimensions">{$supplier_part->get('SKO Dimensions')}</span>
                    </div>
                    <div>
                        <span class="SKO_Cost">{$supplier_part->get('SKO Cost')}</span> <span
                                class="SKO_Delivered_Cost_container discreet {if $supplier_part->get('Supplier Part Currency Code')==$account->get('Account Currency') and !$supplier_part->get('Supplier Part SKO Extra Cost')>0  }hide{/if}">(<span
                                    class="SKO_Delivered_Cost">{$supplier_part->get('SKO Delivered Cost')}</span>)</span>
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
                                class="Supplier_Part_Units_Per_Package">{$supplier_part->get('Supplier Part Units Per Package')}</span> {t}per SKO{/t}
                        )
                    </div>
                    <div>
                        <span class="Unit_CBM">{$supplier_part->get('Unit Dimensions')}</span>
                    </div>
                    <div>
                        <span class="Unit_Weight">{$supplier_part->get('Unit Weight')}</span>
                    </div>
                    <div>
                        <span class="Unit_Cost_Amount">{$supplier_part->get('Unit Cost Amount')}</span> <span
                                class="Unit_Delivered_Cost_container discreet {if $supplier_part->get('Supplier Part Currency Code')==$account->get('Account Currency') and !$supplier_part->get('Supplier Part Unit Extra Cost')>0  }hide{/if}">(<span
                                    class="Unit_Delivered_Cost">{$supplier_part->get('Unit Delivered Cost')}</span>)</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="block info">
        <div id="overviews">
            <div style="margin-bottom: 5px">

                        <span class="Supplier_Part_Units_Per_Package  "
                              title="{t}Units per package{/t}">{$supplier_part->get('Supplier Part Units Per Package')}</span>
                    <i class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i> [<span
                            class="Packages_Per_Carton"
                            title="{t}Packages per carton{/t}">{$supplier_part->get('Supplier Part Packages Per Carton')}</span>]
                    <span class="discreet Supplier_Part_Units_Per_Carton padding_left_10"
                          title="{t}Units per carton{/t}">{$supplier_part->get('Units Per Carton')}</span>
            </div>
            <table border="0" class="overview" style="">

                <tr class="main">
                    <td><span class="Average_Delivery">{$supplier_part->get('Average Delivery')}</span></td>
                    <td class="aright highlight Status">{$supplier_part->get('Status')} </td>
                </tr>
                <tr class="purchase_order_data">
                    <td></td>
                </tr>
            </table>



            <table id="barcode_data" border="0" class="overview {if $part->get('Part Barcode Number')==''}hide{/if} ">
                <tr class="main">
                    <td class="label">
                        <i {if $part->get('Part Barcode Key')} class="fa fa-barcode button" onClick="change_view('inventory/barcode/{$part->get('Part Barcode Key')}')"{else}  class="fa fa-barcode"{/if} ></i>
                    </td>
                    <td class="Part_Barcode_Number highlight">{$part->get('Part Barcode Number')} </td>
                    <td class="barcode_labels aright {if !$part->get('Part Barcode Key')}hide{/if}">
                        <a title="{t}Stock keeping unit (Outer){/t}"
                           href="/asset_label.php?object=part&key={$part->id}&type=package"><i
                                    class="fa fa-tag "></i></a>
                        <a class="padding_left_10" title="{t}Commercial unit label{/t}"
                           href="/asset_label.php?object=part&key={$part->id}&type=unit"><i class="fa fa-tags "></i></a>
                    </td>

                </tr>


            </table>

        </div>
    </div>
    <div style="clear:both">
    </div>
    {if $part->get('Part Status')=='Discontinuing' or $part->get('Part Status')=='Not In Use' }
        <div class="alert alert-error alert-title" style="text-align: center;margin-bottom: 0px">
            {t}This product is not longer required by client{/t}
        </div>
    {/if}
</div>


