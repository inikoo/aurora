{include file='header.tpl'} 
<div id="bd">
	<div class="branch" style="width:280px;float:left;margin:0">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; {t}Top Customers{/t}</span> 
	</div>
	{include file='calendar_splinter.tpl'} 
	<div style="clear:both">
	</div>
	<h1 style="margin-top:10px">
		{$title}, <span class="id">{$period}</span> <img id="show_calendar_browser" style="cursor:pointer;vertical-align:text-bottom;position:relative;top:-3px;{if $tipo=='f'}display:none{/if}" src="art/icons/calendar.png" alt="calendar" /> 
	</h1>
	<div id="the_table" class="data_table" style="clear:both;margin-top:5px">
		<span class="clean_table_title">{t}Customers List{/t} <img id="export0" class="export_data_link" label="{t}Export Table{/t}" alt="{t}Export Table{/t}" src="art/icons/export_csv.gif"></span> 
		<div style="font-size:90%" id="transaction_chooser">
			<span style="float:right;margin-left:7px;" class=" table_type transaction_type state_details {if $criteria=='net_balance'}selected{/if} label_page_type" id="net_balance">{t}Balance{/t}</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $criteria=='invoices'}selected{/if} label_page_type" id="invoices">{t}Number of invoices{/t}</span> 
			<span style="float:right;margin-left:7px;" class=" table_type transaction_type state_details {if $top==200}selected{/if} label_page_type" id="top200" top="200">200</span> <span style="float:right;margin-left:7px;" class=" table_type transaction_type state_details {if $top==100}selected{/if} label_page_type" id="top100" top="100">100</span> <span style="float:right;margin-left:7px;" class=" table_type transaction_type state_details {if $top==25}selected{/if} label_page_type" id="top25" top="25">25</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $top==10}selected{/if} label_page_type" id="top10" top="10">10</span> 
		</div>
		<div class="table_top_bar" style="margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1} 
		<div id="table0" class="data_table_container dtable btable" style="font-size:80%">
		</div>
	</div>
</div>
<div id="dialog_export" style="padding:15px 25px 5px 20px">
	<table>
		<tr>
			<td colspan="3">
			<div class="buttons">
				<button onclick="window.location='export.php?ar_file=ar_reports&tipo=customers&output=xls'" style="width:70px"><img src="art/icons/page_excel.png" alt=""> Excel</button> <button onclick="window.location='export.php?ar_file=ar_reports&tipo=customers&output=csv'" style="width:70px"><img src="art/icons/page_white_text.png" alt=""> CSV</button>
			</div>
			</td>
		</tr>
		<tr style="height:10px">
			<td colspan="3"></td>
		</tr>
		<tr>
			<td>{t}Map{/t}:</td>
			<td>Default</td>
			<td>
			<div class="buttons small">
				<button onclick="alert('not availeable yet! :(')">{t}Change map{/t}</button>
			</div>
			</td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 