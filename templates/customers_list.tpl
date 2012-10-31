{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}
<input type="hidden" id="customer_list_key" value="{$customer_list_key}"/>
<div class="branch"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  {if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a  href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; <a href="customers_lists.php?store={$store->id}">{t}Lists{/t}</a> &rarr; {$customer_list_name}</span>
</div>
 <div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px">
    <div class="buttons" style="float:left">
<span class="main_title">{t}Customers List{/t}: <span class="id">{$customer_list_name}</span></span>
    </div>
  <div class="buttons">
      {if $modify}<button onclick="window.location='edit_customers.php?list_key={$customer_list_key}'" ><img src="art/icons/table_edit.png" alt=""/> {t}Edit Customers in List{/t}</button>{/if}
           <button onclick="window.location='customers_address_label.pdf.php?label=l7159&scope=list&id={$customer_list_key}'" ><img src="art/icons/printer.png" alt=""/> {t}Print{/t}</button>

      </div>
    <div style="clear:both"></div>
</div>


 <div id="the_table" class="data_table" style="clear:both;margin-top:10px">
      <div>
      <span class="clean_table_title">{t}Customers{/t}  <img id="export_data"  style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
    
 
<div class="table_top_bar">
				</div>
 <div class="clusters">
					<div class="buttons small left cluster">
	<button class="table_option {if $view=='general'}selected{/if}" id="general">{t}General{/t}</button> 
						<button class="table_option {if $view=='contact'}selected{/if}" id="contact">{t}Contact{/t}</button> 
						<button class="table_option {if $view=='address'}selected{/if}" id="address">{t}Address{/t}</button> 
						<button class="table_option {if $view=='balance'}selected{/if}" id="balance">{t}Balance{/t}</button> 
						<button class="table_option {if $view=='rank'}selected{/if}" id="rank">{t}Ranking{/t}</button>
						<button class="table_option {if $view=='weblog'}selected{/if}"  id="weblog"  >{t}WebLog{/t}</button>

	</div>
	<div style="clear:both">
					</div>
      </div>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
 <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable "> </div>
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
  
<div id="dialog_export" style="padding:15px 25px 5px 20px;">
<table class="edit" border=0>
<tr><td colspan=2><div class="buttons"><button id="export_xls" style="width:70px"><img src="art/icons/page_excel.png" alt=""> Excel</button> <button id="export_csv" style="width:70px"><img src="art/icons/page_white_text.png" alt=""> CSV</button></div></td></tr>
<tr style="height:10px"><td colspan=2></td></tr>
<tr style="font-size:85%" id="show_fields_tr"><td ><td><div class="buttons small"><button onClick="show_export_fields_dialog()">{t}Fields{/t}</button></div></td></tr>

<tbody id="export_field_list" style="display:none">
<input type="hidden" value="{$table_key}" id="table_key"/>
<tr><td><input class="field" field="Customer Key" id="export_field_customer_id" {if in_array('Customer Key',$export_fields)}checked{/if} type="checkbox"></td><td>{t}Customer ID{/t}</td></tr>
<tr><td><input class="field" field="Customer Name" id="export_field_customer_name"  {if in_array('Customer Name',$export_fields)}checked{/if} type="checkbox"></td><td>{t}Customer Name{/t}</td></tr>
<tr><td><input class="field" field="Customer Main Contact Name" id="export_field_customer_contact_name"  {if in_array('Customer Main Contact Name',$export_fields)}checked{/if} type="checkbox"></td><td>{t}Customer Contact Name{/t}</td></tr>

<tr><td><input class="field" field="Customer Main Plain Email" id="export_field_customer_email"  {if in_array('Customer Main Plain Email',$export_fields)}checked{/if} type="checkbox"></td><td>{t}Customer Email{/t}</td></tr>
<tr><td><input class="field" field="Customer Main Plain Telephone" id="export_field_customer_telephone"  {if in_array('Customer Main Plain Telephone',$export_fields)}checked{/if} type="checkbox"></td><td>{t}Customer Telephone{/t}</td></tr>
<tr><td><input class="field" field="Customer Main Address" id="export_field_customer_address" {if in_array('Customer Main Address',$export_fields)}checked{/if}  type="checkbox"></td><td>{t}Customer Address{/t}</td></tr>
</tbody>
</table>

</table>
</div>





  {include file='footer.tpl'}