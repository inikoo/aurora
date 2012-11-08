{include file='header.tpl' }
<div id="bd" style="padding:0px" >
<div style="padding:0 20px">
 {include file='orders_navigation.tpl'}
  <h1>Orders Lists ({$store->get('Store Code')})</h1>


<input type="hidden" id="store_id" value="{$store->id}"/>
<input type="hidden" id="block_view" value="{$block_view}"/>


</div>
 <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $block_view=='orders'}selected{/if}"  id="orders">  <span> {t}Orders{/t}</span></span></li>
    <li> <span class="item {if $block_view=='invoices'}selected{/if}"  id="invoices">  <span> {t}Invoices{/t}</span></span></li>
    <li> <span class="item {if $block_view=='dn'}selected{/if}"  id="dn">  <span> {t}Delivery Notes{/t}</span></span></li>
 
  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px;padding-bottom:30px">


  <div  id="block_orders" class="data_table" style="{if $block_view!='orders'}display:none{/if};clear:both;padding-top:15px;">
    <span class="clean_table_title">{t}Orders Lists{/t}</span>
    

     
    
     <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
      
    
      

    </div>
    
    
   
     {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

    <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable"> </div>
  
  
  </div>
  
  
   <div  id="block_invoices"   class="data_table" style="{if $block_view!='invoices'}display:none{/if};clear:both;padding-top:15px;">
    <span class="clean_table_title">{t}Invoice List{/t}</span>
     <div id="table_type" class="table_type">

       
     </div>



    <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
   
 
    
    </div>
    
    
    
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

   
    
    <div  id="table1"   class="data_table_container dtable btable"> </div>
 
</div>

 <div   id="block_dn"  class="data_table" style="{if $block_view!='dn'}display:none{/if};clear:both;padding-top:15px;">
    <span class="clean_table_title">{t}Delivery Note List{/t}</span>
    
      
    <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
      <div >
   
</div>
  
    
    </div>

    
    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }

    
   
    <div  id="table2"   class="data_table_container dtable btable"> </div>
 

  
  
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
