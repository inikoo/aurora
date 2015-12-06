	{if isset($upload_file)}{assign "upload_file" $upload_file}{else}{assign "upload_file" ""}{/if} 

<div id="result" class="result hide">
</div>
<div  id="fields" class="new_object {if $upload_file}$upload_file{/if}" object="{$object_name}" parent='{$state.parent}' parent_key='{$state.parent_key}' has_been_fully_validated="0" >
    <table >
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

 
	
	<tr id="{$field.id}_field"  class="{if $smarty.foreach.fields.last}last{/if} {if !$render}hide{/if}  {$class}"   >
		<td id="{$field.id}_label" class="label" ><span>{$field.label}</span></td>
		
		 		<td class="show_buttons" >
		 		<i id="{$field.id}_validation" class="fa fa-asterisk {if $required and !( $edit=='option' and $field.value!='' ) }required{/if} {if !$required || ($edit=='option' and $field.value!='') }valid{/if}   field_state " ></i> 
		 		 </td>

		
		<td  id="{$field.id}_container" class="{$field.type} new" field="{$field.id}" _required="{$required}" field_type='{$edit}' server_validation='{$server_validation}' object='{$object_name}' key='{$state.key}' parent='{$state.parent}' parent_key='{$state.parent_key}'> 
	<span id="{$field.id}_editor" class=""> 
		
		<span id="{$field.id}_formated_value" class="{$field.id} formated_value hide" ondblclick="open_edit_field('{$object_name}','{$state.key}','{$field.id}')">{if isset($field.formated_value)}{$field.formated_value}{else}{$field.value}{/if}</span>
		<span id="{$field.id}_value" class="hide " >{$field.value}</span>

		{if $edit=='string' or $edit=='email' or  $edit=='int_unsigned' or $edit=='smallint_unsigned' or $edit=='mediumint_unsigned' or $edit=='int' or $edit=='smallint' or $edit=='mediumint' or $edit=='anything' or $edit=='numeric' } 
		
	
		<input id="{$field.id}" class="input_field " value="{$field.value}" has_been_valid="0"/>
		<span id="{$field.id}_msg" class="msg"></span> 
		
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
		utilsScript: "/js/telephone_utils.js",
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
		<input id="{$field.id}_formated"  class="option_input_field " value="{$field.formated_value}" readonly  onClick="toggle_options('{$field.id}')"/>
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
		
		<input id="{$field.id}_formated"  class="option_input_field " value="{$field.formated_value}" readonly  onClick="toggle_options('{$field.id}')"/>
		
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
		<input id="{$field.id}_formated" class="option_input_field "  value="{$field.formated_value}" />
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
		                $('#{$field.id}_formated').val('xx');

		                //     var date = $(this).datepicker("getDate");
		                $('#{$field.id}_formated').val($.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate")))

		                $('#{$field.id}_datepicker').addClass('hide')
		            }
		        });
		    });

		    $('#{$field.id}_formated').focusin(function() {
		        $('#{$field.id}_datepicker').removeClass('hide')

		    });

		    $('#{$field.id}_formated').on('input', function() {
		        var date = chrono.parseDate($('#{$field.id}_formated').val())

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
  	    
  
		</span>
	</td>
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
{*}onclick="{if $upload_file}upload_new_object('{$object_name}'){else}save_new_object('{$object_name}'){/if}" {*}
<span class="save potentially_valid" id="{$object_name}_save"  onclick="save_new_object('{$object_name}','{$upload_file}')"  >{t}Save{/t} <i id="{$object_name}_save_icon" class="fa fa-cloud  " ></i></span> 
<span id="{$object_name}_msg" class="msg"></span></span> 
<span class="hide results" id="{$object_name}_create_other" onClick="change_view(state.request)">{t}Add another{/t} <i class="fa fa-plus"></i>  </span> 
<span class="hide results" id="{$object_name}_go_new" request="" request_template="{$new_object_request}" onClick="change_to_new_object_view()"  >{if isset($new_object_label)}{$new_object_label}{else}{t}View new object{/t}{/if} <i class="fa fa-arrow-right"></i> </span>

</td>
</tr>

</table>
</div>
 <script>
    $(".input_field").on("input propertychange", function(evt) {
        
        if($('#'+$(this).attr('id')+'_container').attr('server_validation')){
       
         var delay=200;
        }else{
         var delay=10;
        }
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_field($(this),delay)
    });
    
     $(".confirm_input_field").on("input propertychange", function(evt) {
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        on_changed_confirm_value($(this).attr('confirm_field'),$(this).val())
    });
   
   {if isset($js_code)}{$js_code}{/if}
</script> 