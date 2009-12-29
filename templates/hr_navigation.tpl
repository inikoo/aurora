{if !$editing}
<span class="nav2 onleft"><a {if $sub_parent=='hr'}class="selected"{/if} href="hr.php">{t}Staff{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='areas'}class="selected"{/if} href="company_areas.php">{t}Areas{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='departments'}class="selected"{/if}href="company_departments.php">{t}Departments{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='positions'}class="selected"{/if}href="positions.php">{t}Positions{/t}</a></span>
{/if}



<div class="search_box" {if $editing}style="position:relative;top:2px"{/if}>
  <div class="general_options">
{foreach from=$general_options_list item=options }
{if $options.tipo=="url"}
 <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
{else}
 <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
{/if}
{/foreach}
</div>
  
</div>  