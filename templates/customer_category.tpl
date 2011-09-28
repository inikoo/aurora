{include file='header.tpl'}
<div id="bd" >
  {include file='contacts_navigation.tpl'} 

 <input type="hidden" id="category_key" value="{$category->id}" />
 <div> 
  <span   class="branch">{if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a  href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; <a  href="customer_categories.php?store_id={$store->id}&id=0">{t}Categories{/t}</a> &rarr;  {$category->get_smarty_tree('customer_categories.php')}</span>
</div>
 

  
<div style="clear:left;">
  <h1>{t}Category{/t}: {$category->get('Category Label')}</h1>
</div>


{$category->get('Category Number Children')}
<div class="data_table" style="{if $category->get('Category Children')==0}display:none;{/if}clear:both;margin-bottom:20px">
    <span class="clean_table_title">Subcategories</span>

    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name0 filter_value=$filter_value0  }

       <div  id="table1"   class="data_table_container dtable btable "> </div>		
</div>



  <div id="children_table" class="data_table" style="{if $category->get('Category Deep')==1}display:none;{/if}clear:both;margin-top:0px">
      <span class="clean_table_title">{t}Customers in this category{/t}</span>
      
  
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
  <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	  <td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='contact'}class="selected"{/if}  id="contact"  >{t}Contact{/t}</td>
	  <td {if $view=='address'}class="selected"{/if}  id="address"  >{t}Address{/t}</td>
	  <td {if $view=='balance'}class="selected"{/if}  id="balance"  >{t}Balance{/t}</td>
	  <td {if $view=='rank'}class="selected"{/if}  id="rank"  >{t}Ranking{/t}</td>
	</tr>
      </table>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name1 filter_value=$filter_value1  }
 <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable "> </div>
 </div>

  
</div> 
{include file='footer.tpl'}
{include file='new_category_splinter.tpl'}

