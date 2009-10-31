{include file='header.tpl'}
<div id="bd" >

 

 
  <span class="nav2 onright" ><a href="warehouse.php?id={$warehouse->id}">{t}Exit Edit{/t}</a></span>


  <div class="search_box" >
  <div style="padding:0 0 5px 0">
 <a style="padding-left:20px"  class="state_details" href="new_warehouse_area.php?warehouse_id={$warehouse->id}">{t}Add Area{/t}</a>
 <a style="padding-left:20px"  class="state_details" href="new_location.php?warehouse_id={$warehouse->id}">{t}Add Location{/t}</a>

</div>

   
  </div>

  <div style="clear:left;margin:0 0px">
    <h1>{t}Warehouse{/t}</h1>
  </div>
 <div>
 
   <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='areas'}selected{/if}"  id="areas">  <span> {t}Areas{/t}</span></span></li>
    <li> <span class="item {if $edit=='locations'}selected{/if}"  id="locations">  <span> {t}Location{/t}</span></span></li> 
  </ul>
  
   <div class="tabbed_container" > 
   <div>
   
     <div style="float:right">
	<span class="save" style="display:none" id="description_save" onclick="save('description')">{t}Save{/t}</span>
	<span id="description_reset"  style="display:none"   class="undo" onclick="reset('description')">{t}Cancel{/t}</span>
      </div>
	
      <table style="margin:0;" class="edit" border=0>
	<tr><td class="label">{t}Warehouse Code{/t}:</td><td>
	      <input  
		 id="code" 
		 onKeyUp="changed(this)" 
		 onMouseUp="changed(this)"  
		 onChange="changed(this)"  
		 name="code" 
		 changed=0 
		 type='text' 
		 class='text' 
		 style="width:15em" 
		 MAXLENGTH="16" 
		 value="{$warehouse->get('Warehouse Code')}" 
		 ovalue="{$warehouse->get('Warehouse Code')}"  
		 />
	    </td>
	  </tr>
	  <tr><td class="label">{t}Warehouse Name{/t}:</td><td>
	      <input   
		 id="name" 
		 onKeyUp="changed(this)"    
		 onMouseUp="changed(this)"  
		 onChange="changed(this)"  
		 name="name" 
		 changed=0 
		 type='text'  
		 MAXLENGTH="255" 
		 style="width:30em"  
		 class='text' 
		 value="{$warehouse->get('Warehouse Name')}"  
		     ovalue="{$warehouse->get('Warehouse Name')}"  
		 />
	    </td>
	  </tr>
	</table>
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
