{foreach from=$object_fields item=field_group } 
<div class="details_data">
<div class="group_label">{$field_group.label}</div>
{foreach from=$field_group.fields item=field } 
<div  class="field {if isset($field.render) and !$field.render}hide{/if}">
		<div class="label">
			{$field.label}
		</div>
		<div id="{$field.id}" class="value {$field.class}">
			{$field.value}
		</div>
	</div>
{/foreach}
<div class="field"></div>
</div>
{/foreach}
