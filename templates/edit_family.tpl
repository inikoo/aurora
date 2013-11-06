{include file='header.tpl'} 
<div id="bd">
	<div>
		{include file='assets_navigation.tpl'} 
		<input type='hidden' id="family_key" value="{$family->id}"> 
		<input type='hidden' id="number_sites" value="{$store->get('Store Websites')}"> 
		<input type='hidden' id="site_key" value="{$store->get_site_key()}"> 
		<input type="hidden" id="subject" value="family"> 
		<input type="hidden" id="subject_key" value="{$family->id}"> 
		<input type="hidden" id="products_table_id" value="0"> 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; <a href="department.php?id={$department->id}">{$department->get('Product Department Name')}</a> &rarr; {$family->get('Product Family Code')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons">
				{if isset($next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} <button style="margin-left:0px" onclick="window.location='family.php?id={$family->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button>  <button style="margin-left:0px; {if !$family->get_number_products() || !$can_delete}display:none{/if}" onclick="delete_family()"><img src="art/icons/delete.png" alt="" /> {t}Delete{/t}</button> 
			</div>
			<div class="buttons" style="float:left">
				{if isset($prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} <span class="main_title">{t}Family{/t}: <span id="title_name">{$family->get('Product Family Name')}</span> <span class="id" id="title_code">({$family->get('Product Family Code')})</span></span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div id="msg_div">
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='details'}selected{/if}" id="details"> <span> {t}Description{/t}</span></span></li>
		<li> <span class="item {if $edit=='discounts'}selected{/if}" id="discounts"> <span> {t}Discounts{/t}</span></span></li>
		<li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"><span> {t}Pictures{/t}</span></span></li>
		<li> <span class="item {if $edit=='products'}selected{/if}" id="products"><span> {t}Products{/t}</span></span></li>
		<li> <span class="item {if $edit=='web'}selected{/if} " id="web"><span> {t}Web Pages{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div id="d_details" class="edit_block" style="{if $edit!='details'}display:none{/if}">
			<table style="clear:both;width:800px" class="edit" border="0">
				<tr class="title">
					<td>{t}Family Details{/t} </td>
					<td colspan="2"> 
					<div class="buttons">
						<button id="save_edit_family" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_family" class="negative disabled">{t}Reset{/t}</button> 
					</div>
					</td>
				</tr>
				<tr style="display:">
					<td colspan="2" style="text-align:right;color:#777;font-size:90%"> 
					<div id="delete_family_warning" style="border:1px solid red;padding:5px 5px 15px 5px;color:red;display:none;text-align:center">
						<h2>
							{t}Delete Family{/t} 
						</h2>
						<p>
							{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t} 
						</p>
						<p id="delete_family_msg">
						</p>
						<div class="buttons">
							<button id="cancel_delete_family" style="cursor:pointer;display:none;font-weight:800">{t}No i dont want to delete it{/t}</button> <button id="save_delete_family" style="cursor:pointer;display:none;margin-left:20px;">{t}Yes, delete it!{/t}</button> 
						</div>
						<p id="deleting" style="display:none;">
							{t}Deleting family, wait please{/t} 
						</p>
						<div style="clear:both">
						</div>
					</div>
					</td>
					<td> </td>
				</tr>
				<tr>
					<td class="label" style="width:100px">{t}Family Code{/t}:</td>
					<td> 
					<div>
						<input id="code" changed="0" type='text' class='text' maxlength="16" value="{$family->get('Product Family Code')}" ovalue="{$family->get('Product Family Code')}" />
						<div id="code_Container">
						</div>
					</div>
					</td>
					<td id="code_msg" class="edit_td_alert" style="width:300px"></td>
				</tr>
				<tr>
					<td class="label">{t}Family Name{/t}:</td>
					<td> 
					<div>
						<input id="name" changed="0" type='text' maxlength="255" class='text' value="{$family->get('Product Family Name')}" ovalue="{$family->get('Product Family Name')}" />
						<div id="name_Container">
						</div>
					</div>
					</td>
					<td id="name_msg" class="edit_td_alert" style="width:300px"></td>
				</tr>
				<tr>
					<td class="label">{t}Family Char{/t}:</td>
					<td> 
					<div>
						<input id="special_char" type='text' maxlength="255" class='text' value="{$family->get('Product Family Special Characteristic')}" ovalue="{$family->get('Product Family Special Characteristic')}" />
						<div id="special_char_Container">
						</div>
					</div>
					</td>
					<td id="special_char_msg" class="edit_td_alert" style="width:300px"></td>
				</tr>
				<tr style="height:110px">
					<td class="label">{t}Description{/t}:</td>
					<td> 
					<div>
<textarea style="height:100px" id="description" name="description" rows="5" class='text' value="{$family->get('Product Family Description')}" ovalue="{$family->get('Product Family Description')}" />{$family->get('Product Family Description')}</textarea> 
						<div id="description_Container">
						</div>
					</div>
					</td>
					<td id="description_msg" class="edit_td_alert" style="width:300px"></td>
				</tr>
				<tr class="title">
					<td colspan="5">{t}Categories{/t}</td>
				</tr>
				<tr class="first">
					<td style="width:180px" class="label">{t}Department{/t}:</td>
					<td style="text-align:left"> <span id="current_department_code">{$family->get('Product Family Main Department Code')}</span> <img id="edit_family_department" id="family" style="margin-left:5px;cursor:pointer" src="art/icons/edit.gif" alt="{t}Edit{/t}" title="{t}Edit{/t}" /s> </td>
					<td style="width:200px" id="Product_Name_msg" class="edit_td_alert"></td>
				</tr>
			</table>
		</div>
		<div id="d_pictures" class="edit_block" style="{if $edit!='pictures'}display:none{/if}">
			{include file='edit_images_splinter.tpl' parent=$family} 
		</div>
		<div id="d_discounts" class="edit_block" style="margin:0;padding:0 0px;{if $edit!='discounts'}display:none{/if}">
			<div class="new_item_dialog" id="new_deal_dialog" style="display:none">
				<div id="new_deal_messages" class="messages_block">
				</div>
				<table class="edit">
					<tr>
						<td>{t}Deal Name{/t}:</td>
						<td> 
						<input id="new_deal_name" onkeyup="new_deal_changed(this)" onmouseup="new_deal_changed(this)" onchange="new_deal_changed(this)" changed="0" type='text' class='text' maxlength="16" value="" />
						</td>
					</tr>
					<tr>
						<td>{t}Deal Description{/t}:</td>
						<td> 
						<input id="new_deal_description" onkeyup="new_deal_changed(this)" onmouseup="new_deal_changed(this)" onchange="new_deal_changed(this)" changed="0" type='text' maxlength="255" class='text' value="" />
						</td>
					</tr>
				</table>
			</div>
			<div class="data_table" sxtyle="margin:25px 10px;">
				<span class="clean_table_title">{t}Deals{/t}</span> 
				<div class="buttons small">
					<button id="add_deal" class="positive">{t}Add Deal{/t}</button> <button style="display:none" id="save_new_deal">Save New Deal</button> <button style="display:none" id="cancel_add_deal">Cancel</button> 
				</div>
				<div class="clean_table_caption" style="clear:both;">
					<div style="float:left;">
						<div id="table_info4" class="clean_table_info">
							<span id="rtext4"></span> <span class="rtext_rpp" id="rtext_rpp4"></span> <span class="filter_msg" id="filter_msg4"></span> 
						</div>
					</div>
					<div class="clean_table_filter" style="display:none" id="clean_table_filter4">
						<div class="clean_table_info">
							<span id="filter_name4">{$filter_name4}</span>: 
							<input style="border-bottom:none" id='f_input4' value="{$filter_value0}" size="10" />
							<div id='f_container4'>
							</div>
						</div>
					</div>
					<div class="clean_table_controls">
						<div>
							<span style="margin:0 5px" id="paginator4"></span> 
						</div>
					</div>
				</div>
				<div id="table4" style="font-size:90%" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div id="d_web" class="edit_block" style="margin:0;padding:0 0px;{if $edit!='web'}display:none{/if}">
			
			
				<span class="clean_table_title">{t}Pages{/t}</span> 
				
				
				<div class="table_top_bar">
				</div>
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="{if $pages_view=='page_properties'}selected{/if}" id="page_properties">{t}Page Properties{/t}</button> <button class="{if $pages_view=='page_html_head'}selected{/if}" id="page_html_head">{t}HTML Head{/t}</button> <button class="{if $pages_view=='page_header'}selected{/if}" id="page_header">{t}Header{/t}</button> 
					</div>
					
					
					<div class="buttons small" >
				<button id="new_family_page" class=""><img src="art/icons/add.png"> {t}New Page{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
				</div>
				{include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6 } 
				<div id="table6" style="font-size:85%" class="data_table_container dtable btable">
				</div>
			
		</div>
		<div id="d_products" class="edit_block" style="margin:0;padding:0 0px;{if $edit!='products'}display:none{/if}">
			<div class="data_table" style="clear:both">
				<span class="clean_table_title">{t}Products{/t}</span>
				
				<div  class="elements_chooser">
						<img class="menu" id="product_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
						<div id="product_type_chooser" style="{if $elements_product_elements_type!='type'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Historic}selected{/if} label_product_Historic" id="elements_type_Historic" table_type="Historic">{t}Historic{/t} (<span id="elements_type_Historic_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Discontinued}selected{/if} label_product_Discontinued" id="elements_type_Discontinued" table_type="Discontinued">{t}Discontinued{/t} (<span id="elements_type_Discontinued_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Private}selected{/if} label_product_Private" id="elements_type_Private" table_type="Private">{t}Private{/t} (<span id="elements_type_Private_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.NoSale}selected{/if} label_product_NoSale" id="elements_type_NoSale" table_type="NoSale">{t}No Sale{/t} (<span id="elements_type_NoSale_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Sale}selected{/if} label_product_Sale" id="elements_type_Sale" table_type="Sale">{t}Sale{/t} (<span id="elements_type_Sale_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="product_web_chooser" style="{if $elements_product_elements_type!='web'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.ForSale}selected{/if} label_product_ForSale" id="elements_web_ForSale" table_type="ForSale">{t}Online{/t} (<span id="elements_web_ForSale_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.OutofStock}selected{/if} label_product_OutofStock" id="elements_web_OutofStock" table_type="OutofStock">{t}Out of Stock{/t} (<span id="elements_web_OutofStock_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.Discontinued}selected{/if} label_product_Discontinued" id="elements_web_Discontinued" table_type="Discontinued">{t}Discontinued{/t} (<span id="elements_web_Discontinued_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.Offline}selected{/if} label_product_Offline" id="elements_web_Offline" table_type="Offline">{t}Offline{/t} (<span id="elements_web_Offline_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="product_stock_chooser" style="{if $elements_product_elements_type!='stock'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Error}selected{/if} label_product_Error" id="elements_stock_Error" table_type="Error">{t}Error{/t} (<span id="elements_stock_Error_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.OutofStock}selected{/if} label_product_OutofStock" id="elements_stock_OutofStock" table_type="OutofStock">{t}Out of Stock{/t} (<span id="elements_stock_OutofStock_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.VeryLow}selected{/if} label_product_VeryLow" id="elements_stock_VeryLow" table_type="VeryLow">{t}Very Low{/t} (<span id="elements_stock_VeryLow_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Low}selected{/if} label_product_Low" id="elements_stock_Low" table_type="Low">{t}Low{/t} (<span id="elements_stock_Low_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Normal}selected{/if} label_product_Normal" id="elements_stock_Normal" table_type="Normal">{t}Normal{/t} (<span id="elements_stock_Normal_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Excess}selected{/if} label_product_Excess" id="elements_stock_Excess" table_type="Excess">{t}Excess{/t} (<span id="elements_stock_Excess_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">]</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $elements_stock_aux=='InWeb'}selected{/if}" id="elements_stock_aux_InWeb" table_type="InWeb" title="{t}InWeb Products{/t}">{t}In Web{/t}</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $elements_stock_aux=='ForSale'}selected{/if}" id="elements_stock_aux_ForSale" table_type="ForSale" title="{t}ForSale Products{/t}">{t}For Sale{/t}</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $elements_stock_aux=='All'}selected{/if}" id="elements_stock_aux_All" table_type="All" title="{t}All Products{/t}">{t}All{/t}</span> <span style="float:right;margin-left:0px" class=" table_type transaction_type state_details">[</span> 
						</div>
				</div>
				<div class="table_top_bar">
				</div>
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="{if $view=='view_state'}selected{/if}" id="view_state">{t}State{/t}</button> <button class="{if $view=='view_name'}selected{/if}" id="view_name">{t}Name{/t}</button> <button class="{if $view=='view_price'}selected{/if}" id="view_price">{t}Price{/t}</button> 
					</div>
					
					
					<div class="buttons small" style="margin-right:0px" >
				<button style="margin-left:0px" id="new_product"><img src="art/icons/add.png" alt="" /> {t}New Product{/t}</button>
				</div>
					
					<div style="clear:both">
					</div>
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" style="font-size:90%" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
	<div class="buttons small">
		<button id="show_history" style="{if $show_history}display:none{/if};margin-right:0px" onclick="show_history()">{t}Show changelog{/t}</button> <button id="hide_history" style="{if !$show_history}display:none{/if};margin-right:0px" onclick="hide_history()">{t}Hide changelog{/t}</button> 
	</div>
	<div id="history_table" class="data_table" style="clear:both;{if !$show_history}display:none{/if}">
		<span class="clean_table_title">{t}Changelog{/t}</span> 
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table1" class="data_table_container dtable btable history">
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
<div id="dialog_new_product_choose" style="padding:10px;display:none">
	<div id="new_product_choose_msg">
	</div>
	{t}Create new product{/t}: 
	<div class="general_options">
		<table style="margin:10px">
			<tr>
				<td> <span style="margin:0 10px" class="unselectable_text state_details" onclick="new_product_from_part()">{t}From a Part{/t}</span></td>
				<td> <span class="unselectable_text state_details" onclick="new_product_from_scratch()">{t}From Scratch{/t}</span></td>
			</tr>
		</table>
	</div>
</div>
<div id="dialog_new_part" style="display:none;padding:10px;">
	<h2>
		New Part 
	</h2>
	<div class="general_options" style="float:right">
		<span style="margin-right:10px;display:none" id="save_area" class="state_details">{t}Save{/t}</span> <span style="margin-right:10px;" id="close_add_area" class="state_details">{t}Close Dialog{/t}</span> 
	</div>
	<table class="edit">
		<tr>
			<td class="label" style="width:7em">{t}Description{/t}:</td>
			<td> 
			<input name="code" id="new_part_description" name="code" changed="0" type='text' class='text' size="16" value="" maxlength="16" />
			</td>
		</tr>
		<tr>
			<td class="label">{t}Gross Weight{/t}:</td>
			<td> 
			<input name="name" id="new_name" name="code" changed="0" type='text' size="6" maxlength="80" class='text' value="" />
			Kg</td>
		</tr>
		<tr>
			<td class="label">{t}Supplier{/t}:</td>
			<td style="text-align:left"> 
			<div style="width:15em;position:relative;top:00px">
				<input id="supplier" style="text-align:left;width:18em" type="text"> 
				<div id="supplier_container">
				</div>
			</div>
			</td>
		</tr>
		<input id="supplier_key" value="1" type="hidden"> 
		<tr>
			<td> </td>
			<td> 
			<table border="0" class="edit">
				<tr>
					<td class="label" style="width:4em">{t}Code{/t}:</td>
					<td> 
					<input id="supplier_product_code" size="4" type='text' maxlength="20" class='text' />
				</tr>
				<tr>
					<td class="label">{t}Cost{/t}:</td>
					<td> 
					<input id="supplier_produc_cost" size="4" type='text' maxlength="20" class='text' />
				</tr>
				<tr>
					<td class="label">{t}Name{/t}:</td>
					<td> 
					<input id="supplier_produc_name" size="12" type='text' maxlength="20" class='text' />
				</tr>
				<tr>
					<td class="label">{t}Description{/t}:</td>
					<td><textarea id="supplier_product_description" size="4" type='text' maxlength="20" class='text'></textarea> 
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_family_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Family List{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_new_product" style="padding:20px 20px 10px 20px ">
	<div id="new_product_msg">
	</div>
	<div class="buttons">
		<button class="positive" onclick="window.location='associate_product_part.php?id={$family->id}'">{t}Manually{/t}</button> <button class="positive" onclick="window.location='import.php?subject=family&subject_key={$family->id}'">{t}Import from file{/t}</button> <button class="negative" id="close_dialog_new_product">{t}Cancel{/t}</button> 
	</div>
</div>
<div id="dialog_edit_deal" style="padding:20px 20px 0px 10px;width:350px">
	<input type="hidden" id="edit_deal_key" value="" />
	<table style="width:100%" class="edit">
		<tr>
			<td style="width:3em" class="label">{t}Name{/t}:</td>
			<td> 
			<input style="width:100%" id="deal_name_input" value=""></td>
		</tr>
		<tr>
			<td class="label">{t}Description{/t}:</td>
			<td><textarea style="width:100%;height:100px" id="deal_description_input" value=""></textarea></td>
		</tr>
		<tr style="height:10px">
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td> 
			<div class="buttons">
				<button class="positive" onclick="save_edit_deal()">{t}Save{/t}</button> <button class="negative" onclick="cancel_edit_deal()">{t}Reset{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{include file='assert_elements_splinter.tpl'} {include file='footer.tpl'} 