{if $store_list_length>1}
<dl class="dropdown">
  <dt id="one-ddheader" onmouseover="ddMenu('one',1)" onmouseout="ddMenu('one',-1)" onclick="window.location='stores.php'" >{t}Stores{/t}</dt>
  <dd id="one-ddcontent" onmouseover="cancelHide('one')" onmouseout="ddMenu('one',-1)">
    <ul>
      {foreach from=$tree_list item=store }
      <li><a href="store.php?id={$store.id}" class="underline">{$store.code}</a></li>
      {/foreach}
    </ul>
  </dd>
</dl>
{else}
<dl class="dropdown">
  <dt id="one-ddheader" onmouseover="ddMenu('one',1)" onmouseout="ddMenu('one',-1)" onclick="window.location='store.php?id={$store_id}'" >{t}Departments{/t}</dt>
  <dd id="one-ddcontent" onmouseover="cancelHide('one')" onmouseout="ddMenu('one',-1)">
    <ul>
      {foreach from=$tree_list item=department }
      <li><a href="department.php?id={$department.id}" class="underline">{$department.code}</a></li>
      {/foreach}
    </ul>
  </dd>
</dl>

{/if}
 <span class="nav2 onleft"><a href="families.php">{t}Families{/t}</a></span>
 <span class="nav2 onleft"><a href="products.php?parent=none">{t}Products{/t}</a></span>
  <span class="nav2 onleft"><a href="deals.php">{t}Deals{/t}</a></span>

 <span class="nav2 onleft"><a href="categories.php">{t}Categories{/t}</a></span>
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
  
  <div id="search" style="text-align:right;{if !$search_scope}display:none{/if}">
    <span class="search_title" >{t}{$search_label}{/t}:</span>
    <input size="25" class="text search" id="{$search_scope}_search" value="" state="" name="search"/><img align="absbottom" id="{$search_scope}_clean_search"  class="submitsearch" src="art/icons/zoom.png" >
    <div id="{$search_scope}_search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="{$search_scope}_search_results" style="display:none;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;left:-520px">
	<table id="{$search_scope}_search_results_table"></table>
      </div>
    </div>
  </div>
  
</div>  


