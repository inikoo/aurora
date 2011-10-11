{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" style="padding:0">
<div style="padding:0 20px">
<input type="hidden" id="location_key" value="{$location->id}" />
{include file='locations_navigation.tpl'}
<div style="clear:left;"> 
  <span class="branch">{if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="inventory.php?id={$location->get('Location Warehouse Key')}">{$location->get('Warehouse Name')} {t}Inventory{/t}</a>  &rarr; <a  href="warehouse_area.php?id={$location->get('Location Warehouse Area Key')}">{$location->get('Warehouse Area Name')} {t}Area{/t}</a> {if $location->get('Location Shelf Key')} &rarr; <a  href="shelf.php?id={$location->get('Location Shelf Key')}">{t}Shelf{/t} {$location->get('Shelf Code')}</a>{/if} &rarr; {$location->get('Location Code')}</span>
</div>

 <div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>{t}Location{/t}: {$location->get('Location Code')} </h1>
  </div>
</div>



<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='details'}selected{/if}"  id="details">  <span> {t}Details{/t}</span></span></li>
    <li> <span class="item {if $view=='parts'}selected{/if}"  id="parts">  <span> {t}Parts{/t}</span></span></li>
    <li> <span class="item {if $view=='history'}selected{/if}"  id="history">  <span> {t}Stock History{/t}</span></span></li>
</ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>


<div id="block_details" style="{if $view!='details'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
<div style="width:400px">
 <table class="show_info_product">
      <tr><td>{t}Location{/t}:</td><td style="font-weight:800">{$location->get('Location Code')}</td></tr>
      <tr><td>{t}Used for{/t}:</td><td>{$location->get('Location Mainly Used For')}</td></tr>
      <tr><td>{t}Max Capacity{/t}:</td><td>{$location->get('Location Max Volume')}</td></tr>
      <tr><td>{t}Max Weight{/t}:</td><td>{$location->get('Location Max Weight')}</td></tr>
      <tr><td>{t}Max Slots{/t}:</td><td>{$location->get('Location Max Slots')}</td></tr>
    </table>
</div>
<div id="plot" style="{if !$show_details}display:none;{/if}"></div>
</div>     

<div id="block_parts" style="{if $view!='parts'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


 
  


  
  
    <div id="the_table" class="data_table" style="clear:both;margin-top:0px">
      <span class="clean_table_title">{t}Parts{/t}</span>
      
 
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
  {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
  <div  id="table1" style="font-size:90%"  class="data_table_container dtable btable "> </div>
</div>

</div>

<div id="block_history" style="{if $view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

  
  <span class="clean_table_title">{t}History{/t}</span>
   <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
  {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
  <div  id="table0" style="font-size:90%"  class="data_table_container dtable btable "> </div>





</div>




      


   
    
</div>


<div id="dialog_add_part" style="padding:10px 20px">

    <div id="manage_stock" >
	<div id="manage_stock_messages" style="padding:5px 5px">{t}Choose the part the you want to place in this location{/t}</div>
	
	<div id="manage_stock_locations" style="display:none">
	  <input id="new_location_input" type="text">
	  <div id="new_location_container"></div>
	</div>
	
	
	<div id="manage_stock_products" style="width:300px;xdisplay:none;margin-bottom:30px;margin-left:2px;">
	  <input id="new_product_input" type="text">
	  <div id="new_product_container">
	    
	  </div>
	</div>
	
	<div id="manage_stock_engine"></div>
      </div>
  
    
      <div id="product_messages" style="display:none;clear:both"></div>
 

</div>



{include file='footer.tpl'}

{include file='stock_splinter.tpl'}

<div id="dialog_part_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none;width:650px">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Parts{/t}</span>
            {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
            <div  id="table2"   class="data_table_container dtable btable "> </div>
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

<div id="filtermenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="rppmenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu2" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="rppmenu2" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},2)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

 
 
