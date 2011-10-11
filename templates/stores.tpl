{include file='header.tpl'}

<div id="bd" style="padding:0px">

<div style="padding:0 20px">

{include file='assets_navigation.tpl'}
<div  style="clear:left;"> 
  <span class="branch">{t}Stores{/t}</span>
</div>

</div>


<div style="padding:0px">
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li style="display:none"> <span class="item {if $block_view=='details'}selected{/if}"  id="details">  <span> {t}Details{/t}</span></span></li>
    <li> <span class="item {if $block_view=='stores'}selected{/if}"  id="stores">  <span> {t}Stores{/t}</span></span></li>
    <li> <span class="item {if $block_view=='departments'}selected{/if}"  id="departments">  <span> {t}Departments{/t}</span></span></li>
    <li> <span class="item {if $block_view=='families'}selected{/if}"  id="families">  <span> {t}Families{/t}</span></span></li>
    <li> <span class="item {if $block_view=='products'}selected{/if}" id="products"  ><span>  {t}Products{/t}</span></span></li>
    <li> <span class="item {if $block_view=='deals'}selected{/if}" style="display:none" id="deals">  <span> {t}Offers{/t}</span></span></li>

  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
</div>


<div style="padding:0 20px">

<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>
<div id="block_stores" style="{if $block_view!='stores'}display:none;{/if}clear:both;margin:10px 0 40px 0">

<div class="data_table" style="clear:both">
    <span class="clean_table_title">{t}Stores{/t} <img id="export_csv0"   tipo="stores" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
     
    
    
 <div class="table_top_bar" ></div>
 <span   style="float:right;margin-left:80px" class="state_details"  id="change_display_mode" >{$display_mode_label}</span>
<table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	 <td class="option {if $view=='general'}selected{/if}" id="dgeneral" >{t}Summary{/t}</td>
	    <td class="option {if $view=='stock'}selected{/if}"  id="stock" {if !$view_stock}style="display:none"{/if} >{t}Stock{/t}</td>
	    <td class="option {if $view=='sales'}selected{/if}" id="sales" {if !$view_sales}style="display:none"{/if} >{t}Sales{/t}</td>
	
	</tr>
      </table>
        <table id="stores_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>

	  <td class="option {if $period=='all'}selected{/if}" period="all"  id="period_all" >{t}All{/t}</td>
	  <td class="option {if $period=='three_year'}selected{/if}"  period="three_year"  id="period_three_year"  >{t}3Y{/t}</td>
	  <td class="option {if $period=='year'}selected{/if}"  period="year"  id="period_year"  >{t}1Yr{/t}</td>
	  <td class="option {if $period=='yeartoday'}selected{/if}"  period="yeartoday"  id="period_yeartoday"  >{t}YTD{/t}</td>	
	  <td class="option {if $period=='six_month'}selected{/if}"  period="six_month"  id="period_six_month"  >{t}6M{/t}</td>
	  <td class="option {if $period=='quarter'}selected{/if}"  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
	  <td class="option {if $period=='month'}selected{/if}"  period="month"  id="period_month"  >{t}1M{/t}</td>
	  <td class="option {if $period=='ten_day'}selected{/if}"  period="ten_day"  id="period_ten_day"  >{t}10D{/t}</td>
	  <td class="option {if $period=='week'}selected{/if}" period="week"  id="period_week"  >{t}1W{/t}</td>
	</tr>
      </table>


       <table  id="stores_avg_options" style="float:left;margin:0 0 0 20px ;padding:0 {if $view!='sales'};display:none{/if}"  class="options_mini" >
	<tr>
	  <td class="option {if $avg=='totals'}selected{/if}" avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	  <td class="option {if $avg=='month'}selected{/if}"  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	  <td class="option {if $avg=='week'}selected{/if}"  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>

	</tr>
      </table>
       
       <div  class="clean_table_caption"  style="clear:both;">
	 <div style="float:left;">
	   <div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div>
	 </div>
	 <div class="clean_table_filter clean_table_filter_show" id="clean_table_filter_show0" {if $filter_show0}style="display:none"{/if}>{t}filter results{/t}</div>
	 <div class="clean_table_filter" id="clean_table_filter0" {if !$filter_show0}style="display:none"{/if}>
	   <div class="clean_table_info" style="padding-bottom:1px; ">
	     <span id="filter_name0" class="filter_name"  style="margin-right:5px">{$filter_name0}:</span>
	     <input style="border-bottom:none;width:6em;" id='f_input0' value="{$filter_value0}" size=10/> <span class="clean_table_filter_show" id="clean_table_filter_hide0" style="margin-left:8px">{t}Hide filter{/t}</span>
	     <div id='f_container0'></div>
	   </div>
	 </div>
	 <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
       </div>
       
       
       <div  id="table0"   class="data_table_container dtable btable with_total"> </div>		
       
