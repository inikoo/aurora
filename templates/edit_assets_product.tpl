{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>


<div id="bd" >

{if $next.id>0}<span class="nav2 onright"><a href="assets_product.php?id={$next.id}">{t}Next{/t}</a></span>{/if}
{if $prev.id>0}<span class="nav2 onright" ><a href="assets_product.php?id={$prev.id}">{t}Previous{/t}</a></span>{/if}
<span class="nav2 onright" style="margin-left:20px"><a href="assets_family.php?id={$family_id}">{t}Up{/t}</a></span>


<span class="nav2 onright"><a href="assets_index.php">{t}Product index{/t}</a></span>
{*}<span class="nav2 onright"><a href="assets_info.php?id={$product_id}">{t}Product Information{/t}</a></span>{/*}



<span class="nav2"><a href="assets_tree.php">{$home}</a></span>
<span class="nav2"><a href="assets_department.php?id={$department_id}">{$department}</a></span>
<span class="nav2"><a href="assets_family.php?id={$family_id}">{$family}</a></span>


  <div id="yui-main">
    <div class="yui-b" >

       <div style="position:relative;bottom:0px;float:right;padding: 0 ;text-align:right;width:160px;">
	       {include file='product_search.tpl'}
	 
	 
	 <table class="options" >
	   <tr><td  {if $hide[0]==0}class="selected"{/if} id="but_view0" >{t}Details{/t}</td></tr>
	   {if $view_orders}<tr><td  {if $hide[3]==0}class="selected"{/if}  id="but_view3" >{t}Plots{/t}</td></tr>{/if}
	   {if $view_orders}<tr><td {if $hide[1]==0}class="selected"{/if}  id="but_view1"  >{t}Orders{/t}</td></tr>{/if}
	   {if $view_cust}<tr><td {if $hide[2]==0}class="selected"{/if}  id="but_view2"  >{t}Customers{/t}</td>{else}<td style="visibility:hidden" ></td></tr>{/if}
	   {if $view_stock}<tr><td {if $hide[3]==0}class="selected"{/if}  id="but_view3"  >{t}Stock{/t}</td>{else}<td style="visibility:hidden" ></td></tr>{/if}
	 </table>
	 {if $modify}
	 <table class="options" >
	   <tr><td {if $hide[5]==0}class="edit"{/if}    id="but_view5" >{t}Edit{/t}</td></tr>  
	 </table>
	
	 <table id="edit_buts" class="but edit"   {if $hide[5]==1} style="display:none"{/if}   >
	   <tr><td id="edit_product">{t}Edit General{/t}</td></tr>
	   <tr><td  id="edit_dim" >{t}Edit Dimensions{/t}</td></tr>
	   <tr><td  id="edit_details" >{t}Edit Details{/t}</td></tr>

	   <tr><td  id="add_pic" >{t}Add Picture{/t}</td></tr>
	   
	   <tr><td id="add_supplier">{t}Add Supplier{/t}</td></tr>
	 </table>
	 {/if}
	 {if $modify_stock}
	 <table class="but"  id="buts">
	   <tr><td id="update_stock">{t}Set Stock{/t}</td></tr>
	 </table>
	 {/if}

	 <table class="but"  id="buts">
	   <tr><td id="save">{t}Save{/t}</td></tr>
	 </table>


       </div>

       <fieldset class="prodinfo" style="width:760px">
	<legend>{$description}</legend>
	
	<div style="padding-top:0;width:200px;heightb:230px;text-align:center;">
	  <span style="font-size:150%;font-weight:800">{$code}</span>
	  
	  <div id="imagediv"  pic_id="{$images[0].id}"  style="width:200px;height:140px;padding:0px 0;xborder:none;cursor:pointer">
	    <img src="{if $images[0]}{$images[0].src}{else}art/nopic.png{/if}"     id="image"   alt="{t}Image{/t}"/>
	  </div>
	  <div class="image_caption" id="caption" {if $images!=1}style="display:none"{/if}>{$images[0].caption}</div>
	  <table class="other_images " id="otherimages"   xstyle="display:none"   >
	    {foreach from=$images item=image  name=foo}
	    {if $smarty.foreach.foo.first}<tr>{else}
	      <td  id="oim_{$smarty.foreach.foo.iteration}" pic_id="{$image.id}"  ><img  src="{$image.src}"  /></td>
	      {/if}
	      {if $smarty.foreach.foo.last}</tr>{/if}
	    {/foreach}
	  </table>
	  
	</div>


	<div>
	    <form  enctype="multipart/form-data" method="POST" action="ar_assets.php"   id="uploadpicForm"   > 
      <input type="hidden" name="tipo" value="uploadpic"/>
      <input type="hidden" name="product_id" value="{$id}"/>

      <br>
      <table >
	<tr><td>{t}Add a new picture{/t}</td></tr>
	<tr><td>{t}Caption{/t}:</td><td><input  class="text" name="caption" type="text" /></td></tr>

	<tr><td>{t}File{/t}:</td><td><input  class="file" name="uploadedfile" type="file" /></td></tr>
	
      </table>
    </form>
	</div>

	
	
	<div style="border:none;padding:0;margin:0">
	<table class="edit">
	  <tr><td colspan="3">{t}Product Identification{/t}:</td></tr>
	  <tr>
	    <td>{t}Numeric code{/t}:</td><td colspan="2">{$id}</td>
	  </tr>
	  <tr>
	    <td>{t}Unique code{/t}:</td><td>{$code}</td><td class="aright"><input value="{$code}"> </td>
	  </tr>
	  <tr>
	    <td>{t}Product Family{/t}:</td><td>{$family}</td><td class="aright"><span style="float:left">{t}Current Family:{/t}<span>{$family}</span> <span>[{t}Families list{/t}]</span></span> {t}Family unique code: {/t}<input value="{$family_id}"> </td>
	  </tr>
	  	  <tr>
	    <td>{t}Category{/t}:</td><td>{$categories}</td><td class="aright"><span>{t}category1,category2...{/t} <span>[{t}Categories list{/t}]</span><br/></span><input style="width:100%" type="text" value="{$categories}"> 
	    </td>
	  </tr>


	</table>

	<table class="edit">
	  <tr><td colspan="3"> {t}Physical Properties{/t}</td></tr>
	  <td>{t}Item units{/t}:</td><td>{$units_tipo}</td>
	  <td class="aright">
	    <select name="units_tipo"  id="units_tipo" >
	      {foreach from=$aunits_tipo item=tipo key=tipo_id }
	      <option value="{$tipo_id}">{$tipo}</option>
	      {/foreach}
	      </select>
	  </td>
	  

	  <tr ><td>{t}Unit Weight{/t}:</td><td id="uw">{$uw}</td><td class="aright"><input id="v_uw" class="aright text"  type="text" nvalue="{$n_uw}"  value="{$uw}"   onkeypress="return key_filter(event,{$key_filter_number})"  > {t}Kg{/t}</td></tr>
	  <tr><td>{t}Unit Dimensions{/t}:</td><td>{$udim}</td><td class="aright"><span id="v_udim_shape_ex">{$ashape_example[$ushape]}</span><input id="v_udim" type="text" value="{$udim}"  onkeypress="return key_filter(event,{$key_filter_dimension})"    > 
	      <select id="v_udim_shape">
		{foreach from=$ashape item=shape key=shape_id }
		<option value="{$shape_id}"  {if $shape_id==$ushape}selected="selected"{/if} >{$shape}</option>x
		{/foreach}
	      </select>
	  </td></tr>
	  <tr ><td>{t}Outer Weight{/t}:</td><td>{$ow}</td><td  class="aright" ><input type="text" value="{$ow}"> {t}Kg{/t}</td></tr>
	  <tr><td>{t}Outer Dimensions{/t}:</td><td>{$odim}</td><td  class="aright" ><span id="v_odim_shape_ex" >{$ashape_example[$oshape]}</span><input id="v_odim"  type="text" value="{$odim}"  onkeypress="return key_filter(event,{$key_filter_number})"  > 
	      <select id="v_odim_shape">
		{foreach from=$ashape item=shape key=shape_id }
		<option value="{$shape_id}"  {if $shape_id==$oshape}selected="selected"{/if} >{$shape}</option>
		{/foreach}
	      </select>
	  </td></tr>
	   <tr><td>{t}Colour{/t}:</td><td>{$color}</td><td  class="aright"><span>{t}colour1,colour2,...{/t} <span>[{t}Colour list{/t}]</span></span><input type="text" value="{$color}"> 
	   </td>
	   </tr>
	</table>
	<table class="edit">
	  <tr><td colspan="3"> {t}Chemical Properties{/t}</td></tr>
	  
	   <tr>
	     <td>{t}Material{/t}:</td>
	     <td>{$materials}</td><td><span>{t}material1(%),material2(%),...{/t} <span>[{t}Materials list{/t}]</span> <span>[{t}Example{/t}]</span></span><input style="width:100%" type="text" value="{$color}"> 
	   </td>
	   </tr>
	  <tr>
	     <td>{t}Ingredients{/t} (material1):</td>
	     <td>{$ingredients}</td><td><span>{t}ingredient1,ingredient2,...{/t} <span>[{t}Ingredient list{/t}]</span></span><input style="width:100%" type="text" value="{$color}"> 
	   </td>
	   </tr>
	</table>



	    {if $view_stock}
	<table class="edit">
	  <tr>
	    <td>{t}Stock{/t}:<br>{$stock_units}</td><td class="stock" id="stock">{$stock}</td><td class="aright">Set stock to <input class="text"/> outers<br>add <input type="text" > outers to current value  <br>substract <input type="text" > outers because <select></select>  <br/> {t}Can you explain why the stock change?{/t}<br><textarea></textarea><br>{t}Checked by:{/t}</td>
	  </tr>
	    <tr>
	     <td>{t}Warehouse Location{/t}:</td>
	     <td>{$materials}</td><td><span>{t}location1(type),location2(type),...{/t} <span>[{t}Warehouse Map{/t}]</span>  <span>[{t}Example{/t}]</span><br/></span><input style="width:100%" type="text" value="{$color}"> 
	   </td>
	   </tr>
	   <tr>
	    <td>{t}Available{/t}:</td><td>{$available}</td>
	  </tr>
	  {if $nextbuy>0   }<tr><td rowspan="2">{t}Next shipment{/t}:</td><td>{$next_buy}</td></tr><tr><td class="noborder">{$nextbuy_when}</td>{/if}
	  </tr>
	</table>
	{/if}
	</div>
	<table class="edit">
	  <tr><td colspan="3"> {t}Sale Properties{/t}</td></tr>
	  <tr>
	    <td>{t}Units per outer{/t}:</td><td>{$units}</td><td class="aright"><input type="text" value="{$units}"> 
	    </td>
	  </tr>


	  
	  <tr>
	    <td>{t}Sell Price{/t}:</td><td  class="price">{$price}</td>
	    <td  class="aright" >£<input value="{$v_price}" type="text"/> {t}per outer{/t}</td>
	  </tr>
	  <tr><td>{t}Sold Since{/t}:</td><td >{$first_date} ({$weeks_since}{t}w{/t})</td>
	    <td   class="aright" ><input style="text-align:right" class="date_input" size="8" type="text"  id="v_invoice_date"  value="{$v_po_date_invoice}" name="invoice_date" /></td>
	  </tr>


	</table>
	<table class="edit">
	  <tr><td colspan="2">{t}Suppliers{/t}</td><td class="aright" ><button>{t}Add new Supplier{/t}</button></td></tr>
	  {if $view_suppliers}
	  {if $suppliers>0}
	  {foreach from=$suppliers_name item=supplier key=supplier_id }
	  <tr><td>{t}Supplier{/t}:</td><td><a href="supplier.php?id={$supplier_id}">{$supplier}</a></td><td  class="aright" ><button>{t}Delete{/t}</button> <button>{t}Desactivate{/t}</button> </td></tr>
	  <tr><td>{t}Supplier code{/t}:</td><td>{$suppliers_code[$supplier_id]}</td><td  class="aright" ><input value="{$suppliers_code[$supplier_id]}" type="text"></td></tr>
	  <tr><td>{t}Unit Cost Price{/t}:</td><td>{$suppliers_price[$supplier_id]}</td><td class="aright" >£<input value="{$suppliers_price[$supplier_id]}" type="text"/></td></tr>
	  {/foreach}
	  {else}
	  <tr><td colspan=2 style="color:brown;font-weight:bold;cursor:pointer">{t}No supplier set{/t}</td></tr>
	  {/if}
	  {/if}
	  <tr>
	    <td>{t}New Supplier{/t}</td>
	    <td></ts>
	    <td class="aright" >
	     <select name="supplier_id"   >
	      {foreach from=$asuppliers item=suppliers key=suppliers_id }
	      <option value="{$suppliers_id}" >{$suppliers}</option>
	      {/foreach}
	    </select>
	     <td>
	  </tr>
	  <tr><td>{t}Supplier code{/t}:</td><td></td><td class="aright"><input value="" type="text"></td></tr>
	  <tr><td>{t}Unit Cost Price{/t}:</td><td></td><td class="aright">£<input value="" type="text"/></td></tr>
	</table>

	
	{if $outall>0 and $view_sales}
	<table >
	  <tr><td>{t}Total Sales{/t}:</td><td class="aright" >{$tsall}</td><td class="aright" >{$outall}</td></tr>
	  <tr><td>{t}Avg W Sales {/t}:</td><td class="aright">{$awtsall}</td><td class="aright" >{$awsall}</td></tr>
	  <tr><td>{t}Last Q Avg W Sales{/t}:</td><td class="aright">{$awtsq}</td><td class="aright" >{$awsq}</td></tr>
	  <tr><td>{t}Last Week Sales{/t}:</td><td class="aright">{$tsw}</td><td class="aright" >{$outw}</td></tr>
	</table>
	{/if}

	<div class="product_details"  id="block0" {if $hide[0]==1} style="display:none"{/if}      >
	  <h2>{$description}</h2>
	  
	  <div style="border:none" id="extended_description">{$short_description}</div>
	</div>

      
      </fieldset>
       {if $view_orders}
       <div id="block3" class="product_plots"   {if $hide[3]==1} style="display:none"{/if}       >
	 
	 <h2 style="margin:0 0 0 50px ;padding:0" class="plot_title"  id="plot_title">{$plot_title}</h2>

	 <div id="plot_options" style="width:130px;float:right;text-align:right">
	   <h2>{t}Plots{/t}</h2>
	   <table class="options" >
	     <tr><td  style="text-align:left" {if $view_plot==0}class="selected"{/if} id="plot_view0" >{t}W Avg Sales{/t}</td></tr>
	     <tr><td  style="text-align:left" {if $view_plot==1}class="selected"{/if} id="plot_view1" >{t}W Avg Orders{/t}</td></tr>
	     <tr><td  style="text-align:left"{if $view_plot==2}class="selected"{/if} id="plot_view2" >{t}W Avg SpO{/t}</td></tr>
	     <tr><td  style="text-align:left"{if $view_plot==3}class="selected"{/if} id="plot_view3" >{t}M Avg Sales{/t}</td></tr>
	     <tr><td  style="text-align:left" {if $view_plot==4}class="selected"{/if} id="plot_view4" >{t}M Avg Orders{/t}</td></tr>
	     <tr><td  style="text-align:left" {if $view_plot==5}class="selected"{/if} id="plot_view5" >{t}M Avg SpO{/t}</td></tr>
	   </table>

	 </div>
	 <div id="plot0" class="product_plot"  style="{if $view_plot!=0}display:none{/if}" >
	   <p>Unable to load Flash content. The YUI Charts Control requires Flash Player 9.0.45 or higher. You can download the latest version of Flash Player from the <a href="http://www.adobe.com/go/getflashplayer">Adobe Flash Player Download Center</a>.</p>
	 </div>
	 <div id="plot1" class="product_plot"  style="{if $view_plot!=1}display:none{/if}" >

	   <p>Unable to load Flash content. The YUI Charts Control requires Flash Player 9.0.45 or higher. You can download the latest version of Flash Player from the <a href="http://www.adobe.com/go/getflashplayer">Adobe Flash Player Download Center</a>.</p>
	 </div>
	 <div id="plot2" class="product_plot"  style="{if $view_plot!=2}display:none{/if}" >

	   <p>Unable to load Flash content. The YUI Charts Control requires Flash Player 9.0.45 or higher. You can download the latest version of Flash Player from the <a href="http://www.adobe.com/go/getflashplayer">Adobe Flash Player Download Center</a>.</p>
	 </div>
	 <div id="plot3" class="product_plot"  style="{if $view_plot!=3}display:none{/if}" >

	   <p>Unable to load Flash content. The YUI Charts Control requires Flash Player 9.0.45 or higher. You can download the latest version of Flash Player from the <a href="http://www.adobe.com/go/getflashplayer">Adobe Flash Player Download Center</a>.</p>
	 </div>
	 <div id="plot4" class="product_plot"  style="{if $view_plot!=4}display:none{/if}" >

	   <p>Unable to load Flash content. The YUI Charts Control requires Flash Player 9.0.45 or higher. You can download the latest version of Flash Player from the <a href="http://www.adobe.com/go/getflashplayer">Adobe Flash Player Download Center</a>.</p>
	 </div>
	 <div id="plot5" class="product_plot"  style="{if $view_plot!=5}display:none{/if}" >

	   <p>Unable to load Flash content. The YUI Charts Control requires Flash Player 9.0.45 or higher. You can download the latest version of Flash Player from the <a href="http://www.adobe.com/go/getflashplayer">Adobe Flash Player Download Center</a>.</p>
	 </div>

       </div>
{/if}

  {if $view_orders} <div id="block1" {if $hide[1]==1} style="display:none"{/if}    > {include file='table.tpl'     table_id=0 table_title=$t_title0 filter=$filter filter_name=$filter_name}</div>{/if}
      {if $view_cust} <div id="block2" {if $hide[2]==1} style="display:none"{/if}    >{include file='table.tpl'    table_id=1 table_title=$t_title1  filter=$filter2 filter_name=$filter_name2}</div>{/if}
 {if $view_stock} <div id="block4" {if $hide[4]==1} style="display:none"{/if}     >  {include file='table.tpl'    dates=1 table_id=2 table_title=$t_title2  options=$stock_table_options options_status=$stock_table_options_tipo  }</div>{/if}


      


    </div>
  </div>
    <div class="yui-b">
    </div>

</div> 

{if $modify}
<div id="edit_product_form">
  <div class="hd">{t}Edit Product{/t}</div> 
  <div class="bd"> 
    <form method="POST" action="ar_assets.php"> 
      <input name="tipo" type="hidden" value="edit_product" />
      <input name="id" type="hidden" value="{$id}" />

      <br>
      <table >
	<tr><td>{t}Code{/t}:</td><td><input name="code" type='text' class='text' SIZE="16" MAXLENGTH="16" value="{$code}"/></td></tr>
	<tr><td>{t}Description{/t}:</td><td><input name="description" type='text'  SIZE="35" MAXLENGTH="80" class='text' value="{$description}"/></td></tr>
	<tr><td>{t}Units per Outer{/t}:</td><td><input name="units"  SIZE="4" type='text'  MAXLENGTH="20" class='text' value="{$units}" /></td></tr>
	<tr><td>{t}Units per Carton{/t}:</td><td><input name="units_carton"  SIZE="4" type='text'  MAXLENGTH="20" class='text'  value="{$units_carton}" /></td></tr>
	<tr><td>{t}Type of Unit{/t}:</td><td>	
	    <select name="units_tipo"  id="units_tipo" >
	      {foreach from=$aunits_tipo item=tipo key=tipo_id }
	      <option value="{$tipo_id}" {if $units_tipo==$tipo_id}selected="selected"{/if}   >{$tipo}</option>
	      {/foreach}
	</select</td></tr>
	<tr><td>{t}Price Outer{/t}:</td><td>{$cur_symbol} <input name="price" type='text'  SIZE="6" MAXLENGTH="20" class='text'  value="{$n_price}" /></td></tr>
	<tr><td>{t}Unit Retail Price{/t}:</td><td>{$cur_symbol} <input name="rrp" type='text'  SIZE="6" MAXLENGTH="20" class='text'value="{$n_rrp}"  /></td></tr>

      </table>
    </form>
  </div>
</div>
<div id="addtosupplier_form">
  <div class="hd">{t}Add to supplier{/t}</div> 
  <div class="bd"> 
    <form method="POST" action="ar_assets.php"> 
      <input name="tipo" type="hidden" value="add_tosupplier" />
      <input name="product_id" type="hidden" value="{$id}" />

      <br>
      <table >
	<tr>
	  <td>{t}Supplier{/t}:</td>
	  <td></td>	
	    <select name="supplier_id"   >
	      {foreach from=$asuppliers item=suppliers key=suppliers_id }
	      <option value="{$suppliers_id}" >{$suppliers}</option>
	      {/foreach}
	    </select>
	  </td>
	</tr>
	
	<tr><td>{t}Supplier Product Code{/t}:</td><td><input name="code" type='text' class='text' SIZE="16" MAXLENGTH="16" value=""/></td></tr>
  	<tr><td>{t}Supplier Price Unit{/t}:</td><td>{$cur_symbol} <input name="price" type='text'  SIZE="6" MAXLENGTH="20" class='text'  value="" /></td></tr>
	
	
	
	
	
      </table>
    </form>
  </div>
</div>
<div id="upload_pic_form">
  <div class="hd">{t}Upload a picture{/t}</div> 
  <div class="bd"> 
    <form  enctype="multipart/form-data" method="POST" action="ar_assets.php"   id="uploadpicForm"   > 
      <input type="hidden" name="tipo" value="uploadpic"/>
      <input type="hidden" name="product_id" value="{$id}"/>

      <br>
      <table >
	<tr><td>{t}Caption{/t}:</td><td><input  class="text" name="caption" type="text" /></td></tr>

	<tr><td>{t}File{/t}:</td><td><input  class="file" name="uploadedfile" type="file" /></td></tr>
	
      </table>
    </form>
  </div>
</div>


<div id="edit_details_form">
  <div class="hd">{t}Product Details{/t}</div> 
  <div class="bd"> 
<form method="post" action="ar_assets.php" id="form1">
<input type="hidden" name="product_id" value="{$id}" />
<input type="hidden" name="tipo" value="editproductdetails" />

<textarea id="editor" name="editor" rows="20" cols="100">
{$short_description}
</textarea>
</form>
  </div>
</div>
{/if}

{if $modify_stock}
<div id="setstock_form">
  <div class="hd">{t}Set Stock{/t}</div> 
  <div class="bd"> 
    <form method="POST" action="ar_assets.php"> 
      <input name="tipo" type="hidden" value="set_stock" />
      <input name="product_id" type="hidden" value="{$id}" />

      <br>
      <table >
	<tr><td>{t}Date{/t}:</td><td><input name="date" type='text' class='text' SIZE="10" MAXLENGTH="10" value="{$date}"/></td></tr>
	<tr><td>{t}Time{/t}:</td><td><input name="time" type='text'  SIZE="5" MAXLENGTH="5" class='text' value="{$time}"/></td></tr>
	<tr><td>{t}Checked by{/t}:</td><td>	
	    <select name="author"   >
	      {foreach from=$acheckedby item=checkedby key=checkedby_id name=foo}
	      <option value="{$checkedby_id}" {if $smarty.foreach.foo.iteration==2}selected="selected"{/if}  >{$checkedby}</option>
	      {/foreach}
	</select</td></tr>
	<tr><td>{t}Number of Outers{/t}:</td><td><input name="qty" type='text'  SIZE="12" MAXLENGTH="20" class='text'   /></td></tr>


      </table>
    </form>
  </div>
</div>
{/if}


{include file='footer.tpl'}

