{include file='header.tpl'}
<input type="hidden" id="Custom_Field_Store_Key" value="{$store_key}">
<input type="hidden" id="Custom_Field_Table" value="Part">


<div id="bd" style="padding:0 20px">
<h1>{t}Part Configuration{/t}</h1>



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

<div id="yui-main" >
    
    
    
 
  <div class="search_box" ></div>
  <div   id="contact_messages_div" >
      <span id="contact_messages"></span>
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
	    <div   >
	      <input style="text-align:left;" id="Custom_Field_Name" value="" ovalue="" valid="0">
	      <div id="Custom_Field_Name_Container"  ></div>
	    </div>
	  </td>
	  <td style="width:70px"></td>
	  
	</tr>
	<tr>
		<td style="width:120px" class="label">{t}Default Value{/t}:</td>
	  <td  style="text-align:left;width:350px">
	    <div   >
	      <input style="text-align:left;" id="Default_Value" value="" ovalue="" valid="0">
	      <div id="Default_Value_Container"  ></div>
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
      <div id="Customer_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
	{t}Another contact has been found with the similar details{/t}.
	<table style="margin:10px 0">
	  <tr><td><span  style="cursor:pointer;text-decoration:underline" onClick="edit_founded()"    id="pick_founded">{t}Edit the Customer found{/t} (<span id="founded_name"></span>)</span></td></tr>
	  <tr><td><span style="color:red">{t}Creating this customer is likely to produce duplicate contacts.{/t}</span></br<span  style="cursor:pointer;text-decoration:underline;color:red"  id="save_when_founded" >{t}Create customer anyway{/t}</span></td></tr>

	</table>
      </div>
      <div id="email_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
	<b>{t}Another contact has the same email{/t}</b>.
	<table style="margin:10px 0">
	  <tr><td style="cursor:pointer;text-decoration:underline" onclick="edit_founded()">{t}Edit the Customer found{/t} (<span id="email_founded_name"></span>)</td></tr>
	  <tr><td><span style="color:red">{t}Creating this customer will produce duplicate contacts. The email will not be added.{/t}</span></br><span  style="cursor:pointer;text-decoration:underline;color:red" id="force_new">{t}Create customer anyway{/t}</span></td></tr>
	</table>
      </div>
      
          <div id="email_found_other_store_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
	<b>{t}A Customer has the same email in another store{/t}</b>.
	<table style="margin:10px 0">
	<input type="hidden" value="" id="found_email_other_store_customer_key">
	  <tr><td style="cursor:pointer;text-decoration:underline" onclick="clone_founded()">{t}Use contact data to create new customer in this store{/t}</td></tr>
	</table>
      </div>
      
      
      <div style="clear:both;padding:10px;" id="validation">

	<div style="font-size:80%;margin-bottom:10px;display:none" id="mark_Customer_found">{t}Company has been found{/t}</div>
	
      </div>

      </div>
      
      
      <div style="clear:both;height:40px"></div>
	</div>
    
	<hr/>

      

    </div>
	
	
</div>
</div>
{include file='footer.tpl'}


