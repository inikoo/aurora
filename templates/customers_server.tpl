{include file='header.tpl'}
<div id="bd" >
 
{include file='contacts_navigation.tpl'}
<div class="branch"> 
  <span >{t}Customers{/t}</span>
</div>
<div class="top_page_menu">
<div class="buttons" style="float:right">
</div>
<div class="buttons" style="float:left">
<button  onclick="window.location='customers_server_stats.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button>
</div>
<div style="clear:both"></div>
</div>

 

<div class="data_table" style="clear:both;margin-top:15px">
    <span class="clean_table_title">{t}Customers per Store{/t} <img id="export_csv0"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 

  <div  style="font-size:90%">
            <span style="float:right;margin-left:20px" class="table_type  state_details {if $type=='contacts_with_orders'}selected{/if}"  id="contacts_with_orders"   >{t}Contacts with Orders{/t}</span>
          <span style="float:right;margin-left:20px" class="table_type  state_details {if $type=='all_contacts'}selected{/if}"  id="all_contacts"   >{t}All Contacts{/t}</span>


	

     </div>

<div class="table_top_bar"></div>
 
 
 <span   style="float:right;margin-left:80px" class="state_details"  id="change_display_mode" >{$display_mode_label}</span>



<table style="float:left;margin:0 0 0 0px ;padding:0;margin-bottom:10px"  class="options" >
	
      </table>



       
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  no_filter=1} 
<div  id="table0"   class="data_table_container dtable btable with_total"> </div>		
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
{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols0 session_address="orders-table-csv_export0" export_options=$csv_export_options0 }
{include file='footer.tpl'}
