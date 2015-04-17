<div id="sticky_note_div" class="sticky_note pink" style="position:relative;left:-20px;width:270px;{if $order->get('Sticky Note')==''}display:none{/if}">
	<img id="sticky_note_bis" style="float:right;cursor:pointer" src="art/icons/edit.gif"> 
	<div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
		{$order->get('Sticky Note')} 
	</div>
</div>
<div id="dialog_sticky_note" style="padding:20px 20px 0px 20px;width:340px;display:none">
	<div id="sticky_note_msg">
	</div>
	<table>
		<tr>
			<td> <textarea style="width:330px;height:125px" id="sticky_note_input" onkeyup="change_sticky_note()">{$order->get('Sticky Note')}</textarea> </td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button class="positive" onclick="save_sticky_note()">{t}Save{/t}</button> <button class="negative" onclick="close_dialog_sticky_note()">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>