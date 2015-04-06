
<table border="0" id="vouchers" style="width:100%;font-size:95%;margin-bottom:5px" class="edit">
	{foreach from=$order->get_vouchers_info() item=voucher name=vouchers} 
	{if $smarty.foreach.vouchers.first}
	<tr class=title>
	<td>{t}Vouchers{/t}</td>
	</tr>
	{/if}
	
	<tr id="voucher_{$voucher.key}">
		<td>{$voucher.code}</td>
		<td><b><a href="deal.php?id={$voucher.deal_key}">{$voucher.deal_code}</a></b> {$voucher.deal_name}</td>
		<td><img style="cursor:pointer;height:10px;position:relative;top:2px" onclick="remove_voucher({$voucher.key})" src="art/icons/cross_bw.png"></td>
	</tr>
	{/foreach} 
</table>
<div class="buttons left small" style="font-size:105%">
	<button id="add_voucher" onClick="show_dialog_add_voucher()"><img src="art/icons/add.png"> {t}Voucher{/t}</button> 
</div>
