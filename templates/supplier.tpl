{include file='header.tpl'}
{*}<script type="text/javascript"><!--{$js_code}// --></script>{/*}

<div id="bd" >

  
  <span class="nav2"><a href="suppliers.php">{$home}</a></span>
  
  
  <div id="yui-main">
    
    <div style="float:right">
      
      <div class="yui-b" style="text-align:right">
	<table class="options" >
	  <tr>
	    <td {if $view_block[0]==1 }class="selected"{/if}    id="but_view0" >{t}Purchases{/t}</td>
	    <td {if $view_block[1]==1 }class="selected"{/if}    id="but_view1"  >{t}Products{/t}</td>
	  </tr>
	</table>
	<table class="but"  id="buts">
	  
	  <tr><td id="new_po">{t}Create PO{/t}</td></tr>
	  <tr><td id="dn">{t}New Delivery{/t}</td></tr>
	  <tr ><td id="edit_products" >{t}Manage Products{/t}</td>  </tr>
	  <tr ><td id="edit_supplier" >{t}Edit Supplier Info{/t}</td>  </tr>
	</table>
      </div>
    </div>
      <div class="yui-b" >
	<h2>{$supplier.name}</h2>
	<fieldset class="prodinfo" style="width:730px">
	  <legend>{t}General Information{/t}</legend>
	  <table >
	    <tr><td>{t}Id{/t}:</td><td class="aright">{$supplier.id_name}</td></tr>
	    <tr><td>{t}Code{/t}:</td><td class="aright" >{$supplier.code}</td></tr>
	    <tbody id="names_b"   style="background:#dbedff"    >
	    {if $company}<tr  id="name_c"    key="name"  busy="0" ><td>{t}Company{/t}:</td><td>{$company}</td><td id="company_edit" style="xdisplay:none"><input type="text" style="width:100%" class="text" value="{$v_company}"/></td><td></td></tr>{/if}
	    {if $contact}<tr><td>{t}Contact{/t}:</td><td>{$contact}</td><td></td></tr>{/if}
	    </tbody>
	    <tbody id="tel_b" style="background:#e6ffdb">
	    {foreach from=$tels key=tel_id  item=tel name=foo}
	    <tr  id="tel_c{$tel_id}"   c_id="{$tel_id}" key="tel" busy="0" >
	      <td id="tel_l{$tel_id}">{t}Tel{/t}:</td>
	      <td id="tel{$tel_id}" >{$tel.tel}</td>
	      <td>
		<input type="text" class="text" size="10" id="tel_name{$tel_id}"  value="{$tel.name}" > 
		<input type="text" class="text" size="3" id="tel_code{$tel_id}"   value="{$tel.code}"  >
		<input type="text" class="text" size="15" id="tel_number{$tel_id}" value="{$tel.number}"  > {t}Ext{/t} <input type="text" class="text" size="3" id="tel_ext{$tel_id}" value="{$tel.ext}" >
	      </td>
	      <td>
		<img      id="tel_del{$tel_id}"    src="art/icons/cross.png" align="bottom" style="vertical-align:top;padding:0;border:none;cursor:pointer">
	      </td>
	    </tr>
	    {/foreach}
	    <tr id="tel_c" c_id="" key="tel" busy="0"  >
	      <td id="tel_l">{t}New Tel{/t}:</td><td id="tel"></td>
	      <td style="vertical-align:bottom;" >
		<input type="text" class="text labels" size="10" id="tel_name"  value="{t}description{/t}" > 
		<input type="text" class="text labels" size="3" id="tel_code"   value="{t}code{/t}"  >
		<input type="text" class="text labels" size="15" id="tel_number" value="{t}number{/t}"   > {t}Ext{/t} <input  type="text" class="text" size="3" id="tel_ext">
	      </td>
	      <td>		
		<img      id="tel_del"    src="art/icons/cross.png" align="bottom" style="display:none;vertical-align:top;padding:0;border:none;cursor:pointer">
	      </td>
	    </tr>
	    </tbody>
	    <tbody id="fax_b"   style="background:#dbedff"    >
	    {foreach from=$faxes key=fax_id  item=fax name=foo}
	    <tr id="fax_c{$fax_id}" c_id="{$fax_id}"  busy="0"   key="fax">
	      <td id="fax_l{$fax_id}">{t}Fax{/t}:</td>
	      <td id="fax{$fax_id}"  >{$fax.fax}</td>
	      <td>
		<input type="text" class="text" size="10" id="fax_name{$fax_id}"  value="{$fax.name}" > 
		<input type="text" class="text" size="3" id="fax_code{$fax_id}"   value="{$fax.code}"  >
		<input type="text" class="text" size="15" id="fax_number{$fax_id}" value="{$fax.number}"   >
	      </td>
	      <td>		

		<img      id="fax_del{$fax_id}"    src="art/icons/cross.png" align="bottom" style="vertical-align:top;padding:0;border:none;cursor:pointer">
	      </td>
	    </tr>
	    {/foreach}
	    <tr id="fax_c" c_id=""   busy="0" key="fax">
	      <td id="fax_l">{t}New Fax{/t}:</td><td id="fax" ></td>
	      <td style="vertical-align:bottom;">
		<input type="text" class="text labels" size="10" id="fax_name"  value="{t}description{/t}" > 
		<input type="text" class="text labels" size="3" id="fax_code"   value="{t}code{/t}"  >
		<input type="text" class="text labels" size="15" id="fax_number" value="{t}number{/t}"  >
	  </td>
	      <td>	
		<img      id="fax_del"    src="art/icons/cross.png" align="bottom" style="display:none;vertical-align:top;padding:0;border:none;cursor:pointer">

	      </td>
	    </tr>
	    </tbody>
	    <tbody id="email_b" style="background:#e6ffdb">
	    {foreach from=$emails key=email_id item=email name=foo}
	    <tr id="email_c{$email_id}" c_id="{$email_id}"  busy="0"  key="email">
	    <td id="email_l{$email_id}">{t}Email{/t}</td>  
	    <td   id="email{$email_id}"   class="aright">{$email.email}</td>
	    <td>
	      <input type="text" class="text" size="10" id="email_name{$email_id}"  value="{$email.contact}" > 
	      <input type="text" class="text" size="30" id="email_address{$email_id}"  value="{$email.address}">
	    </td>
	    <td>
	      <img      id="email_del{$email_id}"    src="art/icons/cross.png" align="bottom" style="vertical-align:top;padding:0;border:none;cursor:pointer">

	    </td>
	    </tr>
	    {/foreach}

	    <tr id="email_c" c_id=""  busy="0"  key="email">
	      <td id="email_l" >{t}New email{/t}:</td>
	      <td id="email"></td>
	      <td>
		<input type="text" class="text labels" size="10" id="email_name"  value="{t}contact{/t}" > 
		<input type="text" class="text labels" size="30" id="email_address"  value="{t}email address{/t}">
		   </td>
	      <td>	
	<img      id="email_del"    src="art/icons/cross.png" align="bottom" style="display:none;vertical-align:top;padding:0;border:none;cursor:pointer">
			      
	      </td>
	    </tr>
	    </tbody>
	    <tbody id="www_b" style="background:#dbedff">
	    {foreach from=$wwws key=www_id item=www name=foo}
	    <tr id="www_c{$www_id}"   busy="0" c_id="{$www_id}" key="www">
	      <td id="www_l{$www_id}">{t}Web Page{/t}:</td>
	      <td id="www{$www_id}" class="aright">{$www.www}</td>
	       <td>
		 <input   size="10"  type="text" class="text" size="20" id="www_name{$www_id}" value="{$www.title}" >
		 <input  size="30" type="text" class="text" size="20" id="www_address{$www_id}" value="{$www.address}" >
	       </td>
	      <td>
		<img      id="www_del{$www_id}"    src="art/icons/cross.png" align="bottom" style="vertical-align:top;padding:0;border:none;cursor:pointer">
	      </td>
	    </tr>
	    {/foreach}

	    <tr id="www_c" c_id="" key="www"   busy="0"  >
	      <td id="www_l">{t}New web address{/t}:</td>
	      <td id="www"></td>
	      <td>
		<input size="10"  type="text" class="text labels" size="20" id="www_name"  value="{t}title{/t}" >
		<input size="30" type="text" class="text labels" size="20" id="www_address"  value="{t}www address{/t}"  >
	      </td>
	      <td >
		<img id="www_del"    src="art/icons/cross.png" align="bottom" style="display:none;vertical-align:top;padding:0;border:none;cursor:pointer">
	      </td>
	    </tr>
	    </tbody>
	    
	    <tbody id="a_b" style="background:#e6ffdb">
	      {foreach from=$addresses key=a_id item=address name=foo}
	    <tr  id="a_c{$a_id}" c_id=""  busy="0"  key="a"  >
	      <td  id="a_l{$a_id}">{t}Address{/t}:</td>
	      <td  id="a{$a_id}"   >{$address.address}</td>
	      <td>
		<table>
		  <tr ><td>{t}Description{/t}:</td><td><input    class="text" type="text" id="a_desc{$a_id}" value="{$address.description}"></td></tr>
		  <tr ><td>{t}Internal{/t}:</td><td><input  class="text" type="text" id="a_1{$a_id}" value="{$address.a1}"></td></tr>
		  <tr ><td>{t}Bulding{/t}:</td><td><input  class="text" type="text" id="a_2{$a_id}" value="{$address.a2}"></td></tr>
		  <tr ><td>{t}Street{/t}:</td><td><input  class="text" type="text" id="a_3{$a_id}" value="{$address.a3}"></td></tr>
		  <tr ><td>{t}Town{/t}:</td><td><input  class="text" type="text" id="a_town{$a_id}" value="{$address.town}"></td></tr>
		  <tr ><td>{t}Post Code{/t}:</td><td><input  class="text" type="text" id="a_pc{$a_id}" value="{$address.pc}"></td></tr>
		  <tr ><td>{t}Country{/t}:</td><td>
		      <select id="a_country{$a_id}"  >
			{foreach from=$countries item=country key=country_id}
			<option   {if $country_id==$address.country_id  }selected{/if}     value="{$country_id}">{$country}</option>
			{/foreach}
		      </select>
		    </td>
		  </tr>
		</table>
	      </td>
	      <td >
		<img id="www_a{$a_id}"    src="art/icons/cross.png" align="bottom" style="vertical-align:top;padding:0;border:none;cursor:pointer">
	      </td>
	    </tr>
	    {/foreach}
	       <tr  id="a_c" c_id=""   busy="0" key="a"  >
	      <td  id="a_l">{t}New address{/t}:</td>
	      <td  id="a"   ></td>
	      <td>
		<table>
		  <tr ><td>{t}Description{/t}:</td><td><input  class="text" type="text" id="a_desc" value=""></td></tr>
		  <tr ><td>{t}Internal{/t}:</td><td><input  class="text" type="text" id="a_1" value=""></td></tr>
		  <tr ><td>{t}Bulding{/t}:</td><td><input  class="text" type="text" id="a_2" value=""></td></tr>
		  <tr ><td>{t}Street{/t}:</td><td><input  class="text" type="text" id="a_3" value=""></td></tr>
		  <tr ><td>{t}Town{/t}:</td><td><input  class="text" type="text" id="a_town" value=""></td></tr>
		  <tr ><td>{t}Post Code{/t}:</td><td><input  class="text" type="text" id="a_pc" value=""></td></tr>
		  <tr ><td>{t}Country{/t}:</td><td>
		      <select id="a_country"  >
			{foreach from=$countries item=country key=country_id}
			<option   {if $country_id==$default_country_id  }selected{/if}     value="{$country_id}">{$country}</option>
			{/foreach}
		      </select>
		    </td>
		  </tr>
		</table>
	      </td>
	      <td >
		<img id="www_a"    src="art/icons/cross.png" align="bottom" style="display:none;vertical-align:top;padding:0;border:none;cursor:pointer">
	      </td>
	    </tr>



	    </tbody>
	    
	  </table>

	  <table >
	    <tr><td>{t}Out of Stock{/t}:</td><td class="stock">{$supplier.outofstock}</td></tr>
	    <tr><td>{t}Low Availability {/t}:</td><td class="aright">{$supplier.lowstock}</td></tr>
	  </table>
	</fieldset>
	<div id="block0" {if $view_block[0]==0}style="display:none"{/if}>
	  {if $supplier.pos>0}{include file='table.tpl' table_id=1 table_title=$t_title1  filter=$filter1 filter_name=$filter_name1   filter_value=$filter_value1  options=$table1_options options_status=$table1_options_status  } {/if}
	</div>
	<div id="block1" {if $view_block[1]==0}style="display:none"{/if} >
	  {if $supplier.products>0}{include file='table.tpl' table_id=2 table_title=$t_title2 filter=$filter2 filter_name=$filter_name2         filter_value=$filter_value2   } {/if}
	</div>
      </div>
    </div>
    <div class="yui-b"></div>

  </div> 
</div>



{include file='footer.tpl'}

