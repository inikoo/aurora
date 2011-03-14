{include file='header.tpl'}
<div id="bd" >
<span class="nav2 onleft"><a href="#">{t}Create List{/t}</a></span>

<span class="nav2 onleft"><a href="customers_lists.php">{t}View List{/t}</a></span>
<span class="nav2 onleft"><a href="new_campaign.php">{t}Create Campaign{/t}</a></span>
<span class="nav2 onleft"><a href="campaign_builder.php">{t}View Campaign{/t}</a></span>
{include file='contacts_navigation.tpl'}


   
      <h2 style="clear:both">{t}Customers Lists{/t}</h2>

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
