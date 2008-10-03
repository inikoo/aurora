{include file='header.tpl'}
<div id="bd" >
<span class="nav2 onright"><a href="assets_index.php">{t}Product index{/t}</a></span>
  <div id="yui-main">
    <div class="yui-b">
      <h2>{t}Products to sell{/t}</h2>
      
       <fieldset class="prodinfo" style="width:670px;height:90px">
	   <legend>{t}Products Overview{/t}</legend>
	   <table id="otable0" {if $view_table!=0} style="display:none"{/if}   >
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
      </fieldset>

       <div class="data_table" style="margin-top:25px">
       {include file='table.tpl' hide_table=$hide_first  table_id=0 table_title=$t_title filter=$filter filter_name=$filter_name}
       <div {if $hide_first==1} style="display:none"{/if} id="table0"   class="data_table_container dtable btable {$showtable}"> </div>

       {if $view_stock}
       <div {if $view_table!=1} style="display:none"{/if} id="table1" class="data_table_container dtable btable"></div>
       {/if}
       {if $view_sales}<div  {if $view_table!=2} style="display:none"{/if} id="table2" class="data_table_container dtable btable"></div>{/if}
       </div>

    </div>
  </div>
  <div class="yui-b" style="text-align:right">
    {include file='product_search.tpl'}

    {if $view_sales or $view_stock}
    <table class="options" >
      <tr {if $products==0 }style="display:none"{/if}>
	<td  {if $view_table==0}class="selected"{/if} id="but_view0" >{t}Basic{/t}</td>
	{if $view_stock}<td {if $view_table==1}class="selected"{/if}  id="but_view1"  >{t}Stock{/t}</td>{/if}
	{if $view_sales}<td  {if $view_table==2}class="selected"{/if}  id="but_view2"  >{t}Sales{/t}</td>{/if}
    </table>
    {/if}
    {if $modify}
    <table class="options" >
      <tr><td   id="but_view3" >{t}Edit{/t}</td>  </tr>
    </table>


    <div id="edit_menu" style="display:none">
      <table class="but edit" style="float:right">
	<tr><td id="add_department">{t}Add Department{/t}</td></tr>
	<tr><td id="upload">{t}Upload Products{/t}</td></tr>
      </table>
    {*}
    <p>
    {t escape=no}You can also insert departments, families and products  from a csv file. Download the <a href="templates.php?in=root">template</a>, edit it,  upload it and voila!{/t}
    </p>
    <button id="upload">{t}Upload Products{/t}</button>
    
    <h2>Inventories</h2>
    <p>You can check a cvs file for the last inventory  <a   href="inventory.php?tipo=last" >here</a>.</p>
    <p>Use this  <a   href="inventory.php?tipo=new">template</a> create a new inventory.</p>
    <button id="upload_inventory">{t}Upload Inventory{/t}</button>
  
    {/*}


    </div>
    {/if}
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
