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
<h1>{$data.code} {$units}x {$data.description}</h1>
<div class="chooser" >
  <ul>
    <li id="config" {if $edit=='config'}class="selected"{/if} ><img src="art/icons/cog.png"> {t}Product Configuration{/t}</li>
    <li id="description" {if $edit=='description'}class="selected"{/if} > <img src="art/icons/information.png"> {t}Description{/t}</li>
    <li id="pictures" {if $edit=='pictures'}class="selected"{/if} > <img src="art/icons/photos.png"> {t}Pictures{/t}</li>
    <li id="prices" {if $edit=='prices'}class="selected"{/if} ><img src="art/icons/money_add.png"> {t}Price, Discounts{/t}</li>
    <li id="suppliers" {if $edit=='suppliers'}class="selected"{/if} ><img src="art/icons/cog_add.png"> {t}Suppiers{/t}</li>
    <li id="dimat" {if $edit=='dimat'}class="selected"{/if} ><img src="art/icons/shape_ungroup.png"> {t}Dimensions, Materials{/t}</li>


  </ul>


</div> 



<div style="clear:both;padding:20px 20px" id="edit_messages"></div>


<div  {if $edit!="config"}style="display:none"{/if}  class="edit_block" id="d_config">
  
  <table class="tipo">
    <tr>
      <td {if $data.product_tipo=='normal'}class="selected"{/if}>{t}Normal{/t}</td>
      <td {if $data.product_tipo=='dependant'}class="selected"{/if}>{t}Stock Dependant{/t}</td>
      <td {if $data.product_tipo=='shortcut'}class="selected"{/if}>{t}Shortcut{/t}</td>
      <td {if $data.product_tipo=='mix'}class="selected"{/if}>{t}Mixture{/t}</td>
    </tr>
  </table>
  
  <div {if $data.product_tipo!='dependant'}display="none"{/if} >
    <table class="edit" >
      <tr>
	<td style="width:10em">{t}Units Definition{/t}:</td>
	<td style="width:10em" id="v_units_tipo" ovalue="$data.units_tipo" value="$data.units_tipo">{$data.units_tipo_name}</td>
	<td><span id="units_tipo_save"  style="cursor:pointer;visibility:hidden" onclick="uniit_tipo_save()">{t}Save{/t} <img src="art/icons/disk.png"/></span></td>
      </tr>

      <tr>
	<td><span id="units_tipo_plural">{$data.units_tipo_plural}</span> {t}Per Outer{/t}:</td>
	<td><input ovalue="{$data.units}" value="{$data.units}" onblur="units_changed(this)" style="text-align:right;width:5em"></td>
      </tr>
    </table>
  </div>

</div>


<div  {if $edit!="prices"}style="display:none"{/if}  class="edit_block" id="d_prices">
  <table class="edit" >
    <tr class="title"><td></td><td style="text-align:right">{t}Price per Outer{/t} ({$units}{$data.units_tipo_shortname})</td><td style="text-align:right">{t}Price per{/t} {$data.units_tipo_name}</tr>
	
    <tr>
      <td class="label">{t}Sale Price{/t}:</td>
      <td>{$currency}<input  onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thosusand_sep}',2);price_changed(this)" style="text-align:right;width:10em"  factor="{$factor_inv_units}" name="price" id="v_price" value="{$data.price}"  ovalue="{$data.price}" ></td>
      <td id="price_ou">{$price_perunit}</td>
      <td id="price_change"></td>
      <td><span onClick="save_price('price')"  name="price" style="cursor:pointer;visibility:hidden" id="price_save">{t}Save{/t} <img src="art/icons/disk.png"/></span></td></tr>
    <tr>
      <td class="label">{t}Recomended Retail Price{/t}:</td>
      <td id="rrp_ou">{$rrp_perouter}</td>
      <td>{$currency}<input onblur="format_rrp(this)" style="text-align:right;width:10em"   factor="{$factor_units}"   name="rrp" id="v_rrp" ovalue="{$data.rrp}" value="{$data.rrp}" ></td> 
      <td id="rrp_change">{if $data.rrp==''}{t}RRP not set{/t}{/if}</td>
      
      <td><span onClick="save_price('rrp')" name="rrp" style="cursor:pointer;visibility:hidden" id="rrp_save">{t}Save{/t} <img src="art/icons/disk.png"/></span></td>
    </tr>
      
    
  </table>
