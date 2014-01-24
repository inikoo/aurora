{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<input type="hidden" id="calendar_id" value="{$calendar_id}" />
	<input type="hidden" id="from" value="{$from}" />
	<input type="hidden" id="to" value="{$to}" />
	<input type="hidden" id="subject" value="report_pp" />
	<input type="hidden" id="subject_key" value="" />
		<input type="hidden" id="employee_key" value="{$employee->id}" />

	
	
	<div style="padding:0 20px">
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; <a href="report_pp.php">{t}Pickers & Packers{/t}</a> &rarr; {$employee->get('Staff Name')}</span> 
		</div>
		<div class="top_page_menu" style="margin-top:5px">
			<div class="buttons" style="float:right">
			</div>
			<div class="buttons">
				<span class="main_title no_buttons">{$title}</span> 
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
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
		<li> <span class="item {if $block_view=='overview'}selected{/if}"  id="overview"> <span> {t}Overview{/t}</span></span></li>
		<li> <span class="item {if $block_view=='picked'}selected{/if}"  id="picked"> <span> {t}Picked{/t}</span></span></li>
		<li> <span class="item {if $block_view=='packed'}selected{/if}"  id="packed"> <span> {t}Packed{/t}</span></span></li>
	</ul>
	<div class="tabs_base">
			</div>
	<div style="padding:0 20px;padding-bottom:30px">
	<div id="block_overview" class="data_table" style="clear:both;margin-top:20px;{if $block_view!='overview'}display:none{/if}">
	
	</div>
	
		<div id="block_picked" class="data_table" style="clear:both;margin-top:20px;{if $block_view!='picked'}display:none{/if}">
			<span id="table_title" class="clean_table_title">{t}Picked Delivery Notes{/t}</span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name='' filter_value='' no_filter=true } 
			<div id="table0" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_packed" class="data_table" style="clear:both;margin-top:20px;{if $block_view!='packed'}display:none{/if}">
			<span id="table_title" class="clean_table_title">{t}Packed Delivery Notes{/t}</span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name='' filter_value='' no_filter=true } 
			<div id="table1" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'} 