	{include file='header.tpl'}
<input type="hidden" id="Custom_Field_Store_Key" value="{$store_key}">
<input type="hidden" id="Custom_Field_Table" value="Customer">
<div id="bd" style="padding:0px">

<div style="padding:0 20px">
<h1>{t}Customer Store Configuration{/t}</h1>

</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='new_custom_fields'}selected{/if}"  id="new_custom_fields">  <span> {t}Adding New custom Fields{/t}</span></span></li>
    <li> <span class="item {if $view=='custom_form'}selected{/if}"  id="custom_form">  <span> {t}Custom Form{/t}</span></span></li>
	<li> <span class="item {if $view=='email_config'}selected{/if}"  id="email_config">  <span> {t}Email Configuration{/t}</span></span></li>
</ul>

<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px">

  <div id="block_new_custom_fields"  style="{if $view!='new_custom_fields'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
  
<h3>Adding new custom field</h3>
	<div style="clear:both;margin-top:0px;margin-right:0px;width:{if $options_box_width}{$options_box_width}{else}700px{/if};float:right;margin-bottom:10px" class="right_box">
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


    
    

  <div >
     <div id="results" style="margin-top:0px;float:right;width:390px;"></div>
      
      <div  style="float:left;width:540px;" >
      
      
      <table class="edit"  border=0 style="width:100%;margin-bottom:0px" >
      <input type="hidden" value="{$store_key}" id="Store_Key"/>
      <input type="hidden" value="{$customer_type}" id="Customer_Type"/>
	  
	  
	<tbody id="company_section">

      
  


	
	<tr class="first">
	<td style="width:120px" class="label">{t}Field Name{/t}:</td>
	  <td  style="text-align:left;width:350px">
	    <div  style="" >
	      <input style="text-align:left;" id="Custom_Field_Name" value="" ovalue="" valid="0">
	      <div id="Custom_Field_Name_Container" style="" ></div>
	    </div>
	  </td>
	  <td style="width:70px"></td>
	  
	</tr>
	<tr>
		<td style="width:120px" class="label">{t}Default Value{/t}:</td>
	  <td  style="text-align:left;width:350px">
	    <div  style="" >
	      <input style="text-align:left;" id="Default_Value" value="" ovalue="" valid="0">
	      <div id="Default_Value_Container" style="" ></div>
	    </div>
	  </td>
	  <td style="width:70px"></td>
	  
	</tr>
	
	<tr>
	 <td class="label" style="width:200px">{t}Custom Field Type{/t}:</td>
	 <input type="hidden" value="varchar" id="Custom_Field_Type"  />
	 <input type="hidden" value="Yes" id="Custom_Field_In_New_Subject"  />
	 <input type="hidden" value="Yes" id="Custom_Field_In_Showcase"  />
	 
	 <td>
	   <div  class="options" style="margin:0">
	   <span class="option selected" onclick="change_allow(this,'Custom_Field_Type','varchar')" >{t}String{/t}</span> 
	   <span class="option" onclick="change_allow(this,'Custom_Field_Type','Mediumint')" >{t}Integer{/t}</span>
	   </div>
	 </td>
	 </tr>
	 
	  <tr>
	 <td class="label" style="width:400px">{t}Custom Field In New Subject{/t}:</td>
	 <td>
	   <div class="options" style="margin:0">
	   <span class="option selected" onclick="change_allow(this,'Custom_Field_In_New_Subject','Yes')" >{t}Yes{/t}</span> 
	   <span class="option" onclick="change_allow(this,'Custom_Field_In_New_Subject','No')" >{t}No{/t}</span>
	   </div>
	 </td>
	 </tr>

	 <tr>
	 <td class="label" style="width:300px">{t}Custom Field In Showcase{/t}:</td>
	 <td>
	   <div class="options" style="margin:0">
	   <span class="option selected" onclick="change_allow(this,'Custom_Field_In_Showcase','Yes')" >{t}Yes{/t}</span> 
	   <span class="option" onclick="change_allow(this,'Custom_Field_In_Showcase','No')" >{t}No{/t}</span>
	   </div>
	 </td>
	 </tr>
	
	 </tbody>



{foreach from=$categories item=cat key=cat_key name=foo  }
 <tr>
 
 <td class="label">{t}{$cat->get('Category Label')}{/t}:</td>
 <td>
  <select id="cat{$cat_key}" cat_key="{$cat_key}"  onChange="update_category(this)">
    {foreach from=$cat->get_children_objects() item=sub_cat key=sub_cat_key name=foo2  }
        {if $smarty.foreach.foo2.first}
        <option  value="">{t}Unknown{/t}</option>
        {/if}
        <option value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Label')}</option>
    {/foreach}
  </select>
  
 </td>   
