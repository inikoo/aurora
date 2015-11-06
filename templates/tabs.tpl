{if  count($_content.tabs)>1  }
<div id="maintabs" class="tabs">
{foreach from=$_content.tabs item=tab  key=id} 
		<div id="tab_{$id}" class="tab {if  isset($tab.class)}{$tab.class}{else}left{/if} {if isset($tab.selected) and $tab.selected}selected{/if}"   {if  isset($tab.reference)}onclick="change_view('{$tab.reference}')" {else} onclick="change_tab('{$id}')"{/if} title="{$tab.title}">
			{if isset($tab.icon) and $tab.icon!=''}<i class="fa fa-{$tab.icon}"></i>{/if} <span class="label"> {$tab.label}</span> 
		</div>
{/foreach}
	
</div>
{/if}
{if   count($_content.subtabs)>1   }
<div id="subtabs" class="subtabs">
{foreach from=$_content.subtabs item=tab key=id } 
		<div id="subtab_{$id}"class="tab left {if isset($tab.selected) and $tab.selected}selected{/if}"   onclick="change_subtab('{$id}')" title="{$tab.title}">
			{if isset($tab.icon) and  $tab.icon!=''}<i class="fa fa-{$tab.icon}"></i>{/if} <span class="label"> {$tab.label}</span> 
		</div>
{/foreach} 		
</div>
{/if}