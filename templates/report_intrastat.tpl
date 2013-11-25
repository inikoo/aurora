{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="to" value="{$to}" />
	<input type="hidden" id="from" value="{$from}" />
	<input type="hidden" id="calendar_id" value="{$calendar_id}" />
	<input type="hidden" id="subject" value="report_intrastat" />
	<input type="hidden" id="subject_key" value="" />
	<div class="branch" style="width:280px;float:left;margin:0">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; {t}Intrastat{/t} 
	</div>
	<div class="top_page_menu">
		<div class="buttons">
		</div>
		<div class="buttons">
			<span class="main_title no_buttons">{$title}</span> 
		</div>
	
	</div>
	
	<div id="calendar_container" style="padding:0 0px;padding-bottom:0px;">
			<div id="period_label_container" style="{if $period==''}display:none{/if}">
				<img src="art/icons/clock_16.png"> <span id="period_label">{$period_label}</span>
			</div>
			{include file='calendar_splinter.tpl'} 
			<div style="clear:both">
			</div>
		</div>
	
		<div style="margin-top:10px">
			<span class="clean_table_title">{t}Records{/t} <a href="export_data_report_intrastat.php"><img id="export_data" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></a></span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0 } 
		</div>
		<div id="table0" class="data_table_container dtable btable" style="font-size:85%">
		</div>
	
</div>
{include file='footer.tpl'} 