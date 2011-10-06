{include file='header.tpl'}
<div id="bd" >
 {include file='suppliers_navigation.tpl'}
 
 <div> 
 <span  class="branch"><a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a  href="supplier_categories.php?id=0">{t}Supplier Categories{/t}</a> &rarr; {$category->get_smarty_tree('supplier_categories.php')}
 </div> 
  
<div style="clear:left;">
  <h1>{t}Category{/t}: {$category->get('Category Name')}</h1>
</div>



<div class="data_table" style="clear:both">
    <span class="clean_table_title">Subcategories</span>

 <span   style="float:right;margin-left:20px" class="state_details"  id="change_stores_mode" >{$display_stores_mode_label}</span>
 <span   style="float:right;margin-left:20px" class="state_details"  id="change_stores" >{$display_stores_label}</span>
 <span   style="float:right;margin-left:20px" class="state_details"  id="change_display_mode" >{$display_mode_label}</span>

    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

       <div  id="table0"   class="data_table_container dtable btable "> </div>		
</div>

{*
<div class="data_table" style="clear:both;margin-top:20px">
    <span class="clean_table_title">{t}Products{/t}</span>
 <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
 <span   style="float:right;margin-left:80px" class="state_details"  id="change_display_mode" >{$display_mode_label}</span>
    <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  {if $view_stock}<td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>{/if}
	</tr>
      </table>
    <table id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>

	  <td  {if $period=='all'}class="selected"{/if} period="all"  id="period_all" >{t}All{/t}</td>
	  <td {if $period=='year'}class="selected"{/if}  period="year"  id="period_year"  >{t}1Yr{/t}</td>
	  <td  {if $period=='quarter'}class="selected"{/if}  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
	  <td {if $period=='month'}class="selected"{/if}  period="month"  id="period_month"  >{t}1M{/t}</td>
	  <td  {if $period=='week'}class="selected"{/if} period="week"  id="period_week"  >{t}1W{/t}</td>
	</tr>
      </table>
    <table  id="avg_options" style="float:left;margin:0 0 0 20px ;padding:0 {if $view!='sales'};display:none{/if}"  class="options_mini" >
	<tr>
	  <td {if $avg=='totals'}class="selected"{/if} avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	  <td {if $avg=='month'}class="selected"{/if}  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	  <td {if $avg=='week'}class="selected"{/if}  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>

	</tr>
      </table>
       
        {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

       <div  id="table1"   class="data_table_container dtable btable with_total"> </div>		
</div>
 *}
</div> 
{include file='footer.tpl'}
{include file='new_category_splinter.tpl'}

