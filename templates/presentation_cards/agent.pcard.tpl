{assign "agent" $object} 
<div class="presention_card">
	<table>
		<tr id="result_controls" class="controls">
			<td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
			<td> <span class=" results link" id="create_other" onclick="change_view(state.request)">{t}Add another{/t} <i class="fa fa-plus"></i> </span> <span class="hide results link" id="create_other" onclick="clone_it()">{t}Clone it{/t} <i class="fa fa-flask"></i> </span> </td>
		</tr>
		<tr class="title">
			<td colspan="2">{t}Agent{/t} <i onclick="change_view('agent/{$agent->get('Store Key')}/{$agent->id}')" class="fa fa-user-secret link"></i></td>
		</tr>
		<tr>
			<td class="label">{$agent->get_field_label('Agent Code')|capitalize}</td>
			<td>{$agent->get('Code')}</td>
		</tr>
		<tr>
			<td class="label">{$agent->get_field_label('Agent Name')|capitalize}</td>
			<td>{$agent->get('Name')}</td>
		</tr>
		<tr>
			<td class="label">{$agent->get_field_label('Agent Location')|capitalize}</td>
			<td>{$agent->get('Location')}</td>
		</tr>
	</table>
</div>
