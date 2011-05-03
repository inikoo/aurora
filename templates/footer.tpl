<div id="footer">
<a style="margin-left:10px" href="terms_use.php">{t}Terms of use{/t}</a>
<a style="margin-left:10px" href="report_issue.php?t=bug">{t}Report a problem{/t}</a>
<a style="margin-left:10px" href="report_issue.php?t=feature">{t}Request a feature{/t}</a>

<div class='adv' style="margin-top:2px">{t}Powered by Inikoo{/t}</div>
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
