{include file='header.tpl'}
<div id="bd" >

{include file='hr_navigation.tpl'}




 <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>{t}Editing Company Department{/t}:</h1>
  </div>

 
   <ul class="tabs" id="chooser_ul" style="clear:both">
         <li> <span class="item {if $edit=='company_department'}selected{/if}"  id="details">  <span> {t}Department Details{/t}</span></span></li>

    </ul>
  
  <div class="tabbed_container"> 
 
    <div  class="edit_block" style="{if $edit!="details"}display:none{/if}"  id="d_details">

  <div class="general_options" style="float:right">
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_company_department" onClick="save_edit_general('company_department')" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_company_department" onClick="reset_edit_general('company_department')" class="state_details">{t}Reset{/t}</span>
   </div>
      
      
      
      <div id="new_department_messages" class="messages_block"></div>
	  <table class="edit">
	   
	<tr class="first"><td style="width:11em" class="label">Department Code:</td>
	  <td  style="text-align:left;width:19em">
	    <div  style="width:15em;position:relative;top:00px" >

	      <input style="text-align:left;width:18em" id="Company_Department_Code" value="{$company_department->get('Company Department Code')}" ovalue="{$company_department->get('Company Department Code')}" >
	      <div id="Company_Department_Code_Container" style="" ></div>
	    </div>
	  </td>
	 <td id="Company_Department_Code_msg" class="edit_td_alert"></td>
	</tr>
	<tr class="first"><td style="" class="label">{t}Department Name{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Company_Department_Name" value="{$company_department->get('Company Department Name')}" ovalue="{$company_department->get('Company Department Name')}">
	      <div id="Company_Department_Name_Container" style="" ></div>
	    </div>
	  </td>
	  	 <td id="Company_Department_Name_msg" class="edit_td_alert"></td>

	</tr>
      

        <tr class="first"><td style="" class="label">{t}Department Description{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Company_Department_Description" value="{$company_department->get('Company Department Description')}" ovalue="{$company_department->get('Company Department Description')}">
	      <div id="Company_Department_Description_Container" style="" ></div>
	    </div>
	  </td>
	  	 <td id="Company_Department_Description_msg" class="edit_td_alert"></td>
	</tr>
	
	  </table>
	  
 </div>
  
 </div>
 
 
 
 
  <div id="the_table1" class="data_table" style=" clear:both">
  <span class="clean_table_title">{t}History{/t}</span>
  <div  id="clean_table_caption1" class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div id="clean_table_filter1" class="clean_table_filter" style="display:none">
      <div class="clean_table_info"><span id="filter_name1" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input1' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
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
