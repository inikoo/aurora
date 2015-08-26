 <div id="navigation">
	<div id="section_links" class="{if isset($_content.sections_class)}{$_content.sections_class}{/if}">
		{foreach from=$_content.sections item=section_link } 
		<div {if isset($section_link.id) and $button.id }id="{$button.id}"{/if} class="section right"  {if isset($section_link.url)}onclick="location.href='{$section_link.url}'"{/if} title="{$section_link.title}">
			{if $section_link.icon!=''}<i class="fa fa-{$section_link.icon}"></i>{/if} <span class="section_label">{$section_link.label}</span> 
		</div>
		{/foreach} 
	</div>
	<div class="branch">
		<span> {foreach from=$_content.branch name=branch item=branch } <a href="{$branch.url}"> {if $branch.icon!=''}<i class="fa fa-{$branch.icon} fa-fw"></i>{/if} {$branch.label}</a> {if !$smarty.foreach.branch.last}&rarr;{/if} {/foreach} </span> 
	</div>
</div>
<div id="header">
	{foreach from=$_content.left_buttons item=button } 
	<div  {if isset($button.id) and $button.id }id="{$button.id}"{/if}  class="square_button left" {if isset($button.url) and $button.url!=''}onclick="location.href='{$button.url}'" {/if} title="{$button.title}">
		<i class="fa fa-{$button.icon} fa-fw"></i> 
	</div>
	{/foreach} 
	<h1>
		{$_content.title}
	</h1>
	<div id="search_form" style="{if !$_content.search.show}display:none{/if}">
		<input id="search" placeholder="{$_content.search.placeholder}">
		<div class="square_button right">
			<i class="fa fa-search fa-fw"></i> 
		</div>
	</div>
	{foreach from=$_content.right_buttons name=right_buttons item=button } 
	<div {if isset($button.id) and $button.id }id="{$button.id}" {/if} class="square_button right {if $smarty.foreach.right_buttons.first}border{/if}" {if isset($button.url)}onclick="location.href='{$button.url}'" {/if} title="{$button.title}">
		<i class="fa fa-{$button.icon} fa-fw "></i> 
	</div>
	{/foreach} 
</div>
