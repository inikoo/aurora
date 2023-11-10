<div class="asset_container" style="margin-top:20px;border:none;xwidth:400px">

    <div class="block sales_data">

        <table>

            <tr class="header">
                <td colspan=3>{$header_total_sales}</td>
            </tr>
            <tr class="total_sales">
                <td>{$agent->get('Total Acc Invoiced Amount Soft Minify')}</td>
                <td>{$agent->get('Total Acc Dispatched Soft Minify')}</td>
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
                    <span title="{$agent->get('Year To Day Acc Invoiced Amount')}">{$agent->get('Year To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('1 Year Ago Invoiced Amount')}">{$agent->get('1 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('2 Year Ago Invoiced Amount')}">{$agent->get('2 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('3 Year Ago Invoiced Amount')}">{$agent->get('3 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('4 Year Ago Invoiced Amount')}">{$agent->get('4 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$agent->get('Year To Day Acc Dispatched')}">{$agent->get('Year To Day Acc Dispatched Minify')}</span>
                    <span title="{$year_data.0.dispatched_delta_title}">{$year_data.0.dispatched_delta}</span></td>
                <td>
                    <span title="{$agent->get('1 Year Ago Dispatched')}">{$agent->get('1 Year Ago Dispatched Minify')}</span>
                    <span title="{$year_data.1.dispatched_delta_title}">{$year_data.1.dispatched_delta}</span></td>
                <td>
                    <span title="{$agent->get('2 Year Ago Dispatched')}">{$agent->get('2 Year Ago Dispatched Minify')}</span>
                    <span title="{$year_data.2.dispatched_delta_title}">{$year_data.2.dispatched_delta}</span></td>
                <td>
                    <span title="{$agent->get('3 Year Ago Dispatched')}">{$agent->get('3 Year Ago Dispatched Minify')}</span>
                    <span title="{$year_data.3.dispatched_delta_title}">{$year_data.3.dispatched_delta}</span></td>
                <td>
                    <span title="{$agent->get('4 Year Ago Dispatched')}">{$agent->get('4 Year Ago Dispatched Minify')}</span>
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
                    <span title="{$agent->get('Quarter To Day Acc Invoiced Amount')}">{$agent->get('Quarter To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('1 Quarter Ago Invoiced Amount')}">{$agent->get('1 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('2 Quarter Ago Invoiced Amount')}">{$agent->get('2 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('3 Quarter Ago Invoiced Amount')}">{$agent->get('3 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('4 Quarter Ago Invoiced Amount')}">{$agent->get('4 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$agent->get('Quarter To Day Acc Dispatched')}">{$agent->get('Quarter To Day Acc Dispatched Minify')}</span>
                    <span title="{$quarter_data.0.dispatched_delta_title}">{$quarter_data.0.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('1 Quarter Ago Dispatched')}">{$agent->get('1 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.1.dispatched_delta_title}">{$quarter_data.1.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('2 Quarter Ago Dispatched')}">{$agent->get('2 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.2.dispatched_delta_title}">{$quarter_data.2.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('3 Quarter Ago Dispatched')}">{$agent->get('3 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.3.dispatched_delta_title}">{$quarter_data.3.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{$agent->get('4 Quarter Ago Dispatched')}">{$agent->get('4 Quarter Ago Dispatched Minify')}</span>
                    <span title="{$quarter_data.4.dispatched_delta_title}">{$quarter_data.4.dispatched_delta}</span>
                </td>
            </tr>
        </table>

    </div>

    <div style="clear:both"></div>
</div>