
{include file='header.tpl'}

  
<div id="bd" >
	<div>
		{include file='marketing_navigation.tpl'}

		</head>

		<body>
		<div style="clear:left;margin:0 0px">
   		 <h1>{t}Add People to List{/t}</h1>
		</div>
		
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
		<li> <span class="item {if $view=='other'}selected{/if}"  id="other">  <span> {t}Emarketings{/t}</span></span></li>
		<li> <span class="item {if $view=='email'}selected{/if}"  id="email">  <span> {t}Campaign{/t}</span></span></li>
    		<li> <span class="item {if $view=='metrics'}selected{/if}" id="metrics"  ><span>  {t}Lists{/t}</span></span></li>
    		<li> <span class="item {if $view=='web'}selected{/if}"  id="web">  <span> {t}Report{/t}</span></span></li>
		<li> <span class="item {if $view==''}selected{/if}"  id="web">  <span> {t}Autoresponders{/t}</span></span></li>
    		
		</ul>
 		<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
	

	
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
	
	<div id="left_panel">
		<a onClick="" style="text-decoration:none; cursor:pointer"><div id="create_new_list">Create New List
		</div></a><br>
		<div id="view_list" style="text-decoration:none; cursor:pointer">View Lists
		</div><br>
		<div id="signup_forms" onMouseover="show('left_slide');">Design Sign up forms ▼
		</div><br>
		
		<input type="text" name="list_search" id="list_search" value="Search List Subscribers" onClick="empty_text();">
		<input type="submit" value="Go" class="list_search_button">

	</div>
	
	
	<div id="new_list">
		<form name="add_people" id="add_people" action="" method="post" onSubmit="">
			
   		 	<h2>{t}Add People To List{/t}</h2>
			
			<select id="list_name" name="list_name">
  			<option value="choose">Choose a list</option>
			{foreach from=$list item=list_item}
  			<option value="{$list_item[0]}">{$list_item[1]}</option>
			{/foreach}
 		
			</select> <br><br>
			<div class="sub_head">Email Address</div>
			<input type="text" name="people_email" id="people_email"  class="av_text" style="width:649px;"><br><br>
			<div class="sub_head">First Name</div>
			<input type="text" name="people_first_name" id="people_first_name" class="av_text" style="width:649px;"><br><br>
			<div class="sub_head">Last Name</div>
			<input type="text"name="people_last_name" id="people_last_name"  class="av_text" style="width:649px;"><br><br>
			<div class="sub_head">Email Type</div>
			<INPUT id="people_email_type" TYPE=RADIO NAME="people_email_type" VALUE="text">Text&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<INPUT id="people_email_type" TYPE=RADIO NAME="people_email_type" VALUE="html">HTML&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<INPUT id="people_email_type" TYPE=RADIO NAME="people_email_type" VALUE="mobile">Mobile
			<br>
			<br>
			<INPUT TYPE=CHECKBOX id="permission" NAME="permission">This recipient has given me permission to add him/her to my MailChimp Managed List.
			<br><br>
			<div class="bt" style=" float: left;">
  			<input type="submit" value="Subscribe" name="add_people" id="add_people" />
  			</div>
			<div style="padding:10px; float:left"></div>
			<div class="bt" style="float:left">
  			<input type="button" value="Cancel" name="add_member_cancel" id="add_member_cancel"/>
  			</div>
			<br><br>
		</form>
	</div>
	
	
 	
	<br>
	
</div>

{include file='footer.tpl'}


