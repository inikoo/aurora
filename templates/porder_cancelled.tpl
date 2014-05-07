{include file='header.tpl'} 
<input type="hidden" value="{$po->id}" id="po_key"> 
<input type="hidden" value="{$supplier->id}" id="supplier_key"> 
<div id="bd">
	{include file='suppliers_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a href="supplier.php?id={$supplier->id}">{$supplier->get('Supplier Name')}</a> &rarr; {$po->get('Purchase Order Public ID')} ({$po->get('Purchase Order Current Dispatch State')})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Purchase Order{/t} <span class="id">{$po->get('Purchase Order Public ID')}</span></span> 
		</div>
		<div class="buttons">
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div class="prodinfo" style="margin-top:2px;font-size:85%;border:1px solid #ddd;padding:10px;">
		<table style="width:200px;color:#ccc;border-top: 1px solid #ccc" class="order_header">
			<tr>
				<td>{t}Goods{/t}:</td>
				<td id="goods" class="aright">{$po->get('Items Net Amount')}</td>
			</tr>
			<tr>
				<td>{t}Shipping{/t}:</td>
				<td class="aright" id="shipping">{$po->get('Shipping Net Amount')}</td>
			</tr>
			<tr>
				<td>{t}Tax{/t}:</td>
				<td id="vat" class="aright">{$po->get('Total Tax Amount')}</td>
			</tr>
			<tr>
				<td>{t}Total{/t}</td>
				<td id="total" class="stock aright ">{$po->get('Total Amount')}</td>
			</tr>
		</table>
		<div style="border:0px solid red;xwidth:290px;float:right">
			<table border="0" class="order_header" style="margin-right:30px;float:right">
				<tr>
					<td class="aright" style="padding-right:40px">{t}Created{/t}:</td>
					<td>{$po->get('Creation Date')}</td>
				</tr>
				<tr>
					<td class="aright" style="padding-right:40px">{t}Submitted{/t}:</td>
					<td>{$po->get('Submitted Date')}</td>
				</tr>
				<tr>
					<td colspan="2" class="aright">{t}via{/t} {$po->get('Purchase Order Main Source Type')} {t}by{/t} {$po->get('Purchase Order Main Buyer Name')}</td>
				</tr>
				<tr>
					<td class="aright" style="padding-right:40px">{t}Cancelled{/t}:</td>
					<td>{$po->get('Cancelled Date')}</td>
				</tr>
			</table>
		</div>
		<table border="0">
			<tr>
				<td>{t}Purchase Order Id{/t}:</td>
				<td class="aright">{$po->get('Purchase Order Key')}</td>
			</tr>
			<tr>
				<td>{t}Supplier{/t}:</td>
				<td class="aright"><a href="supplier.php?id={$supplier->get('Supplier Key')}">{$supplier->get('Supplier Name')}</a></td>
			</tr>
			<tr>
				<td>{t}Items{/t}:</td>
				<td class="aright" id="distinct_products">{$po->get('Number Items')}</td>
			</tr>
		</table>
		<table style="clear:both;border:none;" class="notes">
			<tr>
				<td style="border:none">{t}Notes{/t}:</td>
				<td>{$po->get('Purchase Order Cancel Note')}</td>
			</tr>
		</table>
		<div style="clear:both">
		</div>
	</div>
	<div id="the_table" class="data_table" style="margin:20px 0px;clear:both">
		<span class="clean_table_title">{t}Supplier products ordered{/t}</span> 
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
		<div id="table0" style="font-size:80%" class="data_table_container dtable btable">
		</div>
	</div>
</div>

<div id="filtermenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='footer.tpl'} 