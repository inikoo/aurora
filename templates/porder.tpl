{include file='header.tpl'}
<div id="time2_picker" class="time_picker_div"></div>
<div id="bd" >

  


<span class="nav2"><a href="suppliers.php">{t}Suppliers Index{/t}</a></span>
<span class="nav2"><a href="porders.php">{t}Orders Index{/t}</a></span>

<span class="nav2 onright" ><a href="supplier.php?id={$po.supplier_id}">&uarr; {$supplier.code}</a></span>


<div >

<div style="padding:0px;float:right;width:350px;margin:0px 20px;text-align:right;xborder: 1px solid black">
  <table class="submit_order"  border=0>
    <tr><td>{$po.dates.created}</td><td style="text-align:right">{t}Created{/t}</td></tr>

    <tr id="submit_ready" {if $po.date_submited==''}style="display:none"{/if}  ><td id="submit_date">{$po.dates.submited}</td><td class="aright">{t}Submited{/t}</td></tr>
    
    <tr id="submit_noready" {if !$po.date_submited=='' or $po.status_id>=80}style="display:none"{/if} ><td></td><td id="submit_po" onClick="submit_order(this)" class="but">{t}Submit{/t}</td></tr>
   
    <tr id="submit_dialog" style="display:none"><td colspan=2>
	<table >
	  <tr><td>{t}Submited Date{/t}:</td><td><input id="v_calpop1" style="text-align:right;"  class="text" name="submites_date" type="text"  size="10" maxlength="10"  value="{$date}"    /><img   id="calpop1" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   />  <div id="cal1Container" style="position:absolute;display:none; z-index:2">	</td></tr>
	  <tr><td class="aright">{t}Time{/t}:</td><td class="aright"><input id="v_time"   style="text-align:right;" class="text" name="expected_date" type="text"  size="5" maxlength="5"  value="{$time}"   /><img   id="calpop1" style="cursor:pointer" src="art/icons/time.png" align="top" alt=""   /> 	</td></tr>
	  <tr><td>{t}Expected Date{/t}:</td><td><input id="v_calpop2" style="text-align:right;"  class="text" name="expected_date" type="text"  size="10" maxlength="10"     /><img   id="calpop2" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> <div id="cal2Container" style="display:none; z-index:2;position:absolute"></div>	</td></tr>
	    <tr><td colspan=2 class="aright"><span style="cursor:pointer;margin-right:16px"  onClick="submit_order_save(this)"  >Save <img   src="art/icons/disk.png" align="top" alt=""   /></span></td></tr>
	</table>
    </td></tr>

    <tr id="expected_ready" {if $po.date_expected=='' or $po.date_received!=''  }style="display:none"{/if}   ><td id="expected_date" >{$po.dates.expected}</td><td  style="text-align:right">{t}Expected{/t}</td></tr>
   

    <tr  id="expected_noready"     {if $po.date_submited=='' or $po.date_expected!='' or $po.status_id>=80}style="display:none"{/if}    ><td></td><td id="set_estimated_po" class="but" onClick="change_et_order(this)">{t}Set ET{/t}</td></tr>
    <tr  id="expected_change"     {if $po.date_submited==''  or $po.date_expected=='' or $po.status_id>=80}style="display:none"{/if}    ><td></td><td id="change_edtimated_po" class="but" onClick="change_et_order(this)">{t}Change ET{/t}</td></tr>
     <tr id="expected_dialog" style="display:none;text-align:right"><td colspan=2>
	<table border=1 style="float:right">
	  <tr><td>{t}Expected Date{/t}:</td><td><input id="v_calpop7" style="text-align:right;"  class="text" name="expected_date" type="text"  size="10" maxlength="10"     /><img   id="calpop7" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> <div id="cal7Container" style="display:none; z-index:2;position:absolute"></div>	</td></tr>
	    <tr><td colspan=2 class="aright"><span style="cursor:pointer;margin-right:16px"  onClick="et_order_save(this)"  >Save <img   src="art/icons/disk.png" align="top" alt=""   /></span></td></tr>
	</table>
    </td></tr>


    <tr  id="receive_ready"  {if $po.date_received==''}style="display:none"{/if}    ><td   id="receive_date"   >{$po.dates.received}</td><td style="text-align:right">{t}Received{/t}</td></tr>

    <tr  id="receive_noready"  {if $po.date_received!=''}style="display:none"{/if}     ><td></td><td id="receive_po" class="but" onClick="receive_order(this)"  >{t}Receive{/t}</td></tr>


     <tr id="receive_dialog" style="display:none;text-align:right"><td colspan=2>
	<table  style="float:right">
	  <tr><td>{t}Received by{/t}:</td><td  style="text-align:right"><span id="choose_receiver" style="cursor:pointer;font-size:85%;color:#777">{t}Choose name{/t}</td></tr>
	  <tr><td colspan=2 style="text-align:right" id="receivers_name"></td></tr>
	  <tr><td colspan=2 style="text-align:right">{t}at{/t}: <input id="v_calpop3" style="text-align:right;"  class="text" name="submites_date" type="text"  size="10" maxlength="10"  value="{$date}"    /><img   id="calpop3" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> <input id="v_time3"   style="text-align:right;" class="text" name="expected_date" type="text"  size="5" maxlength="5"  value="{$time}"   /><img   id="timepop2" style="cursor:pointer" src="art/icons/time.png" align="top" alt=""   />  <div id="cal3Container" style="position:absolute;display:none; z-index:2"></div>	</td></tr>

	    <tr><td colspan=2 class="aright"><span style="cursor:pointer;margin-right:2px"  onClick="receive_order_save(this)"  >Save <img   src="art/icons/disk.png" align="top" alt=""   /></span></td></tr>
	</table>
    </td></tr>

    <tr id="check_ready"  {if $po.date_checked==''}style="display:none"{/if}      ><td id="check_date"  >{$po.dates.checked}</td><td      style="text-align:right" >{t}Checked{/t}</td></tr>

    <tr id="check_noready"  {if $po.date_checked!='' or $po.status_id<80}style="display:none"{/if}    ><td></td><td id="receive_po" class="but"   onClick="check_order(this)"   >{t}Check{/t}</td></tr>

     <tr id="check_dialog" style="display:none;text-align:right"><td colspan=2>
	<table border=1 style="float:right">
	  <tr><td>{t}Items Cheked by{/t}:</td><td><span id="choose_checker" style="cursor:pointer;font-size:85%;color:#777">{t}Choose name{/t}</td></tr>
	   <tr><td colspan=2 style="text-align:right" id="checkers_name"></td></tr>
	  <tr><td colspan=2 style="text-align:right">{t}at{/t}: <input id="v_calpop4" style="text-align:right;"  class="text" name="submites_date" type="text"  size="10" maxlength="10"  value="{$date}"    /><img   id="calpop4" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> <input id="v_time4"   style="text-align:right;" class="text" name="expected_date" type="text"  size="5" maxlength="5"  value="{$time}"   /><img   id="calpop1" style="cursor:pointer" src="art/icons/time.png" align="top" alt=""   />  <div id="cal4Container" style="position:absolute;display:none; z-index:2"></div>	</td></tr>

	    <tr><td class="aleft"><span onClick="fill_check(this,'check')" id="check_fill">{t}{/t}</span></td><td class="aright"><span style="cursor:pointer;margin-right:2px"  onClick="check_order_save(this)"  >Save <img   src="art/icons/disk.png" align="top" alt=""   /></span></td></tr>
	</table>
    </td></tr>


    <tr id="consolidate_ready"  {if $po.date_consolidated==''}style="display:none"{/if}  ><td id="consolidate_date"  >{$po_dates.consolidated}</td><td>{t}Consolidated{/t}</td></tr>

    <tr id="consolidate_noready"  {if $po.date_consolidated!='' or $po.status_id<90 }style="display:none"{/if}   ><td></td><td id="receive_po" class="but" onClick="consolidate_order(this)"   >{t}Consolidate{/t}</td></tr>

     <tr id="consolidate_dialog" style="display:none;text-align:right"><td colspan=2>
	<table border=1 style="float:right">
	  <tr><td>{t}Consolidated by{/t}:</td><td><span style="c">{t}Choose name{/t}</td></tr>
	  <tr><td colspan=2 style="text-align:right">{t}at{/t}: <input id="v_calpop5" style="text-align:right;"  class="text" name="submites_date" type="text"  size="10" maxlength="10"  value="{$date}"    /><img   id="calpop5" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> <input id="v_time5"   style="text-align:right;" class="text" name="expected_date" type="text"  size="5" maxlength="5"  value="{$time}"   /><img   id="calpop1" style="cursor:pointer" src="art/icons/time.png" align="top" alt=""   />  <div id="cal5Container" style="position:absolute;display:none; z-index:2"></div>	</td></tr>

	    <tr><td colspan=2 class="aright"><span style="cursor:pointer;margin-right:2px"  onClick="consolidate_order_save(this)"  >{t}Save{/t} <img   src="art/icons/disk.png" align="top" alt=""   /></span></td></tr>
	</table>
    </td></tr>
    {if $po.cancelled!=''}
    <tr><td>{$po_dates.cancelled}</td><td>{t}Cancelled{/t}</td></tr>
    {else}
    <tr {if $po.status_id>=80}style="display:none"{/if}  id="cancel_noready" ><td></td><td id="cancel_po" class="but" onClick="cancel_order(this)"   >{t}Cancel{/t}</td></tr>
    {/if}
      <tr id="cancel_dialog" style="display:none;text-align:right"><td colspan=2>
	<table  style="float:right">
	  
	  <tr><td colspan=2 class="aright">{t}Conform that you want to cancel this order{/t}</td></tr>

	  <tr><td></td><td class="aright"><span style="cursor:pointer;margin-right:2px"  onClick="cancel_order_save(this)"  >{t}Cancel{/t}</span></td></tr>
	</table>
    </td></tr>

    <tr id="staff_list_row"><td></td></tr>
