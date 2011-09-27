{include file='header.tpl'}
<div id="bd" >

{include file='contacts_navigation.tpl'}
<div> 
  <span   class="branch">{if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a  href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Lists{/t}</span>
</div>

<h2 style="clear:left">{t}Customers Lists{/t} ({$store->get('Store Code')})</h2>

<input type="hidden" id="store_id" value="{$store->id}">

    
    <div id="the_table" class="data_table" style="margin-top:20px;clear:both;display:none" >
    <span class="clean_table_title">Customers List</span>
 <div id="table_type">
         <a  style="float:right"  class="table_type state_details"  href="customers_lists_csv.php" >{t}Export (CSV){/t}</a>

     </div>


  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>

   

</div>


      
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=true }
     	<div  id="table0"   class="data_table_container dtable btable "> </div>
      </div>

    

<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>



 </div>

{include file='footer.tpl'}
