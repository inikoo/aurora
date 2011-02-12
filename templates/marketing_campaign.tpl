{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 0px">

<div style="clear:left;margin:0 0px">

  <div style="background-color:#E6DDD5;height:60px;">
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
	<span style="padding-left:700px;">
       
       Move to : <select name="select_folder" id="select_folder">
	<option value="0">Select</option>
	{section name="i" loop="$create"}
		
	<option value="folder_{$create[i].$edit_id}">{$create[i].$folder_name}</option>
	{/section}
	</select> &nbsp;<input type="submit" name="move" value="Move"> &nbsp; <input type="button" name="delete" value="Delete" onclick="delFolder()"></span>
	
<table height="520" border="0" height="100%">
<tr>
 <td style="background-color:#d3dbe8">
<div class="campaign_create"><ul class="hover-list"><li><a id="create_camp" href="">Create Campaign<span class="dwn">▼</span></a>
 <ul>
       <li>
<a href="regular_campaign.php">regular ol' campaign</a></li>  
<li><a href="">A/B split campaign</a></li> 
 </ul> </li> </ul> 

<div>



<div class="campaign_type">
 <img src="art/left_camp_f.png">
 <a href =""> All campaigns </a><br><br>
<img src="art/left_camp_f.png">
 <a href =""> Drafts </a><br><br>
<img src="art/left_camp_f.png">
 <a href ="">Scheduled campaigns </a><br><br>
<img src="art/left_camp_f.png">
 <a href ="">Unfiled </a><br><br>

</div>
<br>
<div class="campaign_type2"><br>
<span style="padding-left:20px;padding-bottom:10px;"><a href="#" name="newFolder" id="newFolder" onClick="showFolder()" style="text-decoration:none;color:#000000;font-size:16px;">&nbsp; Create Folder</a></span>
<div id="folder" style="height: 148px;
   overflow: auto;
   ">
	{section name="j" loop="$create"}
	  <span style="padding-left:20px;" id="folder_{$create[j].$edit_id}">
	  <img src="art/icons/folder_add.png" / > <a style="color:#000000;" href="marketing_campaign.php?fid=folder_{$create[j].$edit_id}">{$create[j].$folder_name}</a>
	  </span>
<div style="float:right; padding-right:30px;">
<img src="art/icons/edit.ico" height="9"  class="click" id="edit_{$create[j].$edit_id}" onClick="edit('edit_{$create[j].$edit_id}','folder_{$create[j].$edit_id}','{$create[j].$folder_name}')" />
<img src="art/icons/delete.ico" id="del_{$create[j].$edit_id}" onClick="del('del_{$create[j].$edit_id}')" />
</div>
<br>
	{/section}
</div><br></div><br>

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
		{sectionelse}
		<tr><td colspan="6">{$null_result}</td></tr>
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
