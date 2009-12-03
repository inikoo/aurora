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
 <span class="nav2 onleft"><a class="selected"  href="companies.php">{t}Companies{/t}</a></span>
<span class="nav2 onleft"><a    href="contacts.php">{t}Personal Contacts{/t}</a></span>
<span class="nav2 onleft"><a    href="requests.php">{t}Requests{/t}</a></span>
<span class="nav2 onleft"><a    href="marketing.php">{t}Marketing{/t}</a></span>


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
    <span class="search_title" >{t}Contacts{/t}:</span>
    <input size="8" class="text search" id="product_search" value="" name="search"/><img align="absbottom" id="product_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
    <span  class="search_msg"   id="product_search_msg"    ></span> <span  class="search_sugestion"   id="product_search_sugestion" ></span>
  </div>
</div>  


