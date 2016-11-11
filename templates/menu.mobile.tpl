<nav class="mdl-navigation">

    {foreach from=$nav_menu item=menu }
        <span class="mdl-navigation__link" id="module_{$menu[3]}" onclick=" document.querySelector('.mdl-layout').MaterialLayout.drawerToggleHandler_(); change_view('{$menu[2]}')"
              class=" {$menu[3]} {$menu[5]}   {if ($current_item==$menu[3]  and $menu[4]=='module')  or ( $current_section==$menu[3]  and $menu[4]=='section')}selected{/if}  {$menu[4]}">{$menu[0]}
            <span class="label">{$menu[1]}</span></span>
    {/foreach}
    <span class="mdl-navigation__link">
        <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="margin-right:5px"  onclick="desktop_view()" ><i class="material-icons">computer</i></button>
         <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored"  onclick="logout()" ><i class="material-icons">power_settings_new</i></button>

    </span>
</nav>