</table>
</div>


<div class="prodinfo" style="margin-left:20px;width:550px;margin-top:25px;border:1px solid black;font-size:85%">
 <table  border=1 style="float:right">
    <tr><td>{t}Goods{/t}:</td><td id="goods" class="aright">{$po.money.goods}</td></tr>
    <tr><td>{t}Shipping{/t}:</td><td class="aright" id="shipping"  >{$po.money.shipping}</td>
      <td  id="edit_shipping" style="display:none" > {$currency}
	<input style="text-align:right" class="text" size="7"  id="v_shipping"  value="{$nm_value_shipping}" name="shipping"  />{$decimal_point}
	<input style="text-align:right" maxlength="2" id="v_shipping_c" class="text" size="1" name="shipping"  value="{$nc_value_shipping}"/></td></tr>
    <tr> <tr><td>{t}Vat{/t}:</td><td id="vat" class="aright"   >{$po.money.vat}</td>
      <td id="edit_vat" style="display:none"  >{$po.vat}
	<input style="text-align:right" class="text" size="7"  id="v_vat" value="{$nm_value_vat}" name="vat" />{$decimal_point}
	<input  maxlength="2" style="text-align:right" id="v_vat_c" class="text" size="1"  value="{$nc_value_vat}"  name="vat"  /></td></tr>
    <tr id="other_charge"  {if $n_value_other==0}style="display:none"{/if} ><td >{t}Other{/t}:</td><td class="aright"  id="other"  >{$value_other}</td>
      <td id="edit_other" style="display:none">{$currency}
	

	<input style="text-align:right" class="text" size="7"  id="v_other" value="{$nm_value_other}"   name="other" />{$decimal_point}
	<input  style="text-align:right" maxlength="2" id="v_other_c" class="text" size="1"   value="{$nc_value_other}"  name="other"   /></td>
      
    </tr>
    <tr  {if $n_value_dif==0}style="display:none"{/if}  ><td>{t}Diff{/t}:</td><td class="stock aright" style="background:red">{$value_dif}</td></tr>
    <tr>
      <td>{t}Total{/t}</td><td id="total" class="stock aright ">{$po.money.total}</td>
      <td  id="edit_total" style="display:none" >  {$currency}
	<input style="text-align:right" class="text" size="7"  id="v_total" value="{$nm_value_total}" />{$decimal_point}
	      <input   maxlength="2" style="text-align:right" id="v_total_c" class="text" size="1"   value="{$nc_value_total}" /></td>
    </tr>
    
  </table>

  <h1 style="padding:0px 0 10px 0;width:300px;xborder:1px solid red" id="po_title">{$title}</h1>
  <table border=1 style="float:left">
    <tr><td>{t}Purchase Order Id{/t}:</td><td class="aright">{$po.id}</td></tr>
    <tr {if $dn_number==''}style="display:none"{/if} id="row_public_id" ><td>{t}Invoice Number{/t}:</td><td id="public_id" class="aright">{$dn_number}</td><td class="aright" id="edit_public_id" style="display:none" ><input style="text-align:right" class="text" size="7"  id="v_invoice_number"  name="invoice_number" value="{$dn_number}"  /></td></tr>
    <tr {if $tipo lt 1}style="display:none"{/if}  id="row_invoice_date"><td>{t}Invoice Date{/t}:</td><td id="invoice_date" >{$po_date_invoice}</td>
      <td class="aright" id="edit_invoice_date" style="display:none" >
	<input style="text-align:right" class="date_input" size="8" type="text"  id="v_invoice_date"  value="{$v_po_date_invoice}" name="invoice_date" />
    </td></tr>
    <tr {if $tipo!=2}style="display:none"{/if} id="row_date_received" >
      <td>{t}Time Received{/t}:</td><td id="date_received" >{$po_datetime_received}</td>
      <td class="aright" id="edit_date_received" style="display:none" >
	<input  type="text" class="date_input" size="8"  id="v_date_received"  value="{$v_po_date_received}"  name="date_received"  />
	<input  type="text" class="time_input" size="3"  name="time_received" id="v_time_received"  value="{$v_po_time_received}"  />
	
      </td>
    </tr>
    <tr {if $tipo!=2}style="display:none"{/if} id="row_received_by"  >
      <td>{t}Received by{/t}:</td>	
      <td id="received_by" >{$received_by}</td>
      <td class="aright" id="edit_received_by" style="display:none" >
	<select name="received_by"  id="v_received_by" >
	  {foreach from=$staff_list item=staff key=staff_id }
	  <option value="{$staff_id}" {if $received_id==$staff_id}selected="selected"{/if}   >{$staff}</option>
	  {/foreach}
	</select>
      </td>
    </tr>
    <tr {if $tipo!=2}style="display:none"{/if} id="row_checked_by"  >
      <td>{t}Checked by{/t}:</td>	
      <td id="checked_by" >{$checked_by}</td>
      <td class="aright" id="edit_checked_by" style="display:none" >
	<select name="checked_by"  id="v_checked_by" >
	  {foreach from=$staff_list item=staff key=staff_id }
	  <option value="{$staff_id}" {if $checked_id==$staff_id}selected="selected"{/if}   >{$staff}</option>
	  {/foreach}
	</select>
      </td>
    </tr>
    
    <tr><td>{t}Items{/t}:</td><td class="aright" id="distinct_products">{$po.items}</td></tr>
  </table>
  
  <table style="clear:left"  border=1>
    <tr><td>{t}Invoice Number{/t}</td></tr>
    <tr><td>{t}Invoice Date{/t}</td></tr>
    <tr><td>{t}Order Reference{/t}</td></tr>
    <tr><td>{t}Goods Value{/t}</td></tr>
    

  </table>

  

  
  <table style="clear:both;border:none;display:none" class="notes">
    <tr><td style="border:none">{t}Notes{/t}:</td><td style="border:none"><textarea id="v_note" rows="2" cols="60" ></textarea></td></tr>
  </table>
  

