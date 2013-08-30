{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<div style="padding:0 20px">
		<input type="hidden" value="{$store->id}" id="store_key" />
		{include file='marketing_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr; {/if} {$store->get('Store Code')} {t}Marketing{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				<button onclick="window.location='store_deals.php?store={$store->id}'"><img src="art/icons/money.png" alt=""> {t}Offers{/t}</button> {if $modify and false} <button id="new_reminder"><img src="art/icons/add.png" alt=""> {t}Remainder{/t}</button> <button id="new_media_campaign"><img src="art/icons/add.png" alt=""> {t}Media{/t}</button> <button id="new_post_campaign"><img src="art/icons/add.png" alt=""> {t}Post{/t}</button> <button id="new_email_campaign"><img src="art/icons/add.png" alt=""> {t}Mailshot{/t}</button> <button id="new_newsletter"><img src="art/icons/add.png" alt=""> {t}Newsletter{/t}</button> <button id="new_campaign"><img src="art/icons/add.png" alt=""> {t}Campaign{/t}</button> {/if} 
		
		
		
		</div>
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Marketing{/t} <span class="id">{$store->get('Store Name')}</span></span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> <span class="item {if $view=='metrics'}selected{/if}" id="metrics"><span> {t}Overview{/t}</span></span></li>
		<li> <span class="item {if $view=='campaigns'}selected{/if}" id="campaigns"> <span> {t}Campaigns{/t}</span></span></li>
		<li> <span class="item {if $view=='newsletter'}selected{/if}" id="newsletter"> <span> {t}eNewsletters{/t}</span></span></li>
		<li> <span class="item {if $view=='email'}selected{/if}" id="email"> <span> {t}Mailshots{/t}</span></span></li>
		<li> <span class="item {if $view=='post'}selected{/if}" id="post"> <span> {t}Marketing Post{/t}</span></span></li>
		<li> <span class="item {if $view=='media'}selected{/if}" id="media"> <span> {t}Marketing Media{/t}</span></span></li>
		<li> <span class="item {if $view=='follow'}selected{/if}" id="follow"> <span> {t}Remainders{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_metrics" style="{if $view!='metrics'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<h2>
			{t}Marketing Metrics{/t} 
		</h2>
		<div class="buttons small left">
		<button class="nav2 onleft"><a href="#">{t}Create List{/t}</a></button>
<button class="nav2 onleft"><a href="customers_lists.php">{t}View List{/t}</a></button>
<button class="nav2 onleft"><a href="new_campaign.php">{t}Create Campaign{/t}</a></button>
<button class="nav2 onleft"><a href="campaign_builder.php">{t}View Campaign{/t}</a></button>
</div>
	</div>
	<div id="block_campaigns" style="{if $view!='campaigns'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="block_newsletter" style="{if $view!='newsletter'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="block_email" style="{if $view!='email'}display:none;{/if}clear:both;margin:5px 0 40px 0;padding:0 20px">
		<div class="buttons">
			<button id="new_email_campaign2" class="positive">{t}New Email Campaign{/t}</button> 
		</div>
		<span class="clean_table_title" style="clear:both">{t}Email Campaigns{/t}</span> 
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0 } 
		<div id="table0" class="data_table_container dtable btable">
		</div>
	</div>
	<div id="block_post" style="{if $view!='post'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="block_media" style="{if $view!='media'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="block_follow" style="{if $view!='follow'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<h2>
			{t}Follow-up's Emails{/t} 
		</h2>
	</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
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
			<input style="width:100%" id="email_campaign_name" value="" />
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

{include file='footer.tpl'} 