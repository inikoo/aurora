<div class="name_and_categories">
    <span class="strong"> <span class="Product_Units_Per_Case">{$product->get('Units Per Case')}</span>x <span
                class="Product_Name">{$product->get('Name')}</span></span>
    <ul class="tags Categories" style="float:right">
        {foreach from=$product->get_category_data() item=item key=key}
            <li><span class="button" onclick="change_view('category/{$item.category_key}')"
                      title="{$item.label}">{$item.code}</span></li>
        {/foreach}
    </ul>
    <div style="clear:both">
    </div>
</div>


<div class="asset_container">

    <div class="block picture">

        <div style="clear:both">
        </div>
        <div class="data_container">
            {assign "image_key" $product->get_main_image_key()}
            <div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}">
                <img src="/{if $image_key}image_root.php?id={$image_key}&amp;size=small{else}art/nopic.png{/if}"> </span>
            </div>
            {include file='upload_main_image.tpl' object='Product' key=$product->id class="{if $image_key!=''}hide{/if}"}
        </div>
        <div style="clear:both">
        </div>
    </div>

    <div class="block sales_data">

        <table>

            <tr class="header">
                <td colspan=3>{$header_total_sales}</td>
            </tr>
            <tr class="total_sales">
                <td>{$product->get('Total Acc Invoiced Amount')}</td>
                <td>{$product->get('Total Acc Quantity Invoiced')}</td>
                <td>{$customers}</td>
            </tr>
        </table>

        <table>
            <tr class="header">
                <td>{$year_data.0.header}</td>
                <td>{$year_data.1.header}</td>
                <td>{$year_data.2.header}</td>
                <td>{$year_data.3.header}</td>
                <td>{$year_data.4.header}</td>
            </tr>
            <tr>
                <td>
                    <span title="{$product->get('Year To Day Acc Invoiced Amount')}">{$product->get('Year To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('1 Year Ago Invoiced Amount')}">{$product->get('1 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('2 Year Ago Invoiced Amount')}">{$product->get('2 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('3 Year Ago Invoiced Amount')}">{$product->get('3 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('4 Year Ago Invoiced Amount')}">{$product->get('4 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$product->get('Year To Day Acc Quantity Invoiced')}">{$product->get('Year To Day Acc Quantity Invoiced Minify')}</span>
                    <span title="{$year_data.0.quantity_invoiced_delta_title}">{$year_data.0.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('1 Year Ago Quantity Invoiced')}">{$product->get('1 Year Ago Quantity Invoiced Minify')}</span>
                    <span title="{$year_data.1.quantity_invoiced_delta_title}">{$year_data.1.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('2 Year Ago Quantity Invoiced')}">{$product->get('2 Year Ago Quantity Invoiced Minify')}</span>
                    <span title="{$year_data.2.quantity_invoiced_delta_title}">{$year_data.2.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('3 Year Ago Quantity Invoiced')}">{$product->get('3 Year Ago Quantity Invoiced Minify')}</span>
                    <span title="{$year_data.3.quantity_invoiced_delta_title}">{$year_data.3.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('4 Year Ago Quantity Invoiced')}">{$product->get('4 Year Ago Quantity Invoiced Minify')}</span>
                    <span title="{$year_data.4.quantity_invoiced_delta_title}">{$year_data.4.quantity_invoiced_delta}</span>
                </td>
            </tr>
            <tr class="space">
                <td colspan="5"></td>
            </tr>
            <tr class="header">
                <td>{$quarter_data.0.header}</td>
                <td>{$quarter_data.1.header}</td>
                <td>{$quarter_data.2.header}</td>
                <td>{$quarter_data.3.header}</td>
                <td>{$quarter_data.4.header}</td>
            </tr>
            <tr>
                <td>
                    <span title="{$product->get('Quarter To Day Acc Invoiced Amount')}">{$product->get('Quarter To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('1 Quarter Ago Invoiced Amount')}">{$product->get('1 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('2 Quarter Ago Invoiced Amount')}">{$product->get('2 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('3 Quarter Ago Invoiced Amount')}">{$product->get('3 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('4 Quarter Ago Invoiced Amount')}">{$product->get('4 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$product->get('Quarter To Day Acc Quantity Invoiced')}">{$product->get('Quarter To Day Acc Quantity Invoiced Minify')}</span>
                    <span title="{$quarter_data.0.quantity_invoiced_delta_title}">{$quarter_data.0.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('1 Quarter Ago Quantity Invoiced')}">{$product->get('1 Quarter Ago Quantity Invoiced Minify')}</span>
                    <span title="{$quarter_data.1.quantity_invoiced_delta_title}">{$quarter_data.1.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('2 Quarter Ago Quantity Invoiced')}">{$product->get('2 Quarter Ago Quantity Invoiced Minify')}</span>
                    <span title="{$quarter_data.2.quantity_invoiced_delta_title}">{$quarter_data.2.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('3 Quarter Ago Quantity Invoiced')}">{$product->get('3 Quarter Ago Quantity Invoiced Minify')}</span>
                    <span title="{$quarter_data.3.quantity_invoiced_delta_title}">{$quarter_data.3.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$product->get('4 Quarter Ago Quantity Invoiced')}">{$product->get('4 Quarter Ago Quantity Invoiced Minify')}</span>
                    <span title="{$quarter_data.4.quantity_invoiced_delta_title}">{$quarter_data.4.quantity_invoiced_delta}</span>
                </td>
            </tr>
        </table>


    </div>


    <div class="block info">
        <div id="overviews">

            <table border="0" class="overview">
                <tr>
                    <td class=" Product_Status" title="{t}Status{/t}">{$product->get('Status')}</td>
                    <td class="aright Product_Web_State" title="{t}Web state{/t}">{$product->get('Web State')}</td>
                </tr>


            </table>

            <table border="0"
                   class="overview {if $product->get('Product Status')=='Discontinued'}super_discreet{/if} {if $product->get('Product Status')=='Discontinued' and $product->get('Product Availability')==0}hide{/if}">

                <tr id="stock_available" class="{if $product->get('Product Number of Parts')==0}hide{/if}">
                    <td>{t}Stock available{/t}:</td>
                    <td class="aright Product_Availability">{$product->get('Availability')}</td>
                </tr>

            </table>

            <table border="0" class="overview" style="">
                <tr class="main">
                    <td>{t}Price{/t}</td>
                    <td class="aright  Product_Price">{$product->get('Price')} </td>
                </tr>
                <tr id="rrp" class="{if $product->get('Product RRP')==''}hide{/if}">
                    <td>{t}RRP{/t}</td>
                    <td class="aright  Product_Unit_RRP">{$product->get('Unit RRP')} </td>
                </tr>

            </table>


            <table border="0" class="overview">


                <tr id="valid_to" class="{if $product->get('Product Status')!='Discontinued'}hide{/if}">
                    <td>{t}To{/t}:</td>
                    <td class="aright Product_Valid_To">{$product->get('Valid To')}</td>
                </tr>
                <tr id="suspended_date" class="{if $product->get('Product Status')!='Suspended'}hide{/if}">
                    <td>{t}To{/t}:</td>
                    <td class="aright Product_Valid_To">{$product->get('Valid To')}</td>
                </tr>

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