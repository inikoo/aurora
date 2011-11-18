
   
   
     {if $return_to_order}<div style="text-align:right;cursor:pointer;" onClick="back_to_take_order({ $return_to_order})" class="quick_button">{t}Order{/t}</div>{/if}

     <div style="width:540px;float:right;text-align:left">
     <div style="border-bottom:1px solid #777;padding-bottom:5px">
       <div  class="buttons">
       <button id="add_new_billing_address" ><img src="art/icons/add.png" alt=""> {t}Add Billing Address{/t}</button>
      
       </div>
     <div style="height:25px;display:table-cell; vertical-align:bottom">
       <span >{t}Billing Address Library{/t}</span>
       </div>
       
       
       
       </div>
     
       <div id="dialog_new_billing_address" style="width:540px;margin-top:10px;padding:10px 0 0 0 ;border:1px solid #ccc;display:none">
       <table id="new_billing_address_table" border=0 style="width:500px;margin:0 auto">
       {include file='edit_address_splinter.tpl' close_if_reset=true address_identifier='billing_' address_type='Shop' show_tel=true show_contact=true  address_function='Billing'  hide_type=true hide_description=true show_form=false  show_components=false }
     </table>
</div>
      <table>
       <tr id="tr_address_showcase">
	    <td colspan=2 style="xborder:1px solid black"  id="billing_address_showcase">
	      <div  style="display:none" class="address_container"  id="address_container0">
		<div class="address_display" id="address_display0"></div>
		<div  class="address_buttons" id="address_buttons0" >
		  <span class="small_button small_button_edit" style="float:left" id="contacts_address_button0" address_id="0" onclick="contacts_address(event,this)" ><img src="art/icons/person.png" alt="{t}Contacts{/t}"/></span>
		  
		  <input type="checkbox" class='Is_Main' /> {t}Main{/t}
		  <span class="small_button small_button_edit" id="delete_address_button0" address_id="0" onclick="delete_address(event,this)" >{t}Remove{/t}</span>
		  <span class="small_button small_button_edit" id="edit_address_button0" address_id="0" onclick="edit_address(0)" >{t}Edit{/t}</span>
		</div>
	      </div>
	      

	      <div class="address_container"  style="display:none" id="billing_address_container0">
	      
	    <div class="billing_address_tel_div" id="billing_address_tel_div0" style="color:#777;font-size:90%;"><span class="billing_address_tel_label" id="billing_address_tel_label0" style="visibility:hidden">{t}Tel{/t}: </span><span  class="billing_address_tel" id="billing_address_tel0"></span></div>

		<div class="address_display"  id="billing_address_display0"></div>
		<div class="address_buttons" id="billing_address_buttons0">
		  <span  style="float:left" id="contacts_address_button0" address_id="0" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/user.png" alt="{t}Contacts{/t}"/></span>
		  <span  style="float:left;margin-left:5px;cursor:pointer" id="contacts_address_button0" address_id="0" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/telephone.png" alt="{t}Telephones{/t}"/>
		  </span>
		  <div class="buttons small">
		  <button id="billing_set_main0" style="float:left" class="{if $key==$customer->get('Customer Billing Address Key')}hide{/if}  billing_set_main small_button small_button_edit"  onClick="change_main_address(0,{literal}{{/literal}type:'billing',prefix:'billing_',Subject:'Customer',subject_key:{$customer->get('Customer Key')}{literal}}{/literal})" >{t}Set as Main{/t}</button>
		  <button  class="small_button small_button_edit" id="delete_address_button0" address_id="0" onclick="delete_address(0,'billing_')" >{t}Remove{/t}</span>
		  <button  class="small_button small_button_edit" id="edit_address_button0" address_id="0" onclick="edit_address(0,'billing_')" >{t}Edit{/t}</span>
		   </div> 
		</div>
	      </div>
	      
	      

	      {foreach  from=$customer->get_billing_address_objects()  item=address key=key }
	      
	      <div class="address_container"  id="billing_address_container{$address->id}">

		      <div id="billing_address_tel_div{$address->id}" style="color:#777;font-size:90%;"><span id="billing_address_tel_label{$address->id}"  style="{if !$address->get_principal_telecom_key('Telephone')}visibility:hidden;{/if}" >{t}Tel{/t}: </span><span id="billing_address_tel{$address->id}">{$address->get_formated_principal_telephone()}</span></div>

	<div class="address_display"  id="billing_address_display{$address->id}">{$address->display('xhtml')}</div>
		<div style="clear:both" class="address_buttons" id="billing_address_buttons{$address->id}">
		  <span  style="float:left" id="contacts_address_button{$address->id}" address_id="{$address->id}" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/user.png" alt="{t}Contacts{/t}"/></span>
		  <span  style="float:left;margin-left:5px;cursor:pointer" id="contacts_address_button{$address->id}" address_id="{$address->id}" onclick="contacts_address(event,this)" >
		    <img style="display:none" src="art/icons/telephone.png" alt="{t}Telephones{/t}"/>
		  </span>
		  <div class="buttons small">
		  <button id="billing_set_main{$address->id}" style="float:left" class="{if $key==$customer->get('Customer Billing Address Key')}hide{/if}  billing_set_main small_button small_button_edit"  onClick="change_main_address({$address->id},{literal}{{/literal}type:'billing',prefix:'billing_',Subject:'Customer',subject_key:{$customer->get('Customer Key')}{literal}}{/literal})" >{t}Set as Main{/t}</button>
		  {if $key==$customer->get('Customer Main Address Key')}<img src="art/icons/lock.png" alt="lock"> <span  class="state_details" > {t}Contact{/t}</span>{/if}	
		 <button {if $key==$customer->get('Customer Main Address Key')}style="display:none"{/if} class="small_button small_button_edit" id="delete_address_button{$address->id}" address_id="{$address->id}" onClick="delete_address({$address->id},{literal}{{/literal}type:'billing',prefix:'billing_',Subject:'Customer',subject_key:{$customer->get('Customer Key')}{literal}}{/literal})" >{t}Remove{/t}</button>
		  <button {if $key==$customer->get('Customer Main Address Key')}style="display:none"{/if} class="small_button small_button_edit" id="edit_address_button{$address->id}" address_id="{$address->id}" onclick="display_edit_billing_address({$address->id},'billing_')" >{t}Edit{/t}</button>
		  </div>
		</div>
	
	      </div>
	      
	      {/foreach}
	    </td>
       </tr>
	  </table>
      
      </div>

     <div style="width:260px">
       <div style="border-bottom:1px solid #777;padding-bottom:5px">
         <div style="height:25px;display:table-cell; vertical-align:bottom;">
        <span >{t}Current billing Address{/t}:</span>
       </div>
       </div>
    
   <div id="billing_current_address" style="font-size:120%;margin-top:15px">
      
 {$customer->display_billing_address('xhtml')}
     </div>
 </div>
<div style="clear:both"></div>
  
   
