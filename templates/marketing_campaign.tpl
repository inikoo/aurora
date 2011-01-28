{include file='header.tpl'}

<div id="bd"  style="padding:0px">
<div style="padding:0 0px">

<div style="clear:left;margin:0 0px">

  <div style="background-color:#7080b1;height:60px;">
  <div class="campaign_head">Campaigns</div>
  <table  style="margin-top:24px;" cellspacing="10" width="445">
  	<tr>
	<td><div class="topmenu"><a href="">Emarketing</a></div></td>
	<td><div class="topmenu current"><a href="">Campaigns</a</div></td>
       <td><div class="topmenu"><a href="">Lists</a</div></td>
	<td><div class="topmenu"><a href="">Reports</a</div></td>
	<td><div class="topmenu"><a href="">Autoresponders</a</div></td>
	</tr>
 </table>

</div> 	
<div style="padding:10px 0px 0px 0px;"><span style="padding-left:750px;"><select name="folder"><option value="">New Folder</option></select> &nbsp;<input type="submit" name="move" value="Move"> &nbsp; <input type="submit" name="delete" value="Delete"></span>
<table height="520" border="1">
<tr>
 <td style="background-color:#d3dbe8">
<div class="campaign_create"><a id="create_camp" href="">Create Campaign<span class="dwn">▼</span></a><div>

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
<form action="" method="POST"><span style="padding-left:20px;"><img src="art/icons/folder_add.png" / ><a href="#" name="newFolder" id="newFolder" onClick="showFolder()" style="text-decoration:none;">&nbsp; Create Folder</a></span>
<div id="folder">
	{section name="i" loop="$create"}
	  <span style="padding-left:20px;"><img src="art/icons/folder_add.png" / >&nbsp; {$create[i].$folder_name}</span>&nbsp;&nbsp;<img src="art/icons/edit.ico" height="9" onClick="edit()" />&nbsp;<img src="art/icons/delete.ico" onClick="delete()" /><br>
	{/section}
</div></form>
</td> 
<td>
  <table width="730">
	  <tr>
	   
	   <td class="display_campaign1">Serial</td>
	   <td class="display_campaign2">Type</td>
	   <td class="display_campaign3">Status</td>
	   <td class="display_campaign4">List </td>
	   <td class="display_campaign5">Emails</td>
	   <td class="display_campaign6">Content</td>
		
	</tr>
	
		{section name=value loop=$value}
		<tr>
		<td align="center" width="50"><input type="checkbox" name="chkbox" value=""> &nbsp; {$value[value].$key}</td>
		<td>TYPE</td>
		<td align="center" width="50">{$value[value].$status}</td>
		<td>LIST</td>
		<td align="center" width="50">{$value[value].$email}</td>
		<td>{$value[value].$content}</td>
		</tr>
		{/section}
     
  </table>



</td>


</tr>


</table>

</div>


















    


	
		</div>

	</div>


</div>
</div>

{include file='footer.tpl'}

<div id="rppmenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
