{include file='header.tpl'}
<div id="time2_picker" class="time_picker_div"></div>

<div id="bd" >
<div id="cal1Container" style="position:absolute;left:610px;top:120px;display:none;z-index:3"></div>

<div class="order_actions" >
    <span class="state_details" onClick="location.href='supplier.php?id={$supplier->get('Supplier Key')}'" style="float:left;margin-top:2px" >{t}Supplier Page{/t}</span>

  <span class="state_details" id="delete_po">{t}Delete{/t}</span>
  <span class="state_details" id="submit_po" style="margin-left:20px">{t}Submit{/t}</span>
   
</div>


<div class="prodinfo" style="margin-top:2px;font-size:85%;border:1px solid #ddd;padding:10px">
    
    

    <table style="width:200px;" class="order_header" >
      <tr><td>{t}Goods{/t}:</td><td id="goods" class="aright">{$po->get('Items Net Amount')}</td></tr>
      <tr><td>{t}Shipping{/t}:</td><td class="aright" id="shipping"  >{$po->get('Shipping Net Amount')}</td></tr>
      <tr><td>{t}Tax{/t}:</td><td id="vat" class="aright"   >{$po->get('Total Tax Amount')}</td></tr>
      <tr><td>{t}Total{/t}</td><td id="total" class="stock aright ">{$po->get('Total Amount')}</td></tr>
    
    </table>
    
    
    <div style="border:0px solid red;width:290px;float:right">
    <table  border=0  class="order_header"  style="margin-right:30px;float:right">
      <tr><td class="aright" style="padding-right:40px">{t}Created{/t}:</td><td>{$po->get('Creation Date')}</td></tr>
    
    </table>
    </div>
    
    
    <h1 style="padding:0px 0 10px 0;width:300px;xborder:1px solid red" id="po_title">{t}Purchase Order{/t}: {$po->get('Purchase Order Public ID')}</h1>
    <table border=0 >
      <tr><td>{t}Purchase Order Id{/t}:</td><td class="aright">{$po->get('Purchase Order Key')}</td></tr>
      <tr><td>{t}Supplier{/t}:</td><td class="aright"><a href="supplier.php?id={$supplier->get('Supplier Key')}">{$supplier->get('Supplier Name')}</a></td></tr>
      <tr><td>{t}Items{/t}:</td><td class="aright" id="distinct_products">{$po->get('Number Items')}</td></tr>
    </table>

  
    <table style="clear:both;border:none;display:none" class="notes">
      
      <tr><td style="border:none">{t}Notes{/t}:</td><td style="border:none"><textarea id="v_note" rows="2" cols="60" ></textarea></td></tr>
    </table>
    
  <div style="clear:both"></div>
  
</div>





