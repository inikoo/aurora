{include file='header.tpl'} 
<div id="bd">
	<div>
		{include file='assets_navigation.tpl'} 
		<input type='hidden' id="family_key" value="{$family->id}"> 
		<input type='hidden' id="number_sites" value="{$store->get('Store Websites')}"> 
		<input type='hidden' id="site_key" value="{$store->get_site_key()}"> 
		<input type="hidden" id="subject" value="family"> 
		<input type="hidden" id="subject_key" value="{$family->id}"> 
		<input type="hidden" id="scope" value="family"> 
		<input type="hidden" id="scope_key" value="{$family->id}"> 
		<input type="hidden" id="products_table_id" value="0"> 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}" title="{$store->get('Store Name')}" >{$store->get('Store Code')}</a> &rarr; <a id="department_branch_link" href="department.php?id={$department->id}" title="{$department->get('Product Department Name')}">{$department->get('Product Department Code')}</a> &rarr; <a href="family.php?id={$family->id}" title="{$family->get('Product Family Name')}">{$family->get('Product Family Code')}</a> ({t}Editing{/t})</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons">
				{if isset($next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} <button style="margin-left:0px" onclick="window.location='family.php?id={$family->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> <button id="show_delete_family_dialog" style="margin-left:0px; {if !$family->get_number_products() || !$can_delete}display:none{/if}"><img src="art/icons/delete.png" alt="" /> {t}Delete{/t}</button> 
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
		<li> <span class="item {if $edit=='details'}selected{/if}" id="details"> <span> {t}Family{/t}</span></span></li>
		<li> <span class="item {if $edit=='products'}selected{/if}" id="products"><span> {t}Products{/t}</span></span></li>
		<li> <span class="item {if $edit=='web'}selected{/if} " id="web"><span> {t}Web Pages{/t}</span></span></li>
	</ul>
	<div class="tabbed_container no_padding">
		<div id="d_details" class="edit_block" style="{if $edit!='details'}display:none{/if}">
			<div class="buttons small left tabs">
				<button class="item indented {if $edit_details_subtab=='department'}selected{/if}" id="details_subtab_department" block_id="department">{t}Department{/t}</button> <button class="item {if $edit_details_subtab=='code'}selected{/if}" id="details_subtab_code" block_id="code">{t}Name, Code{/t}</button> <button class="item {if $edit_details_subtab=='info'}selected{/if}" id="details_subtab_info" block_id="info">{t}Description{/t}</button> <button class="item {if $edit_details_subtab=='discounts'}selected{/if}" id="details_subtab_discounts" block_id="discounts">{t}Discounts{/t}</button> <button class="item {if $edit_details_subtab=='pictures'}selected{/if}" id="details_subtab_pictures" block_id="pictures">{t}Pictures{/t}</button> 
			</div>
			<div class="tabs_base">
			</div>
			<div id="d_details_subtab_code" style="{if $edit_details_subtab!='code' }display:none{/if};padding:20px">
				<table style="clear:both;width:800px" class="edit" border="0">
					<tr class="title">
						<td colspan="2">{t}Family Details{/t} </td>
					</tr>
					<tr>
						<td class="label" style="width:100px">{t}Code{/t}:</td>
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
						<td class="label">{t}Name{/t}:</td>
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
						<td class="label">{t}Label{/t}:</td>
						<td> 
						<div>
							<input id="special_char" type='text' maxlength="255" class='text' value="{$family->get('Product Family Special Characteristic')}" ovalue="{$family->get('Product Family Special Characteristic')}" />
							<div id="special_char_Container">
							</div>
						</div>
						</td>
						<td id="special_char_msg" class="edit_td_alert" style="width:300px"></td>
					</tr>
					<tr class="buttons">
						<td colspan="2"> 
						<div class="buttons">
							<button id="save_edit_family" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_family" class="negative disabled">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
			</div>
			<div id="d_details_subtab_pictures" style="{if $edit_details_subtab!='pictures' }display:none{/if};padding:20px">
				{include file='edit_images_splinter.tpl' parent=$family} 
			</div>
			<div id="d_details_subtab_discounts" style="{if $edit_details_subtab!='discounts' }display:none{/if};padding:20px">
				<div class="data_table" sxtyle="margin:25px 10px;">
					<span class="clean_table_title" style="margin-right:5px">{t}Deals{/t}</span> 
					<div class="buttons small left">
						<button id="add_deal"> <img src="art/icons/add.png"> {t}New{/t}</button> 
					</div>
					<div class="table_top_bar space">
					</div>
					{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 } 
					<div id="table4" style="font-size:90%" class="data_table_container dtable btable">
					</div>
				</div>
			</div>
			<div id="d_details_subtab_department" style="{if $edit_details_subtab!='department' }display:none{/if};padding:20px">
					<input type="hidden" id="Family_Department_Key" value="{$family->get('Product Family Main Department Key')}" ovalue="{$family->get('Product Family Main Department Key')}" oformatedvalue="{$family->get('Product Family Main Department Code')}" oformatedvalue_bis="{$family->get('Product Family Main Department Name')}">

				<table style="clear:both;width:800px" class="edit" border="0">
					<tr class="title">
						<td colspan="5">{t}Department{/t}</td>
					</tr>
					<tr class="first">
						<td style="width:180px" class="label">{t}Department{/t}:</td>
						<td style="text-align:left"> <span id="current_department_code">{$family->get('Product Family Main Department Code')}</span>, <span id="current_department_name">{$family->get('Product Family Main Department Name')}</span> <img id="edit_family_department" style="margin-left:5px;cursor:pointer" src="art/icons/edit.gif" alt="{t}Edit{/t}" title="{t}Edit{/t}" /s> </td>
						<td style="width:200px" id="Family_Department_Key_msg" class="edit_td_alert"></td>
					</tr>
					
					<tr class="buttons">
							<td></td>
							<td > 
							<div class="buttons" style="float:left">
								<button class="positive disabled" id="save_edit_family_department">{t}Save{/t}</button> 
								<button class="negative disabled" id="reset_edit_family_department">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					
				</table>
			</div>
			<div id="d_details_subtab_info" style="{if $edit_details_subtab!='info' }display:none{/if}">
				<table class="edit" style="width:890px;padding:20px;margin-left:20px;margin-top:10px">
					<tr class="title space10">
						<td>{t}Family Description{/t} <span id="Family_Description_msg"></span></td>
						<td> 
						<div class="buttons small">
							<button style="margin-right:10px" id="save_edit_family_general_description" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px" id="reset_edit_family_general_description" class="negative disabled">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
					<tr>
					</tr>
				</table>
				<form onsubmit="return false;" style="position:relative;left:-3px">
<textarea id="Family_Description" ovalue="{$family->get('Product Family Description')|escape}" rows="20" cols="75">{$family->get('Product Family Description')|escape}</textarea> 
				</form>
			</div>
		</div>
		<div id="d_web" class="edit_block" style="margin:0;padding:0 0px;{if $edit!='web'}display:none{/if};">
			<div style="padding:20px">
				<span class="clean_table_title" style="margin-right:5px">{t}Pages{/t}</span> 
				<div class="buttons small left">
					<button id="new_family_page" class=""><img src="art/icons/add.png"> {t}New{/t}</button> 
				</div>
				<div class="table_top_bar">
				</div>
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="{if $pages_view=='page_properties'}selected{/if}" id="page_properties">{t}Page Properties{/t}</button> <button class="{if $pages_view=='page_html_head'}selected{/if}" id="page_html_head">{t}HTML Head{/t}</button> <button class="{if $pages_view=='page_header'}selected{/if}" id="page_header">{t}Header{/t}</button> 
					</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6 } 
				<div id="table6" style="font-size:85%" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div id="d_products" class="edit_block" style="margin:0;padding:0 0px;{if $edit!='products'}display:none{/if}">
			<div class="data_table" style="clear:both;padding:20px">
				<span class="clean_table_title" style="margin-right:5px">{t}Products{/t}</span> 
				<div class="buttons small left">
					<button style="margin-left:0px" id="new_product"><img src="art/icons/add.png" alt="" /> {t}New{/t}</button> 
				</div>
				<div class="elements_chooser">
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
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" style="font-size:90%" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
	</div>
	<div class="buttons small">
		<button id="show_history" style="{if $show_history}display:none{/if};margin-right:0px" onclick="show_history('family')">{t}Show changelog{/t}</button> <button id="hide_history" style="{if !$show_history}display:none{/if};margin-right:0px" onclick="hide_history('family')">{t}Hide changelog{/t}</button> 
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
<div id="dialog_department_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Department List{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_new_product" style="padding:20px 20px 10px 20px ">
	<div id="new_product_msg">
	</div>
	<div class="buttons small">
		<button onclick="window.location='associate_product_part.php?id={$family->id}'">{t}Manually{/t}</button> <button onclick="window.location='import.php?subject=family&subject_key={$family->id}'">{t}Import from file{/t}</button> <button class="negative" id="close_dialog_new_product">{t}Cancel{/t}</button> 
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
<div id="dialog_delete_family" style=";padding:10px 20px 20px 20px;width:350px">
	<h2>
		{t}Delete Family{/t} 
	</h2>
	<p>
		{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t} 
	</p>
	<p id="delete_family_msg">
	</p>
	<div class="buttons">
		<span id="deleting" style="display:none;"><img src="art/loading.gif" /> {t}Deleting family, wait please{/t}</span><button id="cancel_delete_family" style="cursor:pointer;font-weight:800">{t}No i dont want to delete it{/t}</button> <button id="save_delete_family" style="cursor:pointer;margin-left:20px;">{t}Yes, delete it!{/t}</button> 
	</div>
	<div style="clear:both">
	</div>
</div>
{include file='assert_elements_splinter.tpl'} {include file='footer.tpl'} 