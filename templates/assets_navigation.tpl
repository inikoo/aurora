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
{/if}



{if $store_key}

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
 <span class="nav2 onleft"><a href="families.php?store_id={$store_key}">{t}Families{/t}</a></span>
 <span class="nav2 onleft"><a href="products.php?store_id={$store_key}">{t}Products{/t}</a></span>
  <span class="nav2 onleft"><a href="deals.php?store_id={$store_key}">{t}Deals{/t}</a></span>
 <span class="nav2 onleft"><a href="product_categories.php?store_id={$store_key}id=0">{t}Products Categories{/t}</a></span>

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
{if $search_scope}
<table class="search"  border=0>
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
{/if}
