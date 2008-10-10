{include file='header.tpl'}
<div id="bd" >
<span class="nav2 onright"><a href="assets_index.php">{t}Product index{/t}</a></span>
  <div id="top" style="width:100%;margin:15px 0 0 0 ">
    <div style="width:100%;background:red">

      <table style="float:left;margin:0 0 0 20px ;padding:0"  class="options" {if $products==0 }style="display:none"{/if}>
	  <tr><td  {if $view_table=='general'}class="selected"{/if} id="general_view" >{t}General{/t}</td>
	  {if $view_stock}<td {if $view_table=='stock'}class="selected"{/if}  id="stock_view"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $view_table=='sales'}class="selected"{/if}  id="sales_view"  >{t}Sales{/t}</td>{/if}</tr>
	</table>
<div style="float:right">
<form  id="prod_search_form" action="assets_index.php" method="GET" >
{t}Product Code{/t}
<input size="8" class="text search" id="prod_search" value="" name="search"/><img onclick="document.getElementById('prod_search_form').submit()"align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search">
 </form>
</div>

    </div>

    <div style="clear:both;padding:10px 20px ;border:1px solid #ddd;margin:0 20px;">
      <div style="float:left;">
	<h2 style="padding:0;margin:0">{t}Products by department{/t}</h2>
	

	<table id="otable0" style="font-size:90%;margin-top:10px" {if $view_table!=0} style="display:none"{/if}   >
	  <tr>
	    <td>{t}Number of Products{/t}:</td><td class="aright">{$products}</td>
	  </tr>
	   <tr>
	     <td>{t}Number of Families{/t}:</td><td class="aright">{$families}</td>
	   </tr>
	  <tr>
	    <td>{t}Number of Departments{/t}:</td><td class="aright">{$departments}</td>
	  </tr>
	  
	</table>
	{if $view_stock}
	   <table id="otable1" {if $view_table!=1} style="display:none"{/if}    >
	     <tr>
	       <td>{t}Stock Value{/t}:</td><td class="aright">{$stock_value}</td>
	     </tr>
	     
	   </table>
	   {/if}
	    {if $view_sales}
	   <table id="otable2"    {if $view_table!=2} style="display:none"{/if}   >
	     <tr>
	       <td>{t}Total Sales{/t}:</td><td class="aright">{$total_sales}</td>
	     </tr>
	     
	   </table>
	   {/if}
      </div>
      <div style="text-align:right;float:right">
	{include file='product_search.tpl'}
      </div>
      <div style="text-align:right;float:right">
	{if $view_sales or $view_stock}
	<table class="options" {if $products==0 }style="display:none"{/if}>
	  <tr><td  {if $view_table=='general'}class="selected"{/if} id="general_view" >{t}General{/t}</td></tr>
	  {if $view_stock}<tr><td {if $view_table=='stock'}class="selected"{/if}  id="stock_view"  >{t}Stock{/t}</td>{/if}</tr>
	  {if $view_sales}<tr><td  {if $view_table=='sales'}class="selected"{/if}  id="sales_view"  >{t}Sales{/t}</td>{/if}</tr>
	</table>

	{/if}
</div>
	       <div style="text-align:right;float:right">

	
      </div>
      <div style="clear:both"></div>
    </div>
  </div>
  
  <div class="data_table" style="margin:25px 20px">
	<span id="table_title" class="clean_table_title">{t}{$table_title}{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div id="table_info0" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg0"></span></div></div>

	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
	</div>
	<div  id="table0"   class="data_table_container dtable btable "> </div>
      </div>

</div> 


<div id="add_department_form">
  <div class="hd">{t}New Department{/t}</div> 
  <div class="bd"> 
    <form method="POST" action="ar_assets.php"> 
      <input name="tipo" type="hidden" value="new_department" />
      <br>
      <table >
	<tr><td>{t}Code{/t}:</td><td><input name="code" type='text' class='text' MAXLENGTH="16"/></td></tr>
	<tr><td>{t}Full Name{/t}:</td><td><input name="name" type='text'  MAXLENGTH="60" class='text' /></td></tr>
      </table>
    </form>
  </div>
</div>
{*}
<div id="upload_i_form">
  <div class="hd">{t}New Products from file{/t}</div> 
  <div class="bd"> 
    <form  enctype="multipart/form-data" method="POST" action="upload_inventory.php"   id="uploadForm"   > 
      <input name="from" type="hidden" value="tree" />
      <br>
      <table >
	<tr><td>{t}CVS File{/t}:</td><td><input  class="file" name="uploadedfile" type="file" /></td></tr>
      </table>
    </form>
  </div>
</div>
{/*}
<div id="upload_products_form">
  <div class="hd">{t}Inventory file{/t}</div> 
  <div class="bd"> 
    <form  enctype="multipart/form-data" method="POST" action="upload_assets.php"   id="uploadForm"   > 
      <br>
      <table >
	<tr><td>{t}CVS File{/t}:</td><td><input  class="file" name="uploadedfile" type="file" /></td></tr>
      </table>
    </form>
  </div>
</div>

{include file='footer.tpl'}
