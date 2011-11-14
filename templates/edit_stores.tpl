{include file='header.tpl'}
<div id="bd" >
{include file='assets_navigation.tpl'}
<div class="branch"> 
  <span>{t}Stores{/t}</span>
</div>
<div class="top_page_menu">
    <div class="buttons left" style="float:left">
        <button style="margin-left:0px"  onclick="window.location='stores.php'" ><img src="art/icons/door_out.png" alt=""/> {t}Exit Edit{/t}</button>
    </div>
    <div class="buttons" style="float:right">
    </div>
    <div style="clear:both"></div>
</div>


<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Stores{/t}</h1>
</div>






    <ul class="tabs" id="chooser_ul">
      <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Headquarters{/t}</span></span></li>
      <li> <span class="item {if $edit=='stores'}selected{/if}" id="stores"  ><span> {t}Stores{/t}</span></span></li>
    </ul>
    <div  class="tabbed_container"> 
      
      <div id="edit_messages"></div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="description"}display:none{/if}"  id="d_description">
      
       <div class="general_options" style="float:right">
	        <span  style="margin-right:10px;visibility:hidden" onClick="save_edit_general('corporation')" id="save_edit_corporation" class="state_details">{t}Save{/t}</span>
	        <span style="margin-right:10px;visibility:hidden" onClick="reset_edit_general('corporation')" id="reset_edit_corporation" class="state_details">{t}Reset{/t}</span>
      </div>
      
	<table class="edit">
	  <tr><td style="width:200px">{t}Corporation Name{/t}:</td><td style="width:200px"><input  id="name" onKeyUp="validate_general('corporation','name',this.value)"    onMouseUp="validate_general('corporation','name',this.value)"  onChange="validate_general('corporation','name',this.value)" changed=0 type='text' class='text' style="width:100%" MAXLENGTH="256" value="{$corporation->get('Corporation Name')}" ovalue="{$corporation->get('Corporation Name')}" /></td><td class="edit_td_alert" id="name_msg"></td></tr>
	  <tr><td>{t}Corporation Currency{/t}:</td><td><input  id="currency" onKeyUp="validate_general('corporation','currency',this.value)"    onMouseUp="validate_general('corporation','currency',this.value)"  onChange="validate_general('corporation','currency',this.value)" changed=0 type='text' class='text' style="width:3em" MAXLENGTH="3" value="{$corporation->get('Corporation Currency')}"  ovalue="{$corporation->get('Corporation Currency')}" /></td><td class="edit_td_alert" id="currency_msg"></td></tr>

	</table>
	
      </div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="stores"}display:none{/if}"  id="d_stores">
      <div class="general_options" style="float:right">
	<span   style="margin-right:10px"  id="add_store" class="state_details" >Create Store</span>
	<span  style="margin-right:10px;display:none"  id="save_store" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;display:none" id="close_add_store" class="state_details">{t}Close Dialog{/t}</span>
	
      </div>
      <div id="new_store_messages" style="float:left;padding:5px;border:1px solid #ddd;width:480px;margin-bottom:15px;display:none">
      </div>

	

	  <div id="new_store_dialog" style="float:left;padding:5px;border:1px solid #ddd;width:480px;margin-bottom:15px;display:none"  >
	<table class="edit">
	  <tr class="first"><td class="label" >{t}Code{/t}:</td><td><input  id="new_code" onKeyUp="new_store_changed(this)"    onMouseUp="new_store_changed(this)"  onChange="new_store_changed(this)"  name="code" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
	  <tr><td class="label" >{t}Name{/t}:</td><td><input   id="new_name" onKeyUp="new_store_changed(this)"    onMouseUp="new_store_changed(this)"  onChange="new_store_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:20em"  class='text' value="" /></td>
	   
	  <tr>
	    
	    <td class="label" style="xwidth:160px">{t}Target Country{/t}:</td>
	    <td  style="text-align:left">
	      <div  style="width:15em;position:relative;top:00px" >
		<input id="address_country" style="text-align:left;width:18em" type="text">
		<div id="address_country_container"  ></div>
	      </div>
	    </td>
	  </tr>
	  <input id="address_country_code" value="" type="hidden">
	  <input id="address_country_2acode" value="" type="hidden">
<tr>
<td class="label">{t}Currency{/t}:</td><td>
 <div  style="width:15em;position:relative;top:00px" >
		<input id="currency" style="text-align:left;width:18em" type="text">
		<div id="currency_container"  ></div>
	      </div> <input id="currency_code" value="" type="hidden" ></td>
</tr>	  
<tr>
<td class="label">{t}Locale{/t}:</td>
<div class="options" style="margin:5px 0" id="shelf_type_type_container">
		   <input type="hidden" value="{$shelf_default_type}" ovalue="{$shelf_default_type}" id="shelf_type_type"  >
		  {foreach from=$locales item=locale key=locale_key}
		    <span class="radio{if $locale.selected} selected{/if}"  id="radio_shelf_type_{$locale_key}" radio_value="{$locale_key}">{$locale.name}</span> 
		  {/foreach}
		</div>
<td></td>
</tr>

	</table>
	</div>

<div   class="data_table" style="clear:both">
	  <span class="clean_table_title">{t}Stores{/t}</span>
	 

  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div class="clean_table_filter" style="display:none" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
  </div>
  <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>
</div>

  </div>
  
  
  
  <div id="the_table1" class="data_table" style="margin:20px 20px 0px 20px; clear:both;padding-top:10px">
  <span class="clean_table_title">{t}History{/t}</span>
  <div  id="clean_table_caption1" class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div id="clean_table_filter1" class="clean_table_filter" style="display:none">
      <div class="clean_table_info"><span id="filter_name1" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>

</div>



</div> 
{include file='footer.tpl'}
