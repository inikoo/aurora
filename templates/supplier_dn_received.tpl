{include file='header.tpl'}
<div id="time2_picker" class="time_picker_div"></div>
<div id="bd" >


<input id="supplier_deliver_note_key" value="{$supplier_dn->id}" type="hidden"/>

<div class="order_actions" >
    <span class="state_details" onClick="location.href='supplier.php?id={$supplier->get('Supplier Key')}'" style="float:left;margin-top:2px" >{t}Supplier Page{/t}</span>
      <span class="state_details" id="edit_dn" style="margin-left:20px">{t}Edit Delivery Note{/t}</span>

      <span class="state_details" id="make_dn_as_checked" style="margin-left:20px">{t}Mark Delivery Note as Checked{/t}</span>


 <DIV style="display:none">
  <span class="state_details" id="set_damages_top" style="margin-left:20px">{t}Set Damages{/t}</span>
  <span class="state_details" id="set_skus_top" style="margin-left:20px">{t}Set SKUs{/t}</span>
  <span class="state_details" id="set_locations_top" style="margin-left:20px">{t}Set Locations{/t}</span>
</DIV>

</div>


<div class="prodinfo" style="margin-top:2px;font-size:85%;border:1px solid #ddd;padding:10px">
    <div style="border:0px solid red;width:290px;float:right">
    <table  border=0  class="order_header"  style="margin-right:30px;float:right">
      <tr><td class="aright" style="padding-right:40px">{t}Created{/t}:</td><td>{$supplier_dn->get('Creation Date')}</td></tr>
      <tr><td class="aright" style="padding-right:40px">{t}Inputted{/t}:</td><td>{$supplier_dn->get('Input Date')}</td></tr>
      <tr><td class="aright" style="padding-right:40px">{t}Received{/t}:</td><td>{$supplier_dn->get('Received Date')}</td></tr>

    </table>
    </div>
    
    
    <h1 style="padding:0px 0 10px 0;width:300px;xborder:1px solid red" id="po_title">{t}Supplier Delivery Note{/t}: {$supplier_dn->get('Supplier Delivery Note Public ID')} ({$supplier_dn->get('Supplier Delivery Note Current State')})</h1>
    <table border=0 >
      <tr><td>{t}Supplier Delivery Note Key{/t}:</td><td class="aright">{$supplier_dn->get('Supplier Delivery Note Key')}</td></tr>
      <tr><td>{t}Supplier{/t}:</td><td class="aright"><a href="supplier.php?id={$supplier->get('Supplier Key')}">{$supplier->get('Supplier Name')}</a></td></tr>
      <tr><td>{t}Items{/t}:</td><td class="aright" id="distinct_products">{$supplier_dn->get('Number Items')}</td></tr>
    </table>

  
    <table style="clear:both;border:none;display:none" class="notes">
      
      <tr><td style="border:none">{t}Notes{/t}:</td><td style="border:none"><textarea id="v_note" rows="2" cols="60" ></textarea></td></tr>
    </table>
    
  <div style="clear:both"></div>
  
</div>


 <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
	<tr>
	  <td  class="selected" id="counting" >{t}Check Delivery{/t}</td><td style="border:none;color:#000">&rarr;</td>
	  <td  id="set_skus"  >{t}Assing SKUs{/t}</td><td style="border:none;color:#000">&rarr;</td>
	  <td  id="set_locations"  >{t}Assing Locations{/t}</td>
	</tr>
      </table>


<div id="the_table" class="data_table" style="margin:20px 0px;clear:both">
  <span class="clean_table_title">{t}Supplier Products{/t}</span>
  	<div id="table_type">
	  <span id="take_values_from_dn" style="margin-left:20px;float:right;color:brown" class="table_type state_details">{t}Copy values from DN{/t}</span>
	  <span id="set_damages_bis" style="float:right;color:brown" class="table_type state_details">{t}Set Damaged{/t}</span>
	  <span id="set_received" style="display:none;float:right;color:brown" class="table_type state_details">{t}Set Received{/t}</span>
	</div>



  <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
      
      

      
     
     
    </div>

  
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div class="clean_table_filter" {if !$show_all}style="visibility:hidden"{/if} id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{t}Product Code{/t}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container0'></div></div></div>
    <div class="clean_table_controls" {if !$show_all}style="visibility:hidden"{/if}  id="clean_table_controls0" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
  </div>
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





<div id="checked_dialog" class="yuimoenu" style="border:none;padding:10px">
<div class="db" style="border:1px solid #777" >
  <div id="checked_dialog_msg"></div>
  <table>
    <tr>
      <td class="aright" style="width:100px"></td><td>
	<div class="options" style="margin:0px 0;width:200px" id="checked_method_container">
      </td>
    </tr>
    <input type="hidden" id="date_type" value="now"/>
   
        <input type="hidden" id="checked_by" value="{$user_staff_key}"/>

      <td class="aright">{t}Checked By{/t}:</td><td style="position:relative"> <span id="get_checker" class="state_details" style="position:absolute;left:200px">{t}Modify{/t}</span><span id="checked_by_alias">{$user}</span></td>
    </tr>

    <tr><td colspan=2 style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0">
	<span style="margin-left:50px" class="state_details" onClick="checked_order_save(this)"  >Save</span>
    
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
	<td staff_id="{$_staff.id}" id="chekers{$_staff.id}" onClick="select_staff(this,event)" >{$_staff.alias}</td>
	{if $_staff.mod==$staff_cols}</tr>{/if}
      {/foreach}
    </table>
<span class="state_details" style="float:right" onClick="close_dialog('staff')" >{t}Close{/t}</span>
  </div>
</div>





{include file='footer.tpl'}

