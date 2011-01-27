{include file='header.tpl'}
<div id="bd" >
 
 <div style="clear:left;">
  <h1>{t}File Transfer Via FTP{/t}</h1>
</div>



<div class="data_table" style="clear:both">
  
<html>
<head>
<title>ftp uploading class</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form action="ftp_upload.php" method="post" enctype="multipart/form-data" name="form1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="14%">server name </td>
<td width="75%"><input name="server" type="text" id="server"> <font size="1px">Write in the format "ftp.servername.com" </font></td>
<td width="11%">&nbsp;</td>
</tr>
<tr>
<td>user name </td>
<td><input name="username" type="text" id="username"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>password</td>
<td><input name="password" type="password" id="password"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>File name </td>
<td><input type="file" name="file"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td><input type="submit" name="Submit" value="Upload"></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table> 
</form>
</body>
</html>


 
</div>


<div class="data_table" style="clear:both" align="center"><h3>{$confirm1}<br><br>{$confirm2}</h3></div>
  
</div> 
{include file='footer.tpl'}

