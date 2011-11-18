{include file='header.tpl'}
<div id="bd" style="padding:0px">
 <div style="padding:0 20px">
{include file='contacts_navigation.tpl'}
<div class="branch"> 
  <span   >{if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}{$store->get('Store Code')} {t}Customers{/t}</span>
</div>
<div class="top_page_menu">

<div class="buttons" style="float:right">

{if $modify}
<button  id="new_customer"><img src="art/icons/add.png" alt=""> {t}Add Customer{/t}</button>
<button  onclick="window.location='edit_customers.php?store={$store->id}'" ><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Customers{/t}</button>
<button  onclick="window.location='customer_store_configuration.php?store={$store->id}'" ><img src="art/icons/cog.png" alt=""> {t}Configuration{/t}</button>
{/if}
</div>


<div class="buttons" style="float:left">

<button  onclick="window.location='customers_stats.php?store={$store->id}'" ><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button>
<button  onclick="window.location='customers_lists.php?store={$store->id}'" ><img src="art/icons/table.png" alt=""> {t}Lists{/t}</button>
<button  onclick="window.location='customer_categories.php?id=0&store={$store->id}'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Categories{/t}</button>



</div>


<div style="clear:both"></div>
</div>

<h1>{t}Customers{/t} <span class="id">{$store->get('Store Code')}</span></h1>



</div>


<div style="padding:0px">
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">
    <li> <span class="item {if $block_view=='contacts_with_orders'}selected{/if}"  id="contacts_with_orders">  <span> {t}Contacts with Orders{/t}</span></span></li>
    <li> <span class="item {if $block_view=='all_contacts'}selected{/if}"  id="all_contacts">  <span> {t}All Contacts{/t}</span></span></li>

  </ul>

<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
</div>


<div style="padding:0 20px">






  <div style="padding:15px 0 30px 0"> 
  
  
  
  
  <div id="overview_all_contacts" style="{if $block_view!='all_contacts'}display:none;{/if}margin:15px 0 10px 0">
<div style="width:400px;float:left">
<p style="padding:2px 10px;border-top:1px solid black;border-bottom:1px solid black">
{$overview_all_contacts_text}
</p>
</div>
<div style="float:left;font-size:80%;text-align:center">

<div style="margin-left:20px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
{t}Total Contacts{/t}
<div style="font-size:120%;font-weight:800">{$store->get('Contacts')}</div>
<div style="margin-top:2px;color:#555">
{t}Total with orders{/t}<div style="font-size:120%"><span style="font-weight:800">{$store->get('Contacts With Orders')}</span> <span>({$store->get('Percentage Total With Orders')})</span></div>
</div>




</div>
<div style="display:none;margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px"><a href="new_customers_list.php?store={$store->get('Store Key')}&active=1&auto=1">{t}Active Customers{/t}<div style="font-size:120%;font-weight:800">{$store->get('Active Contacts')}</div></a></div>
<div style="display:none;margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px"><a href="new_customers_list.php?store={$store->get('Store Key')}&lost=1&auto=1">{t}Lost Customers{/t}<div style="font-size:120%;font-weight:800">{$store->get('Lost Contacts')}</div></a></div>
<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px"><a href="new_customers_list.php?store={$store->get('Store Key')}&potential=1&auto=1">{t}Potential Customers{/t}<div style="font-size:120%;font-weight:800">{$store->get('Potential Customers')}</div></a></div>

</div>
<div style="clear:both"></div>
</div>
  
   <div id="overview_contacts_with_orders" style="{if $block_view!='contacts_with_orders'}display:none;{/if}margin:15px 0 10px 0">
<div style="width:400px;float:left">
<p style="padding:2px 10px;border-top:1px solid black;border-bottom:1px solid black">
{$overview_contacts_with_orders_text}
</p>
</div>
<div style="float:left;font-size:80%;text-align:center">

