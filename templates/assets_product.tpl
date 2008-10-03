{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>


<div id="bd" >

<div id="sub_header">
{if $next.id>0}<span class="nav2 onright"><a href="report_sales.php?id={$next.id}">{t}Next{/t}</a></span>{/if}
{if $prev.id>0}<span class="nav2 onright" ><a href="assets_product.php?id={$prev.id}">{t}Previous{/t}</a></span>{/if}
<span class="nav2 onright" style="margin-left:20px"><a href="assets_family.php?id={$family_id}">{t}Up{/t}</a></span>
<span class="nav2 onright"><a href="assets_index.php">{t}Product inddex{/t}</a></span>
<span class="nav2"><a href="assets_tree.php">{$home}</a></span>
<span class="nav2"><a href="assets_department.php?id={$department_id}">{$department}</a></span>
<span class="nav2"><a href="assets_family.php?id={$family_id}">{$family}</a></span>
</div>
<div id="doc3" style="clear:both;" class="yui-g yui-t4" >
<div id="yui-main"> 
  <div class="yui-b">


<div  class="yui-gd" style="clear:both;padding:0;width:100%">
 <div class="yui-u first" >
  <div id="photo_container">
    <div style="border:1px solid #ddd;padding-top:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
     <span style="font-size:150%;font-weight:800">{$code}</span>
	  <div id="imagediv"  pic_id="{$images[0].id}"  style="border:1px solid #ddd;width:200px;height:140px;padding:0px 0;xborder:none;cursor:pointer;xbackground:red;margin: 0 0 10px 9px">
	    <img src="{ if $images[0]}{$images[0].src}{else}art/nopic.png{/if}"     id="image"   alt="{t}Image{/t}"/>
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
         
        {if $outall>0 and $view_sales}
	<table class="show_info_product" style="margin-top:10px;width:220px">
	  <tr><td id="outall_label" title="Total Sales">{t}TS{/t}:</td><td class="aright" >{$outall} {t}Outers{/t}
         </td></tr>
	  <tr><td id="awoutall_label" title="Average Weelky Sales" >{t}AWS{/t}:</td><td class="aright" >{$awoutall} {t}Outers/w{/t}</td></tr>
	  <tr><td id="awoutq_label" title="Average Weelky Sales (Last 12 weeks)">{t}AWSQ{/t}:<td class="aright" >{$awoutq} {t}Outers/w{/t}</td></tr>
	</table>
	{/if}
   </div>

	{if $edit}


	<div>
	    <form  enctype="multipart/form-data" method="POST" action="ar_assets.php"   id="uploadpicForm"   > 
      <input type="hidden" name="tipo" value="uploadpic"/>
      <input type="hidden" name="product_id" value="{$id}"/>

      
      <table >
	<tr><td>{t}Add a new picture{/t}</td></tr>
	<tr><td>{t}Caption{/t}:</td><td><input  class="text" name="caption" type="text" /></td></tr>

	<tr><td>{t}File{/t}:</td><td><input  class="file" name="uploadedfile" type="file" /></td></tr>
	
      </table>
    </form>
</div>
	{/if}
	</div>


	<div class="yui-u">
	<h1>{$description}</h1>
<div class="yui-b"  style="width:100%;">
      <div class="yui-g" style="width:100%;"   >
         <div class="yui-u first">
<div style="height:8em;">
	<table class="show_info_product" {if $edit}class="edit"{/if}>
	 {if $edit} <tr><td colspan="2">{t}Product Identification{/t}</td></tr>{/if}
	  <tr>
	    <td>{t}Numeric code{/t}:</td><td colspan="2">{$product_id}</td>
	  </tr>
	  <tr>
	    <td>{t}Unique code{/t}:</td><td>{$code}</td>{if $edit}<td class="aright"><input value="{$code}"> </td>{/if}
	  </tr>
	  <tr>
	    <td>{t}Product Family{/t}:</td><td><a href="assets_family.php?id={$family_id}">{$family}</a></td>{if $edit}<td class="aright"><span style="float:left">{t}Current Family:{/t}<span>{$family}</span> <span>[{t}Families list{/t}]</span></span> {t}Family unique code: {/t}<input value="{$family_id}"> </td>{/if}
	  </tr>
	  	  <tr>
		  {if $categories!='' or $edit}
	    <td>{t}Category{/t}:</td><td>{$categories}</td>{if $edit}<td class="aright"><span>{t}category1,category2...{/t} <span>[{t}Categories list{/t}]</span><br/></span><input style="width:100%" type="text" value="{$categories}"> 
	    </td>{/if}
	  </tr>
{/if}

	</table>
</div>



	<table    class="show_info_product"  {if $edit}class="edit"{/if}>
	 {if $edit} <tr><td colspan="3"> {t}Sale Properties{/t}</td></tr>{/if}

 <td>{t}Item units{/t}:</td><td class="aright" >{$units_tipo}</td>{if $edit}
	  <td class="aright">
	    <select name="units_tipo"  id="units_tipo" >
	      {foreach from=$aunits_tipo item=tipo key=tipo_id }
	      <option value="{$tipo_id}">{$tipo}</option>
	      {/foreach}
	      </select>
	  </td>{/if}
	  </tr>


	  <tr>
	    <td id="upo_label" title="Units per Outer">{t}UpO{/t}:</td><td  class="aright">{$units}</td>{if $edit}<td class="aright"><input type="text" value="{$units}"> 
	    </td>{/if}
	  </tr>
	  <tr>
	    <td>{t}Sell Price{/t}:</td><td  class="price aright">{$price}</td>
	  {if $edit}  <td  class="aright" >£<input value="{$v_price}" type="text"/> {t}per outer{/t}</td>{/if}
	  </tr>
	  <tr><td>{t}Sold Since{/t}:</td><td class="aright">{$first_date} ({$weeks_since}{t}w{/t})</td>
	   {if $edit} <td   class="aright" ><input style="text-align:right" class="date_input" size="8" type="text"  id="v_invoice_date"  value="{$v_po_date_invoice}" name="invoice_date" /></td>{/if}
	  </tr>


	</table>






	<table  class="show_info_product" {if $edit}class="edit"{/if}>
	  {if $edit}<tr><td colspan="3"> {t}Physical Properties{/t}</td></tr>{/if}
	  {if $uw or $edit}
	  <tr ><td>{t}Unit Weight{/t}:</td><td id="uw">{$uw}</td>{if $edit}<td class="aright"><input id="v_uw" class="aright text"  type="text" nvalue="{$n_uw}"  value="{$uw}"   onkeypress="return key_filter(event,{$key_filter_number})"  > {t}Kg{/t}</td>{/if}</tr>
	  {/if}
	   {if $udim or $edit}
	  <tr><td>{t}Unit Dimensions{/t}:</td><td>{$udim}</td>{if $edit}<td class="aright"><span id="v_udim_shape_ex">{$ashape_example[$ushape]}</span><input id="v_udim" type="text" value="{$udim}"  onkeypress="return key_filter(event,{$key_filter_dimension})"    > 
	      <select id="v_udim_shape">
		{foreach from=$ashape item=shape key=shape_id }
		<option value="{$shape_id}"  {if $shape_id==$ushape}selected="selected"{/if} >{$shape}</option>x
		{/foreach}
	      </select>
	  </td>{/if}</tr>
	  {/if}
	  {if $ow or $edit}<tr ><td>{t}Outer Weight{/t}:</td>{if $edit}<td>{$ow}</td><td  class="aright" ><input type="text" value="{$ow}"> {t}Kg{/t}</td>{/if}</tr>{/if}
	  {if $odim or $edit}<tr><td>{t}Outer Dimensions{/t}:</td><td>{$odim}</td>{if $edit}<td  class="aright" ><span id="v_odim_shape_ex" >{$ashape_example[$oshape]}</span><input id="v_odim"  type="text" value="{$odim}"  onkeypress="return key_filter(event,{$key_filter_number})"  > 
	      <select id="v_odim_shape">
		{foreach from=$ashape item=shape key=shape_id }
		<option value="{$shape_id}"  {if $shape_id==$oshape}selected="selected"{/if} >{$shape}</option>
		{/foreach}
	      </select>
	  </td>{/if}</tr>{/if}
	   {if $color or $edit}<tr><td>{t}Colour{/t}:</td><td>{$color}</td>{if $edit}<td  class="aright"><span>{t}colour1,colour2,...{/t} <span>[{t}Colour list{/t}]</span></span><input type="text" value="{$color}"> {/if}
	   </td>{/if}
	   </tr>
	</table>
	<table  class="show_info_product" {if $edit}class="edit"{/if}>
	  {if $edit} <tr><td colspan="3"> {t}Chemical Properties{/t}</td></tr>{/if}
	  {if $material or $edit}
	   <tr>
	     <td>{t}Material{/t}:</td>
	     <td>{$materials}</td>{if $edit}<td><span>{t}material1(%),material2(%),...{/t} <span>[{t}Materials list{/t}]</span> <span>[{t}Example{/t}]</span></span><input style="width:100%" type="text" value="{$color}"> 
	   </td>{/if}
	   </tr>{/if}
	   {if $ingredients!='' or $edit}
	  <tr>
	     <td>{t}Ingredients{/t}:</td>
	     <td>{$ingredients}</td>{if $edit}<td><span>{t}ingredient1,ingredient2,...{/t} <span>[{t}Ingredient list{/t}]</span></span><input style="width:100%" type="text" value="{$color}"> 
	   </td>{/if}
	   </tr>
{/if}
	</table>


         </div>
         <div class="yui-u">
<div style="height:8em;">
	<table   class="show_info_product" {if $edit}class="edit"{/if}>
	  <tr>
	    <td>{t}Stock{/t}:<br>{$stock_units}</td><td class="stock" id="stock" class="aright">{$stock}</td>{if $edit}<td class="aright">Set stock to <input class="text"/> outers<br>add <input type="text" > outers to current value  <br>substract <input type="text" > outers because <select></select>  <br/> {t}Can you explain why the stock change?{/t}<br><textarea></textarea><br>{t}Checked by:{/t}</td>{/if}
	  </tr>
{if $locations}
	    <tr>
	     <td>{t}Location{/t}:</td>
	     <td>{$locations}</td>{if $edit}<td><span>{t}location1(type),location2(type),...{/t} <span>[{t}Warehouse Map{/t}]</span>  <span>[{t}Example{/t}]</span><br/></span><input style="width:100%" type="text" value="{$color}"> 
	   </td>{/if}
	   </tr>{/if}
	   <tr>
	    <td>{t}Available{/t}:</td><td>{$available}</td>
	  </tr>
	  {if $nextbuy>0   }<tr><td rowspan="2">{t}Next shipment{/t}:</td><td>{$next_buy}</td></tr><tr><td class="noborder">{$nextbuy_when}</td>{/if}
	  </tr>
	</table>
	</div>
	<table  class="show_info_product" {if $edit}class="edit"{/if}   >
	  {if $edit}<tr><td colspan="2">{t}Suppliers{/t}</td>{if $edit}<td class="aright" ><button>{t}Add new Supplier{/t}</button></td>{/if}</tr>{/if}
	  {if $view_suppliers}
	  {if $suppliers>0}
	  {foreach from=$suppliers_name item=supplier key=supplier_id }
	  <tr><td>{t}Supplier{/t}:</td><td><a href="supplier.php?id={$supplier_id}">{$supplier}</a></td>{if $edit}<td  class="aright" ><button>{t}Delete{/t}</button> <button>{t}Desactivate{/t}</button> </td>{/if}</tr>
	  {if $suppliers_code[$supplier_id]}</td>{if $edit}<td  class="aright" ><input value="{$suppliers_code[$supplier_id]}" type="text"></td>{/if}</tr>{/if}
	   <tr><td>{t}Unit Cost Price{/t}:</td><td>{$suppliers_price[$supplier_id]}</td>{if $edit}<td class="aright" >£<input value="{$suppliers_price[$supplier_id]}" type="text"/></td>{/if}</tr>
	  {/foreach}
	  {else}
	  <tr><td colspan=2 style="color:brown;font-weight:bold;cursor:pointer">{t}No supplier set{/t}</td></tr>
	  {/if}
	  {/if}
	  {if $edit}
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
	  <tr><td>{t}Supplier code{/t}:</td>{if $edit}<td></td><td class="aright"><input value="" type="text"></td>{/if}</tr>
	  <tr><td>{t}Unit Cost Price{/t}:</td>{if $edit}<td></td><td class="aright">£<input value="" type="text"/></td>{/if}</tr>
{/if}
	</table>





         </div>
      </div>
   </div>


	</div>

</div>






</div> 
</div>
<div class="yui-b">
<div  style="float:right;margin-top:10px;text-align:right">
{include file='product_search.tpl'}
</div>	 
	 
<table border=0 cellpadding="2" style="float:right;margin-top:20px;" class="view_options">
<tr style="border-bottom:1px solid #ddd">

<th><img src="art/icons/information.png" title="{t}Product Details{/t}"/></th>
{if $view_orders}
<th><img src="art/icons/chart_line.png" title="{t}Charts{/t}"/></th>
<th><img  src="art/icons/cart.png" title="{t}Orders{/t}"/></th>
{/if}
<th><img src="art/icons/user_green.png" title="{t}Customers{/t}"/></th>
<th><img src="art/icons/package.png" title="{t}Stock{/t}"/></th>
</tr>
<tr style="height:18px;border-bottom:1px solid #ddd">
<td  id="but_view0" {if $hide[0]!=0}title="Show Product Details"{else}title="Hide Product Details"{/if} ><img {if $hide[0]!=0}style="display:none"{/if} src="art/icons/tick.png"  id="but_logo0"  /></td>

{if $view_orders}
<td  id="but_view3" {if $hide[3]!=0}title="Show Charts"{else}title="Hide Charts"{/if} ><img {if $hide[3]!=0}style="display:none"{/if} src="art/icons/tick.png"  id="but_logo3"  /></td>
<td  id="but_view1" {if $hide[1]!=0}title="Show Orders"{else}title="Hide Orders"{/if} ><img {if $hide[1]!=0}style="display:none"{/if} src="art/icons/tick.png"    id="but_logo1"   /></td>
{/if}
<td  id="but_view2" {if $hide[2]!=0}title="Show Customers who have Ordered"{else}title="Hide Customers who have Ordered"{/if} ><img {if $hide[2]!=0}style="display:none"{/if} src="art/icons/tick.png"    id="but_logo2"   /></td>
<td  id="but_view4" {if $hide[4]!=0}title="Show Stock History"{else}title="Hide Stock History"{/if} ><img {if $hide[4]!=0}style="display:none"{/if} src="art/icons/tick.png"    id="but_logo4"   /></td>

</tr>
<tr><td  colspan="5" style="text-align:center"><a href="edit_product.php?id={$product_id}">Edit Product</a></td></tr>
<tr><td  colspan="5" style="text-align:center"><a href="edit_product.php?id={$product_id}">Set Stock</a></td></tr>

</table>


</div> 




<div style="clear:both"><div>

<div  id="block3" class="product_plots"   {if $hide[3]==1} style="display:none"{/if}       >
	 
	 <h2 style="margin:0 0 0 50px ;padding:0" class="plot_title"  id="plot_title">{$plot_title}</h2>

	 <div id="plot_options" style="width:130px;float:right;text-align:right">
<h3>{t}Plot Menu{/t}</h3>
<table border=0 class="plot_menu">
<tr class="top">
<td class="left"></td>
<td  ><img src="art/icons/calendar_view_week.png" title="{t}Weekly{/t}"/></td>
<td><img src="art/icons/calendar_view_month.png" title="{t}Monthy{/t}"/></td>
<td><img src="art/icons/calendar_view_quarter.png" title="{t}Quarterly{/t}"/></td>
<td><img src="art/icons/calendar_view_year.png" title="{t}Yearly{/t}"/></td>
<tr>
<td class="left"><img src="art/icons/money.png" title="{t}Net Sales{/t}"/></td>
<td><img id="plot_sales_week" class="opaque"  src="art/icons/chart_line.png" title="{t}Sales per week{/t}"/></td>
<td><img id="plot_sales_month" class="opaque" src="art/icons/chart_bar.png" title="{t}Sales per month{/t}"/></td>
<td><img id="plot_sales_quarter" class="opaque" src="art/icons/chart_bar.png" title="{t}Sales per quarter{/t}"/></td>
<td><img id="plot_sales_year"class="opaque" src="art/icons/chart_line.png" title="{t}Sales per year{/t}"/></td>

<td></td>
</tr>
<tr>
<td class="left"><img src="art/icons/basket.png" title="{t}Outers Sold{/t}"/></td>
<td><img id="plot_out_week" class="opaque" src="art/icons/chart_line.png" title="{t}Outers sold per month{/t}"/></td>
<td><img id="plot_out_month" class="opaque" src="art/icons/chart_bar.png" title="{t}Outers sold per month{/t}"/></td>
<td><img id="plot_out_quarter" class="opaque" src="art/icons/chart_bar.png" title="{t}Outers sold  per quarter{/t}"/></td>
<td><img id="plot_out_year" class="opaque" src="art/icons/chart_line.png" title="{t}Outers sold  per year{/t}"/></td>
<td></td>
</tr>
<tr>
<td class="left"><img src="art/icons/package.png" title="{t}Stock{/t}"/></td>
<td><img id="plot_stock_day"  class="opaque" src="art/icons/chart_line.png" title="{t}Stock History{/t}"/></td>
<td></td>
<td></td>
<td></td>
</tr>
</table>





	 </div>
	 <div id="xplot0" class="product_plot"  style="height:300px;{if $view_plot!=0};display:none{/if}" >
	   <iframe id="the_plot" src ="plot.php?tipo={$plot_tipo}" frameborder=0 height="100%" scrolling="no" width="100%"></iframe>
	 </div>
	 


</div>


{if $view_orders} 
<div   id="block1" {if $hide[1]==1} style="display:none"{/if}    > 
<div class="data_table" style="margin-top:25px">
{include file='table.tpl'     table_id=0 table_title=$t_title0 filter=$filter filter_name=$filter_name}
<div  id="table0"   class="data_table_container dtable btable "> </div>
</div>
</div>
{/if}

{if $view_cust} 
<div id="block2" {if $hide[2]==1} style="display:none"{/if}    >
<div class="data_table" style="margin-top:25px">
{include file='table.tpl'    table_id=1 table_title=$t_title1  filter=$filter2 filter_name=$filter_name2}
<div  id="table1"   class="data_table_container dtable btable "> </div>
</div>
</div>
{/if}



{if $view_stock} 
<div id="block4" {if $hide[4]==1} style="display:none"{/if}     >  
{include file='table.tpl'  dates=1 table_id=2 table_title=$t_title2  options=$stock_table_options options_status=$stock_table_options_tipo  }
<div  id="table2"   class="data_table_container dtable btable "> </div>
</div>
</div>
{/if}


</div>


</div>{include file='footer.tpl'}

