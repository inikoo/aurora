{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}


   
      <h2 style="clear:both">{t}Customers Lists{/t}</h2>


    
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
 <div id="dialog_new_list" style="padding:10px">
 <imput id="direct_store_key" value="{$direct_store_key}" type="hidden"/>
  <div id="new_customer_msg"></div>
  {t}Choose Store{/t}:
    <div id="store_options"   class="options" style="margin:5px 0">
     {foreach from=$store_options item=store_option key=store_key name=foo3}
     <span  class="catbox {if $store_option.selected}selected{/if}"  onclick="new_list({$store_key})"   >{$store_option.code}</span>
     {/foreach}
    </div>
  <span  class="unselectable_text state_details" onClick="close_dialog_new_list()" >{t}Cancel{/t}</span>
</div>
{include file='footer.tpl'}
