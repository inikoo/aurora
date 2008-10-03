{include file='header.tpl'}
<div id="bd" >

{if $next.id>0}<span class="nav2 onright"><a href="assets_family.php?id={$next.id}">{t}Next{/t}</a></span>{/if}
{if $prev.id>0}<span class="nav2 onright" ><a href="assets_family.php?id={$prev.id}">{t}Previous{/t}</a></span>{/if}
<span class="nav2 onright" style="margin-left:20px"><a href="assets_department.php?id={$department_id}">{t}Up{/t}</a></span>


<span class="nav2 onright"><a href="assets_index.php">{t}Product index{/t}</a></span>
<span class="nav2"><a href="assets_tree.php">{$home}</a></span>
<span class="nav2"><a href="assets_department.php?id={$department_id}">{$department}</a></span>


  <div id="yui-main" >
    <div class="yui-b">
      <h2>{$family}, {$family_description}</h2>
      <div class="data_table" style="margin-top:25px">
	{include file='table.tpl' hide_table=$hide_first  table_id=0 table_title='Products' filter=$filter filter_name=$filter_name}
	<div {if $hide_first==1} style="display:none"{/if} id="table0"   class="data_table_container dtable btable {$showtable}"> </div>
        {if $view_stock}<div  {if $view_table!=1} style="display:none"{/if} id="table1" class="data_table_container dtable btable"></div>{/if}
	{if $view_sales}<div  {if $view_table!=2} style="display:none"{/if} id="table2" class="data_table_container dtable btable"></div>{/if}
      </div>
    </div>
  </div>
  <div class="yui-b" style="text-align:right">
    {include file='product_search.tpl'}
    {if $products>0 and ($view_sales or $view_stock) }
    <table class="options" >
      <tr><td  {if $view_table==0}class="selected"{/if} id="but_view0" >{t}Basic{/t}</td></tr>
      {if $view_stock}<tr><td {if $view_table==1}class="selected"{/if}  id="but_view1"  >{t}Stock{/t}</td></tr>{/if}
      {if $view_sales}<tr><td  {if $view_table==2}class="selected"{/if}  {if $products==0 }style="display:none"{/if} id="but_view2"  >{t}Sales{/t}</td></tr>{/if}
    </table>
    {/if}
    {if $modify}
    <table class="options" >
      <tr><td   id="but_view3" >{t}Edit{/t}</td>  </tr>
    </table>
    <div id="edit_menu" style="display:none">
      <table class="but edit" style="float:right">
	{if $create}<tr><td id="add_product">{t}Add Product{/t}</td></tr>{/if}
      </table>
    </div>
    {/if}
     
  </div>
</div> 
<div id="add_product_form">
  <div class="hd">{t}New Product{/t}</div> 
  <div class="bd"> 
    <form method="POST" action="ar_assets.php"> 
      <input name="tipo" type="hidden" value="new_product" />
      <input name="family_id" type="hidden" value="{$family_id}" />

      <br>
      <table >
	<tr><td>{t}Code{/t}:</td><td><input name="code" type='text' class='text' SIZE="16" MAXLENGTH="16"/></td></tr>
	<tr><td>{t}Description{/t}:</td><td><input name="description" type='text'  SIZE="35" MAXLENGTH="80" class='text' /></td></tr>
	<tr><td>{t}Units per Outer{/t}:</td><td><input name="units"  SIZE="4" type='text'  MAXLENGTH="20" class='text' /></td></tr>
	<tr><td>{t}Units per Carton{/t}:</td><td><input name="units_carton"  SIZE="4" type='text'  MAXLENGTH="20" class='text' /></td></tr>
	<tr><td>{t}Type of Unit{/t}:</td><td>	
	    <select name="units_tipo"  id="units_tipo" >
	      {foreach from=$units_tipo item=tipo key=tipo_id }
	      <option value="{$tipo_id}">{$tipo}</option>
	      {/foreach}
	</select></td></tr>
	<tr><td>{t}Price Outer{/t}:</td><td><input name="price" type='text'  SIZE="6" MAXLENGTH="20" class='text' /></td></tr>
	<tr><td>{t}Retail Price{/t}:</td><td><input name="rrp" type='text'  SIZE="6" MAXLENGTH="20" class='text' /></td></tr>

	
	<tr><td>{t}Supplier{/t}:</td><td>	
	    <select name="supplier_id"   >
	      {foreach from=$asuppliers item=suppliers key=suppliers_id }
	      <option value="{$suppliers_id}" >{$suppliers}</option>
	      {/foreach}
	</select></td></tr>
	<tr><td>{t}Supplier Product Code{/t}:</td><td><input name="scode" type='text' class='text' SIZE="16" MAXLENGTH="16" value=""/></td></tr>
  	<tr><td>{t}Supplier Price Unit{/t}:</td><td>{$cur_symbol} <input name="sprice" type='text'  SIZE="6" MAXLENGTH="20" class='text'  value="" /></td></tr>
	



	

      </table>
    </form>
  </div>
</div>
<div id="upload_product_form">
  <div class="hd">{t}New Products from file{/t}</div> 
  <div class="bd"> 
    <form  enctype="multipart/form-data" method="POST" action="upload_assets.php"   id="uploadForm"   > 
      <input name="from" type="hidden" value="family" />
      <br>
      <table >
	<tr><td>{t}CVS File{/t}:</td><td><input  class="file" name="uploadedfile" type="file" /></td></tr>
      </table>
    </form>
  </div>
</div>
{include file='footer.tpl'}
