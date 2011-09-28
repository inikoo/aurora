{include file='header.tpl'}
<div id="bd" >
  {include file='contacts_navigation.tpl'} 
<div> 
  <span   class="branch">{if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a  href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Categories{/t}</span>
</div>

 <div style="clear:left;">
  <h1>{t}Customer Categories Home{/t}</h1>
</div>

 <input type="hidden" id="category_key" value="0" />


<div class="data_table" style="clear:both">
    <span class="clean_table_title">{t}Main Categories{/t}</span>
   
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

       <div  id="table1"   class="data_table_container dtable btable "> </div>		
</div>



  
</div> 
{include file='footer.tpl'}
{include file='new_category_splinter.tpl'}
