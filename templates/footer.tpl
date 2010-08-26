<div id="ft">
<a href="site_map.php">{t}Site Map{/t}</a>
<a style="margin-left:10px" href="terms_use.php">{t}Terms of use{/t}</a>
<a style="margin-left:10px" href="report_bug.php">{t}Report Bug{/t}</a>
<div style="margin-top:2px">{t}Powered by Kaktus{/t}</div>
</div> 

</div>
<div id="langmenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      {foreach from=$lang_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" href="{$menu[0]}"><img src="art/flags/{$menu[1]}.gif"/ > {$menu[2]}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<iframe id="yui-history-iframe" src="blank.html"></iframe> 
<input id="yui-history-field" type="hidden">
  </body>
</html>
