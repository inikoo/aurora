<div id="order_container" >
<input type="hidden" value="{$last_basket_page_key}" id="last_basket_page_key">


	<div id="control_panel" style="height:300px">
		{if $cancelled}
		<h1>{t}Your order has been cancelled{/t}</h1>
		{else}
		<h1>{t}Your basket is empty{/t}</h1>
		{/if}
	</div>
</div>	
	