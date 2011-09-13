{include file='header.tpl'}
<div id="bd" >
 {include file='contacts_navigation.tpl'}`

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
			<input id="code" name="code" changed=0 type='text' class='text' MAXLENGTH="16" value="{$location->get('Location Code')}" ovalue="{$location->get('Location Code')}" />
			<div id="Product_Units_Per_Case_Container" style="" ></div>
			</td>
			<td style="width:200px" id="Product_Units_Per_Case_msg" class="edit_td_alert"></td>
		</tr>
	  

  
	<tr><td class="label">{t}Used for{/t}:</td>
 
	<td>
		<div id="location_used_for" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
		{foreach from=$used_for_list item=cat key=cat_id name=foo}
		<span class="{if $location->get('Location Mainly Used For')==$cat.name}selected{/if}" onclick="save_location('used_for','{$cat.name}')" id="used_for_{$cat.name}">{$cat.name}</span> 
	    {/foreach}
		
		
		</div>
	</td>
	
	 </tr>
	<tr><td class="label">{t}Shape{/t}:</td>

	<td>
		<div id="location_shape_type" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
		{foreach from=$shape_type_list item=cat key=cat_id name=foo}
		<span class="{if $location->get('Location Shape Type')==$cat.name}selected{/if}" onclick="save_location('shape','{$cat.name}')" id="shape_{$cat.name}">{$cat.name}</span> 
	    {/foreach}
		
		
		</div>
	</td>
	
	 </tr>
	 

	
	</table>
  </div> 

</div>
</div>


{include file='footer.tpl'}
