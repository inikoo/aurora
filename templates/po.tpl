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
       <table class="options" >
	 <tr><td {if $view_all==1 }class="selected"{/if}    id="but_view0" >{t}All products{/t}</td></tr>
       </table>

	   <table class="but"  id="buts">
	     
	      {if $tipo==0}<tr><td id="submiting">{t}Submit{/t}</td><td id="deleting" class="edit" >{t}Delete{/t}</td>  </tr>{/if}
	      {if $tipo==1}<tr><td id="receiving">{t}Receive{/t}</td><td id="canceling" class="edit" >{t}Cancel{/t}</td>  </tr>{/if}
	      {if $tipo==2}<tr><td id="returning" class="edit" >{t}Cancel Deliver{/t}</td>  </tr>{/if}
	      {if $tipo==3}<tr><td id="deleting" class="edit" >{t}Delete{/t}</td>  </tr>{/if}

	   </table>
    </div>

    <div class="yui-b" >
      <fieldset class="prodinfo" style="width:700px;margin-top:25px">
	<legend>{$title}</legend>


	<table >
	  <tr><td>{t}Purchase Order Id{/t}:</td><td class="aright">{$po_number}</td></tr>
	  <tr><td>{t}Created{/t}:</td><td class="aright">{$po_date_creation}</td></tr>


	  {if $tipo>0}
	  <tr><td>{t}Submitted{/t}:</td><td class="aright">{$po_date_submited}</td></tr>
	  {/if}

	  {if $tipo==1}<tr><td>{t}Expected{/t}:</td><td id="date_expected" class="aright">{$po_date_expected}</td></tr>{/if}
	  
	  
	  
	  <tr {if $dn_number==''}style="display:none"{/if} id="row_public_id" ><td>{t}Invoice Number{/t}:</td><td id="public_id" class="aright">{$dn_number}</td><td class="aright" id="edit_public_id" style="display:none" ><input style="text-align:right" class="text" size="7"  id="v_invoice_number"  name="invoice_number" value="{$dn_number}"  /></td></tr>
	  <tr {if $po_date_invoice==''}style="display:none"{/if}  id="row_invoice_date"><td>{t}Invoice Date{/t}:</td><td id="invoice_date" >{$po_date_invoice}</td>
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
	  

	  </table>


	
	

	<table >

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
	  <tr><td>{t}Distinct Products{/t}:</td><td class="aright" id="distinct_products">{$items}</td></tr>
	</table>





      	<table style="clear:both;border:none;display:none" class="notes">
	  <tr><td style="border:none">{t}Notes{/t}:</td><td style="border:none"><textarea id="v_note" rows="2" cols="60" ></textarea></td></tr>
	</table>
	

      </fieldset>



     
      <div id="block1" >
	{include file='table.tpl' table_id=1 table_title=$t_title1 filter=$filter1 filter_name=$filter_name1         filter_value=$filter_value1  } 
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
  {*}
    <div class="yui-b">
	<form action="http://search.yahoo.com/search" onsubmit="return YAHOO.deliverynote.ACJson.validateForm();">
	  <div id="ysearch"  style="clear:both;border:none">
	    <label>Add Product to List: </label>
	    <input id="ysearchinput" type="text" name="p" style="position:static;width:20em">
	    <input id="ysearchsubmit" type="submit" value="OK">
	    <div id="ysearchcontainer" style="text-align:left;width:20em;"></div>
	  </div>
	</form>

    </div>
{/*}
</div> 
{*}
<div id="upload_dn">
  <div class="hd">{t}New Products from file{/t}</div> 
  <div class="bd"> 
    <form  enctype="multipart/form-data" method="POST" action="new_delivernote.php"   id="uploadForm"   > 
      <br>
      <table >
	<tr><td>{t}CVS File{/t}:</td><td><input  class="file" name="uploadedfile" type="file" /></td></tr>
      </table>
    </form>
  </div>
</div>
{/*}


<div id="submiting_form">
  <div class="hd">{t}Submiting the purchase order{/t}</div> 
  <div class="bd"> 
 <form action="ar_suppliers.php" >
    <input type="hidden" name="po_id" value="{$po_id}" >
    <input type="hidden" name="tipo" value="po_go" >

      <table >
	<tr><td>{t}Expected Date{/t}:</td><td><input id="v_calpop1"   class="text" name="expected_date" type="text"  size="10" maxlength="10"     /><img   id="calpop1" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> </td></tr>
      </table>
    </form>
	<div id="cal1Container" style="display:none; z-index:2"></div>

  </div>
</div>

<div id="deleting_form">
  <div class="hd">{t}Deleting Purchase Order{/t}</div> 
  <div class="bd"> 
    <form action="ar_suppliers.php" >
    <input type="hidden" name="po_id" value="{$po_id}" >
    <input type="hidden" name="tipo" value="po_goback" >
    <p>{t}Are you sure that you want to delete this purchase order?{/t}</p>
      <table class="but" style="margin:auto;border-spacing:10px  10px" >
	<tr><td class="edit" id="delete_po">{t}Yes{/t}</td></tr>
      </table>
    </form>
  </div>
</div>

<div id="receiving_form">
  <div class="hd">{t}Receiving Purchase Order Products{/t}</div> 
  <div class="bd"> 
    <form action="ar_suppliers.php" >
    <input type="hidden" name="po_id" value="{$po_id}" >
    <input type="hidden" name="tipo" value="po_go" >
    <p>{t}Do you receive this order?{/t}</p>
      <table class="but" style="margin:auto;border-spacing:10px  10px" >
	<tr><td class="edit" id="receive_po">{t}Yes{/t}</td></tr>
      </table>
    </form>
  </div>
</div>



<div id="returning_form">
  <div class="hd">{t}Return Purchase Order{/t}</div> 
  <div class="bd"> 

    <input type="hidden" name="po_id" value="{$po_id}" >
    <input type="hidden" name="tipo" value="po_goback" >
      <table >
	<tr><td>{t}Return{/t}</td><td>{t}Cancel{/t}</td></tr>
      </table>
    </form>
  </div>
</div>

<div id="canceling_form">
  <div class="hd">{t}Cancel Purchase Order{/t}</div> 
  <div class="bd"> 
    <form action="ar_suppliers.php" >
    <p>{t}Are you sure that you want to cancel this purchase order?{/t}</p>
    <input type="hidden" name="po_id" value="{$po_id}" >
    <input type="hidden" name="tipo" value="po_goback" >
      <table style="margin:auto;cursor:pointer">
	<tr><td class="edit" id="cancel_po">{t}Yes{/t}</td></tr>

      </table>
    </form>
  </div>
</div>

{include file='footer.tpl'}

