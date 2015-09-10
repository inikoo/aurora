{foreach from=$_content.branch name=branch item=branch } 
<span {if isset($branch.reference) and $branch.reference!=''}onclick="change_view('{$branch.reference}')"{/if} > {if $branch.icon!=''}<i class="fa fa-{$branch.icon} "></i>{/if} {$branch.label}</span> {if !$smarty.foreach.branch.last}<i class="fa fa-angle-double-right separator"></i>{/if}
{/foreach}
