{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">
 {include file='orders_navigation.tpl'}
<div class="branch"> 
  <span ><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {t}Orders{/t}</span>
</div>
 
    <h1>{t}Orders Corporate Overview{/t}</h1>
</div> 
 
 <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">
    <li> <span class="item {if $block_view=='orders'}selected{/if}"  id="orders">  <span> {t}Orders{/t}</span></span></li>
    <li> <span class="item {if $block_view=='invoices'}selected{/if}"  id="invoices">  <span> {t}Invoices{/t}</span></span></li>
    <li> <span class="item {if $block_view=='dn'}selected{/if}"  id="dn">  <span> {t}Delivery Notes{/t}</span></span></li>
 
  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px">
 

  <div id="block_orders"   style="padding-top:20px;clear:both;{if $block_view!='orders'}display:none{/if}">
    <span class="clean_table_title">{t}Orders Per Store{/t} <img id="export_csv0"   class="export_data_link" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  no_filter=1} 
    <div  id="table0"   class="data_table_container dtable btable with_total "> </div>
  </div>
  <div  id="block_invoices"  style="padding-top:20px;clear:both;{if $block_view!='invoices'}display:none{/if}">
    <span class="clean_table_title">{t}Invoices Per Store{/t} <img id="export_csv1" class="export_data_link" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>


    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  no_filter=1} 
    <div  id="table1"   class="data_table_container dtable btable with_total "> </div>
  </div>  
  <div  id="block_dn"  style="padding-top:20px;clear:both;{if $block_view!='dn'}display:none{/if}">
    <span class="clean_table_title">{t}Delivery Notes Per Store{/t} <img id="export_csv2"   class="export_data_link" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>

    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:0px"></div>
    <div class="clusters">
    <div class="buttons small left cluster" >
	<button  {if $dn_view=='dn_state'}class="selected"{/if} id="dn_state" >{t}Dispatch State{/t}</button>
    <button  {if $dn_view=='dn_type'}class="selected"{/if} id="dn_type" >{t}Type of Order{/t}</button>
   </div>
      <div style="clear:both"></div>
	</div>
    
    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  no_filter=1} 
    <div  id="table2"   class="data_table_container dtable btable with_total "> </div>
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
<div id="filtermenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu2" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu2" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},2)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols0 session_address="orders-table-csv_export0" export_options=$csv_export_options0 }
{include file='export_csv_menu_splinter.tpl' id=1 cols=$export_csv_table_cols1 session_address="orders-table-csv_export1" export_options=$csv_export_options1 }
{include file='export_csv_menu_splinter.tpl' id=2 cols=$export_csv_table_cols2 session_address="orders-table-csv_export2" export_options=$csv_export_options2 }

{include file='footer.tpl'}
