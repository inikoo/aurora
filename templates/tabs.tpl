{if !empty($_content.tabs)}
<div id="maintabs" class="tabs">
{foreach from=$_content.tabs item=tab } 
		<div {if isset($tab.id) and $tab.id }id="{$tab.id}"{/if} class="tab left {if isset($tab.selected) and $tab.selected}selected{/if}"  {if isset($tab.view) and $tab.view!=''}onclick="change_view('{$tab.view}')"{/if} title="{$tab.title}">
			{if isset($tab.icon) and $tab.icon!=''}<i class="fa fa-{$tab.icon}"></i>{/if} <span class="label"> {$tab.label}</span> 
		</div>
{/foreach} 		
</div>
{/if}
{if !empty($_content.subtabs)}
<div id="subtabs" class="tabs">
{foreach from=$_content.subtabs item=tab } 
		<div {if isset($tab.id) and $tab.id }id="{$tab.id}"{/if} class="subtab left selected{if isset($tab.selected) and $tab.selected}selected{/if}"  {if isset($tab.view) and $tab.view!=''}onclick="change_view('{$tab.view}')"{/if} title="{$tab.title}">
			{if $tab.icon!=''}<i class="fa fa-{$tab.icon}"></i>{/if} <span class="label"> {$tab.label}</span> 
		</div>
{/foreach} 		
</div>
{/if}