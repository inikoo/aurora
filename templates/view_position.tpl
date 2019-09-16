{foreach from=$_content.branch name=branch item=branch }
    <span {if isset($branch.reference) and $branch.reference!=''}onclick="change_view('{$branch.reference}' {if isset($branch.metadata)},{$branch.metadata}{/if}  )"{/if} >
        {if !empty($branch.icon)}<i class="fa fa-fw fa-{$branch.icon} "></i>{elseif !empty($branch.html_icon)}{$branch.html_icon}{/if} {$branch.label}</span>
    {if !$smarty.foreach.branch.last}<i class="fa fa-angle-double-right separator"></i>{/if}
{/foreach}
