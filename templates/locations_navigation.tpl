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
  <dt id="one-ddheader" onmouseover="ddMenu('one',1)" onmouseout="ddMenu('one',-1)" onclick="window.location='warehouse.php?id={$warehouse_id}'" >{t}Departments{/t}</dt>
  <dd id="one-ddcontent" onmouseover="cancelHide('one')" onmouseout="ddMenu('one',-1)">
    <ul>
      {foreach from=$tree_list item=warehouse_area }
      <li><a href="warehouse_area.php?id={$warehouse_area.id}" class="underline">{$warehouse_area.code}</a></li>
      {/foreach}
    </ul>
  </dd>
</dl>

{/if}
<span class="nav2 onleft"><a href="locations.php">{t}Locations{/t}</a></span>
<span class="nav2 onleft"><a href="warehouse_areas.php">{t}Areas{/t}</a></span>
{if $view_parts}<span class="nav2 onleft"><a href="parts.php">{t}Parts{/t}</a></span>{/if}



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
  <div id="search">
    <span class="search_title" >{t}Locations{/t}:</span>
    <input size="8" class="text search" id="location_search" value="" name="search"/><img align="absbottom" id="location_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
    <span  class="search_msg"   id="location_search_msg"    ></span> <span  class="search_sugestion"   id="location_search_sugestion" ></span>
  </div>
</div>