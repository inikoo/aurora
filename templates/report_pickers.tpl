{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		<div class="branch" style="width:280px;float:left;margin:0">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; {t}Pickers & Packers{/t}</span> 
		</div>
		{include file='calendar_splinter.tpl'} 
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
			</div>
			<div class="buttons" style="float:left;margin-bottom:4px" ">
				<span class="main_title"> {$title}, <span class="id">{$period}</span> <img id="show_calendar_browser" style="cursor:pointer;vertical-align:text-bottom;position:relative;top:-3px;{if $tipo=='f'}display:none{/if}" src="art/icons/calendar.png" alt="calendar" /> </span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='pickers'}selected{/if}" onclick="window.location='report_pp.php?tipo={$ref_tipo}&view=pickers'" id="pickers"> <span> {t}Pickers{/t}</span></span></li>
		<li> <span class="item {if $block_view=='packers'}selected{/if}" onclick="window.location='report_pp.php?tipo={$ref_tipo}&view=packers'" id="packers"> <span> {t}Packers{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div style="padding:0 20px;padding-bottom:30px">
		<div class="data_table" style="clear:both;margin-top:20px">
			<span id="table_title" class="clean_table_title">{t}Pickers{/t}</span> 
			<div class="table_top_bar space">
				</div>
			{include file='table_splinter.tpl' table_id=0 filter_name='' filter_value='' no_filter=true } 
			<div id="table0" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'} 