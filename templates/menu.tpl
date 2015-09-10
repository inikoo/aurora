<div id="menu">
	<ul>
		{foreach from=$nav_menu item=menu } 
		<li id="module_{$menu[2]}" onclick="change_view('{$menu[1]}')" class="module {if $current_item==$menu[1]}selected{/if}">{$menu[0]}</li>
		{/foreach} 
	</ul>
	<div style="clear:both">
	</div>
</div>
