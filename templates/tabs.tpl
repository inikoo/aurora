{if !empty($_content.tabs)}
<div id="maintabs" class="tabs">
{foreach from=$_content.tabs item=tab  key=id} 
		<div id="tab_{$id}" class="tab left {if isset($tab.selected) and $tab.selected}selected{/if}"  onclick="change_tab('{$id}')" title="{$tab.title}">
			{if isset($tab.icon) and $tab.icon!=''}<i class="fa fa-{$tab.icon}"></i>{/if} <span class="label"> {$tab.label}</span> 
		</div>
{/foreach} 		
</div>
{/if}
{if !empty($_content.subtabs)}
<div id="subtabs" class="tabs">
{foreach from=$_content.subtabs item=tab key=id } 
		<div id="subtab_{$id}"class="subtab left selected{if isset($tab.selected) and $tab.selected}selected{/if}"   onclick="change_subtab('{$id}')" title="{$tab.title}">
			{if $tab.icon!=''}<i class="fa fa-{$tab.icon}"></i>{/if} <span class="label"> {$tab.label}</span> 
		</div>
{/foreach} 		
</div>
{/if}