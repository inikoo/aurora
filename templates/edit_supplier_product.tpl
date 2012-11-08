{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >
{include file='suppliers_navigation.tpl'}

<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Product{/t}: <span id="title_name">{$supplier_product->get('Supplier Product Name')}</span> (<span id="title_code">{$supplier_product->get('Supplier Product Code')}</span>)</h1>
</div>



  
  <ul class="tabs" id="chooser_ul">
    <li><span  class="item {if $edit=='parts'}selected{/if}" id="parts" > <span>{t}Parts{/t}</span></span></li>
    <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
    <li> <span class="item {if $edit=='prices'}selected{/if}" id="prices"  ><span> {t}Price, Discounts{/t}</span></span></li>
   </ul>
  
 
     <div class="tabbed_container" > 
 
     

 


<div class="edit_block" {if $edit!="prices"}style="display:none"{/if}  id="d_prices">
<input id="v_cost" value="{$supplier_product->get('Supplier Product Cost')}" type="hidden"/>
<div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_product_price" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_product_price" class="state_details">{t}Reset{/t}</span>
	
      </div>


<table class="edit" border=0>
 <tr class="title"><td colspan=5>{t}Price{/t}</td></tr>

<tr class="first"><td  class="label">{t}Price per Outer{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:7em;position:relative;top:00px" >
       <input style="text-align:left;width:8em" id="Product_Price" value="{$supplier_product->get('Price')}" ovalue="{$supplier_product->get('Price')}" valid="0">
       <div id="Product_Price_Container"  ></div>
     </div>
   </td>
<td id="price_per_unit" cost="{$supplier_product->get('Supplier Product Cost')}"  old_price="{$supplier_product->get('Supplier Product Price')}"  units="{$supplier_product->get('Supplier Product Units Per Case')}">{$supplier_product->get_formated_price_per_unit()}</td>
<td id="price_margin">{t}Margin{/t}: {$supplier_product->get('Margin')}</td>

   <td style="width:200px" id="Product_Price_msg" class="edit_td_alert"></td>
 </tr>

<tr class="first"><td  class="label">{t}RRP per Unit{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:7em;position:relative;top:00px" >
       <input style="text-align:left;width:8em" id="Product_RRP" value="{$supplier_product->get('RRP')}" ovalue="{$supplier_product->get('RRP')}" valid="0">
       <div id="Product_RRP_Container"  ></div>
     </div>
   </td>
<td ></td>
<td id="rrp_margin">{t}Margin{/t}: {$supplier_product->get('RRP Margin')}</td>
   <td style="width:200px" id="Product_RRP_msg" class="edit_td_alert"></td>
 </tr>


</table>


  
</div>

<div class="edit_block" {if $edit!="parts"}style="display:none"{/if}  id="d_parts">
  {t}Add new part{/t} 
  <div id="adding_new_part" style="width:200px;margin-bottom:45px"><input id="new_part_input" type="text"><div id="new_part_container"></div></div>

  
  <table  class="edit" style="width:33em"  >
    <tbody id="new_part_form" style="display:none;background:#f1fdf2"  part_id="" >
      <tr class="top title">
	<td style="width:18em" class="label" colspan=2>
	  <img id="cancel_new"         class="icon" onClick="cancel_new_part()" src="art/icons/cross.png">
	  <img id="save_part_new"  class="icon" onClick="save_new_part()" src="art/icons/disk.png">
	  <span id="new_part_name"></span> <img id="save_part_{$part_id}" src="art/icons/new.png">
	</td>
      </tr>
      <tr>
	<td class="label">{t}Parts product code{/t}:</td>
	<td style="text-align:left;width:11em"><input style="text-align:right;width:10em" value="" id="new_part_code" value="" ></td>
      </tr>
      <tr class="last">
	<td class="label">{t}Estimated price per{/t} {$data.units_tipo_name}:</td>
	<td style="text-align:left">{$currency}<input style="text-align:right;width:6em" value="" id="new_part_cost" id=""></td>
      </tr>
      <tr>
	<td style="background:white" colspan="4"></td>
      </tr>
    </tbody>
    <tbody id="current_parts_form">

      {foreach from=$parts item=part key=part_id }
      <tr  id="sup_tr1_{$part_id}" class="top title">
	<td  class="label" colspan=2>
	  <img id="delete_part_{$part_id}" class="icon" onclick="delete_part({$part_id},'{$part}')"  src="art/icons/cross.png">
	  <img id="save_part_{$part_id}" class="icon" style="visibility:hidden" onClick="save_part({$part_id})" src="art/icons/disk.png">
	  <a href="part.php?sku={$part_id}">{$part.code}</a>
	</td>
      </tr>
      <tr id="sup_tr2_{$part_id}">
	<td class="label" style="width:15em">{t}Parts product code{/t}:</td>
	<td style="text-align:left;">
	  <input style="padding-left:2px;text-align:left;width:10em" value="{$part.part_product_code}" name="code"   onkeyup="part_changed(this,{$part_id})" ovalue="{$part.part_product_code}" id="v_part_code{$part_id}"></td>
      </tr>
      <tr id="sup_tr3_{$part_id}" class="last">
	<td class="label">{t}Cost per{/t} {$data.units_tipo_name}:</td>
	<td style="text-align:left">{$currency}<input id="v_part_cost{$part_id}" style="text-align:right;width:6em"  name="price " onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thousand_sep}',4);part_changed(this,{$part_id})"  value="{$part.price}" ovalue="{$part.price}" ></td>
      </tr>
      <tr id="sup_tr4_{$part_id}">
	<td colspan="2"></td>
      </tr>
      {/foreach}
    </tbody>
    
  </table>	  
</div>
<div class="edit_block" {if $edit!="pictures"}style="display:none"{/if}  id="d_pictures">


{include file='edit_images_splinter.tpl'}






</div>
<div class="edit_block" {if $edit!="description"}style="display:none"{/if}"  id="d_description">

<div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_product_description" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_product_description" class="state_details">{t}Reset{/t}</span>
	
      </div>

<table class="edit">
 <tr class="title"><td colspan=5>{t}Units{/t}</td></tr>

<tr><td  class="label">{t}Units Type{/t}:</td>
<td  style="text-align:left">

<select id="Product_Units_Type" onChange="option_selected('product_description','unit_type')" ovalue="{$unit_type}">
{foreach from=$unit_type_options key=value item=label}
   <option label="{$label}" value="{$value}" {if $value==$unit_type}selected="selected"{/if}  >{$label}</option>

{/foreach}
</select>


   </td>
   <td id="Product_Units_Type_msg" class="edit_td_alert"></td>
 </tr>
 
 <tr><td  class="label">{t}Unit Package Type{/t}:</td>
<td  style="text-align:left">

<select id="Product_Unit_Package_Type" onChange="option_selected('product_description','unit_packing_type')"  ovalue="{$unit_packing_type}"  >
{foreach from=$unit_packing_type_options key=value item=label}
   <option label="{$label}" value="{$value}" {if $value==$unit_packing_type}selected="selected"{/if}  >{$label}</option>

{/foreach}
</select>
  </td>
   <td id="Product_Unit_Package_Type_msg" class="edit_td_alert"></td>
 </tr>
 
<tr class="first"><td  class="label"><span style="font-size:80%">({t}Without packing{/t})</span> {t}Unit Weight{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px;" >
       <input style="text-align:left;width:6em" id="Product_Unit_Weight" value="{$supplier_product->get('Supplier Product Unit Net Weight')}" ovalue="{$supplier_product->get('Supplier Product Unit Net Weight')}" valid="0">
       <span style="margin-left:6.75em">{t}Kg{/t}</span>
       <div id="Product_Unit_Weight_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Unit_Weight_msg" class="edit_td_alert"></td>
 </tr>

<tr class="first"><td  class="label"><span style="font-size:80%">({t}With packing{/t})</span> {t}Unit Weight{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:6em" id="Product_Unit_Gross_Weight" value="{$supplier_product->get('Supplier Product Unit Gross Weight')}" ovalue="{$supplier_product->get('Supplier Product Unit Gross Weight')}" valid="0">
        <span style="margin-left:6.75em">{t}Kg{/t}</span>
       <div id="Product_Unit_Gross_Weight_Container"  ></div>
     </div>
    
   </td>
   <td style="width:200px" id="Product_Unit_Gross_Weight_msg" class="edit_td_alert"></td>
 </tr>
 
 
 <tr class="first"><td  class="label"><span style="font-size:80%">({t}With packing{/t})</span> {t}Unit Best Approximate Volume{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:6em" id="Product_Unit_Gross_Volume" value="{$supplier_product->get('Supplier Product Unit Gross Volume')}" ovalue="{$supplier_product->get('Supplier Product Unit Gross Volume')}" valid="0">
        <span style="margin-left:6.75em">{t}Liters{/t}</span>
       <div id="Product_Unit_Gross_Volume_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Unit_Gross_Volume_msg" class="edit_td_alert"></td>
 </tr>
 
<tr class="first"><td  class="label"><span style="font-size:80%">({t}With packing{/t})</span> {t}Minimun Orthogonal Volume{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:6em" id="Product_Unit_MOV" value="{$supplier_product->get('Supplier Product Unit Minimun Orthogonal Gross Volume')}" ovalue="{$supplier_product->get('Supplier Product Unit Minimun Orthogonal Gross Volume')}" valid="0">
        <span style="margin-left:6.75em">{t}Liters{/t}</span>
       <div id="Product_Unit_MOV_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Unit_MOV_msg" class="edit_td_alert"></td>
 </tr>
</table>

<table class="edit">
 <tr class="title"><td colspan=5>{t}Cases{/t}</td></tr>
<tr class="first"><td  class="label">{t}Units Per Outer{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:6em" id="Product_Units_Per_Case" value="{$supplier_product->get('Supplier Product Units Per Case')}" ovalue="{$supplier_product->get('Supplier Product Units Per Case')}" valid="0">
       <div id="Product_Units_Per_Case_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Units_Per_Case_msg" class="edit_td_alert"></td>
 </tr>

 
<tr class="first"><td  class="label"> {t}Case Weight{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:6em" id="Product_Case_Gross_Weight" value="{$supplier_product->get('Supplier Product Case Gross Weight')}" ovalue="{$supplier_product->get('Supplier Product Case Gross Weight')}" valid="0">
      <span style="margin-left:6.75em">{t}Kg{/t}</span>
       <div id="Product_Case_Gross_Weight_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Case_Gross_Weight_msg" class="edit_td_alert"></td>
 </tr>


 
<tr class="first"><td  class="label"><span style="font-size:80%">({t}With packing{/t})</span> {t}Minimun Orthogonal Case Volume{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:6em" id="Product_Case_MOV" value="{$supplier_product->get('Supplier Product Case Minimun Orthogonal Gross Volume')}" ovalue="{$supplier_product->get('Supplier Product Case Minimun Orthogonal Gross Volume')}" valid="0">
        <span style="margin-left:6.75em">{t}Liters{/t}</span>
       <div id="Product_Case_MOV_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Case_MOV_msg" class="edit_td_alert"></td>
 </tr>
</table>

<div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_product_description" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_product_description" class="state_details">{t}Reset{/t}</span>
	
      </div>

<table class="edit">
 <tr class="title"><td colspan=5>{t}Name / Description{/t}</td></tr>
 
 <tr><td  class="label">{t}Product ID{/t}:</td>
 <td>{$supplier_product->pid}</td>
 </tr>
<tr class="first"><td  class="label">{t}Product Code{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Product_Code" value="{$supplier_product->get('Supplier Product Code')}" ovalue="{$supplier_product->get('Supplier Product Code')}" valid="0">
       <div id="Product_Code_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Code_msg" class="edit_td_alert"></td>
 </tr>

<tr class="first"><td  class="label">{t}Product Name{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Product_Name" value="{$supplier_product->get('Supplier Product Name')}" ovalue="{$supplier_product->get('Supplier Product Name')}" valid="0">
       <div id="Product_Name_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Name_msg" class="edit_td_alert"></td>
 </tr>
<tr class="first"><td  class="label">{t}Product Supplier Web Site{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Product_URL" value="{$supplier_product->get('Supplier Product URL')}" ovalue="{$supplier_product->get('Supplier Product URL')}" valid="0">
       <div id="Product_URL_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_URL_msg" class="edit_td_alert"></td>
 </tr>
</table>



 
</div>


</div>


<div id="the_table0" class="data_table" style="margin:20px 20px 0px 20px; clear:both;padding-top:10px">
  <span class="clean_table_title">{t}History{/t}</span>
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
  <div  id="table0"   class="data_table_container dtable btable"> </div>
</div>



</div>



</div>


<div id="Editor_add_part" style="position:fixed;top:-200px;width:280px">
  <div style="display:none" class="hd"></div>
    <div class="bd dt-editor" >
          <table border=0>
          
         
          
	    <input type="hidden" id="add_part_sku" value=0 >
	     <input type="hidden" id="add_part_key" value=0 >

	    <tr><td>{t}Add part{/t}</tr>
	    <tr>
	    
	    <td id="other_part" >
			
			<div id="add_part"  style="width:260px">
			  <input id="add_part_input" type="text" value="" >
			  <div id="add_part_container"></div>
			</div>


	      </td>
	    </tr>
	   
	  </table>
	  <div class="yui-dt-button">
	    <button style="display:none" onclick="save_add_part();" class="yui-dt-default">{t}Save{/t}</button>
	    <button onclick="close_add_part_dialog()" >{t}Cancel{/t}</button>
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

{include file='footer.tpl'}


