{include  file="$head_template"}
 <body>
   <div id="container" >
     {include file="$home_header_template"}
     <div id="page_content" >

     {include file="$left_menu_template"}
     <div id="central_content" style="min-height:600px">
       <div id="no_logged_error" style="border:1px solid #ccc;margin:20px 40px;padding:20px;{if $logged_in}display:none{/if}" > 
	     <p>Invalid operation.</p><p>Remember that if you are trying to reset your password, the emailed link expires in 24 hours, if the is the case you can try again <a href="lost_password.php">here</a>.</p><p>If the problem persists send us an email with all your details to {mailto address=$email encode="hex"}
	   and we will take the required actions.</p>
       </div>
       <div id="error" style="border:1px solid #ccc;margin:20px 40px;padding:20px;display:none" > 
	     <p>Error</p>
       </div>
       <div id="password_changed" style="border:1px solid #ccc;margin:20px 40px;padding:20px;display:none" > 
	     <p>Your password had changed.</p>
       </div>
       
       {if $logged_in}
       <div id="change_password" style="border:1px solid #ccc;margin:20px 40px;padding:20px;" > 
	 
	  <div id="get_password" style="margin-top:0px;" > 
	   <p id="password_instructions">Hello {$user->get('User Alias')}<br>Please type your <strong>new</strong> password.<br/><span class="verysmall">(use at least 6 characters)</span></p>
	   <p id="password_msg1" style="display:none">Please confirm your password</p>
	   <p id="password_msg2" style="display:none">Please please be sure that both passwords are identical</p>
	   <p id="password_msg3" style="display:none">The passowrd should be at least 6 character long</p>
	   <p id="password_msg4" style="display:none">Please type your password</p>
		   
	   <table>

	     <tr><td id="password_label">Password:</td><td><input id="password" confirmed='no' type="password"></td></tr>
	     <tr><td id="password_confirmation_label">Confirm Password:</td><td><input id="password_confirmation" type="password"></td></tr>
	     <tr><td colspan="2" style="text-align:right" id="submit_password"><button>Submit</button></td></tr>

	   </table>
	 </div>
	 </div>
       {/if}
       </div>
     
     <div style="clear:both"></div>

      </div>
     
   
     {include file="$footer_template"}
     
   </div>
 </body>