</div>
</div>
<div id="block_departments" style="{if $block_view!='departments'}display:none;{/if}clear:both;margin:10px 0 40px 0">
<div class="data_table" style="clear:both;">
<span  id="export_csv1" style="float:right;margin-left:20px"  class="table_type state_details" tipo="families" >{t}Export (CSV){/t}</span>
    <span   class="clean_table_title" style="">{t}Departments{/t}</span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
    <span   style="float:right;margin-left:80px" class="state_details" state="{$show_percentages}"  id="show_percentages"  atitle="{if $show_percentages}{t}Normal Mode{/t}{else}{t}Comparison Mode{/t}{/if}"  >{if $show_percentages}{t}Comparison Mode{/t}{else}{t}Normal Mode{/t}{/if}</span>
  
  <table style="float:left;margin:0 0 0 0px ;padding:0"  class="department_view_options options" >
	<tr>
	    <td class="option {if $department_view=='general'}selected{/if}" id="department_general" >{t}Summary{/t}</td>
	    <td class="option {if $department_view=='stock'}selected{/if}"  id="department_stock" {if !$view_stock}style="display:none"{/if} >{t}Stock{/t}</td>
	    <td class="option {if $department_view=='sales'}selected{/if}" id="department_sales" {if !$view_sales}style="display:none"{/if} >{t}Sales{/t}</td>
	</tr>
 </table>

    <table id="department_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $department_view!='sales' };display:none{/if}"  class="options_mini" >
	  <tr>

	  <td class="option {if $department_period=='all'}selected{/if}" period="all"  id="department_period_all" >{t}All{/t}</td>
	  <td class="option {if $department_period=='three_year'}selected{/if}"  period="three_year"  id="department_period_three_year"  >{t}3Y{/t}</td>
	  <td class="option {if $department_period=='year'}selected{/if}"  period="year"  id="department_period_year"  >{t}1Yr{/t}</td>
	  <td class="option {if $department_period=='yeartoday'}selected{/if}"  period="yeartoday"  id="department_period_yeartoday"  >{t}YTD{/t}</td>	
	  <td class="option {if $department_period=='six_month'}selected{/if}"  period="six_month"  id="department_period_six_month"  >{t}6M{/t}</td>
	  <td class="option {if $department_period=='quarter'}selected{/if}"  period="quarter"  id="department_period_quarter"  >{t}1Qtr{/t}</td>
	  <td class="option {if $department_period=='month'}selected{/if}"  period="month"  id="department_period_month"  >{t}1M{/t}</td>
	  <td class="option {if $department_period=='ten_day'}selected{/if}"  period="ten_day"  id="department_period_ten_day"  >{t}10D{/t}</td>
	  <td class="option {if $department_period=='week'}selected{/if}" period="week"  id="department_period_week"  >{t}1W{/t}</td>
	  </tr>
      </table>
	<table  id="department_avg_options" style="float:left;margin:0 0 0 25px ;padding:0 {if $department_view!='sales'};display:none{/if}"  class="options_mini" >
	  <tr>
	    <td class="option {if $department_avg=='totals'}selected{/if}" avg="totals"  id="department_avg_totals" >{t}Totals{/t}</td>
	    <td class="option {if $department_avg=='month'}selected{/if}"  avg="month"  id="department_avg_month"  >{t}M AVG{/t}</td>
	    <td class="option {if $department_avg=='week'}selected{/if}"  avg="week"  id="department_avg_week"  >{t}W AVG{/t}</td>
	  </tr>
       </table>
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name0 filter_value=$filter_value1  }
<div  id="table1"   class="data_table_container dtable btable with_total"> </div>
</div>
</div>
<div id="block_families" style="{if $block_view!='families'}display:none;{/if}clear:both;margin:10px 0 40px 0">
   <div class="data_table" style="margin:0px;clear:both">
    <span class="clean_table_title">{t}Families{/t} 
           <img id="export_csv2"   tipo="families_in_department" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif">

    </span>

