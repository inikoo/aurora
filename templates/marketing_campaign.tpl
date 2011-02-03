{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 0px">

<div style="clear:left;margin:0 0px">

  <div style="background-color:#f8d285;height:60px;">
  <div class="campaign_head">Campaigns</div>
  <table  style="margin-top:24px;" cellspacing="10" width="445">
  	<tr>
	<td><div class="topmenu"><a href="marketing.php">Emarketing</a></div></td>
	<td><div class="topmenu current"><a href="marketing_campaign.php">Campaigns</a</div></td>
       <td><div class="topmenu"><a href="marketing_list.php">Lists</a</div></td>
	<td><div class="topmenu"><a href="">Reports</a</div></td>
	<td><div class="topmenu"><a href="">Autoresponders</a</div></td>
	</tr>
 </table>

</div> 	
<div style="padding:10px 0px 0px 0px;">
	<form action="" method="post">
	<span style="padding-left:750px;"><select name="select_folder">
	<option value="0">Select</option>
	{section name="i" loop="$create"}
		
	<option value="folder_{$create[i].$edit_id}">{$create[i].$folder_name}</option>
	{/section}
	</select> &nbsp;<input type="submit" name="move" value="Move"> &nbsp; <input type="submit" name="delete" value="Delete"></span>
	
<table height="520" border="0" height="100%">
<tr>
 <td style="background-color:#d3dbe8">
<div class="campaign_create"><a id="create_camp" href="marketing_create_campaign.php">Create Campaign<span class="dwn">▼</span></a><div>

<a style="margin: 5px 0pt;" title="create an inbox inspection test of your email" class="button p3" href="#">inbox inspection</a>

<div class="campaign_type">
 <img src="art/left_camp.png">
 <a href =""> all campaigns </a><br>
<img src="art/left_camp.png">
 <a href =""> drafts </a><br>
<img src="art/left_camp.png">
 <a href ="">scheduled campaigns </a><br>
<img src="art/left_camp.png">
 <a href ="">unfiled </a><br>

</div>
<br>
<span style="padding-left:20px;"><img src="art/icons/folder_add.png" / ><a href="#" name="newFolder" id="newFolder" onClick="showFolder()" style="text-decoration:none;">&nbsp; Create Folder</a></span>
<div id="folder">
	{section name="j" loop="$create"}
	  <span style="padding-left:20px;" id="folder_{$create[j].$edit_id}">
	  <img src="art/icons/folder_add.png" / > <a href="marketing_campaign.php?fid=folder_{$create[j].$edit_id}">{$create[j].$folder_name}</a>
	  </span>
<div style="float:right; padding-right:30px;">
<img src="art/icons/edit.ico" height="9"  class="click" id="edit_{$create[j].$edit_id}" onClick="edit('edit_{$create[j].$edit_id}','folder_{$create[j].$edit_id}','{$create[j].$folder_name}')" />
<img src="art/icons/delete.ico" id="del_{$create[j].$edit_id}" onClick="del('del_{$create[j].$edit_id}')" />
</div>
<br>
	{/section}
</div>

</td> 
<td>
  <table width="730" border="0">
	  <tr bgcolor="#7080B1">
	   
	   <td class="display_campaign1">Serial</td>
	   <td class="display_campaign2">Type</td>
	   <td class="display_campaign3">Status</td>
	   <td class="display_campaign4">List </td>
	   <td class="display_campaign5">Emails</td>
	   <td class="display_campaign6">Content</td>
		
	</tr>
	
		{section name=value loop=$value}
		<tr>
		
		<td align="center"><input type="checkbox" name="chkbox[]" value="{$value[value].$key}"> {$smarty.section.value.index+1}</td>
		<td>TYPE</td>
		<td align="center">{$value[value].$status}</td>
		<td>LIST</td>
		<td align="center">{$value[value].$email}</td>
		<td>{$value[value].$content}</td>
		
		</tr>
		{/section}
     
  </table>



</td>


</tr>


	</table>
	</form>
</div>


		</div>

	</div>


</div>
</div>

{include file='footer.tpl'}
