{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >

<div class="search_box" style="margin-top:15px">
  <div class="general_options">
    {foreach from=$general_options_list item=options }
        {if $options.tipo=="url"}
            <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
        {else}
            <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
        {/if}
    {/foreach}
    </div>
</div>
<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Product{/t}: <span id="title_name">{$product->get('Product Name')}</span> (<span id="title_code">{$product->get('Product Code')}</span>)</h1>
</div>



  
  <ul class="tabs" id="chooser_ul">
    <li><span  class="item {if $edit=='config'}selected{/if}" id="config" > <span>{t}Parts{/t}</span></span></li>
    <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
    <li> <span class="item {if $edit=='prices'}selected{/if}" id="prices"  ><span> {t}Price, Discounts{/t}</span></span></li>
    <li> <span class="item  {if $edit=='dimat'}selected{/if}" id="dimat" ><span> {t}Dimensions{/t}</span></span></li>
    <li> <span class="item {if $edit=='web'}selected{/if} " id="web" ><span> {t}Web Pages{/t}</span></span></li>
	</ul>
  
 
     <div class="tabbed_container" > 
 
      
      <div style="clear:both;height:.1em;padding:0px 20px;;margin:20px auto;xborder-top: 1px solid #cbb;;xborder-bottom: 1px solid #caa;width:770px;" id="description_messages">
	
	<div id="info_name" style="float:left;width:260px;{if !( $edit=='pictures') }display:none{/if}">
	  
	  <table    class="show_info_product">
	    <tr>
	      <td colspan="2" class="aright product_name" id="l_product_name" >{$product->get('Product Name')}</td>
	    </tr>
	    <tr>
	      <td>{t}Code{/t}:</td><td  class="aright">{$product->get('Product Code')}</td>
	    </tr>
	    <tr>
	      <td>{t}Store{/t}:</td><td  class="aright">{$store->get('Store Name')}</td>
	    </tr>
	    <tr>
	      <td>{t}Family{/t}:</td><td  class="aright">{$product->get('Product Family Code')}</td>
	    </tr>
 
	    
	  </table>
	</div>
	<div  id="info_price"  style="float:left;width:260px;margin-left:20px;display:none">
  
	  <table    class="show_info_product">
	    <tr style="{if $product->get('Product Units Per Case')==1}display:none{/if}">
	      <td>{t}Unit per Case{/t}:</td><td  class="aright">{$product->get('Units')}</td>
	    </tr>
	    <tr>
	      <td>{t}Cost{/t}:</td><td  id="l_formated_cost" class="aright">
		{$product->get('Formated Cost')}
	      </td>
	    </tr>
	    <td>{t}Price{/t}:</td><td  id="l_formated_price" class="price aright">
	      {$product->get('Formated Price')}
	    </td>
</tr>
<tr id="tr_rrp_per_unit" {if $product->get('Product RRP')==''}style="display:none"{/if} >
	    <td>{t}RRP{/t}:</td><td id="l_formated_rrp_per_unit" class="aright">{$product->get('RRP Per Unit')}</td>
	  </tr>
	</table>
</div>


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
	
	
	<table style="margin:0;width:500px"  class="edit">
	  <tr>
	    <td >{t}Type of Product{/t}:</td>
	    
	    <td >
	      <div class="options" style="margin:5px 0">
		<span {if $product->get('Product Type')=="Normal"}class="selected"{/if} id="type_prod_normal">{t}Simple{/t}</span>
		<span {if $product->get('Product Type')=="Shortcut"}class="selected"{/if} id="type_prod_shortcut">{t}Shortcut{/t}</span>
		<span {if $product->get('Product Type')=="Mix"} class="selected"{/if} id="type_prod_mix">{t}Mixture{/t}</span>
	      </div>
	    </td>
	    
	  </tr>

	    {if $num_parts==0}
	  {t}Choose the part{/t} 
	    </table>
	    <div id="adding_new_part" style="width:200px;margin-bottom:45px"><input id="new_part_input" type="text"><div id="new_part_container"></div></div>
	  
	  {else}
	 
	    <tbody id="current_parts_form">
	      
	      {foreach from=$parts item=part key=part_id }
	      
	      <tr  id="sup_tr1_{$part_id}" class="top title">
		<td class="label" style="font-weight:200">Part</td>
		<td >
		{$part.description} ({t}SKU{/t}{$part.sku})
		</td>
	      </tr>
	      <tr id="sup_tr2_{$part_id}">
		<td class="label" style="width:15em">{t}Parts Per Product{/t}:</td>
		<td style="text-align:left;">
		  <input style="padding-left:2px;text-align:left;width:3em" value="{$part.parts_per_product}" name="parts_per_product"  changed=0           onkeyup="part_changed(this,{$part_id})" ovalue="{$part.parts_per_product}" id="v_part_code{$part_id}"></td>
	      </tr>
	      <tr id="sup_tr3_{$part_id}" class="last">
		<td class="label">{t}Note For Pickers{/t}:</td>
	<td style="text-align:left"><input id="v_part_cost{$part_id}" style="text-align:right;width:16em"  name="notes" onblur="part_changed(this,{$part_id})"  value="{$part.note}" ovalue="{$part.note}" ></td>
	      </tr>
	      {/foreach}
	    </tbody>
	    
	  </table>	  
	  
	  {t}Change Part{/t} 
	  <div id="adding_new_part" style="width:200px;margin-bottom:45px"><input id="new_part_input" type="text"><div id="new_part_container"></div></div>
	  
	  
	  {/if}
	
	
	<div {if $data.product_tipo!='dependant'}display="none"{/if} >
	  <table class="edit" border=0>
	    <tr>
	      
	      <td  class="label" style="width:10em">{t}Units Definition{/t}:</td>
	      
	      <td colspan=2>
		<div class="options" style="margin:5px 0">
		  {foreach from=$units_tipo item=unit_tipo key=part_id }<span {if $unit_tipo.selected}class="selected"{/if} id="unit_tipo_{$unit_tipo.name}">{$unit_tipo.fname}</span>{/foreach}
		</div>
	      </td>
	      
	      
	    </tr>
	    
	    <tr>
	      <td class="label">{t}Units Per Outer{/t}:</td>
	      <td><span id="units">{$units}</span>
		<input 
		   id="v_units" 
		   ovalue="{$units}" 
		   name="units" 
		   value="{$product->get('Product Units Per Case')}"  
		   style="display:none;text-align:right;width:5em"     
		   onkeydown="to_save_on_enter(event,this)" 
		   onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thousand_sep}',3);units_changed(this)" />
	</td>
	      <td style="width:5em">
		<span 
		   onclick="change_units()" 
		   id="change_units_but" 
		   style="cursor:pointer;text-decoration:underline;color:#777">{t}Change{/t}</span>
		<span id="change_units_diff" style="display:none"></span>
	      </td>
	      <td>
		<span 
		   id="units_cancel" 
		   style="cursor:pointer;visibility:hidden;color:#777" 
		   onclick="units_cancel()">
		  {t}Cancel{/t}
		</span>
		<span 
		   id="units_save"   
		   style="margin-left:10px;cursor:pointer;visibility:hidden;color:#777" 
	     onclick="units_save()">
		  {t}Save{/t} <img src="art/icons/disk.png"/>
		</span>
		

	      </td>
	    </tr>
	    <tr style="display:none" id="change_units_price">
	      <td class="label">{t}Outers Sale Price{/t}:</td>
	      <td>{$currency} <input  
				 onkeydown="to_save_on_enter(event,this)"  
				 onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thousand_sep}',2);price_fcu_changed(this)" 
				 style="text-align:right;width:6em"  
				 factor="{$factor_inv_units}" 
				 name="price_fcu" 
				 id="v_price_fcu" 
				 value="{$data.price}"  
			   ovalue="{$data.price}" >
	      </td>
	      <td><span id="change_units_price_diff"></span></td>
	    <tr>
	      

	    <tr style="display:none" id="change_units_oweight">
	      <td class="label">{t}Outers Weight{/t}:</td>
	      <td >{t}Kg{/t} <input 
				style="text-align:right;width:5em"  
				onkeydown="to_save_on_enter(event,this)" 
				id="v_oweight_fcu"  
				tipo="number" 
				value="{$data.oweight}"  
				name="oweight"  
				ovalue="{$data.oweight}" 
				onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thousand_sep}',3);oweight_fcu_changed(this)"  >
	      </td>
	      <td ><span id="change_units_oweight_diff"></span></td>
	    </tr>
	    
	    <tr style="display:none" id="change_units_odim">
	      <td class="label">{t}Outers Dimensions{/t}:</td>
	      <td ><span style="cursor:pointer">{$data.odim_tipo_name}</span> 
		<input 
		   style="text-align:right;width:6em" 
		   onkeydown="to_save_on_enter(event,this)"   
		   onblur="odim_fcu_changed(this)" 
		   tipo="shape1"  
		   name="odim"   
		   id="v_odim_fcu" 
		   value="{$data.odim}"   
	     ovalue="{$data.odim}">
	      </td>
	      <td ><span id="change_units_odim_diff"></span></td>
	      
	    </tr>
	    <tr style="display:none" id="change_units_odim_example">
	
	      <td style="font-size:90%;color:#777" colspan=2><img id="odim_alert_fcu" src="art/icons/exclamation.png" title="{t}Wrong Format{/t}"  style="cursor:pointer;;visibility:hidden;float:left" /> {$shape_example[$data.odim_tipo]}</td>
	      <td></td>
	    <tr>
	
    </table>
  </div>

