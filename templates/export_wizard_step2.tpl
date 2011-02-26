{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="clear:both" >
<h1 style="padding:40px;">Export Wizard - Step 2</h1>
<form action="export_data.php?subject=customer&subject_key={$customer_id}" method="POST" name="frm_export">



<table style="margin-left:60px;" border="1" width="400">
<tr><td colspan="2"><B>Select Fields to export</B></td></tr>
<div id="result">
{foreach from=$list key=list_key item=list_item name=foo}
<tr><td>
<input type="hidden" style="width:25px;" name="seq{$smarty.foreach.foo.index+1}" id="txt{$smarty.foreach.foo.index+1}" value="{$smarty.foreach.foo.index+1}" readonly="readonly"><a onClick=myfunc({$smarty.foreach.foo.index},{$smarty.foreach.foo.index-1});>Up</a>&nbsp;<a onClick=myfunc({$smarty.foreach.foo.index},{$smarty.foreach.foo.index+1});>Down</a></td>
<td>{$list_key}</td>
</tr>
{/foreach}
</div>
<tr>
<td colspan="2">
<input type="checkbox" id="save" name="save" value="save" checked="checked" /> Save my map&nbsp;<input type="checkbox" id="header" name="header" value="header" checked="checked" /> Include Field Names
</td>
</tr/>
<tr>
<td colspan="2"><input type="SUBMIT" name="SUBMIT" id="SUBMIT" value="Export Map"></td>

</tr>
</table>


</form>
</div>
</div>
{include file='footer.tpl'}
