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
<h1>{$product->get('Product Code')} {$product->get('Product Short Description')}</h1>
<div class="chooser" >
  <ul >
    <li id="config" {if $edit=='config'}class="selected"{/if} ><img src="art/icons/cog.png"> {t}Parts{/t}</li>
    <li id="description" {if $edit=='description'}class="selected"{/if} > <img src="art/icons/information.png"> {t}Description{/t}</li>
    <li id="pictures" {if $edit=='pictures'}class="selected"{/if} > <img src="art/icons/photos.png"> {t}Pictures{/t}</li>
    <li id="prices" {if $edit=='prices'}class="selected"{/if} ><img src="art/icons/money_add.png"> {t}Price, Discounts{/t}</li>
    <li id="dimat" {if $edit=='dimat'}class="selected"{/if} ><img src="art/icons/shape_ungroup.png"> {t}Dimensions{/t}</li>
    <li id="dimat" {if $edit=='web'}class="selected"{/if} ><img src="art/icons/page_world.png"> {t}Web Pages{/t}</li>
    

  </ul>
</div>

<div style="clear:both;height:.1em;padding:0px 20px;;margin:20px auto;border-top: 1px solid #cbb;;border-bottom: 1px solid #caa;width:770px;" id="description_messages">

  <div style="float:right">
  <span class="save" style="display:none" id="description_save" onclick="save('description')">Save</span><span id="description_reset"  style="display:none"   class="reset" onclick="reset('description')">Reset</span>
  </div>
  <span style="display:none">Number of changes:<span id="description_num_changes">0</span></span>

  <div id="description_errors">
  </div>
  <div id="description_warnings">
  </div>
</div>
<div class="edit_block" {if $edit!="config"}style="display:none"{/if}   id="d_config">
  

 <table style="margin:0;"  class="edit">
<tr>
  <td class="label">{t}Type of Product{/t}:</td>
   
      <td >
<div class="options" style="margin:5px 0">
<span {if $product->get('Product Type')=="Normal"}class="selected"{/if} id="type_prod_normal">{t}Normal{/t}</span>
<span {if $product->get('Product Type')=="Shortcut"}class="selected"{/if} id="type_prod_shortcut">{t}Shortcut{/t}</span>
<span {if $product->get('Product Type')=="Mix"} class="selected"{/if} id="type_prod_mix">{t}Mixture{/t}</span>
</div>
</td>

</tr>


  </table>
  


<div style="margin:10px">
{if $num_parts==0}
 {t}Choose the part{/t} 
  <div id="adding_new_part" style="width:200px;margin-bottom:45px"><input id="new_part_input" type="text"><div id="new_part_container"></div></div>

{else}
<table class="edit" style="width:33em" >
<tbody id="current_parts_form">

      {foreach from=$parts item=part key=part_id }
      <tr  id="sup_tr1_{$part_id}" class="top title">
	<td  class="label" colspan=2>
{$part.description}
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
</div>

  <div {if $data.product_tipo!='dependant'}display="none"{/if} >
    <table class="edit" border=0>
      <tr>
	<td  class="label" style="width:10em">{t}Units Definition{/t}:</td>
   <td colspan=2>
<div class="options" style="margin:5px 0">
  {foreach from=$units_tipo item=unit_tipo key=part_id }
<span {if $unit_tipo.selected}class="selected"{/if} id="unit_tipo_{$unit_tipo.name}">{$unit_tipo.fname}</span>
{/foreach}
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
	     value="{$units}"  
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
<div class="edit_block" {if $edit!="prices"}style="display:none"{/if}  id="d_prices">
  <table class="edit" >
    <tr class="title"><td></td>
    <td style="text-align:right;width:12em">{t}Price per Outer{/t} ({$units}{$data.units_tipo_shortname})</td>
    <td style="text-align:right;width:12em">{t}Price per{/t} {$product->get('Unit Type')}</td>
    <td style="text-align:right;width:12em">{t}Margin{/t}</td>
    </tr>
	
    <tr>
      <td class="label">{t}Sale Price{/t}:</td>
      <td>{$currency}<input  onkeydown="to_save_on_enter(event,this)"  
        onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thousand_sep}',2);price_changed(this)" style="text-align:right;width:10em"  
        factor="{$factor_inv_units}" 
        name="price" 
        id="v_price" 
        value="{$product->get('Price','System')}"  
        ovalue="{$product->get('Price','System')}">
        </td>
      <td id="price_ou" style="text-align:right">{$product->get('Price Per Unit Formated')}</td>
      <td id="price_change"></td>
      <td><span onClick="save_price('price')" name="price" style="cursor:pointer;visibility:hidden" id="price_save">{t}Save{/t} <img src="art/icons/disk.png"/></span></td></tr>
    <tr>
      <td class="label">{t}Recomended Retail Price{/t}:</td>
      <td id="rrp_ou" style="text-align:right">{$product->get('RRP Per Outer Formated')}</td>
      <td>{$currency}
      <input onkeydown="to_save_on_enter(event,this)" 
        onblur="format_rrp(this)" style="text-align:right;width:6em"
        factor="{$factor_units}"   
        name="rrp" id="v_rrp" 
        ovalue="{$product->get('RRP Per Unit','System')}" 
        value="{$product->get('RRP Per Unit','System')}" ></td> 
      <td id="rrp_change" >{if $product->get('Product RRP Per Unit')==''}{t}RRP not set{/t}{/if}</td>
      
      <td><span onClick="save_price('rrp')" name="rrp" style="cursor:pointer;visibility:hidden" id="rrp_save">{t}Save{/t} <img src="art/icons/disk.png"/></span></td>
    </tr>
      
    
  </table>
