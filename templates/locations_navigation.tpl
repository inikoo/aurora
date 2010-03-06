
{if $warehouse_list_length>1}
<dl class="dropdown">
  <dt id="one-ddheader" onmouseover="ddMenu('one',1)" onmouseout="ddMenu('one',-1)" onclick="window.location='warehouses.php'" >{t}Warehouses{/t}</dt>
  <dd id="one-ddcontent" onmouseover="cancelHide('one')" onmouseout="ddMenu('one',-1)">
    <ul>
      {foreach from=$tree_list item=warehouse }
      <li><a href="warehouse.php?id={$warehouse.id}" class="underline">{$warehouse.code}</a></li>
      {/foreach}
    </ul>
  </dd>
</dl>
{else}
<dl class="dropdown">
  <dt id="one-ddheader" onclick="window.location='warehouses.php'" ><span>{t}Warehouses{/t}</span> <img onmouseover="ddMenu('one',1)" onmouseout="ddMenu('one',-1)" src="art/icons/abajo_white.png"/></dt>
  <dd id="one-ddcontent" onmouseover="cancelHide('one')" onmouseout="ddMenu('one',-1)">
    <ul>
      <li><a href="warehouse.php?id={$warehouse_id}" class="parent">{$warehouse_name}</a></li>
      {foreach from=$tree_list item=warehouse_area }
      <li><a href="warehouse_area.php?id={$warehouse_area.id}" class="underline">{$warehouse_area.code}</a></li>
      {/foreach}
    </ul>
  </dd>
</dl>

{/if}


<span class="nav2 onleft"><a {if $sub_parent=='areas'}class="selected"{/if} href="warehouse_areas.php">{t}Areas{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='shelfs'}class="selected"{/if} href="shelfs.php">{t}Shelfs{/t}</a></span>
<span class="nav2 onleft"><a {if $sub_parent=='locations'}class="selected"{/if} href="locations.php">{t}Locations{/t}</a></span>
{if $view_parts}<span {if $sub_parent=='parts'}class="selected"{/if} class="nav2 onleft"><a href="parts.php">{t}Parts{/t}</a></span>{/if}



<div class="search_box">
  <div class="general_options">
    {foreach from=$general_options_list item=options }
    {if $options.tipo=="url"}
    <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
    {else}
    <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
    {/if}
    {/foreach}
  </div>
  
  <div id="search" style="text-align:right;{if !$search_scope}display:none{/if}">
    <span class="search_title" >{t}{$search_label}{/t}:</span>
    <input size="25" class="text search" id="{$search_scope}_search" value="" name="search"/><img align="absbottom" id="{$search_scope}_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search">
    <div id="{$search_scope}_search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="{$search_scope}_search_results" style="display:none;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;left:-520px">
	<table id="{$search_scope}_search_results_table"></table>
      </div>
    </div>
  </div>
  
</div>  