</div>
<div class="edit_block" {if $edit!="web"}style="display:none"{/if}  id="d_web">
  <table class="edit" >

    {foreach from=$web_pages item=page }
    <tr><td><input id="pagetitle{$page.id}" value="{$page.title}" ovalue="{$page.title}"/></td><td><input id="pageurl{$page.id}" value="{$page.url}" ovalue="{$page.url}"/></td><td><img src="art/icons/cross.png"/></td><td><img src="art/icons/disk.png"/></td></tr>
    {/foreach}
      
    
  </table>
</div>
<input id="v_cost" value="{$product->get('Product Cost')}" type="hidden"/>
<div class="edit_block" {if $edit!="prices"}style="display:none"{/if}  id="d_prices">

<div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_product_price" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_product_price" class="state_details">{t}Reset{/t}</span>
	
      </div>


<table class="edit" border=0>
 <tr class="title"><td colspan=5>{t}Price{/t}</td></tr>

<tr class="first"><td style="" class="label">{t}Price per Outer{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:7em;position:relative;top:00px" >
       <input style="text-align:left;width:8em" id="Product_Price" value="{$product->get('Price')}" ovalue="{$product->get('Price')}" valid="0">
       <div id="Product_Price_Container" style="" ></div>
     </div>
   </td>
<td id="price_per_unit" cost="{$product->get('Product Cost')}"  old_price="{$product->get('Product Price')}"  units="{$product->get('Product Units Per Case')}">{$product->get_formated_price_per_unit()}</td>
<td id="price_margin">{t}Margin{/t}: {$product->get('Margin')}</td>

   <td style="width:200px" id="Product_Price_msg" class="edit_td_alert"></td>
 </tr>

<tr class="first"><td style="" class="label">{t}RRP per Unit{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:7em;position:relative;top:00px" >
       <input style="text-align:left;width:8em" id="Product_RRP" value="{$product->get('RRP')}" ovalue="{$product->get('RRP')}" valid="0">
       <div id="Product_RRP_Container" style="" ></div>
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
 <tr class="title"><td colspan=3>{t}Weight{/t}</td></tr>
<tr class="first"><td style="" class="label">{t}Unit Weight{/t}:</td>
   <td  style="text-align:left;width:4em;">
     <div  style="width:4em;position:relative;top:00px" >
       <input style="text-align:left;width:4em" id="Product_Unit_Weight" value="{$product->get('Net Weight Per Unit')}" ovalue="{$product->get('Net Weight Per Unit')}" valid="0"> 
       <div id="Product_Unit_Weight_Container" style="" ></div>
     </div>
    
   </td><td>Kg</td>
   <td style="width:450px" id="Product_Unit_Weight_msg" class="edit_td_alert"></td>
 </tr>
<tr><td style="" class="label">{t}Outer Weight{/t}:<br/><small>with packing<small/></td>
   <td  style="text-align:left">
     <div  style="width:4em;position:relative;top:00px" >
       <input style="text-align:left;width:4em" id="Product_Outer_Weight" value="{$product->get('Product Gross Weight')}" ovalue="{$product->get('Product Gross Weight')}" valid="0">
       <div id="Product_Outer_Weight_Container" style="" ></div>
     </div>
   </td><td>Kg</td>
   <td id="Product_Outer_Weight_msg" class="edit_td_alert"></td>
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

<div  class="edit_block" style="margin:0;padding:0;{if $edit!="description"}display:none{/if}"  id="d_description">

<div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_product_description" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_product_description" class="state_details">{t}Reset{/t}</span>
	
      </div>

<table class="edit">
 <tr class="title"><td colspan=5>{t}Units{/t}</td></tr>
<tr class="first"><td style="" class="label">{t}Units Per Outer{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Product_Units_Per_Case" value="{$product->get('Product Units Per Case')}" ovalue="{$product->get('Product Units Per Case')}" valid="0">
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
       <input style="text-align:left;width:18em" id="Product_Name" value="{$product->get('Product Name')}" ovalue="{$product->get('Product Name')}" valid="0">
       <div id="Product_Name_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Product_Name_msg" class="edit_td_alert"></td>
 </tr>
<tr><td style="" class="label">{t}Special Characteristic{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Product_Special_Characteristic" value="{$product->get('Product Special Characteristic')}" ovalue="{$product->get('Product Special Characteristic')}" valid="0">
       <div id="Product_Special_Characteristic_Container" style="" ></div>
     </div>
   </td>
   <td id="Product_Special_Characteristic_msg" class="edit_td_alert"></td>
 </tr>
<tr><td style="" class="label">{t}Product Description{/t}:</td>
   <td  style="text-align:left">
     <div  style="height:100px;width:25em;position:relative;top:00px" >

<textarea id="Product_Description"  olength="{$product->get('Product Description Length')}"  value="{$product->get('Product Description')}"  ovalue="{$product->get('Product Description')}"  ohash="{$product->get('Product Description MD5 Hash')}" rows="6" cols="42">{$product->get('Product Description')}</textarea>
       
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
  <div  id="clean_table_caption0" class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div id="clean_table_filter0" class="clean_table_filter" style="display:none">
      <div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
  </div>
  <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>



</div>



</div>




{include file='footer.tpl'}


