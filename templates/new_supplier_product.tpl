{include file='header.tpl'}

<div id="bd" style="padding:0px">
<input type="hidden" id="supplier_key" value="{$supplier->id}"/>
{include file='suppliers_navigation.tpl'} 
<div style="padding:0px 20px;">

		<div class="branch">
 			<span  class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; {$supplier->get('Supplier Name')}</span> 
			</span>
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
		
			<span class="main_title">{t}New Supplier Product{/t}</span>
			</div>
			<div class="buttons" style="float:right">
				<button onclick="window.location='supplier.php?id={$supplier->id}'"><img src="art/icons/table_edit.png" alt=""> {t}Cancel{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		
	




<div id="contact_messages_div" >
      <span id="contact_messages"></span>
    </div>
<div style="margin-top:20px">
     <div id="results" style="margin-top:0px;float:right;width:600px;"></div>
	 <div  style="float:left;width:800px;" >

<input type="hidden" value="{$supplier_key}" id="supplier_key"/>
    <table class="edit"  border="0" style="width:100%;margin-bottom:0px" >
	
	<tr class="title">
	<td colspan=3>{t}Supplier Product Info{/t}</td>
	</tr>


	 <tr><td style="width:200px"class="label">{t}Product Code{/t}:</td><td style="width:370px">
	  <div  >
	   <input  style="width:100%" id="product_code" changed=0 type='text' MAXLENGTH="255"  class='text' 
	   value="" />
	   <div id="product_code_Container"  ></div>
       </div>
	   </td>
	   <td id="product_code_msg" class="edit_td_alert" ></td>
	  </tr>



	 <tr><td style="width:200px"class="label">{t}Units per case{/t}:</td><td style="width:370px">
	  <div  >
	   <input  style="width:100%" id="units_per_case" changed=0 type='text' MAXLENGTH="255"  class='text' 
	   value="" />
	   <div id="units_per_case_Container"  ></div>
       </div>
	   </td>
	   <td id="units_per_case_msg" class="edit_td_alert" ></td>
	  </tr>

	 <tr><td style="width:200px"class="label">{t}Case Cost{/t}:</td><td style="width:370px">
	  <div  >
	   <input  style="width:100%" id="case_cost" changed=0 type='text' MAXLENGTH="255"  class='text' 
	   value="" />
	   <div id="case_cost_Container"  ></div>
       </div>
	   </td>
	   <td id="case_cost_msg" class="edit_td_alert" ></td>
	  </tr>

	 <tr><td style="width:200px"class="label">{t}Product Name{/t}:</td><td style="width:370px">
	  <div  >
	   <input  style="width:100%" id="product_name" changed=0 type='text' MAXLENGTH="255"  class='text' 
	   value="" />
	   <div id="product_name_Container"  ></div>
       </div>
	   </td>
	   <td id="product_name_msg" class="edit_td_alert" ></td>
	  </tr>

	 <tr><td style="width:200px"class="label">{t}Product Description{/t}:</td><td style="width:370px">
	  <div  >
	   <input  style="width:100%" id="product_description" changed=0 type='text' MAXLENGTH="255"  class='text' 
	   value="" />
	   <div id="product_description_Container"  ></div>
       </div>
	   </td>
	   <td id="product_description_msg" class="edit_td_alert" ></td>
	  </tr>
<tr style="height:10px">
<td colspan=2>
</td>
</tr>

<tr>
<td colspan=2>
<div class="buttons" >
			<button  style="margin-right:10px;visibility:"  id="save_new_product" class="positive disabled">{t}Continue{/t}</button>
			<button style="margin-right:10px;visibility:" id="reset_new_product" class="negative">{t}Cancel{/t}</button>
	</div>
</td>
</tr>

</table>

  <table class="edit"  border="0" style="width:100%;margin-bottom:0px" >
	
	<tr class="title">
	<td colspan=3>{t}Part Info{/t}</td>
	</tr>


		 <tr><td style="width:200px"class="label">{t}Part Description{/t}:</td><td style="width:370px">
	  <div  >
	   <input  style="width:100%" id="part_description" changed=0 type='text' MAXLENGTH="255"  class='text' 
	   value="" />
	   <div id="part_description_Container"  ></div>
       </div>
	   </td>
	   <td id="part_description_msg" class="edit_td_alert" ></td>
	  </tr>

		<tr><td style="width:200px"class="label">{t}Part Weight{/t}:</td><td style="width:370px">
	  <div  >
	   <input  style="width:100%" id="gross_weight" changed=0 type='text' MAXLENGTH="255"  class='text' 
	   value="" />
	   <div id="gross_weight_Container"  ></div>
       </div>
	   </td>
	   <td id="gross_weight_msg" class="edit_td_alert" ></td>
	  </tr>
<tr style="height:10px">
<td colspan=2>
</td>
</tr>

<tr>
<td colspan=2>
<div class="buttons" >
			<button  style="margin-right:10px;visibility:"  id="save_new_product" class="positive disabled">{t}Continue{/t}</button>
			<button style="margin-right:10px;visibility:" id="reset_new_product" class="negative">{t}Cancel{/t}</button>
	</div>
</td>
</tr>

</table>

    
      </div>
      <div style="clear:both;height:40px"></div>
	</div>
      </div>

</div>

</div>

{include file='footer.tpl'}