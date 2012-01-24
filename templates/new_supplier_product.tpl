{include file='header.tpl'}
<div id="bd">
<h1>{t}New Supplier Product{/t}</h1>

<div class="search_box" ></div>
<div id="contact_messages_div" >
      <span id="contact_messages"></span>
    </div>
<div >
     <div id="results" style="margin-top:0px;float:right;width:600px;"></div>
	 <div  style="float:left;width:600px;" >

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



</table>


<table class="options" style="float:right;padding:0;margin:0">
	<tr>
	<div class="buttons" >
			<button  style="margin-right:10px;visibility:"  id="save_new_product" class="positive disabled">{t}Save{/t}</button>
			<button style="margin-right:10px;visibility:" id="reset_new_product" class="negative">{t}Reset{/t}</button>
	</div>
	</tr>
</table>
    
      </div>
      <div style="clear:both;height:40px"></div>
	</div>
      </div>
<div class="star_rating" id="star_rating_template" style="display:none"><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /></div>



</div>

{include file='footer.tpl'}