{include file='header.tpl'}
<div id="bd" >
 {include file='assets_navigation.tpl'}
 <div style="clear:left;"> 
 <span class="branch" ><a  href="store.php?id={$store->id}">{$store->get('Store Name')}</a>&rarr; {$department->get('Product Department Name')}</span>
 </div>
 <div id="no_details_title" style="clear:both;{if $show_details}display:none;{/if}">
    <h1>Department: {$department->get('Product Department Name')} ({$department->get('Product Department Code')})</h1>
  </div> 

<div id="info" style="margin:10px 0;padding:0;{if !$show_details}display:none;{/if}">

<h2 style="margin:0;padding:0">{t}Department Information{/t}:</h2>
<div style="width:350px;float:left">
  <table  class="show_info_product">

    <tr >
      <td>{t}Code{/t}:</td><td class="price">{$department->get('Product Department Code')}</td>
    </tr>
    <tr >
      <td>{t}Name{/t}:</td><td>{$department->get('Product Department Name')}</td>
    </tr>
   </table>
    <table    class="show_info_product">
    <tr>
	    <td>{t}Families{/t}:</td><td class="number"><div>{$department->get('Families')}</div></td>
	  </tr>
	  <tr>
	    <td>{t}Products{/t}:</td><td class="number"><div>{$department->get('For Sale Products')}</div></td>
	  </tr>
 
     <tr >
      <td>{t}Web Page{/t}:</td><td>{$department->get('Web Page Links')}</td>
    </tr>

  </table>
</div>
<div style="width:15em;float:left;margin-left:20px">

