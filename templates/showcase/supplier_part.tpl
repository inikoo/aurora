<div class="sticky_notes" >
{include file="sticky_note.tpl" value=$supplier_part->get('Sticky Note') object="Supplier_Part" key="{$supplier_part->id}" field="Supplier_Part_Sticky_Note"  }
</div>
<div class="name_and_categories">
    <span class="strong"><span class="Supplier_Part_Description">{$supplier_part->get('Supplier Part Description')}</span>  </span>
    <ul class="tags Categories" style="float:right">
        {foreach from=$part->get_category_data() item=item key=key}
            <li><span class="button" onclick="change_view('category/{$item.category_key}')"
                      title="{$item.label}">{$item.code}</span></li>
        {/foreach}
    </ul>
    <div style="clear:both">
    </div>
</div>


<div class="asset_container">
    <div class="block picture">
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
    <div class="block carton_sko_units " style="width: 540px">


        <table border="0" class="overview {if $part->get('Part Barcode Number')==''}hide{/if} ">
            <tr class=" units_data">

                <td>
                    <i class="fal fa-fw fa-stop-circle" title="{t}Unit{/t}" ></i>
                </td>
                <td>

                </td>

                <td>
                    <a  target="_blank"title="{t}Commercial unit label{/t}" href="/asset_label.php?object=part&key={$part->id}&type=unit"><i class="fal fa-barcode-alt fa-fw padding_right_5" ></i></a> <span class="Part_Barcode_Number" data-label_no_set="{t}Not set{/t}" >{if $part->get('Part Barcode Number')==''}<span class="discreet italic">{t}Not set{/t}</span>{else}{$part->get('Part Barcode Number')}{/if}</span>
                    {if $part->get('Part Barcode Key')}
                        <i class="discreet_on_hover button fal fa-external-link-square" onClick="change_view('inventory/barcode/{$part->get('Part Barcode Key')}')"></i>
                    {/if}
                </td>

                <td style="">
                    <span class="Unit_Dimension">{$supplier_part->get('Unit Dimensions')}</span>
                </td>

                <td style="text-align: right">
                    <span class="Unit_Weight">{$part->get('Unit Weight')}</span>
                </td>

            </tr>
            <tr class="sko_data">

                <td>
                    <i class="fal fa-box fa-fw" title="{t}SKO{/t}" ></i>
                </td>
                <td style="padding-left: 4px">
                    <span class="discreet" title="{t}Units per SKO{/t}"><i class="fal fa-fwx fa-stop-circle very_discreet" style="font-size: 80%;margin-right: 1px" ></i><i class="fal fa-fws very_discreet fa-times" style="position: relative;top:1px;margin-right: 3px"></i>{$part->get('Units Per Package')}</span>
                </td>

                <td>
                    <a target="_blank" title="{t}Stock keeping unit (Outer) label{/t}" href="/asset_label.php?object=part&key={$part->id}&type=package"><i class="fas fa-barcode-alt fa-fw padding_right_5" ></i></a> <span class="Part_SKO_Barcode" data-label_no_set="{t}Not set{/t}" >{if $part->get('Part SKO Barcode')==''}<span class="discreet italic">{t}Not set{/t}</span>{else}{$part->get('Part SKO Barcode')}{/if}</span>

                </td>

                <td style="">
                    <span class="SKO_Dimensions">{$supplier_part->get('SKO Dimensions')}</span>
                </td>
                <td style="text-align: right">
                    <span class="Package_Weight">{$part->get('Package Weight')}</span>
                </td>



            </tr>
            <tr class="carton_data">

                <td>
                    <i class="fal fa-pallet fa-fw" title="{t}Carton{/t}" ></i>
                </td>
                {if $supplier_part->get('Supplier Part Units Per Carton')!=$part->get('Units Per Carton')  and $part->get('Units Per Carton')!='' }
                    {assign "error_units_per_carton" 1}
                {else}
                    yyyy
                    {assign "error_units_per_carton" 0}

                {/if}

                {$supplier_part->get('Supplier Part Units Per Carton')} {$part->get('Units Per Carton')}

                <td style="padding-left: 4px;padding-right: 4px" class="">
                    <span class="discreet {if $error_units_per_carton}error{/if}" title="{t}Units per carton{/t}">
                        <i class="fal  fa-stop-circle " style="font-size: 80%;margin-right: 1px" ></i><i class="fal  fa-times" style="position: relative;top:1px;margin-right: 3px"></i><span class="Supplier_Part_Units_Per_Carton">{$supplier_part->get('Supplier Part Units Per Carton')}</span>
                        {if $error_units_per_carton} <i class="fal fa-not-equal"></i> {$part->get('Units Per Carton')} <i class="fas fa-exclamation-circle"></i> {/if}
                    </span>

                </td>
                <td>
                    <a target="_blank"  title="{t}Carton label{/t}" href="/asset_label.php?object=supplier_part&key={$supplier_part->id}&type=carton"><i class="far fa-barcode-alt fa-fw padding_right_5" ></i></a> <span class="Part_Carton_Barcode" data-label_no_set="{t}Not set{/t}" >{if $part->get('Part Carton Barcode')==''}<span class="discreet error italic">{t}Not set{/t}</span>{else}{$part->get('Part Carton Barcode')}{/if}</span>


                </td>
                <td style="">
                    <span class="Carton_CBM">{$supplier_part->get('Carton CBM')}</span>
                </td>
                <td style="text-align: right">
                    <span class="Carton_Weight">{$part->get('Carton Weight')}</span>
                </td>
            </tr>

        </table>

        <table border="0" class="overview" style="">


            <tr class="">
                <td>{t}Current landed cost{/t}</td>
                <td class="aright">{$supplier_part->get('Unit Delivered Cost')}</td>
            </tr>
        </table>

        <table border="0" class="hide">
            <tr class="carton">
                <td class=" canvas">
                    <a target="_blank" href="/asset_label.php?object=supplier_part&key={$supplier_part->id}&type=carton">
                    <canvas width="80" height="80">
                    </canvas>
                    </a>
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
                    <a target="_blank" title="{t}Stock keeping unit (Outer){/t}" href="/asset_label.php?object=part&key={$supplier_part->part->id}&type=package">
                    <canvas width="80" height="80">
                    </canvas>
                    </a>
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
                    <a target="_blank" class="padding_left_10" title="{t}Commercial unit label{/t}" href="/asset_label.php?object=part&key={$part->id}&type=unit">
                    <canvas width="80" height="80">
                    </canvas>
                    </a>
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
            <div class="hide" style="margin-bottom: 5px">

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

            {assign "next_deliveries" $supplier_part->get_next_deliveries_data()}


            <div  class="{if $next_deliveries|@count==0}hide{/if} " style="padding-top:10px;clear:both">
                <table border="0" class="overview with_title">
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
    <div style="clear:both">
    </div>
</div>
