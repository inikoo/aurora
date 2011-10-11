{include file='header.tpl'}
<div id="time2_picker" class="time_picker_div"></div>
<div id="bd" >
<div id="cal1Container" style="position:absolute;left:610px;top:120px;display:none;z-index:3"></div>

<div class="order_actions" >
    <span class="state_details" onClick="location.href='supplier.php?id={$supplier->get('Supplier Key')}'" style="float:left;margin-top:2px" >{t}Return to Supplier Page{/t}</span>

  <span class="state_details" id="cancel_po">{t}Cancel{/t}</span>
  <span class="state_details" id="invoice_po" style="margin-left:20px">{t}Match to Invoice{/t}</span>
  <span class="state_details" id="dn_po" style="margin-left:20px">{t}Match to Delivery Note{/t}</span>

</div>


<div class="prodinfo" style="margin-top:2px;font-size:85%;border:1px solid #ddd;padding:10px">
    
    

    <table style="width:200px;" class="order_header" >
      <tr><td>{t}Goods{/t}:</td><td id="goods" class="aright">{$po->get('Items Net Amount')}</td></tr>
      <tr><td>{t}Shipping{/t}:</td><td class="aright" id="shipping"  >{$po->get('Shipping Net Amount')}</td></tr>
      <tr><td>{t}Tax{/t}:</td><td id="vat" class="aright"   >{$po->get('Total Tax Amount')}</td></tr>
      <tr><td>{t}Total{/t}</td><td id="total" class="stock aright ">{$po->get('Total Amount')}</td></tr>
    
    </table>
    
    
    <div style="border:0px solid red;xwidth:290px;float:right">
    <table  border=0  class="order_header"  style="margin-right:30px;float:right">
      <tr><td class="aright" style="padding-right:40px">{t}Created{/t}:</td><td>{$po->get('Creation Date')}</td></tr>
      <tr><td class="aright" style="padding-right:40px">{t}Submitted{/t}:</td><td>{$po->get('Submitted Date')}</td></tr>
      <tr><td colspan="2" class="aright">{t}via{/t} {$po->get('Purchase Order Main Source Type')} {t}by{/t} {$po->get('Purchase Order Main Buyer Name')}</td></tr>
      <tr><td class="aright" style="padding-right:40px">    <div id="estimated_delivery_Container" style="position:absolute;display:none; z-index:2"></div>
<img id="edit_estimated_delivery" src="art/icons/edit.gif" alt="({t}edit{/t})"> {t}Estimated Delivery{/t}:</td><td class="aright" id="estimated_delivery">{if $po->get('Purchase Order Estimated Receiving Date')==''}{t}Unknown{/t}{else}{$po->get('Estimated Receiving Date')}{/if}</td></tr>

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
	  <span id="table_type_list" style="display:none;float:right;color:brown" class="table_type state_details {if $table_type=='list'}state_details_selected{/if}">{t}Amend Purchase Order{/t}</span>
	  
	</div>



  <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;display:none"></div>
      <span   style="float:right;margin-left:20px;display:none" class="state_details" state="{$show_all}"  id="show_all"  atitle="{if !$show_all}{t}Show only ordered{/t}{else}{t}Show all products available{/t}{/if}"  >{if $show_all}{t}Show only ordered{/t}{else}{t}Show all products available{/t}{/if}</span>     
      

      
      <table style="float:left;margin:0 0 5px 0px ;padding:0;display:none"  class="options" >
	<tr><td  {if $view=='used_in'}class="selected"{/if} id="general" >{t}Used In{/t}</td>
	  <td {if $view=='history'}class="selected"{/if}  id="stock"  >{t}History{/t}</td>

	</tr>
      </table>
    
    </div>

  
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div class="clean_table_filter" {if !$show_all}style="visibility:hidden"{/if} id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{t}Product Code{/t}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container0'></div></div></div>
    <div class="clean_table_controls" {if !$show_all}style="visibility:hidden"{/if}  id="clean_table_controls0" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
  </div>
  <div  id="table0"  style="font-size:80%" class="data_table_container dtable btable "> </div>
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



<div id="cancel_dialog" class="yuimenu" style="border:none">
  <div class="bd" style="padding-bottom:0px">

  <div id="cancel_dialog_msg"></div>
  <table>
    <tr><td style="width:100px">{t}Note{/t}:</td><td style="width:100px"></td></tr>
    <tr>
      <td colspan="2">
	<textarea style="width:100%;margin-bottom:10px" id="cancel_note"></textarea>
      </td>
    </tr>
    <tr><td colspan=2 style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0">
	<button style="margin-left:50px" class="state_details" onClick="cancel_order_save()"  >{t}Do it{/t}</button>
      </td>
    </tr>
  </table>
  </div>
</div>

<div id="dn_dialog" class="nicebox">
<div class="bd">
  <div id="dn_dialog_msg"></div>
  <table>
    <tr><td class="label">{t}Delivery Note Number{/t}:</td><td style="width:100px"><input id="dn_number" value=""></td></tr>
        <tr><td class="label">{t}Delivery Note Date{/t}:</td><td style="width:100px"><input id="v_calpop1" style="text-align:right;"  class="text" name="submites_date" type="text"  size="10" maxlength="10"  value="{$date}"    /><img   id="calpop1" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt=""   /> </tr>

    <tr><td colspan=2 style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0">
	<button style="margin-left:50px" onClick="dn_order_save()"  >{t}Match to Delivery Note{/t}</button>
      </td>
    </tr>
  </table>
  </div>
</div>





<div id="edit_estimated_delivery_dialog" class="yuimenu"  style="border:none;padding-bottom:0px">
  <div class="bd" style="padding-bottom:0px">
   <table>
   <tr><td colspan=2> 
   <span >{t}Estimated Delivery{/t}:</span>
  <input id="v_calpop_estimated_delivery" type="text" class="text" size="11" maxlength="10" name="from" value="{$po->get('Estimated Receiving Date For Edition')}"/>
  <img   id="estimated_delivery_pop" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="choose"   />
  <br/>
  </td></tr>
    <tr>
        <td colspan=2 style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0">
	<button style="margin-left:50px" onClick="submit_edit_estimated_delivery(this)"  >Save</button>
    
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

