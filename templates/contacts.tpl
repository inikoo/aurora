{include file='header.tpl'}
<div id="bd" >
<span class="nav2 onright"><a href="customers.php">{t}List of customers{/t}</a></span>
  <div id="yui-main">
    <div class="yui-b">
      <h2>{t}Contacts List{/t}</h2>
 {include file='table.tpl' table_id=0 table_title=$table_title filter=$filter filter_name=$filter_name}
    </div>
  </div>
  <div class="yui-b" style="text-align:right">
    {include file='contact_search.tpl'}
    	   <table class="but"  id="buts">
	     <tr><td id="add_contact">{t}Add Contact{/t}</td></tr>
	   </table>
    

    
    </div>
  </div>
</div> 
{include file='footer.tpl'}
{*}
<div id="add_contact_dialog">
  <div class="hd">{t}New Contact{/t}</div>
  <div class="bd">
    
    
    <div id="newcontact_preview">
      <div id="newcontact_data" >
	<ul id="clones">
	  <li id="show_name" style="display:none" >{t}New contact{/t}</li>
	  <li id="show_address" style="display:none" >{t}Addresses{/t}</li>
	  <li id="show_tel" style="display:none" >{t}Telephones{/t}</li>
	  <li id="show_email" style="display:none" >{t}Emails{/t}</li>
	  <li id="show_www" style="display:none" >{t}Web sites{/t}</li>

	</ul>
      </div>


      <iframe id="mapframe"  style="display:none" src="http://localhost/kaktus/map.php"></iframe>
    </div>


  <div id="newcontact_options">
    <div id="typeofcontact" class="yui-buttongroup" >
      <h3>{t}Chose type of contact{/t}</h3>
      <input id="contact_dialog" type="radio" name="contact_dialog" value="{t}Person{/t}" >
      <input id="company_dialog" type="radio" name="company_dialog" value="{t}Business{/t}" checked="ckecked"><br/>
    </div>
    <div id="addtocontact" style="text-align:left;border: 0px solid #ccc;width:70%;float:right;margin-top:0px;visibility:hidden">
      <button id="add_address">{t}Add Address{/t}</button>
	<button id="add_tel">{t}Add Telephone{/t}</button>
	<button id="add_email">{t}Add Email{/t}</button>
	<button id="add_haddress">{t}Add Address{/t}</button>
	<button id="add_www">{t}Add Web Site{/t}</button>
    </div>
  </div>
  
  
  
  <div id="newcontact_edit">
    
    <div id="newcontact" class="newitem">
      <h3>{t}Add a contact to this company{/t}</h3>
      <form id="form_contact">
	<label for="prefix"  >{t}Prefix{/t}  :</label>
	<select name="prefix"  id="prefix" >
	  {foreach from=$prefix item=pref key=pref_id }
	  <option value="{$pref_id}">{$pref}</option>
	  {/foreach}
	</select><br/>
	<label for="firstname"  >{t}First Name{/t}  :</label><input  id="firstname" class="text" type="text" value="" name="firstname"/><br/>
	<label for="lastname">{t}Last Name{/t}:</label><input id="lastname"  class="text" type="text" value="" name="lastname"/><br/>
	<label for="suffix">{t}Suffix{/t}:</label><input  id="suffix"  class="text" type="text" value="" name="suffix"/><br/>
      </form>
      <button id="skip_contact">{t}Not now{/t}</button>
      <button id="submit_contact">{t}Ok{/t}</button>
    </div>
    
    <div id="newtel" class="newitem"   >
      <h3>{t}Adding a telephone to the contact{/t}</h3>
      <form id="form_tel">
	<label for="tipo_tel"  >{t}Type of Number{/t}:</label>
	<select id="tipo_tel">
	  {foreach from=$tel_tipo item=ttipo key=ttipo_id}
	  <option value="{$ttipo_id}">{$ttipo}</option>
	  {/foreach}
	</select><br/>
	<br/>
    
    <span id="telarea"><label for="area"  >{t}Company Area{/t}:</label><input  id="area" class="text" type="text" value="" name="area"/><br/></span>
    
    <label for="code"  >{t}Country Code{/t}:</label><input  id="code" class="text" type="text" value="" name="code"/><br/>
    <label for="number"  >{t}Telephone{/t}:</label><input  id="number" class="text" type="text" value="" name="number"/><br/>
    <label for="ext"  >{t}Extention{/t}:</label><input  id="ext" class="text" type="text" value="" name="extention"/><br/>
  </form>
	 <button id="skip_tel">{t}Skip{/t}</button>
	 <button id="cancel_tel" style="display:none">{t}Cancel{/t}</button>
	 <button id="submit_tel">{t}Ok{/t}</button>
      </div>
       <div id="newemail" class="newitem"   >
	 <div id="select_tipoemail" >
	      <label for="tipoemail"  >{t}Type of Email{/t}:</label>
	      <select name="tipoemail" id="tipoemail">
		{foreach from=$email_tipo item=etipo key=etipo_id}
		<option value="{$ttipo_id}">{$etipo}</option>
		{/foreach}
	      </select>
	 </div>
	      
	 <form id="form_email">
	   <label for="emailcontact"  >{t}Contact{/t}:</label><input  id="emailcontact" class="text" type="text" value="" name="emailcontact"/><br/>
	   <label for="email"  >{t}Email{/t}:</label><input  id="email" class="text" type="text" value="" name="email"/><br/>
	 </form>
	 <button id="skip_email">{t}Skip{/t}</button>
	 <button id="cancel_email" style="display:none"  >{t}Cancel{/t}</button>
	 <button id="submit_email">{t}Ok{/t}</button>
       </div>

       <div id="newaddress" class="newitem"   >
	 <form id="form_address">
	   <label id="label_tipo_address"  for="tipo">{t}Type of Address{/t}:</label>
	   <select id="tipo_address"    >
	     {foreach from=$address_tipo item=atipo key=atipo_id}
	     <option value="{$atipo_id}">{$atipo}</option>
	     {/foreach}
	   </select>
	 <label for="country">{t}Country{/t}:</label><br/>
	 <select id="country"  >
	   {foreach from=$country item=country key=country_id}
	   <option   {if $country_id==$default_country_id  }selected{/if}     value="{$country_id}">{$country}</option>
	   {/foreach}
	 </select><br/>
	 <label for="postcode">{t}Postcode{/t}:</label><input class="text" type="text" value="" id="postcode" name="postcode"/><br/><br/>
	 <label for="a1"  >{t}Internal{/t}  :</label><input  class="text" type="text" value=""  id="a1" name="a1"/><br/>
	 <label for="a2"  >{t}Bulding{/t}  :</label><input  class="text" type="text" value="" id="a2" name="a2"/><br/>
	 <label for="a3"  >{t}Street{/t}  :</label><input  class="text" type="text" value=""  id="a3" name="a3"/><br/>
	 <label for="town"  >{t}Town/City{/t}  :</label><input  class="text" type="text" value=""  id="town" name="town"/><br/>
	 <label for="state">{t}County{/t}:</label><input class="text" type="text" value="" id="state" name="state"/><br/>
	  </form>
	 <button id="skip_address">{t}Skip{/t}</button>
	 <button id="cancel_address">{t}Cancel{/t}</button>
	 <button id="submit_address">{t}Ok{/t}</button>
       </div>
	    <div id="newwww" class="newitem"   >
	      <form id="form_www">
	      <label for="wwwname"  >{t}Website Name{/t}:</label><input  id="wwwname" class="text" type="text" value="" name="wwwname"/><br/>
	      <label for="www"  >{t}Address{/t}:</label><input  id="www" class="text" type="text" value="" name="www"/><br/>
	       </form>
	      <button id="cancel_www">{t}Cancel{/t}</button>
	      <button id="submit_www">{t}Ok{/t}</button>
	    </div>


         <div id="newcompany" class="newitem" style="display:block">
	   <h3>{t}Write the name of the company{/t}</h3>
	   <label for="name"  >{t}Company Name{/t}  :</label><input  id="companyname" class="text" type="text" value="" name="companyname"/><br/>

	   <button id="submit_company">{t}Ok{/t}</button>
	 </div>
    </div>
  <div id="newcontact_messages"></div>
    </div>
  <div id="submit_newcontact"  style="margin:0 0 0 250px;padding: 0 0 20px 0px;float:left">
    <button id="cancel_newcontact">{t}Cancel{/t}</button>
    <button id="save_newcontact"  >{t}Save{/t}</button>
    
    
  </div>
  

  </div>    

{/*}

<div id="add_contact_dialog">
  <div class="hd">{t}Chosse the type of contact{/t}</div> 
  <div class="bd"> 
      <table  class="but" style="margin:auto;border-spacing:10px  20px;font-size:130%">
	<tr><td id="newcompay">{t}Company{/t}</td><td id="newperson" >{t}Person{/t}</td></tr>
      </table>

  </div>
</div>

</div>
