<table border="0" class="info_block with_title" style="width:100%;{if $number_dns==0}display:none{/if}">
	<tr style="border-bottom:1px solid #333;">
		<td colspan="2">{t}Replacements & Shortages Delivery Notes{/t}:</td>
	</tr>
	{foreach from=$dns_data item=dn} 
	<tr>
		<td> <a href="dn.php?id={$dn.key}">{$dn.number}</a> <a target='_blank' href="dn.pdf.php?id={$dn.key}"> <img style="height:10px;vertical-align:0px" src="art/pdf.gif"></a> <img onclick="print_pdf('dn',{$dn.key})" style="cursor:pointer;margin-left:2px;height:10px;vertical-align:0px" src="art/icons/printer.png"> </td>
		<td class="right" style="text-align:right"> {$dn.state} </td>
	</tr>
	<tr style="{if $dn.dispatch_state=='Dispatched'}display:none{/if}">
		<td colspan="2" class="aright" style="text-align:right"> {$dn.data} </td>
	</tr>
	<tr>
		<td colspan="2" class="aright" style="text-align:right" id="operations_container{$dn.key}">{$dn.operations}</td>
	</tr>
	<tr style="{if $dn.dispatch_state=='Dispatched'}display:none{/if}">
		<td colspan="2"> 
		<table style="width:100%;margin:0px;">
			<tr>
				<td style="border:1px solid #eee;width:50%;text-align:center" id="pick_aid_container{$dn.key}"><a href="order_pick_aid.php?id={$dn.key}">{t}Picking Aid{/t}</a> <a target='_blank' href="order_pick_aid.pdf.php?id={$dn.key}"> <img style="height:10px;vertical-align:0px" src="art/pdf.gif"></a> <img onclick="print_pdf('order_pick_aid',{$dn.key})" style="cursor:pointer;margin-left:2px;height:10px;vertical-align:0px" src="art/icons/printer.png"> </td>
				<td style="border:1px solid #eee;width:50%;;text-align:center" class="aright" style="text-align:right" id="pack_aid_container{$dn.key}"><a href="order_pack_aid.php?id={$dn.key}">{t}Pack Aid{/t}</a></td>
			</tr>
		</table>
		</td>
	</tr>
	{/foreach} 
</table>
