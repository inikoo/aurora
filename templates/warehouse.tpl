{include file='header.tpl'}
<div id="bd" >
{include file='locations_navigation.tpl'}

 <div style="clear:left;margin:0 0px">
    <h1>{t}Warehouse{/t}: {$warehouse->get('Warehouse Name')} ({$warehouse->get('Warehouse Code')})</h1>
  </div>


      

  <div   style="display:none;border:1px solid #ccc;text-align:left;margin:0px;padding:20px;height:270px;width:600px;margin: 0 0 10px 0;float:left">
    <img   src="_warehouse.png" name="printable_map" />
    
    
   
  </div>
  

 <div id="the_table1" class="data_table" style="margin:0px 0px;clear:both">
    <span class="clean_table_title">{t}Warehouse Areas{/t}</span>
{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
    <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>


  



  <div id="the_table0" class="data_table" style="margin:20px 0px;clear:both">
    <span class="clean_table_title">{t}Locations{/t}</span>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable "> </div>
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
