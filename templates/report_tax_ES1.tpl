{include file='header.tpl'} 
<div id="bd">
	{include file='reports_navigation.tpl'} 
	<div style="float:right;font-size:70%">
		{t}Limit{/t}: 
		<input type="text" id="limite" name="limite" size="10" value="{$umbral}" />
		{t}Year{/t}: 
		<input type="text" id="year" name="year" size="4" value="{$year}" />
		<span class="state_details" style="font-size:100%;margin-left:10px" id="submit_report">{t}Prepare Report{/t}</span> 
	</div>
	<div id="no_details_title" style="clear:left;">
		<h1>
			Modelo 347
		</h1>
	</div>

	<div id="the_table" class="data_table" style="clear:both">
		<span class="clean_table_title">{$titulo}  <a style="float:right" class="table_type state_details" href="report_tax_ES1_csv.php?umbral={$umbral}&year={$year}"><img id="export0" class="export_data_link" label="{t}Export Table{/t}" alt="{t}Export Table{/t}" src="art/icons/export_csv.gif"></a></span> 
	
		
					<div class="table_top_bar" style="margin-bottom:15px">
					</div>
				
				
		
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		
		
		<div id="table0" class="data_table_container dtable btable" style="font-size:85%">
		</div>
	</div>
</div>
</div>
</div>
</div>
{include file='footer.tpl'} 