
{foreach from=$object_fields item=field_group } 
<div class="details_data">
<div class="group_label">{$field_group.label}</div>
{foreach from=$field_group.fields item=field } 
{if isset($field.edit)}{assign "edit" $field.edit}{else}{assign "edit" ""}{/if}
{if isset($field.server_validation)}{assign "server_validation" $field.server_validation}{else}{assign "server_validation" ""}{/if}

{if isset($field.invalid_msg)}{assign "invalid_msg" $field.invalid_msg}{else}{assign "invalid_msg" "{t}Invalid value{/t}"}{/if}

<div  class="field {if isset($field.render) and !$field.render}hide{/if}">
		<div id="{$field.id}_label" class="label">
			{$field.label}
		</div>
		{if isset($field.invalid_msg)}
		{foreach from=$field.invalid_msg item=msg key=msg_key  } 
		<span id="{$field.id}_{$msg_key}_invalid_msg" class="hide">{$msg}</span>
		{/foreach}
		{else}
		<span id="{$field.id}_invalid_msg" class="hide">{$invalid_msg}</span>
		{/if}
	
	
		<div id="{$field.id}_container" class="value {$field.class}" field_type='{$edit}' server_validation='{$server_validation}' object='{$state._object->get_object_name()}' key='{$state.key}' parent='{$state.parent}' parent_key='{$state.parent_key}' >
			<span style="padding-right:5px">

			<i id="{$field.id}_reset_button" class="fa fa-sign-out fa-flip-horizontal fw reset hide" onClick="close_edit_field('{$field.id}')"></i>
			<i id="{$field.id}_edit_button"  class="fa fa-pencil fw edit {if $edit==''}invisible{/if}" onClick="open_edit_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i>

			</span>
			<span id="{$field.id}_value" class="{$field.id}" ondblclick="open_edit_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')">{if isset($field.formated_value)}{$field.formated_value}{else}{$field.value}{/if}</span>
			<span id="{$field.id}_editor" class="" >
			{if 
			    $edit=='string' or 
			    $edit=='int_unsigned' or 
			    $edit=='smallint_unsigned' or 
			    $edit=='mediumint_unsigned' or 
			    $edit=='int' or 
			     $edit=='smallint' or 
			    $edit=='mediumint' or 
			     $edit=='anything' or 
			    $edit=='numeric'
			    }
			
			
			
			<input id="{$field.id}" class="input_field hide" value="{$field.value}"/> 
			{/if}
			{if $edit=='option' }

			<input id="{$field.id}" type="hidden" value="{$field.value}"  /> 
			<input id="{$field.id}_formated" class="option_input_field hide" value="{$field.formated_value}"  readonly/> 

            <div id="{$field.id}_options" class="dropcontainer hide"  >
			<ul>
			{foreach from=$field.options item=option key=value} 
			<li id="{$field.id}_option_{$value}" label="{$option}" value="{$value}" class="{if $value==$field.value}selected{/if}" onClick="select_option('{$field.id}','{$value}','{$option}' )">{$option} <i class="fa fa-circle fw current_mark {if $value==$field.value}current{/if}"></i></li>
			{/foreach}
			</ul>
			</div>
			
			{/if}

			
			<i id="{$field.id}_save_button" class="fa fa-cloud  save {$edit} hide" onClick="save_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i>

           <span id="{$field.id}_msg" class="msg"></span>
            </span>
			
		</div>
	</div>
{/foreach}
<div class="field"></div>
</div>
{/foreach}




<script>
    $(".input_field").on("input propertychange", function(evt) {
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_field($(this),200)
    });
   
</script>
