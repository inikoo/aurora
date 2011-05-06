{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >
{include file='assets_navigation.tpl'}

<div style="clear:left;margin:0 0px">
    <h1>{t}Editing part{/t}: <span class="id">{$part->get_sku()}<span> </h1>
    <h2 id="title_description">{$part->get('Part XHTML Description')}</h2>
</div>



  
  <ul class="tabs" id="chooser_ul">
      <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>

    <li><span  class="item {if $edit=='products'}selected{/if}" id="products" > <span>{t}Products{/t}</span></span></li>
        <li><span  class="item {if $edit=='suppliers'}selected{/if}" id="suppliers" > <span>{t}Suppliers{/t}</span></span></li>
    <li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
	</ul>

 
     <div class="tabbed_container" > 
 
 
     


<div class="edit_block" {if $edit!="descripton2"}style="display:none"{/if}   id="d_description2">
	
	<table style="margin:5px 0px ;xwidth:500px"  border=0 class="edit">
	 <tr class="title"><td >{t}Type of Product{/t}</td></tr>
	 <tr>
	   
	    
	    <td style="text-align:center" >
	      <div class="options" style="margin:0px 0;font-size:140%">
		<span {if $part->get('Product Type')=="Normal"}class="selected"{/if} id="type_prod_normal">{t}Simple{/t}</span>
		<span {if $part->get('Product Type')=="Shortcut"}class="selected"{/if} id="type_prod_shortcut">{t}Shortcut{/t}</span>
		<span {if $part->get('Product Type')=="Mix"} class="selected"{/if} id="type_prod_mix">{t}Mixture{/t}</span>
	      </div>
	    </td>
	    
	  </tr>
</table>




	    {if $num_parts==0}
	  {t}Choose the part{/t} 
	   
	    <div id="adding_new_part" style="width:400px;margin-bottom:45px"><input id="new_part_input" type="text"><div id="new_part_container"></div></div>
	  
	  {else}
	  
	  
	  
	  
	 	  <table class="edit" border=0  id="part_editor_table"   >
	 	   <tr class="title">
	 	   <td colspan=2 >{t}Part List{/t}</td>
	 	   <td colspan=2>
	 	   <div style="text-align:right;font-weight:100"  id="product_part_items" product_part_key="{$part->get_current_product_part_key()}"  >
	 
	  <span style="margin-right:10px;visibility:hidden" id="save_edit_part"   onclick="save_part()" class="state_details">{t}Save{/t}</span>
	  <span style="margin-right:10px;visibility:hidden" id="reset_edit_part"  onclick="reset_part()" class="state_details">{t}Reset{/t}</span>
	   <span style="margin-right:10px;" onClick="add_part()" id="add_part" class="state_details">{t}Add Part to List{/t}</span>
	  </div>
	 	   </td>
	 	   </tr>
	   
	  {foreach from=$part->get_current_part_list('smarty') key=sku item=part_list}
	   
	   <tr  id="part_list{$sku}" sku="{$sku}" class="top title">
		<td class="label" style="width:150px;font-weight:200">{t}Part{/t}</td>
		<td style="width:120px"><span>{$part_list.part->get_sku()}</span></td>
		<td style="width:350px">{$part_list.part->get('Part XHTML Description')}</td>
		<td>
		<span onClick="remove_part({$sku})" style="cursor:pointer"><img   src="art/icons/delete_bw.png"/> {t}Remove{/t}</span>
		<span onClick="show_change_part_dialog({$sku},this)"  style="cursor:pointer;margin-left:15px"><img  src="art/icons/arrow_refresh_bw.png"/> {t}Change{/t}</span>
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
	  
	  
	  {/if}
	
	
	

</div>





<div class="edit_block" {if $edit!="dimensions"}style="display:none"{/if}  id="d_dimensions">


<div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_product_weight" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_product_weight" class="state_details">{t}Reset{/t}</span>
	
      </div>

<table class="edit" >
 <tr class="title">
 <td colspan=3>{t}Weight{/t}</td>
 </tr>
<tr class="first"><td style="" class="label">{t}Unit Weight{/t}:</td>
   <td  style="text-align:left;width:4em;">
     <div  style="width:4em;position:relative;top:00px" >
       <input style="text-align:left;width:4em" id="Product_Unit_Weight" value="{$part->get('Net Weight Per Unit')}" ovalue="{$part->get('Net Weight Per Unit')}" valid="0"> 
       <div id="Product_Unit_Weight_Container" style="" ></div>
     </div>
    
   </td><td>Kg</td>
   <td style="width:450px" id="Product_Unit_Weight_msg" class="edit_td_alert"></td>
 </tr>
<tr style="display:none">
<td style="" class="label">{t}Outer Weight{/t}:<br/><small>with packing</small></td>
   <td  style="text-align:left">
     <div  style="width:4em;position:relative;top:00px" >
       <input style="text-align:left;width:4em" id="Product_Outer_Weight" value="{$part->get('Product Gross Weight')}" ovalue="{$part->get('Product Gross Weight')}" valid="0">
       <div id="Product_Outer_Weight_Container" style="" ></div>
     </div>
   </td><td>Kg</td>
   <td id="Product_Outer_Weight_msg" class="edit_td_alert"></td>
 </tr>


</table>





</div>

<div class="edit_block" {if $edit!="products"}style="display:none"{/if}  id="d_products">
 
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
	  <img id="change_part_{$part_id}" class="icon" onclick="change_part({$part_id},'{$part}')"  src="art/icons/arrow_refresh_bw.png">
	  <img id="delete_part_{$part_id}" class="icon" onclick="delete_part({$part_id},'{$part}')"  src="art/icons/cross.png">
	  <img id="save_part_{$part_id}" class="icon" style="visibility:hidden" onClick="save_part({$part_id})" src="art/icons/disk.png">
	  <a href="part.php?id={$part_id}">{$part.code}</a>
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
<div class="edit_block" {if $edit!="suppliers"}style="display:none"{/if}  id="d_suppliers">
 
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
	  <img id="change_part_{$part_id}" class="icon" onclick="change_part({$part_id},'{$part}')"  src="art/icons/arrow_refresh_bw.png">
	  <img id="delete_part_{$part_id}" class="icon" onclick="delete_part({$part_id},'{$part}')"  src="art/icons/cross.png">
	  <img id="save_part_{$part_id}" class="icon" style="visibility:hidden" onClick="save_part({$part_id})" src="art/icons/disk.png">
	  <a href="part.php?id={$part_id}">{$part.code}</a>
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
<tr class="first"><td style="" class="label">{t}Units Per Outer{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Product_Units_Per_Case" value="{$part->get('Product Units Per Case')}" ovalue="{$part->get('Product Units Per Case')}" valid="0">
       <div id="Product_Units_Per_Case_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Units_Per_Case_msg" class="edit_td_alert"></td>
 </tr>
<tr><td style="" class="label">{t}Units Type{/t}:</td>
<td  style="text-align:left">

<select>
{foreach from=$unit_type_options key=value item=label}
   <option label="{$label}" value="{$value}" {if $value==$unit_type}selected="selected"{/if}  >{$label}</option>

{/foreach}
</select>


   </td>
   <td id="Product_Units_Type_msg" class="edit_td_alert"></td>
 </tr>


</table>



<div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_product_description" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_product_description" class="state_details">{t}Reset{/t}</span>
	
      </div>

<table class="edit">
 <tr class="title"><td colspan=5>{t}Name / Description{/t}</td></tr>
<tr class="first"><td style="" class="label">{t}Product Name{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Product_Name" value="{$part->get('Product Name')}" ovalue="{$part->get('Product Name')}" valid="0">
       <div id="Product_Name_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Name_msg" class="edit_td_alert"></td>
 </tr>
<tr><td style="" class="label">{t}Special Characteristic{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Product_Special_Characteristic" value="{$part->get('Product Special Characteristic')}" ovalue="{$part->get('Product Special Characteristic')}" valid="0">
       <div id="Product_Special_Characteristic_Container" style="" ></div>
     </div>
   </td>
   <td id="Product_Special_Characteristic_msg" class="edit_td_alert"></td>
 </tr>
<tr><td style="" class="label">{t}Product Description{/t}:</td>
   <td  style="text-align:left">
     <div  style="height:100px;width:25em;position:relative;top:00px" >

<textarea id="Product_Description"  olength="{$part->get('Product Description Length')}"  value="{$part->get('Product Description')}"  ovalue="{$part->get('Product Description')}"  ohash="{$part->get('Product Description MD5 Hash')}" rows="6" cols="42">{$part->get('Product Description')}</textarea>
       
       <div id="Product_Description_Container" style="" ></div>
     </div>
   </td>
   <td id="Product_Description_msg" class="edit_td_alert"></td>
 </tr>

</table>

<table class="edit">
 <tr class="title"><td colspan=5>{t}Categories{/t}</td></tr>
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





</div>


<div id="the_table0" class="data_table" style="margin:20px 20px 0px 20px; clear:both;padding-top:10px">
  <span class="clean_table_title">{t}History{/t}</span>
  {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
  <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>



</div>



</div>


<div id="Editor_change_part" style="xposition:fixed;xtop:-200px;">
  <div style="display:none" class="hd"></div>
    <div class="bd dt-editor" >
          <table border=0>
	    <input type="hidden" id="change_part_sku" value=0 >
	    <tbody id="change_part_selector">
	    <tr><td>{t}Choose the part{/t}</tr>
	    <tr >
	    
	    <td  >
			
			<div  style="width:410px">
			  <input id="change_part" type="text" value=""   >
			  <div id="change_part_container"></div>
			</div>


	      </td>
	    </tr>
	   </tbody>
	   <tbody id="change_part_confirmation" style="display:none">
	   <tr><td>{t}Part{/t}: <span id="change_part_old_part"></span> <br/>{t}will be replaced with{/t}:<br/><span id="change_part_new_part"></span>
	   
	   </td></tr>
	   
	   </tbody>
	   
	  </table>
	  <div style="margin-top:20px">

	    <button id="save_change_part" class="state_details" style="display:none" onclick="save_change_part();" >{t}Save{/t}</button>
	    <button class="state_details" onclick="close_change_part_dialog()" >{t}Cancel{/t}</button>
	  </div>
    </div>
</div>

<div id="Editor_add_part" style="position:fixed;top:-200px;">
  <div style="display:none" class="hd"></div>
    <div class="bd dt-editor" >
          <table border=0>
          
         
          
	    <input type="hidden" id="add_part_sku" value=0 >
	     <input type="hidden" id="add_part_key" value=0 >

	    <tr><td>{t}Add Part{/t}</tr>
	    <tr>
	    
	    <td id="other_part" >
			
			<div id="add_part"  style="width:460px">
			  <input id="add_part_input" type="text" value="" style="width:460px">
			  <div id="add_part_container" style="width:460px"></div>
			</div>


	      </td>
	    </tr>
	   
	  </table>
	  
	  <div>
	  
	  </div>
	  
	  <div class="yui-dt-button">
	    <button style="display:none" onclick="save_add_part();" class="state_details">{t}Save{/t}</button>
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


