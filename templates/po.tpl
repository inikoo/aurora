{include file='header.tpl'}
<div id="time2_picker" class="time_picker_div"></div>
<div id="bd" >

  
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal2Container"></div>
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal3Container"></div>

<span class="nav2"><a href="suppliers.php">{$home}</a></span>
<span class="nav2"><a href="supplier.php?id={$supplier_id}">{$name}</a></span>


  <div id="yui-main">

    <div style="float:right;width:250px;margin-top:0px;text-align:right">
      {if $new==1}
      <p>
      {t escape=no}To add items download this <a href="templates_dn.php">template</a>, and upload it.{/t}
    </p>

      <button id="upload">{t}Upload Items{/t}</button>
      <br/> <br/>
      <button id="submit_dn"  >{t}Submit Deliver Note{/t}</button>
      {/if}

      <table style="float:right;text-align:right" >
	<tr><td>{$po_date_creation}</td><td>{t}Created{/t}</td></tr>
	<tr><td>{$po_date_submited}</td><td>{if $po_date_submited!=''}{t}Submited{/t}{else}<span id="submit_po" style="cursor:pointer">{t}Submit{/t}</span>{/if}</td></tr>
	<tr><td colspan=2>
	    <table >
	    <tr><td>{t}Submited Date{/t}:</td><td><input id="v_calpop1"   class="text" name="submites_date" type="text"  size="10" maxlength="10"  value="{$now}"    /><img   id="calpop1" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> 	</td></tr>
	    <tr><td class="aright">{t}Time{/t}:</td><td class="aright"><input id="v_calpop1"   class="text" name="expected_date" type="text"  size="5" maxlength="5"     /><img   id="calpop1" style="cursor:pointer" src="art/icons/time.png" align="top" alt=""   /> 	</td></tr>
	    <tr><td>{t}Expected Date{/t}:</td><td><input id="v_calpop2"   class="text" name="expected_date" type="text"  size="10" maxlength="10"     /><img   id="calpop1" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> 	</td></tr>
	    <tr><td colspan=2 class="aright"><span style="cursor:pointer;margin-right:16px">Save <img   src="art/icons/disk.png" align="top" alt=""   /></span></td></tr>
	    </table>
	</td></tr>
	<tr {if $po_date_submited==''}style="display:none"{/if}><td>{$po_date_expected}</td><td>{t}Expected{/t}</td></tr>
	<tr ><td></dt><td>{if $po_date_received!=''}{t}Received{/t}{else}<span id="receive_po" style="cursor:pointer">{t}Receive{/t}</span>{/if}</td></tr>
<tr><td></dt><td>{if $po_date_cancelled!=''}{t}Cancelled{/t}{else}<span id="cancelled_po" style="cursor:pointer">{t}Cancel{/t}</span>{/if}</td></tr>
	     

	   </table>
    </div>

    <div class="yui-b" >
      <div class="prodinfo" style="margin-left:20px;width:700px;margin-top:25px;border:1px solid black;font-size:85%">
	<h1>{$title}</h1>


	<table border=1 style="float:left">
	  <tr><td>{t}Purchase Order Id{/t}:</td><td class="aright">{$po_number}</td></tr>
	  <tr><td>{t}Created{/t}:</td><td class="aright">{$po_date_creation}</td></tr>


	  {if $tipo>0}
	  <tr><td>{t}Submitted{/t}:</td><td class="aright">{$po_date_submited}</td></tr>
	  {/if}
	  {if $tipo==1}<tr><td>{t}Expected{/t}:</td><td id="date_expected" class="aright">{$po_date_expected}</td></tr>{/if}
	  
	  
	  
	  <tr {if $dn_number==''}style="display:none"{/if} id="row_public_id" ><td>{t}Invoice Number{/t}:</td><td id="public_id" class="aright">{$dn_number}</td><td class="aright" id="edit_public_id" style="display:none" ><input style="text-align:right" class="text" size="7"  id="v_invoice_number"  name="invoice_number" value="{$dn_number}"  /></td></tr>
	  <tr {if $tipo<1}style="display:none"{/if}  id="row_invoice_date"><td>{t}Invoice Date{/t}:</td><td id="invoice_date" >{$po_date_invoice}</td>
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
	  
<tr><td>{t}Items{/t}:</td><td class="aright" id="distinct_products">{$items}</td></tr>
	  </table>


	
	

	<table  border=1 style="float:right">

	  <tr><td>{t}Goods{/t}:</td><td id="goods" class="aright">{$value_goods}</td></tr>
	  <tr><td>{t}Shipping{/t}:</td><td class="aright" id="shipping"  >{$value_shipping}</td>
	    <td  id="edit_shipping" style="display:none" > {$currency}
	      <input style="text-align:right" class="text" size="7"  id="v_shipping"  value="{$nm_value_shipping}" name="shipping"  />{$decimal_point}
	      <input style="text-align:right" maxlength="2" id="v_shipping_c" class="text" size="1" name="shipping"  value="{$nc_value_shipping}"/></td></tr>
	  <tr> <tr><td>{t}Vat{/t}:</td><td id="vat" class="aright"   >{$value_vat}</td>
	    <td id="edit_vat" style="display:none"  >{$currency}
	      <input style="text-align:right" class="text" size="7"  id="v_vat" value="{$nm_value_vat}" name="vat" />{$decimal_point}
	      <input  maxlength="2" style="text-align:right" id="v_vat_c" class="text" size="1"  value="{$nc_value_vat}"  name="vat"  /></td></tr>
	  <tr id="other_charge"  {if $n_value_other==0}style="display:none"{/if} ><td >{t}Other{/t}:</td><td class="aright"  id="other"  >{$value_other}</td>
	    <td id="edit_other" style="display:none">{$currency}


	      <input style="text-align:right" class="text" size="7"  id="v_other" value="{$nm_value_other}"   name="other" />{$decimal_point}
	      <input  style="text-align:right" maxlength="2" id="v_other_c" class="text" size="1"   value="{$nc_value_other}"  name="other"   /></td>

	  </tr>
	  <tr  {if $n_value_dif==0}style="display:none"{/if}  ><td>{t}Diff{/t}:</td><td class="stock aright" style="background:red">{$value_dif}</td></tr>
	  <tr>
	    <td>{t}Total{/t}</td><td id="total" class="stock aright ">{$value_total}</td>
	     <td  id="edit_total" style="display:none" >  {$currency}
	    <input style="text-align:right" class="text" size="7"  id="v_total" value="{$nm_value_total}" />{$decimal_point}
	      <input   maxlength="2" style="text-align:right" id="v_total_c" class="text" size="1"   value="{$nc_value_total}" /></td>
	  </tr>

	</table>

      	<table style="clear:both;border:none;" class="notes">
	  <tr><td style="border:none">{t}Notes{/t}:</td><td style="border:none"><textarea id="v_note" rows="2" cols="60" ></textarea></td></tr>
	</table>
	

      </div>



      <div id="the_table" class="data_table" style="margin:20px 20px;clear:both">
	<span class="clean_table_title">{t}Products{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div id="table_info0" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg0"></span></div></div>
	  <div class="clean_table_filter"><div class="clean_table_info"><span class="clean_table_add_items {if $items==0}selected{/if}"  >{if $items>0}{t}Add Products{/t}{else}{t}Adding Products Mode{/t}{/if}</span> <span id="filter_name0">{t}Product Code{/t}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
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



  </div>
</div> 



{include file='footer.tpl'}

