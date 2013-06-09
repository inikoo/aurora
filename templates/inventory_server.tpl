{include file='header.tpl'}
<div id="bd" >
<input type='hidden' id="warehouse_id" value="{$warehouse_id}">
<span id="search_no_results" style="display:none">{t}No results found, try te a more comprensive search{/t} <a style="font-weight:800" href="search_inventory.php{if $warehouse_id}?warehouse={$warehouse_id}{/if}">{t}here{/t}</a>.</span>
<div class="branch"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}{$warehouse->get('Warehouse Name')} {t}Inventory{/t}</span>
</div>


<h1 style="margin-bottom:10px">{t}Inventory{/t}</h1>



<div class="col" style="width:430px">
<table class="search"  border=0 style="margin-top:5px">
<tr>
<td class="form" >
<div id="search" class="asearch_container"  style=";float:left;">
  <input style="width:300px" class="search" id="locations_search" value="" state="" name="search"/>
      <img style="position:relative;left:305px" align="absbottom" id="locations_clean_search" class="submitsearch" src="art/icons/zoom.png">

    <div id="locations_search_Container" style="display:none"></div>
</div>    
  
</td></tr>
</table>  
<div id="locations_search_results" style="font-size:10px;float:right;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;top:-500px">
<table id="locations_search_results_table"></table>
</div>

<h2>{t}Locations{/t}</h2>

<div style="text-align:center;padding:20px">
<span onClick="location.href='warehouse.php?id={$warehouse_id}'" class="button state_details">{t}Locations List{/t}</span>
</div>

</div>	
<div  class="col" style="width:430px">


<table class="search"  border=0 style="margin-top:5px">
<tr>
<td class="form" >
<div id="search" class="asearch_container"  style=";float:left;">
  <input style="width:300px" class="search" id="parts_search" value="" state="" name="search"/>
      <img style="position:relative;left:305px" align="absbottom" id="parts_clean_search" class="submitsearch" src="art/icons/zoom.png">

    <div id="parts_search_Container" style="display:none"></div>
</div>    
  
</td></tr>
</table>  
<div id="parts_search_results" style="font-size:10px;float:right;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;top:-500px">
<table id="parts_search_results_table"></table>
</div>
<h2>{t}Parts{/t}</h2>
<div style="text-align:center;padding:20px">
<span onClick="location.href='inventory.php?id={$warehouse_id}'" class="button state_details">{t}Parts List{/t}</span>
</div>
</div>

<div  class="col" style="margin-top:30px;width:430px">

<h2>{t}Orders{/t}</h2>
<div style="text-align:center;padding:20px">
<span onClick="location.href='warehouse_orders.php?id={$warehouse_id}'" class="button state_details">{t}Pending Orders{/t}</span>
</div>
</div>


</div>

{include file='footer.tpl'}


