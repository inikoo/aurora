{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2018 at 00:18:01 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}
<div class="sticky_notes">
{include file="sticky_note.tpl" value=$product->get('Sticky Note') object="Product" key="{$product->id}" field="Product_Sticky_Note"  }
</div>

<div class="name_and_categories">
    <span class="strong"> <span class="Product_Units_Per_Case">{$product->get('Units Per Case')}</span>x <span
                class="Product_Name">{$product->get('Name')}</span></span>
    <ul class="tags Categories" style="float:right">

        {if $family_data.id}
        <li><span class="button" onclick="change_view('category/{$family_data.id}')" title="{$family_data.label}">{$family_data.code}</span></li>
        {/if}
        {if $department_data.id}
        <li><span class="button department" onclick="change_view('category/{$department_data.id}')"  title="{$department_data.label}">{$department_data.code}</span></li>
        {/if}




    </ul>
    <div style="clear:both">
    </div>
</div>


<div class="asset_container">
    {assign "image_key" $product->get_main_image_key()}
    <div style="width: 240px;" class="block picture {if $image_key==''}hide{/if} ">


        <div style="height: 240px;"  class="data_container">

            <div style="width: 240px;height: 240px;" id="main_image" class="wraptocenter main_image ">
                <img style="max-width: 240px;max-height: 240px;" src="/{if $image_key}image.php?id={$image_key}&amp;s=240x240{else}art/nopic.png{/if}"> </span>
            </div>

        </div>



    </div>




    <div class="block info">
        <div id="overviews">

            <table class="overview">
                <tr>
                    <td class=" Product_Status" title="{t}Status{/t}">{$product->get('Status')}</td>
                    <td class="aright Product_Web_State" title="{t}Web state{/t}">{$product->get('Web State')}</td>
                </tr>


            </table>

            <table
                   class="overview {if $product->get('Product Status')=='Discontinued'}super_discreet{/if} {if $product->get('Product Status')=='Discontinued' and $product->get('Product Availability')==0}hide{/if}">

                <tr id="stock_available" class="{if $product->get('Product Number of Parts')==0}hide{/if}">
                    <td>{t}Stock available{/t}:</td>
                    <td class="aright Product_Availability">{$product->get('Availability')}</td>
                </tr>

                {assign "next_deliveries" $product->get_next_deliveries_data()}

                {if $next_deliveries|@count >0}
                <table class="overview with_title next_deliveries">
                    <tr class="top">
                        <td colspan="3">{t}Next deliveries{/t}</td>
                    </tr>
                    {foreach from=$next_deliveries item=next_delivery }
                        <tr class="main ">
                            <td>{$next_delivery.link}</td>
                            <td>{$next_delivery.date}</td>
                            <td class="aright highlight">{$next_delivery.qty}</td>
                        </tr>
                    {/foreach}
                </table>
                {/if}


            </table>

            <table class="overview" >
                <tr class="main">
                    <td>{t}Price{/t}</td>
                    <td class="aright  Product_Price">{$product->get('Price')} </td>
                </tr>
                <tr id="rrp" class="{if $product->get('Product RRP')==''}hide{/if}">
                    <td>{t}RRP{/t}</td>
                    <td class="aright  Product_Unit_RRP">{$product->get('Unit RRP')} </td>
                </tr>

            </table>


            <table class="overview">


                <tr id="valid_to" class="{if $product->get('Product Status')!='Discontinued'}hide{/if}">
                    <td>{t}To{/t}:</td>
                    <td class="aright Product_Valid_To">{$product->get('Valid To')}</td>
                </tr>
                <tr id="suspended_date" class="{if $product->get('Product Status')!='Suspended'}hide{/if}">
                    <td>{t}To{/t}:</td>
                    <td class="aright Product_Valid_To">{$product->get('Valid To')}</td>
                </tr>

            </table>




            <table class="overview" >
                <tr class="main">
                    <td>{t}Webpage{/t}</td>
                    <td class="aright ">
                        <span class="Webpage_State_Icon">
                            {$product->webpage->get('State Icon')}
                        </span>
                        <span onclick="change_view('webpage/{$product->webpage->id}')" class="link">{$product->webpage->get('Code')|lower}</span>

                    </td>
                </tr>


            </table>

            <table id="barcode_data" class="overview {if $product->get('Product Barcode Number')==''}hide{/if} ">
                <tr class="main">
                    <td class="label">
                        <i {if $product->get('Product Barcode Key')} class="fa fa-barcode button" onClick="change_view('inventory/barcode/{$product->get('Product Barcode Key')}')"{else}  class="fa fa-barcode"{/if} ></i>
                    </td>
                    <td class="Product_Barcode_Number highlight">{$product->get('Product Barcode Number')} </td>
                    <td class="barcode_labels aright {if !$product->get('Product Barcode Key')}hide{/if}">
                        <a class="padding_left_10" title="{t}Unit label{/t}"
                           href="/asset_label.php?object=product&key={$product->id}&type=unit"><i class="fa fa-tags "></i></a>
                    </td>

                </tr>


            </table>


            {assign deal_components $product->get_deal_components('objects')}
            <table class="overview" >
                {foreach from=$deal_components item=deal_component name=deal_component}
                    <tr class="main">
                        <td>
                            {$deal_component->get('Icon')}
                        </td>
                        <td class="aright ">
                            {$deal_component->get('Description')}

                        </td>
                    </tr>
                {/foreach}
            </table>




        </div>
    </div>
    <div style="clear:both">
    </div>
</div>


<script>

    function category_view() {
        change_view('products/{$product->get('Product Store Key')}/category/' + $('#Product_Family_Key').val())
    }


</script>