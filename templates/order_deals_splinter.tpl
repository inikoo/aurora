<table border="0" id="offers" style="width:100%;font-size:95%;margin-bottom:5px" class="edit">
	{foreach from=$order->get_deals_info() item=deal name=deals} 
	{if $smarty.foreach.deals.first}
	<tr class="title">
	<td>{t}Offers{/t}</td>
	</tr>
	{/if}
	
	<tr id="deal_{$deal.key}">
		<td><a href="deal.php?id={$deal.key}">{$deal.name}</a></td>
		<td>{$deal.terms_allowances}</td>
	</tr>
	{/foreach} 
</table>

