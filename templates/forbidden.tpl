{include file='header.tpl'}
<div id="bd" >

<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {t}Forbidden Content{/t}</span> 
		</div>

<div class="top_page_menu">
			<div class="buttons" style="float:left">
				<span class="main_title" style="bottom:-3px">{$title} <span style="margin-left:10px;color:#777;font-style:italic">{t}Forbidden content{/t}</span></span> 
			</div>
			<div class="buttons" style="float:right">
			</div>
			<div style="clear:both">
			</div>
		</div>

<div class="warning" style="width:440px;padding:10px;margin-top:20px">
<p >
{if $scope=='store'}
{t}Sorry, you don't have the rights to access{/t} {$store->get('Store Name')}, {t}please contact your system administrator{/t}.

{else if $scope=='orders'}
{t}Sorry, you don't have the rights to access orders information, please contact your system administrator{/t}.

{else}
{t}You don't have the rights to see this page, please contact your system administrator{/t}.
{/if}
</p>
</div>
</div>

{include file='footer.tpl'}
