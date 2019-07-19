{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 February 2017 at 18:19:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{assign "kpi" $supplier_production->get_kpi('Month To Day')}
<div class="asset_container" style="border-bottom:1px solid #ccc;padding:20px">

    <div class="block sales_data">

        <table>

            <tr class="header">
                <td colspan=3>{$header_total_sales}</td>
            </tr>
            <tr class="total_sales">
                <td>{$supplier->get('Total Acc Invoiced Amount Soft Minify')}</td>
                <td>{$supplier->get('Total Acc Dispatched Soft Minify')}</td>
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
                    <span title="{$supplier->get('Year To Day Acc Invoiced Amount')}">{$supplier->get('Year To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('1 Year Ago Invoiced Amount')}">{$supplier->get('1 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('2 Year Ago Invoiced Amount')}">{$supplier->get('2 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('3 Year Ago Invoiced Amount')}">{$supplier->get('3 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('4 Year Ago Invoiced Amount')}">{$supplier->get('4 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$supplier->get('Year To Day Acc Dispatched')}">{$supplier->get('Year To Day Acc Dispatched Minify')}</span>
                    <span title="{$year_data.0.dispatched_delta_title}">{$year_data.0.dispatched_delta}</span></td>
                <td>
                    <span title="{$supplier->get('1 Year Ago Dispatched')}">{$supplier->get('1 Year Ago Dispatched Minify')}</span>
                    <span title="{$year_data.1.dispatched_delta_title}">{$year_data.1.dispatched_delta}</span></td>
                <td>
                    <span title="{$supplier->get('2 Year Ago Dispatched')}">{$supplier->get('2 Year Ago Dispatched Minify')}</span>
                    <span title="{$year_data.2.dispatched_delta_title}">{$year_data.2.dispatched_delta}</span></td>
                <td>
                    <span title="{$supplier->get('3 Year Ago Dispatched')}">{$supplier->get('3 Year Ago Dispatched Minify')}</span>
                    <span title="{$year_data.3.dispatched_delta_title}">{$year_data.3.dispatched_delta}</span></td>
                <td>
                    <span title="{$supplier->get('4 Year Ago Dispatched')}">{$supplier->get('4 Year Ago Dispatched Minify')}</span>
                    <span title="{$year_data.4.dispatched_delta_title}">{$year_data.4.dispatched_delta}</span></td>
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
                    <span title="{$supplier->get('Quarter To Day Acc Invoiced Amount')}">{$supplier->get('Quarter To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('1 Quarter Ago Invoiced Amount')}">{$supplier->get('1 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('2 Quarter Ago Invoiced Amount')}">{$supplier->get('2 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('3 Quarter Ago Invoiced Amount')}">{$supplier->get('3 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('4 Quarter Ago Invoiced Amount')}">{$supplier->get('4 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$supplier->get('Quarter To Day Acc Dispatched')}">{$supplier->get('Quarter To Day Acc Dispatched Minify')}</span>
                    <span title="{$quarter_data.0.dispatched_delta_title}">{$quarter_data.0.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('1 Quarter Ago Dispatched')}">{$supplier->get('1 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.1.dispatched_delta_title}">{$quarter_data.1.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('2 Quarter Ago Dispatched')}">{$supplier->get('2 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.2.dispatched_delta_title}">{$quarter_data.2.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('3 Quarter Ago Dispatched')}">{$supplier->get('3 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.3.dispatched_delta_title}">{$quarter_data.3.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$supplier->get('4 Quarter Ago Dispatched')}">{$supplier->get('4 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.4.dispatched_delta_title}">{$quarter_data.4.dispatched_delta}</span>
                </td>
            </tr>
        </table>

    </div>

<div  class="hide" style="border:1px solid #eee;padding:10px;font-size:150%;width:200px" title="{$kpi.ppm.ppm_formatted_amount} /{$kpi.ppm.ppm_formatted_hrs} ">{$kpi.ppm.ppm_formatted_kpi}</div>
    <div style="clear: both"></div>
</div>
