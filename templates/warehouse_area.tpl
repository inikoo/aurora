{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0 20px">
{include file='locations_navigation.tpl'}
 <div style="clear:left;"> 
 <span class="branch" >{t}Warehouse{/t}: <a  href="warehouse.php?id={$warehouse_area->get('Warehouse Area Warehouse Key')}">{$warehouse_area->get('Warehouse Name')}({$warehouse_area->get('Warehouse Code')})</a></span>
 <div style="clear:left;margin:0 0px">
    <h1>{t}Warehouse Area{/t}: {$warehouse_area->get('Warehouse Area Name')} ({$warehouse_area->get('Warehouse Area Code')})</h1>
  </div>
  
</div>  
  </div>


<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='details'}selected{/if}"  id="details">  <span> {t}Details{/t}</span></span></li>
        <li> <span class="item {if $view=='locations'}selected{/if}"  id="locations">  <span> {t}Locations{/t}</span></span></li>

    <li> <span class="item {if $view=='parts'}selected{/if}"  id="parts">  <span> {t}Parts{/t}</span></span></li>
</ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>


<div id="block_details" style="{if $view!='details'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

  
<div   style="display:none;border:1px solid #ccc;text-align:left;margin:0px;padding:20px;height:270px;width:600px;margin: 0 0 10px 0;float:left">
    <img   src="_warehouse.png" name="printable_map" />
  </div>
  
</div>  
<div id="block_locations" style="{if $view!='locations'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

    <span class="clean_table_title">{t}Locations{/t}</span>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
<div id="block_parts" style="{if $view!='parts'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

   

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
