
{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 0px">

<div style="clear:left;margin:0 0px">

  <div style="background-color:#f8d285;height:60px;">
  <div class="campaign_head">Add People</div>
  <table  style="margin-top:24px;" cellspacing="10" width="445">
  	<tr>
	<td><div class="topmenu"><a href="marketing.php">Emarketing</a></div></td>
	<td><div class="topmenu"><a href="marketing_campaign.php">Campaigns</a</div></td>
       <td><div class="topmenu current"><a href="marketing_list.php">Lists</a</div></td>
	<td><div class="topmenu"><a href="">Reports</a</div></td>
	<td><div class="topmenu"><a href="">Autoresponders</a</div></td>
	</tr>
 </table>

</div> 	
<div id="block_metrics" style="{if $view!='metrics'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="block_newsletter" style="{if $view!='newsletter'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="block_email" style="{if $view!='email'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


  		<span   class="clean_table_title" >{t}Email Campaigns{/t}</span>


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
	<div id="check_div2" style="display:none;"></div>
	
	

	

	{literal}
 	<script>
   	 $("#create_new_list").click(function () {
     	 $('#list_or_group').slideDown("slow",function(){
		 $('#group_div').fadeOut("slow",function(){
			 $('#list_details_div').fadeOut("slow");
	  });
	 });
    	 });
	 function slideCrm()
	 {
      	 $('#list_or_group').slideDown("slow");
		}
	</script>
	
	<script>
   	 $("#view_list").click(function () {
     	 $('#new_list').slideUp("slow",function(){
		$('#list_or_group').slideUp("slow",function(){
			$('#group_div').slideUp("slow",function(){
			  $('#list_details_div').slideDown("slow");
	});
	
	 });
	 });
    	 });
	 function slideCrm()
	 {
      	 $('#new_list').slideDown("slow");
		}
	</script>
	
	{/literal}
	

	
	<div class="add_people_list" style="float:left;"><fieldset class="field_set" style=" margin: 0em 0 1.2em 3em;"> <legend class="legend_part">Add People</legend>
		<form action="" method="post" name="list_form" id="list_form" onSubmit="return validate();">
		
	<div id="change_list">
			<select id="list_name" name="list_name" onChange=change_list();>
  			{foreach from=$list item=list_item}
  			<option value="{$list_item[0]}" {if $list_item[1]==$current_list}selected{/if}>{$list_item[1]}</option>
			{/foreach}
			</select>
			</div>
			<p style="padding-top:20px;"></p>

   		 	<h2>{t}Add People To List "{$current_list}"{/t}</h2>





		
	
		<div id="list_div" class="sub_head">First Name</div>
		<div id="name_div"><input type="text" name="people_first_name" id="people_first_name" class="av_text" style="width:670px;" onClick="show('people_first_name_msg');"></div>
		<div id="people_first_name_msg" class="invalid-error" style="display:none;width:671px;">Please enter first name.</div>  <br>

		<div id="default_name_div" class="sub_head">Last Name</div>
		<input type="text" name="people_last_name" id="people_last_name" class="av_text" style="width:670px;"  onClick="show('people_last_name_msg');">
		<div id="people_last_name_msg" class="invalid-error" style="display:none;width:671px;">Please enter last name.</div> <br><br>

		<div id="email_div" class="sub_head">Email Address</div>
		<input type="text" name="people_email" id="people_email" class="av_text" style="width:670px;"  onClick="show('email_msg');">
		<div id="email_msg" class="invalid-error" style="display:none;width:671px;">Please enter a valid email address.</div><br><br>
		{if $group_title != ''}
			<div class="sub_head" id="group_name" name="group_name" style="display:block;">{$group_title}</div>
			<div>{foreach from=$group item=group_item}		
			<input type="checkbox"name="group_name[]" id="group_name[]" value="{$group_item[0]}"> {$group_item[1]}<br>
			{/foreach}</div>
			{/if}
			<div class="sub_head">Email Type</div>
			<INPUT id="people_email_type1" TYPE="radio" NAME="people_email_type" VALUE="text">&nbsp;Text&nbsp;&nbsp;&nbsp;&nbsp;
			<INPUT id="people_email_type2" TYPE="radio" NAME="people_email_type" VALUE="html">&nbsp;HTML&nbsp;&nbsp;&nbsp;&nbsp;
			<INPUT id="people_email_type3" TYPE="radio" NAME="people_email_type" VALUE="mobile">&nbsp;Mobile
			<div id="people_email_type_msg" class="invalid-error" style="display:none;">Please select your email type.</div>  <br>
			<br>
			<br>
			<INPUT TYPE=CHECKBOX id="permission" NAME="permission">&nbsp;This recipient has given me permission to add him/her to my Managed List.
			<div id="permission_msg" class="invalid-error" style="display:none;">Please check above to agree with the terms &amp; conditions.</div>  <br>
			<br><br>
				
	
	
		
		
		<div class="bt" style=" float: left;">
  		<input type="submit" value="Subscribe" name="add_people" id="save_list" style="width:70px;margin-right:10px;" />
  		</div>
		
		<div style="padding-left:70px;">
		<div class="bt">
  		<input type="button" value="Cancel" name="cancel_list" id="cancel_list" onClick="document.location='marketing.php'; return false;"/>
  		</div>
		</div>
		</form></fieldset>
	</div>
	



	</div>


</div>
</div>

{include file='footer.tpl'}
