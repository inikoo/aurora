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
     <button id="add_contact">{t}Add Contact{/t}</button><br/><br/>
    
    </div>
  </div>
</div> 
{include file='footer.tpl'}

<div id="add_contact_dialog">
 <div class="hd">{t}New personal contact{/t}</div>
<div class="addcontactpreview" id="addcontactpreview"        >
<div id="show_name"></div>
<div id="show_address"></div>
<div id="show_tel"></div>
<div id="show_email"></div>
<div id="show_www"></div>


</div>
<div id="tab_container" class="bd yui-navset addcontactnavset" >
    <ul class="yui-nav">
        <li   class="selected"><a href="#tab1"><em>{t}Contact{/t}</em></a></li>
        <li class="selectedd"><a href="#tab2"><em>{t}Address{/t}</em></a></li>
        <li  class="selectedd"  ><a href="#tab3"><em>{t}Telephone{/t}</em></a></li>
	<li><a href="#tab4"><em>{t}Email{/t}</em></a></li>
	<li><a href="#tab5"><em>{t}www{/t}</em></a></li>
	<li   class="selectedx" ><a href="#tab6"><em>{t}Notes{/t}</em></a></li>
	<li    ><a href="#tab7"><em>{t}Picture{/t}</em></a></li>

    </ul>            
    <div class="yui-content" >
        <div id="tab1" class="edit_tab"   >
	  
	  
	  <div class="edit_section">
	    
	    <div id="typeofcontact" class="yui-buttongroup" >
	      <input id="contact_dialog" type="radio" name="contact_dialog" value="Person" >
		<input id="company_dialog" type="radio" name="company_dialog" value="Bussines" checked>
	    </div>
	    
	    <div id="newcontact" class="newitem" style="display:none">
	      <h3>Write the name of the contact</h3>
	      <label for="prefix"  >{t}Prefix{/t}  :</label>
	      <select name="prefix"  id="prefix" >
		{foreach from=$prefix item=pref key=pref_id }
		<option value="{$pref_id}">{$pref}</option>
		{/foreach}
	      </select><br/>
	      <label for="name"  >{t}First Name{/t}  :</label><input  id="firstname" class="text" type="text" value="" name="name"/><br/>
	      <label for="surname">{t}Last Name{/t}:</label><input id="secondname"  class="text" type="text" value="" name="surname"/><br/>
	      <label for="suffix">{t}Suffix{/t}:</label><input  id="suffix"  class="text" type="text" value="" name="suffix"/><br/>
	      <button id="submit_contact">{t}Submit{/t}</button>
	    </div>
	    <div id="newcompany" class="newitem">
	      <h3>Write the name of the company</h3>
	      <label for="name"  >{t}Company Name{/t}  :</label><input  id="companyname" class="text" type="text" value="" name="companyname"/><br/>
	      <button id="submit_company">{t}Submit{/t}</button>
	    </div>
	    
	  </div>
	</div>
        <div id="tab2"  class="edit_tab" >
	  <div class="edit_section">
	    <button id="add_address">{t}Add Address{/t}</button>
	    <div id="newaddress" class="newitem"   >
	      <label for="tipo">{t}Type of Address{/t}:</label>
	      <select>
		{foreach from=$address_tipo item=atipo key=atipo_id}
		<option value="{$atipo_id}">{$atipo}</option>
		{/foreach}
	      </select><br/>

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
	      <button id="submit_address">{t}Submit{/t}</button>
	      </div>
	  </div>

	  <iframe id="mapframe" style="float:left;width:500px;border:none;"
	     width="100px" height="100%">
	  </iframe>

	  <div style="clear:both"></div>
	</div>
	<div id="tab3"  class="edit_tab">
	  <div class="edit_section"  >
	    <button id="add_tel">{t}Add Telephone{/t}</button>
	    <div id="newtel" class="newitem"   >
	      <label for="tipo_tel"  >{t}Type of Number{/t}:</label>
	      <select id="tipo_tel">
		{foreach from=$tel_tipo item=ttipo key=ttipo_id}
		<option value="{$ttipo_id}">{$ttipo}</option>
		{/foreach}
	      </select><br/>
	      <br/>
	      <label for="code"  >{t}Country Code{/t}:</label><input  id="code" class="text" type="text" value="" name="code"/><br/>
	      <label for="number"  >{t}Telephone Number{/t}:</label><input  id="number" class="text" type="text" value="" name="number"/><br/>
	      <label for="ext"  >{t}Extention{/t}:</label><input  id="ext" class="text" type="text" value="" name="extention"/><br/>
	      <button id="submit_tel">{t}Submit{/t}</button>
	    </div>
	  </div>
	  <table style="display:none">
	    <thead>
              <tr>
                <th>{t}Type of Number{/t}</th>
                <th>{t}Number{/t}</th>
              </tr>
            </thead>
	    <tbody>
	    </tbody>
	  </table>
	  <div style="clear:both"></div>
	</div>
	<div id="tab4"  class="edit_tab">
	  <div class="edit_section"  >
	    <button id="add_email">{t}Add Email{/t}</button>
	    <div id="newemail" class="newitem"   >
	      <label for="tipoemail"  >{t}Type of Number{/t}:</label>
	      <select name="tipoemail" id="tipoemail">
		{foreach from=$email_tipo item=etipo key=etipo_id}
		<option value="{$ttipo_id}">{$etipo}</option>
		{/foreach}
	      </select><br/>
	      <br/>
	      <label for="emailcontact"  >{t}Contact{/t}:</label><input  id="emailcontact" class="text" type="text" value="" name="emailcontact"/><br/>
	      <label for="email"  >{t}Email{/t}:</label><input  id="email" class="text" type="text" value="" name="email"/><br/>
	      <button id="submit_email">{t}Submit{/t}</button>
	    </div>
	  </div>
	  <table style="display:none">
	    <thead>
              <tr>
                <th>{t}Contact{/t}</th>
                <th>{t}Email{/t}</th>
              </tr>
            </thead>
	    <tbody>
	    </tbody>
	  </table>
	  <div style="clear:both"></div>
	</div>
	<div id="tab5"  class="edit_tab">
	  <div class="edit_section"  >
	    <button id="add_www">{t}Add Web Site{/t}</button>
	    <div id="newwww" class="newitem"   >
	      <br/>
	      <label for="wwwname"  >{t}Website Name{/t}:</label><input  id="wwwname" class="text" type="text" value="" name="wwwname"/><br/>
	      <label for="www"  >{t}Address{/t}:</label><input  id="www" class="text" type="text" value="" name="www"/><br/>
	      <button id="submit_www">{t}Submit{/t}</button>
	    </div>
	  </div>
	  <table style="display:none">
	    <thead>
              <tr>
                <th>{t}Type of Web Site{/t}</th>
                <th>{t}Address{/t}</th>
              </tr>
            </thead>
	    <tbody>
	    </tbody>
	  </table>
	  <div style="clear:both"></div>
	</div>
	<div id="tab6"  class="edit_tab">

	    <form method="post" action="#" id="form1">
	      <textarea id="noteeditor" name="noteeditor" rows="10" cols="40"></textarea>
	    </form>
	    
	    

	    
	  </div>
    </div>
</div>
</div>
