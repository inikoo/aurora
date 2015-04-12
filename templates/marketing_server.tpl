{include file='header.tpl'} 
	<input type="hidden" value="{$session_data}" id="session_data" />

		<input type="hidden" id="subject" value="stores"> 
		<input type="hidden" id="subject_key" value="">
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; &#8704; {t}Marketing{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				<button style="visibility:hidden" onclick="window.location='marketing_server_stats.php'"><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button> 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Marketing Overview{/t}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> <span class="item {if $block_view=='stores'}selected{/if}" id="stores"> <span> {t}Stores{/t}</span></span></li>
		<li> <span class="item {if $block_view=='campaigns'}selected{/if}" id="campaigns"> <span> {t}Campaigns{/t}</span></span></li>
		<li> <span class="item {if $block_view=='offers'}selected{/if}" id="offers"> <span> {t}Offers{/t}</span></span></li>
	</ul>
	<div class="tabs_base">
	</div>
	<div style="padding:0 20px">
		<div id="block_stores" style="{if $block_view!='stores'}display:none{/if}">
			<div class="data_table" style="clear:both;margin-top:15px">
				<span class="clean_table_title">{t}Store Marketing Sections{/t}</span> 
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0} 
				<div id="table0" class="data_table_container dtable btable with_total">
				</div>
			</div>
		</div>
		<div id="block_campaigns" style="{if $block_view!='campaigns'}display:none{/if}">
			<div class="data_table" style="clear:both;margin-top:15px">
				<span class="clean_table_title">{t}Campaigns{/t}</span> 
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 no_filter=0} 
				<div id="table2" class="data_table_container dtable btable" style="font-size:90%">
				</div>
			</div>
		</div>
		<div id="block_offers" style="{if $block_view!='offers'}display:none{/if}">
			<div class="data_table" style="clear:both;margin-top:15px">
				<span class="clean_table_title">{t}Offers{/t}</span> 
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 no_filter=0} 
				<div id="table1" class="data_table_container dtable btable" style="font-size:90%">
				</div>
			</div>
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

<div id="filtermenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:11px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:11px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},1)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>

<div id="filtermenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:21px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:21px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},2)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='footer.tpl'} 