{*
{if !$editing or false}
<span class="nav2 onleft"><a {if $sub_parent=='hr'}class="selected"{/if} href="hr.php">{t}Staff{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='areas'}class="selected"{/if} href="company_areas.php">{t}Areas{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='departments'}class="selected"{/if}href="company_departments.php">{t}Departments{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='positions'}class="selected"{/if}href="positions.php">{t}Positions{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='holidays'}class="selected"{/if}href="holidays.php">{t}Business Holidays{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='staff_holidays'}class="selected"{/if}href="staff_holidays.php">{t}Staff Holidays{/t}</a></span>
{/if}
*}

<span class="nav2 onright" style="padding:0px">{if $next.id>0}<a class="next" href="customer.php?{$parent_info}id={$next.id}" ><img src="art/icons/next_white.png" style="padding:0px 10px" alt=">" title="{$next.name}"  /></a>{/if}</span>
{if $parent_url}<span class="nav2 onright"><a   href="{$parent_url}">{$parent_title}</a></span>{/if}
<span class="nav2 onright" style="margin-left:20px; padding:0px"> {if $prev.id>0}<a class="prev" href="customer.php?{$parent_info}id={$prev.id}" ><img src="art/icons/previous_white.png" style="padding:0px 10px" alt="<" title="{$prev.name}"  /></a>{/if}</span>

<table class="search"  border=0 style="{if $search_label==''}display:none{/if}">
<tr>
<td class="label"  >{t}Search{/t}:</td>
<td class="form" >
<div id="search" class="asearch_container"  style=";float:left;{if !$search_scope}display:none{/if}">
  <input style="width:300px" class="search" id="{$search_scope}_search" value="" state="" name="search"/>
      <img style="position:relative;left:305px" align="absbottom" id="{$search_scope}_clean_search" class="submitsearch" src="art/icons/zoom.png">

    <div id="{$search_scope}_search_Container" style="display:none"></div>
</div>    
  
</td></tr>
</table>  
<div id="{$search_scope}_search_results" style="font-size:10px;float:right;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;top:-500px">
<table id="{$search_scope}_search_results_table"></table>
</div>

<div style="clear:both;margin-top:0px;margin-right:0px;width:{if $options_box_width}{$options_box_width}{else}300px{/if};float:right;margin-bottom:0px" class="right_box">
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