<div id="the_table" class="data_table" style="margin:20px 0px;clear:both">
  <span class="clean_table_title">{t}Supplier Products{/t}</span>
  	<div id="table_type">
	  <span id="table_type_list" style="float:right;color:brown" class="table_type state_details {if $table_type=='list'}state_details_selected{/if}">{t}Recomended Order{/t}</span>
	</div>
	
	<div id="todelete" style="display:none">
	  <span onClick="swap_show_items(this)"  status="{$status}"  id="show_items" class="but {if !$show_all}selected{/if}  ">Items</span>
	  <span onClick="swap_show_all_products(this)" status="{$status}" {if $status!=0}style="display:none"{/if} id="show_all_products"  class="but {if $show_all}selected{/if}">Show all supplier products</span>
	  <span onClick="swap_show_all_products(this,1)" style="display:none" id="show_amend"  class="but">Amend order</span>
	  <span onClick="swap_item_found(this)" style="display:none" id="show_found"  class="but">Add Product Found in Delivery</span>
	  <span onClick="swap_new_item_found(this)" style="display:none" id="show_new_found"  class="but">Undentificated Product Found in Delivery</span>
	</div>

	<div id="list_options0"> 
	  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
	  <span   style="float:right;margin-left:20px" class="state_details" state="{$show_all}"  id="show_all"  atitle="{if !$show_all}{t}Show only ordered{/t}{else}{t}Show all products available{/t}{/if}"  >{if $show_all}{t}Show only ordered{/t}{else}{t}Show all products available{/t}{/if}</span>     
	  
	  <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
	    <tr><td  {if $view=='used_in'}class="selected"{/if} id="general" >{t}Used In{/t}</td>
	      <td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>
	  <td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>
	    </tr>
	  </table>
	  <table id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	    <tr>
	  <td  {if $period=='all'}class="selected"{/if} period="all"  id="period_all" >{t}All{/t}</td>
	  <td {if $period=='year'}class="selected"{/if}  period="year"  id="period_year"  >{t}1Yr{/t}</td>
	  <td  {if $period=='quarter'}class="selected"{/if}  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
	  <td {if $period=='month'}class="selected"{/if}  period="month"  id="period_month"  >{t}1M{/t}</td>
	  <td  {if $period=='week'}class="selected"{/if} period="week"  id="period_week"  >{t}1W{/t}</td>
	</tr>
      </table>
      <table  id="avg_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td {if $avg=='totals'}class="selected"{/if} avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	  <td {if $avg=='month'}class="selected"{/if}  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	  <td {if $avg=='week'}class="selected"{/if}  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>
	  <td {if $avg=='month_eff'}class="selected"{/if} style="display:none" avg="month_eff"  id="avg_month_eff"  >{t}M EAVG{/t}</td>
	  <td {if $avg=='week_eff'}class="selected"{/if} style="display:none"  avg="week_eff"  id="avg_week_eff"  >{t}W EAVG{/t}</td>
	</tr>
      </table>
    </div>

  
  {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
  <div  id="table0"  style="font-size:80%" class="data_table_container dtable btable"> </div>
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


<div id="delete_dialog" class="nicebox">
<div class="bd" >
  <div id="delete_dialog_msg" class="dialog_msg" style="padding:0 0 10px 0 ">{t}Note: this action can not be undone{/t}.</div>
  <table style="width:250px">
   
    <tr><td style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0">
	<button onClick="delete_order()"  >{t}Delete Purchase Order{/t}</button>
    
    </td>
</tr>
  </table>
  </div>
</div>


<div id="submit_dialog" class="nicebox">
<div class="bd" >
  <div id="submit_dialog_msg"></div>
  <table>
    <tr>
      <td class="label">{t}Submit Method{/t}:</td><td>
	<div class="options" style="margin:0px 0;width:200px" id="submit_method_container">
	  <input type="hidden" value="{$submit_method_default}" ovalue="{$submit_method_default}" id="submit_method"  >
	  {foreach from=$submit_method item=unit_tipo key=name} <span style="float:left;margin-bottom:5px;margin-right:5px" class="radio{if $unit_tipo.selected} selected{/if}"  id="radio_shelf_type_{$name}" radio_value="{$name}">{$unit_tipo.fname}</span> {/foreach}
	</div>
      </td>
    </tr>
    <input type="hidden" id="date_type" value="now"/>
    <tr id="tr_manual_submit_date">
      <td class="label" >{t}Submit Date{/t}:</td><td><input id="v_calpop1" style="text-align:right;"  class="text" name="submites_date" type="text"  size="10" maxlength="10"  value="{$date}"    /><img   id="calpop1" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   />  </td>
    </tr>
      
    <tr >
        <input type="hidden" id="submitted_by" value="{$user_staff_key}"/>

      <td class="label" ><img class="edit_mini_button" id="get_submiter" src="art/icons/edit.gif" alt="({t}edit{/t})"/> {t}Submit By{/t}:</td><td   ><span id="submited_by_alias" class="value" >{$user}</span></td>
    </tr>

    <tr><td colspan=2 style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0">
	<button  onClick="submit_order_save(this)"  >{t}Save{/t}</button>
    
    </td>
</tr>
  </table>
  </div>
</div>


<div id="staff_dialog" class="yuimenu options_list"  >
  <div class="bd">
    <table border=1>
      {foreach from=$staff item=_staff name=foo}
      {if $_staff.mod==0}<tr>{/if}
	<td staff_id="{$_staff.id}" id="receivers{$_staff.id}" onClick="select_staff(this,event)" >{$_staff.alias}</td>
	{if $_staff.mod==$staff_cols}</tr>{/if}
      {/foreach}
    </table>
<span class="state_details" style="float:right" onClick="close_dialog('staff')" >{t}Close{/t}</span>
  </div>
</div>

{include file='footer.tpl'}

