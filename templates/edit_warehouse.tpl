{include file='header.tpl'}
<div id="bd" >

<div class="search_box" style="margin-top:15px">
  <div class="general_options">
    {foreach from=$general_options_list item=options }
        {if $options.tipo=="url"}
            <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
        {else}
            <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
        {/if}
    {/foreach}
    </div>
</div>
<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Warehouse{/t}: <span id="title_name">{$warehouse->get('Warehouse Name')}</span> (<span id="title_code">{$warehouse->get('Warehouse Code')}</span>)</h1>
</div>
<ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='areas'}selected{/if}"  id="areas">  <span> {t}Areas{/t}</span></span></li>
    <li> <span class="item {if $edit=='locations'}selected{/if}"  id="locations">  <span> {t}Location{/t}</span></span></li> 
  </ul>
<div class="tabbed_container" > 
   <div id="description_block" style="{if $edit!='description'}display:none{/if}" >
   
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
   <div id="areas_block" style="{if $edit!='areas'}display:none{/if}" >
    <div class="general_options" style="float:right">
      <span   style="margin-right:10px"  id="add_area_here" class="state_details" >Add Area</span>
       <span  style="margin-right:10px;display:none"  id="save_area" class="state_details">{t}Save{/t}</span>
      <span style="margin-right:10px;display:none" id="close_add_area" class="state_details">{t}Close Dialog{/t}</span>
      
    </div>
    
     <div id="new_warehouse_area_messages" style="float:left;padding:5px;border:1px solid #ddd;width:400px;margin-bottom:15px;display:none">
      <table class="edit" ">
    	<tr><td class="label">{t}Warehouse{/t}:</td><td><span style="font-weight:800">{$warehouse->get('Warehouse Name')}</span><input type="hidden" id="warehouse_key" ovalue="{$warehouse->id}" value="{$warehouse->id}"></td></tr>
	    <tr><td class="label">{t}Area Code{/t}:</td><td><input  id="area_code" ovalue=""  type="text"/></td></tr>
	    <tr><td class="label">{t}Area Name{/t}:</td><td><input  id="area_name" ovalue=""  type="text"/></td></tr>
	    <tr><td class="label">{t}Area Description{/t}:</td><td><textarea ovalue="" id="area_description"></textarea></td></tr>
       </table>  
     
    </div>
     <div id="new_warehouse_area_block" style="font-size:80%;float:left;padding:10px 15px;border:1px solid #ddd;width:200px;margin-bottom:15px;margin-left:10px;display:none">Messages
     </div>
    
    
    
 <div id="the_table1" class="data_table" style="margin:0px 0px;clear:left;">
    <span class="clean_table_title">{t}Warehouse Areas{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter1"><div class="clean_table_info"><span id="filter_name1">{$filter_name1}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value1}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
    </div>
    <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>
  </div>
   <div id="locations_block" style="{if $edit!='locations'}display:none{/if}" >
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
