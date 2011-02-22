{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="clear:both" >
<h1 style="padding:40px;">Export Wizard</h1>
<form action="new_customer_csv.php?id=1931" method="POST" name="frm_export">
<table style="margin-left:60px;" border="1" width="400">
<tr><td width="40"></td><td>Select fields to export</td></tr>
<tr><td><input type="checkbox" name="fld[]" id="fld[]" value="Customer Key" checked="checked"></td>
<td>Customer Key</td></tr>
<tr><td><input type="checkbox" name="fld[]" id="fld[]" value="Customer Store Key" checked="checked"></td>
<td>Customer Store Key</td>
</tr>
<tr>
<td colspan="2"><input type="SUBMIT" name="SUBMIT" id="SUBMIT" value="Export"></td>
</tr>
</table>
</form>
</div>
</div>
</div>
{include file='footer.tpl'}
