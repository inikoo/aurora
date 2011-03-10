{include file='header.tpl'}
<div id="bd" >

<div class="general_options"></div>


<h2>Create Post   <span style="padding-left:200px;">{$queue}</span>  </h2> 



<br><br><br>
 <form action="create_mail_data.php" method="post">
	<table border="0" align="center" width="700px">
		{*<tr>
			<td>Select Post Type : </td> <td>
							<select name="post_type" style="width:150px;"> 
								<option value="Catalogue">Catalogue</option> 
								<option value="Advert">Advert</option>
								<option value="Letter">Letter</option>
							</select></td>
		</tr>*}

		<tr>
			<td colspan="2">Enter The Content : </td> 
				
		</tr>

		<tr>

			<td colspan="2"><textarea name="content" class="ckeditor" rows="30" style="width:500px;">
</textarea></td>
		</tr>

		<tr>
			<td> <input type="submit" name="submit" value="Send"> </td>
		</tr>
	</table>
 </form>

</div>

  
  {include file='footer.tpl'}
