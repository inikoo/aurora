{include file='header.tpl'}
<div id="bd" >
<div id="sub_header">
    <span class="nav2 onleft" style="">{t}Editing Department{/t}: <span style="font-style: italic;">{$department->get('Product Department Name')}</span> (<span style="font-style: italic;">{$department->get('Product Department Code')}</span>)</span>
    <span class="nav2 onright" style="margin-left:20px"><a href="store.php?edit=0">{t}Exit edit{/t}</a></span>
  </div>
  
   <div id="doc3" style="clear:both;" class="yui-g yui-t4" >
    <ul class="tabs" id="chooser_ul">
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
   
     <table class="edit">
      <tr><td class="label">{t}Code{/t}:</td><td><input  id="code" onKeyUp="edit_dept_changed(this)"    onMouseUp="edit_dept_changed(this)"  onChange="edit_dept_changed(this)"  name="code" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="{$department->get('Product Department Code')}" ovalue="{$department->get('Product Department Code')}"  /></td></tr>
      <tr><td class="label">{t}Name{/t}:</td><td><input   id="name" onKeyUp="edit_dept_changed(this)"    onMouseUp="edit_dept_changed(this)"  onChange="edit_dept_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="{$department->get('Product Department Name')}"  ovalue="{$department->get('Product Department Name')}"  /></td>
	<td>
	  <span class="save" id="description_save" onclick="save('description')" style="display:none">{t}Update{/t}</span><span class="reset" id="description_reset" onclick="reset('description')" style="display:none">{t}Reset{/t}</span>
      </td></tr>
    </table>
   
   </div>   
   <div  class="edit_block" style="{if $edit!="discounts"}display:none{/if}"  id="d_discounts">
   </div>   
   <div  class="edit_block" style="{if $edit!="pictures"}display:none{/if}"  id="d_pictures">
   </div>   
  <div  class="edit_block" style="{if $edit!="families"}display:none{/if}"  id="d_families">
    <table >
      <tr><td>{t}Family Code{/t}:</td><td><input  id="new_code" onKeyUp="new_family_changed(this)"    onMouseUp="new_family_changed(this)"  onChange="new_family_changed(this)"  name="code" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
      <tr><td>{t}Family Name{/t}:</td><td><input   id="new_name" onKeyUp="new_family_changed(this)"    onMouseUp="new_family_changed(this)"  onChange="new_family_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td></tr>
      <tr><td>{t}Family Description{/t}:</td><td><textarea   id="new_description" onKeyUp="new_family_changed(this)"    onMouseUp="new_family_changed(this)"  onChange="new_family_changed(this)"  name="description" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  ></textarea> </td></tr>
	<td>
	  <span class="save" id="add_new_family" onclick="save_new_family()" style="display:none">Add</span>
      </td></tr>
    </table>
  


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
   </div>   
    
   
   </div>
   </div>
  

  
 

  
</div> 
{include file='footer.tpl'}
