{include  file="$head_template"}
<body class="yui-skin-sam">
     <div id="central_content" style="min-height:600px">
       
       
       <div id="error" style="border:1px solid #ccc;margin:20px 40px;padding:20px;{if !$error}display:none{/if}" > 
	 <p>Oops, an internal error has ocurred. Try again please.</p><p>If the problem persists send us an email with all your details to {mailto address=$email encode="hex"}
	   and we will take the required actions.</p>
       </div>
       
       <div  style="border:1px solid #ccc;margin:20px 40px;padding:20px;" > 
	 
	 <div id="get_email" store_key="{$store_key}" > 
	   <p id="email_instructions">Please enter your email address.</p>
	   <p id="email_error_msg_1" style="display:none">Hey, you forgot to write the email, <b>please write your email address</b>.</p>
	   <p id="email_not_valid_msg_1" style="display:none">The email seems incorrect, <b>please double check your email address</b>.</p>
	   <p id="email_not_valid_msg_2" style="display:none">That is not a email address, <b>please double check your email address</b>.</p>
	   <p id="email_not_confirmed" style="display:none">Please confirm the the email address</p>
	   <p id="email_error_confirmed" style="display:none">The emails are differents, make sure the both email fields are identical</p>
	   <p id="email_ok" style="display:none">Ok</p>
	   <table>
	     <tr ><td id="email_label">Email:</td><td><input id="email" style="width:240px" type="text"></td><td></td></tr>
	     <tr ><td  id="email_confirmation_label">Confirm Email:</td><td><input id="email_confirmation" style="width:240px" confirmed="no" type="text"></td><td><button id="submit_email" class="button disabled">Continue</button></td></tr>
	  
	   </table>
	   
	 
	 </div>
	
	 <div id="found_email" style="margin-top:20px;display:none">
	   <p><span id="registered_email" class="strong"></span> are already registered in {$store->get('Store Name')}.</p>
	   <p>Type your password to login</p>
	   <table>

	      <tr><td id="password_login_label">Password:</td><td><input id="password_login"  type="password_login"></td></tr>
	      

	   </table>
	   
	   <div style="font-size:80%;width:300px;float:right;color:#777;margin-top:10px">
	     <p>
	       <h4>You don't remember your password?</h4>
	       Click <span id="password_reminder" class="link">here</span> and we will send you an email with instructions how to access your acount.
	     </p>

	      <p>
	       <h4>You can not access your email account?</h4>
	       Call us at {$tel}.
	     </p>

	   </div>

	   <div style="clear:right"></div>
	 </div>


	 <div id="get_password" style="margin-top:20px;display:none;" > 
	   <p id="password_instructions">Please type your password. (use at least 6 characters)</p>
	   <p id="password_msg1" style="display:none">Please confirm your password</p>
	   <p id="password_msg2" style="display:none">Please please be sure that both passwords are identical</p>
	   <p id="password_msg3" style="display:none">The passowrd should be at least 6 character long</p>
	   <p id="password_msg4" style="display:none">Please type your password</p>
		   
	   <table>

	     <tr><td id="password_label">Password:</td><td><input id="password" confirmed='no' type="password"></td></tr>
	     <tr><td id="password_confirmation_label">Confirm Password:</td><td><input id="password_confirmation" type="password"></td></tr>

	   </table>
	 </div>
	   
	 <div id="get_customer_type" style="margin-top:20px;display:none" > 
	   <p id="customer_type_instructions">Please tell us what type of trader you are.</p>
	   <p id="customer_type_not_confirmed_msg" style="display:none">Please confirm that you are a trader (check the appropiate box).</p>
	   <p id="customer_type_not_confirmed_msg2" style="display:none">Sorry, but if you do not confirm that your intention is to trade with the products we offer, you can not register.</p>
	   
	   <p id="customer_type_other_msg" style="display:none">Please specify what type of customer are you</p>
	   <p id="customer_type_ok_msg" style="display:none">Ok. press continue</p>
	   <p id="customer_type_other_msg" style="display:none">Fill the relevant fields.</p>
	   
	   <div id="customer_type_options">
	     <input id="customer_type_wholesaler" type="radio"  class="radio" name="customer_type" value="wholesaler" />Wholesaler
	     <br />
	     <input id="customer_type_big_shop" type="radio"  class="radio" name="customer_type" value="big_shop" />Big Retailer
	     <br />
	     <input id="customer_type_small_shop" type="radio"  class="radio" name="customer_type" value="small_shop" />Small Retailer
	     <br />
	     <input id="customer_type_internet" type="radio"  class="radio" name="customer_type" value="internet" />Internet Shop
	     <br />
	     <input id="customer_type_market" type="radio"  class="radio" name="customer_type" value="market" />Market Stand
	     <br />
	     <input id="customer_type_special" type="radio"  class="radio" name="customer_type" value="special" />I am organizang a wedding or other big event.
	     <br />
	     
	     <input id="customer_type_other" type="radio" name="customer_type" value="other" />Other 
	     <div id="customer_type_extra_info" style="display:none">
	       <span id="other_type_label">Describe type of trade:</span> <input  value="" id="other_type"/><br/>
	       <input class="error" type="checkbox" id="confirmation_trade_only" value="confirm_trader" /> <span id="confirmation_trade_only_msg">Please confirm that your intention is to trade with the products we offer.</span>
	     
	     </div>
	   </div>
	   
	   <div>
	   <p>{t}Where did you hear about us? (optional){/t}</p>
	   {html_options name=foo options=$hear_about_us selected=$selected_hear_about_us}
	   
	   </div>
	   
	   
	   <div class="continue">
	     <span class="button disabled" id="submit_customer_type">Continue</span>
	   </div>
	   
	   </div>
	   
	   
           <div id="get_details" style="margin-top:20px;" > 
	     
	     
	     <div id="company_or_person" style="display:none">
	       <p>Are you part of a company or a private person?</p>
	       <input type="radio" name="radiogroup" id="company">
	       <label for="radio-company">Company</label><br/>
	       <input type="radio" name="radiogroup" id="person">
	       <label for="radio-person">Private Person</label>
	     </div>
	     <p id="customer_details_instructions"></p>
	     <p id="customer_details_msg1" style="display:none">Please provide your company name</p>
	     <p id="customer_details_msg2" style="display:none">Please provide your name (e.g. Mr Jones)</p>
	     <p id="customer_details_msg3" style="display:none">Please fill the requered field</p>
	     
	       <table>
		 <tbody style="display:none" id="company_choosen">
		   <tr><td id="company_name_label">Company Name:</td><td><input id="company_name" type="text"></td></tr>

		   <tr><td>Tax/Registration Number:</td><td><input id="company_tax_number" type="text"></td></tr>
		   
		   <tr><td id="company_contact_label">Contact Name:</td><td><input id="company_contact" type="text"/></td></tr>
		   
		   
		 </tbody>
		 <tbody style="display:none" id="person_choosen">
		   
		   
		   <tr><td id="person_contact_label" >Contact Name:</td><td><input id="person_contact" type="text"/></td></tr>
		   
		   
		 </tbody>
		 
		 
		 
		 
	       </table>
	       
	       <div class="continue">
		 <span class="button disabled" id="submit_details" style="display:none">Continue</span>
	       </div>

	       
	   </div>
	     


	   <div id="get_optional_details" style="margin-top:20px;xdisplay:none" > 
	     <p>Give us you contact details (optional)</p>
	       
	       <table class="edit" border=0>
		 <tr ><td id="telephone_label" >Telephone:</td><td><input id="telephone" check="no" type="text"></td></tr>
		
		        {include file='../templates/edit_address_splinter.tpl' close_if_reset=true address_identifier='' address_function='Shipping'  hide_type=true hide_description=true show_form=true  hide_buttons=true}

		
		 
	 
	   
	       </table>
	     
	       
	  
	    
	     
	     <div style="margin-top:20px;margin-left:10px">
	     Receive our weekly Newsletter:
	     <input type="checkbox" id="newsletter" />
	     <br />
	     Receive Ofers and special promotions by email:
	     <input type="checkbox"id="emarketing"  />
	     <br />
	     Recevie a printed catalogue by post:
	     <input type="checkbox" id="catalogue" />
	     </div>
	 
	     <table style="margin-top:20px">
	       <tr>
		 <td style="width:200px;border:1px solid #ccc;padding:10px">
		   <p>Almost done!</p>
		   <p id="final_tel_error_msg" style="display:none">Please check the telephone number<p>
		   <p id="final_msg">Click the submit button, and shorly you will receive an email with the instructions to activate your acount</p>
		 </td>
		 <td>
		   <span class="button disabled" id="submit" style="margin-left:20px">Submit</span>
		 </td>
	     </table>
	     
	   


	 </div>
       </div>
       <div style="clear:both"></div>

      </div>
     
   </body>
   
