{include file='header.tpl' }
<div id="bd" style="padding:0px" >
<div style="padding:0 20px">
{include file='orders_navigation.tpl'}
<div style=""> 
  <span   class="branch">{if $user->get_number_stores()>1}<a  href="orders_server.php">{t}Orders{/t}</a> &rarr; {/if}{$store->get('Store Code')} {t}Orders{/t}</span>
</div>
 
  <h1>Orders {$store->get('Store Name')} ({$store->get('Store Code')})</h1>

</div>
 <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $block_view=='orders'}selected{/if}"  id="orders">  <span> {t}Orders{/t}</span></span></li>
    <li> <span class="item {if $block_view=='invoices'}selected{/if}"  id="invoices">  <span> {t}Invoices{/t}</span></span></li>
    <li> <span class="item {if $block_view=='dn'}selected{/if}"  id="dn">  <span> {t}Delivery Notes{/t}</span></span></li>
 
  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px;padding-bottom:30px">


  <div  id="block_orders" class="data_table" style="{if $block_view!='orders'}display:none{/if};clear:both;">


<div style="clear:both;margin-top:20px">
    <span class="clean_table_title">{t}Orders{/t} <img id="export_csv0"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
    
	
    <div id="table_type" class="table_type">
        <div  style="font-size:90%"   id="dispatch_chooser" style="display:{if $view!='orders'}none{/if}">
            <span style="float:right;margin-left:20px" class="table_type dispatch state_details {if $dispatch=='all_orders'}selected{/if}"  id="restrictions_all_orders" table_type="all_orders"  >{t}All Orders{/t} ({$store->get('Total Orders')})</span>
            <span style="float:right;margin-left:20px" class="table_type dispatch  state_details {if $dispatch=='in_process'}selected{/if}"  id="restrictions_orders_in_process" table_type="in_process"   >{t}In Process{/t} ({$store->get('Orders In Process')})</span>
            <span style="float:right;margin-left:20px" class="table_type dispatch state_details {if $dispatch=='dispatched'}selected{/if}"  id="restrictions_orders_dispatched"  table_type="dispatched"  >{t}Dispatched{/t} ({$store->get('Dispatched Orders')})</span>
            <span style="float:right;margin-left:20px" class="table_type dispatch state_details {if $dispatch=='unknown'}selected{/if}"  id="restrictions_orders_unknown"  table_type="unknown"  >{t}Unknown{/t} ({$store->get('Unknown Orders')})</span>
            <span style="float:right;margin-left:20px" class="table_type dispatch state_details {if $dispatch=='cancelled'}selected{/if}"  id="restrictions_orders_cancelled"  table_type="cancelled"  >{t}Cancel{/t} ({$store->get('Cancelled Orders')})</span>
            <span style="float:right;margin-left:20px" class="table_type dispatch state_details {if $dispatch=='suspended'}selected{/if}"  id="restrictions_orders_suspended"  table_type="suspended"  >{t}Suspended{/t} ({$store->get('Suspended Orders')})</span>

        </div>
     </div>
     
    
     <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
      
      <div style="">
   <table  style="float:left;margin:0 0 0 0px ;padding:0;clear:left"  class="options_mini" >
     
     
    

   </table>
