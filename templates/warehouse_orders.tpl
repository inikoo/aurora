{include file='header.tpl'}
<div id="bd" >
  <span class="nav2 onleft"><a class="selected" id="warehouse_operations" href="warehouse_orders.php">{t}Warehouse Operations{/t}</a></span>

  <div  id="orders_table" class="data_table" style="clear:left;margin-top:23px">
    <span class="clean_table_title">{t}Orders In Warehouse{/t}</span>

     
   <div  style="font-size:90%">
   
       
          <span   style="float:right;margin-left:20px" class="table_type  state_details {if $table_type=='all_contacts'}selected{/if}"  id="restrictions_all_contacts" table_type="all_contacts"  >{t}All Wanting Orders{/t} ({$store->get('Total Customer Contacts')})</span>
  <span   style="float:right;margin-left:20px" class="table_type  state_details {if $table_type=='all_customers'}selected{/if}"  id="restrictions_all_customers" table_type="all_customers"   >{t}Ready to Pick{/t} ({$store->get('Total Customers')})</span>
  <span   style="float:right;margin-left:20px" class="table_type  state_details {if $table_type=='active_customers'}selected{/if}"  id="restrictions_active_customers"  table_type="active_customers"  >{t}Ready to Pack{/t} ({$store->get('Active Customers')})</span>
  <span   style="float:right;margin-left:20px" class="table_type  state_details {if $table_type=='active_customers'}selected{/if}"  id="restrictions_active_customers"  table_type="active_customers"  >{t}Ready to Ship{/t} ({$store->get('Active Customers')})</span>

         
         
     </div>
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
  
  <table style="float:left;margin:0 0 0 0px ;padding:0;height:15px;"  class="options">
	<tr>
	 

	</tr>
      </table>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

    <div  id="table0" style="font-size:90%"  class="data_table_container dtable btable "> </div>
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

{include file='footer.tpl'}


<div id="pick_it_dialog">
{foreach from=$pickers item=picker}
<div></div>
{/foreach}

<table class="edit">
<tr class="first"><td style="" class="label">{t}Staff Name{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Staff_Name" value="" ovalue="" valid="0">
       <div id="Staff_Name_Container" style="" ></div>
     </div>
   </td>
   <td id="Staff_Name_msg" class="edit_td_alert"></td>
 </tr>
</table>

<table class="edit">
  <tr><td>{t}PIN{/t}:</td><td><input /></td></tr>
  <tr><td colspan="2"><span class="button">Cancel</span><span class="button">Go</span><td></tr>
</table>

<div>
