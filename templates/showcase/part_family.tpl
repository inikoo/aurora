<div class="name_and_categories">
	<span class="strong"><span class="Category_Label">{$category->get('Label')}</span> </span> 
	<ul class="tags Categories" style="float:right">
		{foreach from=$category->get_category_data() item=item key=key} 
		<li><span class="button" onclick="change_view('category/{$item.category_key}')" title="{$item.label}">{$item.code}</span></li>
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
				<img src="/{if $image_key}image_root.php?id={$image_key}&amp;size=small{else}art/nopic.png{/if}"> </span> 
			</div>
			{include file='upload_main_image.tpl' object='Category' key=$category->id class="{if $image_key!=''}hide{/if}"} 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div class="block sales_data">
		<table>
			<tr class="header">
				<td colspan="3">{$header_total_sales}</td>
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
				<td><span title="{$category->get('Year To Day Acc Invoiced Amount')}">{$category->get('Year To Day Acc Invoiced Amount Minify')}</span> <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span></td>
				<td><span title="{$category->get('1 Year Ago Invoiced Amount')}">{$category->get('1 Year Ago Invoiced Amount Minify')}</span> <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span></td>
				<td><span title="{$category->get('2 Year Ago Invoiced Amount')}">{$category->get('2 Year Ago Invoiced Amount Minify')}</span> <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span></td>
				<td><span title="{$category->get('3 Year Ago Invoiced Amount')}">{$category->get('3 Year Ago Invoiced Amount Minify')}</span> <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span></td>
				<td><span title="{$category->get('4 Year Ago Invoiced Amount')}">{$category->get('4 Year Ago Invoiced Amount Minify')}</span> <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span></td>
			</tr>
			<tr>
				<td><span title="{$category->get('Year To Day Acc Dispatched')}">{$category->get('Year To Day Acc Dispatched Minify')}</span> <span title="{$year_data.0.dispatched_delta_title}">{$year_data.0.dispatched_delta}</span></td>
				<td><span title="{$category->get('1 Year Ago Dispatched')}">{$category->get('1 Year Ago Dispatched Minify')}</span> <span title="{$year_data.1.dispatched_delta_title}">{$year_data.1.dispatched_delta}</span></td>
				<td><span title="{$category->get('2 Year Ago Dispatched')}">{$category->get('2 Year Ago Dispatched Minify')}</span> <span title="{$year_data.2.dispatched_delta_title}">{$year_data.2.dispatched_delta}</span></td>
				<td><span title="{$category->get('3 Year Ago Dispatched')}">{$category->get('3 Year Ago Dispatched Minify')}</span> <span title="{$year_data.3.dispatched_delta_title}">{$year_data.3.dispatched_delta}</span></td>
				<td><span title="{$category->get('4 Year Ago Dispatched')}">{$category->get('4 Year Ago Dispatched Minify')}</span> <span title="{$year_data.4.dispatched_delta_title}">{$year_data.4.dispatched_delta}</span></td>
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
				<td><span title="{$category->get('Quarter To Day Acc Invoiced Amount')}">{$category->get('Quarter To Day Acc Invoiced Amount Minify')}</span> <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span></td>
				<td><span title="{$category->get('1 Quarter Ago Invoiced Amount')}">{$category->get('1 Quarter Ago Invoiced Amount Minify')}</span> <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span></td>
				<td><span title="{$category->get('2 Quarter Ago Invoiced Amount')}">{$category->get('2 Quarter Ago Invoiced Amount Minify')}</span> <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span></td>
				<td><span title="{$category->get('3 Quarter Ago Invoiced Amount')}">{$category->get('3 Quarter Ago Invoiced Amount Minify')}</span> <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span></td>
				<td><span title="{$category->get('4 Quarter Ago Invoiced Amount')}">{$category->get('4 Quarter Ago Invoiced Amount Minify')}</span> <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span></td>
			</tr>
			<tr>
				<td><span title="{$category->get('Quarter To Day Acc Dispatched')}">{$category->get('Quarter To Day Acc Dispatched Minify')}</span> <span title="{$quarter_data.0.dispatched_delta_title}">{$quarter_data.0.dispatched_delta}</span></td>
				<td><span title="{$category->get('1 Quarter Ago Dispatched')}">{$category->get('1 Quarter Ago Dispatched Minify')}</span> <span title="{$quarter_data.1.dispatched_delta_title}">{$quarter_data.1.dispatched_delta}</span></td>
				<td><span title="{$category->get('2 Quarter Ago Dispatched')}">{$category->get('2 Quarter Ago Dispatched Minify')}</span> <span title="{$quarter_data.2.dispatched_delta_title}">{$quarter_data.2.dispatched_delta}</span></td>
				<td><span title="{$category->get('3 Quarter Ago Dispatched')}">{$category->get('3 Quarter Ago Dispatched Minify')}</span> <span title="{$quarter_data.3.dispatched_delta_title}">{$quarter_data.3.dispatched_delta}</span></td>
				<td><span title="{$category->get('4 Quarter Ago Dispatched')}">{$category->get('4 Quarter Ago Dispatched Minify')}</span> <span title="{$quarter_data.4.dispatched_delta_title}">{$quarter_data.4.dispatched_delta}</span></td>
			</tr>
		</table>
	</div>
	<div class="block info">
		<div id="overviews">
			<table id="stock_table" border="0" class="overview">
				<tbody class="info">
					<tr class="main ">
						<td class=" highlight Part_Status">{$category->get('Status')} </td>
						<td class="aright "> <span class=" {if $category->get('Part Category Status')!='NotInUse'}hide{/if}">{$category->get('Valid To')}</span> </td>
					</tr>
					
					<tr>
					<td colspan=2>
                    <table  style="width:100%;;margin-bottom:10px">
                    <tr style="border-top:1px solid #ccc;border-bottom:1px solid #ccc">
						<td style="border-left:1px solid #ccc;width:25%" class="In_Process_Parts acenter discreet" title="{t}Parts in process{/t}">{$elements_numbers.status.InProcess}</td>
						<td style="border-left:1px solid #ccc;width:25%" class="In_Use_Parts acenter" title="{t}Parts active{/t}">{$elements_numbers.status.InUse}</td>
						<td style="border-left:1px solid #ccc;width:25%" class="Discontinuing_Parts acenter discreet " title="{t}Parts discontinuing{/t}">{$elements_numbers.status.Discontinuing}</td>
						<td style="border-left:1px solid #ccc;width:25%;border-right:1px solid #ccc;" class="Not_In_Use_Parts acenter very_discreet" title="{t}Parts discontinued{/t}">{$elements_numbers.status.NotInUse}</td>
					</tr>
					</table>
					</td>
					</tr>
				</tbody>
				<tr class="main hide edit_controls" id="edit_stock_controls">
					<td colspan="2"> 
					<table style="width:100%">
						<tr>
							<td class="super_discreet highlight Current_On_Hand_Stock" style="font-size:200%">{$category->get('Current On Hand Stock')}</td>
							<td id="stock_diff" class="acenter"> </td>
							<td id="new_stock" class="aright highlight" style="font-size:200%"></td>
						</tr>
						<tr>
							<td><i class="fa  fa-times button discreet" aria-hidden="true" title="{t}Close edit{/t}" onclick="close_edit_stock()"></i> </td>
							<td></td>
							<td id="saving_buttons" class="aright discreet  "> <span id="saving_buttons" class="aright discreet   &gt;&lt;span class=" save">{t}Save{/t}</span><i class="fa  fa-cloud   save " aria-hidden="true" title="{t}Save{/t}" id="save_stock" onclick="save_stock()"></i></span> </td>
						</tr>
					</table>
					</td>
				</tr>
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