<div class="name_and_categories">
    <span class="strong"><span class="Category_Label">{$store->get('Label')}</span> </span>

    <div style="clear:both"></div>
</div>
<div class="asset_container">

    <div class="block sales_data">
        <table>
            <tr class="header">
                <td colspan="3">{$header_total_sales}</td>
            </tr>
            <tr class="total_sales">
                <td>{$store->get('Total Acc Invoiced Amount Soft Minify')}</td>
                <td><i class="fa fa-file-text-o" aria-hidden="true" title="{t}Invoices{/t}"></i> {$store->get('Total Acc Invoices Soft Minify')}</td>
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
                    <span title="{$store->get('Year To Day Acc Invoiced Amount')}">{$store->get('Year To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('1 Year Ago Invoiced Amount')}">{$store->get('1 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('2 Year Ago Invoiced Amount')}">{$store->get('2 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('3 Year Ago Invoiced Amount')}">{$store->get('3 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('4 Year Ago Invoiced Amount')}">{$store->get('4 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$store->get('Year To Day Acc Invoices')}">{$store->get('Year To Day Acc Invoices Minify')}</span>
                    <span title="{$year_data.0.invoices_delta_title}">{$year_data.0.invoices_delta}</span></td>
                <td>
                    <span title="{$store->get('1 Year Ago Invoices')}">{$store->get('1 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.1.invoices_delta_title}">{$year_data.1.invoices_delta}</span></td>
                <td>
                    <span title="{$store->get('2 Year Ago Invoices')}">{$store->get('2 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.2.invoices_delta_title}">{$year_data.2.invoices_delta}</span></td>
                <td>
                    <span title="{$store->get('3 Year Ago Invoices')}">{$store->get('3 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.3.invoices_delta_title}">{$year_data.3.invoices_delta}</span></td>
                <td>
                    <span title="{$store->get('4 Year Ago Invoices')}">{$store->get('4 Year Ago Invoices Minify')}</span>
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
                    <span title="{$store->get('Quarter To Day Acc Invoiced Amount')}">{$store->get('Quarter To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('1 Quarter Ago Invoiced Amount')}">{$store->get('1 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('2 Quarter Ago Invoiced Amount')}">{$store->get('2 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('3 Quarter Ago Invoiced Amount')}">{$store->get('3 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('4 Quarter Ago Invoiced Amount')}">{$store->get('4 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$store->get('Quarter To Day Acc Invoices')}">{$store->get('Quarter To Day Acc Invoices Minify')}</span>
                    <span title="{$quarter_data.0.invoices_delta_title}">{$quarter_data.0.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('1 Quarter Ago Invoices')}">{$store->get('1 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.1.invoices_delta_title}">{$quarter_data.1.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('2 Quarter Ago Invoices')}">{$store->get('2 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.2.invoices_delta_title}">{$quarter_data.2.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('3 Quarter Ago Invoices')}">{$store->get('3 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.3.invoices_delta_title}">{$quarter_data.3.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$store->get('4 Quarter Ago Invoices')}">{$store->get('4 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.4.invoices_delta_title}">{$quarter_data.4.invoices_delta}</span>
                </td>
            </tr>
        </table>
    </div>
    <div class="block info">
        <div id="overviews">
            <table id="stock_table" border="0" class="overview">
                <tbody class="info">
                <tr class="main ">
                    <td><i class="fa fa-cube" aria-hidden="true" title="{t}Products{/t}"></i> <span class=" highlight Store_State">{$store->get('State')} </span></td>
                    <td class="aright "><span class=" {if $store->get('Part Category Status')!='NotInUse'}hide{/if}">{$store->get('Valid To')}</span>
                    </td>
                </tr>

                <tr>
                    <td colspan=2>
                        <table style="width:100%;;margin-bottom:10px">
                            <tr style="border-top:1px solid #ccc;border-bottom:1px solid #ccc">
                                <td style="border-left:1px solid #ccc;width:25%" class="New_Products align_center discreet" title="{t}New products{/t}"><i class="fa fa-child" aria-hidden="true" title="{t}New products (less than 2 weeks){/t}"></i> {$store->get('New Products')}</td>
                                <td style="border-left:1px solid #ccc;width:25%" class="Active_Products align_center" title="{t}Active products{/t}">{$store->get('Active Products')}</td>
                                <td style="border-left:1px solid #ccc;width:25%" class="Discontinuing_Products align_center discreet " title="{t}Suspended products{/t}">{$store->get('Suspended Products')}</td>
                                <td style="border-left:1px solid #ccc;width:25%;border-right:1px solid #ccc;" class="Discontinuing_Products align_center very_discreet" title="{t}Discontinued products{/t}">{$store->get('Discontinued Products')}</td>
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