</div>
<div style="clear:both"></div>
</div>



<div id="the_table" class="data_table" style="margin:20px 20px;clear:both">
  <span class="clean_table_title">{t}Products{/t}</span>
  <span onClick="swap_show_items(this)"  status="{$status}"  id="show_items" class="but {if !$show_all}selected{/if}  ">Items</span>
  <span onClick="swap_show_all_products(this,1)" {if $status!=0}style="display:none"{/if} id="show_all_products"  class="but {if $show_all}selected{/if}">Show all supplier products</span>
  <span onClick="swap_show_all_products(this,1)" style="display:none" id="show_amend"  class="but">Amend order</span>
  <span onClick="swap_item_found(this)" style="display:none" id="show_found"  class="but">Add Product Found in Delivery</span>
  <span onClick="swap_new_item_found(this)" style="display:none" id="show_new_found"  class="but">Undentificated Product Found in Delivery</span>

  
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div class="clean_table_filter" {if !$show_all}style="visibility:hidden"{/if} id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{t}Product Code{/t}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" {if !$show_all}style="visibility:hidden"{/if}  id="clean_table_controls0" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
  </div>
  <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>
{if $items>0}
<div  id="table0" class="dtable btable" style="margin-bottom:0"></div>
<div    style="border:1px solid #ccc;width:200px;float:right;padding:0;margin:0;border-top:none;">
  <table border=0  style="width:100%,padding:0;margin:0;float:right" >
    <tr><td  class="aright" >{t}Order Cost{/t}</td><td width=100 class="aright">{$value_goods}</td></tr>
    {if $credit!=0  }<tr><td  class="aright" >{t}Credits{/t}</td><td width=100 class="aright">{$value_credit}</td></tr>{/if}
    {if $others!=0  }<tr><td  class="aright" >{t}Charges{/t}</td><td width=100 class="aright">{$value_others}</td></tr>{/if}
    <tr><td  class="aright" >{t}Shipping{/t}</td><td width=100 class="aright">{$value_shipping}</td></tr>
    <tr><td  class="aright" >{t}VAT{/t}</td><td width=100 class="aright">{$value_vat}</td></tr>
    <tr><td  class="aright" >{t}Total{/t}</td><td width=100 class="aright">{$value_total}</td></tr>
  </table>
</div>
{/if}
</div> 

<div id="filtermenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


<div id="receiver_list" class="yuimenu staff_list"  >
  <div class="bd">
    <table border=1>
      {foreach from=$staff item=_staff name=foo}
      {if $_staff.mod==0}<tr>{/if}
	<td staff_id="{$_staff.id}" id="receivers{$_staff.id}" onClick="select_staff(this,event,'receivers')" >{$_staff.alias}</td>
	{if $_staff.mod==4}</tr>{/if}
      {/foreach}
    </table>
  </div>
</div>

<div id="checker_list" class="yuimenu staff_list"  >
  <div class="bd">
    <table border=1>
      {foreach from=$staff item=_staff name=foo}
      {if $_staff.mod==0}<tr>{/if}
	<td staff_id="{$_staff.id}" id="checkers{$_staff.id}" onClick="select_staff(this,event,'checkers')" >{$_staff.alias}</td>
	{if $_staff.mod==4}</tr>{/if}
      {/foreach}
    </table>
  </div>
</div>



{include file='footer.tpl'}

