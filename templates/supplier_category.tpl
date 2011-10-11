{include file='header.tpl'}
<div id="bd" >
 {include file='suppliers_navigation.tpl'}
 <input id="category_key" type="hidden" value="{$category->id}"/>
 <div> 
 <span  class="branch"><a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a  href="supplier_categories.php?id=0">{t}Supplier Categories{/t}</a> &rarr; {$category->get_smarty_tree('supplier_categories.php')}
 </div> 
  
<div style="clear:left;">
  <h1>{t}Category{/t}: {$category->get('Category Name')}</h1>
</div>



<div class="data_table" style="{if $category->get('Category Children')==0}display:none;{/if}clear:both;margin-bottom:20px">
    <span class="clean_table_title">Subcategories</span>
       <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>

<table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr><td  {if $view=='general'}class="selected"{/if} id="suppliers_general" >{t}General{/t}</td>
	</tr>
      </table>
  <table id="supplier_categories_period_options" style="float:left;margin:0 0 0 20px ;padding:0"  class="options_mini" >
	<tr>
	  <td class="option {if $period=='all'}selected{/if}" period="all"  id="period_all" >{t}All{/t}</td>
	  <td class="option {if $period=='three_year'}selected{/if}"  period="three_year"  id="period_three_year"  >{t}3Y{/t}</td>
	  <td class="option {if $period=='year'}selected{/if}"  period="year"  id="period_year"  >{t}1Yr{/t}</td>
	  <td class="option {if $period=='six_month'}selected{/if}"  period="six_month"  id="period_six_month"  >{t}6M{/t}</td>
	  <td class="option {if $period=='quarter'}selected{/if}"  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
	  <td class="option {if $period=='month'}selected{/if}"  period="month"  id="period_month"  >{t}1M{/t}</td>
	  <td class="option {if $period=='ten_day'}selected{/if}"  period="ten_day"  id="period_ten_day"  >{t}10D{/t}</td>
	  <td class="option {if $period=='week'}selected{/if}" period="week"  id="period_week"  >{t}1W{/t}</td>
	  <td style="visibility:hidden"></td>
	  	  <td  class="option {if $period=='yeartoday'}selected{/if}"  period="yeartoday"  id="period_yeartoday"  >{t}YTD{/t}</td>	
	  	  <td  class="option {if $period=='monthtoday'}selected{/if}"  period="monthtoday"  id="period_monthtoday"  >{t}MTD{/t}</td>	
	  	  <td  class="option {if $period=='weektoday'}selected{/if}"  period="weektoday"  id="period_weektoday"  >{t}WTD{/t}</td>	
	</tr>
      </table>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
 
       <div  id="table0"   class="data_table_container dtable btable "> </div>		
</div>

<div id="children_table" class="data_table" style="{if $category->get('Category Deep')==1}display:none;{/if}clear:both;margin-top:0px">
      <span class="clean_table_title">{t}Suppliers in this category{/t}</span>
      
  
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
   <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr><td  {if $suppliers_view=='general'}class="selected"{/if} id="suppliers_general" >{t}General{/t}</td>
	  <td {if $suppliers_view=='contact'}class="selected"{/if}  id="suppliers_contact"  >{t}Contact{/t}</td>
	
	  <td {if $suppliers_view=='products'}class="selected"{/if}  id="suppliers_products"  >{t}Products{/t}</td>
	  {if $view_stock}<td {if $suppliers_view=='stock'}class="selected"{/if}  id="suppliers_stock"  >{t}Parts Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $suppliers_view=='sales'}class="selected"{/if}  id="suppliers_sales"  >{t}Parts Sales{/t}</td>{/if}
	  {if $view_sales}<td  {if $suppliers_view=='profit'}class="selected"{/if}  id="suppliers_profit"  >{t}Profit{/t}</td>{/if}

	</tr>
      </table>
      <table id="suppliers_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $suppliers_view!='sales' and  $suppliers_view!='profit'};display:none{/if}"  class="options_mini" >
	<tr>
	  <td class="option {if $suppliers_period=='all'}selected{/if}" period="all"  id="suppliers_period_all" >{t}All{/t}</td>
	  <td class="option {if $suppliers_period=='three_year'}selected{/if}"  period="three_year"  id="suppliers_period_three_year"  >{t}3Y{/t}</td>
	  <td class="option {if $suppliers_period=='year'}selected{/if}"  period="year"  id="suppliers_period_year"  >{t}1Yr{/t}</td>
	  <td class="option {if $suppliers_period=='six_month'}selected{/if}"  period="six_month"  id="suppliers_period_six_month"  >{t}6M{/t}</td>
	  <td class="option {if $suppliers_period=='quarter'}selected{/if}"  period="quarter"  id="suppliers_period_quarter"  >{t}1Qtr{/t}</td>
	  <td class="option {if $suppliers_period=='month'}selected{/if}"  period="month"  id="suppliers_period_month"  >{t}1M{/t}</td>
	  <td class="option {if $suppliers_period=='ten_day'}selected{/if}"  period="ten_day"  id="suppliers_period_ten_day"  >{t}10D{/t}</td>
	  <td class="option {if $suppliers_period=='week'}selected{/if}" period="week"  id="suppliers_period_week"  >{t}1W{/t}</td>
	  <td style="visibility:hidden"></td>
	  	  <td  class="option {if $suppliers_period=='yeartoday'}selected{/if}"  period="yeartoday"  id="suppliers_period_yeartoday"  >{t}YTD{/t}</td>	
	  	  <td  class="option {if $suppliers_period=='monthtoday'}selected{/if}"  period="monthtoday"  id="suppliers_period_monthtoday"  >{t}MTD{/t}</td>	
	  	  <td  class="option {if $suppliers_period=='weektoday'}selected{/if}"  period="weektoday"  id="suppliers_period_weektoday"  >{t}WTD{/t}</td>	

	  
	</tr>
      </table>

{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
 <div  id="table1"  style="font-size:90%"  class="data_table_container dtable btable "> </div>
 </div>










</div> 
{include file='footer.tpl'}
{include file='new_category_splinter.tpl'}

