<?php
	include_once('common.php');

	$folder_name = $_GET['q'];
	
	$sql = "insert into `Mail Folder`(`Mail Folder Name`,`Mail Folder Email`)values('$folder_name','carlos@aw-regalos.com')";
	$rr = mysql_query($sql);
	//$id = mysql_insert_id();
	
	$query = "SELECT `Mail Folder Name`,`Mail Folder Key` from `Mail Folder`";
	$result = mysql_query($query);
	while($row=mysql_fetch_assoc($result))
	{	

		$send = $row['Mail Folder Key'];
		$edit = 'edit_'.$send;
		$del = 'del_'.$send;
		$folder = 'folder_'.$send;
		
?>
	<span style="padding-left:20px;" id="<?php echo $folder; ?>">
	<img src="art/icons/folder_add.png" / ><a href="marketing_campaign.php?fid=<?php echo $folder; ?>"><?php echo $row['Mail Folder Name']; ?>
	</span>
	<div style="float:right; padding-right:30px;">
	<img src="art/icons/edit.ico"  id="<?php echo $edit; ?>"  height="9"  onClick="edit('<?php echo $edit; ?>','<?php echo $folder; ?>','<?php echo $row['Mail Folder Name']; ?>')"/>&nbsp;
	<img src="art/icons/delete.ico" id="<?php echo $del; ?>"   onClick="del('<?php echo $del; ?>')"  />
	</div>
	<br>

<?php
	}
?>
