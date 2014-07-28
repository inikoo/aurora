<table class="recordList" border="0">
	<tr>
		<th class='list-column-left' style='text-align: left; width: 320px;'> 
		<div class='buttons small left'>
			<span style='float:left'>{t}Fields{/t} </span> <button style='margin-left:10px' onclick='show_save_map()' id='new_map'>{t}Save map{/t}</button> 
		</div>
		</th>
		<th class='list-column-left' style='text-align: left; width: 200px;'>
			{t}Record{/t} {$index} {t}of{/t} {$number_of_records} <span id="ignore_record_label" style="color:red;{if !$ignore_record}display:none{/if}">({t}Ignored{/t})</th>
		<th style='width:150px'>
		<div class='buttons small'>
			<button style="cursor:pointer;{if $ignore_record}display:none{/if}" onclick="ignore_record({$index})" id="ignore" class="subtext">{t}Ignore Record{/t}</button>
			<button style="cursor:pointer;{if !$ignore_record}display:none{/if}" onclick="read_record({$index})" id="unignore" class="subtext">{t}Read Record{/t}</button>
		</div>
		</th>
		<th style='width:150px'>
		<div>
			<img src='art/first_button.png' title='{t}First{/t}' alt='{t}First{/t}' style='height:14px;{if $index > 1}cursor:pointer{else}opacity:.25{/if}' id='first' onclick='get_record_data(1)'>
			<img src='art/previous_button.gif' title='{t}Previous{/t}' alt='{t}Previous{/t}' style='margin-left:10px;height:14px;$index > 1' id='prev' onclick='get_record_data({$prev_index})'>
			<img src='art/next_button.gif' title='{t}Next{/t}' alt='{t}Next{/t}' style='margin-left:10px;height:14px;cursor:pointer;{if $$index < $number_of_records}cursor:pointer{else}opacity:.25{/if}' id='next' onclick='get_record_data({$prev_index})'>
			<img src='art/last_button.png' title='Last' alt='Last' style='margin-left:10px;height:14px;cursor:pointer;{if $index < $number_of_records}cursor:pointer{else}opacity:.25{/if}' id='next' onclick='get_record_data({$number_of_records})'>
		</div>
		</th>
	</tr>
	<tr style="height:20px;border-top:1px solid #ccc">
		<td>
		<select id="select0" onchange="option_changed(this.options[this.selectedIndex].value,this.selectedIndex)">
		</select>
		</td>
		<td colspan="3">test1</td>
	</tr>
	<tr style="height:20px;border-top:1px solid #ccc">
		<td>
		<select id="select1" onchange="option_changed(this.options[this.selectedIndex].value,this.selectedIndex)">
		</select>
		</td>
		<td colspan="3">Test Desc</td>
	</tr>
</table>
