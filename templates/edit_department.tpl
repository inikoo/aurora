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
    <h1>{t}Editing Department{/t}: <span id="title_name">{$department->get('Product Department Name')}</span> (<span id="title_code">{$department->get('Product Department Code')}</span>)</h1>
</div>
   
 

   <ul class="tabs" id="chooser_ul" style="clear:both">
      <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
      <li> <span class="item {if $edit=='discounts'}selected{/if}"  id="discounts">  <span> {t}Discounts{/t}</span></span></li>
      <li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
      <li> <span class="item {if $edit=='families'}selected{/if}" id="families"  ><span> {t}Families{/t}</span></span></li>
      <li> <span class="item {if $edit=='web'}selected{/if} " id="web" ><span> {t}Web Pages{/t}</span></span></li>
    </ul>
  
  <div class="tabbed_container"> 
  <span style="display:none" id="description_num_changes"></span>
    <div id="description_errors"></div>
  <div id="info_name" style="margin-left:20px;float:left;width:360px;{if !($edit=='discounts' or $edit=='pictures')  }display:none{/if}">
	<table    class="show_info_product">
	  <tr>
	    <td>{t}Department Code{/t}:</td><td  class="aright">{$department->get('Product Department Code')}</td>
	  </tr>
	  <tr>
	    <td>{t}Department Name{/t}:</td><td  class="aright">{$department->get('Product Department Name')}</td>
	  </tr>
	</table>
   </div>
   <div  class="edit_block" style="{if $edit!="description"}display:none{/if}"  id="d_description">
   
     <div class="general_options" style="float:right">
       
       <span style="margin-right:10px;display:none"  onclick="save('description')" id="description_save" class="state_details">{t}Save Changes{/t}</span>
       <span style="margin-right:10px;display:none;" id="description_reset" onclick="reset('description')" i class="state_details">{t}Reset{/t}</span>
       
     </div>

     <table class="edit">
      <tr><td class="label">{t}Code{/t}:</td><td><input  id="code" onKeyUp="edit_dept_changed(this)"    onMouseUp="edit_dept_changed(this)"  onChange="edit_dept_changed(this)"  name="code" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="{$department->get('Product Department Code')}" ovalue="{$department->get('Product Department Code')}"  /></td></tr>
      
      
      <tr><td class="label">{t}Name{/t}:</td><td><input   id="name" onKeyUp="edit_dept_changed(this)"    onMouseUp="edit_dept_changed(this)"  onChange="edit_dept_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="{$department->get('Product Department Name')}"  ovalue="{$department->get('Product Department Name')}"  /></td>
      </tr>
    </table>
   
   </div>   
   <div  class="edit_block" style="{if $edit!="discounts"}display:none{/if}"  id="d_discounts">
   </div>   
   <div  class="edit_block" style="{if $edit!="pictures"}display:none{/if}"  id="d_pictures">
   {include file='edit_images_splinter.tpl'}

   
   </div>   
   <div  class="edit_block" style="{if $edit!="families"}display:none{/if}"  id="d_families">
     
     <div class="general_options" style="float:right">
       <span   style="margin-right:10px"  id="add_family" class="state_details" >Create Family</span>
       <span  style="margin-right:10px;display:none"  id="save_new_family" class="state_details">{t}Save{/t}</span>
       <span style="margin-right:10px;display:none" id="cancel_add_family" class="state_details">{t}Close Dialog{/t}</span>
     </div>

    <div     style="margin:0 0 10px 0;padding:10px;border:1px solid #ccc;display:none"  id="new_family_dialog" >
      <div id="new_family_messages" class="messages_block"></div>
    <table >
      <tr><td>{t}Family Code{/t}:</td><td><input  id="new_code" onKeyUp="new_family_changed(this)"    onMouseUp="new_family_changed(this)"  onChange="new_family_changed(this)"  name="code" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
      <tr><td>{t}Family Name{/t}:</td><td><input   id="new_name" onKeyUp="new_family_changed(this)"    onMouseUp="new_family_changed(this)"  onChange="new_family_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td></tr>
      <tr><td>{t}Special Characteristic{/t}:</td><td><input   id="new_special_char" onKeyUp="new_family_changed(this)"    onMouseUp="new_family_changed(this)"  onChange="new_family_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td></tr>
      
      <tr><td>{t}Description{/t}:</td><td><textarea   id="new_description" onKeyUp="new_family_changed(this)"    onMouseUp="new_family_changed(this)"  onChange="new_family_changed(this)"  name="description" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  ></textarea> </td></tr>
      <td>
	  <span class="save" id="add_new_family" onclick="save_new_family()" style="display:none">Add</span>
      </td></tr>
    </table>
  </div>


  <div   class="data_table" >
    <span class="clean_table_title">{t}Families{/t}</span>

    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" style="display:none" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
  
    </div>
   <div  class="edit_block" style="{if $edit!="web"}display:none{/if}"  id="d_web">

     <div class="general_options" style="float:right">
       <span style="margin-right:10px;"  onclick="" id="web_create" class="state_details">{t}Create Page{/t}</span>
       <span style="margin-right:10px;"  onclick="save('web')" id="web_save" class="state_details">{t}Save Changes{/t}</span>
       <span style="margin-right:10px;;" id="web_reset" onclick="reset('web')" i class="state_details">{t}Reset{/t}</span>
     </div>
       <table class="edit">
