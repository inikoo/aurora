<div id="address_bar">
    <div id="section_links"
         class="  {if isset($_content.sections_class)}{$_content.sections_class}{/if} {if count($_content.sections)<=1}hide{/if}">
        {foreach from=$_content.sections|@array_reverse item=section_link }
            <div {if isset($section_link.id) and $section_link.id }id="{$section_link.id}"{/if}
                 class="section  {if isset($section_link.class)}{$section_link.class}{else}right{/if} {if isset($section_link.selected) and $section_link.selected}selected{/if}" {if isset($section_link.reference) and $section_link.reference!=''}onclick="change_view('{$section_link.reference}')"{/if}
                 title="{if isset($section_link.title)}{$section_link.title}{elseif isset($section_link.label) }{$section_link.label}{else}*{/if}">
                {if !empty($section_link.icon)}<i class="far fa-{$section_link.icon} "></i>{/if}
                {if !empty($section_link.html_icon)}{$section_link.html_icon}{/if}
                <span class="section_label"> {if isset($section_link.label) }{$section_link.label}{else}*{/if}</span>
            </div>
        {/foreach}
        <div   style="clear:both"></div>
    </div>
    <div   style="clear:both"></div>
</div>
<div id="header" >
    {foreach from=$_content.left_buttons item=button }
        <div {if isset($button.id) and $button.id }id="{$button.id}"{/if}
             class="square_button {if !empty($button.class)}{$button.class}{/if} left"   {if isset($button.reference) and $button.reference!=''}onclick="change_view('{$button.reference}' {if isset($button.metadata)},{$button.metadata}{/if}  )"{/if}
             title="{$button.title}">
            {if isset($button.html_icon)}{$button.html_icon}{else}<i class="far fa-{$button.icon} fa-fw">{/if}</i>{if isset($button.text)}{$button.text}{/if}
        </div>
    {/foreach}

    {if isset($_content.avatar) }
        {$_content.avatar}
    {/if}

    <span id="nav_title" class="title">
		{$_content.title}
	</span>

    {if isset($_content.skip) and $_content.skip }
        <span id="skip" class="title button skip" onClick="skip()">
		{t}Skip{/t} 
	</span>
    {/if}
    <div id="search_form" class="search_form" style="position:relative;{if !$_content.search.show}display:none{/if}">
        <input id="search" placeholder="{$_content.search.placeholder}">
        <div id="clear_search" class="hide"><i onclick="clear_search()" class="fa fa-times "></i></div>
        <div class="square_button right">
            <i class="fa fa-search fa-fw"></i>
        </div>
        <div id="results_container" class="search_results_container">
            <div id="results_container_shifted" class="hide">
                <table id="results" border="0">
                    <tr class="hide" id="search_result_template" view=""
                        onClick="change_view(this.getAttribute('view'))">
                        <td class="store padding_left_20 hide"></td>
                        <td class="label"></td>
                        <td class="details"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    {foreach from=$_content.right_buttons name=right_buttons item=button }
        <div {if isset($button.id) and $button.id }id="{$button.id}" {/if} {if isset($button.click)  }onClick="{$button.click}" {/if}
             class=" {if isset($button.class)}{$button.class}{/if}  square_button right {if $smarty.foreach.right_buttons.first}border{/if}"
             {if isset($button.reference) and $button.reference!=''}onclick="change_view('{$button.reference}')"
             {/if}title="{$button.title}">
            {if isset($button.pre_text)}{$button.pre_text}{/if} <i class="{if isset($button.icon_class)}{$button.icon_class}{else}fa{/if} fa-{$button.icon} fa-fw "></i> {if isset($button.text)}{$button.text}{/if}
        </div>
    {/foreach}
</div>
