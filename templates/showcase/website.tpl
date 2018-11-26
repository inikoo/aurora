{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 June 2017 at 19:23:54 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div class="name_and_categories">
    <span class="strong"><span class="Category_Label">{$website->get('Name')}</span> </span>

    <div style="clear:both"></div>
</div>
<div class="asset_container">

    <div class="block sales_data " >
        <table style="width:500px">
            <tr class="header">
                <td colspan="3">{$header_total_views}</td>
            </tr>
            <tr class="total_sales">
                <td>{$website->get('Total Acc Invoiced Amount Soft Minify')}</td>
                <td><i class="fal fa-file-alt" aria-hidden="true" title="{t}Invoices{/t}"></i> {$website->get('Total Acc Invoices Soft Minify')}</td>
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
                    <span title="{$website->get('Year To Day Acc Invoiced Amount')}">{$website->get('Year To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('1 Year Ago Invoiced Amount')}">{$website->get('1 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('2 Year Ago Invoiced Amount')}">{$website->get('2 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('3 Year Ago Invoiced Amount')}">{$website->get('3 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('4 Year Ago Invoiced Amount')}">{$website->get('4 Year Ago Invoiced Amount Minify')}</span>
                    <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$website->get('Year To Day Acc Invoices')}">{$website->get('Year To Day Acc Invoices Minify')}</span>
                    <span title="{$year_data.0.invoices_delta_title}">{$year_data.0.invoices_delta}</span></td>
                <td>
                    <span title="{$website->get('1 Year Ago Invoices')}">{$website->get('1 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.1.invoices_delta_title}">{$year_data.1.invoices_delta}</span></td>
                <td>
                    <span title="{$website->get('2 Year Ago Invoices')}">{$website->get('2 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.2.invoices_delta_title}">{$year_data.2.invoices_delta}</span></td>
                <td>
                    <span title="{$website->get('3 Year Ago Invoices')}">{$website->get('3 Year Ago Invoices Minify')}</span>
                    <span title="{$year_data.3.invoices_delta_title}">{$year_data.3.invoices_delta}</span></td>
                <td>
                    <span title="{$website->get('4 Year Ago Invoices')}">{$website->get('4 Year Ago Invoices Minify')}</span>
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
                    <span title="{$website->get('Quarter To Day Acc Invoiced Amount')}">{$website->get('Quarter To Day Acc Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('1 Quarter Ago Invoiced Amount')}">{$website->get('1 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('2 Quarter Ago Invoiced Amount')}">{$website->get('2 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('3 Quarter Ago Invoiced Amount')}">{$website->get('3 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('4 Quarter Ago Invoiced Amount')}">{$website->get('4 Quarter Ago Invoiced Amount Minify')}</span>
                    <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span title="{$website->get('Quarter To Day Acc Invoices')}">{$website->get('Quarter To Day Acc Invoices Minify')}</span>
                    <span title="{$quarter_data.0.invoices_delta_title}">{$quarter_data.0.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('1 Quarter Ago Invoices')}">{$website->get('1 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.1.invoices_delta_title}">{$quarter_data.1.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('2 Quarter Ago Invoices')}">{$website->get('2 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.2.invoices_delta_title}">{$quarter_data.2.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('3 Quarter Ago Invoices')}">{$website->get('3 Quarter Ago Invoices Minify')}</span>
                    <span title="{$quarter_data.3.invoices_delta_title}">{$quarter_data.3.invoices_delta}</span>
                </td>
                <td>
                    <span title="{$website->get('4 Quarter Ago Invoices')}">{$website->get('4 Quarter Ago Invoices Minify')}</span>
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
                        <table class="mesh" style="width:100%;;margin-bottom:10px;margin-top:10px">
                            <tr >
                                <td style="width:25%" class="New_Products align_center discreet" title="{t}New products{/t}"><i class="fa fa-child" aria-hidden="true" title="{t}New products (less than 2 weeks){/t}"></i> {$website->get('New Products')}


                                </td>
                                <td style="width:50%" class="Active_Products align_center" title="{t}Active products{/t}"><i class="fa fa-cube" aria-hidden="true"></i> {$website->get('Active Products')}
                                    <span  class="Discontinuing_Products align_center discreet padding_left_10 " title="{t}Discontinuing products{/t}"> ( <i class="fa fa-cube discreet warning" aria-hidden="true" ></i> {$website->get('Discontinuing Products')} )</span>


                                </td>
                                <td style=";width:25%;" class="Discontinued_Products align_center very_discreet" title="{t}Discontinued products{/t}"><i class="fa fa-cube very_discreet" aria-hidden="true""></i>
                                    {$website->get('Discontinued Products')}
                                    <span class='italic' title="{t}Suspended product{/t}">+ {$website->get('Suspended Products')} </span>

                                </td>
                            </tr>
                        </table>
                        <table style="width:100%;;margin-bottom:10px" class="mesh">
                            <tr >
                                <td style="width:25%" class="Active_Web_For_Sale align_center discreet " title="{t}Active products online{/t}"><i class="fa fa-microphone padding_right_10" aria-hidden="true" "></i> {$website->get('Active Web For Sale')}</td>
                                <td style="width:25%" class="Active_Web_Out_of_Stock align_center " title="{t}Active products out of stock{/t}"><i class="fa fa-ban error padding_right_10" aria-hidden="true" "></i> {$website->get('Active Web Out of Stock')} {if $website->get('Store Active Web For Sale')>0 and $website->get('Store Active Web Out of Stock')>0 }({$website->get('Percentage Active Web Out of Stock')}){/if}</td>
                                <td style="width:25%" class="Active_Web_Offline align_center discreet" title="{t}Active products offline{/t}"><i class="fa fa-microphone-slash discreet padding_right_10" aria-hidden="true" "></i> {$website->get('Active Web Offline')} {if $website->get('Store Active Web For Sale')>0 and $website->get('Store Active Web Offline')>0 }({$website->get('Percentage Active Web Offline')}){/if}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                </tbody>

            </table>

            <table id="stock_table" border="0" class="overview" >
                <tbody class="info">



                <tr class="main ">
                    <td colspan=2>
                        <i class="far fa-sitemap margin_right_10" title="{t}Site map{/t}"></i> <em>https://{$website->get('Website URL')}/sitemap_index.xml.php</em>  <a target="_blank" href="https://{$website->get('Website URL')}/sitemap_index.xml.php"> <i class="fal margin_left_10 fa-external-link"></i> </a>
                    </td>
                </tr>
                </tbody>

            </table>


        </div>
    </div>
    <div style="clear:both"></div>
</div>

