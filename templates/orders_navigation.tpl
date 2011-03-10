{if $warehouse_list_length>1}
<dl class="dropdown">
  <dt id="one-ddheader" onmouseover="ddMenu('one',1)" onmouseout="ddMenu('one',-1)" onclick="window.location='warehouse_orders.php'" >{t}Warehouse Operations{/t}</dt>
  <dd id="one-ddcontent" onmouseover="cancelHide('one')" onmouseout="ddMenu('one',-1)">
    <ul>
      {foreach from=$warehouse_list item=warehouse }
      <li><a href="warehouse_orders.php?warehouse_id={$warehouse.id}" class="underline">{$warehouse.code}</a></li>
      {/foreach}
    </ul>
  </dd>
</dl>
{else}
<span class="nav2 onleft"><a {if $view=='warehouse_orders'}class="selected"{/if} href="warehouse_orders.php">{t}Warehouse Operations{/t}</a></span>
{/if}
 <span class="nav2 onleft"><span   id="orders" {if $view=='orders'}class="selected"{/if} >{t}Orders{/t}</span></span>
  <span class="nav2 onleft"><span id="invoices" {if $view=='invoices'}class="selected"{/if} >{t}Invoices{/t}</span></span>
  <span class="nav2 onleft"><span  id="dn"  {if $view=='dn'}class="selected"{/if} >{t}Delivery Notes{/t}</span></span>



<table class="search"  border=0 style="{if $search_label==''}display:none{/if}">
<tr>
<td class="label" style="" >{t}Search{/t}:</td>
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


<div style="clear:both;margin-top:0px;margin-right:0px;width:{if $options_box_width}{$options_box_width}{else}700px{/if};float:right;margin-bottom:10px" class="right_box">
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