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
