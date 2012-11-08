{include file='header.tpl'}
<div id="time2_picker" class="time_picker_div"></div>
<div id="bd" >

  


<span class="nav2"><a href="suppliers.php">{t}Suppliers Index{/t}</a></span>
<span class="nav2"><a href="porders.php">{t}Orders Index{/t}</a></span>

<span class="nav2 onright" ><a href="supplier.php?id={$supplier->get('Supplier Key')}">&uarr; {$supplier->get('Supplier Code')}</a></span>


<div >
  
  <div style="padding:0px;float:right;width:350px;text-align:right;xborder: 1px solid black">
    <table class="submit_order"  border=0>
    <tr><td>{$po->get('Creation Date')}</td><td style="text-align:right">{t}Created{/t}</td></tr>
    
    <tr id="submit_ready" {if $po->get('Purchase Order Submitted Date')==''}style="display:none"{/if}  ><td id="submit_date">{$po->get('Purchase Order Submitted Date')}</td><td class="aright">{t}Submitted{/t}</td></tr>
    
    {if  $po->get('Purchase Order Submitted Date')==''}
    <tr id="submit_noready"  ><td></td><td id="submit_po" onClick="submit_order(this)" class="but">{t}Submit{/t}</td></tr>
    
    <tr id="submit_dialog" style="display:none"><td colspan=2>
	<table>
	  <tr><td>{t}Submitted Date{/t}:</td><td><input id="v_calpop1" style="text-align:right;"  class="text" name="submites_date" type="text"  size="10" maxlength="10"  value="{$date}"    /><img   id="calpop1" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   />  <div id="cal1Container" style="position:absolute;display:none; z-index:2">	</td></tr>
	  <tr><td class="aright">{t}Time{/t}:</td><td class="aright"><input id="v_time"   style="text-align:right;" class="text" name="expected_date" type="text"  size="5" maxlength="5"  value="{$time}"   /><img   id="calpop1" style="cursor:pointer" src="art/icons/time.png" align="top" alt=""   /> 	</td></tr>
	  <tr><td>{t}Expected Date{/t}:</td><td><input id="v_calpop2" style="text-align:right;"  class="text" name="expected_date" type="text"  size="10" maxlength="10"     /><img   id="calpop2" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> <div id="cal2Container" style="display:none; z-index:2;position:absolute"></div>	</td></tr>
	    <tr><td colspan=2 class="aright"><span style="cursor:pointer;margin-right:16px"  onClick="submit_order_save(this)"  >Save <img   src="art/icons/disk.png" align="top" alt=""   /></span></td></tr>
	</table>
    </td></tr>
    {/if}

    
    <tr id="expected_ready" {if $po->get('Purchase Order Estimated Receiving Date')=='' }style="display:none"{/if}   ><td id="expected_date" >{$po->get('Purchase Order Estimated Receiving Date')}</td><td  style="text-align:right">{t}Expected{/t}</td></tr>
    

    <tr  id="expected_noready"     {if $po->get('Purchase Order Submitted Date')=='' or $po->get('Purchase Order Estimated Receiving Date')!=''}style="display:none"{/if}    ><td></td><td id="set_estimated_po" class="but" onClick="change_et_order(this)">{t}Set ET{/t}</td></tr>
    <tr  id="expected_change"     {if  $po->get('Purchase Order Submitted Date')=='' or $po->get('Purchase Order Estimated Receiving Date')==''}style="display:none"{/if}    ><td></td><td id="change_edtimated_po" class="but" onClick="change_et_order(this)">{t}Change ET{/t}</td></tr>
    <tr id="expected_dialog" style="display:none;text-align:right"><td colspan=2>
	<table  style="float:right">
	  <tr><td>{t}Expected Date{/t}:</td><td><input id="v_calpop7" style="text-align:right;"  class="text" name="expected_date" type="text"  size="10" maxlength="10"     /><img   id="calpop7" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> <div id="cal7Container" style="display:none; z-index:2;position:absolute"></div>	</td></tr>
	  <tr><td colspan=2 class="aright"><span style="cursor:pointer;margin-right:16px"  onClick="et_order_save(this)"  >Save <img   src="art/icons/disk.png" align="top" alt=""   /></span></td></tr>
	</table>
    </td></tr>
    {if  $po->get('Purchase Order Submitted Date')==''}
    <tr id="delete"  ><td></td><td id="delete_po" onClick="delete_order(this)" class="but">{t}Delete{/t}</td></tr>

    {else}
    <tr  id="receive_ready"  {if $po->get('Purchase Order Received Date')==''}style="display:none"{/if}    ><td   id="receive_date"   >{$po->get('Purchase Order Received Date')}</td><td style="text-align:right">{t}Received{/t}</td></tr>
    
    <tr  id="receive_noready"  {if $po->get('Purchase Order Received Date')!=''}style="display:none"{/if}     ><td></td><td id="receive_po" class="but" onClick="receive_order(this)"  >{t}Receive{/t}</td></tr>
    

     <tr id="receive_dialog" style="display:none;text-align:right"><td colspan=2>
	<table  style="float:right">
	  <tr><td>{t}Received by{/t}:</td><td  style="text-align:right"><span id="choose_receiver" style="cursor:pointer;font-size:85%;color:#777">{t}Choose name{/t}</td></tr>
	  <tr><td colspan=2 style="text-align:right" id="receivers_name"></td></tr>
	  <tr><td colspan=2 style="text-align:right">{t}at{/t}: <input id="v_calpop3" style="text-align:right;"  class="text" name="submites_date" type="text"  size="10" maxlength="10"  value="{$date}"    /><img   id="calpop3" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> <input id="v_time3"   style="text-align:right;" class="text" name="expected_date" type="text"  size="5" maxlength="5"  value="{$time}"   /><img   id="timepop2" style="cursor:pointer" src="art/icons/time.png" align="top" alt=""   />  <div id="cal3Container" style="position:absolute;display:none; z-index:2"></div>	</td></tr>

	    <tr><td colspan=2 class="aright"><span style="cursor:pointer;margin-right:2px"  onClick="receive_order_save(this)"  >Save <img   src="art/icons/disk.png" align="top" alt=""   /></span></td></tr>
	</table>
    </td></tr>

    <tr id="check_ready"  {if $po->get('Purchase Order Checked Date')==''}style="display:none"{/if}      ><td id="check_date"  >{$po->get('Purchese Order Checked Date')}</td><td      style="text-align:right" >{t}Checked{/t}</td></tr>

    <tr id="check_noready"  {if $po->get('Purchase Order Checked Date')!='' }style="display:none"{/if}    ><td></td><td id="receive_po" class="but"   onClick="check_order(this)"   >{t}Check{/t}</td></tr>

     <tr id="check_dialog" style="display:none;text-align:right"><td colspan=2>
	<table  style="float:right">
	  <tr><td>{t}Items Cheked by{/t}:</td><td><span id="choose_checker" style="cursor:pointer;font-size:85%;color:#777">{t}Choose name{/t}</td></tr>
	   <tr><td colspan=2 style="text-align:right" id="checkers_name"></td></tr>
	  <tr><td colspan=2 style="text-align:right">{t}at{/t}: <input id="v_calpop4" style="text-align:right;"  class="text" name="submites_date" type="text"  size="10" maxlength="10"  value="{$date}"    /><img   id="calpop4" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> <input id="v_time4"   style="text-align:right;" class="text" name="expected_date" type="text"  size="5" maxlength="5"  value="{$time}"   /><img   id="calpop1" style="cursor:pointer" src="art/icons/time.png" align="top" alt=""   />  <div id="cal4Container" style="position:absolute;display:none; z-index:2"></div>	</td></tr>

	    <tr><td class="aleft"><span onClick="fill_check(this,'check')" id="check_fill">{t}Fill Check{/t}</span></td><td class="aright"><span style="cursor:pointer;margin-right:2px"  onClick="check_order_save(this)"  >Save <img   src="art/icons/disk.png" align="top" alt=""   /></span></td></tr>
	</table>
    </td></tr>


    <tr id="consolidate_ready"  {if $po->get('Purchase Order Consolidated Date')==''}style="display:none"{/if}  ><td id="consolidate_date"  >{$po->get('Purchese Order Consolidated Date')}</td><td>{t}Consolidated{/t}</td></tr>

    <tr id="consolidate_noready"  {if $po->get('Purchase Order Consolidated Date')!='' }style="display:none"{/if}   ><td></td><td id="receive_po" class="but" onClick="consolidate_order(this)"   >{t}Consolidate{/t}</td></tr>

     <tr id="consolidate_dialog" style="display:none;text-align:right"><td colspan=2>
	<table  style="float:right">
	  <tr><td>{t}Consolidated by{/t}:</td><td><span style="c">{t}Choose name{/t}</td></tr>
	  <tr><td colspan=2 style="text-align:right">{t}at{/t}: <input id="v_calpop5" style="text-align:right;"  class="text" name="submites_date" type="text"  size="10" maxlength="10"  value="{$date}"    /><img   id="calpop5" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> <input id="v_time5"   style="text-align:right;" class="text" name="expected_date" type="text"  size="5" maxlength="5"  value="{$time}"   /><img   id="calpop1" style="cursor:pointer" src="art/icons/time.png" align="top" alt=""   />  <div id="cal5Container" style="position:absolute;display:none; z-index:2"></div>	</td></tr>

	    <tr><td colspan=2 class="aright"><span style="cursor:pointer;margin-right:2px"  onClick="consolidate_order_save(this)"  >{t}Save{/t} <img   src="art/icons/disk.png" align="top" alt=""   /></span></td></tr>
	</table>
    </td></tr>
{/if}


    {if $po->get('Purchase Order Current Dispatch State')=='Cancelled'}
    <tr><td>{$po->get('Purchese Order Consolidated Date')}</td><td>{t}Cancelled{/t}</td></tr>
    {else}
    <tr {if true}style="display:none"{/if}  id="cancel_noready" ><td></td><td id="cancel_po" class="but" onClick="cancel_order(this)"   >{t}Cancel{/t}</td></tr>
    {/if}
      <tr id="cancel_dialog" style="display:none;text-align:right"><td colspan=2>
	<table  style="float:right">
	  
	  <tr><td colspan=2 class="aright">{t}Conform that you want to cancel this order{/t}</td></tr>

	  <tr><td></td><td class="aright"><span style="cursor:pointer;margin-right:2px"  onClick="cancel_order_save(this)"  >{t}Cancel{/t}</span></td></tr>
	</table>
    </td></tr>

    <tr id="options_list_row"><td></td></tr>
