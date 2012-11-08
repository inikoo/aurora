{include file='header.tpl'}
<div id="bd" >

{include file='hr_navigation.tpl'}




 <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>{t}Company Departments{/t}</h1>
  </div>

 
<div class="data_table" style="clear:both">
   <span class="clean_table_title">{t}Departments List{/t}</span>
<span  style="float:right;margin-left:20px;" class="table_type state_details"><a style="text-decoration:none" href="import_csv.php?subject=departments">{t}Import (CSV){/t}</a></span>
  <span  id="export_csv0" style="float:right;margin-left:20px"  class="table_type state_details" tipo="company_departments" >{t}Export (CSV){/t}</span>
  
  
  {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable"> </div>
  </div>

</div>


  <div id="filtermenu0" class="yuimenu">
    <div class="bd">
      <ul class="first-of-type">
	<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
	{foreach from=$filter_menu0 item=menu }
	<li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
	{/foreach}
      </ul>
    </div>
  </div>
  
  <div id="rppmenu0" class="yuimenu">
    <div class="bd">
      <ul class="first-of-type">
	<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
	{foreach from=$paginator_menu0 item=menu }
	<li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
	{/foreach}
      </ul>
    </div>
  </div>
  
  {include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="company_departments-table-csv_export" export_options=$csv_export_options } 
  {include file='footer.tpl'}