</div>
<div  {if $edit!="dimat"}style="display:none"{/if}  class="edit_block" id="d_dimat">

<table class="edit" >
  <tr class="title"><td colspan=6>{t}Weight{/t}</td></tr>
  <tr>
    <td class="label" style="width:12em">{t}Per{/t} {$data.units_tipo_name}:</td>
    <td style="width:4em" class="text-align:left">{t}Kg{/t}</td>
    <td><input style="text-align:right;width:5em"  name="weight"   id="v_weight" tipo="number" value="{$data.weight}"   onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thosusand_sep}',3);weight_changed(this)"     ovalue="{$data.weight}"></td>
    <td class="icon" style="width:20px"><img id="weight_save" style="cursor:pointer;visibility:hidden" onClick="simple_save('weight')" src="art/icons/disk.png"></td>
  </tr>
  <tr>
    <td class="label">{t}Per outer{/t}:<br>{t}including packing{/t}</td>
    <td>{t}Kg{/t}</td>
    <td><input style="text-align:right;width:5em"  id="v_oweight"  tipo="number" value="{$data.oweight}"  name="oweight"  ovalue="{$data.oweight}"  onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thosusand_sep}',3);weight_changed(this)"  ></td>
    <td class="icon"><img id="oweight_save" style="cursor:pointer;visibility:hidden" onClick="simple_save('oweight')" src="art/icons/disk.png"></td>
  </tr>
</table>
 <table class="edit" >
    <tr class="title"><td colspan=5>{t}Dimensions{/t}</td></tr>
 <tr>
   <td class="label" style="width:12em">{$data.units_tipo_name|capitalize}:</td>
   <td style="width:4em"><span  style="cursor:pointer" id="dim_shape">{$data.dim_tipo_name}</span></td>
   <td><input style="text-align:right;width:10em"  onblur="dim_changed(this)" tipo="shape{$data.dim_tipo}" name="dim" id="v_dim" value="{$data.dim}" ovalue="{$data.dim}"   ></td>
   <td style="width:16em" style="font-size:90%;color:#777" ><img id="dim_alert" src="art/icons/exclamation.png" title="{t}Wrong Format{/t}"  style="cursor:pointer;;visibility:hidden;float:left" /> <span id="dim_shape_example">{$shape_example[$data.dim_tipo]}<span></td>
   <td  style="width:20px"><img id="dim_save" style="cursor:pointer;visibility:hidden" onClick="simple_save('dim')" src="art/icons/disk.png"></td></tr>
    <tr>
      <td class="label">{t}Outer{/t}:<br>{t}including packing{/t}</td>
      <td ><span style="cursor:pointer">{$data.odim_tipo_name}</span></td>
      <td><input style="text-align:right;width:10em"  onblur="dim_changed(this)" tipo="shape1"  name="odim"   id="v_odim" value="{$data.odim}"   ovalue="{$data.odim}"      ></td>
      <td style="font-size:90%;color:#777"><img id="odim_alert" src="art/icons/exclamation.png" title="{t}Wrong Format{/t}"  style="cursor:pointer;;visibility:hidden;float:left" /> {$shape_example[$data.odim_tipo]}</td>
      <td><img id="odim_save" style="cursor:pointer;visibility:hidden" onClick="simple_save('odim')" src="art/icons/disk.png"></td></tr>
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
    <tr class="last"><td class="label">{t}Estimated price per{/t} {$data.units_tipo_name}:</td><td colspan=3>{$currency}<input style="text-align:right;width:6em" value="" id="new_supplier_cost" id=""></td></tr>
    <tr><td style="background:white" colspan="4"></td></tr>
    </tbody>
    <tbody id="current_suppliers_form">
    {foreach from=$suppliers_name item=supplier key=supplier_id }
    <tbody id="supplier_body{$supplier_id}">
    <tr  class="top title"><td class="label">{t}Supplier{/t}:</td><td><a href="supplier.php?id={$supplier_id}">{$supplier}</a> {if $supplier!=$suppliers_name[$supplier_id] }{$suppliers_name[$supplier_id]}{/if}</td><td class="icon"><img id="save_supplier_{$supplier_id}" style="cursor:pointer;display:none" onClick="save_supplier({$supplier_id})" src="art/icons/disk.png"></td><td class="icon"><img onclick="delete_supplier({$supplier_id},'{$supplier}')" style="cursor:pointer" id="delete_supplier_{$supplier_id}" src="art/icons/cross.png"></td></tr>
    <tr><td class="label">{t}Suppliers product code{/t}:</td><td colspan=3><input style="text-align:right;width:10em" value="{$suppliers_code[$supplier_id]}"  supplier_id="{$supplier_id}"      tipo="text" onkeyup="change_element(this)" ovalue="{$suppliers_code[$supplier_id]}" id="v_supplier_code{$supplier_id}"></td></tr>
    <tr class="last"><td class="label">{t}Cost per{/t} {$data.units_tipo_name}:</td><td colspan=3>{$currency}<input id="v_supplier_cost{$supplier_id}" style="text-align:right;width:6em"  supplier_id="{$supplier_id}"  tipo="money" onkeyup="change_element(this)" value="{$suppliers_num_price[$supplier_id]}" ovalue="{$suppliers_num_price[$supplier_id]}" ></td></tr>
    <tr><td colspan="4"></td></tr>
    </tbody>
    {/foreach}
    </body>
    