</table>



</div>


<div class="prodinfo" style="width:550px;margin-top:25px;xborder:1px solid black;font-size:85%">
 <table  border=1 style="float:right">
    <tr><td>{t}Goods{/t}:</td><td id="goods" class="aright">{$po->get('Items Net Amount')}</td></tr>
    <tr><td>{t}Shipping{/t}:</td><td class="aright" id="shipping"  >{$po->get('Shipping Net Amount')}</td></tr>
    <tr><td>{t}Tax{/t}:</td><td id="vat" class="aright"   >{$po->get('Total Tax Amount')}</td></tr>
    <tr><td>{t}Total{/t}</td><td id="total" class="stock aright ">{$po->get('Total Amount')}</td></tr>
    
    
  </table>

  <h1 style="padding:0px 0 10px 0;width:300px;xborder:1px solid red" id="po_title">{t}Purchase Order{/t}: {$po->get('Purchase Order Public ID')}</h1>
  <table border=0 style="float:left">
    <tr><td>{t}Purchase Order Id{/t}:</td><td class="aright">{$po->get('Purchase Order Key')}</td></tr>
    <tr><td>{t}Supplier{/t}:</td><td class="aright">{$supplier->get('Supplier Name')}</td></tr>

    <tr {if $dn_number==''}style="display:none"{/if} id="row_public_id" ><td>{t}Invoice Number{/t}:</td><td id="public_id" class="aright">{$dn_number}</td><td class="aright" id="edit_public_id" style="display:none" ><input style="text-align:right" class="text" size="7"  id="v_invoice_number"  name="invoice_number" value="{$dn_number}"  /></td></tr>
    <tr {if $tipo lt 1}style="display:none"{/if}  id="row_invoice_date"><td>{t}Invoice Date{/t}:</td><td id="invoice_date" >{$po_date_invoice}</td>
      <td class="aright" id="edit_invoice_date" style="display:none" >
	<input style="text-align:right" class="date_input" size="8" type="text"  id="v_invoice_date"  value="{$v_po_date_invoice}" name="invoice_date" />
    </td></tr>
    <tr {if $tipo!=2}style="display:none"{/if} id="row_date_received" >
      <td>{t}Time Received{/t}:</td><td id="date_received" >{$po->get('Purchase Order Received Date')}</td>
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
	  {foreach from=$options_list item=staff key=staff_id }
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
	  {foreach from=$options_list item=staff key=staff_id }
	  <option value="{$staff_id}" {if $checked_id==$staff_id}selected="selected"{/if}   >{$staff}</option>
	  {/foreach}
	</select>
      </td>
    </tr>




    <tr><td>{t}Items{/t}:</td><td class="aright" id="distinct_products">{$po->get('Purchase Order Distinct Items')}</td></tr>
  </table>

  <div style="clear:left" id="match_invoice">
    <span class="but" style="margin-left:10px" onClick="match_invoice_open()">{t}Match to Invoice{/t}</span>
  </div>

  <table  class="edit"   style="clear:left;display:none" id="match_invoice_dialog">
    <tr ><td style="text-align:left"><span class="but" style="text-align:left" onClick="match_invoice_close()">Close</span></td>
      <td><span  id="match_invoice_save"  style="cursor:pointer;display:none" onClick="match_invoice_save()">Save <img src="art/icons/disk.png" /></span></td></tr>
    <tr class="top">
      <td><span id="changed_invoice_number" class="changed" style="visibility:hidden;">*</span><img id="error_invoice_number" title="" style="visibility:hidden" src="art/icons/exclamation.png"/> {t}Invoice Number{/t}</td>
      <td><input onChange="changed(this)" style="width:7em" id="v_invoice_number" name="invoice_number" ovalue="{$po->get('Purchase Order Public ID')}" value="{$po->get('Purchase Order Public ID')}"></td></tr>
    <tr>
      <td><span id="changed_order_reference"  class="changed" style="visibility:hidden;">*</span><img id="error_order_reference" title="" style="visibility:hidden" src="art/icons/exclamation.png"/>{t}Order Reference{/t}</td>
      <td><input onChange="changed(this)" style="width:7em"  name="order_reference" id="v_order_reference" ovalue="{$po->get('Purchase Order Reference')}" value="{$po->get('Purchase Order Reference')}"></td>
    </tr>
    <tr><td>{t}Invoice Date{/t}</td><td><input id="v_invoice_date" style="width:6.5em" value="{$po->get('Purchase Order Invoice Date')}"  size="10" maxlength="10" > <img   id="calpop6" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt="" /><div id="cal6Container" style="position:absolute;display:none; z-index:2"></td></tr>

    <tr class="top">
      <td>{t}Goods Value{/t}</td>
      <td>{$currency}<span id="v_goods">{$po->get('Purchase Order Net Items Amount')}</span></td>
    </tr>
    <tr>
      <td><span id="changed_shipping" class="changed" style="visibility:hidden;">*</span><img id="error_shipping" title="" style="visibility:hidden" src="art/icons/exclamation.png"/> {t}Shipping Value{/t}</td>
      <td>{$currency}<input style="width:6em" onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thosusand_sep}',2);changed(this)"  id="v_shipping" name="shipping" value="{$po->get('Purchase Order Shipping Amount')}" ovalue="{$po->get('Purchase Order Shipping Amount')}">
    </td>
    </tr>
    <tr><td><span id="changed_charges" class="changed" style="visibility:hidden;">*</span><img id="error_charges" title="" style="visibility:hidden" src="art/icons/exclamation.png"/> {t}Charges Value{/t}</td>
      <td>{$currency}<input  style="width:6em" onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thosusand_sep}',2);changed(this)" id="v_charges" name="charges"  ovalue="{$po->get('Purchase Order Charges Amount')}"  value="{$po->get('Purchase Order Net Charges Amount')}"></td></tr>
    <tr><td><span id="changed_diff" class="changed" style="visibility:hidden;">*</span><img id="error_diff" title="" style="visibility:hidden" src="art/icons/exclamation.png"/> {t}Balance{/t}</td><td>{$currency}<input  style="width:6em" onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thosusand_sep}',2);changed(this)" id="v_diff" name="diff" ovalue="{$po->get('Purchase Order Net Adjust Amount')}" value="{$po->get('Purchase Order Net Adjust Amount')}"></td></tr>

    <tr><td><span id="changed_vat" class="changed" style="visibility:hidden;">*</span><img id="error_vat" title="" style="visibility:hidden" src="art/icons/exclamation.png"/> {t}Vat Value{/t}</td><td>{$currency}<input  style="width:6em" onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thosusand_sep}',2);changed(this)"  id="v_vat" name="vat" ovalue="{$po->get('Purchase Order Total Amount')}" value="{$po->get('Purchase Order Total Tax Amount')}"></td></tr>
    <tr><td>{t}Total{/t}</td><td>{$currency}<span id="v_total">{$po->get('Purchase Order Total Amount')}</span></td></tr>


  </table>

  

  
  <table style="clear:both;border:none;display:none" class="notes">
    <tr><td style="border:none">{t}Notes{/t}:</td><td style="border:none"><textarea id="v_note" rows="2" cols="60" ></textarea></td></tr>
  </table>
  

