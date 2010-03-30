{include file='head.tpl'}
 <body>
   <div id="container" >
     {include file='home_header.tpl'}
     {include file='left_menu.tpl'}
     <div id="central_content">
       
       <div style="border:1px solid #ccc;margin:20px 40px;padding:20px;" > 
	 <p>Please write your email address.</p>
	 <table>
	   <tr id="email_tr"><td>Email:</td><td><input id="email" type="text"></td></tr>
	 
	   
	   
	 </table>
	 <div class="continue"><span class="button disabled">Continue</span></div>
	 
       </div>


       <div style="border:1px solid #ccc;margin:20px 40px;padding:20px;" > 
	 <p>Please tell us what type of trader you are.</p>
	 <input type="radio" name="customer_type" value="wholesaler" />Wholesaler
	 <br />
	 <input type="radio" name="customer_type" value="big_shop" />Big Retailer
	 <br />
	 <input type="radio" name="customer_type" value="small_shop" />Small Retailer
	 <br />
	 <input type="radio" name="customer_type" value="internet" />Internet Shop
	 <br />
	 <input type="radio" name="customer_type" value="market" />Market Stand
	 <br />
	 <input type="radio" name="customer_type" value="special" />I am organizang a wedding or other big event.
	 <br />
	 
	 <input type="radio" name="sex" value="other" />Other 
	<div id="customer_type_extra_info" style="display:none">
	  Describe type of trade: <input value="" id="other_type"/><br/>
	 <input type="checkbox" name="vehicle" value="confirm_trader" /> Prease confirm that your intention is to trade with the products we offer.
	 </div>
	<div class="continue">
	  <span class="button disabled">Continue</span>
	</div>
       </div>

        <div style="border:1px solid #ccc;margin:20px 40px;padding:20px;" > 
	 <p>Are you part of a company or a private person?</p>
	 <input type="radio" name="radiogroup" id="radio-company">
	 <label for="radio-company">Company</label><br/>
	 <input type="radio" name="radiogroup" id="radio-person">
	 <label for="radio-person">Private Person</label>
	
	 <div id="company_choosen">
	   <table>
	     <tbody style="display:none">
	      <tr><td>Company Name:</td><td><input id="company_name" type="text"></td></tr>
	      <tr><td>Company Registation Number:</td><td><input id="company_registration_number" type="text"></td></tr>
	      <tr><td>Company Tax Number:</td><td><input id="company_tax_number" type="text"></td></tr>
	      
	      <tr><td>Contact Name:</td><td><input id="company_contact" type="text"></td></tr>


	     </tbody>
	      <tbody style="display:none">
	     
	      
	      <tr><td>Contact Name:</td><td><input id="person_contact" type="text"></td></tr>


	     </tbody>
	      

	      <tr><td colspan=2 style="padding-top:15px;text-align:right"><span class="button">Continue</span></td></tr>
	   
	 </table>
	   
	 </div>
	</div>


  <div style="border:1px solid #ccc;margin:20px 40px;padding:20px;" > 
	 <p>Give us you contact details</p>
	 
	  <table>
	   <tr ><td>Telephone:</td><td><input id="telephone" type="text"></td></tr>
	   <tr ><td>Address:</td><td></tr>
	   
  <tr class="first">
    
    <td class="label" style="width:160px">
      <span id="{$address_identifier}show_country_d1" onclick="toggle_country_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;display:none">+</span>
      Country:</td>
    <td  style="text-align:left">
      <div id="{$address_identifier}myAutoComplete" style="width:15em;position:relative;top:-10px" >
	<input id="{$address_identifier}address_country" style="text-align:left;width:18em" type="text">
	<div id="{$address_identifier}address_country_container" style="position:relative;top:18px" ></div>
	
      </div>
    </td>
	  </tr>
  <input id="{$address_identifier}address_country_code" value="" type="hidden">
  <input id="{$address_identifier}address_country_2acode" value="" type="hidden">
  
  
  <tr id="{$address_identifier}tr_address_country_d1">
	    <td class="label" style="width:160px">
	      <span id="{$address_identifier}show_country_d2" onclick="toggle_country_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;display:none">+</span> 
	      <span id="{$address_identifier}label_address_country_d1">{t}Region{/t}</span>:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_country_d1" value="" ovalue="" ></td>
  </tr>
  <tr id="{$address_identifier}tr_address_country_d2">
    <td class="label" style="width:160px"><span id="{$address_identifier}label_address_country_d2">{t}Subregion{/t}</span>:</td><td  style="text-align:left">
	    <input style="text-align:left;width:18em" id="{$address_identifier}address_country_d2" value="" ovalue="" ></td>
  </tr>
  
  <tr id="{$address_identifier}tr_address_postal_code">
    <td class="label" style="width:160px">{t}Postal Code{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_postal_code" value="" ovalue=""  ></td>
  </tr>
  
  <tr>
    <td class="label" style="width:160px">
      <span id="{$address_identifier}show_town_d1" onclick="toggle_town_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">+</span> {t}City{/t}:</td>
    <td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_town" value="" ovalue="" ></td>
  </tr>
  <tr style="display:none" id="{$address_identifier}tr_address_town_d1">
    <td class="label" style="width:160px" >
	      <span id="{$address_identifier}show_town_d2" onclick="toggle_town_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">x</span> {t}City 1st Div{/t}:</td>
    <td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_town_d1" value="" ovalue="" ></td>
  </tr>
  <tr style="display:none;" id="{$address_identifier}tr_address_town_d2">
    <td class="label" style="width:160px">{t}City 2nd Div{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_town_d2" value="" ovalue="" ></td>
  </tr>
  <tr>
    <td class="label" style="width:160px">{t}Street/Number{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_street" value="" ovalue="" ></td>
  <tr>
    <td class="label" style="width:160px">{t}Building{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_building" value="" ovalue="" ></td>
  </tr>
  <tr >
    <td class="label" style="width:160px">{t}Internal{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_internal" value="" ovalue="" ></td>
  </tr>
	 
	   <tr><td colspan=2 style="padding-top:15px;text-align:right"><span class="button">Continue</span></td></tr>
	   
	 </table>
	 


	




	</div>










	  <div style="border:1px solid #ccc;margin:20px 40px;padding:20px;" > 
	 <p>Nearly done. Please choose if you want some of the something</p>


	 Receive a weekly Newsletter:
	 <input type="checkbox" name="vehicle" value="Bike" />
	 <br />
	 Receive Ofers and special promotions by email:
	 <input type="checkbox" name="vehicle" value="Car" />
	 <br />
	 Recevie a printed catalogue by post:
	 <input type="checkbox" name="vehicle" value="Airplane" />


	




	</div>


     </div>
    
     
     
     
   
     {include file='footer.tpl'}
     
   </div>
 </body>
