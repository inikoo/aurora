{include file='header.tpl'} 
<input type="hidden" value="{$session_data}" id="session_data" />
<input type="hidden" value="{$store->id}" id="store_key" />
<input type="hidden" value="{$deal->id}" id="deal_key" />
<input type="hidden" value="{$post_create_action}" id="post_create_action" />
<input type="hidden" id="trigger" value="{$trigger}" />
<input type="hidden" id="trigger_key" value="{$trigger_key}" />
<input type="hidden" id="terms_type" value="{$deal->get('Deal Terms Type')}" />


<div id="bd">
	{include file='marketing_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.png" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr; {/if} <a href="marketing.php?store={$store->id}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr; <a href="campaign.php?id={$campaign->id}">{$campaign->get('Deal Campaign Name')}</a> &rarr; <a href="deal.php?id={$deal->id}">{$deal->get('Deal Name')}</a></span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}New Allowance{/t} @ {t}Deal{/t} <span class="id">{$deal->get('Deal Name')}</span></span> 
		</div>
		<div class="buttons">
			<button class="negative" onclick="window.location='{$link_back}'">{t}Cancel{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<table id="new_deal" class="edit" border="0">
		<tr class="title ">
			<td colspan="2">{t}Terms and Allowances {/t}</td>
		</tr>
		<tr>
			<td></td>
			<td><span>{$deal->get('Deal Term Allowances Label')}</span></td>
		</tr>
		<tr class="title space20">
			<td colspan="2">{t}New Allowance{/t}</td>
		</tr>
		<tr class="space15">
			<td class="label" style>{t}Allowance{/t}:</td>
			<td class="input"> 
						<input type="hidden" id="target" value="{$target}"> 
				<input type="hidden" id="target_key" value="{$target_key}"> 

			<input type="hidden" id="allowances" value> 
			<div class="styled-select" id="allowances_select">
				<select id="order_more_than_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
				</select>
				<select id="for_every_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Get Same Free">{t}Give x extra free{/t}</option>
				</select>
				<select id="for_every_any_product_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Get Cheapest Free">{t}Get x cheapest free{/t}</option>
				</select>
				<select id="every_order_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
					<option value="Amount Off">{t}Amount off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}products{/t})</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Mirror other deal allowances{/t}</option>
				</select>
				<select id="next_order_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
					<option value="Amount Off">{t}Amount off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}Department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}Family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}Products{/t})</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Mirror other deal allowances{/t}</option>
				</select>
				<select id="voucher_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}Department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}Family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}Products{/t})</option>
					<option value="Bonus Product From Family">{t}Free Product from a Bonus Family{/t}</option>
					<option value="Bonus Product">{t}Free product{/t}</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Mirror other deal allowances{/t}</option>
				</select>
				<select id="amount_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
					<option value="Amount Off">{t}Amount off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}Department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}Family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}Products{/t})</option>
					<option value="Bonus Product From Family">{t}Free Product from a Bonus Family{/t}</option>
					<option value="Bonus Product">{t}Free product{/t}</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Mirror other deal allowances{/t}</option>
				</select>
				<select id="order_interval_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
					<option value="Amount Off">{t}Amount off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}Department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}Family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}Products{/t})</option>
					<option value="Bonus Product From Family">{t}Free Product from a Bonus Family{/t}</option>
					<option value="Bonus Product">{t}Free product{/t}</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Mirror other deal allowances{/t}</option>
				</select>
				<select id="order_number_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage Off{/t}</option>
					<option value="Amount Off">{t}Amount off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}Department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}Family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}Products{/t})</option>
					<option value="Bonus Product From Family">{t}Free Product from a Bonus Family{/t}</option>
					<option value="Bonus Product">{t}Free product{/t}</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Mirror other deal allowances{/t}</option>
				</select>
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tr id="target_bis_department_options" style="display:none">
			<td class="label">{t}Department{/t}:</td>
			<td class="input"> 
			<div class="buttons small left">
				<span style="float:left;margin-right:10px" id="target_bis_department_formated"></span> <button style id="target_bis_update_department" onclick="show_dialog_departments_list('target_bis')">{t}Change department{/t}</button> 
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tr id="target_bis_family_options" style="display:none">
			<td class="label">{t}Family{/t}:</td>
			<td class="input"> 
			<div class="buttons small left">
				<span style="float:left;margin-right:10px" id="target_bis_family_formated"></span> <button style id="target_bis_update_family" onclick="show_dialog_families_list('target_bis')">{t}Change family{/t}</button> 
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tr id="target_bis_product_options" style="display:none">
			<td class="label">{t}Product{/t}:</td>
			<td class="input"> 
			<div class="buttons small left">
				<span style="float:left;margin-right:10px" id="target_bis_product_formated"></span> <button style id="target_bis_update_product" onclick="show_dialog_products_list('target_bis')">{t}Change product{/t}</button> 
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tr id="default_free_product_from_family_options" style="display:none">
			<td class="label">{t}Default Product{/t}:</td>
			<td class="input"> 
			<input type="hidden" id="default_free_product_from_family" value> 
			<div class="buttons small left">
				<span style="float:left;margin-right:10px" id="default_free_product_from_family_formated"></span> <button style id="default_free_product_from_family_update_product" onclick="show_dialog_products_list('default_free_product_from_family')">{t}Change product{/t}</button> 
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tr id="clone_deal_options" style="display:none">
			<td class="label">{t}Deal{/t}:</td>
			<td class="input"> 
			<div class="buttons small left">
				<input type="hidden" id="clone_deal_key" id=""> <span style="float:left;margin-right:10px" id="clone_deal_formated"></span> <button style id="update_clone_deal">{t}Change deal{/t}</button> 
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tr id="percentage_off_tr">
			<td class="label">{t}Discount{/t}:</td>
			<td class="input very_short"> 
			<input id="percentage_off" value ovalue> <span>%</span> 
			<div id="percentage_off_Container">
			</div>
			</td>
			<td id="percentage_off_msg" class="messages edit_td_alert"> </td>
		</tr>
		<tr id="amount_off_tr" style="display:none">
			<td class="label">{t}Discount{/t}:</td>
			<td class="input very_short"> 
			<input id="amount_off" value ovalue> <span>{$currency_symbol}</span> 
			<div id="amount_off_Container">
			</div>
			</td>
			<td id="amount_off_msg" class="messages edit_td_alert"> </td>
		</tr>
		<tr id="get_same_free_tr">
			<td class="label">{t}Get Free{/t}:</td>
			<td class="input very_short"> 
			<input id="get_same_free" value ovalue> <span>{t}outer{/t}</span> 
			<div id="get_same_free_Container">
			</div>
			</td>
			<td id="get_same_free_msg" class="messages edit_td_alert"> </td>
		</tr>
		<tr class="buttons">
			<td></td>
			<td style="text-align:right"> <span style="display:none" id="waiting"><img src='art/loading.gif' alt=''> {t}Processing Request{/t}</span> 
			<div id="form_buttons" class="buttons">
				<button id="save_new_allowance" class="positive disabled">{t}Save{/t}</button> <button style id="reset_new_allowance" onclick="window.location='{$link_back}'" class="negative">{t}Cancel{/t}</button> 
			</div>
			
			</td>
			<td id="new_deal_msg" class="messages"></td>
		</tr>
	</table>
</div>
<div id="dialog_departments_list" style="position:absolute;left:-1000;top:0">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="data_table">
			<span class="clean_table_title">{t}Department List{/t}</span> {include file='table_splinter.tpl' table_id=101 filter_name=$filter_name101 filter_value=$filter_value101} 
			<div id="table101" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_families_list" style="position:absolute;left:-1000;top:0">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="data_table">
			<span class="clean_table_title">{t}Family List{/t}</span> {include file='table_splinter.tpl' table_id=102 filter_name=$filter_name102 filter_value=$filter_value102} 
			<div id="table102" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_products_list" style="position:absolute;left:-1000;top:0">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="data_table">
			<span class="clean_table_title">{t}Products List{/t}</span> {include file='table_splinter.tpl' table_id=103 filter_name=$filter_name103 filter_value=$filter_value103} 
			<div id="table103" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_deals_list" style="position:absolute;left:-1000;top:0">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="data_table">
			<span class="clean_table_title">{t}Deal List{/t}</span> {include file='table_splinter.tpl' table_id=105 filter_name=$filter_name105 filter_value=$filter_value105} 
			<div id="table105" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'} 