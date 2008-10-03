{include file='header.tpl'}
<div id="bd" >
<span class="nav2 onright"><a href="assets_index.php">{t}Product index{/t}</a></span>
  <div id="yui-main">
    <div class="yui-b">
      <h2>Departments</h2>
      <div id="departments"  class="dtable btable  etable">
	<table id="table0" style="width:100%;border:1px solid #ccc">
	  {foreach from=$dept item=dept_item key=prod_key}
	  <tr>
	    <td>{$dept_item[0]}</td>
	    <td>{$dept_item[2]}</td>
	    <td>{$dept_item[3]}</td>

	  </tr>
	  {/foreach}
	</table>
      </div>
      <h2>Families</h2>
      <div id="families"  class="dtable btable etable">
	<table  id="table1" style="width:100%;border:1px solid #ccc">
	  {foreach from=$fam item=fam_item key=fam_key}
	  <tr>
	    <td>{$fam_item[0]}</td>
	    <td>{$fam_item[1]}</td>
	    <td>{$fam_item[3]}</td>
	    <td>{$fam_item[4]}</td>
	</tr>
	  {/foreach}
	</table>
      </div>
      <h2>Product Data (Requiered)</h2>
      <div id="products" class="dtable btable etable" style="font-size:80%">
	<table   id="table2"  style="width:100%;border:1px solid #ccf">
	  {foreach from=$prod item=prod_item key=prod_key}
	  <tr>
	    <td>{$prod_item[0]}</td>
	    <td>{$prod_item[1]}</td>
	    <td>{$prod_item[2]}</td>
	    <td>{$prod_item[3]}</td>
	    <td>{$prod_item[4]}</td>
	    <td>{$prod_item[5]}</td>
	    <td>{$prod_item[6]}</td>
	    <td>{$prod_item[7]}</td>
	    <td>{$prod_item[8]}</td>
	    <td>{$prod_item[9]}</td>
	    <td>{$prod_item[10]}</td>
	    <td>{$prod_item[11]}</td>
	    <td>{$prod_item[12]}</td>

	</tr>
	{/foreach}
    </table>
      </div>
	<h2>Product Data (Optional)</h2>
      <div id="products" class="dtable btable etable" style="font-size:80%">
	<table   id="table3"  style="width:100%;border:1px solid #ccf">
	  {foreach from=$prodopt item=prod_item key=prod_key}
	  <tr>
	    <td>{$prod_item[0]}</td>
	    <td>{$prod_item[1]}</td>
	    <td>{$prod_item[2]}</td>
	    <td>{$prod_item[3]}</td>
	    <td>{$prod_item[4]}</td>
	    <td>{$prod_item[5]}</td>
	    <td>{$prod_item[6]}</td>
	    <td>{$prod_item[7]}</td>
	    <td>{$prod_item[8]}</td>


	</tr>
	{/foreach}
    </table>


  </div>
    <h2>Product Dimensions (Optional)</h2>
      <div id="productsdim" class="dtable btable etable" style="font-size:80%">
	<table   id="table4"  style="width:100%;border:1px solid #ccf">
	  {foreach from=$proddim item=prod_item key=prod_key}
	  <tr>
	    <td>{$prod_item[0]}</td>
	    <td>{$prod_item[1]}</td>
	    <td>{$prod_item[2]}</td>
	    <td>{$prod_item[3]}</td>
	    <td>{$prod_item[4]}</td>
	    <td>{$prod_item[5]}</td>
	    <td>{$prod_item[6]}</td>
	    <td>{$prod_item[7]}</td>
	    <td>{$prod_item[8]}</td>
	    <td>{$prod_item[9]}</td>
	    <td>{$prod_item[10]}</td>
	    <td>{$prod_item[11]}</td>
	    <td>{$prod_item[12]}</td>
	    <td>{$prod_item[13]}</td>
	    <td>{$prod_item[14]}</td>




		    
	  </tr>
	{/foreach}
    </table>
  </div>
  <h2>Product Supplier (Optional)</h2>
      <div id="productsup" class="dtable btable etable" style="font-size:80%;">
	<table   id="table5"  style="width:100%;border:1px solid #ccf;width:50%">
	  {foreach from=$prodsup item=prod_item key=prod_key}
	  <tr>
	    <td>{$prod_item[0]}</td>
	    <td>{$prod_item[1]}</td>
	    <td>{$prod_item[2]}</td>
	    <td>{$prod_item[3]}</td>
	    <td>{$prod_item[4]}</td>
	    <td>{$prod_item[5]}</td>
	    <td>{$prod_item[6]}</td>
	    <td>{$prod_item[7]}</td>
	    <td>{$prod_item[8]}</td>


	    


	</tr>
	{/foreach}
    </table>
  </div>


  
  
      
  
    </div>
  </div>
  <div class="yui-b" style="text-align:left">
    <div id="upload_department_form">
      <div class="hd">{t}Upload the file again (if need it){/t}</div> 
      <div class="bd"> 
	<form  enctype="multipart/form-data" method="POST" action="upload_assets.php"   id="uploadForm"   > 
	  <input name="from" type="hidden" value="tree" />
	  <br>
	  <table >
	    <tr><td>{t}CVS File{/t}:</td><td><input  class="file" name="uploadedfile" type="file" /></td></tr>
	  </table>
	</form>
      </div>
    </div>
  </div>

  <div style="margin-left:32em;height:10em"><button id="submit">{t}Submit{/t}</button></div>
</div> 




{include file='footer.tpl'}
