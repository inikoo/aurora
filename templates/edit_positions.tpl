{include file='header.tpl'}
<div id="bd" >

{include file='hr_navigation.tpl'}




 <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>{t}Editing Company Positions{/t}</h1>
  </div>

 
   <ul class="tabs" id="chooser_ul" style="clear:both">
      <li> <span class="item {if $edit=='positions'}selected{/if}"  id="positions">  <span> {t}Company Positions{/t}</span></span></li>
   
    </ul>
  
  <div class="tabbed_container"> 
 
  
   <div  class="edit_block" style="{if $edit!="positions"}display:none{/if}"  id="d_positions">
 
   <div class="general_options" style="float:right">
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_position" class="state_details disabled">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_position" class="state_details">{t}Close Dialog{/t}</span>
		<span   style="margin-right:10px"  id="add_position" class="state_details" >Create Position</span>

      </div>
      <div  class="new_item_dialog"  id="new_position_dialog" style="display:none"  >
      <div id="new_position_messages" class="messages_block"></div>
	  <table class="edit">
	   
	   
	   
	   
	   <tr class="first"><td style="width:11em" class="label">Area:</td>
	  <td  style="text-align:left;width:19em">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Company_Area" value="" ovalue="" >
	      <div id="Company_Area_Container" style="" ></div>
	    </div>
	  </td>
	  <td id="Company_Area_Key_msg" class="edit_td_alert"></td>
	  	      <input type='hidden' id="Company_Area_Key" value="" ovalue="" >

	</tr>
	   
	     <tr class="first"><td style="width:11em" class="label">Department:</td>
	  <td  style="text-align:left;width:19em">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Company_Department" value="" ovalue="" >
	      <div id="Company_Department_Container" style="" ></div>
	    </div>
	  </td>
	  <td id="Company_Department_Key_msg" class="edit_td_alert"></td>
	  	      <input type='hidden' id="Company_Department_Key" value="" ovalue="" >

	</tr>
	   
	   
	<tr class="first"><td style="width:11em" class="label">Position Code:</td>
	  <td  style="text-align:left;width:19em">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Position_Code" value="" ovalue="" >
	      <div id="Position_Code_Container" style="" ></div>
	    </div>
	  </td>
	  <td id="Position_Code_msg" class="edit_td_alert"></td>
	</tr>
	<tr class="first"><td style="" class="label">{t}Position Name{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Position_Name" value="" ovalue="">
	      <div id="Position_Name_Container" style="" ></div>
	    </div>
	  </td>
	  	  <td id="Position_Name_msg" class="edit_td_alert"></td>

	</tr>
	
	  </table>
	  </div>
 
 <div class="data_table" style="clear:both">
   <span class="clean_table_title">{t}Position List{/t}</span>
  
  
  <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
   
   
   </div>   
   
   
   </div>
 
 
 
 
  <div id="the_table1" class="data_table" style=" clear:both">
  <span class="clean_table_title">{t}History{/t}</span>
  <div  id="clean_table_caption1" class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div id="clean_table_filter1" class="clean_table_filter" style="display:none">
      <div class="clean_table_info"><span id="filter_name1">{$filter_name}</span>: <input style="border-bottom:none" id='f_input1' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
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
