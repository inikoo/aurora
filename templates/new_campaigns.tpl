{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}

      <h2 style="clear:both">{t}Create Campaign{/t} <span style="padding-left:300px;">{$link}</span></h2>
<div style="border:1px solid #ccc;padding:50px;width:690px">
	<div id="campaign_div">{$msg}</div>
      <table border="0" width="700">
	<form action="create_campaign_data.php" method="post" name="campaign" id="campaign">
	<tr>
	  <td width="300"> Select list </td><td><b>:</b></td><td align="right"> 

		
		<select name="customer_list_key" id="customer_list_key" style="width:233px;">
			{section name="record" loop="$customer"}
				<option value="{$customer[record].$k}">{$customer[record].$n}</option>
			{/section} 
		</select>
		
	 </td>
	</tr>	
	<tr>
	  <td width="300"> Campaign Name   </td><td><b>:</b></td><td align="right"> <input type="text" name="campaign_name" id="campaign_name" size="30" value="{$campaign_name}"> </td>
	</tr>
	
	<tr>
	  <td> Campaign Objective  </td><td><b>:</b></td><td> <input type="text" name="campaign_obj" id="campaign_obj" size="30" value="{$campaign_obj}"> </td>
	</tr>

	<tr>
	  <td> Campaign Maximum Email </td><td><b>:</b></td><td> <input type="text" name="campaign_mail" id="campaign_mail" size="30" value="{$campaign_mail}"> </td>
	</tr>

	<tr>
	  <td>  Campaign Content </td> <td><b>:</b></td><td></td>
	</tr>

	<tr>
		<td colspan="3"><textarea name="campaign_content" id="campaign_content" class="ckeditor" cols="28">{$campaign_content}</textarea></td>
	</tr>

	<tr>
	  <td colspan=3 align="right"> <input type="button" name="createCampaign" value="Create" onclick="process();"> </td>
	</tr>
		
		<input type="hidden" name="max_num_mail" id="max_num_mail" value="{$count}"> 		
	</form>
      </table>

</div> 


</div>

{include file='footer.tpl'}
