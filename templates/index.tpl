{include file='header.tpl'} 
<div id="bd" style="padding:0px 0px">
	<div style="padding:0px 20px">
		<div class="branch" style="clear:left;">
			<span>{t}Dashboard{/t}</span> 
		</div>
		<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px">
			<div class="buttons" style="float:right">
				<button onclick="window.location='edit_dashboard.php?id={$dashboard_key}'"><img src="art/icons/cog.png" alt=""> {t}Configure Dashboard{/t}</button> 
			</div>
			<div class="buttons" style="float:left">
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div class="dashboard_blocks" style="margin-top:20px">
		{foreach from=$blocks key=key item=block} 
		<div class="{$block.class}" style="margin-bottom:30px">
			<iframe onload="changeHeight(this);" id="block_{$block.key}" src="{$block.src}&block_key={$block.key}" width="100%" {if $block.height}height="{$block.height}{/if}" frameborder="0" scrolling="no"> 
		<p>
			{t}Your browser does not support iframes{/t}.
		</p>
		</iframe> 
	</div>
	{/foreach} 
</div>
</div>
{include file='footer.tpl'} 