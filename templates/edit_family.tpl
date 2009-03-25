{include file='header.tpl'}
<div id="bd" >



 <div class="search_box" style="clear:both;margin-right:20px;margin-top:10px" >
    <span class='reset' onclick='window.location="family.php?edit=0"'   >{t}Exit{/t}</span>
 </div>
  

<div style="clear:left;margin-left:20px;width:700px"id="details" class="details" >
  <h1><span style="color:red">{t}Editing Family{/t}:</span> <span id="family_name">{$family->get('Product Family Name')}</span> (<span id="family_code">{$family->get('Product Family Code')}</span>)</h1>
  
  <h2>{t}Family data{/t}</h2>
  <div id="edit_family_form">
    <span style="display:none" id="description_num_changes"></span>
    <div id="description_errors"></div>
    <table >
      <tr><td>{t}Code{/t}:</td><td><input  id="code" onKeyUp="edit_dept_changed(this)"    onMouseUp="edit_dept_changed(this)"  onChange="edit_dept_changed(this)"  name="code" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="{$family->get('Product Family Code')}" ovalue="{$family->get('Product Family Code')}"  /></td></tr>
      <tr><td>{t}Name{/t}:</td><td><input   id="name" onKeyUp="edit_dept_changed(this)"    onMouseUp="edit_dept_changed(this)"  onChange="edit_dept_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="{$family->get('Product Family Name')}"  ovalue="{$family->get('Product Family Name')}"  /></td>
	<td>
	  <span class="save" id="description_save" onclick="save('description')" style="display:none">{t}Update{/t}</span><span class="reset" id="description_reset" onclick="reset('description')" style="display:none">{t}Reset{/t}</span>
      </td></tr>
    </table>
  </div>



  <div id="add_product_form" style="padding:0;margin:0;display:none">
    <h2>{t}Adding new product{/t}</h2>
    <div id="add_family_messages"></div>
    <table >

	<tr><td>{t}Code{/t}:</td><td><input name="code" id="new_code"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)"  name="code" changed=0 type='text' class='text' SIZE="16" value="" MAXLENGTH="16"/></td></tr>
	<tr><td>{t}Name{/t}:</td><td><input name="name"  id="new_name"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)"  name="code" changed=0  type='text'  SIZE="35" MAXLENGTH="80" class='text' value=""   /></td></tr>
	<tr><td>{t}Short descriprtion{/t}:</td><td><input name="sdescription"  id="new_sdescription"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)"  name="code" changed=0  type='text'  SIZE="35" MAXLENGTH="32" class='text' /></td></tr>

	<tr><td>{t}Units/Case{/t}:</td><td><input name="units" id="new_units"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)" SIZE="4" type='text'  MAXLENGTH="20" class='text' /><span style="margin-left:20px;">{t}Type of Unit{/t}:</span>	
	 
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
	<tr><td>{t}Price{/t}:</td><td>Per Outer: <input name="price" type='text'  SIZE="6" MAXLENGTH="20" class='text' /><span id="label_price_per_unit" style="margin-left:15px">Per Unit:</span> <input name="price_unit" id="nwe_price_unit"  type='text'  SIZE="6" MAXLENGTH="20" class='text' /></td></tr>
	<tr><td>{t}Retail Price{/t}:</td><td>Per Outer:  <input name="rrp" type='text'  SIZE="6" MAXLENGTH="20" class='text' /><span id="label_price_per_unit" style="margin-left:15px">Per Unit:</span> <input name="rrp_unit" id="new_rrp_unit" type='text'  SIZE="6" MAXLENGTH="20" class='text' /></td></tr>

	<tr style="height:40px"><td style="vertical-align:middle">{t}Parts{/t}:</td><td style="vertical-align:middle">
	    <span class="save" onclick="create_part()">Create Part</span>
	    <span class="save" onclick="guess_part()">Guess Part</span>
	    <span class="save"  onclick="assing_part()">Assign Part</span>

	    <span style="margin-left:10px;display:none" id="dmenu_label">{t}SKU/description{/t}:</span><span id="dmenu_position"></span>
	    <div  id="dmenu" style="width:30em;position:relative;left:22.6em;bottom:17px;display:none ">
	      <input name="dmenu_input" id="dmenu_input" type='text'  SIZE="32" MAXLENGTH="20" class='text' />
	      <div id="dmenu_container"></div></div>


</td></tr>



	<tr><td></td><td>
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



	<td>
	  <span class="save" id="add_new_family" onclick="save_new_family()" style="display:none">Add</span>
      </td></tr>
    </table>
  </div>

</div>


  <div   class="data_table" style="margin:0px 20px">
    <span class="clean_table_title">{t}Products{/t}</span> <span class="new" style="font-size:90%" id="add_new_product">{t}Add Product{/t}</span> <span class="multiple" style="font-size:90%" id="restrictions" value="for_sale" on click="change_multiple(this)"  >{t}For Sale{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" style="display:none" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>

  
</div> 
{include file='footer.tpl'}
