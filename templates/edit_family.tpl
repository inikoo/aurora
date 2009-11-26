{include file='header.tpl'}
<div id="bd" >
  <div id="sub_header">
    <span class="nav2 onleft" style="">{t}Editing Family{/t}: <span id="title_name" style="font-style: italic;">{$family->get('Product Family Name')}</span> (<span id="title_code" style="font-style: italic;">{$family->get('Product Family Code')}</span>)</span>
    <span class="nav2 onright" style="margin-left:20px"><a href="family.php?edit=0">{t}Exit edit{/t}</a></span>
  </div>

  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='discounts'}selected{/if}"  id="discounts">  <span> {t}Discounts{/t}</span></span></li>
    <li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
    <li> <span class="item {if $edit=='products'}selected{/if}" id="products"  ><span> {t}Products{/t}</span></span></li>
    <li> <span class="item {if $edit=='web'}selected{/if} " id="web" ><span> {t}Web Pages{/t}</span></span></li>
  </ul>
 <div class="tabbed_container" > 
    <div id="info_name" style="margin-left:20px;float:left;width:260px;{if !($edit=='discounts' or $edit=='pictures')  }display:none{/if}">
	<table    class="show_info_product">
	  <tr>
	    <td>{t}Family Code{/t}:</td><td  class="aright">{$family->get('Product Family Code')}</td>
	  </tr>
	  <tr>
	    <td>{t}Family Name{/t}:</td><td  class="aright">{$family->get('Product Family Name')}</td>
	  </tr>
	</table>
      </div>
    <div  class="edit_block" style="{if $edit!="description"}display:none{/if}"  id="d_description">
       <span style="display:none" id="description_num_changes"></span>
    <div id="description_errors"></div>
    <table >
      <tr><td>{t}Code{/t}:</td><td><input  id="code" onKeyUp="edit_family_changed(this)"    onMouseUp="edit_family_changed(this)"  onChange="edit_family_changed(this)"  name="code" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="{$family->get('Product Family Code')}" ovalue="{$family->get('Product Family Code')}"  /></td></tr>
      <tr><td>{t}Name{/t}:</td><td><input   id="name" onKeyUp="edit_family_changed(this)"    onMouseUp="edit_family_changed(this)"  onChange="edit_family_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="{$family->get('Product Family Name')}"  ovalue="{$family->get('Product Family Name')}"  /></td>
	<td>
	  <span class="save" id="description_save" onclick="save('description')" style="display:none">{t}Update{/t}</span><span class="reset" id="description_reset" onclick="reset('description')" style="display:none">{t}Reset{/t}</span>
      </td></tr>
    </table>
    </div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="pictures"}display:none{/if}"  id="d_pictures">
	
      </div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="discounts"}display:none{/if}"  id="d_discounts">
		<div  class="new_item_dialog"  id="new_deal_dialog" style="display:none">
	   <div id="new_deal_messages" class="messages_block"></div>
	   <table class="edit" >
	     <tr><td>{t}Deal Name{/t}:</td><td><input  id="new_deal_name" onKeyUp="new_deal_changed(this)"    onMouseUp="new_deal_changed(this)"  onChange="new_deal_changed(this)"  changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
	     <tr><td>{t}Deal Description{/t}:</td><td><input   id="new_deal_description" onKeyUp="new_deal_changed(this)"    onMouseUp="new_deal_changed(this)"  onChange="new_deal_changed(this)" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td>
	     </tr>
	  </table>
	 </div>
	 
	 <div   class="data_table" sxtyle="margin:25px 10px;">
	   <span class="clean_table_title">{t}Deals{/t}</span>
	  <table class="options" style="float:right;padding:0;margin:0">
	    <tr>
	      <td  id="add_deal">Add Deal</td>
	      <td  style="display:none" id="save_new_deal">Save New Deal</td>
	      <td  style="display:none" id="cancel_add_deal">Cancel</td>
	    </tr>
	  </table>
	  <div  class="clean_table_caption"  style="clear:both;">
	    <div style="float:left;"><div id="table_info4" class="clean_table_info"><span id="rtext4"></span> <span class="rtext_rpp" id="rtext_rpp4"></span> <span class="filter_msg"  id="filter_msg4"></span></div></div>
	    <div class="clean_table_filter" style="display:none" id="clean_table_filter4"><div class="clean_table_info"><span id="filter_name4">{$filter_name4}</span>: <input style="border-bottom:none" id='f_input4' value="{$filter_value0}" size=10/><div id='f_container4'></div></div></div>
	    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator4"></span></div></div>
	  </div>
	  <div  id="table4"   class="data_table_container dtable btable "> </div>
	 </div>
      </div>
      
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="web"}display:none{/if}"  id="d_web">
      </div>
      
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="products"}display:none{/if}"  id="d_products">
	<div   style="margin:0 0 10px 0;padding:10px;border:1px solid #ccc;xdisplay:none"  id="new_product_dialog" >
	  <div id="new_product_messages" class="messages_block"></div>
	  <table class="edit" >
	    <tr><td class="label" style="width:7em">{t}Code{/t}:</td><td><input name="code" id="new_code"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)"  name="code" changed=0 type='text' class='text' SIZE="16" value="" MAXLENGTH="16"/></td></tr>
    	<tr><td class="label" >{t}Name{/t}:</td><td><input name="name"  id="new_name"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)"  name="code" changed=0  type='text'  SIZE="35" MAXLENGTH="80" class='text' value=""   /></td></tr>
	    <tr><td class="label">{t}Special Char{/t}:</td><td><input name="sdescription"  id="new_sdescription"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)"  name="code" changed=0  type='text'  SIZE="35" MAXLENGTH="32" class='text' /></td></tr>
	    <tr><td class="label">{t}Units/Case{/t}:</td><td><input name="units" id="new_units"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)" SIZE="4" type='text'  MAXLENGTH="20" class='text' /><span style="margin-left:20px;">{t}Type of Unit{/t}:</span>	
	 
