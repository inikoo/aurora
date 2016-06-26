 <div class="presention_card">
	<table>
		<tr id="result_controls" class="controls">
			<td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
			<td> </td>
		</tr>
		<tr class="title">
			<td colspan="2">
			{if $object->get('Staff Type')=='Contractor'}{t}Contractor (system user){/t}
			{else}{t}Employee (system user){/t}{/if}
			 <i onclick="change_view('{if $object->get('Staff Type')=='Contractor'}contractor{else}employee{/if}/{$object->id}')" class="fa fa-{if $object->get('Staff Type')=='Contractor'}hand-spock-o{else}employee{/if} button"></i></td>
		</tr>
		<tr>
			<td class="label">{$object->get_field_label('Staff Alias')|capitalize}</td>
			<td>{$object->get('Alias')}</td>
		</tr>
		<tr>
			<td class="label">{$object->get_field_label('Staff Name')|capitalize}</td>
			<td>{$object->get('Name')}</td>
		</tr>
		<tr>
			<td class="label">{$object->get_field_label('Staff User Handle')|capitalize}</td>
			<td>{$object->get('User Handle')}</td>
		</tr>
	</table>
</div>
