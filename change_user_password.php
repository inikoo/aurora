<?php
	include('common.php');
		
	if(isset($_REQUEST['change_password']))
	{
		if(isset($_REQUEST['password']) && isset($_REQUEST['cpassword'])) 
		{
			if($_REQUEST['password'] == $_REQUEST['cpassword'])
			{
			
			$query = "update `User Dimension` set `User Password`= '".$_REQUEST['password']."'  where `User Key`='".$_SESSION['user_key']."'";
			mysql_query($query);
			$_SESSION['msg'] = 'Password Updated';
			}
			else
			{
			$_SESSION['msg'] = 'Password Mismatched';
			header('location:chage_user_password.php');
			exit();
			}
		}
	}
	
?>
<style type="text/css">
	.textbox{
	font-size:11px;
	color:#453333;
}
	.button{
	font-size:11px;
	color:$453333;
}
</style>
<form action="change_user_password.php" method="POST">
<table>
	
	<tr>
		<td colspan="2"><?php echo isset($_SESSION['msg'])?$_SESSION['msg']:''; ?></td>
	</tr>
	<tr>
		<td class="textbox">Enter New Password : </td> <td><input type="password" name="password"></td>
	</tr>
	<tr>
		<td class="textbox">Enter Again : </td> <td><input type="password" name="cpassword"></td>
	</tr>
	<tr>
	</tr>
	<tr>
		<?php
		if(isset($_REQUEST['change_password']))
		{
		?>
			<td colspan="2" class="button" align="center"><input type="button" name="close" onclick="javascript:window.close();" value="Close"></td>
		<?php
		}
		else
		{
		?>	
			<td colspan="2" class="button" align="center"><input type="submit" name="change_password" value="Save"></td>
		<?php
		}
		?>
	</tr>
</table>
</form>
