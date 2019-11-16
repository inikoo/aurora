{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 February 2017 at 18:19:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}





<div class="asset_container" style="border-bottom:1px solid #ccc;padding:20px">

    <div class="block sales_data">
        <table>
            <tr class="header">
                <td colspan="3">{$header_total_sales}</td>
            </tr>
            <tr class="total_sales">
                <td title="{t}Sales{/t}">{$account->get('Total Acc Invoiced Amount Soft Minify')}</td>
                <td title="{t}Distinct parts dispatched{/t}">{$distinct_parts}</td>
                <td title="{t}Deliveries{/t}">{$deliveries}</td>
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
            <tr  >
                <td>
                    <span title="{t}Distinct parts dispatched year to day{/t}">{$account->get('Year To Day Acc Distinct Parts Dispatched')}</span>
                    <span title="{$year_data.0.dispatched_delta_title}">{$year_data.0.dispatched_delta}</span></td>
                <td>
                    <span title="{t}Distinct parts dispatched {/t} {$year_data.1.header}">{$account->get('1 Year Ago Distinct Parts Dispatched')}</span>
                    <span title="{$year_data.1.dispatched_delta_title}">{$year_data.1.dispatched_delta}</span></td>
                <td>
                    <span  title="{t}Distinct parts dispatched {/t} {$year_data.2.header}">{$account->get('2 Year Ago Distinct Parts Dispatched')}</span>
                    <span title="{$year_data.2.dispatched_delta_title}">{$year_data.2.dispatched_delta}</span></td>
                <td>
                    <span  title="{t}Distinct parts dispatched {/t} {$year_data.3.header}">{$account->get('3 Year Ago Distinct Parts Dispatched')}</span>
                    <span title="{$year_data.3.dispatched_delta_title}">{$year_data.3.dispatched_delta}</span></td>
                <td>
                    <span title="{t}Distinct parts dispatched {/t} {$year_data.4.header}">{$account->get('4 Year Ago Distinct Parts Dispatched')}</span>
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
                    <span title="{t}Distinct parts dispatched{/t} {$quarter_data.0.header}">{$account->get('Quarter To Day Acc Distinct Parts Dispatched')}</span>
                    <span title="{$quarter_data.0.dispatched_delta_title}">{$quarter_data.0.dispatched_delta}</span>
                </td>
                <td>
                    <span  title="{t}Distinct parts dispatched{/t} {$quarter_data.1.header}"">{$account->get('1 Quarter Ago Distinct Parts Dispatched')}</span>
                    <span title="{$quarter_data.1.dispatched_delta_title}">{$quarter_data.1.dispatched_delta}</span>
                </td>
                <td>
                    <span  title="{t}Distinct parts dispatched{/t} {$quarter_data.2.header}"">{$account->get('2 Quarter Ago Distinct Parts Dispatched')}</span>
                    <span title="{$quarter_data.2.dispatched_delta_title}">{$quarter_data.2.dispatched_delta}</span>
                </td>
                <td>
                    <span title="{t}Distinct parts dispatched{/t} {$quarter_data.3.header}"">{$account->get('3 Quarter Ago Distinct Parts Dispatched')}</span>
                    <span title="{$quarter_data.3.dispatched_delta_title}">{$quarter_data.3.dispatched_delta}</span>
                </td>
                <td>
                    <span  title="{t}Distinct parts dispatched{/t} {$quarter_data.4.header}">{$account->get('4 Quarter Ago Distinct Parts Dispatched')}</span>
                    <span title="{$quarter_data.4.dispatched_delta_title}">{$quarter_data.4.dispatched_delta}</span>
                </td>
            </tr>
        </table>
    </div>


    <div class="block info">
        <div id="overviews">
            <table id="stock_table" class="overview">
                <tbody class="info">
                <tr class="main ">
                    <td class="  " style="text-align: center"> {t}Stock value{/t} {$warehouse->get('Stock Amount')}</td>
                </tr>

                <tr>
                    <td colspan=2>
                        <table style="width:100%;;margin-bottom:10px">
                            <tr style="border-top:1px solid #ccc;border-bottom:1px solid #ccc">
                                <td style="border-left:1px solid #ccc;width:25%"
                                    class=" align_center discreet" title="{t}Parts in process{/t}"><i class="fa fa-seedling padding_right_5" aria-hidden="true"></i> <span class="In_Process_Parts">{$account->get('In Process Parts Number')}</span></td>

                                <td style="border-left:1px solid #ccc;width:25%" class=" align_center"
                                    title="{t}Parts active{/t}"><i class="far fa-box padding_right_5" aria-hidden="true" ></i>  <span class="In_Use_Parts strong">{$account->get('Active Parts Number')}</span></td>

                                <td style="border-left:1px solid #ccc;width:25%"
                                    class=" align_center discreet "
                                    title="{t}Parts discontinuing{/t}"><i class="far fa-box warning discreet padding_right_5" aria-hidden="true" ></i> <span class="Discontinuing_Parts">{$account->get('Discontinuing Parts Number')}</span></td>

                                <td style="border-left:1px solid #ccc;width:25%;border-right:1px solid #ccc;"
                                    class=" align_center very_discreet"
                                    title="{t}Parts discontinued{/t}"><i class="fal fa-box red padding_right_5" aria-hidden="true" ></i> <span class="Not_In_Use_Parts">{$account->get('Discontinued Parts Number')}</span></td>








                            </tr>
                        </table>
                    </td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>

    <div style="clear: both"></div>

</div>