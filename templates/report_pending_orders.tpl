{include file='header.tpl'} 
<div id="bd" class="no_padding">
		<input type="hidden" id="to" value="{$to}" />
		<input type="hidden" id="from" value="{$from}" />
		<input type="hidden" id="calendar_id" value="{$calendar_id}" />
		<input type="hidden" id="subject" value="report_pending_orders" />
		<input type="hidden" id="subject_key" value="" />
	<div style="padding:0 20px">
		<div class="branch" style="width:280px;float:left;margin:0">
			
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; {t}Pending Orders{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				<button style="display:none" onclick="window.location='customers_server_stats.php'"><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button> 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title no_buttons">{t}Pending Orders{/t}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div style="padding:0px">
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
			<li> <span class="item {if $block_view=='stores'}selected{/if}" id="stores"> <span> {t}Stores{/t}</span></span></li>
		</ul>
		<div class="tabs_base">
	</div>
	<div id="calendar_container" style="padding:0 20px;padding-bottom:0px;{if $block_view!='stores'}display:none{/if}">
			<div id="period_label_container" style="{if $period==''}display:none{/if}">
				<img src="art/icons/clock_16.png"> <span id="period_label">{$period_label}</span> 
			</div>
			{include file='calendar_splinter.tpl' } 
			<div style="clear:both">
			</div>
		</div>
	
	
	</div>
	<div style="padding:0 20px">
		<div id="block_stores" style="clear:both;margin:10px 0 40px 0;">
			<span class="clean_table_title">{t}Pending orders per store{/t} </span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1} 
			<div id="table0" class="data_table_container dtable btable with_total" style="font-size:90%">
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
{include file='footer.tpl'} 