{if !$editing}
<span class="nav2 onleft"><a {if $sub_parent=='hr'}class="selected"{/if} href="hr.php">{t}Staff{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='areas'}class="selected"{/if} href="company_areas.php">{t}Areas{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='departments'}class="selected"{/if}href="company_departments.php">{t}Departments{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='positions'}class="selected"{/if}href="positions.php">{t}Positions{/t}</a></span>
{/if}


<div class="right_box">
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
<table class="search"  border=0 style="{if !$search_scope}display:none{/if}" > 
<tr>
<td class="label" style="" >{$search_label}:</td>
<td class="form" style="">
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