<tr><td>Page Properties</td></tr>
      <tr><td class="label">{t}Page Title{/t}:</td><td><input  id="web_title"  style="width:30em" MAXLENGTH="64" value="{$department->get('Page Title')}" ovalue="{$department->get('Page Title')}"  /></td></tr>
      <tr><td class="label">{t}Page Short Title{/t}:</td><td><input  id="web_short_title"  style="width:30em" MAXLENGTH="24" value="{$department->get('Page Short Title')}" ovalue="{$department->get('Page Short Title')}"  /></td></tr>
      <tr><td class="label">{t}Page Description{/t}:</td><td><textarea  id="web_short_title"  style="width:30em" MAXLENGTH="24" value="{$department->get('Page Short Title')}" ovalue="{$department->get('Page Short Title')}"  /></textarea></td></tr>
<tr><td>Content</td></tr>
<tr><td class="label">{t}Title{/t}:</td><td><input  id="web_store_title"  style="width:30em" MAXLENGTH="64" value="{$department->get('Page Store Title')}" ovalue="{$department->get('Page Store Title')}"  /></td></tr>
<tr><td class="label">{t}Subtitle{/t}:</td><td><input  id="web_store_subtitle"  style="width:30em" MAXLENGTH="64" value="{$department->get('Page Store Subtitle')}" ovalue="{$department->get('Page Store Subtitle')}"  /></td></tr>
<tr><td class="label">{t}Slogan{/t}:</td><td><input  id="web_store_slogan"  style="width:30em" MAXLENGTH="64" value="{$department->get('Page Store Slogan')}" ovalue="{$department->get('Page Store Slogan')}"  /></td></tr>
<tr><td class="label">{t}Short Introduction{/t}:</td><td><input  id="web_store_abstract"  style="width:30em" MAXLENGTH="64" value="{$department->get('Page Store Abstract')}" ovalue="{$department->get('Page Store Abstract')}"  /></td></tr>
<tr><td>Layout</td></tr>
 <tr><td></td></tr>
  <tr><td class="label">{t}Header Block{/t}:</td>
  <td>
  <table border=0>
  <tr><td>{t}Description{/t}</td><td><img id="header_block_description" display="yes" src="art/icons/accept.png"></td></tr>
  <tr><td colspan=3><textarea></textarea></td></tr>
    <tr><td>{t}Offers{/t}</td><td><img id="header_block_offers" display="yes" src="art/icons/accept.png"></td></tr>
  <tr><td>{t}New Products{/t}</td><td><img id="header_block_new" display="yes" src="art/icons/accept.png"><td></td></tr>

  </table>
  </td>
  </tr>
   <tr><td class="label">{t}Header Block Layout{/t}:</td>
  <td>
	<div style="float:left;width:125px;text-align:center;">
	<img style="border:1px solid #ccc" src="art/header_block_splited.png" alt="splited"/>
	{t}Splited{/t}
	</div>
	<div  style="float:left;width:125px;text-align:center">
	<img style="border:1px solid #ccc" src="art/header_block_tabbed.png" alt="tabbed"/>
	{t}Tabbed{/t}
	</div>
	<div  style="float:left;width:125px;text-align:center">
	<img style="border:1px solid #ccc" src="art/header_block_fluid_block.png" alt="fluid_block"/>
	{t}Fluid Block{/t}
	</div>
	
  </td>
  </tr>
  
  
 <tr><td class="label">{t}Products Layout{/t}:</td><td>
	<div style="float:left;width:125px;text-align:center;">
	<img style="border:1px solid #ccc" src="art/page_layout_thumbnails.png"/>
	{t}Thumbnails{/t}
	</div>
	<div  style="float:left;width:125px;text-align:center">
	<img style="border:1px solid #ccc" src="art/page_layout_list.png"/>
	{t}List{/t}
	</div>
	<div  style="float:left;width:125px;text-align:center">
	<img style="border:1px solid #ccc" src="art/page_layout_slideshow.png"/>
	{t}Slideshow{/t}
	</div>
	<div  style="float:left;width:125px;text-align:center">
	<img style="border:1px solid #ccc" src="art/page_layout_manual.png"/>
	{t}Manual{/t}
	</div>
	

    </td></tr>


      </tr>
      

    </table>

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
