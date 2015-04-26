 <table border="0" class="info_block with_title"  style="width:100%;{if $number_refunds==0}display:none{/if}">
	<tr style="border-bottom:1px solid #333;">
		<td colspan="2">{t}Refunds{/t}:</td>
	</tr>
	{foreach from=$refunds_data item=refund} 
	<tr>
		<td> <a href="invoice.php?id={$refund.key}">{$refund.number}</a> <a target='_blank' href="invoice.pdf.php?id={$refund.key}"> <img style="height:10px;vertical-align:0px" src="art/pdf.gif"></a> <img onclick="print_pdf('invoice',{$refund.key})" style="cursor:pointer;margin-left:2px;height:10px;vertical-align:0px" src="art/icons/printer.png"> </td>
		<td class="right" style="text-align:right"> {$refund.state} </td>
	</tr>
	<tr>
		<td colspan="2" class="aright" style="text-align:right"> {$refund.data} </td>
	</tr>
	<tr>
		<td colspan="2" class="right" style="text-align:right" id="operations_container{$refund.key}">{$refund.operations}</td>
	</tr>
	{/foreach} 
	
</table>
