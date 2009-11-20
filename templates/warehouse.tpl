{include file='header.tpl'}
<div id="bd" >
{include file='locations_navigation.tpl'}

  
 

  <div class="search_box no_top_nav"   >
  
  <div style="height:1.6em">
 <a style="padding-left:20px;float:right"  class="state_details" href="edit_warehouse.php?id={$warehouse->id}">{t}Edit Warehouse{/t}</a>
   <span  id="show_details" style="float:right;{if $show_details}display:none{/if}" class="state_details"  onClick="show_details()" >{t}Show details{/t}</span>
   <span  id="show_map" style="margin:0 20px;xpadding:0 20px;float:right;{if $show_map}display:none{/if}" class="state_details"  onClick="show_map()" >{t}Show map{/t}</span>
  </div>
  
  
    <span class="search_title">{t}Location Code{/t}:</span> <input size="8" class="text search" id="location_search" value="" name="search"/><img align="absbottom" id="location_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
     <span  class="search_msg"   id="location_search_msg"    >
     </span> <span  class="search_sugestion"   id="location_search_sugestion"    ></span>
     
     
  </div>

  <div style="clear:left;margin:0 0px">
    <h1>{t}Warehouse{/t}</h1>
  </div>


      

  <div   style="display:none;border:1px solid #ccc;text-align:left;margin:0px;padding:20px;height:270px;width:600px;margin: 0 0 10px 0;float:left">
    <img   src="_warehouse.png" name="printable_map" />
    
    
   
  </div>
  

 <div id="the_table1" class="data_table" style="margin:0px 0px;clear:both">
    <span class="clean_table_title">{t}Warehouse Areas{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter1"><div class="clean_table_info"><span id="filter_name1">{$filter_name1}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value1}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
    </div>
    <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>


  



  <div id="the_table0" class="data_table" style="margin:20px 0px;clear:both">
    <span class="clean_table_title">{t}Locations{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>


    </div>
  </div>


<div id="filtermenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu0" class="yuimenu">
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
