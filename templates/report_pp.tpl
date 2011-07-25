{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}
{include file='calendar_splinter.tpl'}

<h1 style="clear:left">{$title}</h1>


 <div class="data_table" style="clear:both;">
	<span id="table_title" class="clean_table_title">{t}Pickers{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div id="table_info0" class="clean_table_info"> <span class="filter_msg"  id="filter_msg0"></span></div></div>

	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
	</div>
	<div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>

<div class="data_table" style="clear:both;">
	<span id="table_title" class="clean_table_title">{t}Packers{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div id="table_info1" class="clean_table_info"> <span class="filter_msg"  id="filter_msg1"></span></div></div>

	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
	</div>
	<div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>

</div>
{include file='footer.tpl'}

