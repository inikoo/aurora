{include file='header.tpl'} 
<input type="hidden" value="{$session_data}" id="session_data" />
<input type="hidden" value="{$store->id}" id="store_key" />
<input type="hidden" value="{$scope_subject}" id="scope_subject" />
<input type="hidden" value="{$post_create_action}" id="post_create_action" />
<div id="bd">
	{if $scope_subject=='Campaign'}{include file='marketing_navigation.tpl'}{else if $scope_subject=='Customer'}{include file='contacts_navigation.tpl'} {else}{include file='assets_navigation.tpl'}{/if} 
	<div class="branch">
		{if $scope_subject=='Campaign'} <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr; {/if} <a href="marketing.php?store={$store->id}" title="{$campaign->get('Deal Campaign Name')}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr; <a href="campaign.php?id={$campaign->id}" title="{$campaign->get('Deal Campaign Name')}">{$campaign->get('Deal Campaign Code')}</a> ({t}New Offer{/t})</span> {elseif $scope_subject=='Family'} <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}" title="{$store->get('Store Name')}">{$store->get('Store Code')}</a> &rarr; <a id="department_branch_link" href="department.php?id={$department->id}" title="{$department->get('Product Department Name')}">{$department->get('Product Department Code')}</a> &rarr; <a href="family.php?id={$family->id}" title="{$family->get('Product Family Name')}">{$family->get('Product Family Code')}</a> ({t}New offer{/t})</span> {elseif $scope_subject=='Department'} <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}" title="{$store->get('Store Name')}">{$store->get('Store Code')}</a> &rarr; <a id="department_branch_link" href="department.php?id={$department->id}" title="{$department->get('Product Department Name')}">{$department->get('Product Department Code')}</a> ({t}New offer{/t})</span> {elseif $scope_subject=='Store'} <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="stores.php"> &#8704; {t}Stores{/t}</a> &rarr; <a href="store.php?id={$store->id}" title="{$store->get('Store Name')}">{$store->get('Store Code')}</a> ({t}New offer{/t})</span> {elseif $scope_subject=='Customer'} <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{t}Customers{/t} ({$store->get('Store Code')})</a> &rarr; <a href="customer.php?id={$customer->id}">{$customer->get_formated_id()}</a> ({t}New offer{/t})</span> {elseif $scope_subject=='Product'} <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Code')}</a> &rarr; <a href="department.php?id={$product->get('Product Main Department Key')}">{$product->get('Product Main Department Code')}</a> &rarr; <a href="family.php?id={$product->get('Product Family Key')}">{$product->get('Product Family Code')}</a> &rarr; <a href="product.php?pid={$product->pid}" title="{$product->get('Product Name')}">{$product->get('Product Code')}</a> ({t}New offer{/t})</span> {/if} 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			{if $scope_subject=='Store'} <span class="main_title">{t}New Offer{/t} @ {t}Store{/t} <span class="id">{$store->get('Store Code')}</span></span> {else if $scope_subject=='Department'} <span class="main_title">{t}New Offer{/t} @ {t}Department{/t} <span class="id">{$department->get('Product Department Code')}</span></span> {else if $scope_subject=='Family'} <span class="main_title">{t}New Offer{/t} @ {t}Family{/t} <span class="id">{$family->get('Product Family Code')}</span></span> {else if $scope_subject=='Product'} <span class="main_title">{t}New Offer{/t} @ {t}Product{/t} <span class="id">{$product->get('Product Code')}</span></span> {else if $scope_subject=='Customer'} <span class="main_title">{t}New Offer{/t} @ {t}Customer{/t} <span class="id">{$customer->get('Customer Name')}</span></span> {else if $scope_subject=='Campaign'} <span class="main_title">{t}New Offer{/t} @ {t}Campaign{/t} <span class="id">{$campaign->get('Deal Campaign Code')}</span></span> {else} <span class="main_title">{t}New Offer{/t}</span> {/if} 
		</div>
		<div class="buttons">
			<button class="negative" onclick="window.location='{$link_back}'">{t}Cancel{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<table id="new_deal" class="edit" border="0">
		<tbody id="trigger_options" style="{if $scope_subject!='Campaign'}display:none{/if}">
			<tr class="title">
				<td colspan="3">Trigger</td>
			</tr>
			<tr class="space10" style="{if $scope_subject!='Campaign'}display:none{/if}">
				<td class="label" style>{t}Trigger{/t}:</td>
				<td class="input" style="text-align:left;"> 
				<input type="hidden" id="trigger" value="{$trigger}" />
				<input type="hidden" id="trigger_key" value="{$trigger_key}" />
				<div class="styled-select">
					<select id="tigger" onchange="trigger_changed(this.value)">
						<option value="Order">{t}Order{/t}</option>
						<option value="Department">{t}Department{/t}</option>
						<option value="Family">{t}Family{/t}</option>
						<option value="Product">{t}Product{/t}</option>
						<option value="Customer">{t}Customer{/t}</option>
					</select>
				</div>
				</td>
				<td class="messages"></td>
			</tr>
			<tr id="trigger_department_options" style="display:none">
				<td class="label">{t}Department{/t}:</td>
				<td class="input"> 
				<div class="buttons small left">
					<span style="float:left;margin-right:10px" id="department_formated"></span> <button style id="update_department">{t}Change department{/t}</button> 
				</div>
				</td>
				<td class="messages"></td>
			</tr>
			<tr id="trigger_family_options" style="display:none">
				<td class="label">{t}Family{/t}:</td>
				<td class="input"> 
				<div class="buttons small left">
					<span style="float:left;margin-right:10px" id="family_formated"></span> <button style id="update_family">{t}Change family{/t}</button> 
				</div>
				</td>
				<td class="messages"></td>
			</tr>
			<tr id="trigger_product_options" style="display:none">
				<td class="label">{t}Product{/t}:</td>
				<td class="input"> 
				<div class="buttons small left">
					<span style="float:left;margin-right:10px" id="product_formated"></span> <button style id="update_product">{t}Change product{/t}</button> 
				</div>
				</td>
				<td class="messages"></td>
			</tr>
			<tr id="trigger_customer_options" style="display:none">
				<td class="label">{t}Customer{/t}:</td>
				<td class="input"> 
				<div class="buttons small left">
					<span style="float:left;margin-right:10px" id="customer_formated"></span> <button style id="update_customer">{t}Change customer{/t}</button> 
				</div>
				</td>
				<td class="messages"></td>
			</tr>
		</tbody>
		<tbody id="campaign_options" style="{if $scope_subject=='Campaign'}display:none{/if}">
			<tr class="title">
				<td colspan="2">Campaign</td>
			</tr>
			<tr class="space10" style="{if $scope_subject=='Campaign'}display:none{/if}">
				<td class="label">{t}Campaign{/t}:</td>
				<td class="input"> 
				<input type="hidden" id="campaign_key" value="{if $scope_subject=='Campaign'}{$campaign->id}{/if}" ovalue />
				<div class="buttons small left">
					<span style="float:left;margin-right:10px" id="campaign_formated"></span> <button style="display:none" id="update_campaign">{t}Change campaign{/t}</button> <button style="margin-bottom:2px" id="select_campaign" alt_label="{t}Change campaign{/t}" label="{t}Select campaign{/t}">{t}Select campaign{/t}</button> <button style id="new_campaign">{t}New campaign{/t}</button> 
				</div>
				</td>
				<td class="messages"></td>
			</tr>
		</tbody>
		<tbody id="new_campaign_fields" style="display:none">
			<tr>
				<td class="label" style>{t}Code{/t}:</td>
				<td class="input"> 
				<div>
					<input class="short" id="campaign_code" value="" ovalue=""> 
					<div id="campaign_code_Container">
					</div>
				</div>
				</td>
				<td id="campaign_code_msg" class="messages edit_td_alert"> </td>
			</tr>
			<tr>
				<td class="label">{t}Name{/t}:</td>
				<td class="input"> 
				<div>
					<input id="campaign_name" value="" ovalue=""> 
					<div id="campaign_name_Container">
					</div>
				</div>
				</td>
				<td id="campaign_name_msg" class="messages edit_td_alert"> </td>
			</tr>
			<tr id="campaign_description_tr">
				<td class="label">{t}Description{/t}:</td>
				<td class="input"> 
				<div>
					<textarea id="campaign_description" value="" ovalue=""></textarea>
					<div id="campaign_description_Container">
					</div>
				</div>
				</td>
				<td id="campaign_description_msg" class="messages edit_td_alert"> </td>
			</tr>
			<tr>
				<td class="label">{t}Start{/t}:</td>
				<td style="text-align:left;"> 
				<div class="buttons small left">
					<input id="v_calpop1" style="text-align:right;float:left" class="text" type="text" size="10" maxlength="10" value="" ovalue="" />
					<div id="campaign_from_Container" style="position:absolute;display:none; z-index:2">
					</div>
					<img id="calpop1" style="height:16px;width:16px;float:left;margin-left:4px;cursor:pointer;text-align:right;position:relative;bottom:1px;z-index:0;margin-right:20px" src="art/icons/calendar_view_month.png" align="top" alt="" /> <button id="start_now">{t}Start now{/t}</button> 
				</div>
				</td>
				<td id="campaign_from_msg" class="messages edit_td_alert"> </td>
			</tr>
			<tr>
				<td class="label">{t}Until{/t}:</td>
				<td style="text-align:left;"> 
				<div class="buttons small left">
					<input id="v_calpop2" style="text-align:right;float:left" class="text" type="text" size="10" maxlength="10" value="" ovalue="" />
					<div id="campaign_to_Container" style="position:absolute;display:none; z-index:2">
					</div>
					<img id="calpop2" style="height:16px;width:16px;float:left;margin-left:4px;cursor:pointer;text-align:right;position:relative;bottom:1px;z-index:0;margin-right:20px" src="art/icons/calendar_view_month.png" align="top" alt="" /> <button id="to_permanent">{t}Permanent{/t}</button> 
				</div>
				</td>
				<td id="campaign_to_msg" class="messages edit_td_alert"> </td>
			</tr>
		</tbody>
		<tr class="title space20">
			<td colspan="2">{t}Terms & Allowances{/t}</td>
		</tr>
		<tr class="space10">
			<td class="label" style>{t}Terms{/t}:</td>
			<td class="input"> 
			<div class="styled-select">
				<input type="hidden" id="terms" value> 
				<input type="hidden" id="target" value="{$target}"> 
				<input type="hidden" id="target_key" value="{$target_key}"> 
				<select id="department_terms_select" onchange="terms_changed(this.value)" style="{if $trigger!='Department'}display:none{/if}">
					<option value="Department Quantity Ordered">{t}Order more than{/t}</option>
					<option value="Department For Every Quantity Ordered">{t}For every{/t}</option>
				</select>
				<select id="family_terms_select" onchange="terms_changed(this.value)" style="{if $trigger!='Family'}display:none{/if}">
					<option value="Family Quantity Ordered">{t}Order more than{/t}</option>
					<option value="Family For Every Quantity Ordered">{t}For every{/t}</option>
				</select>
				<select id="product_terms_select" onchange="terms_changed(this.value)" style="{if $trigger!='Product'}display:none{/if}">
					<option value="Product Quantity Ordered">{t}Order more than{/t}</option>
					<option value="Product For Every Quantity Ordered">{t}For every{/t}</option>
				</select>
				<select id="customer_terms_select" onchange="terms_changed(this.value)" style="{if $trigger!='Customer'}display:none{/if}">
					<option value="Voucher">{t}Voucher{/t}</option>
					<option value="Amount">{t}Amount{/t}</option>
					<option value="Every Order">{t}Every Order{/t}</option>
					<option value="Next Order">{t}Next Order{/t}</option>
					<option value="Customer Department Quantity Ordered">{t}Order more than{/t} ({t}Department{/t})</option>
					<option value="Customer Family Quantity Ordered">{t}Order more than{/t} ({t}Family{/t})</option>
					<option value="Customer Product Quantity Ordered">{t}Order more than{/t} ({t}Product{/t})</option>
				</select>
				<select id="customer_terms_select" onchange="terms_changed(this.value)">
					<option value="Voucher">{t}Voucher{/t}</option>
					<option value="Amount">{t}Amount{/t}</option>
					<option value="Order Number">{t}Nth Order{/t}</option>
					<option value="Order Interval">{t}Last order placed (Days){/t}</option>
					<option value="Voucher AND Amount">{t}Voucher & Amount{/t}</option>
					<option value="Voucher AND Order Number">{t}Voucher & Nth Order{/t}</option>
					<option value="Voucher AND Order Interval">{t}Voucher & Last order placed (Days){/t}</option>
					<option value="Amount AND Order Number">{t}Amount & Nth Order{/t}</option>
					<option value="Amount AND Order Interval">{t}Amount & Last order placed (Days){/t}</option>
				</select>
			</div>
			</td>
			<td id="deal_name_msg" class="messages edit_td_alert"> </td>
		</tr>
		<tr id="target_department_options" style="display:none">
			<td class="label">{t}Department{/t}:</td>
			<td class="input"> 
			<div class="buttons small left">
				<span style="float:left;margin-right:10px" id="target_department_formated"></span> <button style id="target_update_department">{t}Change department{/t}</button> 
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tr id="target_family_options" style="display:none">
			<td class="label">{t}Family{/t}:</td>
			<td class="input"> 
			<div class="buttons small left">
				<span style="float:left;margin-right:10px" id="target_family_formated"></span> <button style id="target_update_family">{t}Change family{/t}</button> 
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tr id="target_product_options" style="display:none">
			<td class="label">{t}Product{/t}:</td>
			<td class="input"> 
			<div class="buttons small left">
				<span style="float:left;margin-right:10px" id="target_product_formated"></span> <button style id="target_update_product">{t}Change product{/t}</button> 
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tbody id="voucher_options">
			<tr>
				<td class="label">{t}Voucher code type{/t}:</td>
				<td class="input"> 
				<input type="hidden" id="voucher_code_type" value="Random"> 
				<div class="buttons small left">
					<button id="voucher_code_random" class="selected">{t}Generate Random Code{/t}</button> <button id="voucher_code_custome">{t}Custome Code{/t}</button> 
				</div>
				</td>
				<td class="messages"></td>
			</tr>
			<tr id="voucher_code_tr" style="display: none">
				<td class="label">{t}Voucher Code{/t}:</td>
				<td class="input very_short"> 
					<input id="voucher_code" value="" ovalue=""> 
					<div id="voucher_code_Container">
					</div>
				</td>
				<td id="voucher_code_msg" class="messages edit_td_alert">
				</td>
			</tr>
			<tr id="voucher_tr" style="{if $scope_subject=='Customer'}display:none{/if}">
				<td class="label">{t}Voucher type{/t}:</td>
				<td class="input"> 
				<input type="hidden" id="voucher_type" value="Public"> 
				<div class="buttons small left">
					<button id="voucher_type_public" class="selected" title="{t}One per customer{/t}">{t}Public{/t}</button> <button id="voucher_type_private" title="{t}Only applicable in the backend{/t}">{t}Private{/t}</button> 
				</div>
				</td>
			</tr>
		</tbody>
		<tbody id="amount_options" style="display:none">
			<tr id="amount_tr">
				<td class="label">{t}If amount more than{/t}:</td>
				<td class="input very_short"> 
					<input id="amount" value="" ovalue=""> <span>{$currency_symbol}</span>
					<div id="amount_Container">
					</div>
				</td>
				<td id="amount_msg" class="messages edit_td_alert">
				</td>
			</tr>
			<tr>
				<td class="label">{t}Amount type{/t}</td>
				<td class="input"> 
				<input type="hidden" id="amount_type" value='Order Total Amount'> 
				<div class="buttons small left">
					<button id="amount_type_total" >{t}Total{/t}</button> <button id="amount_type_net" class="selected">{t}Total Net{/t}</button> <button id="amount_type_items">{t}Items Net{/t}</button> 
				</div>
				</td>
				<td class="messages"></td>
			</tr>
		</tbody>
		<tr id="if_order_more_tr" style="display:none">
			<td class="label">{t}If order more than{/t}:</td>
			<td class="input very_short"> 
				<input id="if_order_more" value="" ovalue=""> <span>{$currency_symbol}</span>
				<div id="if_order_more_Container">
				</div>
			
			</td>
			<td id="if_order_more_msg" class="messages edit_td_alert">
			</td>
		</tr>
		<tr id="for_every_ordered_tr" style="display:none">
			<td class="label">{t}For every{/t}:</td>
			<td class="input very_short"> 
				<input id="for_every_ordered" value="" ovalue=""> 
				<div id="for_every_ordered_Container">
				</div>
			</td>
			<td id="for_every_ordered_msg" class="messages edit_td_alert">
			</td>
		</tr>
		<tr id="order_interval_tr" style="display:none">
			<td class="label">{t}Last order placed{/t}:</td>
			<td class="input very_short"> 
				<input id="order_interval" value="" ovalue=""> <span>{t}days{/t}</span>
				<div id="order_interval_Container">
				</div>
			</td>
			<td id="order_interval_msg" class="messages edit_td_alert">
			</td>
		</tr>
		<tr id="order_number_tr" style="display:none">
			<td class="label">{t}Nth Order{/t}:</td>
			<td class="input very_short"> 
				<input id="order_number" value="" ovalue=""> 
				<div id="order_number_Container">
				</div>
			</td>
			<td id="order_number_msg" class="messages edit_td_alert">
			</td>

		</tr>
		<tr class="space15">
			<td class="label" style>{t}Allowances{/t}:</td>
			<td class="input"> 
			<input type="hidden" id="allowances" value> 
			<div class="styled-select" id="allowances_select">
				<select id="order_more_than_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
				</select>
				<select id="for_every_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Get Same Free">{t}Get free (same product){/t}</option>
				</select>
				<select id="every_order_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
					<option value="Amount Off">{t}Amount off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}products{/t})</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Clone other deal allowances{/t}</option>
				</select>
				<select id="next_order_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
					<option value="Amount Off">{t}Amount off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}Department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}Family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}Products{/t})</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Clone other deal allowances{/t}</option>
				</select>
				<select id="voucher_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}Department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}Family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}Products{/t})</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Clone other deal allowances{/t}</option>
				</select>
				<select id="amount_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
					<option value="Amount Off">{t}Amount off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}Department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}Family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}Products{/t})</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Clone other deal allowances{/t}</option>
				</select>
				<select id="order_interval_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage off{/t}</option>
					<option value="Amount Off">{t}Amount off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}Department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}Family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}Products{/t})</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Clone other deal allowances{/t}</option>
				</select>
				<select id="order_number_allowances_select" onchange="allowances_changed(this.value)" style="display:none">
					<option value="Percentage Off">{t}Percentage Off{/t}</option>
					<option value="Amount Off">{t}Amount off{/t}</option>
					<option value="Department Percentage Off">{t}Percentage off{/t} ({t}Department{/t})</option>
					<option value="Family Percentage Off">{t}Percentage off{/t} ({t}Family{/t})</option>
					<option value="Product Percentage Off">{t}Percentage off{/t} ({t}Products{/t})</option>
					<option value="Free Shipping">{t}Free Shipping{/t}</option>
					<option value="Free Charges">{t}Free Charges{/t}</option>
					<option value="Clone">{t}Clone other deal allowances{/t}</option>
				</select>
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tr id="target_bis_department_options" style="display:none">
			<td class="label">{t}Department{/t}:</td>
			<td class="input"> 
			<div class="buttons small left">
				<span style="float:left;margin-right:10px" id="target_bis_department_formated"></span> <button style id="target_bis_update_department">{t}Change department{/t}</button> 
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tr id="target_bis_family_options" style="display:none">
			<td class="label">{t}Family{/t}:</td>
			<td class="input"> 
			<div class="buttons small left">
				<span style="float:left;margin-right:10px" id="target_bis_family_formated"></span> <button style id="target_bis_update_family">{t}Change family{/t}</button> 
			</div>
			</td>
			<td class="messages"></td>
		</tr>
		<tr id="target_bis_product_options" style="display:none">
			<td class="label">{t}Product{/t}:</td>
			<td class="input"> 
			<div class="buttons small left">
				<span style="float:left;margin-right:10px" id="target_bis_product_formated"></span> <button style id="target_bis_update_product">{t}Change product{/t}</button> 
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
				<input id="percentage_off" value="" ovalue=""> <span>%</span>
				<div id="percentage_off_Container">
				</div>
			</td>
			<td id="percentage_off_msg" class="messages edit_td_alert">
			</td>

		</tr>
		<tr id="amount_off_tr" style="display:none">
			<td class="label">{t}Discount{/t}:</td>
			<td class="input very_short"> 
				<input id="amount_off" value="" ovalue=""> <span>{$currency_symbol}</span>
				<div id="amount_off_Container">
				</div>
			</td>
			<td id="amount_off_msg" class="messages edit_td_alert">
			</td>
		</tr>
		<tr id="get_same_free_tr">
			<td class="label">{t}Get Free{/t}:</td>
			<td class="input very_short"> 
				<input id="get_same_free" value="" ovalue=""> 
				<div id="get_same_free_Container">
				</div>
			</td>
			<td id="get_same_free_msg" class="messages edit_td_alert">
			</td>
	
		</tr>
		<tr class="title space20">
			<td colspan="2">Description</td>
		</tr>
		<tr class="space10">
			<td class="label ">{t}Code{/t}:</td>
			<td class="input"> 
			<div>
				<input class="short" id="deal_code" value="" ovalue=""> 
				<div id="deal_code_Container">
				</div>
			</div>
			</td>
			<td id="deal_code_msg" class="messages edit_td_alert">
			</td>
		</tr>
		<tr>
			<td class="label">{t}Name{/t}:</td>
			<td class="input"> 
			<div>
				<input id="deal_name" value="" ovalue=""> 
				<div id="deal_name_Container">
				</div>
			</div>
			</td>
			<td id="deal_name_msg" class="messages edit_td_alert">
			</td>
		</tr>
		<tr id="deal_description_tr">
			<td class="label">{t}Description{/t}:</td>
			<td class="input"> 
			<div>
				<textarea  id="deal_description" value="" ovalue=""></textarea>
				<div id="deal_description_Container">
				</div>
			</div>
			</td>
			<td id="deal_description_msg" class="messages edit_td_alert">
			</td>
			
		</tr>
		<tr class="buttons">
			<td></td>
			<td style="text-align:right"> <span style="display:none" id="waiting"><img src='art/loading.gif' alt=''> {t}Processing Request{/t}</span> 
			<div id="form_buttons" class="buttons">
				<button  id="save_new_deal" class="positive disabled">{t}Save{/t}</button> <button style id="reset_new_deal" onclick="window.location='{$link_back}'" class="negative">{t}Cancel{/t}</button> 
			</div>
			<div style="clear:both;margin-right:10px">
				<table style="float:right;margin-top:15px" border="0">
					<tr>
						<td style="text-align:right;padding:1px 5px">{t}go to new offer{/t}</td>
						<td style="padding:0px"><img id="go_to_new" style="height:12px;position:relative;top:3px;{if $post_create_action!='go_to_new'}cursor:pointe{/if}" src="art/icons/{if $post_create_action=='go_to_new'}checkbox_checked.png{else}checkbox_unchecked.png{/if}"></td>
					</tr>
					<tr>
						<td style="text-align:right;padding:1px 5px">{t}create other offer{/t}</td>
						<td style="padding:0px"><img id="create_other_deal" style="height:12px;position:relative;top:3px;{if $post_create_action=='go_to_new'}cursor:pointe{/if}" src="art/icons/{if $post_create_action!='go_to_new'}checkbox_checked.png{else}checkbox_unchecked.png{/if}"></td>
					</tr>
				</table>
			</div>
			</td>
			<td id="new_deal_msg" class="messages"></td>
		</tr>
	</table>
</div>
<div id="dialog_campaigns_list" style="position:absolute;left:-1000;top:0">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="data_table">
			<span class="clean_table_title">{t}Campaign List{/t}</span> {include file='table_splinter.tpl' table_id=100 filter_name=$filter_name100 filter_value=$filter_value100} 
			<div id="table100" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
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
<div id="dialog_customers_list" style="position:absolute;left:-1000;top:0">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="data_table">
			<span class="clean_table_title">{t}Customers List{/t}</span> {include file='table_splinter.tpl' table_id=104 filter_name=$filter_name104 filter_value=$filter_value104} 
			<div id="table104" class="data_table_container dtable btable">
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