</div>
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
	<tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Discounts{/t}</td>
	  <td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Properties{/t}</td>
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

  
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div class="clean_table_filter" {if !$show_all}style="visibility:hidden"{/if} id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{t}Product Code{/t}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" {if !$show_all}style="visibility:hidden"{/if}  id="clean_table_controls0" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
  </div>
  <div  id="table0"  style="font-size:80%" class="data_table_container dtable btable"> </div>
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


<div id="receiver_list" class="yuimenu options_list" style="display:none" >
  <div class="bd">
    <table border=1>
      {foreach from=$staff item=_staff name=foo}
      {if $_staff.mod==0}<tr>{/if}
	<td staff_id="{$_staff.id}" id="receivers{$_staff.id}" onClick="select_staff(this,event,'receivers')" >{$_staff.alias}</td>
	{if $_staff.mod==$staff_cols}</tr>{/if}
      {/foreach}
    </table>
  </div>
</div>

<div id="checker_list" class="yuimenu options_list" style="display:none" >
  <div class="bd">
    <table border=1>
      {foreach from=$staff item=_staff name=foo}
      {if $_staff.mod==0}<tr>{/if}
	<td staff_id="{$_staff.id}" id="checkers{$_staff.id}" onClick="select_staff(this,event,'checkers')" >{$_staff.alias}</td>
	{if $_staff.mod==$staff_cols}</tr>{/if}
      {/foreach}
    </table>
  </div>
</div>



{include file='footer.tpl'}