</div>
<div class="edit_block" {if $edit!="dimat"}style="display:none"{/if}  id="d_dimat">
<table class="edit" >
  <tr class="title"><td colspan=6>{t}Weight{/t}</td></tr>
  <tr>
    <td class="label" style="width:12em">{t}Per{/t} {$product->get('Product Unit Type')}:</td>
    <td>
        <input style="text-align:right;width:5em"  
            onkeydown="to_save_on_enter(event,this)" 
            name="weight"   
            id="v_weight" 
            tipo="number" 
            value="{$product->get('Net Weight Per Unit')}"   
            onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thousand_sep}',3);weight_changed(this)"
            ovalue="{$product->get('Product Net Weight Per Unit')}"><span id="weight_units">{t}Kg{/t}</span>
    </td>
    <td class="icon" style="width:20px"><img id="weight_save" style="cursor:pointer;visibility:hidden" onClick="simple_save('weight')" src="art/icons/disk.png"></td>
  </tr>
  <tr>
    <td class="label">{t}Per outer{/t}:<br>{t}including packing{/t}</td>
    <td>{t}Kg{/t}</td>
    <td><input style="text-align:right;width:5em"  onkeydown="to_save_on_enter(event,this)" id="v_oweight"  tipo="number" value="{$product->get('Product Gross Weight')}"  name="oweight"  ovalue="{$product->get('Product Gross Weight')}"  onblur="this.value=FormatNumber(this.value,'{$decimal_point}','{$thousand_sep}',3);weight_changed(this)"  ></td>
    <td class="icon"><img id="oweight_save" style="cursor:pointer;visibility:hidden" onClick="simple_save('oweight')" src="art/icons/disk.png"></td>
  </tr>
</table>
<table class="edit" >
    <tr class="title"><td colspan=5>{t}Dimensions{/t}</td></tr>
 <tr>
   <td class="label" style="width:12em">{t}Product gross volume{/t}:<br>{t}including packing{/t}</td><td>{t}Liters{/t}<br>1000{t}cc{/t}</td>
<td><input style="text-align:right;width:5em"  onkeydown="to_save_on_enter(event,this)"  onblur="vol_changed(this)" name="vol" id="v_vol" value="{$product->get('Product Gross Volume')}" ovalue="$product->get('Product Gross Volume')"   ></td>
<td><span>{t}Calculate{/t}</span></td>
</tr>
<tr>
   <td class="label" style="width:12em">{t}Product MOV{/t}:<br>{t}including packing{/t}</td><td>{t}Liters{/t}<br>1000{t}cc{/t}</td>
<td><input style="text-align:right;width:5em"  onkeydown="to_save_on_enter(event,this)"  onblur="movol_changed(this)" name="movol" id="v_movol" value="{$product->get('Product Minimun Orthogonal Gross Volume')}" ovalue="$product->get('Product Minimun Orthogonal Gross Volume')"   ></td>
<td></td>
</tr>


</table>
<div style="display:none">
<table class="edit" border=1>
    <tr class="title"><td colspan=5>{t}Dimensions{/t}</td></tr>
    <tr>
   <td class="label" style="width:12em">{t}Product gross volume{/t}:<br>{t}including packing{/t}</td><td>{t}Liters{/t}<br>1000{t}cc{/t}</td>
   <td style="width:4em"><span  style="cursor:pointer" id="dim_shape">{$data.dim_tipo_name}</span></td>
   <td>
        <input 
            style="text-align:right;width:10em"  
            onkeydown="to_save_on_enter(event,this)"  onblur="dim_changed(this)" 
            tipo="shape{$data.dim_tipo}" name="dim" id="v_dim" value="{$data.dim}" 
            ovalue="{$data.dim}"
        >
   </td>
   <td style="width:24em" style="font-size:90%;color:#777" >
   <img id="dim_alert" src="art/icons/exclamation.png" title="{t}Wrong Format{/t}"  style="cursor:pointer;;visibility:hidden;float:left" /> 
   <span id="dim_shape_example">{$shape_example[$data.dim_tipo]}</span>
   </td>
   <td  style="width:20px"><img id="dim_save" style="cursor:pointer;visibility:hidden" onClick="simple_save('dim')" src="art/icons/disk.png"></td>
 </tr>
    <tr>
      <td class="label">{t}Outer{/t}:<br>{t}including packing{/t}</td>
      <td ><span style="cursor:pointer">{$data.odim_tipo_name}</span></td>
      <td><input style="text-align:right;width:10em" onkeydown="to_save_on_enter(event,this)"   onblur="dim_changed(this)" tipo="shape1"  name="odim"   id="v_odim" value="{$data.odim}"   ovalue="{$data.odim}"      ></td>
      <td style="font-size:90%;color:#777"><img id="odim_alert" src="art/icons/exclamation.png" title="{t}Wrong Format{/t}"  style="cursor:pointer;;visibility:hidden;float:left" /> {$shape_example[$data.odim_tipo]}</td>
      <td><img id="odim_save" style="cursor:pointer;visibility:hidden" onClick="simple_save('odim')" src="art/icons/disk.png"></td></tr>
