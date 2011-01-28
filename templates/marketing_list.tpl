<html>
<head>


{include file='header.tpl'}



     



<div id="bd" >
	<div style="padding:0 20px">
		{include file='marketing_navigation.tpl'}

		</head>



		<body onload="hide();">
		<div style="clear:left;margin:0 0px">
   		 <h1>{t}List{/t}</h1>
		</div>

	</div>
	
 	

	<div id="block_metrics" style="{if $view!='metrics'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="block_newsletter" style="{if $view!='newsletter'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="block_email" style="{if $view!='email'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


  		<span   class="clean_table_title" style="">{t}Email Campaigns{/t}</span>


  		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
    
   
 		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0  }
		<div  id="table0"   class="data_table_container dtable btable"> </div>


	</div>
	<div id="block_web_internal" style="{if $view!='web_internal'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="block_web" style="{if $view!='web'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="block_other" style="{if $view!='other'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="check_div">Please Check Your Entry And Try Again</div>
	<div id="left_panel">
		<a href=""onClick="" style="text-decoration:none"><div id="create_new_list">Create New List
		</div></a><br>
		<div id="signup_forms">Design Sign up forms
		</div><br>
		<input type="text" name="list_search" id="list_search" value="Search List Subscribers" onClick="empty_text();">
		<input type="submit" value="Go" class="list_search_button">

	</div>



	<div id="list_or_group" style="border:1px solid #AAAAAA; margin-left: 220px; width: 670px; display:none;">AAAAAAAAAA<br>AAAAAAA</div>
	<div id="new_list">
		<form action="" method="post" name="list_form" onSubmit="validate_form();">

		<div id="list_div" class="sub_head">List Name</div>
		<div id="name_div"><input type="text" name="list_name" id="list_name" class="av_text" style="width:670px;" onClick="show('list_msg');"></div>
		<div id="list_msg" class="invalid-error">Please enter a value</div>  <br>

		<div id="default_name_div" class="sub_head">Default from Name</div>
		<input type="text" name="default_name" id="default_name" class="av_text" style="width:670px;"  onClick="show('default_name_msg');">  
		<div id="default_name_msg" class="invalid-error">Use something recognizable</div> <br><br>

		<div id="email_div" class="sub_head">Default reply-to email</div>
		<input type="text" name="default_email" id="default_email" class="av_text" style="width:670px;"  onClick="show('email_msg');">  
		<div id="email_msg" class="invalid-error">This is the address, people will reply to.</div><br><br>
	
		<div ="subject_div" class="sub_head">Default subject</div>
		<input type="text" name="default_subject" id="default_subject" class="av_text" style="width:670px;"  onClick="show('subject_msg');">  
		<div id="subject_msg" class="invalid-error">Keep it relevent and non-spammy</div> <br><br>
	
	
		<div id="remind_div" class="sub_head">Remind people how they got on your list *</div>
		<div id="permission_list" style="width:250px; float: left;" >Copy permission reminder from other list</div> 
		<select onClick="show('remind_msg')">
  		<option value="choose">Choose a list</option>
 		
		</select> <br><br>
	
	
		<TEXTAREA NAME="description" COLS=88 ROWS=3 onClick="hide();" style="-moz-border-radius: 5px 5px 5px 5px; padding-right:5px; padding-left:5px; "></TEXTAREA>
		<div id="remind_msg" class="invalid-error">You are receiving this email because you opted at our website....</div> <br><br>


		<div id="info_div" class="sub_head">Is this the correct contact info for this list? why is this necessary?</div>
		<div id="contact_div" style="border:1px solid #AAAAAA; padding:10px; -moz-border-radius: 5px 5px 5px 5px; padding-right:5px; padding-left:5px; ">asasss<br>
			<div class="bt" id="edit_div"><input type="button" value="Edit" name="edit" id="edit" onClick="edit_contact();"/></div>
				
		</div>
		<div id="edit_contact_div"  style="border:1px solid #AAAAAA; padding:10px;">
			<div class="sub_head">Company</div>
			<input type="text" name="company_name" id="company_name"  class="av_text" style="width:649px;"><br><br>
			<div class="sub_head">Address</div>
			<input type="text" name="address" id="address" class="av_text"  style="width:649px;"><br><br>
			<input type="text" name="address2" id="address2" class="av_text" style="width:649px;"><br><br>
			<div class="sub_head">City</div>
			<input type="text" name="city" id="city" class="av_text" style="width:649px;"><br><br>
			<div class="sub_head">State</div>
			<input type="text" name="state" id="state" class="av_text" style="width:649px;"><br><br>
			<div class="sub_head">Zip/Postal Code</div>
			<input type="text" name="pin" id="pin" class="av_text" style="width:649px;"><br><br>
			<div class="sub_head">Country</div>
			<select >
  				<option value="choose">Choose Country</option>
 			</select> <br><br>
			<div class="sub_head">Phone</div>
			<input type="text" name="phone" id="phone" class="av_text" style="width:649px;"><br><br>
		</div>

		<br><br>

		<div id="email_me_div" class="sub_head">Email me at ******@****.com <a onClick="edit_email();">edit</a> when ...</div>
		<div id="edit_email_div" class="sub_head">Email me at <input type="text" id="edit_email" name="edit_email"style="-moz-border-radius: 5px 5px 5px 5px;"> <a href="#">edit</a> when ...</div>
		<br>
		<hr>
		<INPUT TYPE=CHECKBOX NAME="mushrooms"   >People Subscribe
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<INPUT TYPE=CHECKBOX NAME="greenpeppers">People Unsubscribe <br><br><br>
		
		<div id="email_format_div" class="sub_head">Email Format</div>
		<hr>
		<INPUT TYPE=CHECKBOX NAME="mushrooms"   >People can pick Email format (HTML, plain-text, or Mobile)<br><br><br>

		<div id="list_data_div" class="sub_head">Auto-enhance list data</div>
		<hr>
		<INPUT TYPE=CHECKBOX NAME="mushrooms"   >Activate <a href="#">SocialPro</a><br><br>
		
		<div class="bt">
  		<input type="submit" value="Save" name="save_list" id="save_list" />
  		</div>
		</form>
	</div>
 	
	<br>
	
</div>

{include file='footer.tpl'}



</body>
</html>
