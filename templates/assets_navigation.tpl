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
  <div id="search">
    <span class="search_title" >{t}Products{/t}:</span>
    <input size="8" class="text search" id="product_search" value="" name="search"/><img align="absbottom" id="product_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
    <span  class="search_msg"   id="product_search_msg"    ></span> <span  class="search_sugestion"   id="product_search_sugestion" ></span>
  </div>
</div>  