<div id="table_type" class="table_type">
        <div  style="font-size:90%"   id="transaction_chooser" >
                            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family.NoSale}selected{/if} label_family_products_nosale"  id="elements_family_nosale" table_type="nosale"   >{t}No Sale{/t} (<span id="elements_family_nosale_number">{$elements_family_number.NoSale}</span>)</span>
                <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family.Discontinued}selected{/if} label_family_products_discontinued"  id="elements_family_discontinued" table_type="discontinued"   >{t}Discontinued{/t} (<span id="elements_family_discontinued_number">{$elements_family_number.Discontinued}</span>)</span>
                <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family.Discontinuing}selected{/if} label_family_products_discontinued"  id="elements_family_discontinuing" table_type="discontinuing"   >{t}Discontinuing{/t} (<span id="elements_family_discontinuing_number">{$elements_family_number.Discontinuing}</span>)</span>
                <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_family.Normal}selected{/if} label_family_products_normal"  id="elements_family_normal" table_type="normal"   >{t}For Sale{/t} (<span id="elements_family_notes_number">{$elements_family_number.Normal}</span>)</span>
                <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_family.InProcess}selected{/if} label_family_products_inprocess"  id="elements_family_inprocess" table_type="inprocess"   >{t}In Process{/t} (<span id="elements_family_notes_number">{$elements_family_number.InProcess}</span>)</span>

        </div>
     </div>

<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
<span   style="float:right;margin-left:80px" class="state_details" state="{$family_show_percentages}"  id="show_percentages"  atitle="{if $family_show_percentages}{t}Normal Mode{/t}{else}{t}Comparison Mode{/t}{/if}"  >{if $family_show_percentages}{t}Comparison Mode{/t}{else}{t}Normal Mode{/t}{/if}</span>
 <table style="float:left;margin:0 0 0 0px ;padding:0"  class="family_view_options options" >
	<tr>
	    <td class="option {if $family_view=='general'}selected{/if}" id="family_general" >{t}Summary{/t}</td>
	    <td class="option {if $family_view=='stock'}selected{/if}"  id="family_stock" {if !$view_stock}style="display:none"{/if} >{t}Stock{/t}</td>
	    <td class="option {if $family_view=='sales'}selected{/if}" id="family_sales" {if !$view_sales}style="display:none"{/if} >{t}Sales{/t}</td>
	</tr>
      </table>
        <table id="family_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $family_view!='sales'};display:none{/if}"  class="options_mini" >
	<tr>
	  <td class="option {if $family_period=='all'}selected{/if}" period="all"  id="family_period_all" >{t}All{/t}</td>
	  <td class="option {if $family_period=='three_year'}selected{/if}"  period="three_year"  id="family_period_three_year"  >{t}3Y{/t}</td>
	  <td class="option {if $family_period=='year'}selected{/if}"  period="year"  id="family_period_year"  >{t}1Yr{/t}</td>
	  <td class="option {if $family_period=='yeartoday'}selected{/if}"  period="yeartoday"  id="family_period_yeartoday"  >{t}YTD{/t}</td>	
	  <td class="option {if $family_period=='six_month'}selected{/if}"  period="six_month"  id="family_period_six_month"  >{t}6M{/t}</td>
	  <td class="option {if $family_period=='quarter'}selected{/if}"  period="quarter"  id="family_period_quarter"  >{t}1Qtr{/t}</td>
	  <td class="option {if $family_period=='month'}selected{/if}"  period="month"  id="family_period_month"  >{t}1M{/t}</td>
	  <td class="option {if $family_period=='ten_day'}selected{/if}"  period="ten_day"  id="family_period_ten_day"  >{t}10D{/t}</td>
	  <td class="option {if $family_period=='week'}selected{/if}" period="week"  id="family_period_week"  >{t}1W{/t}</td>
	</tr>
      </table>


       <table  id="family_avg_options" style="float:left;margin:0 0 0 20px ;padding:0 {if $family_view!='sales'};display:none{/if}"  class="options_mini" >
	<tr>
	  <td class="option {if $family_avg=='totals'}selected{/if}" avg="totals"  id="family_avg_totals" >{t}Totals{/t}</td>
	  <td class="option {if $family_avg=='month'}selected{/if}"  avg="month"  id="family_avg_month"  >{t}M AVG{/t}</td>
	  <td class="option {if $family_avg=='week'}selected{/if}"  avg="week"  id="family_avg_week"  >{t}W AVG{/t}</td>

	</tr>
      </table>


    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
    <div  id="table2" class="data_table_container dtable btable with_total"></div>

  </div>
  
