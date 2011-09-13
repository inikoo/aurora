{include file='header.tpl'}
<div id="bd" >
 {include file='contacts_navigation.tpl'}

<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Location{/t}: <span id="title_name">{$location->get('Location Code')}</span></h1>
</div>
<ul class="tabs" id="chooser_ul" style="clear:both">
  <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
</ul>
<div class="tabbed_container" > 
  <div id="description_block" style="{if $edit!='description'}display:none{/if}" >
    
     <div style="float:right">
	<span class="save" style="display:none" id="description_save" onclick="save('description')">{t}Save{/t}</span>
	<span id="description_reset"  style="display:none"   class="undo" onclick="reset('description')">{t}Cancel{/t}</span>
	</div>
	
      <table style="margin:0;" class="edit" border=0>
	<tr><td class="label">{t}Location Code{/t}:</td><td>
	      <input  
		 id="code" 
	
		 name="code" 
		 changed=0 
		 type='text' 
		 class='text' 
	
		 MAXLENGTH="16" 
		 value="{$location->get('Location Code')}" 
		 ovalue="{$location->get('Location Code')}"  
		 />
	    </td>
	  </tr>
	<tr><td class="label">{t}Used for{/t}:</td>
	  <td>
	    <div id="location_used_for" value="{$used_for}"  ovalue="{$used_for}"   prefix="used_for_" class="options" style="margin:5px 0">
	    {foreach from=$used_for_list item=cat key=cat_id name=foo}
	    <span {if $cat.selected}class="selected"{/if} name="{$cat_id}" onclick="radio_changed(this)" id="used_for_{$cat_id}">{$cat.name}</span>
	    {/foreach}
	    </div>
	  </td>
	 </tr>
	<tr xstyle="display:none"><td class="label">{t}Shape Type{/t}:</td>
	  <td>
	    <div id="location_shape_type" value="{$shape_type}" ovalue="{$shape_type}"  prefix="shape_type_"  class="options" style="margin:5px 0">
	    {foreach from=$shape_type_list item=cat key=cat_id name=foo}
	    <span {if $cat.selected}class="selected"{/if} name="{$cat_id}"   onclick="radio_changed(this);shape_type_changed();"  id="shape_type_{$cat_id}">{$cat.name}</span>
	    {/foreach}
	    </div>
	  </td>
	 </tr>
	
	</table>
  </div> 

</div>
</div>


{include file='footer.tpl'}
