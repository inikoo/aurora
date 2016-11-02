<ul>
    {foreach from=$nav_menu item=menu }
        <li id="module_{$menu[3]}" onclick="change_view('{$menu[2]}')"
            class="module {$menu[3]} {$menu[5]}   {if $current_step==$menu[3]}current_step{/if}  {$menu[4]}"><span
                    title="{$menu[1]}">{$menu[0]}</span> <span class="label">{$menu[1]}</span></li>
    {/foreach}
</ul>
<div style="clear:both">
</div>
