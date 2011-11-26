{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0 20px">
{include file='locations_navigation.tpl'}
<div class="branch"> 
  <span>{t}Warehouses{/t}</span>
</div>
 <h1 style="clear:left">{t}Warehouses{/t}</h1>
 </div>

<div style="padding:0px">
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='warehouses'}selected{/if}"  id="warehouses">  <span> {t}Warehouses{/t}</span></span></li>
    <li> <span class="item {if $view=='areas'}selected{/if}"  id="areas">  <span> {t}Areas{/t}</span></span></li>
    <li> <span class="item {if $view=='shelfs'}selected{/if}"  id="shelfs">  <span> {t}Shelfs{/t}</span></span></li>
    <li> <span class="item {if $view=='locations'}selected{/if}"  id="locations">  <span> {t}Locations{/t}</span></span></li>
    <li> <span class="item {if $view=='parts'}selected{/if}" id="parts"  ><span>  {t}Parts{/t}</span></span></li>

  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div id="block_warehouses" style="{if $view!='warehouses'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
    <span class="clean_table_title">{t}Warehouses{/t} <img id="export_csv0"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>
<div id="block_areas" style="{if $view!='areas'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
    <span class="clean_table_title">{t}Areas{/t} <img id="export_csv0"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
    <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>  
<div id="block_shelfs" style="{if $view!='shelfs'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
    <span class="clean_table_title">{t}Areas{/t} <img id="export_csv0"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }
    <div  id="table2"   class="data_table_container dtable btable "> </div>
</div>  
<div id="block_locations" style="{if $view!='locations'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
    <span class="clean_table_title">{t}Locations{/t} <img id="export_csv0"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
    {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3  }
    <div  id="table3"   class="data_table_container dtable btable "> </div>
</div>  
<div id="block_parts" style="{if $view!='parts'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
    <span class="clean_table_title">{t}Parts{/t} <img id="export_csv0"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
    {include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4  }
    <div  id="table4"   class="data_table_container dtable btable "> </div>
</div>  
   
   
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
 {include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="warehouses-table-csv_export" export_options=$csv_export_options } 
{include file='footer.tpl'}