<table    class="show_info_product">
      <tr >
      <td colspan="2" class="aright" style="padding-right:10px"> <span class="product_info_sales_options" id="info_period"><span id="info_title">{$store_period_title}</span></span>
      <img id="info_previous" class="previous_button" style="cursor:pointer" src="art/icons/previous.png" alt="<"  title="previous" /> <img id="info_next" class="next_button" style="cursor:pointer"  src="art/icons/next.png" alt=">" tite="next"/></td>
    </tr>
       <tbody id="info_all" style="{if $store_period!='all'}display:none{/if}">
	 <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$department->get('Total Customers')}</td>
	</tr>
	 	<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$department->get('Total Invoices')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$department->get('Total Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$department->get('Total Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$department->get('Total Quantity Delivered')}</td>
	</tr>


      </tbody>

      <tbody id="info_year"  style="{if $store_period!='year'}display:none{/if}">
      	<tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$department->get('1 Year Acc Customers')}</td>
	</tr>
		<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$department->get('1 Year Acc Invoices')}</td>
	</tr>

	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$department->get('1 Year Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$department->get('1 Year Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$department->get('1 Year Acc Quantity Delivered')}</td>
	</tr>

      </tbody>
        <tbody id="info_quarter" style="{if $store_period!='quarter'}display:none{/if}"  >
        <tr >
	     <td>{t}Orders{/t}:</td><td class="aright">{$department->get('1 Quarter Acc Invoices')}</td>
	    </tr>
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$department->get('1 Quarter Acc Customers')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$department->get('1 Quarter Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$department->get('1 Quarter Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$department->get('1 Quarter Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
        <tbody id="info_month" style="{if $store_period!='month'}display:none{/if}"  >
        <tr >
	     <td>{t}Orders{/t}:</td><td class="aright">{$department->get('1 Month Acc Invoices')}</td>
	    </tr>
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$department->get('1 Month Acc Customers')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$department->get('1 Month Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$department->get('1 Month Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$department->get('1 Month Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
       <tbody id="info_week" style="{if $store_period!='week'}display:none{/if}"  >
        <tr >
	     <td>{t}Orders{/t}:</td><td class="aright">{$department->get('1 Week Acc Invoices')}</td>
	    </tr>
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$department->get('1 Week Acc Customers')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$department->get('1 Week Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$department->get('1 Week Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$department->get('1 Week Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
 </table>
</div>

</div>


<div id="plot" class="top_bar" style="position:relative;left:-20px;clear:both;padding:0;margin:0;{if !$show_details}display:none;{/if}">

      
      
      
      <div display="none" id="plot_info"   style="border:none;padding:0;margin0;"    keys="{$department->id}" ></div>
      <ul id="plot_chooser" class="tabs" style="margin:0 20px;padding:0 20px "  >
	<li>
	  <span class="item {if $plot_tipo=='department'}selected{/if}" onClick="change_plot(this)" id="plot_department" tipo="department" category="{$plot_data.department.category}" period="{$plot_data.department.period}" >
	    <span>{t}Department Sales{/t}</span>
	  </span>
	</li>
	<li>
	  <span class="item {if $plot_tipo=='top_families'}selected{/if}"  id="plot_top_families" onClick="change_plot(this)" tipo="top_families" category="{$plot_data.top_families.category}" period="{$plot_data.top_families.period}" name=""  >
	    <span>{t}Top Families{/t}</span>
	  </span>
	</li>
	<li>
	  <span class="item {if $plot_tipo=='pie'}selected{/if}" onClick="change_plot(this)" id="plot_pie" tipo="pie"   category="{$plot_data.pie.category}" period="{$plot_data.pie.period}" forecast="{$plot_data.pie.forecast}" date="{$plot_data.pie.date}"  >
	    <span>{t}Family Pie{/t}</span>
	  </span>
	</li>
      </ul> 
       <ul id="plot_options" class="tabs" style="{if $plot_tipo=='pie'}display:none{/if};position:relative;top:.6em;float:right;margin:0 20px;padding:0 20px;font-size:90% "  >
	<li><span class="item"> <span id="plot_category" category="{$plot_category}" style="xborder:1px solid black;display:inline-block; vertical-align:middle">{$plot_formated_category}</span></span></li>
	<li><span class="item"> <span id="plot_period"   period="{$plot_period}" style="xborder:1px solid black;display:inline-block; vertical-align:middle">{$plot_formated_period}</span></span></li>
      </ul> 


      <div style="clear:both;margin:0 20px;padding:0 20px ;border-bottom:1px solid #999">
      </div>

      <div id="pie_options"  style="{if $plot_tipo!='pie'}display:none;{/if}border:1px solid #ddd;float:right;margin:20px 0px;margin-right:40px;width:300px;padding:10px">
	<table id="pie_category_options" style="float:none;margin-bottom:10px;margin-left:30px"  class="options_mini" >
	  <tr>
	    <td  {if $plot_data.pie.category=='sales'}class="selected"{/if} period="sales"  id="pie_category_sales" >{t}Sales{/t}</td>
	    <td {if $plot_data.pie.category=='profit'}class="selected"{/if}  period="profit"  id="pie_category_profit"  >{t}Profit{/t}</td>
	  </tr>
	</table>
	<table id="pie_period_options" style="float:none;margin-bottom:20px;margin-left:30px"  class="options_mini" >
	  <tr>
	    <td  {if $plot_data.pie.period=='all'}class="selected"{/if} period="all"  id="pie_period_all" onclick="change_plot_period('all')" >{t}All{/t}</td>
	    <td {if $plot_data.pie.period=='y'}class="selected"{/if}  period="year"  id="pie_period_year" onclick="change_plot_period('y')"  >{t}Year{/t}</td>
	    <td  {if $plot_data.pie.period=='q'}class="selected"{/if}  period="quarter"  id="pie_period_quarter" onclick="change_plot_period('q')"  >{t}Quarter{/t}</td>
	    <td {if $plot_data.pie.period =='m'}class="selected"{/if}  period="month"  id="pie_period_month" onclick="change_plot_period('m')"  >{t}Month{/t}</td>
	    <td  {if $plot_data.pie.period=='w'}class="selected"{/if} period="week"  id="pie_period_week" onclick="change_plot_period('w')"  >{t}Week{/t}</td>
	  </tr>
	</table>
		<div style="font-size:90%;margin-left:30px">
	  <span>{$plot_formated_period}</span>: <input class="text" type="text" value="{$plot_formated_date}" style="width:6em"/> <img style="display:none" src="art/icons/chart_pie.png" alt="{t}update{/t}"/>
	</div>
      </div>

	<div  id="plot_div" class="product_plot"  style="width:885px;xheight:325px;">
	  <iframe id="the_plot" src ="{$plot_page}?{$plot_args}" frameborder=0 height="325" scrolling="no" width="{if $plot_tipo=='pie'}500px{else}100%{/if}"></iframe>
	</div>
	

     
     </div>
   
 
 
  
<div class="data_table" style="clear:both;">
  <span id="table_title" class="clean_table_title">{t}Families{/t}</span>
  
    <div id="table_type">
     <span id="table_type_list" style="float:right" class="table_type state_details {if $table_type=='list'}selected{/if}">{t}List{/t}</span>
     <span id="table_type_thumbnail" style="float:right;margin-right:10px" class="table_type state_details {if $table_type=='thumbnails'}selected{/if}">{t}Thumbnails{/t}</span>
     </div>
     
     
  
  
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
  
      <div id="list_options0"> 

  
  <span   style="float:right;margin-left:40px" class="state_details" state="{$show_percentages}"  id="show_percentages"  atitle="{if $show_percentages}{t}Normal Mode{/t}{else}{t}Comparison Mode{/t}{/if}"  >{if $show_percentages}{t}Comparison Mode{/t}{else}{t}Normal Mode{/t}{/if}</span>
  <span   style="float:right;margin-left:20px" class="state_details"  id="restrictions_show_for_sale"   >{t}For Sale{/t} ({$department->get('For Public For Sale Families')})</span>
  <span   style="float:right;margin-left:20px" class="state_details"  id="restrictions_show_discontinued"   >{t}Discontinued{/t} ({$department->get('For Public Discontinued Families')})</span>
  <span   style="float:right;margin-left:20px" class="state_details"  id="restrictions_show_all"   >{t}All{/t} ({$department->get('Families')})</span>


  <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
    <tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
      {if $view_stock}<td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>{/if}
      {if $view_sales}<td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>{/if}
    </tr>
  </table>
  
  <table id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
    <tr>
      
      <td  {if $period=='all'}class="selected"{/if} period="all"  id="period_all" >{t}All{/t} </td>
      <td {if $period=='year'}class="selected"{/if}  period="year"  id="period_year"  >{t}1Yr{/t}</td>
      <td  {if $period=='quarter'}class="selected"{/if}  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
      <td {if $period=='month'}class="selected"{/if}  period="month"  id="period_month"  >{t}1M{/t}</td>
      <td  {if $period=='week'}class="selected"{/if} period="week"  id="period_week"  >{t}1W{/t}</td>
    </tr>
  </table>
	
  <table  id="avg_options" style="float:left;margin:0 0 0 25px ;padding:0 {if $view!='sales'};display:none{/if}"  class="options_mini" >
    <tr>
      <td {if $avg=='totals'}class="selected"{/if} avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
      <td {if $avg=='month'}class="selected"{/if}  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
      <td {if $avg=='week'}class="selected"{/if}  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>
      
    </tr>
  </table>
  </div>
  <div  class="clean_table_caption"  style="clear:both;">
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}

	 <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
       </div>
  

      <div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;{if $table_type!='thumbnails'}display:none{/if}"></div>

  <div  id="table0"  style="{if $table_type=='thumbnails'}display:none{/if}"  class="data_table_container dtable btable with_total"> </div>
</div>

</div> 

<div id="plot_period_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Plot frequency{/t}:</li>
      {foreach from=$plot_period_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_period('{$menu.period}')"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="plot_category_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Plot Type{/t}:</li>
      {foreach from=$plot_category_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_category('{$menu.category}')"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="info_period_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Period{/t}:</li>
      {foreach from=$info_period_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_info_period('{$menu.period}','{$menu.title}')"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
{include file='footer.tpl'}