</table>	  
</div>

<div  {if $edit!="pictures"}style="display:none"{/if}  class="edit_block" id="d_pictures">

  <form action="upload.php" enctype="multipart/form-data" method="post" id="testForm">
    <input type="file" name="testFile"/>
    <input type="button" id="uploadButton" value="Upload"/>
  </form>
  
  <div  id="images" class="edit_images" principal="{$data.principal_image}" >
    {foreach from=$images item=image  name=foo}
    <div id="image{$image.id}" class="image" >
      <div>{$image.name}</div>
      <img class="picture"  src="{$image.med}"/> 
      <div class="operations">
	{if $image.principal==1}
	<span class="img_set_principal" ><img id="img_set_principal{$image.id}" onClick="set_image_as_principal(this)" title="{t}Main Image{/t}" image_id="{$image.id}" principal="1" src="art/icons/asterisk_orange.png"></span>
	{else}
	<span  class="img_set_principal" style="cursor:pointer"  >
	  <img id="img_set_principal{$image.id}" onClick="set_image_as_principal(this)" title="{t}Set as the principal image{/t}" image_id="{$image.id}" principal="0" src="art/icons/picture_empty.png"></span>
	{/if}
	<span style="cursor:pointer" onClick="delete_image({$image.id},'{$image.name}')">{t}Delete{/t} <img src="art/icons/cross.png"></span>
      </div>
      <div class="caption" >
	<div>{t}Caption{/t}:</div>
	<span class="save" >
	  <img   class="caption" id="save_img_caption{$image.id}" onClick="save_image('img_caption',{$image.id})" title="{t}Save caption{/t}"  src="art/icons/disk.png">
	</span>
	<textarea class="caption" onkeydown="caption_changed(this)" id="img_caption{$image.id}" image_id="{$image.id}" ovalue="{$image.caption}">{$image.caption}</textarea>
      </div>
    
    </div>
    {/foreach}
  </div>



