
{include file='header.tpl'}

  
<div id="bd" >
	<div>
		
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
		<form name="remove_people" id="remove_people" action="" method="post" onSubmit="">
			<div id="change_list">
			
   		 	<h2>{t}Remove People From List "{$current_list}"{/t}</h2>
			
			
			<div class="sub_head">Email Address</div>
			<p>email addresses (one per line)</p>
			<textarea id="email_addresses" name="email_addresses" rows="20" cols="80"></textarea>

			
			<br><br>
			<div class="bt" style=" float: left;">
  			<input type="submit" value="Unsubscribe" name="remove_people" id="remove_people" />
  			</div>
			<div style="padding:10px; float:left"></div>
			<div class="bt" style="float:left">
  			<input type="button" value="Cancel" name="remove_people_cancel" id="remove_people_cancel"/>
  			</div>
			<br><br>
		</form>
	</div>
	
	
 	
	<br>
	
</div>

{include file='footer.tpl'}


