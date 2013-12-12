{include file='header.tpl'} 
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
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1} 
				<div id="table0" class="data_table_container dtable btable with_total">
				</div>
			</div>
		</div>
		<div id="block_campaigns" style="{if $block_view!='campaigns'}display:none{/if}">
			<div class="data_table" style="clear:both;margin-top:15px">
				<span class="clean_table_title">{t}Campaigns{/t}</span> 
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=11 filter_name=$filter_name11 filter_value=$filter_value11 no_filter=0} 
				<div id="table11" class="data_table_container dtable btable" style="font-size:90%">
				</div>
			</div>
		</div>
		<div id="block_offers" style="{if $block_view!='offers'}display:none{/if}">
			<div class="data_table" style="clear:both;margin-top:15px">
				<span class="clean_table_title">{t}Offers{/t}</span> 
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=10 filter_name=$filter_name10 filter_value=$filter_value10 no_filter=0} 
				<div id="table10" class="data_table_container dtable btable" style="font-size:90%">
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
{include file='footer.tpl'} 