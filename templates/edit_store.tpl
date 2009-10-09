{include file='header.tpl'}
<div id="bd" >
  <div id="sub_header">
        <span class="nav2 onleft" style="">{t}Editing Store{/t}: <span style="font-style: italic;">{$store->get('Store Name')}</span> (<span style="font-style: italic;">{$store->get('Store Code')}</span>)</span>

    <span class="nav2 onright" style="margin-left:20px"><a href="store.php?edit=0">{t}Exit edit{/t}</a></span>
  </div>
  
  <div id="doc3" style="clear:both;" class="yui-g yui-t4" >
    <ul class="tabs" id="chooser_ul">
      <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
      <li> <span class="item {if $edit=='discounts'}selected{/if}"  id="discounts">  <span> {t}Discounts{/t}</span></span></li>
      <li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
      <li> <span class="item {if $edit=='departments'}selected{/if}" id="departments"  ><span> {t}Departments{/t}</span></span></li>
      <li> <span class="item {if $edit=='web'}selected{/if} " id="web" ><span> {t}Web Pages{/t}</span></span></li>
    </ul>
    <div id="yui-main" class="tabbed_container"> 
      <div id="info_name" style="margin-left:20px;float:left;width:260px;{if !($edit=='discounts' or $edit=='pictures')  }display:none{/if}">
	<table    class="show_info_product">
	  <tr>
	    <td>{t}Store Code{/t}:</td><td  class="aright">{$store->get('Store Code')}</td>
	  </tr>
	  <tr>
	    <td>{t}Store Name{/t}:</td><td  class="aright">{$store->get('Store Name')}</td>
	  </tr>
	</table>
      </div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="description"}display:none{/if}"  id="d_description">

	<div style="float:right">
	  <span class="save" style="display:none" id="description_save" onclick="save('description')">{t}Save{/t}</span>
	  <span id="description_reset"  style="display:none"   class="undo" onclick="reset('description')">{t}Cancel{/t}</span>
	</div>
	
	<table style="margin:0;" class="edit" border=0>
	  <tr><td class="label">{t}Store Code{/t}:</td><td>
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
		 value="{$store->get('Store Code')}" 
		 ovalue="{$store->get('Store Code')}"  
		 />
	    </td>
	  </tr>
	  <tr><td class="label">{t}Store Name{/t}:</td><td>
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
		 value="{$store->get('Store Name')}"  
		     ovalue="{$store->get('Store Name')}"  
		 />
	    </td>
	  </tr>
	</table>
      </div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="pictures"}display:none{/if}"  id="d_pictures">
	
      </div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="discounts"}display:none{/if}"  id="d_discounts">
	
      </div>
      
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="web"}display:none{/if}"  id="d_web">
      </div>
      
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="departments"}display:none{/if}"  id="d_departments">
	<table class="edit">
	  <tr><td>{t}Code{/t}:</td><td><input  id="new_code" onKeyUp="new_dept_changed(this)"    onMouseUp="new_dept_changed(this)"  onChange="new_dept_changed(this)"  name="code" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
	  <tr><td>{t}Full Name{/t}:</td><td><input   id="new_name" onKeyUp="new_dept_changed(this)"    onMouseUp="new_dept_changed(this)"  onChange="new_dept_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td>
	    <td>
	      <span class="save" id="add_new_dept" onclick="save_new_dept()" style="display:none">Add</span>
	  </td></tr>
	</table>
	
	
	<div   class="data_table" style="margin:25px 20px">
	  <span class="clean_table_title">{t}Departments{/t}</span>
	  <div  class="clean_table_caption"  style="clear:both;">
	    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	    <div class="clean_table_filter" style="display:none" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
	    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
	  </div>
	  <div  id="table0"   class="data_table_container dtable btable "> </div>
	</div>
     
      </div>
      
</div>      

      </div>

<div id="the_table1" class="data_table" style="margin:20px 20px 0px 20px; clear:both;padding-top:10px">
  <span class="clean_table_title">{t}History{/t}</span>
  <div  id="clean_table_caption1" class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div id="clean_table_filter1" class="clean_table_filter" style="display:none">
      <div class="clean_table_info"><span id="filter_name1">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>

</div> 
{include file='footer.tpl'}