</div> 
<div id="block_products" style="{if $block_view!='products'}display:none;{/if}clear:both;margin:10px 0 40px 0">
    <div class="data_table" style="margin:0px;clear:both">
        <span class="clean_table_title">{t}Products{/t} 
          <img id="export_csv3"   tipo="products_in_stores" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif">
          <img id="export_xml3"   tipo="products_in_stores" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (XML){/t}" alt="{t}Export (XML){/t}" src="art/icons/export_xml.gif">

        </span>
           
       <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
        <span style="float:right;margin-left:80px" class="state_details" state="{$product_show_percentages}"  id="show_percentages"  atitle="{if $product_show_percentages}{t}Normal Mode{/t}{else}{t}Comparison Mode{/t}{/if}"  >{if $product_show_percentages}{t}Comparison Mode{/t}{else}{t}Normal Mode{/t}{/if}</span>
        
        <table style="float:left;margin:0 0 0 0px ;padding:0"  class="product_view_options options" >
	<tr>
	    <td class="option {if $product_view=='general'}selected{/if}" id="product_general" >{t}Summary{/t}</td>
	    <td class="option {if $product_view=='stock'}selected{/if}"  id="product_stock" {if !$view_stock}style="display:none"{/if} >{t}Stock{/t}</td>
	    <td class="option {if $product_view=='sales'}selected{/if}" id="product_sales" {if !$view_sales}style="display:none"{/if} >{t}Sales{/t}</td>
	    <td class="option {if $product_view=='parts'}selected{/if}" id="product_parts" {if !$view_sales}style="display:none"{/if} >{t}Parts{/t}</td>
	    <td class="option {if $product_view=='cats'}selected{/if}" id="product_cats" {if !$view_sales}style="display:none"{/if} >{t}Groups{/t}</td>

	</tr>
      </table>
        
       
	    <table id="product_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $product_view!='sales' };display:none{/if}"  class="options_mini" >
	    <tr>
	  <td class="option {if $product_period=='all'}selected{/if}" period="all"  id="product_period_all" >{t}All{/t}</td>
	  <td class="option {if $product_period=='three_year'}selected{/if}"  period="three_year"  id="product_period_three_year"  >{t}3Y{/t}</td>
	  <td class="option {if $product_period=='year'}selected{/if}"  period="year"  id="product_period_year"  >{t}1Yr{/t}</td>
	  <td class="option {if $product_period=='yeartoday'}selected{/if}"  period="yeartoday"  id="product_period_yeartoday"  >{t}YTD{/t}</td>	
	  <td class="option {if $product_period=='six_month'}selected{/if}"  period="six_month"  id="product_period_six_month"  >{t}6M{/t}</td>
	  <td class="option {if $product_period=='quarter'}selected{/if}"  period="quarter"  id="product_period_quarter"  >{t}1Qtr{/t}</td>
	  <td class="option {if $product_period=='month'}selected{/if}"  period="month"  id="product_period_month"  >{t}1M{/t}</td>
	  <td class="option {if $product_period=='ten_day'}selected{/if}"  period="ten_day"  id="product_period_ten_day"  >{t}10D{/t}</td>
	  <td class="option {if $product_period=='week'}selected{/if}" period="week"  id="product_period_week"  >{t}1W{/t}</td>
	    </tr>
        </table>
        <table  id="product_avg_options" style="float:left;margin:0 0 0 20px ;padding:0{if $product_view!='sales' };display:none{/if}"  class="options_mini" >
	    <tr>
	        <td class="option {if $product_avg=='totals'}selected{/if}" avg="totals"  id="product_avg_totals" >{t}Totals{/t}</td>
	        <td class="option {if $product_avg=='month'}selected{/if}"  avg="month"  id="product_avg_month"  >{t}M AVG{/t}</td>
	        <td class="option {if $product_avg=='week'}selected{/if}"  avg="week"  id="product_avg_week"  >{t}W AVG{/t}</td>
	        <td class="option {if $product_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff"  id="product_avg_month_eff"  >{t}M EAVG{/t}</td>
	        <td class="option {if $product_avg=='week_eff'}selected{/if}" style="display:none"  avg="week_eff"  id="product_avg_week_eff"  >{t}W EAVG{/t}</td>
	    </tr>
        </table>
        {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3  }
        <div  id="table3"   class="data_table_container dtable btable "> </div>
    </div>
