{include file='header.tpl'} 
<div id="bd">
	<div class="branch" style="width:280px;float:left;margin:0"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a> &rarr; <a  href="reports.php">{t}Reports{/t}</a> &rarr; {t}Sales{/t}
</div>
	
	<div style="clear:both"></div>
	<h1 style="margin-top:10px">
		{t}Intrastat{/t}, <span class="id">{$period}</span>
	</h1>

<div>
	<span class="clean_table_title">{t}Records{/t}  <a href="export_data_report_intrastat.php" ><img id="export_data"  style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></a></span>
		
			<div class="table_top_bar" style="margin-bottom:15px">
			</div>
		
			
			
			
				
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0 } 
			</div>
			<div id="table0" class="data_table_container dtable btable" style="font-size:85%">
			</div>

	</div>
	
	
	
	{include file='footer.tpl'} 