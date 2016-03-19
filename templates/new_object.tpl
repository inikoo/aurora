	{if isset($form_type)}{assign "form_type" $form_type}{else}{assign "form_type" ""}{/if} 

<div id="result" class="result hide">
</div>
<div  id="fields" class="new_object   {if $form_type}$form_type{/if}" object="{$object_name}" parent='{$state.parent}' parent_key='{$state.parent_key}' key="{$state.key}" {if isset($step)}step="{$step}"{/if} has_been_fully_validated="0" >
    <table border=0>
    {foreach from=$object_fields item=field_group } 
        <tr class="title" >
            <td  colspan=3>
	            {$field_group.label}
	        </td>
        </tr>
        {if isset($field_group.class)}{assign "field_class" $field_group.class}{else}{assign "field_class" ""}{/if} 
        {if $field_class=='links'}
        {foreach from=$field_group.fields item=field name=fields} 
	    {if isset($field.render)}{assign "render" $field.render}{else}{assign "render" true}{/if} 
        <tr  class="link {if !$render}hide{/if}" onClick="change_view('{$field.reference}')">
            <td  colspan=3>
	            <i style="margin-right:10px" class="fa fa-link"></i> {$field.label}
	        </td>
        </tr>
        {/foreach}
    {else}
	{foreach from=$field_group.fields item=field name=fields} 
	
	{if isset($field.edit)}{assign "edit" $field.edit}{else}{assign "edit" ""}{/if} 
	{if isset($field.class)}{assign "class" $field.class}{else}{assign "class" ""}{/if} 
	{if isset($field.render)}{assign "render" $field.render}{else}{assign "render" true}{/if} 
	{if isset($field.required)}{assign "required" $field.required}{else}{assign "required" true}{/if} 

	{if isset($field.server_validation)}{assign "server_validation" $field.server_validation}{else}{assign "server_validation" ""}{/if} 
	{if isset($field.invalid_msg)}{assign "invalid_msg" $field.invalid_msg}{else}{assign "invalid_msg" ""}{/if} 
	
	{if isset($field.invalid_msg)} 
	{foreach from=$field.invalid_msg item=msg key=msg_key } 
	<span id="{$field.id}_{$msg_key}_invalid_msg" class="hide">{$msg}</span> 
	{/foreach} 
	{/if} 
	<span id="invalid_msg" class="hide">{t}Invalid value{/t}</span> 


    {if $edit=='address'}
    
    
    	        <tr id="{$field.id}_recipient_field" class="hide" >
	            
	            <td class="label">{t}Recipient{/t}</td>
	            <td class="show_buttons "><i id="{$field.id}_recipient_validation" class="fa fa-asterisk field_state"></i></td>
	        	<td  id="{$field.id}_recipient_container" _required="" field_type='string'  class="recipient "  >
	            <input id="{$field.id}_recipient" value="tmp" class="input_field" field_name="Address Recipient" >
	            </td>
	            </tr>
	            
	             <tr id="{$field.id}_field" class="hide">
	           	<td class="label">{t}Organization{/t}</td>
	             <td class="show_buttons error super_discret"><i id="{$field.id}_organization_validation" class="fa fa-asterisk field_state"></i></td>
	            <td  id="{$field.id}_organization_container" _required="" field_type='string'  class="organization ">
	            <input  id="{$field.id}_organization"  value="tmp" class="input_field" field_name="Address Organization" ></td>
	            </tr>
	            
	            <tr  id="{$field.id}_addressLine1_field"  >
	            <td>{t}Line 1{/t}</td>
	            <td class="show_buttons"><i  id="{$field.id}_addressLine1_validation" class="fa fa-asterisk field_state"></i></td>
	            <td id="{$field.id}_addressLine1_container" _required="" field_type='string'  class="addressLine1 {$field.type} address_value"  field="{$field.id}_addressLine1"  >
	            <input  id="{$field.id}_addressLine1"  value="" class="input_field" field_name="Address Line 1" ></td>
	            </tr>
	            
	            <tr  id="{$field.id}_addressLine2_field"> 
	            <td class="label">{t}Line 2{/t}</td>
	             <td class="show_buttons "><i id="{$field.id}_addressLine2_validation" class="fa fa-asterisk field_state"></i></td>
	            <td id="{$field.id}_addressLine2_container"  _required="" field_type='string'  class="addressLine2 {$field.type} address_value"  field="{$field.id}_addressLine2">
	            <input  id="{$field.id}_addressLine2"   value="" class="input_field" field_name="Address Line 2" ></td>
	            </tr>
	            <tr  id="{$field.id}_sortingCode_field"> 
	            <td class="label">{t}Sorting code{/t}</td>
	            <td class="show_buttons"><i id="{$field.id}_sortingCode_validation" class="fa fa-asterisk field_state"></i></td>
	            <td id="{$field.id}_sortingCode_container" _required="" field_type='string'  class="sortingCode  {$field.type} address_value"  field="{$field.id}_sortingCode">
	            <input  id="{$field.id}_sortingCode"  value="" class="input_field" field_name="Address Sorting Code" ></td>
	            </tr>
	           
	            <tr id="{$field.id}_postalCode_field"> 
	            <td class="label">{t}Postal code{/t}</td>
	            <td class="show_buttons"><i class="fa fa-asterisk field_state"></i></td>
	            <td id="{$field.id}_postalCode_container" _required="" field_type='string'  class="postalCode {$field.type} address_value"  field="{$field.id}_postalCode">
	            <input  id="{$field.id}_postalCode"  value="" class="input_field" field_name="Address Postal Code" ></td>
	            </tr>
	            
	             <tr id="{$field.id}_dependentLocality_field"> 
	           
	            <td class="label">{t}Dependent locality{/t}</td>
	             <td class="show_buttons"><i class="fa fa-asterisk field_state"></i></td>
	            <td id="{$field.id}_dependentLocality_container" _required="" field_type='string'  class="dependentLocality {$field.type} address_value"  field="{$field.id}_dependentLocality">
	            <input id="{$field.id}_dependentLocality"  value="" class="input_field" field_name="Address Dependent Locality" ></td>
	            </tr>
	            
	            <tr id="{$field.id}_locality_field"> 
	            <td class="label">{t}Locality (City){/t}</td>
	            <td class="show_buttons"><i class="fa fa-asterisk field_state"></i></td>
	            <td id="{$field.id}_locality_container" _required="" field_type='string'  class="locality {$field.type} address_value"  field="{$field.id}_locality">
	            <input  id="{$field.id}_locality" value="" class="input_field" field_name="Address Locality" ></td>
	            </tr>
	            
	             <tr   id="{$field.id}_administrativeArea_field"> 
	            <td class="label">{t}Administrative area{/t}</td>
	            <td class="show_buttons"><i class="fa fa-asterisk field_state"></i></td>
	            <td id="{$field.id}_administrativeArea_container" _required="" field_type='string' class="administrativeArea {$field.type} address_value"  field="{$field.id}_administrativeArea">
	            <input id="{$field.id}_administrativeArea" value="" class="input_field" field_name="Address Administrative Area" ></td>
	            </tr>
	            
	            <tr  id="{$field.id}_country_field" class="country"> 
	            <td class="label">{t}Country{/t}</td>
	            <td class="show_buttons error super_discret"><i class="fa fa-asterisk field_state"></i></td>
	            <td  id="{$field.id}_country_container"  _required="" field_type='string'  class="locality {$field.type} address_value "  field="{$field.id}_country" >
	            <input  id="{$field.id}_country" value="" class="input_field" type="hidden" field_name="Address Country 2 Alpha Code" >
	            <input id="{$field.id}_country_select" value="" class="country_select"> 
	            </td>
	            </tr>
	            
	            
	           
	   
	    <script>
	
	
	
    
         var initial_country='{$default_country|lower}';
      
	
	
	
	 telInput_{$field.id} = $("#{$field.id}_country_select")

	  telInput_{$field.id}.intlTelInput({
	     initialCountry: initial_country,
	     preferredCountries: [{$preferred_countries}]
	 });
	 
	 



	 telInput_{$field.id}.on("country-change", function(event,arg) {

        
	        var country_name = telInput_{$field.id}.intlTelInput("getSelectedCountryData").name
	        var country_code = telInput_{$field.id}.intlTelInput("getSelectedCountryData").iso2.toUpperCase()
        
	     
	     if (country_name.match(/\)\s+\(.+\)$/)) {
	         country_name = country_name.replace(/\)\s+\(.+\)$/, ")")
	     } else {
	         country_name = country_name.replace(/\s+\(.+\)$/, "")

	     }
        
      
      
      
        $('#{$field.id}_country').val(country_code)


	     
	    update_new_address_fields('{$field.id}',country_code, hide_recipient_fields=true,arg)
	    $("#{$field.id}_country_select").val(country_name)
	    if(arg!='init'){
	   
        on_changed_address_value("{$field.id}", '{$field.id}_country', country_code) 
        }
        
        
        
       

        

	 });

	 telInput_{$field.id}.trigger("country-change",'init');

	
	
	</script>
	
	
    

    {else}
    <tr id="{$field.id}_field"  class="{if $smarty.foreach.fields.last}last{/if} {if !$render}hide{/if}  {$class}"   >
		<td id="{$field.id}_label" class="label" ><span>{$field.label}</span></td>
		<td class="show_buttons" >
		 		<i id="{$field.id}_validation" class="fa fa-asterisk {if $required and !( $edit=='option' and $field.value!='' ) }required{/if} {if !$required || ($edit=='option' and $field.value!='') }valid{/if}   field_state " ></i> 
		 		 </td>

		
		<td  id="{$field.id}_container" class="{$field.type} new" field="{$field.id}" _required="{$required}" field_type='{$edit}' server_validation='{$server_validation}' object='{$object_name}' key='{$state.key}' parent='{$state.parent}' parent_key='{$state.parent_key}'> 
	   
	
		<span id="{$field.id}_formatted_value" class="{$field.id} formatted_value hide" ondblclick="open_edit_field('{$object_name}','{$state.key}','{$field.id}')">{if isset($field.formatted_value)}{$field.formatted_value}{else}{$field.value}{/if}</span>
		<span id="{$field.id}_value" class="hide " >{$field.value}</span>

		{if $edit=='string' or $edit=='handle' or $edit=='dimensions'  or $edit=='email' or  $edit=='int_unsigned' or $edit=='smallint_unsigned' or $edit=='mediumint_unsigned' or $edit=='int' or $edit=='smallint' or $edit=='mediumint' or $edit=='anything' or $edit=='numeric' } 
		
	
		<input id="{$field.id}" class="input_field " value="{$field.value}" has_been_valid="0"/>
		<span id="{$field.id}_msg" class="msg"></span> 
		
		{elseif $edit=='dropdown_select'}
			
			<input id="{$field.id}" type="hidden" class=" input_field" value="{$field.value}" has_been_valid="0"/>
		<input id="{$field.id}_dropdown_select_label"  field="{$field.id}" scope="{$field.scope}" class=" dropdown_select" value="{$field.formatted_value}" has_been_valid="0"/>

		<span id="{$field.id}_msg" class="msg"></span> 
		<div id="{$field.id}_results_container" class="search_results_container">
		
		<table id="{$field.id}_results" border="0"  >
			<tr class="hide" id="{$field.id}_search_result_template" field="" value="" formatted_value="" onClick="select_dropdown_option(this.getAttribute('field'),this.getAttribute('value'),this.getAttribute('formatted_value'))">
				<td class="code" ></td>
				<td style="width:85%" class="label" ></td>
				
			</tr>
		</table>
	
	</div>
		
		
		<script>
		  $("#{$field.id}_dropdown_select_label").on("input propertychange", function(evt) {
		 
 var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_dropdown_select_field($(this), delay)
    });
		</script>

		{elseif $edit=='textarea' } 
		
	
		<textarea id="{$field.id}" class="input_field "  has_been_valid="0">{$field.value}</textarea>
		<span id="{$field.id}_msg" class="msg"></span> 
		
		{elseif $edit=='attachment' } 
		
	    <input id="{$field.id}" type="file"  class="input_field " has_been_valid="0" >
		<span id="{$field.id}_msg" class="msg"></span> 
		
		
		  

	
		{elseif $edit=='telephone' } 
	    <input  id="{$field.id}" class="input_field telephone_input_field " value="" has_been_valid="0"/>
		<span id="{$field.id}_msg" class="msg"></span> 
	
		
		<script>
		$("#{$field.id}").intlTelInput(
		{
		utilsScript: "/js/libs/telephone_utils.js",
		defaultCountry:'{$account->get('Account Country 2 Alpha Code')}',
		preferredCountries:['{$account->get('Account Country 2 Alpha Code')}']
		}
		);
		
		$("#{$field.id}").intlTelInput("setNumber", "{$field.value}");
		</script>
		{elseif $edit=='pin' or  $edit=='password'} 
		<input id="{$field.id}" type="password" class="input_field " value="{$field.value}" has_been_valid="0" />
		<span id="{$field.id}_msg" class="msg"></span> 
		
		{elseif $edit=='pin_with_confirmation' or  $edit=='password_with_confirmation'} 
		<span id="not_match_invalid_msg" class="hide">{t}Values don't match{/t}</span> 
	    <span id="{$field.id}_cancel_confirm_button" class="hide"><span class="link" onclick="cancel_confirm_field('{$field.id}')"  >({t}start again{/t})</span> </span> 

	
		<input id="{$field.id}" type="password" class="input_field hide" value="{$field.value}" has_been_valid="0" />
		<input id="{$field.id}_confirm" placeholder="{t}Retype new password{/t}" type="password" confirm_field="{$field.id}" class="confirm_input_field hide" value="{$field.value}"  />
				<i id="{$field.id}_confirm_button"  class="fa fa-repeat  save {$edit} hide" onclick="confirm_field('{$field.id}')"></i> 

		
		<i id="{$field.id}_save_button" class="fa fa-cloud  save {$edit} hide" onclick="save_field('{$object_name}','{$state.key}','{$field.id}')"></i> 
		<span id="{$field.id}_msg" class="msg"></span> 
		
		{elseif $edit=='hidden' } 
				<input id="{$field.id}" type="hidden" value="{$field.value}" has_been_valid="0" />

		
		{elseif $edit=='option' } 
		
		<input id="{$field.id}" type="hidden" value="{$field.value}" has_been_valid="0" />
		<input id="{$field.id}_formatted"  class="option_input_field " value="{$field.formatted_value}" readonly  onClick="toggle_options('{$field.id}')"/>
		<span id="{$field.id}_msg" class="msg"></span> 
		
				<div id="{$field.id}_options" class="dropcontainer hide" >

			<ul>
				{foreach from=$field.options item=option key=value} 
				<li id="{$field.id}_option_{$value}" label="{$option}" value="{$value}" class="{if $value==$field.value}selected{/if}" onclick="select_option('{$field.id}','{$value}','{$option}' )">{$option} </li>
				{/foreach} 
			</ul>
			</div>
			
		


		{elseif $edit=='radio_option' } 
		
		<input id="{$field.id}" type="hidden" value="{$field.value}" has_been_valid="0"/>
		
		<input id="{$field.id}_formatted"  class="option_input_field " value="{$field.formatted_value}" readonly  onClick="toggle_options('{$field.id}')"/>
		
        <span id="{$field.id}_msg" class="msg"></span> 
		<div id="{$field.id}_options" class="dropcontainer radio_option  hide" >
			<ul>
				{foreach from=$field.options item=option key=value} 
				<li id="{$field.id}_option_{$value}" label="{$option.label}" value="{$value}" is_selected="{$option.selected}" onclick="select_radio_option('{$field.id}','{$value}','{$option.label}' )"><i class="fa fa-fw checkbox {if $option.selected}fa-check-square-o{else}fa-square-o{/if}"></i> {$option.label} <i class="fa fa-circle fw current_mark {if $option.selected}current{/if}"></i></li>
				{/foreach} 
			</ul>
		</div>

	
		{elseif $edit=='date' } 
		<input id="{$field.id}" type="hidden" value="{$field.value}" has_been_valid="0"/>
		<input id="{$field.id}_time" type="hidden" value="{$field.time}" />
		<input id="{$field.id}_formatted" class="option_input_field "  value="{$field.formatted_value}" />
		<span id="{$field.id}_msg" class="msg"></span> 
		<div id="{$field.id}_datepicker" class="hide datepicker"></div>
		<script>
		
		    $(function() {
		        $("#{$field.id}_datepicker").datepicker({
		            showOtherMonths: true,
		            selectOtherMonths: true,
		            defaultDate: new Date('{$field.value}'),
		            altField: "#{$field.id}",
		            altFormat: "yy-mm-dd",
		            onSelect: function() {
		                $('#{$field.id}').change();
		                $('#{$field.id}_formatted').val('xx');

		                //     var date = $(this).datepicker("getDate");
		                $('#{$field.id}_formatted').val($.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate")))

		                $('#{$field.id}_datepicker').addClass('hide')
		            }
		        });
		    });

		    $('#{$field.id}_formatted').focusin(function() {
		        $('#{$field.id}_datepicker').removeClass('hide')

		    });

		    $('#{$field.id}_formatted').on('input', function() {
		        var date = chrono.parseDate($('#{$field.id}_formatted').val())

		        if (date == null) {
		            var value = '';
		        } else {
		            var value = date.toISOString().slice(0, 10)
		            $("#{$field.id}_datepicker").datepicker("setDate", date);
		        }


		        $('#{$field.id}').val(value)
		        $('#{$field.id}').change();

		    });
		    $('#{$field.id}').on('change', function() {
		        on_changed_value('{$field.id}', $('#{$field.id}').val())

		    });

		    if ('{$field.value}' == '') {
		        $('#{$field.id}').val('')
		    }


		     </script>

        {/if} 
  	    
  
		
	</td>
	</tr>
	{/if}
	{/foreach} 
{/if}
 </div>
{/foreach}

 <tr class="title" >
 <td  colspan=3>
	
	</td>
</tr>
<tr id="{$object_name}_controls" class="controls" >
<td></td>
<td></td>
<td>

<span class="save_form save " id="{$object_name}_save"  onclick="save_new_object('{$object_name}','{$form_type}')"  ><span id="save_label">{t}Save{/t}</span><span class="hide" id="saving_label">{t}Saving{/t}</span> <i id="{$object_name}_save_icon" class="fa fa-cloud  " ></i></span> 
<span id="{$object_name}_msg" class="msg"></span></span> 
<span class="hide results" id="{$object_name}_create_other" onClick="change_view(state.request)">{t}Add another{/t} <i class="fa fa-plus"></i>  </span> 
<span class="hide results" id="{$object_name}_go_new" request=""  onClick="change_to_new_object_view()"  >{if isset($new_object_label)}{$new_object_label}{else}{t}View new object{/t}{/if} <i class="fa fa-arrow-right"></i> </span>

</td>
</tr>

</table>
</div>
 <script>
    $(".input_field").on("input propertychange", function(evt) {

        if ($('#' + $(this).attr('id') + '_container').attr('server_validation')) {

            var delay = 200;
        } else {
            var delay = 10;
        }
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_field($(this), delay)
    });

    $(".input_field").on("file change", function(evt) {

        on_changed_value($(this).attr('id'), $(this).val())
    });

     $(".value").each(function(index) {
     
        if($(this).hasClass('address_value')){
            return;
        }
     
        var field = $(this).attr('field')
        //console.log(field)
        var value = $('#' + field).val()

        var field_data = $('#' + field + '_container')
        var type = field_data.attr('field_type')
        var required = field_data.attr('_required')
        var server_validation = field_data.attr('server_validation')
        var parent = field_data.attr('parent')
        var parent_key = field_data.attr('parent_key')
        var _object = field_data.attr('object')
        var key = field_data.attr('key')


        var validation = validate_field(field, value, type, required, server_validation, parent, parent_key, _object, key)

       
       
            if (validation.class == 'invalid' && value == '') {
                validation.class = 'potentially_valid'
            }
       
         $('#' + field + '_field').removeClass('invalid potentially_valid valid').addClass(validation.class)



    });

   var form_validation = get_form_validation_state()
           process_form_validation(form_validation)

        
    $(".confirm_input_field").on("input propertychange", function(evt) {
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        on_changed_confirm_value($(this).attr('confirm_field'), $(this).val())
    });

   
   {if isset($js_code)}{$js_code}{/if}
</script> 