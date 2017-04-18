{include file="sticky_note.tpl" value=$category->get('Sticky Note') object="Category" key="{$category->id}" field="Category_Sticky_Note"  }

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

    <div class="block picture">

        <div class="data_container">


            {assign "image_key" $category->get_main_image_key()}
            <div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}">
                <img src="/{if $image_key}image_root.php?id={$image_key}&size=small{else}art/nopic.png{/if}">
                </span>
            </div>
            {include file='upload_main_image.tpl' object='Category'  key=$category->id class="{if $image_key!=''}hide{/if}"}
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
                <td>{$category->get('Total Acc Invoiced Amount Soft Minify')}</td>
                <td>{$category->get('Total Acc Quantity Invoiced Soft Minify')}</td>
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

    <div class="block info">
        <div id="overviews">
            <table id="stock_table" border="0" class="overview">
                <tbody class="info">


                <tr>
                    <td colspan=2>
                        <table style="width:100%;;margin-top:10px;margin-bottom:10px" class="mesh">
                            <tr >
                                <td style="width:20%" class="New_Products align_center discreet" title="{t}New products{/t}"><i class="fa fa-child" aria-hidden="true" title="{t}New products (less than 2 weeks){/t}"></i> {$category->get('New Products')}</td>
                                <td style="width:25%" class="Active_Products align_center" title="{t}Active products{/t}"><i class="fa fa-cube" aria-hidden="true"></i> {$category->get('Active Products')}</td>
                                <td style="width:25%" class="Discontinuing_Products align_center discreet " title="{t}Discontinuing products{/t}"><i class="fa fa-cube discreet warning" aria-hidden="true" ></i> {$category->get('Discontinuing Products')}</td>
                                <td style="width:30%;" class="Discontinued_Products align_center very_discreet" title="{t}Discontinued products{/t}"><i class="fa fa-cube very_discreet" aria-hidden="true" ></i> {$category->get('Discontinued Products')}  <span class="Suspended_Products_container {if $category->get('Suspended Products')==0}hide{/if}" title="{t}Suspended products{/t}">(<span class="Suspended_Products">{$category->get('Suspended Products')}</span>)</span>  </td>
                            </tr>
                        </table>
                        <table style="width:100%;;margin-bottom:10px" class="mesh">
                            <tr >
                                <td style="width:25%;border-right:1px  dashed #CCCCCC;" class="Active_Web_For_Sale_including_Out_of_Stock align_center discreet " title="{t}Products online{/t}"><i class="fa fa-microphone " aria-hidden="true" "></i> {$category->get('Active Web For Sale including Out of Stock')}</td>
                                <td style="width:25%;border-left:none" class="Active_Web_Out_of_Stock align_center " title="{t}Products out of stock{/t}"><i class="fa fa-ban error " aria-hidden="true" "></i> {$category->get('Active Web Out of Stock')} {if $category->get('Product Category Active Web For Sale')>0 and $category->get('Product Category Active Web Out of Stock')>0 }({$category->get('Percentage Active Web Out of Stock')}){/if}</td>
                                <td style="width:25%" class="Active_Web_Offline align_center discreet" title="{t}No public products{/t}"><i class="fa fa-microphone-slash discreet " aria-hidden="true" "></i> {$category->get('Active Web Offline')}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>

    <div style="clear:both">
    </div>
</div>


<script>

    function show_images_tab() {
        change_tab('category.images')
    }


</script>