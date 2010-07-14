{if $store_list_length>1}
<dl class="dropdown">
  <dt id="one-ddheader" onmouseover="ddMenu('one',1)" onmouseout="ddMenu('one',-1)" onclick="window.location='stores.php'" >{t}Customers{/t}</dt>
  <dd id="one-ddcontent" onmouseover="cancelHide('one')" onmouseout="ddMenu('one',-1)">
    <ul>
      {foreach from=$tree_list item=store }
      <li><a href="customers.php?store={$store.id}" class="underline">{$store.code}</a></li>
      {/foreach}
    </ul>
  </dd>
</dl>
{else}
<span class="nav2 onleft"><a href="customers.php">{t}Customers{/t}</a></span>
{/if}
 <span style="display:none" class="nav2 onleft"><a {if $nav_parent=='companies'}class="selected"{/if}  href="companies.php">{t}Companies{/t}</a></span>
<span  style="display:none"  class="nav2 onleft"><a  {if $nav_parent=='contacts'}class="selected"{/if}   href="contacts.php">{t}Personal Contacts{/t}</a></span>
<span class="nav2 onleft"><a  {if $nav_parent=='requests'}class="selected"{/if}   href="requests.php">{t}Requests{/t}</a></span>
<span class="nav2 onleft"><a  {if $nav_parent=='marketing'}class="selected"{/if}   href="marketing.php">{t}Marketing{/t}</a></span>



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

<div class="search_box">
 
  <div id="search" style="text-align:right;{if !$search_scope}display:none{/if}">
    <span class="search_title" >{t}{$search_label}{/t}:</span>
    <input size="25" class="text search" id="{$search_scope}_search" value="" name="search"/><img align="absbottom" id="{$search_scope}_clean_search" class="submitsearch" src="art/icons/zoom.png" alt="Clear search">
    <div id="{$search_scope}_search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="{$search_scope}_search_results" style="display:none;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;left:-520px">
	<table id="{$search_scope}_search_results_table"></table>
      </div>
    </div>
  </div>
  
</div>  


