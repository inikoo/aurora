 
<ul>
	{foreach from=$nav_menu item=menu } 
	<li id="module_{$menu[2]}" onclick="change_view('{$menu[1]}')" class="module {if ($current_item==$menu[2]  and $menu[3]=='module')  or ( $current_section==$menu[2]  and $menu[3]=='section')}selected{/if} {$menu[3]}">{$menu[0]}</li>
	{/foreach} 
</ul>
<div style="clear:both">
</div>
