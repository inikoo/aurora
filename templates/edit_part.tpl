{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >
{include file='locations_navigation.tpl'}
<div class="branch"> 
  <span>{if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="inventory.php?id={$warehouse->id}">{$warehouse->get('Warehouse Name')} {t}Inventory{/t}</a> &rarr; <a href="warehouse_parts.php?id={$warehouse->id}">{t}Parts{/t}</a> &rarr; {$part->get_sku()}</span>
</div>


<div class="top_page_menu">
    <div class="buttons" style="float:right">
       
        <button  onclick="window.location='part.php?id={$part->sku}'" ><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button>
     
    </div>
    <div class="buttons" style="float:left">

 </div>
    <div style="clear:both"></div>
</div>

<div style="clear:left;margin:0 0px">
    <h1>{t}Editing part{/t}: <span class="id">{$part->get_sku()}</span> <span id="title_description">{$part->get('Part XHTML Description')}</span>  <span style="padding:0;font-size:80%">{t}Sold as{/t}: {$part->get('Part XHTML Currently Used In')}</span> </h1>
    
</div>

<ul class="tabs" id="chooser_ul">

<li><span class="item {if $edit=='activation'}selected{/if}"  id="activation">  <span> {t}Status{/t}</span></span></li>
    <li><span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
    <li><span class="item {if $edit=='products'}selected{/if}" id="products" > <span>{t}Products{/t}</span></span></li>
    <li><span class="item {if $edit=='suppliers'}selected{/if}" id="suppliers" > <span>{t}Suppliers{/t}</span></span></li>
    <li><span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
</ul>

 
<div class="tabbed_container" > 

<div class="edit_block" {if $edit!="activation"}style="display:none"{/if}  id="d_activation">

<table class="edit"  style="width:800px">
 <td class="label" style="width:200px">{t}Keeping Status{/t}:</td>
 <td>
   <div   class="buttons" >
   <button class="{if $part->get('Part Status')=='In Use'}selected{/if} positive" onclick="save_status('Part Status','In Use')" id="Part Status In Use">{t}Keeping{/t}</button> <button class="{if $part->get('Part Status')=='Not In Use'}selected{/if} negative"  onclick="save_status('Part Status','Not In Use')" id="Part Status Not In Use">{t}Not Keeping{/t}</button>
   </div>
 </td>
 <td style="width:300px"></td>
 </tr>

</table>
</div>
<div class="edit_block" {if $edit!="products"}style="display:none"{/if}  id="d_products">
 

  <span class="clean_table_title">{t}Products{/t}</span>
  {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
  <div  id="table1"   class="data_table_container dtable btable" style="font-size:85%"> </div>
    
  
</div>
<div class="edit_block" {if $edit!="suppliers"}style="display:none"{/if}  id="d_suppliers">

  <span class="clean_table_title">{t}Suppliers{/t}</span>
  {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }
  <div  id="table2"   class="data_table_container dtable btable "> </div>

 
 <div style="display:none">
 {t}Add new part{/t} 
  <div id="adding_new_part" style="width:200px;margin-bottom:45px"><input id="new_part_input" type="text"><div id="new_part_container"></div></div>
</div>
  {*}
  <table  class="edit" style="display:none;width:33em"  >
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
  {*}
  
  
  
</div>
<div class="edit_block" {if $edit!="pictures"}style="display:none"{/if}  id="d_pictures">
    {include file='edit_images_splinter.tpl' parent=$part}
</div>
<div class="edit_block" {if $edit!="description"}style="display:none"{/if}"  id="d_description">



<table class="edit"  style="width:890px">
 <tr class="title"><td colspan=5>{t}Unit{/t}</td>
 <td>
 <div class="buttons" >
	<button  style="visibility:hidden"  id="save_edit_part_unit" class="positive">{t}Save{/t}</button>
	<button style="visibility:hidden" id="reset_edit_part_unit" class="negative">{t}Reset{/t}</button>
</div>
 </td>
 
 </tr>

<tr><td style="width:120px" class="label">{t}Units Type{/t}:</td>
<td  style="text-align:left">
<input type="hidden"  id="Part_Unit_Type"  value="{$unit_type}" ovalue="{$unit_type}"/>
<select id="Part_Unit_Type_Select" onChange="part_unit_change(this)">
{foreach from=$unit_type_options key=value item=label}
   <option   label="{$label}" value="{$value}" {if $value==$unit_type}selected="selected"{/if}  >{$label}</option>

{/foreach}
</select>

   </td>
   <td id="Part_Unit_Type_msg" class="edit_td_alert"></td>
 </tr>

<tr class="first"><td  class="label">{t}Unit Description{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:540px" id="Part_Unit_Description" value="{$part->get('Part Unit Description')}" ovalue="{$part->get('Part Unit Description')}" valid="0">
       <div id="Part_Unit_Description_Container"  ></div>
     </div>
   </td>
   <td id="Part_Unit_Description_msg" class="edit_td_alert"></td>
 </tr>
 
 <tr><td style="width:200px" class="label">{t}Gross Weight{/t} (Kg):</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Part_Gross_Weight" value="{$part->get('Part Gross Weight')}" ovalue="{$part->get('Part Gross Weight')}" valid="0">
       <div id="Part_Gross_Weight_Container"  ></div>
     </div>
   </td>
   <td id="Part_Gross_Weight_msg" class="edit_td_alert"></td>
 </tr>
  <tr><td style="width:200px" class="label">{t}Package Volume{/t} (L):</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Part_Package_Volume" value="{$part->get('Part Package Volume')}" ovalue="{$part->get('Part Package Volume')}" valid="0">
       <div id="Part_Package_Volume_Container"  ></div>
     </div>
   </td>
   <td id="Part_Package_Volume_msg" class="edit_td_alert"></td>
 </tr>
    <tr><td style="width:200px" class="label">{t}Package MOV{/t} (L):</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Part_Package_MOV" value="{$part->get('Part Package Minimun Orthogonal Volume')}" ovalue="{$part->get('Part Package Minimun Orthogonal Volume')}" valid="0">
       <div id="Part_Package_MOV_Container"  ></div>
     </div>
   </td>
   <td id="Part_Package_MOV_msg" class="edit_td_alert"></td>
 </tr> 
  <tr><td style="width:200px" class="label">{t}Tariff Code{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Part_Tariff_Code" value="{$part->get('Part Tariff Code')}" ovalue="{$part->get('Part Tariff Code')}" valid="0">
       <div id="Part_Tariff_Code_Container"  ></div>
     </div>
   </td>
   <td id="Part_Tariff_Code_msg" class="edit_td_alert"></td>
 </tr>
 
 
</table>





<table class="edit" border=0 style="width:890px">
 <tr class="title"><td >{t}General Description{/t} <span id="part_general_description_msg"></span></td>
 <td>
 <div class="buttons" >	
	<button  style="margin-right:10px;visibility:hidden"  id="save_edit_part_description" class="state_details">{t}Save{/t}</button>
	<button style="margin-right:10px;visibility:hidden" id="reset_edit_part_description" class="state_details">{t}Reset{/t}</button>
</div>
 </td>
  </tr>
  <tr ><td colspan=2 style="padding:5px 0 0 0 ">
  <form onsubmit="return false;">
  <textarea id="part_general_description" ovalue="{$part->get('Part General Description')|escape}" rows="20" cols="75">{$part->get('Part General Description')|escape}</textarea>
</form>
  </td></tr>
</table>


<table class="edit" border=0 style="width:890px">
 <tr class="title"><td >{t}Health & Safety{/t} <span id="part_health_and_safety_msg"></span></td>
 <td>
 <div class="buttons" >	
	<button  style="margin-right:10px;visibility:hidden"  id="save_edit_part_health_and_safety" class="state_details">{t}Save{/t}</button>
	<button style="margin-right:10px;visibility:hidden" id="reset_edit_part_health_and_safety" class="state_details">{t}Reset{/t}</button>
</div>
 </td>
  </tr>
  <tr ><td colspan=2 style="padding:5px 0 0 0 ">
  <form onsubmit="return false;">
  <textarea id="part_health_and_safety" ovalue="{$part->get('Part Health And Safety')|escape}" rows="20" cols="75">{$part->get('Part Health And Safety')|escape}</textarea>
</form>
  </td></tr>
</table>





{*}

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

<div class="buttons">
	
	<button  style="margin-right:10px;visibility:hidden"  id="save_edit_part_custom_field" class="state_details">{t}Save{/t}</button>
	<button style="margin-right:10px;visibility:hidden" id="reset_edit_part_custom_field" class="state_details">{t}Reset{/t}</button>
	
</div>
	  
<table class="edit">
 <tr class="title"><td colspan=5>{t}Custom Fields{/t}</td></tr>

 
 {foreach from=$show_case key=custom_field_key item=custom_field_value }
 <tr  id="tr_{$custom_field_value.lable}"><td  class="label">{$custom_field_key}:</td>
   <td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Part_{$custom_field_value.lable}" value="{$custom_field_value.value}" ovalue="{$custom_field_value.value}" valid="0">
       <div id="Part_{$custom_field_value.lable}_Container"  ></div>
     </div>
   </td>
   <td>
   <span id="Part_{$custom_field_value.lable}_msg" class="edit_td_alert"></span>
   </td>
 </tr>
{/foreach}


</table>
{*}
 
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