<div class="options" style="margin:5px 0;display:inline">
  {foreach from=$units_tipo item=unit_tipo key=part_id }
<span {if $unit_tipo.selected}class="selected"{/if} id="unit_tipo_{$unit_tipo.name}">{$unit_tipo.fname}</span>
{/foreach}
</div>

   <select style="display:none" name="units_tipo"  id="units_tipo" >
	      {foreach from=$units_tipo item=tipo key=tipo_id }
	      <option value="{$tipo_id}">{$tipo}</option>
	      {/foreach}
	</select></td></tr>
	<tr><td class="label">{t}Price{/t}:</td><td>Per Outer: <input name="price" type='text'  SIZE="6" MAXLENGTH="20" class='text' /><span id="label_price_per_unit" style="margin-left:15px">Per Unit:</span> <input name="price_unit" id="nwe_price_unit"  type='text'  SIZE="6" MAXLENGTH="20" class='text' /></td></tr>
	<tr><td class="label">{t}Retail Price{/t}:</td><td>Per Outer:  <input name="rrp" type='text'  SIZE="6" MAXLENGTH="20" class='text' /><span id="label_price_per_unit" style="margin-left:15px">Per Unit:</span> <input name="rrp_unit" id="new_rrp_unit" type='text'  SIZE="6" MAXLENGTH="20" class='text' /></td></tr>

	<tr style="height:40px"><td style="vertical-align:middle" class="label">{t}Parts{/t}:</td><td style="vertical-align:middle">
	    <span class="save" onclick="create_part()">Create Part</span>
	    
	    <span class="save"  onclick="assing_part()">Assign Part</span>

	    <span style="margin-left:10px;display:none" id="dmenu_label">{t}SKU/description{/t}:</span><span id="dmenu_position"></span>
	    <div  id="dmenu" style="width:30em;position:relative;left:22.6em;bottom:17px;display:none ">
	      <input name="dmenu_input" id="dmenu_input" type='text'  SIZE="32" MAXLENGTH="20" class='text' />
	      <div id="dmenu_container"></div></div>


