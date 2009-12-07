<span class="nav2 onleft"><a href="suppliers.php">{t}Suppliers{/t}</a></span>
<span class="nav2 onleft"><a href=porders.php">{t}Purchase Orders{/t}</a></span>
<span class="nav2 onleft"><a href="supplier_products.php">{t}Suppliers Products{/t}</a></span>

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
    <span class="search_title" >{t}Supplier Product Code{/t}:</span>
    <input size="8" class="text search" id="supplier_product_search" value="" name="search"/><img align="absbottom" id="product_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
    <span  class="search_msg"   id="product_search_msg"    ></span> <span  class="search_sugestion"   id="product_search_sugestion" ></span>
  </div>
</div>
