{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>



<div id="bd" >

<div id="sub_header">
{if $next.id>0}<span class="nav2 onright"><a href="edit_product.php?id={$next.id}">{t}Next{/t}</a></span>{/if}
{if $prev.id>0}<span class="nav2 onright" ><a href="edit_product.php?id={$prev.id}">{t}Previous{/t}</a></span>{/if}
<span class="nav2 onright" style="margin-left:20px"><a href="assets_family.php?id={$family_id}">{t}Up{/t}</a></span>
<span class="nav2 onright"><a href="products.php">{t}Product index{/t}</a></span>
<span class="nav2"><a href="departments.php">{$home}</a></span>
<span class="nav2"><a href="department.php?id={$department_id}">{$department}</a></span>
<span class="nav2"><a href="family.php?id={$family_id}">{$family}</a></span>
</div>
<div id="doc3" style="clear:both;" class="yui-g yui-t4" >
<div id="yui-main"> 
<h1>{$data.code} {$data.units}x {$data.description}</h1>
<div class="chooser" >
  <ul>
    <li id="description" {if $edit=='description'}class="selected"{/if} > <img src="art/icons/information.png"> {t}Description{/t}</li>
    <li id="pictures" {if $edit=='pictures'}class="selected"{/if} > <img src="art/icons/photos.png"> {t}Pictures{/t}</li>
    <li id="prices" {if $edit=='prices'}class="selected"{/if} ><img src="art/icons/money_add.png"> {t}Price, Discounts{/t}</li>
    <li id="suppliers" {if $edit=='suppliers'}class="selected"{/if} ><img src="art/icons/cog_add.png"> {t}Suppiers{/t}</li>
    <li id="dimat" {if $edit=='dimat'}class="selected"{/if} ><img src="art/icons/shape_ungroup.png"> {t}Dimensions, Materials{/t}</li>
  </ul>
</div> 

<div style="clear:both;padding:20px 20px" id="edit_messages"></div>

<div  {if $edit!="prices"}style="display:none"{/if}  class="edit_block" id="d_prices">
  <table class="edit" >
    <tr><td></td><td>{t}Price per Outer{/t}</td><td>{t}Price per Units{/t}</tr>
	
    <tr><td class="label">{t}Sale Price{/t}:</td><td>{$currency}<input style="text-align:right;width:10em"  id="v_price" value="{$data.price}" ></td></tr>
    <tr><td class="label">{t}Recomended Retail Price{/t}:</td><td></td><td>{$currency}<input style="text-align:right;width:10em"  id="v_price" value="{$data.price}" ></td><td></td></tr>
      
    
  </table>
</div>
<div  {if $edit!="dimat"}style="display:none"{/if}  class="edit_block" id="d_dimat">

<table class="edit" >
  <tr><td class="label">{t}Unit Weight{/t} ({t}Kg{/t}):</td><td colspan=3 class="text-align:left"><input style="float:left;text-align:right;width:10em"   id="weight" tipo="number" value="{$data.weight}"  onkeyup="change_element(this)" ovalue="{$data.weight}"></td><td class="icon"><img id="save_weight" style="cursor:pointer;display:none" onClick="simple_save('weight')" src="art/icons/disk.png"></td></tr>
  <tr><td class="label">{t}Outer Weight{/t} ({t}Kg{/t}):</td><td colspan=2><input style="float:left;text-align:right;width:10em"  id="oweight"  tipo="number" value="{$data.oweight}"  ovalue="{$data.oweight}" onkeyup="change_element(this)"  ></td><td><img id="save_oweight" style="cursor:pointer;display:none" onClick="simple_save('oweight')" src="art/icons/disk.png"></td></tr></tr>
</table>
 <table class="edit" >
 <tr><td class="label">{t}Unit Dimensions{/t}:</td><td><span id="dim_shape">{$data.dim_tipo}</span></td><td><input style="text-align:right;width:10em"  onkeyup="change_element(this)" tipo="shape{$data.dim_tipo_id}" id="dim" value="{$data.dim}" ovalue="{$data.dim}"   ></td><td style="font-size:90%;color:#777" id="dim_shape_example">{$shape_example[$data.dim_tipo_id]}</td><td><img id="save_dim" style="cursor:pointer;display:none" onClick="simple_save('dim')" src="art/icons/disk.png"></td></tr>
  <tr><td class="label">{t}Outer Dimensions{/t}</td><td><span>{$data.odim_tipo}</span></td><td><input style="text-align:right;width:10em"  onkeyup="change_element(this)" tipo="shape1"  id="odim" value="{$data.odim}"   ovalue="{$data.odim}"      ></td><td style="font-size:90%;color:#777">{$shape_example[$data.odim_tipo_id]}</td><td><img id="save_odim" style="cursor:pointer;display:none" onClick="simple_save('odim')" src="art/icons/disk.png"></td></tr>
</tr>
</table>



</div>
<div  {if $edit!="suppliers"}style="display:none"{/if}  class="edit_block" id="d_suppliers">
  {t}Add new supplier{/t} 
  <div id="adding_new_supplier" style="width:200px;margin-bottom:45px"><input id="new_supplier_input" type="text"><div id="new_supplier_container"></div></div>

  
  <table  class="edit"  >
    <tbody id="new_supplier_form" style="display:none;background:#f1fdf2"  supplier_id="" >
    <tr class="top title"><td class="label">{t}Supplier{/t}: <img id="save_supplier_{$supplier_id}" src="art/icons/new.png"></td><td id="new_supplier_name"></td><td class="icon"><img id="save_supplier_new" onClick="save_new_supplier()"   src="art/icons/disk.png"></td><td class="icon"><img id="cancel_new" onClick="cancel_new_supplier()" src="art/icons/cross.png"></td></tr>
    <tr><td class="label">{t}Suppliers product code{/t}:</td><td colspan=3><input style="text-align:right;width:10em" value="" id="new_supplier_code" value="" ></td></tr>
    <tr class="last"><td class="label">{t}Price (Unit){/t}:</td><td colspan=3>{$cur_symbol}<input style="text-align:right;width:6em" value="" id="new_supplier_cost" id=""></td></tr>
    <tr><td style="background:white" colspan="4"></td></tr>
    </tbody>
    <tbody id="current_suppliers_form">
    {foreach from=$suppliers_name item=supplier key=supplier_id }
    <tr class="top title"><td class="label">{t}Supplier{/t}:</td><td><a href="supplier.php?id={$supplier_id}">{$supplier}</a> {if $supplier!=$suppliers_name[$supplier_id] }{$suppliers_name[$supplier_id]}{/if}</td><td class="icon"><img id="save_supplier_{$supplier_id}" style="cursor:pointer;display:none" onClick="save_supplier({$supplier_id})" src="art/icons/disk.png"></td><td class="icon"><img style="cursor:pointer" id="delete_supplier_{$supplier_id}" src="art/icons/cross.png"></td></tr>
    <tr><td class="label">{t}Suppliers product code{/t}:</td><td colspan=3><input style="text-align:right;width:10em" value="{$suppliers_code[$supplier_id]}"  supplier_id="{$supplier_id}"      tipo="text" onkeyup="change_element(this)" ovalue="{$suppliers_code[$supplier_id]}" id="v_supplier_code{$supplier_id}"></td></tr>
    <tr class="last"><td class="label">{t}Price (Unit){/t}:</td><td colspan=3>{$cur_symbol}<input id="v_supplier_cost{$supplier_id}" style="text-align:right;width:6em"  supplier_id="{$supplier_id}"  tipo="money" onkeyup="change_element(this)" value="{$suppliers_num_price[$supplier_id]}" ovalue="{$suppliers_num_price[$supplier_id]}" ></td></tr>
    <tr><td colspan="4"></td></tr>
    {/foreach}
    </body>
    
</table>	  
</div>

<div  {if !$edit=="pictures"}style="display:none"{/if}  class="edit_block" id="d_pictures">




</div>
<div  {if $edit!="description"}style="display:none"{/if} class="edit_block" id="d_description">
  <form id="f_description">
    <input type="hidden" name="tipo" value="update_product">
    <input type="hidden" name="product_id" value="{$product_id}">
    <input type="hidden" id="v_cat" name="v_cat" value="{$v_cat}">
    
    
    <table style="margin:0;" border=0>
      <tr><td><img style="visibility:hidden"  id="c_categories" src="art/icons/accept.png" /></td>
	<td style="vertical-align: top;" >{t}Categories{/t}:</td>
	<td style="vertical-align: top;" >
<table id="cat_list" style="border-right:1px solid #ccc;float:left;margin:0 20px 0 0 ">
  {if $num_cat==0}<tr><td>{t}No assigned catories{/t}<td></td>{/if}
    {foreach from=$cat key=cat_id item=i}
  <tr><td tipo="1" id="cat_{$cat_id}" saved="1" >{$i}</td><td onclick="delete_list_item('',{$cat_id})" ><img  id="cat_t_{$cat_id}" cat_id="{$cat_id}" style="cursor:pointer" src="art/icons/cross.png" /></td></tr>
  {/foreach}
</table>


{if $num_cat_list==0}{t}No categories to choose{/t}{else}
<select name='cat' id="cat_select" prev="0">
  <option iname="">{t}Choose a category{/t}</option>
  {foreach from=$cat_list key=myId item=i}
  <option {if !$i.show}disabled="disabled"{/if}  id="cat_o_{$myId}" iname="{$i.iname}"  parents="{$i.parents}" sname="{$i.name}"  cat_id="{$myId}"   >{$i.name}</option>
  {/foreach}
</select>
<img box="cat_select" id="add_cat" style="position:relative;top:3px;cursor:pointer" src="art/icons/application_go_left.png"/>
{/if}

<span style="margin:0 0 0 25px;">{t}Browse Categories{/t}</span></td></tr>

      <tr><td><img style="visibility:hidden"  id="c_description" src="art/icons/accept.png" /></td>
	<td>{t}Description{/t}:</td><td><input     class=''     ovalue="{$description}"   name="v_description"    value="{$description}"  id="v_description" size="40"/></td><td id="m_description"></td></tr>
      <tr><td><img style="visibility:hidden"  id="c_sdescription" src="art/icons/accept.png" /></td>                               
	<td>{t}Short Description{/t}:</td><td><input  class=''  ovalue="{$sdescription}"  name="v_sdescription"  value="{$sdescription}" id="v_sdescription"  size="40"   /></td><td id="m_sdescription"></td></tr>
      <tr><td><img style="visibility:hidden"  id="c_details" src="art/icons/accept.png" /></td>
	<td>{t}Detailed Description{/t}:<td style="visibility:hidden" class="text_ok" id="i_details"><i>{t}Product details changed{/t}</i></td><td id="m_details"></td></tr>
      <tr><td></td><td colspan="2"><textarea id="v_details" name="v_details" rows="20" cols="100">{$details}</textarea>
      </td></tr>
      
    </table>
  </form>
</div>


</div>
<div class="yui-b">
<div  style="float:right;margin-top:10px;text-align:right">
 <span class="search_title" style="padding-right:15px">{t}Product Code{/t}:</span> <br><input size="8" class="text search" id="prod_search" value="" name="search"/><img align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
       <span  class="search_msg"   id="search_msg"    ></span> <span  class="search_sugestion"   id="search_sugestion"    ></span>
       <br/>
</div>	 

<table  style="width:5em" class="but edit" >
<tr><td ><a href="product.php?id={$data.id}">Exit</a></td></tr>
</table>


</div>
</div>




</div>

<div id="shapes" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Product Shape{/t}: </li>
      {foreach from=$shapes item=shape key=shape_id name=foo}
       {if !$smarty.foreach.foo.first}
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_dim_tipo({$shape_id},0)"> {$shape}</a></li>
      {/if}
      {/foreach}
    </ul>
  </div>
</div>

{include file='footer.tpl'}


