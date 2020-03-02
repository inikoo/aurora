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

<span id="nav_title" class="title">{$_content.title}</span>

{if isset($_content.skip) and $_content.skip }
    <span id="skip" class="title button skip" onClick="skip()">
		{t}Skip{/t}
	</span>
{/if}



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



