{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="clear:both" >
<h1 style="padding:40px;">Export Wizard - Step 2</h1>
<form action="export_data.php?subject=customer&subject_key={$customer_id}" method="POST" name="frm_export" onSubmit="return validate();">



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
<input type="checkbox" id="header" name="header" value="header" checked="checked" /> Include Field Names in exported file
</td>
</tr/>
<tr>
<td colspan="2">
<input type="checkbox" id="save" name="save" value="save" checked="checked" onClick="saveMap();" /> Save my Map for future
	<table id="maps" style="paddingtop:5px; display:block;"><tr><td>Map Name:</td><td><input type="text" id="map_name" name="map_name" value=""/></td></tr>
	<tr><td>Map Description: </td><td><textarea id="map_desc" name="map_desc"></textarea></td></tr></table>
</td>
</tr/>

<tr>
<td colspan="2"><input type="SUBMIT" name="SUBMIT" id="SUBMIT" value="Export Map" ></td>

</tr>
</table>


</form>
</div>
</div>
{include file='footer.tpl'}
