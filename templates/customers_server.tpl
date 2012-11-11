{include file='header.tpl'}
<div id="bd" style="padding:0px">
 <div style="padding:0 20px">
{include file='contacts_navigation.tpl'}
<div class="branch"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  {t}Customers{/t}</span>
</div>
<div class="top_page_menu" >
<div class="buttons" style="float:right">
<button style="display:none" onclick="window.location='customers_server_stats.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button>

</div>
<div class="buttons" style="float:left">
<span class="main_title">{t}Customers{/t} ({t}All Stores{/t})</span>
</div>
<div style="clear:both"></div>
</div>
</div>


<div style="padding:0px">
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
    <li> <span class="item {if $type=='contacts_with_orders'}selected{/if}"  id="contacts_with_orders">  <span> {t}Contacts with Orders{/t}</span></span></li>
    <li> <span class="item {if $type=='all_contacts'}selected{/if}"  id="all_contacts">  <span> {t}All Contacts{/t}</span></span></li>

  </ul>

<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
</div>


<div style="padding:0 20px">

<div id="block_contacts_with_orders" style="clear:both;margin:10px 0 40px 0">

    <span class="clean_table_title">{t}Customers per Store{/t} <img style="display:none" id="export_csv0"   tipo="customers_per_store" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 

<div class="table_top_bar" style="margin-bottom:10px"></div>
 
       
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  no_filter=1} 
<div  id="table0"   class="data_table_container dtable btable with_total"> </div>		

</div>
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
{include file='footer.tpl'}
