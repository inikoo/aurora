<div id="dialog_change_products_element_chooser" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Group products by{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="products_element_chooser_use" style="float:none;margin:0px auto;min-width:120px" onclick="change_products_element_chooser('type')" class="{if $elements_product_elements_type=='type'}selected{/if}"> {t}Type{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="products_element_chooser_state" style="float:none;margin:0px auto;min-width:120px" onclick="change_products_element_chooser('web')" class="{if $elements_product_elements_type=='web'}selected{/if}"> {t}Web State{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="products_element_chooser_stock_state" style="float:none;margin:0px auto;min-width:120px" onclick="change_products_element_chooser('stock')" class="{if $elements_product_elements_type=='stock'}selected{/if}"> {t}Stock Level{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>