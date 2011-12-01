{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >
{include file='assets_navigation.tpl'}
<div class="branch"> 
  <span   >{if $user->get_number_stores()>1}<a  href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; <a href="department.php?id={$product->get('Product Main Department Key')}">{$product->get('Product Main Department Name')}</a> &rarr; <a  href="family.php?id={$product->get('Product Family Key')}">{$product->get('Product Family Code')}</a> &rarr; {$product->get('Product Code')}</span>
</div>
<div class="top_page_menu">
    <div class="buttons" >
        <button style="margin-left:0px"  onclick="window.location='product.php?id={$product->id}'" ><img src="art/icons/door_out.png" alt=""/> {t}Exit Edit{/t}</button>
  </div>
    <div class="buttons" style="float:right">
    </div>
    <div style="clear:both"></div>
</div>
<input type="hidden" id="store_key" value="{$store->get('Store Key')}"/>
    <h1><span class="id">{$product->get('Product Code')}</span> (<i>{$product->get('Product ID')})</i>, {$product->get('Product Name')} </h1>


  
  <ul class="tabs" id="chooser_ul">
    <li><span  class="item {if $edit=='config'}selected{/if}" id="config" > <span>{t}Parts{/t}</span></span></li>
    <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
    <li> <span class="item {if $edit=='prices'}selected{/if}" id="prices"  ><span> {t}Price, Discounts{/t}</span></span></li>
    <li> <span class="item  {if $edit=='dimat'}selected{/if}" id="dimat" ><span> {t}Dimensions{/t}</span></span></li>
    <li> <span class="item {if $edit=='web'}selected{/if} " id="web" ><span> {t}Web Pages{/t}</span></span></li>
	</ul>
  
 
<div class="tabbed_container" > 

 
      <div style="clear:both;height:.1em;padding:0px 20px;;margin:0px auto;width:770px;" id="description_messages">



	<div style="float:right">
	  <span class="save" style="display:none" id="description_save" onclick="save_description()">{t}Save{/t}</span>
	  <span id="description_undo"  style="display:none"   class="undo" onclick="undo('description')">{t}Cancel{/t}</span>
	</div>
	<span style="display:none">Number of changes:<span id="description_num_changes">0</span></span>
	
	<div id="description_errors">
	</div>
	<div id="description_warnings">
	</div>
	<div style="clear:both"></div>
</div>


<div class="edit_block" {if $edit!="config"}style="display:none"{/if}   id="d_config">
	
	<table style="margin:5px 0px ;width:700px"  border=0 class="edit">
	<tbody style="display:none" >
	<tr class="title"><td >{t}Type of Product{/t}</td></tr>
	 <tr>
	   
	    
	    <td style="text-align:center" >
	      <div class="options" style="margin:0px 0;font-size:140%">
		<span {if $product->get('Product Type')=="Normal"}class="selected"{/if} id="type_prod_normal">{t}Simple{/t}</span>
		<span {if $product->get('Product Type')=="Shortcut"}class="selected"{/if} id="type_prod_shortcut">{t}Shortcut{/t}</span>
		<span {if $product->get('Product Type')=="Mix"} class="selected"{/if} id="type_prod_mix">{t}Mixture{/t}</span>
	      </div>
	    </td>
	    
	  </tr>
	  </tbody>
</table>




	  
	  
	  
	  
	 	  <table class="edit" style="width:100%"   border=0  id="part_editor_table"   >
	 	   <tr class="title">
	 	   <td colspan=2 >{t}Part List{/t}</td>
	 	   <td colspan=2>
	 	   <div style="text-align:right;font-weight:100"  id="product_part_items" product_part_key="{$product->get_current_product_part_key()}"  >
	 
	  <span style="margin-right:10px;visibility:hidden" id="save_edit_part"   onclick="save_part()" class="state_details">{t}Save{/t}</span>
	  <span style="margin-right:10px;visibility:hidden" id="reset_edit_part"  onclick="reset_part()" class="state_details">{t}Reset{/t}</span>
	   <span style="margin-right:10px; {if $product->get('Product Type')=="Normal" and $num_parts!=0}xdisplay:none{/if}" id="add_part" class="state_details">{t}Add Part to List{/t}</span>
	  </div>
	 	   </td>
	 	   </tr>
	   
	  {foreach from=$product->get_current_part_list('smarty') key=sku item=part_list}
	   
	   <tr  id="part_list{$sku}" sku="{$sku}" class="top title">
		<td  id="part_list{$sku}_label1" class="label" style="width:150px;font-weight:200">{t}Part{/t}</td>
		<td id="part_list{$sku}_label2" colspan="2"style="width:120px"><span class="id">{$part_list.part->get_sku()}</span>
		{$part_list.part->get('Part XHTML Description')}</td>
		<td style="width:200px;text-align:right">
		<div id="part_list{$sku}_controls">
		<span onClick="remove_part({$sku})" style="cursor:pointer"><img   src="art/icons/delete_bw.png"/> {t}Remove{/t}</span>
		<span onClick="show_change_part_dialog({$sku},this)"  style="display:none;cursor:pointer;margin-left:15px"><img  src="art/icons/arrow_refresh_bw.png"/> {t}Change{/t}</span>
		</div>
		<div id="part_list{$sku}_controls2" style="display:none">
		<span onClick="unremove_part({$sku})" style="cursor:pointer"><img   src="art/icons/arrow_rotate_clockwise.png"/> {t}Restore{/t}</span>
		</div>
		</td>
	    </tr>
	   
	   <tr id="sup_tr2_{$sku}">
		<td class="label" >{t}Parts Per Product{/t}:</td>
		<td style="text-align:left;" colspan=3>
		  <input style="padding-left:2px;text-align:left;width:3em" value="{$part_list.Parts_Per_Product}"  
		  onblur="part_changed(this)"  onkeyup="part_changed(this)"  
		  ovalue="{$part_list.Parts_Per_Product}" id="parts_per_product{$sku}"> <span  id="parts_per_product_msg{$sku}"></span></td>
	      </tr>
	    
	    <tr id="sup_tr3_{$sku}" class="last">
		<td class="label">{t}Note For Pickers{/t}:</td>
	     <td style="text-align:left" colspan=3>
	     <input id="pickers_note{$sku}" style=";width:400px"   onblur="part_changed(this)"  onkeyup="part_changed(this)"     value="{$part_list.Product_Part_List_Note}" ovalue="{$part_list.Product_Part_List_Note}" ></td>
	      </tr>
	      
	   {/foreach}
	    
	  </table>	  
	  
	  

	
	
	

</div>
<div class="edit_block" {if $edit!="web"}style="display:none"{/if}  id="d_web">
  <table class="edit" >

    {foreach from=$web_pages item=page }
    <tr><td><input id="pagetitle{$page.id}" value="{$page.title}" ovalue="{$page.title}"/></td><td><input id="pageurl{$page.id}" value="{$page.url}" ovalue="{$page.url}"/></td><td><img src="art/icons/cross.png"/></td><td><img src="art/icons/disk.png"/></td></tr>
    {/foreach}
      
    
  </table>
</div>
<div class="edit_block" {if $edit!="prices"}style="display:none"{/if}  id="d_prices">

<input id="v_cost" value="{$product->get_cost_supplier()}" type="hidden"/>

<div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_product_price" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_product_price" class="state_details">{t}Reset{/t}</span>
	
      </div>


<table class="edit" border=0>
 <tr class="title"><td colspan=5>{t}Price{/t}</td></tr>

<tr class="first"><td  class="label">{t}Price per Outer{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:7em;position:relative;top:00px" >
       <input style="text-align:left;width:8em" id="Product_Price" value="{$product->get('Price')}" ovalue="{$product->get('Price')}" valid="0">
       <div id="Product_Price_Container"  ></div>
     </div>
   </td>
<td id="price_per_unit" cost="{$product->get_cost_supplier()}"  old_price="{$product->get('Product Price')}"  units="{$product->get('Product Units Per Case')}">{$product->get_formated_price_per_unit()}</td>
<td id="price_margin">{t}Margin{/t}: {$product->get('Margin')}</td>

   <td style="width:200px" id="Product_Price_msg" class="edit_td_alert"></td>
 </tr>

<tr class="first"><td  class="label">{t}RRP per Unit{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:7em;position:relative;top:00px" >
       <input style="text-align:left;width:8em" id="Product_RRP" value="{$product->get('RRP')}" ovalue="{$product->get('RRP')}" valid="0">
       <div id="Product_RRP_Container"  ></div>
     </div>
   </td>
<td ></td>
<td id="rrp_margin">{t}Margin{/t}: {$product->get('RRP Margin')}</td>
   <td style="width:200px" id="Product_RRP_msg" class="edit_td_alert"></td>
 </tr>


</table>

  
</div>
<div class="edit_block" {if $edit!="dimat"}style="display:none"{/if}  id="d_dimat">


<div class="general_options" style="float:right">
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_product_weight" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_product_weight" class="state_details">{t}Reset{/t}</span>
</div>

<table class="edit" >
 <tr class="title">
 <td colspan=3>{t}Weight{/t}</td>
 </tr>
<tr class="first"><td  class="label">{t}Unit Weight{/t}:</td>
   <td  style="text-align:left;">
     <div   >
       <input style="text-align:left;" id="Product_Unit_Weight" value="{$product->get('Net Weight Per Unit')}" ovalue="{$product->get('Net Weight Per Unit')}" valid="0"> 
       <div id="Product_Unit_Weight_Container"  ></div>
     </div>
    
   </td><td>Kg</td>
   <td style="width:450px" id="Product_Unit_Weight_msg" class="edit_td_alert"></td>
 </tr>
<tr style="display:none">
<td  class="label">{t}Outer Weight{/t}:<br/><small>with packing</small></td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Product_Outer_Weight" value="{$product->get('Product Gross Weight')}" ovalue="{$product->get('Product Gross Weight')}" valid="0">
       <div id="Product_Outer_Weight_Container"  ></div>
     </div>
   </td><td>Kg</td>
   <td id="Product_Outer_Weight_msg" class="edit_td_alert"></td>
 </tr>


</table>





</div>
<div class="edit_block" {if $edit!="pictures"}style="display:none"{/if}  id="d_pictures">
    {include file='edit_images_splinter.tpl'}
</div>
<div class="edit_block" {if $edit!="description"}style="display:none"{/if}"  id="d_description">


<table class="edit" style="width:100%">
 <tr class="title"><td colspan=5>{t}Units{/t}</td>
 <td>
 <div class="general_options" style="float:right">
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_product_units" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_product_units" class="state_details">{t}Reset{/t}</span>
</div>

 </td>
 </tr>
<tr class="first"><td style="width:180px" class="label">{t}Units Per Outer{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Product_Units_Per_Case" value="{$product->get('Product Units Per Case')}" ovalue="{$product->get('Product Units Per Case')}" valid="0">
       <div id="Product_Units_Per_Case_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Units_Per_Case_msg" class="edit_td_alert"></td>
 </tr>
<tr><td style="width:180px" class="label">{t}Units Type{/t}:</td>
<td  style="text-align:left">

<select   id="Product_Unit_Type"   >
{foreach from=$unit_type_options key=value item=label}
   <option label="{$label}" value="{$value}" {if $value==$unit_type}selected="selected"{/if}  >{$label}</option>

{/foreach}
</select>


   </td>
   <td id="Product_Units_Type_msg" class="edit_td_alert"></td>
 </tr>


</table>





<table class="edit" style="width:100%">
 <tr class="title"><td colspan=2>{t}Name / Description{/t}</td>
 <td>
 <div class="general_options" style="float:right">
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_product_description" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_product_description" class="state_details">{t}Reset{/t}</span>
</div>
 </td>
 </tr>
<tr class="first"><td style="width:180px" class="label">{t}Product Name{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Product_Name" value="{$product->get('Product Name')}" ovalue="{$product->get('Product Name')}" valid="0">
       <div id="Product_Name_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Name_msg" class="edit_td_alert"></td>
 </tr>
<tr><td style="width:180px" class="label">{t}Special Characteristic{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Product_Special_Characteristic" value="{$product->get('Product Special Characteristic')}" ovalue="{$product->get('Product Special Characteristic')}" valid="0">
       <div id="Product_Special_Characteristic_Container"  ></div>
     </div>
   </td>
   <td id="Product_Special_Characteristic_msg" class="edit_td_alert"></td>
 </tr>
<tr><td style="width:180px" class="label">{t}Product Description{/t}:</td>
   <td  style="text-align:left">
     <div  style="height:100px;width:25em;position:relative;top:00px" >

<textarea id="Product_Description"  olength="{$product->get('Product Description Length')}"  value="{$product->get('Product Description')}"  ovalue="{$product->get('Product Description')}"  ohash="{$product->get('Product Description MD5 Hash')}" rows="6" cols="42">{$product->get('Product Description')}</textarea>
       
       <div id="Product_Description_Container"  ></div>
     </div>
   </td>
   <td id="Product_Description_msg" class="edit_td_alert"></td>
 </tr>

</table>




<table class="edit" style="width:100%">
 <tr class="title"><td colspan=5>{t}Categories{/t}</td></tr>
 
 <tr class="first"><td style="width:180px" class="label">{t}Family{/t}:</td>
   <td  style="text-align:left">
 <span id="current_family_code">{$product->get('Product Family Code')}</span> <img id="edit_family" id="family" style="margin-left:5px;cursor:pointer" src="art/icons/edit.gif" alt="{t}Edit{/t}" title="{t}Edit{/t}" /s>
   </td>
   <td style="width:200px" id="Product_Name_msg" class="edit_td_alert"></td>
 </tr>
 
 
 {foreach from=$categories item=cat key=cat_key name=foo  }
 <tr>
 
 <td class="label">{t}{$cat.name}{/t}:</td>
 <td>
   {foreach from=$cat.teeth item=cat2 key=cat2_id name=foo2}
   <div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}"   class="options" style="margin:5px 0">
     {foreach from=$cat2.elements item=cat3 key=cat3_id name=foo3}
     <span  class="catbox {if $cat3.selected}selected{/if}" value="{$cat3.selected}" ovalue="{$cat3.selected}" onclick="checkbox_changed(this)" cat_id="{$cat3_id}" id="cat{$cat3_id}" parent="{$cat3.parent}" position="{$cat3.position}" default="{$cat3.default}"  >{$cat3.name}</span>
     {/foreach}
    </div>
   {/foreach}
 </td>   
</tr>
{/foreach}




</table>

 
</div>

 <div id="dialog_family_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Family List{/t}</span>
            {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
            <div  id="table2"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>

</div>


<div id="the_table0" class="data_table" style="margin:20px 20px 0px 20px; clear:both;padding-top:10px">
  <span class="clean_table_title">{t}History{/t}</span>
  {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
  <div  id="table0"   class="data_table_container dtable btable "> </div>
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

<div id="filtermenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


<div id="rppmenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>



{include file='footer.tpl'}

<div id="dialog_part_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none;width:650px">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Parts{/t}</span>
            {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1}
            <div  id="table1"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>


