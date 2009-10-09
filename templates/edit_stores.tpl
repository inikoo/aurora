{include file='header.tpl'}
<div id="bd" >
<div id="sub_header">
  <span class="nav2 onleft" style="">{t}Editing Stores{/t}</span>
  <span class="nav2 onright" style="margin-left:20px"><a href="store.php?edit=0">{t}Exit edit{/t}</a></span>
</div>

<div id="doc3" style="clear:both;" class="yui-g yui-t4" >
    <ul class="tabs" id="chooser_ul">
      <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
      <li> <span class="item {if $edit=='stores'}selected{/if}" id="stores"  ><span> {t}Stores{/t}</span></span></li>
    </ul>
    <div id="yui-main" class="tabbed_container"> 
      
      <div id="edit_messages">
	</div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="description"}display:none{/if}"  id="d_description">
	<table class="edit">
	  <tr><td>{t}Corporation Name{/t}:</td><td><input  id="name" onKeyUp="description_changed(this)"    onMouseUp="description_changed(this)"  onChange="description_changed(this)" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
	  
	</table>
	
      </div>
      
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="stores"}display:none{/if}"  id="d_stores">
      



	<div   class="data_table" style="margin:10px 20px 25px 20px">

	  	<div style="margin:0 0 10px 0;padding:10px;border:1px solid #ccc;zdisplay:none" id="new_store_dialog" >
	<table class="edit">
	  <tr class="first"><td class="label" >{t}Code{/t}:</td><td><input  id="new_code" onKeyUp="new_store_changed(this)"    onMouseUp="new_store_changed(this)"  onChange="new_store_changed(this)"  name="code" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
	  <tr><td class="label" >{t}Full Name{/t}:</td><td><input   id="new_name" onKeyUp="new_store_changed(this)"    onMouseUp="new_store_changed(this)"  onChange="new_store_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td>
	   
	  <tr>
	    
	    <td class="label" style="xwidth:160px">{t}Target Country{/t}:</td>
	    <td  style="text-align:left">
	      <div  style="width:15em;position:relative;top:00px" >
		<input id="address_country" style="text-align:left;width:18em" type="text">
		<div id="address_country_container" style="" ></div>
	      </div>
	    </td>
	  </tr>
	  <input id="address_country_code" value="" type="hidden">
	  <input id="address_country_2acode" value="" type="hidden">
	  
<tr>
<td class="label">{t}Locale{/t}:</td><td></td>
</tr>

	</table>
	</div>

	  <span class="clean_table_title">{t}Stores{/t}</span>
	  <table class="options" style="float:right;padding:0;margin:0">
	    <tr>
	      <td  id="add_store">Add Store</td>
	      <td  style="display:none" id="save_new_store">Save New Store</td>
	      <td  style="display:none" id="cancel_add_store">Cancel</td>
	    </tr>
	  </table>

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
