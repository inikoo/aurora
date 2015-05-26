<div id="sticky_note_div" class="sticky_note pink" style="position:relative;left:-20px;width:270px;{if $order->get('Sticky Note')==''}display:none{/if}">
	<img id="sticky_note_bis" style="float:right;cursor:pointer" src="art/icons/edit.gif"> 
	<div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
		{$order->get('Sticky Note')} 
	</div>
</div>
