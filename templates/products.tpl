{include file='header.tpl'}
<div id="bd" >
  <span class="nav2 onright"><a href="departments.php">{t}Departments{/t}</a></span>
  <span class="nav2 onright"><a href="categories.php">{t}Categories{/t}</a></span>
  
  <div class="search_box" >
    <form  id="prod_search_form" action="products.php" method="GET" >
      <span class="search_title">{t}Product Code{/t}:</span> <input size="8" class="text search" id="prod_search" value="" name="search"/><img align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search">
    </form>
    <span  class="state_details"  id="show_details">{t}show details{/t}</span>
  </div>

  <div id="top" class="top_bar">
    <div id="short_menu" class="nodetails" style="{if $show_details}display:none;{/if}width:100%;margin-bottom:0px">
      <table style="float:left;margin:0 0 0 20px ;padding:0"  class="options" {if $products==0 }style="display:none"{/if}>
	<tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  {if $view_stock}<td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>{/if}
	</tr>
      </table>
    </div>
    
    <div id="details" class="details" style="{if !$show_details}display:none;{/if}">
      <div id="details_general"  {if $view!='general'}style="display:none"{/if}></div> 
    </div>
  </div>


  <div class="data_table" style="clear:both;margin:0px 20px">
	<span id="table_title" class="clean_table_title">{t}{$table_title}{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div id="table_info0" class="clean_table_info"> <span class="filter_msg"  id="filter_msg0"></span></div></div>

	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
	</div>
	<div  id="table0"   class="data_table_container dtable btable "> </div>
      </div>

</div> 



{include file='footer.tpl'}
