{include file='header.tpl'}
<div id="bd" >
{include file='locations_navigation.tpl'}


<div style="clear:both">
  <h1 style="padding:10px 0 0 0 ;font-size:140%"><span style="font-weight:800">{t}Warehouse{/t} {$warehouse->get('Warehouse Name')}</span></h1>
 
</div>

 
 
 
 <div   id="block_plot" style="clear:both;{if $display.plot==0}display:none{/if};margin-top:20px;min-height:420px"  >
   {include file='plot_splinter.tpl'}
 </div>
 
 
 
 
 <div id="block_stock_history" class="data_table" style="clear:both;">
    <span   class="clean_table_title">{t}Part Stock History{/t}</span>
     <div >
          <span id="stock_history_type_day" style="float:right" class="table_type state_details {if $stock_history_type=='day'}selected{/if}">{t}Monthly{/t}</span>

     <span id="stock_history_type_week" style="float:right;margin-right:10px" class="table_type state_details {if $stock_history_type=='week'}selected{/if}">{t}Weekly{/t}</span>
     <span id="stock_history_type_day" style="float:right;margin-right:10px" class="table_type state_details {if $stock_history_type=='day'}selected{/if}">{t}Daily{/t}</span>

     </div>
    
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
   
 
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0   no_filter=1   }
    <div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
<div  id="table0"  style="font-size:85%"   class="data_table_container dtable btable "> </div>
</div>
 
 
 
 
  <div id="block_stock_transaction" class="data_table" style="clear:both;margin-top:20px">
    <span   class="clean_table_title">{t}Part Stock Transactions{/t}</span>
    
    
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
   
 
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1   }
    <div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
<div  style="font-size:85%"  id="table1"   class="data_table_container dtable btable "> </div>
</div>
 
 













</div>
</div>




</div>{include file='footer.tpl'}
{include file='stock_splinter.tpl'}

<div id="rppmenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu1" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu1" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

