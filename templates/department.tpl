{include file='header.tpl'}
<div id="bd" >

  {if $next.id>0}<span class="nav2 onright"><a href="department.php?id={$next.id}">{$next.code} &rarr; </a></span>{/if}
  <span class="nav2 onright" ><a href="store.php?id={$store->id}">&uarr; {t}Up{/t}</a></span>
  {if $prev.id>0}<span class="nav2 onright" ><a href="department.php?id={$prev.id}">&larr; {$prev.code}</a></span>{/if}
  {if $modify}<span class="nav2 onright"><a href="department.php?edit=1"  >{t}Edit{/t}</a></span>{/if}
  
  <span class="nav2 onleft"><a class="selected" href="store.php">{$store->get('Store Code')}</a></span>
  <span class="nav2 onleft"><a href="families.php?store_key={$store->id}">{t}Families{/t}</a></span>
  <span class="nav2 onleft"><a href="products.php?store_key={$store->id}">{t}Products{/t}</a></span>
  <span class="nav2 onleft"><a href="categories.php?store_key={$store->id}">{t}Categories{/t}</a></span>

  <div class="search_box">
    <span class="search_titleitle">{t}Product Code{/t}:</span> <input size="8" class="text search" id="product_search" value="" name="search"/><img align="absbottom" id="product_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
     <span  class="search_msg"   id="product_search_msg"    ></span> <span  class="search_sugestion"   id="product_search_sugestion"    ></span>
     <br/>
     <span id="show_details" style="float:right;{if $show_details}display:none{/if}" class="state_details"  onClick="show_details()" >{t}Show details{/t}</span>
  </div>
  
  <div style="clear:left;margin:0 20px">
    <h1><a  href="store.php?id={$store->id}">{$store->get('Store Code')}</a> &rarr; {$department->get('Product Department Name')}</h1>
  </div>
  
  <div id="details" class="top_bar" style="{if !$show_details}display:none;{/if}">
     <div  class="details">
      <div id="details_general"  {if $view!='general'}style="display:none"{/if}>

	<table class="show_info_product" style="width:20em">
	  <tr>
	    <td>{t}Product Families{/t}:</td><td class="aright">{$department->get('Families')}</td>
	  </tr>
	  <tr>
	    <td>{t}Products for sale{/t}:</td><td class="aright">{$department->get('For Sale Products')}</td>
	  </tr>
	</table>
      </div>
    
      
      <div display="none" id="plot_info" period="month" args="&keys={$department->id}"  ></div>
      <ul id="plot_chooser" class="tabs" style="margin:0 20px;padding:0 20px "  >
	<li><span class="item {if $plot_tipo=='department_sales'}selected{/if}" onClick="change_plot(this)" tipo="department_sales"   ><span>{t}Depatment Sales{/t}</span></span></li>
	<li><span class="item {if $plot_tipo=='top_families_sales'}selected{/if}" onClick="change_plot(this)" tipo="top_families_sales"  ><span>{t}Top Families{/t}</span></span></li>
	<li><span class="item {if $plot_tipo=='pie_family_share'}selected{/if}" onClick="change_plot(this)" tipo="pie_family_share"   ><span>{t}Family's Pie{/t}</span></span></li>
      </ul> 
      
      <div style="clear:both;margin:0 20px;padding:0 20px ;border-bottom:1px solid #999">
      </div>

      <div id="pie_options"  style="{if $plot_tipo!='pie_department_share'}display:none;{/if}border:1px solid #ddd;float:right;margin:20px 0px;margin-right:40px;width:300px;padding:10px">
	<table id="pie_period_options" style="float:none;margin-bottom:20px;margin-left:30px"  class="options_mini" >
	  <tr>
	    
	    <td  {if $pie_period=='all'}class="selected"{/if} period="all"  id="pie_period_all" >{t}All{/t}</td>
	    <td {if $pie_period=='year'}class="selected"{/if}  period="year"  id="pie_period_year"  >{t}Year{/t}</td>
	    <td  {if $pie_period=='quarter'}class="selected"{/if}  period="quarter"  id="pie_period_quarter"  >{t}Quarter{/t}</td>
	    <td {if $pie_period=='month'}class="selected"{/if}  period="month"  id="pie_period_month"  >{t}Month{/t}</td>
	    <td  {if $pie_period=='week'}class="selected"{/if} period="week"  id="pie_period_week"  >{t}Week{/t}</td>
	</tr>
      </table>
	<div style="font-size:90%;margin-left:30px">
	<span>{$pie_period_label}</span>: <input class="text" type="text" value="{$pie_date}" style="width:6em"/> <img src="art/icons/chart_pie.png" alt="{t}update{/t}"/>
	</div>
      </div>
      <div  id="plot_div" class="product_plot"  style="width:865px;xheight:325px;">
	<iframe id="the_plot" src ="{$plot_src}" frameborder=0 height="325" scrolling="no" width="{if $plot_tipo=='pie_department_share'}500px{else}100%{/if}"></iframe>
	
      </div>
      <div style="text-align:right">
	<span  class="state_details"  onClick="hide_details()"> {t}Hide details{/t}</span>
      </div>
     </div>
   
    <div style="clear:both"></div>
  </div>
  
 
  
  <div class="data_table" style="clear:both;margin:25px 20px">
	<span id="table_title" class="clean_table_title">{t}Families{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	  <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
	</div>
	<div  id="table0"   class="data_table_container dtable btable "> </div>
      </div>

</div> 


{include file='footer.tpl'}
