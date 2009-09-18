{include file='header.tpl'}
<div id="bd" >

  <span class="nav2 onleft {if $view=='areas'}{/if}" >{t}Areas{/t}</span>
  <span class="nav2 onleft {if $view=='locations'}{/if}" >{t}Locations{/t}</span>

  <span class="nav2 onright" ><a href="new_location.php?warehouse_id={$warehouse->id}">{t}Add Location{/t}</a></span>
  <span class="nav2 onright" ><a href="new_warehouse_area.php?warehouse_id={$warehouse->id}">{t}Add Area{/t}</a></span>
  <span class="nav2 onright" ><a href="edit_warehouse.php?id={$warehouse->id}">{t}Edit Warehouse{/t}</a></span>


  <div class="search_box" >
    <span class="search_title">{t}Location Code{/t}:</span> <input size="8" class="text search" id="location_search" value="" name="search"/><img align="absbottom" id="location_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
     <span  class="search_msg"   id="location_search_msg"    >
     </span> <span  class="search_sugestion"   id="location_search_sugestion"    ></span>
     
      <br>
     <span  id="show_details" style="float:right;{if $show_details}display:none{/if}" class="state_details"  onClick="show_details()" >{t}Show details{/t}</span><span  id="show_map" style="margin:0 20px;xpadding:0 20px;float:right;{if $show_map}display:none{/if}" class="state_details"  onClick="show_map()" >{t}Show map{/t}</span>
    
  </div>

  <div style="clear:left;margin:0 20px">
    <h1>{t}Warehouse{/t}</h1>
  </div>


      

  <div   style="display:none;border:1px solid #ccc;text-align:left;margin:0px;padding:20px;height:270px;width:600px;margin: 0 0 10px 0;float:left">
    <img   src="_warehouse.png" name="printable_map" />
    
    
    <div class="list_of_buttons">
      {foreach from=$areas item=area  }
      <div id="area{$area.id}" class="" onClick="choose_area({$area.id})">{$area.name}</div>
      {/foreach}
    </div>
  </div>
  
  <div id="the_table" class="data_table" style="margin:0px 20px;clear:both">
    <span class="clean_table_title">{t}{$table_title}{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>


    </div>
  </div>


<div id="filtermenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

{include file='footer.tpl'}
