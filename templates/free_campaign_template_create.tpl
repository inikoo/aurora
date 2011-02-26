{include file='header.tpl'}
<div id="bd" >


      <h2 style="clear:both">{t}Free Template{/t} </h2>
<div style="border:1px solid #ccc;padding:50px;width:690px">
	<div id="campaign_div">{$msg}</div>


      <table border="0" width="700">
	<form action="#" method="post" name="free_Template" id="free_Template" onsubmit="return validateForm();">
	

	<tr>
	  <td> Subject </td><td><b>:</b></td><td> <input type="text" name="f_template_sub" id="f_template_sub" size="30" value=""> </td>
	</tr>

	<tr>
	  <td>  Body </td> <td><b>:</b></td><td></td>
	</tr>

	<tr>
		<td colspan="3"><textarea name="f_template_body" id="f_template_body" class="ckeditor" cols="28"></textarea></td>
	</tr>

	<tr>
	  <td colspan=3 align="right"> <input type="submit" name="createCampaign" value="Create & Preview"> </td>
	</tr>
				
	</form>
      </table>

</div> 


</div>

{include file='footer.tpl'}
