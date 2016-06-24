 <div class="presention_card">
	<table>
		<tr id="result_controls" class="controls">
			<td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
			<td> </td>
		</tr>
		<tr class="title">
			<td colspan="2">{if $employee->get('Staff Type')=='Contractor'}{t}Contractoe (system user){/t}{else}{t}Employee (system user){/t}{/if} <i onclick="change_view('employee/{$employee->id}')" class="fa fa-link link"></i></td>
		</tr>
		<tr>
			<td class="label">{$employee->get_field_label('Staff Alias')|capitalize}</td>
			<td>{$employee->get('Alias')}</td>
		</tr>
		<tr>
			<td class="label">{$employee->get_field_label('Staff Name')|capitalize}</td>
			<td>{$employee->get('Name')}</td>
		</tr>
		<tr>
			<td class="label">{$employee->get_field_label('Staff User Handle')|capitalize}</td>
			<td>{$employee->get('User Handle')}</td>
		</tr>
	</table>
</div>
