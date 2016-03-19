<ul>
	{foreach from=$nav_menu item=menu }
	<li id="module_{$menu[3]}" onclick="change_view('{$menu[2]}')" class="module {$menu[3]} {$menu[5]}   {if ($current_item==$menu[3]  and $menu[4]=='module')  or ( $current_section==$menu[3]  and $menu[4]=='section')}selected{/if}  {$menu[4]}">{$menu[0]}<span class="label">{$menu[1]}</spam></li>
	{/foreach} 
</ul>
<div style="clear:both">
</div>