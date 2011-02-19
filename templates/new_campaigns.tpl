{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}

      <h2 style="clear:both">{t}Create Campaign{/t} (for the list {$listName})</h2>
<div style="border:1px solid #ccc;padding:50px;width:690px">
	<div id="campaign_div"></div>
      <table border="0" width="700">
	<form action="" method="post" form="campaign">
	<tr>
	  <td> Campaign Name  </td><td><b>:</b></td><td align="right"> <input type="text" name="campaign_name" id="campaign_name" size="30"> </td>
	</tr>
	
	<tr>
	  <td> Campaign Objective  </td><td><b>:</b></td><td> <input type="text" name="campaign_obj" id="campaign_obj" size="30"> </td>
	</tr>

	<tr>
	  <td> Campaign Maximum Email </td><td><b>:</b></td><td> <input type="text" name="campaign_mail" id="campaign_mail" size="30" onblur="maxEmailNumber({$count})"> </td>
	</tr>

	<tr>
	  <td>  Campaign Content </td> <td><b>:</b></td><td><textarea name="campaign_content" id="campaign_content" cols="28"></textarea></td>
	</tr>

	<tr>
	  <td colspan=3 align="right"> <input type="button" name="createCampaign" value="Create" onclick="getFormData()"> </td>
	</tr>
		<input type="hidden" name="customer_list_key" id="customer_list_key" value="{$customer_list_key}"> 
	</form>
      </table>

</div> 


</div>

{include file='footer.tpl'}
