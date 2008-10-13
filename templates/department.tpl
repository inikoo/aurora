{include file='header.tpl'}
<div id="bd" >

  {if $next.id>0}<span class="nav2 onright"><a href="department.php?id={$next.id}">{t}Next{/t}</a></span>{/if}
  {if $prev.id>0}<span class="nav2 onright" ><a href="department.php?id={$prev.id}">{t}Previous{/t}</a></span>{/if}
  <span class="nav2 onright" style="margin-left:20px"><a href="departments.php">{t}Up{/t}</a></span>
  <span class="nav2 onright"><a href="products.php">{t}Product index{/t}</a></span>
  <span class="nav2"><a href="departments.php">{$home}</a></span>
  
  
 <div id="top" class="top_bar">
  <div style="float:right;padding:10px 40px ">
	<form  id="prod_search_form" action="products.php" method="GET" >
	  {t}Product Code{/t}
	  <input size="8" class="text search" id="prod_search" value="" name="search"/><img onclick="document.getElementById('prod_search_form').submit()"align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search">
	</form>
      </div>
        <h1>{$department}</h1>

   <div id="short_menu" class="nodetails" style="{if $show_details}display:none;{/if}width:100%;margin-bottom:0px">
     <table style="float:left;margin:0 0 0 20px ;padding:0"  class="options" {if $products==0 }style="display:none"{/if}>
	<tr><td  {if $view_table=='general'}class="selected"{/if} id="general_view1" >{t}General{/t}</td>
	  {if $view_stock}<td {if $view_table=='stock'}class="selected"{/if}  id="stock_view1"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $view_table=='sales'}class="selected"{/if}  id="sales_view1"  >{t}Sales{/t}</td>{/if}
	  <td style="padding:0;font-weight:100;color:#777;padding:0 0 0 5px;cursor:default;;border:none"><span  class="state_details"  id="show_details">{t}show details{/t}</span></td>
	</tr>
      </table>
    </div>

    <div id="details" class="details" style="{if !$show_details}display:none;{/if}">
      <div style="float:left;">
	<h2 style="padding:0;margin:0">{t}Products by department{/t}</h2>
	

	<table id="otable0" style="font-size:90%;margin-top:10px" {if $view_table!=0} style="display:none"{/if}   >
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
	{if $view_stock}
	   <table id="otable1" {if $view_table!=1} style="display:none"{/if}    >
	     <tr>
	       <td>{t}Stock Value{/t}:</td><td class="aright">{$stock_value}</td>
	     </tr>
	     
	   </table>
	   {/if}
	    {if $view_sales}
	   <table id="otable2"    {if $view_table!=2} style="display:none"{/if}   >
	     <tr>
	       <td>{t}Total Sales{/t}:</td><td class="aright">{$total_sales}</td>
	     </tr>
	     
	   </table>
	   {/if}
      </div>
      <div style="text-align:right;float:right">
	  {t}Product Code{/t}
	<form  style="margin:0 0 25px 0" id="prod_search_form2" action="products.php" method="GET" >

	  <input size="8" class="text search" id="prod_search" value="" name="search"/><img onclick="document.getElementById('prod_search_form').submit()"align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search">
	</form>
	<span   class="state_details"  id="hide_details">{t}hide details{/t}</span>
      </div>
      <div style="text-align:right;float:right">
	{if $view_sales or $view_stock}
	<table class="options" {if $products==0 }style="display:none"{/if}>
	  <tr><td  {if $view_table=='general'}class="selected"{/if} id="general_view2" >{t}General{/t}</td></tr>
	  {if $view_stock}<tr><td {if $view_table=='stock'}class="selected"{/if}  id="stock_view2"  >{t}Stock{/t}</td>{/if}</tr>
	  {if $view_sales}<tr><td  {if $view_table=='sales'}class="selected"{/if}  id="sales_view2"  >{t}Sales{/t}</td>{/if}</tr>
	</table>

	{/if}
</div>
	       <div style="text-align:right;float:right">

	
      </div>
      <div style="clear:both"></div>
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
