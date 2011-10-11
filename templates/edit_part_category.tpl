{include file='header.tpl'}
<div id="bd" > 
<span class="nav2 onleft"><a href="part_categories.php?id=0">{t}Parts Categories{/t}</a></span>
{include file='assets_navigation.tpl'}


{if $category}
<div class="branch"> 
 <span ><a  href="edit_customer_category.php?store_id={$store->id}&id=0">{t}Customer Categories{/t}</a> &rarr; {$category->get_smarty_tree('edit_part_category.php')}
 </div> 
    <h1 style="clear:both">{t}Editing Category{/t}: <span id="cat_title">{$category->get('Category Name')}</span></h1>
{else}
<h1 style="clear:both">{t}Editing Main Categories{/t}</h1>
{/if}

 
   <ul class="tabs" id="chooser_ul" style="clear:both">
         <li> <span class="item {if $edit=='description'}selected{/if}" {if !$category}style="display:none"{/if} id="description">  <span> {t}Description{/t}</span></span></li>
         <li> <span class="item {if $edit=='subcategory'}selected{/if}"  id="subcategory">  <span> {t}Subcategories{/t}</span></span></li>
    </ul>
  
  <div class="tabbed_container"> 
 

{if $category}
 <div  class="edit_block" style="{if $edit!="description"}display:none{/if}"  id="d_description">
 
  <div class="general_options" style="float:right">
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_category" onClick="save_edit_general('category')" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_category" onClick="reset_edit_general('category')" class="state_details">{t}Reset{/t}</span>
   </div>
      
      
      
      <div id="new_category_messages" class="messages_block"></div>
	  <table class="edit"> 
	<tr class="first"><td  class="label">{t}Category Name{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Category_Name" value="{$category->get('Category Name')}" ovalue="{$category->get('Category Name')}">
	      <div id="Category_Name_Container"  ></div>
	    </div>
	  </td>
	  	 <td id="Category_Name_msg" class="edit_td_alert"></td>
	</tr></table>
</div>
{/if}





<div  class="edit_block" style="{if $edit!="subcategory"}display:none{/if}"  id="d_subcategory">
<div   class="data_table" sxtyle="margin:25px 20px">
	  <span class="clean_table_title">{t}Subcategories{/t}</span>
	 
	  <div  class="clean_table_caption"  style="clear:both;">
	    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	    <div class="clean_table_filter" style="display:none" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
	    <div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
	  </div>
	  <div  id="table0"   class="data_table_container dtable btable "> </div>
	</div>
     
   </div>
 </div>
 
 
 
 
 
 
 <div id="the_table1" class="data_table" style="clear:both">
  <span class="clean_table_title">{t}History{/t}</span>
     {include file='table_splinter.tpl' table_id='_history' filter_name=$filter_name1 filter_value=$filter_value1  }
  <div  id="table_history"   class="data_table_container dtable btable "> </div>
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
{include file='new_category_splinter.tpl'}
