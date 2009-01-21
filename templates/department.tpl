{include file='header.tpl'}
<div id="bd" >

{if $next.id>0}<span class="nav2 onright"><a href="department.php?id={$next.id}">{$next.code} &rarr; </a></span>{/if}
{if $prev.id>0}<span class="nav2 onright" ><a href="department.php?id={$prev.id}">&larr; {$prev.code}</a></span>{/if}
  <span class="nav2 onright" style="margin-left:20px"><a href="departments.php">&uarr; {t}All Departments{/t}</a></span>
  <span class="nav2 on left"><a href="departments.php">{t}Departments{/t}</a></span>
  <span class="nav2 onleft"><a href="categories.php">{t}Categories{/t}</a></span>
  <span class="nav2 onleft"><a href="products.php">{t}Product index{/t}</a></span>

  <div class="search_box" >
    <span class="search_title">{t}Product Code{/t}:</span> <input size="8" class="text search" id="product_search" value="" name="search"/><img align="absbottom" id="product_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
     <span  class="search_msg"   id="product_search_msg"    ></span> <span  class="search_sugestion"   id="product_search_sugestion"    ></span>
     <br/>
   <span  class="state_details" state="{$show_details}"  id="show_details"  atitle="{if $show_details}{t}show details{/t}{else}{t}hide details{/t}{/if}"  >{if $show_details}{t}hide details{/t}{else}{t}show details{/t}{/if}</span>
  </div>
  
  
  <div id="top" class="top_bar">
    <h1>{$department}</h1>
    <div id="short_menu" class="nodetails" style="width:100%;margin-bottom:0px">
      <table style="float:left;margin:0 0 0 20px ;padding:0"  class="options" {if $products==0 }style="display:none"{/if}>
	<tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  {if $view_stock}<td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>{/if}
	</tr>
      </table>

    </div>
    
    <div id="details" class="details" style="{if !$show_details}display:none;{/if}">
      <div id="details_general"  {if $view!='general'}style="display:none"{/if}>
	<table>
	  <tr>
	    <td>{t}Number of Products{/t}:</td><td class="aright">{$products}</td>
	</tr>
	  <tr>
	    <td>{t}Number of Families{/t}:</td><td class="aright">{$families}</td>
	  </tr>
	  <tr>
	    <td>{t}Number of Departments{/t}:</td><td class="aright">{$departments}</td>
	  </tr>
	</table>
      </div>
      <div id="details_stock"  {if $view!='stock'}style="display:none"{/if}>
	<table   >
	  <tr>
	    <td>{t}Stock Value{/t}:</td><td class="aright">{$stock_value}</td>
	  </tr>
	</table>
      </div>
      <div id="details_sales"  {if $view!='sales'}style="display:none"{/if}>
	<table  >
	  <tr>
	    <td>{t}Total Sales{/t}:</td><td class="aright">{$total_sales}</td>
	  </tr>
	</table>
      </div>
    </div>

  </div>
  
  <div class="data_table" style="clear:both;margin:25px 20px">
	<span id="table_title" class="clean_table_title">{t}{$table_title}{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div id="table_info0" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg0"></span></div></div>

	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator">{t}Showing all families{/t}</span></div></div>
	</div>
	<div  id="table0"   class="data_table_container dtable btable "> </div>
      </div>

</div> 


{include file='footer.tpl'}
