{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 July 2018 at 10:08:17 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div class="asset_container" style="margin-top:20px;border:none;xwidth:400px">

    <div class="block sales_data">

        <table>

            <tr class="header">
                <td colspan=3>{$header_total_sales}</td>
            </tr>
            <tr class="total_sales">
                <td>{$customer->get('Invoiced Amount Soft Minify')}</td>
                <td>{$customer->get('Number Invoices Soft Minify')} <span class="very_discreet error">({$customer->get('Number Refunds Soft Minify')})</td>
                <td></td>
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
                    <span title="{$customer->get('Year To Day Acc Invoiced Amount')}">{$customer->get('Year To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('1 Year Ago Invoiced Amount')}">{$customer->get('1 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('2 Year Ago Invoiced Amount')}">{$customer->get('2 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('3 Year Ago Invoiced Amount')}">{$customer->get('3 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('4 Year Ago Invoiced Amount')}">{$customer->get('4 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$customer->get('Year To Day Acc Invoices')}">{$customer->get('Year To Day Acc Invoices Minify')}</span>
                    <span title="{$year_data.0.invoices_delta_title}">{$year_data.0.invoices_delta}</span></td>
                <td>
                    <span title="{$customer->get('1 Year Ago Invoices')}">{$customer->get('1 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.1.invoices_delta_title}">{$year_data.1.invoices_delta}</span></td>
                <td>
                    <span title="{$customer->get('2 Year Ago Invoices')}">{$customer->get('2 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.2.invoices_delta_title}">{$year_data.2.invoices_delta}</span></td>
                <td>
                    <span title="{$customer->get('3 Year Ago Invoices')}">{$customer->get('3 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.3.invoices_delta_title}">{$year_data.3.invoices_delta}</span></td>
                <td>
                    <span title="{$customer->get('4 Year Ago Invoices')}">{$customer->get('4 Year Ago Invoices Minify')}</span>
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
                    <span title="{$customer->get('Quarter To Day Acc Invoiced Amount')}">{$customer->get('Quarter To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('1 Quarter Ago Invoiced Amount')}">{$customer->get('1 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('2 Quarter Ago Invoiced Amount')}">{$customer->get('2 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('3 Quarter Ago Invoiced Amount')}">{$customer->get('3 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('4 Quarter Ago Invoiced Amount')}">{$customer->get('4 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$customer->get('Quarter To Day Acc Invoices')}">{$customer->get('Quarter To Day Acc Invoices Minify')}</span>
                    <span title="{$quarter_data.0.invoices_delta_title}">{$quarter_data.0.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('1 Quarter Ago Invoices')}">{$customer->get('1 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.1.invoices_delta_title}">{$quarter_data.1.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('2 Quarter Ago Invoices')}">{$customer->get('2 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.2.invoices_delta_title}">{$quarter_data.2.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('3 Quarter Ago Invoices')}">{$customer->get('3 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.3.invoices_delta_title}">{$quarter_data.3.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$customer->get('4 Quarter Ago Invoices')}">{$customer->get('4 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.4.invoices_delta_title}">{$quarter_data.4.invoices_delta}</span>
                </td>
            </tr>
        </table>

    </div>

    <div style="clear:both"></div>
</div>