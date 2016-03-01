 
 <div id="address_bar" >
	<div id="section_links" class="{if isset($_content.sections_class)}{$_content.sections_class}{/if} {if count($_content.sections)<=1}hide{/if}">
		{foreach from=$_content.sections|@array_reverse item=section_link } 
		<div {if isset($section_link.id) and $section_link.id }id="{$section_link.id}"{/if} class="section right  {if isset($section_link.selected) and $section_link.selected}selected{/if}"  {if isset($section_link.reference) and $section_link.reference!=''}onclick="change_view('{$section_link.reference}')"{/if}  title="{if isset($section_link.title)}{$section_link.title}{else}{$section_link.label}{/if}">
			{if $section_link.icon!=''}<i class="fa fa-{$section_link.icon} "></i>{/if} <span class="section_label"> {$section_link.label}</span> 
		</div>
		{/foreach} 
	</div>
	
</div>
<div id="header">
	{foreach from=$_content.left_buttons item=button } 
	
	<div  {if isset($button.id) and $button.id }id="{$button.id}"{/if}  class="square_button left"       {if isset($button.reference) and $button.reference!=''}onclick="change_view('{$button.reference}')"{/if} title="{$button.title}">
		<i class="fa fa-{$button.icon} fa-fw"></i> 
	</div>
	{/foreach}  
	
	{if isset($_content.avatar) }
	{$_content.avatar}
	{/if}
	
	<span id="nav_title" class="title">
		{$_content.title}
	</span>
 
<div id="search_form" class="search_form" style="position:relative;{if !$_content.search.show}display:none{/if}">
	<input id="search" placeholder="{$_content.search.placeholder}"> <div id="clear_search" class="hide" ><i onclick="clear_search()" class="fa fa-times "></i></div>
	<div class="square_button right">
		<i class="fa fa-search fa-fw"></i> 
	</div>
	<div id="results_container" class="search_results_container" >
		<div id="results_container_shifted" class="hide">
		<table id="results" border="0" >
			<tr class="hide" id="search_result_template" view="" onClick="change_view(this.getAttribute('view'))">
				<td class="label" ></td>
				<td class="details" ></td>
			</tr>
		</table>
	</div>
	</div>
</div>
	
	
	{foreach from=$_content.right_buttons name=right_buttons item=button } 
	<div {if isset($button.id) and $button.id }id="{$button.id}" {/if} {if isset($button.click)  }onClick="{$button.click}" {/if}      class=" {if isset($button.class)}{$button.class}{/if}  square_button right {if $smarty.foreach.right_buttons.first}border{/if}" {if isset($button.reference) and $button.reference!=''}onclick="change_view('{$button.reference}')"{/if}title="{$button.title}">
		<i class="fa fa-{$button.icon} fa-fw "></i> 
	</div>
	{/foreach} 
</div>
