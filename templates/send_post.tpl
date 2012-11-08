{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}

  <div id="no_details_title"  style="clear:left;xmargin:0 20px;">
    <h1>{t}Customers{/t} </h1>
  </div>

    <div id="the_table" class="data_table" style="clear:both">
      <span class="clean_table_title">{t}Customers List{/t} <span style="padding-left:720px; font-size:11px;">{$msg}</span></span>
      
  {* <div  style="font-size:90%">
          <span style="float:right;margin-left:20px" class="table_type  state_details {if $table_type=='all_contacts'}selected{/if}"  id="restrictions_all_contacts" table_type="all_contacts"  >{t}All Contacts{/t} ({$store->get('Total Customer Contacts')})</span>
          <span style="float:right;margin-left:20px" class="table_type  state_details {if $table_type=='all_customers'}selected{/if}"  id="restrictions_all_customers" table_type="all_customers"   >{t}All Customers{/t} ({$store->get('Total Customers')})</span>
          <span style="float:right;margin-left:20px" class="table_type  state_details {if $table_type=='active_customers'}selected{/if}"  id="restrictions_active_customers"  table_type="active_customers"  >{t}Active Customers{/t} ({$store->get('Active Customers')})</span>

	

     </div>
*}
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
{*  <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	  <td {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='contact'}class="selected"{/if}  id="contact"  >{t}Contact{/t}</td>
	  <td {if $view=='address'}class="selected"{/if}  id="address"  >{t}Address{/t}</td>
	  <td {if $view=='balance'}class="selected"{/if}  id="balance"  >{t}Balance{/t}</td>
	  <td {if $view=='rank'}class="selected"{/if}  id="rank"  >{t}Ranking{/t}</td>
	</tr>
      </table>
*}
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

<form name="orderform" method="POST" action="pdf_send_post.php"><br>
<div align="right"><input type="submit" value="Send Post"></div>
&nbsp;&nbsp;<input type="checkbox" name="checkall" id="checkall" value="all" onclick="checkUncheckAll(this);"/> Select/Unselect All

 <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable"> </div>

</form>
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
