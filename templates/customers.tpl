{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}

  <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>{t}Customers{/t} ({$store->get('Store Code')})</h1>
  </div>
  <div id="info"  style="clear:left;margin-top:10px;padding:0 0px;width:910px;{if $details==0}display:none{/if}">
    <h2>{t}Customers Information{/t} ({$store->get('Store Code')})</h2>
      <p style="width:475px">{$overview_text}</p>
      <div id="plot"  class="top_bar" style="width:100%;;position:relative;clear:both;padding:0;margin:0px;{if !$details}display:none;{/if}">
<span id="plot_info" keys="{$store->get('Store Key')}">
	<ul id="plot_chooser" class="tabs" style="margin:0 0px;padding:0 20px "  >
	  <li>
	    <span class="item  {if $plot_tipo=='customers'}selected{/if}" onClick="change_plot(this)" id="plot_customers" tipo="customers" category="{$plot_data.customers.category}" period="{$plot_data.customers.period}" >
	      <span>{t}All Contacts{/t}</span>
	    </span>
	  </li>
	  <li>
	    <span class="item  {if $plot_tipo=='active_customers'}selected{/if}"  id="plot_active_customers" onClick="change_plot(this)" tipo="active_customers" category="{$plot_data.active_customers.category}" period="{$plot_data.active_customers.period}" name=""  >
	      <span>{t}Actual Customers{/t}</span>
	    </span>
	  </li>
	  
	</ul> 
	<ul id="plot_options" class="tabs" style="{if $plot_tipo=='pie'}display:none{/if};position:relative;top:.6em;float:right;margin:0 20px;padding:0 20px;font-size:90% "  >
	  <li><span class="item option"> <span id="plot_category"  category="{$plot_category}" style="xborder:1px solid black;display:inline-block; vertical-align:middle">{$plot_formated_category}</span></span></li>
	  <li><span class="item option"> <span id="plot_period"   period="{$plot_period}" style="xborder:1px solid black;display:inline-block; vertical-align:middle">{$plot_formated_period}</span></span></li>
	</ul> 
	
	<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999">
	  
      </div>
      
      <iframe id="the_plot" src ="{$plot_page}?{$plot_args}"  frameborder=0 height="400" scrolling="no" width="100%"></iframe>
      </div>
      <p style="width:475px">{$top_text}</p>
      <p style="width:475px">{$export_text}</p>




    </div>

    
    <div id="the_table" class="data_table" style="clear:both">
      <span class="clean_table_title">Customers List</span>
      
   <div  style="font-size:90%">
         <span  id="export_csv0" style="float:right;margin-left:20px"  class="table_type state_details" tipo="customers" >{t}Export (CSV){/t}</span>
          <span style="float:right;margin-left:20px" class="table_type  state_details {if $table_type=='all_contacts'}selected{/if}"  id="restrictions_all_contacts" table_type="all_contacts"  >{t}All Contacts{/t} ({$store->get('Total Customer Contacts')})</span>
          <span style="float:right;margin-left:20px" class="table_type  state_details {if $table_type=='all_customers'}selected{/if}"  id="restrictions_all_customers" table_type="all_customers"   >{t}All Customers{/t} ({$store->get('Total Customers')})</span>
          <span style="float:right;margin-left:20px" class="table_type  state_details {if $table_type=='active_customers'}selected{/if}"  id="restrictions_active_customers"  table_type="active_customers"  >{t}Active Customers{/t} ({$store->get('Active Customers')})</span>

	  <span  id="import_csv0" style="float:right;margin-left:20px"  class="table_type state_details" tipo="import.php" >{t}Import (CSV){/t}</span>

     </div>
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
  
  <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" {if $customers==0 }style="display:none"{/if}>
	<tr>
	  <td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='contact'}class="selected"{/if}  id="contact"  >{t}Contact{/t}</td>
	  <td {if $view=='addresses'}class="selected"{/if}  id="address"  >{t}Address{/t}</td>
	  <td {if $view=='ship_to_addresses'}class="selected"{/if}  id="ship_to_address"  >{t}Shipping Address{/t}</td>
	  <td {if $view=='balance'}class="selected"{/if}  id="balance"  >{t}Balance{/t}</td>
	  <td {if $view=='rank'}class="selected"{/if}  id="rank"  >{t}Ranking{/t}</td>

	</tr>
      </table>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
 <div  id="table0"   class="data_table_container dtable btable "> </div>
 </div>
  
  
  
  
</div>


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

<div id="plot_period_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Plot frequency{/t}:</li>
      {foreach from=$plot_period_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_period('{$menu.period}')"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="plot_category_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Plot Type{/t}:</li>
      {foreach from=$plot_category_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_category('{$menu.category}')"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


<div id="dialog_new_customer" style="padding:10px">
  <div id="new_customer_msg"></div>
  {t}Create new Customer{/t}:
  
  <table style="margin:10px">
<tr>
 <td> <span  style="margin:0 10px" class="unselectable_text state_details" onClick="new_customer('company')" >{t}Company{/t}</span></td>
 <td> <span  style="margin:0 10px" class="unselectable_text state_details" onClick="new_customer('person')" >{t}Individual{/t}</span></td>
</tr>
<tr>
 <td colspan=2 style="text-align:center"> <span class="unselectable_text state_details" onClick="new_customer_from_file()" >{t}Import from file{/t}</span></td>
</tr>
  </table>
  <span  class="unselectable_text state_details" onClick="close_dialog('make_order')" >{t}Cancel{/t}</span>


</div>

{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="customers-csv_export0" export_options=$csv_export_options0 }

{include file='footer.tpl'}
