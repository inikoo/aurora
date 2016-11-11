<div class="name_and_categories">
    <span class="strong"><span class="Category_Label">{$account->get('Label')}</span> </span>

    <div style="clear:both"></div>
</div>
<div class="asset_container">

    <div class="block sales_data">
        <table>
            <tr class="header">
                <td colspan="3">{$header_total_sales}</td>
            </tr>
            <tr class="total_sales">
                <td>{$account->get('Total Acc Invoiced Amount Soft Minify')}</td>
                <td><i class="fa fa-file-text-o" aria-hidden="true" title="{t}Invoices{/t}"></i> {$account->get('Total Acc Invoices Soft Minify')}</td>
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
                    <span title="{$account->get('Year To Day Acc Invoiced Amount')}">{$account->get('Year To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('1 Year Ago Invoiced Amount')}">{$account->get('1 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('2 Year Ago Invoiced Amount')}">{$account->get('2 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('3 Year Ago Invoiced Amount')}">{$account->get('3 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('4 Year Ago Invoiced Amount')}">{$account->get('4 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$account->get('Year To Day Acc Invoices')}">{$account->get('Year To Day Acc Invoices Minify')}</span>
                    <span title="{$year_data.0.invoices_delta_title}">{$year_data.0.invoices_delta}</span></td>
                <td>
                    <span title="{$account->get('1 Year Ago Invoices')}">{$account->get('1 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.1.invoices_delta_title}">{$year_data.1.invoices_delta}</span></td>
                <td>
                    <span title="{$account->get('2 Year Ago Invoices')}">{$account->get('2 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.2.invoices_delta_title}">{$year_data.2.invoices_delta}</span></td>
                <td>
                    <span title="{$account->get('3 Year Ago Invoices')}">{$account->get('3 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.3.invoices_delta_title}">{$year_data.3.invoices_delta}</span></td>
                <td>
                    <span title="{$account->get('4 Year Ago Invoices')}">{$account->get('4 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.4.invoices_delta_title}">{$year_data.4.invoices_delta}</span></td>
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
                    <span title="{$account->get('Quarter To Day Acc Invoiced Amount')}">{$account->get('Quarter To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('1 Quarter Ago Invoiced Amount')}">{$account->get('1 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('2 Quarter Ago Invoiced Amount')}">{$account->get('2 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('3 Quarter Ago Invoiced Amount')}">{$account->get('3 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('4 Quarter Ago Invoiced Amount')}">{$account->get('4 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$account->get('Quarter To Day Acc Invoices')}">{$account->get('Quarter To Day Acc Invoices Minify')}</span>
                    <span title="{$quarter_data.0.invoices_delta_title}">{$quarter_data.0.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('1 Quarter Ago Invoices')}">{$account->get('1 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.1.invoices_delta_title}">{$quarter_data.1.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('2 Quarter Ago Invoices')}">{$account->get('2 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.2.invoices_delta_title}">{$quarter_data.2.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('3 Quarter Ago Invoices')}">{$account->get('3 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.3.invoices_delta_title}">{$quarter_data.3.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$account->get('4 Quarter Ago Invoices')}">{$account->get('4 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.4.invoices_delta_title}">{$quarter_data.4.invoices_delta}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="clear:both"></div>
</div>

