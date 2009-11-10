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