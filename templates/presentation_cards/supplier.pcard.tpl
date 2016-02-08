{assign "supplier" $object} 
<div class="presention_card">
	<table>
		<tr id="result_controls" class="controls">
			<td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
			<td> <span class=" results link" id="create_other" onclick="change_view(state.request)">{t}Add another{/t} <i class="fa fa-plus"></i> </span> <span class="hide results link" id="create_other" onclick="clone_it()">{t}Clone it{/t} <i class="fa fa-flask"></i> </span> </td>
		</tr>
		<tr class="title">
			<td colspan="2">{t}Supplier{/t} <i onclick="change_view('suppliers/{$supplier->get('Store Key')}/{$supplier->id}')" class="fa fa-link link"></i></td>
		</tr>
		<tr>
			<td class="label">{$supplier->get_field_label('Supplier Code')|capitalize}</td>
			<td>{$supplier->get('Code')}</td>
		</tr>
		<tr>
			<td class="label">{$supplier->get_field_label('Supplier Name')|capitalize}</td>
			<td>{$supplier->get('Name')}</td>
		</tr>
		<tr>
			<td class="label">{$supplier->get_field_label('Supplier Location')|capitalize}</td>
			<td>{$supplier->get('Location')}</td>
		</tr>
	</table>
</div>
