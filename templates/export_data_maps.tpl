{include file='header.tpl'}
<div id="bd" >
<div class="data_table" style="clear:both">
   <span class="clean_table_title">{t}Saved Maps{/t}</span>
	<div style="clear: both; margin: 0pt 0px; padding: 0pt 20px; border-bottom: 1px solid rgb(153, 153, 153);"></div>
         <span style="font-size:11px;">{$no_of_maps} record(s)<span>
     <div style="clear: both; margin: 0pt 0px; padding: 0pt 20px; border-bottom: 1px solid #4682b4;"></div>
      <table width="913">
           <tr style="border-bottom:1px #4682b4 solid;"><td class="campaign_header">Map Id</td><td class="campaign_header">Map Name</td><td class="campaign_header">Map Description</td><td class="campaign_header">Export</td>
	   </tr>
    <tr bgcolor="{cycle values="#eeeeee,#d0d0d0"}">
	{foreach from=$maps item=d}
	<tr bgcolor="#eeeeee">
	<td align="center">{$d[0]}</td>
	<td align="center">{$d[1]}</td>
	<td align="center">{$d[2]}</td>
	<td align="center"><a href="export_data.php?subject={$subject}&subject_key={$customer_id}&source=db&id={$d[0]}">Export</a></td>
	</tr>
	{/foreach}
    </tr>
	   <tr><td></td>
		<td></td>
		<td></td>
		<td></td>
	  </tr>
     </table>
  </div>
</form>
</div>
{include file='footer.tpl'}