</tr>
{/foreach}

    
    </table>
      <table class="options" border=0 style="font-size:120%;margin-top:20px;;float:right;padding:0">
	<tr>
		<td   id="creating_message" style="border:none;display:none">{t}Creating Contact{/t}</td>

	  <td  class="disabled" id="save_new_custom_field">{t}Save{/t}</td>
	  <td  id="cancel_add_custom_field">{t}Cancel{/t}</td>
	</tr>
      </table>


      
      

      </div>
      
      
      <div style="clear:both;height:40px"></div>
	</div>
  </div>
		
		
    


		<div id="block_custom_form"  style="{if $view!='custom_form'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

		<h3>Adding new custom field</h3>
		<div  style="float:left;width:1500px;" >
			<table class="edit"  border=0 style="width:100%;margin-bottom:0px" >
			<tr>
			<td>Source Code: </td>
			<td>

			<div style="font-family:courier ;  color: black"> 
			&lt;IFRAME SRC="external_form.php" WIDTH=600 HEIGHT=500&gt;
			</div> </td>
			</tr>
			</table>
		</div>
		</div>

		
		
	<div id="block_email_config"  style="{if $view!='email_config'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

	<div >

      
      <div  style="float:left;width:540px;" >
      
      
      <table class="edit"  border=0 style="width:100%;margin-bottom:0px" >
      <input type="hidden" value="{$store_key}" id="Store_Key"/>
      <input type="hidden" value="customers_store" id="Customer_Type"/>
	  
	  
	<tbody id="company_section">
    
  	<tr class="first">
	<td style="width:120px" class="label">{t}Email{/t}:</td>
	  <td  style="text-align:left;width:350px">
	    <div  style="" >
	      <input style="text-align:left;" id="Email" value="" ovalue="" valid="0">
	      <div id="Email_Container" style="" ></div>
	    </div>
	  </td>
	  <td style="width:70px"></td>
	  
	</tr>
	<tr>
		<td style="width:120px" class="label">{t}Password{/t}:</td>
	  <td  style="text-align:left;width:350px">
	    <div  style="" >
	      <input style="text-align:left;" type="password" id="Password" value="" ovalue="" valid="0">
	      <div id="Password_Container" style="" ></div>
	    </div>
	  </td>
	  <td style="width:70px"></td>
	  
	</tr>
	
	<tr>
		<td style="width:120px" class="label">{t}Confirm Password{/t}:</td>
	  <td  style="text-align:left;width:350px">
	    <div  style="" >
	      <input style="text-align:left;" type="password" id="Confirm_Password" value="" ovalue="" valid="0">
	      <div id="Confirm_Password_Container" style="" ></div>
	    </div>
	  </td>
	  <td style="width:70px"></td>
	  
	</tr>
	
	<tr>
		<td style="width:120px" class="label">{t}Incoming Mail Server{/t}:</td>
	  <td  style="text-align:left;width:350px">
	    <div  style="" >
	      <input style="text-align:left;" id="Incoming_Mail_Server" value="" ovalue="" valid="0">
	      <div id="Incoming_Mail_Server_Container" style="" ></div>
	    </div>
	  </td>
	  <td style="width:70px"></td>
	  
	</tr>
	
	<tr>
		<td style="width:1200px" class="label">{t}Outgoing Mail Server{/t}:</td>
	  <td  style="text-align:left;width:350px">
	    <div  style="" >
	      <input style="text-align:left;" id="Outgoing_Mail_Server" value="" ovalue="" valid="0">
	      <div id="Outgoing_Mail_Server_Container" style="" ></div>
	    </div>
	  </td>
	  <td style="width:70px"></td>
	  
	</tr>

	 </tbody>
  
    </table>
    <table class="options" border=0 style="font-size:120%;margin-top:20px;;float:right;padding:0">
	<tr>
	  <td   id="new_email_field_dialog_msg" style="border:none;display:none"></td>
	  <td  {*class="disabled"*} id="save_new_email">{t}Save{/t}</td>
	  <td  id="cancel_add_email">{t}Cancel{/t}</td>
	</tr>
    </table>
 
      </div>
      
      
      <div style="clear:both;height:40px"></div>
	</div>
		
		</div>	
	</div>
</div>

{include file='footer.tpl'}


