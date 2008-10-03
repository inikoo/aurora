{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
    <div class="yui-b">
      
      {if $thisweek_orders>0}
      <fieldset class="prodinfo" style="width:100%">
	<legend>{t}Orders Resume{/t}</legend>
	<h2>{$f_date}</h2>
	<table >
	     <tr><td>{t}Orders{/t}:<br>{$thisweek_orders}</td><td class="aright">{$orders}</td><td>{t}Last Year Orders{/t}:<br>{$thisweek_orders}</td><td class="aright">{$orders}</td></tr>
	     <tr><td>{t}Orders Value{/t}:</td><td>{$thisweek_orders_value}</td><td>{t}Last Year Orders Value{/t}:</td><td>{$thisweek_orders_value}</td></tr>
	     
	     
	</table>
      </fieldset>
      {/if}

      {if $view_orders} 
      <div class="data_table" style="margin-top:25px">
      {include file='table.tpl' table_id=0 table_title=$t_title0 filter=$filter filter_name=$filter_name}
      <div  id="table0"   class="data_table_container dtable btable "> </div>
      </div>
      
      {/if}
    </div>
  </div>
   <div class="yui-b" style="text-align:right">

   </div>

</div>
{include file='footer.tpl'}

