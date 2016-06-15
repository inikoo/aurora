<div class="asset_profile container">
	<div class="presention_card" style="width:400px;">
		<table class="showcase" style="" border="0">
			<tr class="title">
				<td colspan="2" class="label">{t}Work day{/t}</td>
			</tr>
			<tr>
				<td class="label">{$timesheet->get_field_label('Timesheet Clocked Time')}:</td>
				<td class="Timesheet_Clocked_Time" title="{$timesheet->get('Clocked Hours')}">{$timesheet->get('Clocked Time')}</td>
			</tr>
			<tr>
				<td class="label">{$timesheet->get_field_label('Timesheet Working Time')}:</td>
				<td class="Timesheet_Working_Time" title="{$timesheet->get('Working Hours')}">{$timesheet->get('Working Time')}</td>
			</tr>
			<tr>
				<td class="label">{$timesheet->get_field_label('Timesheet Breaks Time')}:</td>
				<td class="Timesheet_Breaks_Time" title="{$timesheet->get('Breaks Hours')}">{$timesheet->get('Breaks Time')}</td>
			</tr>
			<tr>
				<td class="label">{$timesheet->get_field_label('Timesheet Unpaid Overtime')}:</td>
				<td class="Timesheet_Unpaid_Overtime" title="{$timesheet->get('Unpaid Overtime Hours')}">{$timesheet->get('Unpaid Overtime')}</td>
			</tr>
		</table>
	</div>
</div>