</div>
    <div style="float:right;margin-top:0px;padding:0px;font-size:90%;position:relative;top:-7px">  
    <form action="orders.php?" method="GET" style="margin-top:10px">
      <div style="position:relative;left:18px"><span id="clear_interval" style="font-size:80%;color:#777;cursor:pointer;{if $to=='' and $from=='' }display:none{/if}">{t}clear{/t}</span> {t}Interval{/t}: <input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	<img style="position:relative;right:26px;cursor:pointer;height:15px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="submit_interval"  xonclick="document.forms[1].submit()" alt="{t}Go{/t}" /> 
      </div>
    </form>
    <div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
    <div style="position:relative;right:-80px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>
      </div>
      

    </div>
    
    
    
   
     {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

    <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable "> </div>
  </div>
  
  </div>
  
  
   <div  id="block_invoices"   class="data_table" style="{if $block_view!='invoices'}display:none{/if};clear:both">
   
   <div style="clear:both;margin-top:20px">
    <span class="clean_table_title">{t}Invoices{/t} <img id="export_csv1"   tipo="stores" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
     <div id="table_type" class="table_type">

        <div  style="font-size:90%"   id="invoice_chooser"  style="display:{if $view!='orders'}none{/if}">
           
            <span style="float:right;margin-left:20px" class="table_type invoice_type state_details {if $invoice_type=='all'}selected{/if}"  id="restrictions_all_invoices" table_type="all"  >{t}All{/t} ({$store->get('Total Invoices')})</span>
            <span style="float:right;margin-left:20px" class="table_type invoice_type state_details {if $invoice_type=='invoices'}selected{/if}"  id="restrictions_invoices" table_type="invoices"   >{t}Invoices{/t} ({$store->get('Invoices')})</span>
            <span style="float:right;margin-left:20px" class="table_type invoice_type state_details {if $invoice_type=='refunds'}selected{/if}"  id="restrictions_refunds"  table_type="refunds"  >{t}Refunds{/t} ({$store->get('Refunds')})</span>
            <span style="float:right;margin-left:20px" class="table_type invoice_type state_details {if $invoice_type=='to_pay'}selected{/if}"  id="restrictions_to_pay"  table_type="to_pay"  >{t}To pay{/t} ({$store->get('All To Pay Invoices')})</span>
            <span style="float:right;margin-left:20px" class="table_type invoice_type state_details {if $invoice_type=='paid'}selected{/if}"  id="restrictions_paid"  table_type="paid"  >{t}Paid{/t} ({$store->get('All Paid Invoices')})</span>
        </div>
     </div>



    <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
   
    <div style="float:right;margin-top:0px;padding:0px;font-size:90%;position:relative;top:-7px">  
    <form action="orders.php?" method="GET" style="margin-top:10px">
      <div style="position:relative;left:18px">
      <span id="clear_intervali" style="font-size:80%;color:#777;cursor:pointer;{if $to=='' and $from=='' }display:none{/if}">{t}clear{/t}
      </span> {t}Interval{/t}: <input id="v_calpop1i" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/>
      <img   id="calpop1i" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
      <span class="calpop">&rarr;</span> 
      <input   class="calpop" id="v_calpop2i" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/>
      <img   id="calpop2i" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	<img style="position:relative;right:26px;cursor:pointer;height:15px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="submit_intervali"  xonclick="document.forms[1].submit()" alt="{t}Go{/t}" /> 
      </div>
    </form>
    <div id="cal1iContainer" style="position:absolute;display:none; z-index:2"></div>
    <div style="position:relative;right:-80px"><div id="cal2iContainer" style="display:none; z-index:2;position:absolute"></div></div>
      </div>
    
    </div>
    
    
    
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

   
    
    <div  id="table1"   class="data_table_container dtable btable "> </div>
 
 </div>
 
</div>

 <div   id="block_dn"  class="data_table" style="{if $block_view!='dn'}display:none{/if};clear:both">
   
   <div style="clear:both;margin-top:20px">
   <span class="clean_table_title">{t}Delivery Note List{/t} <img id="export_csv2"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
    
        <div style="font-size:90%"  id="dn_table_type" class="table_type">
            <span style="float:right;margin-left:20px" class="table_type dn_view state_details {if $dn_state_type=='all'}selected{/if}"  id="restrictions_dn_all" table_type="all"  >{t}All{/t} ({$store->get('Total Orders')})</span>
            <img onClick="change_dn_view(this)" state="{$dn_view}"   style="cursor:pointer;float:right;margin-left:20px;position:relative;top:5px;" src="art/icons/previous.png" alt="x"/>
           <div id="dn_view_state_chooser"    style="{if $dn_view!='dn_state'}display:none{/if}">
           <span style="float:right;margin-left:20px" class="table_type dn_view state_details {if $dn_state_type=='returned'}selected{/if}"  id="restrictions_dn_returned"  table_type="returned"  >{t}Return{/t} ({$store->get('Returned Delivery Notes')})</span>
            <span style="float:right;margin-left:20px" class="table_type dn_view state_details {if $dn_state_type=='send'}selected{/if}"  id="restrictions_dn_send"  table_type="send"  >{t}Send{/t} ({$store->get('Dispatched Delivery Notes')})</span>
            <span style="float:right;margin-left:20px" class="table_type dn_view state_details {if $dn_state_type=='ready'}selected{/if}"  id="restrictions_dn_ready"  table_type="ready"  >{t}Ready{/t} ({$store->get('Ready to dn_view Delivery Notes')})</span>
            <span style="float:right;margin-left:20px" class="table_type dn_view state_details {if $dn_state_type=='packing'}selected{/if}"  id="restrictions_dn_packing"  table_type="packing"  >{t}Packing{/t} ({$store->get('Packing Delivery Notes')})</span>
            <span style="float:right;margin-left:20px" class="table_type dn_view state_details {if $dn_state_type=='picking'}selected{/if}"  id="restrictions_dn_picking"  table_type="picking"  >{t}Picking{/t} ({$store->get('Picking Delivery Notes')})</span>
            <span style="float:right;margin-left:20px" class="table_type dn_view  state_details {if $dn_state_type=='ready_to_pick'}selected{/if}"  id="restrictions_dn_ready_to_pick" table_type="ready_to_pick"   >{t}To Pick{/t} ({$store->get('Ready to Pick Delivery Notes')})</span>
            </div>
             <div id="dn_view_type_chooser" style="{if $dn_view!='dn_type'}display:none{/if}">
            <span style="float:right;margin-left:20px" class="table_type dn_view state_details {if $dn_state_type=='shortages'}selected{/if}"  id="restrictions_dn_shortages"  table_type="shortages"  >{t}Shortages{/t} ({$store->get('Delivery Notes For Shortages')})</span>
            <span style="float:right;margin-left:20px" class="table_type dn_view state_details {if $dn_state_type=='replacements'}selected{/if}"  id="restrictions_dn_replacements"  table_type="replacements"  >{t}Replacements{/t} ({$store->get('Delivery Notes For Replacements')})</span>
            <span style="float:right;margin-left:20px" class="table_type dn_view state_details {if $dn_state_type=='donations'}selected{/if}"  id="restrictions_dn_donations"  table_type="donations"  >{t}Donations{/t} ({$store->get('Delivery Notes For Donations')})</span>
            <span style="float:right;margin-left:20px" class="table_type dn_view state_details {if $dn_state_type=='samples'}selected{/if}"  id="restrictions_dn_samples"  table_type="samples"  >{t}Samples{/t} ({$store->get('Delivery Notes For Samples')})</span>
            <span style="float:right;margin-left:20px" class="table_type dn_view  state_details {if $dn_state_type=='orders'}selected{/if}"  id="restrictions_dn_orders" table_type="orders"   >{t}Orders{/t} ({$store->get('Delivery Notes For Orders')})</span>
            </div>
            
     </div>
    <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
      <div style="">
   
</div>
    <div style="float:right;margin-top:0px;padding:0px;font-size:90%;position:relative;top:-7px">  
    <form action="orders.php?" method="GET" style="margin-top:10px">
      <div style="position:relative;left:18px">
      <span id="clear_intervaldn" style="font-size:80%;color:#777;cursor:pointer;{if $to=='' and $from=='' }display:none{/if}">{t}clear{/t}
      </span> {t}Interval{/t}: <input id="v_calpop1dn" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/>
      <img   id="calpop1dn" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
      <span class="calpop">&rarr;</span> 
      <input   class="calpop" id="v_calpop2dn" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/>
      <img   id="calpop2dn" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	<img style="position:relative;right:26px;cursor:pointer;height:15px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="submit_intervaldn"   alt="{t}Go{/t}" /> 
      </div>
    </form>
    <div id="cal1dnContainer" style="position:absolute;display:none; z-index:2"></div>
    <div style="position:relative;right:-80px"><div id="cal2dnContainer" style="display:none; z-index:2;position:absolute"></div></div>
      </div>
    
    </div>

    
    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }

    
   
    <div  id="table2"   class="data_table_container dtable btable "> </div>
 
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
{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="orders-table-csv_export0" export_options=$csv_export_options0 }
{include file='export_csv_menu_splinter.tpl' id=1 cols=$export_csv_table_cols1 session_address="orders-table-csv_export1" export_options=$csv_export_options1 }
{include file='export_csv_menu_splinter.tpl' id=2 cols=$export_csv_table_cols2 session_address="orders-table-csv_export2" export_options=$csv_export_options2 }

{include file='footer.tpl'}
