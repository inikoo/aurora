{include file='header.tpl'}
<div id="bd" >
 {include file='suppliers_navigation.tpl'}
<div> 
  <span   class="branch"><a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; {$supplier->get('Supplier Name')}</span>
</div>

		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				<button onclick="window.location='suppliers.php'"><img src="art/icons/house.png" alt=""> {t}Suppliers{/t}</button> 
			</div>
			<div class="buttons">
				<button onclick="window.location='supplier.php?id={$supplier->id}'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>


 <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>{t}Edit Supplier{/t}: <span id="title_name">{$supplier->get('Supplier Name')}</span> (<span id="title_code">{$supplier->get('Supplier Code')}</span>)</h1>
  </div>

  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='details'}selected{/if}"  id="details">  <span> {t}Supplier Details{/t}</span></span></li>

    <li style="display:none"> <span class="item {if $edit=='company'}selected{/if}"  id="company">  <span> {t}Company Details{/t}</span></span></li>
    <li> <span class="item {if $edit=='products'}selected{/if}"  id="products">  <span> {t}Supplier Products{/t}</span></span></li>
       <li> <span class="item {if $edit=='categories'}selected{/if}"  id="categories">  <span> {t}Categories{/t}</span></span></li>
  </ul>

 <div class="tabbed_container" > 
   <div  class="edit_block" style="{if $edit!="details"}display:none{/if}"  id="d_details">
  
       <div class="general_options" style="float:right">
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_supplier" onClick="save_edit_general('supplier')" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_supplier" onClick="reset_edit_general('supplier')" class="state_details">{t}Reset{/t}</span>
      </div>
  
   <table class="edit" border=0 style="clear:both">
	<tr class="first"><td style="width:11em" class="label">Supplier Code:</td>
	  <td  style="text-align:left;width:19em">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Supplier_Code" value="{$supplier->get('Supplier Code')}" ovalue="{$supplier->get('Supplier Code')}" valid="0">
	      <div id="Supplier_Code_Container"  ></div>
	    </div>
	  </td>
	  <td id="Supplier_Code_msg" class="edit_td_alert"></td>
	</tr>
	<tr class="first"><td  class="label">{t}Company Name{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Supplier_Name" value="{$supplier->get('Supplier Name')}" ovalue="{$supplier->get('Supplier Name')}" valid="0">
	      <div id="Supplier_Name_Container"  ></div>
	    </div>
	  </td>
	  	  <td id="Supplier_Name_msg" class="edit_td_alert"></td>

	</tr>

 <tr ><td style=";width:12em" class="label" >{t}Contact Name{/t}:</td>
   <td  style="text-align:left;width:18em;">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Supplier_Main_Contact_Name" value="{$supplier->get('Supplier Main Contact Name')}" ovalue="{$supplier->get('Supplier Main Contact Name')}" valid="0">
       <div id="Supplier_Main_Contact_Name_Container"  ></div>
     </div>
   </td>
   <td id="Supplier_Main_Contact_Name_msg" class="edit_td_alert"></td>
 </tr>
 
 <tr ><td  class="label">{t}Contact Email{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Supplier_Main_Email" value="{$supplier->get('Supplier Main Plain Email')}" ovalue="{$supplier->get('Supplier Main Plain Email')}" valid="0">
       <div id="Supplier_Main_Email_Container"  ></div>
     </div>
   </td>
   <td id="Supplier_Main_Email_msg" class="edit_td_alert"></td>
 </tr>
 
 <tr ><td  class="label">{t}Telephone{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;" >
       <input style="text-align:left;width:18em" id="Supplier_Main_Telephone" value="{$supplier->get('Supplier Main XHTML Telephone')}" ovalue="{$supplier->get('Supplier Main XHTML Telephone')}" valid="0">
       <div id="Supplier_Main_Telephone_Container"  ></div>
     </div>
   </td>
   <td id="Supplier_Main_Telephone_msg" class="edit_td_alert"></td>
 </tr>
 
<tr ><td  class="label">{t}Fax{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;" >
       <input style="text-align:left;width:18em" id="Supplier_Main_Fax" value="{$supplier->get('Supplier Main XHTML FAX')}" ovalue="{$supplier->get('Supplier Main XHTML FAX')}" valid="0">
       <div id="Supplier_Main_Fax_Container"  ></div>
     </div>
   </td>
   <td id="Supplier_Main_Fax_msg" class="edit_td_alert"></td>
 </tr>
 <tr ><td  class="label">{t}Web Page{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;" >
       <input style="text-align:left;width:18em" id="Supplier_Main_Web_Site" value="{$supplier->get('Supplier Main Web Site')}" ovalue="{$supplier->get('Supplier Main Web Site')}" valid="0">
       <div id="Supplier_Main_Web_Site_Container"  ></div>
     </div>
   </td>
   <td id="Supplier_Main_Web_Site_msg" class="edit_td_alert"></td>
 </tr>
 
     </table>
    
    <div id="supplier_contact_address" style="float:left;width:500px;margin-right:40px;min-height:300px;margin-top:30px">
     <div style="border-bottom:1px solid #777;margin-bottom:7px">
       {t}Contact Address{/t}:
     </div>
     <table border=0 style="width:500px">
       {include file='edit_address_splinter.tpl' address_identifier='contact_' hide_type=true hide_description=true  }
     </table>
     <div style="display:none" id='contact_current_address' ></div>
     <div style="display:none" id='contact_address_display{$supplier->get("Supplier Main Address Key")}' ></div>
   </div>
    
  <div style="clear:both"></div>
   </div>
   

   <div  class="edit_block" style="{if $edit!="company"}display:none{/if}"  id="d_company">
      <div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;display:none"  id="save_new_supplier" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;display:none" id="close_add_supplier" class="state_details">{t}Reset{/t}</span>
	
      </div>


      <div id="new_supplier_messages" class="messages_block"></div>

      


     
	  
       {include file='edit_company_splinter.tpl'}


     
   </div>

  <div  class="edit_block" style="{if $edit!="products"}display:none{/if}"  id="d_products">

     <div class="general_options" style="float:right; text-align:right; ">
	 <span  style="margin-right:10px;"  id="show_new_product_dialog_button" onClick="show_new_product_dialog()" class="state_details">{t}Create New Product{/t}</span>
	    <span  style="margin-right:10px;"  id="import_new_product" class="state_details">{t}Import Products (CSV){/t}</span>
		<span  style="margin-right:10px;visibility:hidden"  id="save_new_product" onClick="save_new_general('product')" class="state_details">{t}Save New Product{/t}</span>
  	    <span style="margin-right:10px;visibility:hidden" id="cancel_new_product" onClick="cancel_new_general('product')" class="state_details">{t}Cancel New Product{/t}</span>
	   
</div>

  <div class="data_table" style="clear:both">
  

  
  
  
  
  
   <table id="new_product_dialog" class="edit" border=0 style="clear:both;display:none">
   <tr><td></td><td  id="new_product_dialog_msg"></td></tr>
	<tr class="first"><td style="width:11em" class="label">Product Code:</td>
	  <td  style="text-align:left;width:19em">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Product_Code" value="" ovalue="" valid="0">
	      <div id="Product_Code_Container"  ></div>
	    </div>
	  </td>
	  <td id="Product_Code_msg" class="edit_td_alert"></td>
	</tr>
	<tr ><td  class="label">{t}Product Name{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Product_Name" value="" ovalue="" valid="0">
	      <div id="Product_Name_Container"  ></div>
	    </div>
	  </td>
	  	  <td id="Product_Name_msg" class="edit_td_alert"></td>

	</tr>

<tr><td  class="label">{t}Unit{/t}:</td>
	  <td  style="text-align:left">
	  <select id="Product_Unit">
	  {foreach from=$units_list key=key item=value}
	    <option {if $units_list_selected==$key}selected="selected" default=1{else}default=0{/if} value="{$key}">{$value}</option>
	  {/foreach}
      </select>
	   
	  </td>
	  	  <td id="Product_Unit_msg" class="edit_td_alert"></td>

	</tr>
<tr ><td  class="label">{t}Units per Case{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:9em" id="Product_Units_Per_Case" value="1" ovalue="1" valid="0">
	      <div id="Product_Units_Per_Case_Container"  ></div>
	    </div>
	  </td>
	  	  <td id="Product_Units_Per_Case_msg" class="edit_td_alert"></td>

	</tr>
<tr ><td  class="label">{t}Price per Case{/t}:</td>
	  <td  style="text-align:left">
	  <table border=0>
	  <tr>
	  <td style="padding:2px 0px">
	  <select id="Product_Currency">
	  {foreach from=$currency_list key=key item=value}
	    <option {if $currency_selected==$key}selected="selected" default=1{else}default=0{/if} value="{$key}"   >{$value}</option>
	  {/foreach}
      </select>
	  
	    </td>
	    <td style="padding:2px 0px 2px 12px">
	     <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:9em" id="Product_Price_Per_Case" value="" ovalue="" valid="0">
	      <div id="Product_Price_Per_Case_Container"  ></div>
	    </div>
	    </td>
	    
	    </table>
	  </td>
	  	  <td ><table><tr><td id="Product_Price_Currency_msg" class="edit_td_alert"></td><td id="Product_Price_Per_Case_msg" class="edit_td_alert"></td></tr></table></td>

	</tr>
	
	<tr ><td  class="label">{t}Description{/t}:</td>
	  <td  style="text-align:left">
	   <textarea id="Product_Description"></textarea>
	  </td>
	  	  <td id="Product_Description_msg" class="edit_td_alert"></td>

	</tr>
	
     </table>
  
  <div id="suppliers_product_list"> 
    <span class="clean_table_title">{t}Suppliers Product List{/t}</span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
    
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable" style="font-size:85%"> </div>
   </div>
   </div>
</div>
 <div  class="edit_block" style="{if $edit!="categories"}display:none{/if}"  id="d_categories">
 
 <table class="edit">
 <tr class="title"><td colspan=5>{t}Categories{/t}</td></tr>
 
 {foreach from=$categories item=cat key=cat_key name=foo  }
 <tr>
 
 <td class="label">{t}{$cat->get('Category Name')}{/t}:</td>
 <td>
  <select id="cat{$cat_key}" cat_key="{$cat_key}"  onChange="save_category(this)">
    {foreach from=$cat->get_children_objects() item=sub_cat key=sub_cat_key name=foo2  }
        {if $smarty.foreach.foo2.first}
        <option {if $categories_value[$cat_key]=='' }selected="selected"{/if} value="">{t}Unknown{/t}</option>
        {/if}
        <option {if $categories_value[$cat_key]==$sub_cat_key }selected="selected"{/if} value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Name')}</option>
    {/foreach}
  </select>
  
 </td>   
</tr>
{/foreach}
 </table>
 
 </div>
</div>
<div id="the_table1" class="data_table" >
  <span class="clean_table_title">{t}History{/t}</span>
     {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>
</div>


<div id="rppmenu1" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu1" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
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