<div style="margin-left:20px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
{t}Total Contacts{/t}
<div style="font-size:120%;font-weight:800">{$store->get('Contacts With Orders')}</div>
</div>
<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px"><a href="new_customers_list.php?store={$store->get('Store Key')}&active=1&auto=1">{t}Active Customers{/t}<div style="font-size:120%;font-weight:800">{$store->get('Active Contacts With Orders')}</div></a></div>
<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px"><a href="new_customers_list.php?store={$store->get('Store Key')}&lost=1&auto=1">{t}Lost Customers{/t}<div style="font-size:120%;font-weight:800">{$store->get('Lost Contacts With Orders')}</div></a></div>

</div>
<div style="clear:both"></div>
</div>
  
  
 <div style="clear:both"> 
      <span class="clean_table_title">{t}Customers List{/t} <img id="export_csv0"   class="export_data_link" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
     
     <div id="table_type_contacts_with_orders" class="table_type"  style="{if $block_view!='contacts_with_orders'}display:none;{/if}font-size:90%" >
        <div     id="transaction_chooser" >
                    <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_contacts_with_orders.Lost}selected{/if} label_contacts_with_orders_lost"  id="elements_contacts_with_orders_lost" table_type="lost"   >{t}Lost{/t} (<span id="elements_contacts_with_orders_lost_number">{$elements_number_contacts_with_orders.Lost}</span>)</span>

            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_contacts_with_orders.Losing}selected{/if} label_contacts_with_orders_losing"  id="elements_contacts_with_orders_losing" table_type="losing"   >{t}Losing{/t} (<span id="elements_contacts_with_orders_losing_number">{$elements_number_contacts_with_orders.Losing}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_contacts_with_orders.Active}selected{/if} label_contacts_with_orders_active"  id="elements_contacts_with_orders_active" table_type="active"   >{t}Active{/t} (<span id="elements_contacts_with_orders_active_number">{$elements_number_contacts_with_orders.Active}</span>)</span>
        </div>
     </div>
     
     
     
     
        <div id="table_type_all_contacts" class="table_type"  style="{if $block_view!='all_contacts'}display:none;{/if}font-size:90%" >
        <div id="transaction_chooser" >
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_all_contacts.Lost}selected{/if} label_all_contacts_lost"  id="elements_all_contacts_lost" table_type="lost"   >{t}Lost{/t} (<span id="elements_all_contacts_lost_number">{$elements_number_all_contacts.Lost}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_all_contacts.Losing}selected{/if} label_all_contacts_losing"  id="elements_all_contacts_losing" table_type="losing"   >{t}Losing{/t} (<span id="elements_all_contacts_losing_number">{$elements_number_all_contacts.Losing}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_all_contacts.Active}selected{/if} label_all_contacts_active"  id="elements_all_contacts_active" table_type="active"   >{t}Active{/t} (<span id="elements_all_contacts_active_number">{$elements_number_all_contacts.Active}</span>)</span>
        </div>
     </div>
 <div class="table_top_bar"></div>
  <div class="clusters">
  <div  class="buttons small left cluster" >
	
	  <button class="table_option {if $view=='general'}selected{/if}" id="general" >{t}General{/t}</button>
	  <button class="table_option {if $view=='contact'}selected{/if}"  id="contact"  >{t}Contact{/t}</button>
	  <button class="table_option {if $view=='address'}selected{/if}"  id="address"  >{t}Address{/t}</button>
	  <button class="table_option {if $view=='balance'}selected{/if}"  id="balance"  >{t}Balance{/t}</button>
	  <button class="table_option {if $view=='rank'}selected{/if}"  id="rank"  >{t}Ranking{/t}</button>
      </div>
      <div style="clear:both"></div>
      </div>
      
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
 <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable "> </div>
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




<div id="dialog_new_customer" style="padding:20px 20px 10px 20px ">
  <div id="new_customer_msg"></div>
  
  <div class="buttons">
  <button  class="positive"  onClick="new_customer()" >{t}Manually{/t}</button>
  <button  class="positive" onClick="new_customer_from_file()" >{t}Import from file{/t}</button>
    <button class="negative" id="close_dialog_new_customer" >{t}Cancel{/t}</button>

  </div>

</div>

{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="customers-csv_export0" export_options=$csv_export_options0 }

{include file='footer.tpl'}
