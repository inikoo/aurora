{include file='header.tpl'} 
<div id="bd">
	
		<div class="branch" style="width:280px;float:left;margin:0">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; {$title}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
			</div>
			<div class="buttons">
				<span class="main_title no_buttons"> {$title}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	
	
	<div style="float:right;font-size:90%">
		
		
		<div class="buttons small ">
		{t}Limit{/t}: 
		<input type="text" id="limite" name="limite" size="10" value="{$umbral}" />
		{t}Year{/t}: 
		<input type="text" id="year" name="year" size="4" value="{$year}" />
		<button id="submit_report" style="margin-left:10px">{t}Prepare Report{/t}</button> 
		</div>
	</div>

	<div id="the_table" class="data_table" style="clear:both">
		<span class="clean_table_title">{$titulo} <a style="float:right" class="table_type state_details" href="report_tax_ES1_csv.php?umbral={$umbral}&year={$year}"><img id="export0" class="export_data_link" label="{t}Export Table{/t}" alt="{t}Export Table{/t}" src="art/icons/export_csv.gif"></a></span> 
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" class="data_table_container dtable btable" style="font-size:85%">
		</div>
	</div>
</div>

{include file='footer.tpl'} 