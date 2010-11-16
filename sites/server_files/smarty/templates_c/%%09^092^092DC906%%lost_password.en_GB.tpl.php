<?php /* Smarty version 2.6.22, created on 2010-10-22 08:59:13
         compiled from templates/lost_password.en_GB.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'mailto', 'templates/lost_password.en_GB.tpl', 12, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['head_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <body>
   <div id="container" >
     <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['home_header_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="page_content" >

     <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['left_menu_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
     <div id="central_content" style="min-height:600px">
       
       
       <div id="error" style="border:1px solid #ccc;margin:20px 40px;padding:20px;<?php if (! $this->_tpl_vars['error']): ?>display:none<?php endif; ?>" > 
	 <p>Oops, an internal error has ocurred. Try again please.</p><p>If the problem persists send us an email with all your details to <?php echo smarty_function_mailto(array('address' => $this->_tpl_vars['email'],'encode' => 'hex'), $this);?>

	   and we will take the required actions.</p>
       </div>
       
       <div  style="border:1px solid #ccc;margin:20px 40px;padding:20px;" > 
	 
	 <div id="get_email"  > 
	   <p id="email_instructions">Please enter your email address.</p>
	   <p id="email_error_msg_1" style="display:none">Hey, you forgot to write the email, <b>please write your email address</b>.</p>
	   <p id="email_not_valid_msg_1" style="display:none">The email seems incorrect, <b>please double check your email address</b>.</p>
	   <p id="email_not_valid_msg_2" style="display:none">That is not a email address, <b>please double check your email address</b>.</p>
	   <p id="email_not_confirmed" style="display:none">Please confirm the the email address</p>
	   <p id="email_error_confirmed" style="display:none">The emails are differents, make sure the both email fields are identical</p>
	   <p id="email_ok" style="display:none">Ok</p>
	   <table>
	     <tr ><td id="email_label">Email:</td><td><input id="email" style="width:240px" type="text"></td><td> <button id="submit_email">Submit</button></td></tr>

	  
	   </table>
	   
	 
	 <div id="no_registered" style="margin-top:15px;display:none"><p>We don't have this email registered in our system, you can register <a href="register.php"><strong>here</strong></a><p></div>
	 <div id="email_send" style="margin-top:15px;display:none"><p>An email has been send to you, please follow the instructions on it.</p><p class="verysmall">Remember that you can always call us if you have any trouble at <br/><?php echo $this->_tpl_vars['tel']; ?>
</p></div>
	 <div id="error" style="margin-top:15px;display:none"><p>An error has occurred.</p><p class="verysmall">Please try again, if you still has troubles call us at <br/><?php echo $this->_tpl_vars['tel']; ?>
</p></div>

	 </div>
	
	

	   <div id="get_optional_details" style="margin-top:20px;display:none" > 
	     <p>Give us you contact details (optional)</p>
	       
	       <table border=0>
		 <tr ><td id="telephone_label" >Telephone:</td><td><input id="telephone" check="no" type="text"></td></tr>
		 <tr ><td>Address:</td><td></td></tr>
		 
		 <tr class="first">
		   
		   <td class="label" style="width:160px">
		     <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
show_country_d1" onclick="toggle_country_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;display:none">+</span>
		     Country:</td>
		   <td  style="text-align:left">
		     <div id="<?php echo $this->_tpl_vars['address_identifier']; ?>
myAutoComplete" style="width:15em;position:relative;xtop:-10px" >
		       <input id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country" style="text-align:left;width:18em" type="text">
		       <div id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_container" style="position:relative;top:18px" ></div>
		       
		     </div>
		   </td>
		 </tr>
		 <input id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_code" value="" type="hidden">
		 <input id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_2acode" value="" type="hidden">
		 
  
		 <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_country_d1">
		   <td class="label" style="width:160px">
		     <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
show_country_d2" onclick="toggle_country_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;display:none">+</span> 
		     <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
label_address_country_d1">Region</span>:</td><td  style="text-align:left">
		     <input style="text-align:left;width:18em" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_d1" value="" ovalue="" ></td>
		 </tr>
		 <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_country_d2">
		   <td class="label" style="width:160px"><span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
label_address_country_d2">Subregion</span>:</td><td  style="text-align:left">
		     <input style="text-align:left;width:18em" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_d2" value="" ovalue="" ></td>
		 </tr>
  
		 <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_postal_code">
		   <td class="label" style="width:160px">Postal Code:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_postal_code" value="" ovalue=""  ></td>
		 </tr>
		 
		 <tr>
		   <td class="label" style="width:160px">
		     <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
show_town_d1" onclick="toggle_town_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">+</span> City:</td>
		   <td  style="text-align:left"><input style="text-align:left;width:18em" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_town" value="" ovalue="" ></td>
		 </tr>
		 <tr style="display:none" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_town_d1">
		   <td class="label" style="width:160px" >
		     <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
show_town_d2" onclick="toggle_town_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">x</span> City 1st Div:</td>
		   <td  style="text-align:left"><input style="text-align:left;width:18em" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_town_d1" value="" ovalue="" ></td>
		 </tr>
		 <tr style="display:none;" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_town_d2">
		   <td class="label" style="width:160px">City 2nd Div:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_town_d2" value="" ovalue="" ></td>
		 </tr>
		 <tr>
		   <td class="label" style="width:160px">Street/Number:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_street" value="" ovalue="" ></td>
		 <tr>
		   <td class="label" style="width:160px">Building:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_building" value="" ovalue="" ></td>
		 </tr>
		 <tr >
		   <td class="label" style="width:160px">Internal:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_internal" value="" ovalue="" ></td>
		 </tr>
		 
	 
	   
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
     
   
     <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['footer_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
     
   </div>
 </body>