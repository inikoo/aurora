{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">
{include file='locations_navigation.tpl'}
<input type="hidden" value="{$warehouse->id}" id="warehouse_id"/>
<div class="branch"> 
  <span >{if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="warehouse_parts.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a> &rarr; Part Movements</span>
</div>
<div class="top_page_menu">
    <div class="buttons" style="float:right">
       
       
    </div>
    <div class="buttons" style="float:left">
         <button  onclick="window.location='parts_movements.php?id={$warehouse->id}'" ><img src="art/icons/house.png" alt=""> {t}Inventory{/t}</button>
	 </div>
    <div style="clear:both"></div>
</div>



 <div style="clear:left;margin:0 0px">
    <h1>{t}Warehouse{/t}: {$warehouse->get('Warehouse Name')} ({$warehouse->get('Warehouse Code')})</h1>
  </div>

</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">

    <li> <span class="item {if $view=='movements'}selected{/if}"  id="locations">  <span> {t}Movements{/t}</span></span></li>
 
  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>


<div id="block_movements" style="{if $view!='movements'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

 <div id="the_table0" class="data_table" style="margin:20px 0px;clear:both">
    <span class="clean_table_title">{t}Part Movements{/t}</span>

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
