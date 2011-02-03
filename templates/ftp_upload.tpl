{include file='header.tpl'}
<div id="bd" >
 
 <div style="clear:left;">
  <h1>{t}File Transfer Via FTP{/t}</h1>
</div>
<ul class="tabs" id="chooser_ul" style="clear:both">
         <li> <span class="item"  id="details">  <span> {t}File Upload To Server{/t}</span></span></li>
</ul>
  
 <div class="tabbed_container"> 


<div class="data_table" style="clear:both">
  
<html>
<head>
<title>{$title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form action="ftp_transfer.php" method="post" enctype="multipart/form-data" name="form1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="14%">server name </td>
<td width="75%"><input name="server" type="text" id="server"> </td>
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
<div class="data_table" style="clear:both" align="center"><h3>{$confirm2}</h3></div>
</div>

  

<ul class="tabs" id="chooser_ul" style="clear:both">
         <li> <span class="item"  id="details">  <span> {t}File Download From Server{/t}</span></span></li>
</ul>
  
 <div class="tabbed_container"> 


<div class="data_table" style="clear:both">
  
<html>
<head>
<title>{$title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form action="ftp_transfer.php" method="post" enctype="multipart/form-data" name="form1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="14%">server name </td>
<td width="75%"><input name="server" type="text" id="server"> </td>
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
<td><input type="text" name="file"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td><input type="submit" name="Download_Submit" value="Download"></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table> 
</form>
</body>
</html> 
</div>
<div class="data_table" style="clear:both" align="center"><h3>{$confirm4}</h3></div>
</div>
</div> 
{include file='footer.tpl'}

