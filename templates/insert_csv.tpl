{include file='header.tpl'}
<div id="bd" >
 {if $scope=='customers_store'}

{include file='contacts_navigation.tpl'}

<div  class="branch"> 

  <span  >{if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a  href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr;  {t}Import Customers{/t} (3/3)</span>
</div>



<div id="top_page_menu" class="top_page_menu">

    <div class="buttons" style="float:left">
        <button  onclick="window.location='customers.php?store={$store->id}'" ><img src="art/icons/house.png" alt=""> {t}Customers{/t}</button>
    </div>
    <div class="buttons" style="float:right">



    </div>
    <div style="clear:both"></div>
</div>
{/if}
<input type="hidden" id="search_type" value="{$search_type}">
<input type="hidden" id="scope" value="{$scope}">
<input type="hidden" id="scope_key" value="{$scope_key}">

    <h1>{t}Import Results{/t}</h1>
  
<table   class="report_sales1"  style="margin-top:20px">
<tr><td>{t}To do records{/t}</td><td id="records_todo"></td><td id="records_todo_comments"></td></tr>
<tr><td>{t}Imported Records{/t}</td><td id="records_imported"></td><td id="records_imported_comments"></td></tr>
<tr><td>{t}Ignored{/t}</td><td id="records_ignored"></td><td id="records_ignored_comments"></td></tr>
<tr><td>{t}Errors{/t}</td><td id="records_error"></td><td id="records_error_comments"></td></tr>

</table>



</div>

{include file='footer.tpl'}
