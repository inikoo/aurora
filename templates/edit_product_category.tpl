{include file='header.tpl'}
<div id="bd" >
 
 
 <div class="branch"> 
 <span ><a  href="categories.php?id=0">{t}Product Categories{/t}</a> &rarr; 
 </div> 



{* <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}"> *}
    <h1>{t}Editing Category{/t}:</h1>
{*  </div> *}

 
   <ul class="tabs" id="chooser_ul" style="clear:both">
         <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
          <li> <span class="item {if $edit=='subcategory'}selected{/if}"  id="subcategory">  <span> {t}Subcategories{/t}</span></span></li>
    </ul>
  
  <div class="tabbed_container"> 
 
{*  <div  class="edit_block" style="{if $edit!="description"}display:none{/if}"  id="d_description">*}
 
  <div class="general_options" style="float:right">
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_category" onClick="save_edit_general('category')" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_category" onClick="reset_edit_general('category')" class="state_details">{t}Reset{/t}</span>
   </div>
      
      
      
      <div id="new_category_messages" class="messages_block"></div>
	  <table class="edit"> 
	<tr class="first"><td style="" class="label">{t}Category Name{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Category_Name" value="{$category->get('Category Name')}" ovalue="{$category->get('Category Name')}">
	      <div id="Category_Name_Container" style="" ></div>
	    </div>
	  </td>
	  	 <td id="Category_Name_msg" class="edit_td_alert"></td>
	</tr></table>
{*</div>*}





 {*<div  class="edit_block" style="{if $edit!="subcategory"}display:none{/if}"  id="d_subcategory">

  <div class="general_options" style="float:right">
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_subcategory" onClick="save_edit_general('subcategory')" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_subcategory" onClick="reset_edit_general('subcategory')" class="state_details">{t}Reset{/t}</span>
   </div>  
   
      <div id="new_category_messages" class="messages_block"></div>
	  <table class="edit"> 
      <tr class="first"><td style="" class="label">{t}Subcategories{/t}:</td></tr>

    {foreach from=$subcategory_name item=subcategory}

   <tr><td></td>   <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Subcategory_Name" value="{$subcategory.subcategory_name}" ovalue="{$subcategory.subcategory_name}" onclick=subcategory_f('{$subcategory.subcategory_key}');>
	      <div id="Subcategory_Name_Container" style="" ></div>
	    </div>
	  </td>
	  	 <td id="Subcategory_Name_msg" class="edit_td_alert"></td><tr>
	{/foreach}</tr>


	  </table>
	 </div>*}
{* ----------------------------------------------------------------------------------------------------------------------------------------- *}
<div   class="data_table" sxtyle="margin:25px 20px">
	  <span class="clean_table_title">{t}SubCategories{/t}</span>
	 
	  <div  class="clean_table_caption"  style="clear:both;">
	    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	    <div class="clean_table_filter" style="display:none" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
	    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
	  </div>
	  <div  id="table0"   class="data_table_container dtable btable "> </div>
	</div>
     
     
{* ----------------------------------------------------------------------------------------------------------------------------------------- *}
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
