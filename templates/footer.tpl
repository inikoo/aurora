<div id="footer" ;">
	<div class="links">
		<a href="terms_use.php">{t}Terms of use{/t}</a> <a style="margin-left:10px;" href="report_issue.php?t=bug">{t}Report a problem{/t}</a> <a style="margin-left:10px;" href="report_issue.php?t=feature">{t}Request a feature{/t}</a> 
	</div>
	<div class='adv'>
		<img src="art/inikoo_logo_mini.png" > <a href="http://www.inikoo.com">{t}Inikoo{/t}</a> <a href="http://www.inikoo.com/changelog.php/v={$inikoo_version}">v{$inikoo_version}</a>
	</div>
	<div style="clear:both"></div>
</div>
<div id="langmenu" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			{foreach from=$lang_menu item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" href="{$menu[0]}"><img style="position:relative;top:-3.5px" src="art/flags/{$menu[1]}.gif" /> {$menu[2]}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
</div>
</body>
</html>
