
	{foreach from=$attachments item=attachment } 
	<div class="attachment">
		{if $attachment.thumbnail} 
		<a href="image.php?id={$attachment.thumbnail}&size=normal" class="imgpop"><img class="thumbnail" src="image.php?id={$attachment.thumbnail}&size=small"></a>
		 {else} 
		<div class="empty_thumbnail">
		</div>
		{/if} 
		<div class="caption">
			{$attachment.caption}
		</div>
		<div class="links">
			<a href="file.php?id={$attachment.key}">{$attachment.icon}</a> <a class="filename" href="file.php?id={$attachment.key}" title="{$attachment.full_name}">{$attachment.name}</a>
		</div>
	</div>
	{/foreach} 
	<div style="clear:both">
	</div>

