<input type='hidden' id="supplier_id" value="{$supplier_id}">

<span id="search_no_results" style="display:none">{t}No results found, try te a more comprensive search{/t} <a style="font-weight:800" href="search_suppliers.php{if $store_key}?supplier={$store_key}{/if}">{t}here{/t}</a>.</span>


<table class="search"  border=0 style="{if $search_label==''}display:none{/if}">
<tr>
<td class="label">{$search_label}:</td>
<td class="form" >
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