</td></tr>

<tr><td colspan="2">
	    <div id="new_part_container"  class=""  style="border:1px solid #ccc">
	   <table class="edit" >
	    <tr><td class="label" style="width:7em">{t}Description{/t}:</td><td><input name="code" id="new_part_description"  name="code" changed=0 type='text' class='text' SIZE="16" value="" MAXLENGTH="16"/></td></tr>
    	<tr><td class="label" >{t}Gross Weight{/t}:</td><td><input name="name"  id="new_name"     name="code" changed=0  type='text'  SIZE="6" MAXLENGTH="80" class='text' value=""   /> Kg</td></tr>
	    <tr><td class="label">{t}Supplier{/t}:</td>
	     <td  style="text-align:left">
	      <div  style="width:15em;position:relative;top:00px" >
		<input id="supplier" style="text-align:left;width:18em" type="text">
		<div id="supplier_container" style="" ></div>
	      </div>
	    </td>
	  </tr>
	  <input id="supplier_key" value="1" type="hidden">
    <tr><td class="label">{t}Supplier Part Code{/t}:</td><td><input name="units" id="new_units"  SIZE="4" type='text'  MAXLENGTH="20" class='text' /><span style="margin-left:20px;">{t}Type of Unit{/t}:</span>	
	    <tr><td class="label">{t}Supplier Part Cost{/t}:</td><td><input name="units" id="new_units"  SIZE="4" type='text'  MAXLENGTH="20" class='text' /><span style="margin-left:20px;">{t}Type of Unit{/t}:</span>	

	 </table>
	    </div>
	</td></tr>


<tr><td colspan="2">
	    <div id="new_part_container"  class=""  style="border:1px solid #ccc">
	   <table class="edit" >
	  
	   <tr><td class="label" style="width:7em">{t}Part{/t}:</td>
	     <td  style="text-align:left">
	      <div  style="width:20em;position:relative;top:00px" >
		<input id="part" style="text-align:left;width:23em" type="text">
		<div id="part_container" style="" ></div>
	      </div>
	    </td>
	  </tr>
	  <input id="part_sku" value="" type="hidden">
	    
	    
	   	    
	
	 </table>
	    </div>
	</td></tr>


	<tr><td colspan="2">
	    <div id="parts_list_container"  class="data_table_container dtable btable " >
	      <table  id="table_parts_list">
		<thead> 
		  <tr><th>{t}SKU{/t}</th><th>Part Description</th><th>Used in</th><th>Parts per Pick</th><th>Note to Picker</th><th></th></tr>
		</thead>
		<tbody> 

		  
		</tbody> 
	      </table>
	    </div>
	</td></tr>


    
	  </table>
	</div>

	 <div   class="data_table" >

    <span class="clean_table_title">{t}Products{/t}</span> 
    
    <table class="options" style="float:right;padding:0;margin:0;">
      <tr>
	<td  id="add_product">Add Product</td>
	<td  style="display:none" id="save_new_product">Save New Product</td>
	<td  style="display:none" id="cancel_add_product">Cancel</td>
      </tr>
    </table>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
     <span style="float:right;margin-left:80px" class="state_details"  id="restrictions" value="for_sale" on click="change_multiple(this)"  >{t}products for sale{/t}</span>
   
<table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr><td  {if $view=='view_state'}class="selected"{/if} id="view_state" >{t}State{/t}</td>
	  {if $view_stock}<td {if $view=='view_name'}class="selected"{/if}  id="view_name"  >{t}Name{/t}</td>{/if}
	  {if $view_sales}<td  {if $view=='view_price'}class="selected"{/if}  id="view_price"  >{t}Price{/t}</td>{/if}
	</tr>
</table>


   

    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" style="display:none" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>

      </div>

 </div>



  
  
 
 
 

  
</div> 
{include file='footer.tpl'}
