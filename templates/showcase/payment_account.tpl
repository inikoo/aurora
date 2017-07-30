{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 July 2017 at 18:07:29 CEST, Trnava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div class="name_and_categories">
    <span class="strong"><span class="Category_Label">{$payment_account->get('Label')}</span> </span>

    <div style="clear:both"></div>
</div>
<div class="asset_container">

    <div class="block sales_data " >
        <table style="width:500px">
            <tr class="header">
                <td colspan="3">{$header_total_sales}</td>
            </tr>
            <tr class="total_sales">
                <td><i class="fa fa-shopping-cart" aria-hidden="true" title="{t}Orders{/t}"></i>  {$payment_account->get('Total Acc Invoiced Amount Soft Minify')}</td>
                <td><i class="fa fa-file-text-o" aria-hidden="true" title="{t}Invoices{/t}"></i> {$payment_account->get('Total Acc Invoices Soft Minify')}</td>
                <td>{$customers}</td>
            </tr>
        </table>
        <table style="width:500px">
            <tr class="header">
                <td>{$year_data.0.header}</td>
                <td>{$year_data.1.header}</td>
                <td>{$year_data.2.header}</td>
                <td>{$year_data.3.header}</td>
                <td>{$year_data.4.header}</td>
            </tr>
            <tr>
                <td>
                    <span title="{$payment_account->get('Year To Day Acc Invoiced Amount')}">{$payment_account->get('Year To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('1 Year Ago Invoiced Amount')}">{$payment_account->get('1 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('2 Year Ago Invoiced Amount')}">{$payment_account->get('2 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('3 Year Ago Invoiced Amount')}">{$payment_account->get('3 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('4 Year Ago Invoiced Amount')}">{$payment_account->get('4 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$payment_account->get('Year To Day Acc Invoices')}">{$payment_account->get('Year To Day Acc Invoices Minify')}</span>
                    <span title="{$year_data.0.invoices_delta_title}">{$year_data.0.invoices_delta}</span></td>
                <td>
                    <span title="{$payment_account->get('1 Year Ago Invoices')}">{$payment_account->get('1 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.1.invoices_delta_title}">{$year_data.1.invoices_delta}</span></td>
                <td>
                    <span title="{$payment_account->get('2 Year Ago Invoices')}">{$payment_account->get('2 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.2.invoices_delta_title}">{$year_data.2.invoices_delta}</span></td>
                <td>
                    <span title="{$payment_account->get('3 Year Ago Invoices')}">{$payment_account->get('3 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.3.invoices_delta_title}">{$year_data.3.invoices_delta}</span></td>
                <td>
                    <span title="{$payment_account->get('4 Year Ago Invoices')}">{$payment_account->get('4 Year Ago Invoices Minify')}</span>
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
                    <span title="{$payment_account->get('Quarter To Day Acc Invoiced Amount')}">{$payment_account->get('Quarter To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('1 Quarter Ago Invoiced Amount')}">{$payment_account->get('1 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('2 Quarter Ago Invoiced Amount')}">{$payment_account->get('2 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('3 Quarter Ago Invoiced Amount')}">{$payment_account->get('3 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('4 Quarter Ago Invoiced Amount')}">{$payment_account->get('4 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$payment_account->get('Quarter To Day Acc Invoices')}">{$payment_account->get('Quarter To Day Acc Invoices Minify')}</span>
                    <span title="{$quarter_data.0.invoices_delta_title}">{$quarter_data.0.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('1 Quarter Ago Invoices')}">{$payment_account->get('1 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.1.invoices_delta_title}">{$quarter_data.1.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('2 Quarter Ago Invoices')}">{$payment_account->get('2 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.2.invoices_delta_title}">{$quarter_data.2.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('3 Quarter Ago Invoices')}">{$payment_account->get('3 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.3.invoices_delta_title}">{$quarter_data.3.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$payment_account->get('4 Quarter Ago Invoices')}">{$payment_account->get('4 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.4.invoices_delta_title}">{$quarter_data.4.invoices_delta}</span>
                </td>
            </tr>
        </table>
    </div>
    <div class="block info" style="width:500px">
        <div id="overviews">
            <table id="stock_table" border="0" class="overview" >
                <tbody class="info">



                <tr class="main ">
                    <td colspan=2>


                        <table style="width:100%;;margin-bottom:10px;margin-top:10px" class="mesh">
                            <tr >
                                <td style="width:25%" class="Active_Web_For_Sale align_center  " title="{t}Active products online{/t}"><i class="fa fa-shopping-cart padding_right_10" aria-hidden="true" "></i> {$payment_account->get('Active Web For Sale')}</td>
                                <td style="width:25%" class="Active_Web_Out_of_Stock align_center " title="{t}Active products out of stock{/t}"><i class="fa fa-file-text-o" padding_right_10" aria-hidden="true" "></i> {$payment_account->get('Active Web Out of Stock')} {if $payment_account->get('Payment Account Active Web For Sale')>0 and $payment_account->get('Payment Account Active Web Out of Stock')>0 }({$payment_account->get('Percentage Active Web Out of Stock')}){/if}</td>
                                <td style="width:25%" class="Active_Web_Offline align_center " title="{t}Active products offline{/t}"><i class="fa fa-users  padding_right_10" aria-hidden="true" "></i> {$payment_account->get('Active Web Offline')} {if $payment_account->get('Payment Account Active Web For Sale')>0 and $payment_account->get('Payment Account Active Web Offline')>0 }({$payment_account->get('Percentage Active Web Offline')}){/if}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>
    <div style="clear:both"></div>
</div>

