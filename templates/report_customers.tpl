{include file='header.tpl'} 
<div id="bd">
		<input type="hidden" id="to" value="{$to}" />
		<input type="hidden" id="from" value="{$from}" />
		<input type="hidden" id="calendar_id" value="{$calendar_id}" />
	<input type="hidden" id="subject" value="report_customers" />
	<input type="hidden" id="subject_key" value="" />
	
	
	<div class="branch" style="width:280px;float:left;margin:0">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; {t}Top Customers{/t}</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:right">
		</div>
		<div class="buttons">
			<span class="main_title no_buttons"> {$title} </span> 
		</div>
		<div style="clear:both">
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
	<h1 style="margin-top:10px">
	</h1>
	<div id="the_table" class="data_table" style="clear:both;margin-top:5px">
		<span class="clean_table_title">{t}Customers List{/t} </span>
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1} 
		<div id="table0" class="data_table_container dtable btable" style="font-size:80%">
		</div>
	</div>
</div>
<div id="dialog_options" style="padding:20px 20px 10px 20px;">
	<table class="edit" style="width:100%">
		<tr class="title">
			<td>{t}Balance{/t}</td>
		</tr>
		<tr>
			<td>
			<div class="buttons small left" style="float:left;">
				<button class=" table_type transaction_type state_details {if $criteria=='net_balance'}selected{/if} label_page_type" id="net_balance">{t}Balance{/t}</button> <button class=" table_type transaction_type state_details {if $criteria=='invoices'}selected{/if} label_page_type" id="invoices">{t}Number of invoices{/t}</button> 
			</div>
			</td>
		</tr>
		<tr class="title space10">
			<td>{t}Number customers{/t}</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small left" style="clear:both;float:left;">
				<button class=" table_type transaction_type state_details {if $top==200}selected{/if} label_page_type" id="top200" top="200">200</button> <button class=" table_type transaction_type state_details {if $top==100}selected{/if} label_page_type" id="top100" top="100">100</button> <button class=" table_type transaction_type state_details {if $top==25}selected{/if} label_page_type" id="top25" top="25">25</button> <button class=" table_type transaction_type state_details {if $top==10}selected{/if} label_page_type" id="top10" top="10">10</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 