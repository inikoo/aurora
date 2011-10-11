
{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 0px">

<div style="clear:left;margin:0 0px">

  <div style="background-color:#f8d285;height:60px;">
  <div class="campaign_head">Remove People</div>
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
   	 function validate_form()
         {
	   if(document.getElementById("email_addresses").value=="")
	{
		document.getElementById('list_msg').style.display = 'block';
		return false;
	}

	 }

	</script>
	
	{/literal}
	

	
	<div class="add_people_list" style="float:left;"><fieldset class="field_set" style=" margin: 0em 0 1.2em 3em;width:610px;"> 
	<legend class="legend_part">Remove People</legend>
	<form name="remove_people" id="remove_people" action="" method="post" onSubmit="return validate_form();">
			<div id="change_list" style="padding-left:10px;">
			
   		 	<h2>{t}Remove People From List "{$current_list}"{/t}</h2>
			
			
			<div class="sub_head">Email Address</div>
			<p>email addresses (one per line)</p>
			<textarea id="email_addresses" name="email_addresses" rows="20" cols="80"></textarea>
			<div id="list_msg" class="invalid-error" style="display:none;width:580px;">Please enter Email Address</div>  <br>
			
			<br>
			<div class="bt" style=" float: left;width:85px;">
  			<input type="submit" value="Unsubscribe" name="remove_people" id="remove_people" style="width:88px;" />
  			</div>
			<div style="padding:10px; float:left"></div>
			<div class="bt" style="float:left">
  			<input type="button" value="Cancel" name="remove_people_cancel" id="remove_people_cancel" onClick="document.location='marketing.php'; return false;"/>
  			</div>
			<br><br>
		</form>	



	</fieldset>
	</div>
	



	</div>


</div>
</div>

{include file='footer.tpl'}