</div>
<div id="block_deals" style="{if $block_view!='deals'}display:none;{/if}clear:both;margin:20px 0 40px 0"></div>
<div id="block_sites" style="{if $block_view!='sites'}display:none;{/if}clear:both;margin:20px 0 40px 0">

<div class="data_table" style="clear:both;margin-top:25px">
<div class="todo">
<h1>TO DO (KAKTUS-319)</h1>
<h2>List/thumbnails of store websites</h2>
<h3>Objective</h3>
<p>
show code (link to:site.php?id=) ,url, type (created with inikoo,created by others),status (live/offline),
some stats like (# visitors,# unique visitors etc)</br>
</p>
<h3>Files</h3>
<p>
ar fie: ar_sites.php?tipo=list_store_sites<br>
DB: `Site Dimension` (To do: more fields have to be created in the DB, `Site Type`(enum[inikoo,other]),`Site Status`... etc )
</p>
</div>
    <span   class="clean_table_title" style="">{t}Web Sites{/t}</span>
 <div id="table_type">
     <span id="table_type_list" style="float:right" class="table_type state_details {if $table_type=='list'}selected{/if}">{t}List{/t}</span>
     <span id="table_type_thumbnail" style="float:right;margin-right:10px" class="table_type state_details {if $table_type=='thumbnails'}selected{/if}">{t}Thumbnails{/t}</span>
     </div>
   
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
    
   
 {include file='table_splinter.tpl' table_id=4 filter_name=$filter_name1 filter_value=$filter_value4 no_filter=4  }
<div  id="table4"   class="data_table_container dtable btable"> </div>
</div>
</div>

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

<div id="rppmenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu2" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},2)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu2" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu3" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu3 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},3)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu3" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu3 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',3)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


<div id="change_display_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Display Mode Options{/t}:</li>
      {foreach from=$mode_options_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_display_mode('{$menu.mode}','{$menu.label}',0)"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="stores-table-csv_export" export_options=$csv_export_options }

{include file='footer.tpl'}
