{include file='header.tpl'}
<div id="bd" style="padding:0" >

<div style="padding:0 20px">


{include file='hr_navigation.tpl'}
<div class="branch"> 
  <span>{t}Staff{/t}</span>
</div>
    <h1>{t}Staff{/t}</h1>
  </div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
    <li> <span class="item {if $block_view=='staff'}selected{/if}"  id="staff">  <span> {t}Staff{/t}</span></span></li>
    <li> <span class="item {if $block_view=='areas'}selected{/if}"   id="areas">  <span> {t}Areas{/t}</span></span></li>
    <li> <span class="item {if $block_view=='departments'}selected{/if}"  id="departments">  <span> {t}Departments{/t}</span></span></li>
    <li> <span class="item {if $block_view=='positions'}selected{/if}"  id="positions">  <span> {t}Positions{/t}</span></span></li>
  
  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px">
 
 
<div id="block_staff" class="data_table" style="{if $block_view!='staff'}display:none;{/if}clear:both;margin:10px 0 40px 0">
<div style="clear:both;margin-top:0px;margin-right:0px;width:{if $options_box_width}{$options_box_width}{else}300px{/if};float:right;margin-bottom:0px" class="right_box">
  <div class="general_options">
    {foreach from=$staff_options_list item=options }
    {if $options.tipo=="url"}
    <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
    {else}
    <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
    {/if}
    {/foreach}
  </div>
</div>
  <div style="clear:both;">
   <span class="clean_table_title">{t}Staff List{/t} <img id="export_csv0"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
   <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
   <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	   <td {if $staff_view=='staff'}class="selected"{/if}  id="staff"  >{t}Staff{/t}</td>
	  <td  {if $staff_view=='exstaff'}class="selected"{/if}  id="exstaff"  >{t}Ex-staff{/t}</td>
	  <td  {if $staff_view=='all'}class="selected"{/if} id="all" >{t}Everybody{/t}</td>
	  
	</tr>
      </table>
  
  {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>
</div>
<div id="block_areas" style="{if $block_view!='areas'}display:none;{/if}clear:both;margin:10px 0 40px 0">


   <span class="clean_table_title">{t}Areas List{/t}</span>
<span  style="float:right;margin-left:20px;" class="table_type state_details"><a style="text-decoration:none" href="import_csv.php?subject=areas">{t}Import (CSV){/t}</a></span>
  <span  id="export_csv1" style="float:right;margin-left:20px"  class="table_type state_details" tipo="company_areas" >{t}Export (CSV){/t}</span>
  
  {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
    <div  id="table1"   class="data_table_container dtable btable "> </div>
 
</div>
<div id="block_departments" style="{if $block_view!='departments'}display:none;{/if}clear:both;margin:10px 0 40px 0">


   <span class="clean_table_title">{t}Departments List{/t}</span>
<span  style="float:right;margin-left:20px;" class="table_type state_details"><a style="text-decoration:none" href="import_csv.php?subject=departments">{t}Import (CSV){/t}</a></span>
  <span  id="export_csv2" style="float:right;margin-left:20px"  class="table_type state_details" tipo="company_departments" >{t}Export (CSV){/t}</span>
  
  
  {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }
    <div  id="table2"   class="data_table_container dtable btable "> </div>
  

</div>
<div id="block_positions" style="{if $block_view!='positions'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>

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
 {include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="staff-table-csv_export" export_options=$csv_export_options } 
  
  {include file='footer.tpl'}
