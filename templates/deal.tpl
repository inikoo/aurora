{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<input id="session_data" type="hidden" value="{$session_data}"  />
	<input id="deal_key" type="hidden" value="{$deal->id}"  />
	<input id="voucher_key" type="hidden" value="{$deal->get('Deal Voucher Key')}"  />
	
	<input id="subject" type="hidden" value="deal"  />
	<input id="subject_key" type="hidden" value="{$deal->id}"  />

	
	<input type="hidden" value="{$store->id}" id="store_key" />
	<div style="padding:0 20px">
		{include file='assets_navigation.tpl'} 
		<div class="branch">
			{if $referrer=='store'} <span><a href="index.php"><img  class="home" src="art/icons/home.png" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if} <a href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; {$deal->get('Deal Name')}</span> {else}
			 <span><a href="index.php"><img  class="home" src="art/icons/home.png" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr; {/if} <a href="marketing.php?store={$store->id}">{$store->get('Store Code')} </a> &rarr; <img   src="art/icons/campaign.png" title="{t}Campaign{/t}"> <a href="campaign.php?id={$campaign->id}">{$campaign->get('Deal Campaign Name')}</a> &rarr; {$deal->get('Deal Name')}</span>
			  {/if} 
		</div>
		<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px;margin-bottom:0px">
			<div class="buttons" style="float:left">
				<span class="main_title no_buttons"><img  id="title_icon" src="art/icons/discount.png" title="{t}Offer{/t}">  {$deal->get('Deal Name')} </span>
							<span id="suspended_tag" style="color:#ba0000;font-size:90%;font-style:italic;{if  $deal->get('Deal Status')!='Suspended'}display:none{/if}">({t}Suspended{/t})</span> 
			<span id="finished_tag" style="color:#777;font-size:90%;font-style:italic;{if  $deal->get('Deal Status')!='Finish'}display:none{/if}">({t}Finished{/t})</span> 
			<span  style="font-style:italic;{if  $deal->get('Deal Mirror Key')==0}display:none{/if}">({t}Mirror of{/t}: {$deal->get_mirrow_formated_link()})</span> 

				</span> 
			</div>
			<div class="buttons small" style="position:relative;top:5px">
				{if $modify} <button onclick="window.location='edit_deal.php?id={$deal->id}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit{/t}</button>{/if} 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div style="padding:0px">
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
			<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Overview{/t}</span></span></li>
			<li style="{if !$deal->is_voucher()}display:none{/if}"> <span class="item {if $block_view=='vouchers'}selected{/if}" id="vouchers"> <span> {t}Applied Vouchers{/t}</span></span></li>

			<li> <span class="item {if $block_view=='orders'}selected{/if}" id="orders"> <span> {t}Orders{/t}</span></span></li>
			<li> <span class="item {if $block_view=='customers'}selected{/if}" id="customers"> <span> {t}Customers{/t}</span></span></li>
			<li> <span class="item {if $block_view=='email_remainder'}selected{/if}" style="{if $deal->get('Deal Terms Type')!='Order Interval'}display:none{/if}" id="email_remainder"> <span> {t}Email Remainder{/t}</span></span></li>
		</ul>
		<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
		</div>
	</div>
	<div style="padding:0 20px">
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		
		
				<div class="right_block info_boxes" style="width:450px">
				<div class="box">
					{t}Customers{/t} 
					<div style="">
						{$deal->get('Used Customers')} 
					</div>
				</div>
				<div class="box"  >
					{t}Orders{/t} 
					<div >
						{$deal->get('Used Orders')}
						<br/><span>{$deal->get_percentage_orders()}</span> 
					</div>
				</div>
				{if $deal->is_voucher()}
				<div class="box" >
					{t}Applied Vouchers{/t} 
					<div >
						{$deal->get_applied_vouchers()} 
						<br/><span>{$deal->get_percentage_applied_vouchers()}</span>
					
					</div>
					
					
				</div>
				{/if}
				
			</div>		
		
			<div class="left_block" >
			<div id="show_dialog_edit_badge">
				{$deal->get_badge()} 
			</div>
			<p  style="{if {$deal->get('Deal Description')}==''}display:none{/if}">
				{$deal->get('Deal Description')} 
			</p>
			
			</div>
			<div class="left_block" style="clear:left">
				
				<table border="0" class="show_info_product">
				<tr>
						<td style="width:150px">{t}Status{/t}:</td>
						<td class="aright">{$deal->get_formated_status()}</td>
					</tr>
					<tr>
						<td style="width:150px">{t}Validity{/t}:</td>
						<td class="aright">{$deal->get('Duration')}</td>
					</tr>
					<tr>
						<td style="width:150px">{t}Terms{/t}:</td>
						<td class="aright">{$deal->get_formated_terms()}</td>
					</tr>
					<tr>
						<td style="width:150px">{t}Terms label{/t}:</td>
						<td class="aright">{$deal->get('Deal XHTML Terms Description Label')}</td>
					</tr>
				</table>
				
			</div>
		
			
			
			<div style="clear:both;padding-top:20px">
			<span class="clean_table_title" style="margin-right:5px">{t}Allowances{/t}</span>
		<div class="buttons small left" style="{if  $deal->get('Deal Status')=='Finish' or  $deal->data['Deal Mirror Key']>0 }display:none{/if}" >
				<button id="new_deal_component" onclick="new_deal_component()" class="positive"><img src="art/icons/add.png"> {t}New{/t}</button> 
			</div> 
			<div class="elements_chooser">
													<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $deal_component_status_elements.Waiting}selected{/if} " id="deal_component_status_elements_Waiting" table_type="Waiting">{t}Waiting{/t} (<span id="deal_component_status_elements_Waiting_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $deal_component_status_elements.Suspended}selected{/if} " id="deal_component_status_elements_Suspended" table_type="Suspended">{t}Suspended{/t} (<span id="deal_component_status_elements_Suspended_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $deal_component_status_elements.Finish}selected{/if} " id="deal_component_status_elements_Finish" table_type="Finish">{t}Finished{/t} (<span id="deal_component_status_elements_Finish_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $deal_component_status_elements.Active}selected{/if} " id="deal_component_status_elements_Active" table_type="Active">{t}Active{/t} (<span id="deal_component_status_elements_Active_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 

						
						</div>
			
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
			<div id="table2" class="data_table_container dtable btable" style="font-size:85%">
			</div>
			
			</div>
		</div>
		<div id="block_customers" style="{if $block_view!='customers'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span id="table_title" class="clean_table_title">{t}Customers{/t}</span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1} 
			<div id="table1" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_orders" style="{if $block_view!='orders'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span id="table_title" class="clean_table_title">{t}Orders{/t}</span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table0" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_vouchers" style="{if $block_view!='vouchers'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span id="table_title" class="clean_table_title">{t}Orders with applied voucher{/t}</span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3} 
			<div id="table3" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_email_remainder" style="{if $block_view!='email_remainder'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			{if $deal->get('Deal Remainder Email Campaign Key')} {if $deal->remainder_email_campaign->get('Email Campaign Status')=='Creating'} 
			<table class="edit" style="clear:both;width:100%;margin-top:10px" border="0">
				{include file='build_email_splinter.tpl' email_campaign=$deal->remainder_email_campaign} 
			</table>
			{/if} {else} 
			<div id="show_create_email_remainder_container" class="buttons left " style="padding:10px;">
				<button id="show_create_email_remainder">{t}Create Email Reminder{/t}</button> 
			</div>
			<span id="new_email_campaign_msg_tr"> </span> 
		</div>
		{/if} 
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
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},1)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},2)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu3" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu3 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},3)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu3" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu3 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',3)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>

