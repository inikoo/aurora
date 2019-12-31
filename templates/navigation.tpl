<div id="address_bar" style="display: flex;position: relative">
    {if isset($_content.sections.left_button)}
        {foreach from=$_content.sections.left_button name=left_buttons item=button }
            <div {if isset($button.id) and $button.id }id="{$button.id}" {/if} {if isset($button.click)  }onClick="{$button.click}" {/if}
                 class=" {if isset($button.class)}{$button.class}{/if}  square_button right {if $smarty.foreach.left_buttons.first}border{/if}"
                 {if isset($button.reference) and $button.reference!=''}onclick="change_view('{$button.reference}')"
                 {/if}title="{$button.title}">
                {if isset($button.pre_text)}{$button.pre_text}{/if}
                {if !empty($button.icon)}<i class="fal fa-{$button.icon} fa-fw "></i>{/if}
                {if !empty($button.html_icon)}{$button.html_icon}{/if}
                {if isset($button.text)}{$button.text}{/if}
            </div>
        {/foreach}
    {/if}
    {if isset($_content.sections.navigation)}
        <div style="flex-grow:2;border-right: 1px solid #dcc">
            <div id="section_links"
                 class="  {if isset($_content.sections_class)}{$_content.sections_class}{/if} {if count($_content.sections)<=1}hide{/if}">
                {foreach from=$_content.sections.navigation item=section_link }
                    <div {if isset($section_link.id) and $section_link.id }id="{$section_link.id}"{/if}
                         class="section  {if isset($section_link.class)}{$section_link.class}{else}left{/if} {if isset($section_link.selected) and $section_link.selected}selected{/if}" {if isset($section_link.reference) and $section_link.reference!=''}onclick="change_view('{$section_link.reference}')"{/if}
                         title="{if isset($section_link.title)}{$section_link.title}{elseif isset($section_link.label) }{$section_link.label}{else}*{/if}">
                        {if !empty($section_link.icon)}<i class="far fa-{$section_link.icon} "></i>{/if}
                        {if !empty($section_link.html_icon)}{$section_link.html_icon}{/if}
                        <span class="section_label"> {if isset($section_link.label) }{$section_link.label}{else}*{/if}</span>
                    </div>
                {/foreach}
                <div style="clear:both"></div>
            </div>
        </div>
    {/if}
    {if isset($_content.sections.right_button)}
        {foreach from=$_content.sections.right_button name=right_buttons item=button }
            <div {if isset($button.id) and $button.id }id="{$button.id}" {/if} {if isset($button.click)  }onClick="{$button.click}" {/if}
                 class=" {if isset($button.class)}{$button.class}{/if}  square_button right {if $smarty.foreach.right_buttons.first}border{/if}"
                 {if isset($button.reference) and $button.reference!=''}onclick="change_view('{$button.reference}')"
                 {/if}title="{$button.title}">
                {if isset($button.pre_text)}{$button.pre_text}{/if}
                {if !empty($button.icon)}<i class="fal fa-{$button.icon} fa-fw "></i>{/if}
                {if !empty($button.html_icon)}{$button.html_icon}{/if}
                {if isset($button.text)}{$button.text}{/if}
            </div>
        {/foreach}
    {/if}
    <div class="smart_search_input" >

        <label for="smart_search" aria-label="{t}Search{/t}" style="">
            <i class="far fa-search"></i>
        </label>
        <form >
            <input placeholder="{if !empty($_content.search.placeholder)}{$_content.search.placeholder}{else}{t}Search{/t}{/if}" />
        </form>
        <div class="options">
        <span class="close_search ">
                <button>
                <i class="fal fa-fw fa-times"></i>
                </button>
            </span>
        </div>
        <div class="options hide">
            <span>
                <button ">
                <i class="small fal fa-fw fa-sliders-h"></i>
                </button>
            </span>
        </div>


    </div>

    <div class="smart_search_result hide">
        <div class="search_result_title  small">
            <div class="result_info italic">{t}Lightweight results{/t} <span class="num"></span></div>


            <div class="options">
            <span>
                <button style="padding-right: 10px">
                <i class="small save valid changed far fa-fw fa-search-plus"></i>
                </button>
            </span>
            </div>

        </div>
        <table class="results">

        </table>
    </div>


</div>

<div id="header">
    {foreach from=$_content.left_buttons item=button }
        <div {if isset($button.id) and $button.id }id="{$button.id}"{/if}
             class="square_button {if !empty($button.class)}{$button.class}{/if} left" {if isset($button.reference) and $button.reference!=''}onclick="change_view('{$button.reference}' {if isset($button.metadata)},{$button.metadata}{/if}  )"{/if}
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

    <div class="pseudo_sections">

    </div>


    {foreach from=$_content.right_buttons name=right_buttons item=button }
        <div {if isset($button.id) and $button.id }id="{$button.id}" {/if} {if isset($button.click)  }onClick="{$button.click}" {/if}
             class=" {if isset($button.class)}{$button.class}{/if}  square_button right {if $smarty.foreach.right_buttons.first}border{/if}"
             {if isset($button.reference) and $button.reference!=''}onclick="change_view('{$button.reference}')"
             {/if}title="{$button.title}">
            {if isset($button.pre_text)}{$button.pre_text}{/if}
            {if !empty($button.icon)}<i class="fa fa-{$button.icon} fa-fw "></i>{/if}
            {if !empty($button.html_icon)}{$button.html_icon}{/if}
            {if isset($button.text)}{$button.text}{/if}
        </div>
    {/foreach}


</div>
