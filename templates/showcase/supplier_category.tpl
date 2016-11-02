<div class="name_and_categories">

    <span class="strong">{$category->get('Label')}</span>
    <ul class="tags Categories" style="float:right">
        {foreach from=$category->get_category_data() item=item key=key}
            <li><span class="button" onclick="change_view('category/{$item.category_key}')"
                      title="{$item.label}">{$item.code}</span></li>
        {/foreach}
    </ul>
    <div style="clear:both">
    </div>
</div>

<div class="asset_container">


    <div class="block sales_data">

        <table>

            <tr class="header">
                <td colspan=3>{$header_total_sales}</td>
            </tr>
            <tr class="total_sales">
                <td>{$category->get('Total Acc Invoiced Amount Soft Minify')}</td>
                <td>{$category->get('Total Acc Dispatched Soft Minify')}</td>
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
                    <span title="{$category->get('Year To Day Acc Invoiced Amount')}">{$category->get('Year To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('1 Year Ago Invoiced Amount')}">{$category->get('1 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('2 Year Ago Invoiced Amount')}">{$category->get('2 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('3 Year Ago Invoiced Amount')}">{$category->get('3 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('4 Year Ago Invoiced Amount')}">{$category->get('4 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$category->get('Year To Day Acc Quantity Invoiced')}">{$category->get('Year To Day Acc Quantity Invoiced Minify')}</span>
                    <span title="{$year_data.0.quantity_invoiced_delta_title}">{$year_data.0.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('1 Year Ago Quantity Invoiced')}">{$category->get('1 Year Ago Quantity Invoiced Minify')}</span>
                    <span title="{$year_data.1.quantity_invoiced_delta_title}">{$year_data.1.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('2 Year Ago Quantity Invoiced')}">{$category->get('2 Year Ago Quantity Invoiced Minify')}</span>
                    <span title="{$year_data.2.quantity_invoiced_delta_title}">{$year_data.2.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('3 Year Ago Quantity Invoiced')}">{$category->get('3 Year Ago Quantity Invoiced Minify')}</span>
                    <span title="{$year_data.3.quantity_invoiced_delta_title}">{$year_data.3.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('4 Year Ago Quantity Invoiced')}">{$category->get('4 Year Ago Quantity Invoiced Minify')}</span>
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
                    <span title="{$category->get('Quarter To Day Acc Invoiced Amount')}">{$category->get('Quarter To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('1 Quarter Ago Invoiced Amount')}">{$category->get('1 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('2 Quarter Ago Invoiced Amount')}">{$category->get('2 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('3 Quarter Ago Invoiced Amount')}">{$category->get('3 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('4 Quarter Ago Invoiced Amount')}">{$category->get('4 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$category->get('Quarter To Day Acc Quantity Invoiced')}">{$category->get('Quarter To Day Acc Quantity Invoiced Minify')}</span>
                    <span title="{$quarter_data.0.quantity_invoiced_delta_title}">{$quarter_data.0.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('1 Quarter Ago Quantity Invoiced')}">{$category->get('1 Quarter Ago Quantity Invoiced Minify')}</span>
                    <span title="{$quarter_data.1.quantity_invoiced_delta_title}">{$quarter_data.1.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('2 Quarter Ago Quantity Invoiced')}">{$category->get('2 Quarter Ago Quantity Invoiced Minify')}</span>
                    <span title="{$quarter_data.2.quantity_invoiced_delta_title}">{$quarter_data.2.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('3 Quarter Ago Quantity Invoiced')}">{$category->get('3 Quarter Ago Quantity Invoiced Minify')}</span>
                    <span title="{$quarter_data.3.quantity_invoiced_delta_title}">{$quarter_data.3.quantity_invoiced_delta}</span>
                </td>
                <td>
                    <span title="{$category->get('4 Quarter Ago Quantity Invoiced')}">{$category->get('4 Quarter Ago Quantity Invoiced Minify')}</span>
                    <span title="{$quarter_data.4.quantity_invoiced_delta_title}">{$quarter_data.4.quantity_invoiced_delta}</span>
                </td>
            </tr>
        </table>


    </div>
    <div style="clear:both">
    </div>
</div>


<script>

    function show_images_tab() {
        change_tab('category.images')
    }


</script>