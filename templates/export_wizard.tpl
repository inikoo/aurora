{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="clear:both" >
<h1 style="padding:40px;">Export Wizard</h1>
<form action="export_wizard_step2.php?subject=customer&subject_key={$customer_id}" method="POST" name="frm_export">
<table style="margin-left:60px;" border="1" width="400">
<tr><td colspan="2"><B>Select Fields to export</B></td></tr>
{foreach from=$list key=list_key item=list_item}
<tr><td><input type="checkbox" name="fld[]" id="fld[]" value="{$list_key}"></td>
<td>{$list_key}</td>
</tr>
{/foreach}

<tr>
<td colspan="2"><input type="SUBMIT" name="SUBMIT" id="SUBMIT" value="Next..."></td>

</tr>
</table>
</form>
</div>
</div>
</div>
{include file='footer.tpl'}