</div>
<div  {if $edit!="description"}style="display:none"{/if} class="edit_block" id="d_description">
  <form id="f_description">
    
    
    <table style="margin:0;" border=0>
      <tr>
	<td><img style="visibility:hidden"  id="c_categories" src="art/icons/accept.png" /></td>
	<td style="vertical-align: top;width:175px" >{t}Categories{/t}:</td>
	<td style="vertical-align: top;width:300px " >
	  <table border=0 id="cat_list" style="float:left;margin:0 20px 0 0 ">
	    <tr>
	      <td>{if $num_cat==0}{t}No category assigned{/t}{/if}<td><td><span style="margin:0 0 0 25px;cursor:pointer" id="browse_cat">{t}Choose Category{/t}</span></td></td>
	    </tr>
	    <tr><td>{t}State{/t}</td></tr>
	    <tr><td>{t}Use{/t}</td></tr>
	    <tr><td>{t}Other{/t}</td></tr>
	    
	  </table>
	</td>
	<td style="width:300px"></td>
      </tr>  
      <tr>
	<td><img   id="description_icon" style="visibility:hidden;cursor:pointer" title="{t}Return recorded value{/t}"  src="art/icons/arrow_undo.png" onclick="return_to_old_value('description')" /></td>
	<td>{t}Description{/t}:</td>
	<td><input     class=''     ovalue="{$data.description}"   name="description"   onKeyUp="description_changed(this)"   value="{$data.description}"  id="v_description" size="40"/></td>
	<td>
	  <span onClick="save_description('description')"  name="description" style="cursor:pointer;display:none" id="description_save">{t}Save{/t} <img src="art/icons/disk.png"/></span>
	  <span id="description_change">
	</td>
      </tr>
      <tr>
	<td><img   id="sdescription_icon" style="visibility:hidden;cursor:pointer" title="{t}Return recorded value{/t}"  src="art/icons/arrow_undo.png" onclick="return_to_old_value('sdescription')" /></td>
	<td>{t}Short Description{/t}:</td>
	<td><input   onKeyUp="description_changed(this)" class=''  ovalue="{$data.sdescription}"  name="sdescription"  value="{$data.sdescription}" id="v_sdescription"  size="40"   /></td>
	<td>
	  <span onClick="save_description('sdescription')"  name="sdescription" style="cursor:pointer;display:none" id="sdescription_save">{t}Save{/t} <img src="art/icons/disk.png"/></span>
	  <span id="sdescription_change">
	</td>
      </tr>
      
      <tr>
	<td><img   id="details_icon" style="visibility:hidden;cursor:pointer" title="{t}Return recorded value{/t}"  src="art/icons/arrow_undo.png" onclick="return_to_old_value('details')" /></td>
	<td>{t}Detailed Description{/t}:</td>
	<td><span onClick="save_description('details')"  name="details" style="cursor:pointer;display:none" id="details_save">{t}Save{/t} <img src="art/icons/disk.png"/> <span id="details_change"></td>
	<td></td>
      </tr>
      <tr><td></td><td colspan="3"><textarea id="v_details" name="v_details" rows="20" cols="100">{$data.details}</textarea>
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


<div id="catlist" class="yuimenu staff_list"  >
  <div class="bd">
    <span>{t}{/t}</span>
    <span>Pyhical State</span>
    <table border=1>
      {foreach from=$state_cat item=_cat name=foo}
      {if $_cat.mod==0}<tr>{/if}
	<td title="{$_cat.name}" cat_id="{$_cat.id}" id="cat_state{$_cat.id}" cat="state" onClick="select_cat(this,event)" >{$_cat.sname}</td>
	{if $_cat.mod==$cat_cols}</tr>{/if}
      {/foreach}
    </table>

    <span>Material</span>
    <table border=1>
      {foreach from=$material_cat item=_cat name=foo}
      {if $_cat.mod==0}<tr>{/if}
	<td title="{$_cat.name}" cat_id="{$_cat.id}" id="checkers{$_cat.id}" onClick="select_cat(this,event)" >{$_cat.sname}</td>
	{if $_cat.mod==$cat_cols}</tr>{/if}
      {/foreach}
    </table>
     <span>Use</span>
     <table border=1>
       {foreach from=$use_cat item=_cat name=foo}
       {if $_cat.mod==0}<tr>{/if}
	 <td title="{$_cat.name}" cat_id="{$_cat.id}" id="checkers{$_cat.id}" onClick="select_cat(this,event)" >{$_cat.sname}</td>
	 {if $_cat.mod==$cat_cols}</tr>{/if}
      {/foreach}
    </table>
  <span>{t}Other{/t}</span>
     <table border=1>
       {foreach from=$mods_cat item=_cat name=foo}
       {if $_cat.mod==0}<tr>{/if}
	 <td title="{$_cat.name}" cat_id="{$_cat.id}" id="checkers{$_cat.id}" onClick="select_cat(this,event)" >{$_cat.sname}</td>
	 {if $_cat.mod==$cat_cols}</tr>{/if}
      {/foreach}
     </table>
     

  </div>
</div>

<div id="units_tipo_list" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      {foreach from=$units_tipo_list item=menu }
       <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_units_tipo({$menu.id},'{$menu.name}','{$menu.sname}')"> {$menu.name}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


{include file='footer.tpl'}


