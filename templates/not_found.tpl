{include file='header.tpl'}
<div id="bd" >

<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {t}Not found{/t}</span> 
		</div>

<div class="top_page_menu">
			<div class="buttons" style="float:left">
				<span class="main_title" style="bottom:-3px">{if isset($title)}{$title}{/if} <span style="margin-left:10px;color:#777;font-style:italic">{t}Not found{/t}</span></span> 
			</div>
			<div class="buttons" style="float:right">
			</div>
			<div style="clear:both">
			</div>
		</div>

<div class="warning" style="width:440px;padding:10px;margin-top:20px">
<p >

{t}Sorry, content not found{/t}.

</p>
</div>
</div>

{include file='footer.tpl'}
