{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<input type="hidden" id="session_data" value="{$session_data}"  />
	<input type="hidden" id="campaign_key" value="{$campaign->id}"  />
	<input type="hidden" id="store_key" value="{$store->id}"  />
	<input type="hidden" id="subject_key" value="{$campaign->id}"  />
	<input type="hidden" id="subject" value="campaign"  />
	<div style="padding:0 20px">
		{include file='marketing_navigation.tpl'} 
		<div class="branch">
			{if $referrer=='store'} <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.png" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if} <a href="store.php?id={$store->id}" title="{$store->get('Store Name')}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr; {$campaign->get('Deal Campaign Name')}</span> {else} <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr; {/if} <a href="marketing.php?store={$store->id}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr; {$campaign->get('Deal Campaign Name')}</span> {/if} 
		</div>
		<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px;margin-bottom:0px">
			<div class="buttons" style="float:left">
				<span class="main_title no_buttons"><img  id="title_icon" src="art/icons/campaign.png" title="{t}Campaign{/t}"> {$campaign->get('Deal Campaign Name')} </span> 
			</div>
			<div class="buttons small" style="position:relative;top:5px">
				{if $modify} <button onclick="window.location='edit_campaign.php?id={$campaign->id}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit{/t}</button>{/if} 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div style="padding:0px">
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
			<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Overview{/t}</span></span></li>
			<li> <span class="item {if $block_view=='deals'}selected{/if}" id="deals"> <span> {t}Offers{/t}</span></span></li>
			<li> <span class="item {if $block_view=='orders'}selected{/if}" id="orders"> <span> {t}Orders{/t}</span></span></li>
			<li> <span class="item {if $block_view=='customers'}selected{/if}" id="customers"> <span> {t}Customers{/t}</span></span></li>
		</ul>
		<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
		</div>
	</div>
	<div style="padding:0 20px">
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:0px 0px 20px 0px">
			
			
			
			<div class="left_block" >
			<h2 style="padding-top:0px">
				{$campaign->get('Deal Campaign Name')} 
			</h2>
			<p style="width:300px">
				{$campaign->get('Deal Campaign Description')} 
			</p>
			
			</div>
			
			<div class="left_block info_boxes" >
				<div class="box">
					{t}Customers{/t} 
					<div style="">
						{$campaign->get('Used Customers')} 
					</div>
				</div>
				<div class="box"  >
					{t}Orders{/t} 
					<div >
						{$campaign->get('Used Orders')} 
					</div>
				</div>
				<div style="clear:both"> 	&nbsp;</div>
			</div>
			
			<div class="left_block">
				
				<table border="0" class="show_info_product">
					<tr>
						<td style="width:150px">{t}Status{/t}:</td>
						<td class="aright">{$campaign->get_formated_status()}</td>
					</tr><tr>
						<td style="width:150px">{t}Validity{/t}:</td>
						<td class="aright">{$campaign->get('Duration')}</td>
					</tr>
					
				</table>
				
			</div>
			
			
			
			
		</div>
		<div id="block_customers" style="{if $block_view!='customers'}display:none;{/if}clear:both;margin:20px 0 40px 0">
			<span id="table_title" class="clean_table_title">{t}Customers{/t}</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1} 
			<div id="table1" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_orders" style="{if $block_view!='orders'}display:none;{/if}clear:both;margin:20px 0 40px 0">
			<span id="table_title" class="clean_table_title">{t}Orders{/t}</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table0" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_deals" style="{if $block_view!='deals'}display:none;{/if}clear:both;margin:20px 0 40px 0">
			<span class="clean_table_title" style="margin-right:5px">{t}Offers{/t}</span> 
			<div class="buttons small left">
				<button id="new_deal" onclick="new_deal()" class="positive"><img src="art/icons/add.png"> {t}New{/t}</button> 
			</div>
			<div class="elements_chooser">
						<img class="menu" id="offer_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" />
						<div id="offer_trigger_chooser" style="{if $elements_offer_elements_type!='trigger'}display:none{/if}">

							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $offer_trigger_elements.Product}selected{/if} " id="offer_trigger_elements_Product" table_type="Product">{t}Product{/t} (<span id="offer_trigger_elements_Product_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $offer_trigger_elements.Family}selected{/if} " id="offer_trigger_elements_Family" table_type="Family">{t}Family{/t} (<span id="offer_trigger_elements_Family_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $offer_trigger_elements.Department}selected{/if} " id="offer_trigger_elements_Department" table_type="Department">{t}Department{/t} (<span id="offer_trigger_elements_Department_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $offer_trigger_elements.Order}selected{/if} " id="offer_trigger_elements_Order" table_type="Order">{t}Order{/t} (<span id="offer_trigger_elements_Order_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						
						
						
							<div id="offer_status_chooser" style="{if $elements_offer_status_elements_type!='status'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $offer_status_elements.Waiting}selected{/if} " id="offer_status_elements_Waiting" table_type="Waiting">{t}Waiting{/t} (<span id="offer_status_elements_Waiting_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $offer_status_elements.Suspended}selected{/if} " id="offer_status_elements_Suspended" table_type="Suspended">{t}Suspended{/t} (<span id="offer_status_elements_Suspended_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $offer_status_elements.Finish}selected{/if} " id="offer_status_elements_Finish" table_type="Finish">{t}Finished{/t} (<span id="offer_status_elements_Finish_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
							<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $offer_status_elements.Active}selected{/if} " id="offer_status_elements_Active" table_type="Active">{t}Active{/t} (<span id="offer_status_elements_Active_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 

</div>
						
						</div>
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
			<div id="table2" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
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
{include file='footer.tpl'} 