<div id="dialog_new_email_campaign" style="padding:20px 10px 20px 10px;width:400px">
	<table class="edit" border="0" style="margin:0px auto">
		<tr>
			<td colspan="2">{t}Type of email{/t}:</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<input id="email_campaign_type" value="select_html_from_template_email" type="hidden" />
			<div class="buttons" id="email_campaign_type_buttons">
				<button id="select_text_email" class="email_campaign_type"><img src="art/icons/script.png" alt="" /> {t}Text Email{/t}</button> <button id="select_html_from_template_email" class="email_campaign_type selected"><img src="art/icons/layout.png" alt="" /> {t}Template Email{/t}</button> <button id="select_html_email" class="email_campaign_type"><img src="art/icons/html.png" alt="" /> {t}HTML Email{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td class="label">{t}Name{/t}:</td>
			<td> 
			<input style="width:100%" id="email_campaign_name" value="{$deal->get('Deal Name')}" />
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button id="save_new_email_campaign" class="positive">{t}Save{/t}</button> <button id="cancel_new_email_campaign" class="negative">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
		<tr id="new_email_campaign_msg_tr" style="display:none">
			<td colspan="2" class="error" id="new_email_campaign_msg"></td>
		</tr>
	</table>
</div>

<div id="dialog_edit_deal_badge" style="padding:20px 10px 20px 10px;width:500px">
	<table class="edit" border="0" style="width:500px">
		
	
		<tr>
			<td class="label">{t}Title{/t}:</td>
			<td style="width:200px"> 
			<input style="width:100%" id="badge_name" onKeyup="badge_changed()"  value="{$deal->get('Deal Label')}" />
			</td>
			<td rowspan=3>
			<div class="offer"><div class="name" id="badge_name_display_bis">{$deal->get('Deal Label')}</div><div id="badge_allowances_display_bis" class="allowances">{$deal->get_allowances_label()}</div> <div id="badge_terms_display_bis" class="terms">{$deal->get('Deal XHTML Terms Description Label')}</div></div>
			</td>
		</tr>
		
		<tr>
			<td class="label">{t}Terms{/t}:</td>
			<td> 
			<input style="width:100%" id="badge_terms"  onKeyup="badge_changed()"  value="{$deal->get('Deal XHTML Terms Description Label')}" />
			</td>
		</tr>
		
		<tr style="{if $deal->get_number_no_finished_components()>1}display:none{/if}">
			<td class="label">{t}Allowances{/t}:</td>
			<td> 
			<input style="width:100%" id="badge_allowances" onKeyup="badge_changed()" value="{$deal->get_allowances_label()}" />
			</td>
		</tr>
		
		<tr>
			<td colspan="2"> 
			<div class="buttons small">
				<button onClick="save_badge()" id="save_badge" class="positive">{t}Save{/t}</button> <button onClick="cancel_badge()" id="cancel_badge" class="negative">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
		
	</table>
</div>


{if $deal->get('Deal Remainder Email Campaign Key')} {include file='build_email_dialogs.tpl' email_campaign=$deal->remainder_email_campaign} {/if} {include file='footer.tpl'} 