</table>
</div>

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

  <form action="upload.php" enctype="multipart/form-data" method="post" id="testForm">
    <input type="file" name="testFile"/>
    <input type="button" id="uploadButton" value="Upload"/>
  </form>
  
  <div  id="images" class="edit_images" principal="{$data.principal_image}" >
    {foreach from=$images item=image  name=foo}
    <div id="image{$image.id}" class="image" >
      <div>{$image.name}</div>
      <img class="picture"  src="{$image.filename}" width=150  /> 
      <div class="operations">
	{if $image.is_principal=='Yes'}
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
<div style="margin:0;padding:0" class="edit_block" {if $edit!="description"}style="display:none"{/if}  id="d_description">
    <table style="margin:0;" class="edit" border=0>
        <tr id="tr_name">
            <td rowspan=2  class="margin_note" >{t}Product Info{/t}:</td>
	        <td class="label">{t}Name{/t}:</td>
	        <td class="left">
	  <input 
	        class='left' 
	        ovalue="{$product->get('Product Name')}"  
	        onMouseUp="description_changed(this)"  
	        onChange="description_changed(this)"   
	        name="description" 
	        changed=0  
	        onKeyUp="description_changed(this)" 
	        value="{$product->get('Product Name')}"  
	        id="name" 
	        size="40"  
	        MAXLENGTH="75" />
    </td>
        </tr>
        <tr id="tr_special_char">
	<td class="label">{t}Special Characteristic{/t}:</td>
	<td class="left" >
	    <input   
	        onKeyUp="description_changed(this)"    
	        onMouseUp="description_changed(this)"  
	        onChange="description_changed(this)"    
	        class='left'  
	        changed=0 
	        ovalue="{$product->get('Product Special Characteristic')}"  
	        name="sdescription"  value="{$product->get('Product Special Characteristic')}" 
	        id="special_char"  
	        size="40"  
	        MAXLENGTH="40" />
	<span onClick="save_description('sdescription')"  name="sdescription" style="cursor:pointer;visibility:hidden" id="sdescription_save"><img src="art/icons/disk.png" title="{t}Save short description{/t}"/></span>
    </td>
    </tr>
        <tr id="tr_use">
    <td rowspan="3" class="margin_note">{t}Categories{/t}:</td>
	<td class="label">{t}Use{/t}:</td>
	<td>
	  <div id="cat_use" class="options" style="margin:5px 0">
	    {foreach from=$cat_use item=cat key=cat_id name=foo}
	    <span {if $cat.selected}class="selected"{/if} value="{$cat.selected}" ovalue="{$cat.selected}" onclick="checkbox_changed(this)" id="cat_use{$cat_id}">{$cat.name}</span>
	    {/foreach}
	  </div>
	</td>
      </tr>
        <tr id="tr_theme">
	<td class="label">{t}Theme{/t}:</td>
	<td>
	  <div id="cat_theme" class="options" style="margin:5px 0">
	    {foreach from=$cat_theme item=cat key=cat_id name=foo}
	    <span {if $cat.selected}class="selected"{/if} value="{$cat.selected}" ovalue="{$cat.selected}" onclick="checkbox_changed(this)"  id="cat_theme{$cat_id}">{$cat.name}</span>

{/foreach}
</div>
</td>

</tr>
        <tr id="tr_materials">
<td class="label">{t}Materials{/t}:</td>
<td>
<div id="cat_material" class="options" style="margin:5px 0">
{foreach from=$cat_material item=cat key=cat_id name=foo}
<span {if $cat.selected}class="selected"{/if} value="{$cat.selected}" ovalue="{$cat.selected}" onclick="checkbox_changed(this)"  id="cat_material{$cat_id}">{$cat.name}</span>

{/foreach}
</div>
</td>
</tr>
        <tr id="tr_details">
    <td class="margin_note">{t}Detailed Description{/t}:</td>
    <td colspan="2"><textarea id="details" name="v_details" changed=0 ovalue="{$product->get('Product Description MD5 Hash')}" rows="20" cols="100">{$product->get('Product Description')}</textarea>
    </td>
    </tr>
    </table>
</div>

</div>
<div class="yui-b"     >
<div  style="float:right;text-align:right;;width:40em">
 <span class="search_title" style="position:relative;left:15px">{t}Product Code{/t}:</span> <input size="8" class="text search" id="prod_search" value="" name="search"/><img align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
       <span  class="search_msg"   id="search_msg"    ></span> <span  class="search_sugestion"   id="search_sugestion"    ></span>
       <br/>
</div>	 




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


