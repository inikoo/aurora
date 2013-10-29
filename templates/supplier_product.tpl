{include file='header.tpl'} 
<input id="part_sku" value="{$part_sku}" type="hidden"> 
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container">
</div>
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		{include file='suppliers_navigation.tpl'} 
		<div class="branch">
			<span><a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a href="supplier.php?id={$supplier->id}">{$supplier->get('Supplier Name')}</a> &rarr; {$supplier_product->get('Supplier Product Code')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
							<span class="main_title"><span class="id">{$supplier_product->get('Supplier Product Code')}</span> {$supplier_product->get('Supplier Product Name')} </span>

			</div>
			<div class="buttons">
				<button  onclick="window.location='edit_supplier_product.php?id='">{t}Edit Supplier Product{/t}</button> 
				<button  onclick="window.location='new_part.php?id={$pid}'">{t}Add Part{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>

		<div id="block_info" style="width:900px;margin-top:10px">
			<div id="photo_container" style="float:left">
				<div style="width:220px;">
					<div id="barcode" style="margin:auto;">
					</div>
				</div>
				<div style="border:1px solid #ddd;padding-stop:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
					<div id="imagediv" style="border:1px solid #ddd;width:190px;;padding:5px 5px;xborder:none;cursor:pointer;xbackground:red;margin: 10px 0 10px 9px;vertical-align:middle">
						<img id="main_image" src="{$supplier_product->get('Part Main Image')}" style="vertical-align:middle;display:block;;width:190px;;margin:0px auto" valign="center" border="1" id="image" alt="{t}Image{/t}" /> 
					</div>
				</div>
				<div style="width:160px;margin:auto;padding-top:5px">
					{foreach from=$supplier_product->get_images_slidesshow() item=image name=foo} {if $image.is_principal==0} <img style="float:left;border:1px solid#ccc;padding:2px;margin:2px;cursor:pointer" src="{$image.thumbnail_url}" title="" alt="" /> {/if} {/foreach} 
				</div>
			</div>
			<div style="width:340px;float:left;margin-left:10px;">
				<table class="show_info_product">
					<tr>
						<td>{t}Supplier{/t}:</td>
						<td class="aright"><a href="supplier.php?id={$supplier_product->get('Supplier Key')}">{$supplier_product->get('Supplier Code')}</a></td>
					</tr>
					<tr>
						<td>{t}Code{/t}:</td>
						<td class="aright">{$supplier_product->get('Supplier Product Code')}</td>
					</tr>
					<tr>
						<td>{t}Name{/t}:</td>
						<td class="aright">{$supplier_product->get('Supplier Product Name')}</td>
					</tr>
					<tr>
						<td>{t}Unit{/t}:</td>
						<td class="aright">{$supplier_product->get('Units')}</td>
					</tr>
				</table>
				<div style="text-align:right">
					{t}Sold by unit{/t}
				</div>
				<table class="show_info_product">
					<tr>
						<td>{if $supplier_product->get('Supplier Product Units Per Case')==1 }{t}Cost{/t}{else}{t}Unit Cost{/t}{/if}:</td>
						<td class="price aright">{$supplier_product->get_formated_price_per_unit()}</td>
					</tr>
					<tbody style="{if $supplier_product->get('Supplier Product Units Per Case')==1 }display:none{/if}">
						<tr>
							<td>{t}Unit per Case{/t}:</td>
							<td class="aright">{$supplier_product->get('Units Per Case')}</td>
						</tr>
						<tr>
							<td>{t}Cost per Case{/t}:</td>
							<td class="aright">{$supplier_product->get_formated_price_per_case()}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="width:280px;float:left;margin-left:20px">
				<table class="show_info_product" style="{if $supplier_product->get('Product Record Type')=='Historic'}display:none{/if};float:right;width:100%">
					<tr>
						<td>{t}Current Stock{/t}:</td>
					</tr>
					{foreach from=$supplier_product->get_parts() item=part_data} 
					<tr>
						<td style="vertical-align:bottom">1&rarr;{$part_data.Parts_Per_Supplier_Product_Unit}</td>
						<td style="vertical-align:bottom"><a href="part.php?sku={$part_data.part->sku}">{$part_data.part->get_sku()}</a></td>
						<td class="stock" style="padding:0">{$part_data.part->get('Current Stock')}</td>
						<td>{$part_data.part->get('Part XHTML Available For Forecast')}</td>
					</tr>
					{/foreach} 
				</table>
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Details{/t}</span></span></li>
		<li> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t} <i>(Parts)</i></span></span></li>
		<li> <span class="item {if $block_view=='stock'}selected{/if}" id="stock"> <span> {t}Stock{/t}</span></span></li>
		<li> <span class="item {if $block_view=='purchase_orders'}selected{/if}" id="purchase_orders"> <span> {t}Purchase Orders{/t}</span></span></li>
		<li> <span class="item {if $block_view=='timeline'}selected{/if}" id="timeline"> <span> {t}History{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div style="padding:0 20px">
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div style="width:280px;float:left;margin-right:20px">
				<table class="show_info_product">
					<tr>
						<td>{t}Unit Weight{/t}:</td>
						<td class="aright">{$supplier_product->get('Formated Weight')}</td>
					</tr>
					<tr>
						<td>{t}Unit Dimensions{/t}:</td>
						<td class="aright">{$supplier_product->get('Formated Dimensions')}</td>
					</tr>
				</table>
			</div>
		</div>
		<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>
		<div id="block_stock" style="{if $block_view!='stock'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span class="clean_table_title">{t}Stock History Chart{/t} <img id="hide_stock_history_chart" alt="{t}hide{/t}" title="{t}Hide Chart{/t}" style="{if !$show_stock_history_chart}display:none;{/if}cursor:pointer;vertical-align:middle;" src="art/icons/hide_button.png" /> <img id="show_stock_history_chart" alt="{t}show{/t}" title="{t}Show Chart{/t}" style="{if $show_stock_history_chart}display:none;{/if}cursor:pointer;vertical-align:middle" src="art/icons/show_button.png" /> </span> 
			<div id="stock_history_plot" style="{if !$show_stock_history_chart}display:none;{/if}">
				<strong>You need to upgrade your Flash Player</strong> 
			</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "930", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_candlestick.xml.php?tipo=part_stock_history&sku={$part_sku}"));
		so.addVariable("preloader_color", "#999999");
		so.write("stock_history_plot");
		// ]]>
	</script> <span class="clean_table_title" style="clear:both;margin-top:20px">{t}Stock History{/t} 
			<div id="stock_history_type" style="display:inline;color:#aaa">
				<span id="stock_history_type_day" table_type="day" style="margin-left:10px;font-size:80%;" class="table_type state_details {if $stock_history_type=='day'}selected{/if}">{t}Daily{/t}</span> <span id="stock_history_type_week" table_type="week" style="margin-left:5px;font-size:80%;" class="table_type state_details {if $stock_history_type=='week'}selected{/if}">{t}Weekly{/t}</span> <span id="stock_history_type_month" table_type="month" style="margin-left:5px;font-size:80%;" class="table_type state_details {if $stock_history_type=='month'}selected{/if}">{t}Monthly{/t}</span> 
			</div>
			</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px">
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 no_filter=1 } 
			<div id="table1" style="font-size:85%" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_timeline" style="{if $block_view!='timeline'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>
		<div id="block_purchase_orders" style="{if $block_view!='purchase_orders'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span id="table_title" class="clean_table_title">{t}Purchase Orders with this Product{/t}</span> {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table0" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'} 