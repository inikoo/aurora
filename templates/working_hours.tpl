	{if isset($metadata.compact_weekdays)}{assign "compact_weekdays" $metadata.compact_weekdays}{else}{assign "compact_weekdays" 1}{/if} 
	{if isset($metadata.compact_weekend)}{assign "compact_weekend" $metadata.compact_weekend}{else}{assign "compact_weekend" 1}{/if} 



<div class="working_hours" >
	<table border="0" style="" >
		<tr class="title">
			<td class="expand_days"></td>
			<td class="day_labels"></td>
			<td>{t}Start{/t}</td>
			<td>{t}Finish{/t}</td>
			<td style="width:280px;padding-left:20px">{t}Breaks{/t}</td>
			<td style="width:30%" ><i class="fa fa-cloud"></i></td>
		</tr>

		{for $i=0 to 8}
		<tr id="day_{$i}" class="{if  ($i>=0 and $i<6) }weekday{else}weekend{/if}  {if $i==0 or $i==6}group{else}day{/if}      {if  ($i>0 and $i<6) and $compact_weekdays}hide{/if}  {if  ( $i==7 or $i==8 )and $compact_weekend}hide{/if} {if  ( $i==6 )and !$compact_weekend}hide{/if} {if  ( $i==0 )and !$compact_weekdays}hide{/if}  "  >
			<td class="expand_days">
			{if $i==0}
			<i class="fa fa-expand" onClick="expand_weekday()"></i>
			{else if $i==1}
			<i class="fa fa-compress"  onClick="compress_weekday()"></i>
            {else if $i==6}
			<i class="fa fa-expand"  onClick="expand_weekend()"></i>
			{else if $i==7}
			<i class="fa fa-compress"  onClick="compress_weekend()"></i>
			{/if}
			
			</td>
			<td class="day_labels">{$day_labels[$i]}</td>
			<td> 
			<input id="wa_weekdays_start_{$i}" class="start time_input_field" placeholder="09:00" value="{if isset($data[$i].start)}$data[$i].start{/if}" />
			</td>
			<td> 
			<input id="wa_weekdays_end_{$i}" class="end time_input_field" placeholder="17:00" value="{if isset($data[$i].end)}$data[$i].end{/if}" />
			</td>
			<td style="padding-left:20px"> 
			<table >
			    
			    {if isset($data[0].breaks)}
			    
				<tr>
					<td> {t}Starting{/t}: 
					<input id="wa_weekdays_start" placeholder="12:00" class="time_input_field" value="{if isset($data[0].start)}$data[0].start{/if}" />
					<input id="wa_weekdays_start" placeholder="30" class="minutes_input_field" value="{if isset($data[0].start)}$data[0].start{/if}" />
					{t}minutes{/t} </td>
				</tr>
				{/if}
				
				<tr>
				<td >
				<span style="color:#aaa" class="link" ><i class="fa fa-plus"></i> {t}add break{/t}</span>
				<span class="hide">
				 {t}Starting{/t}: 
					<input id="break_start_{$i}" placeholder="12:00" class="time_input_field" value="{if isset($data[0].start)}$data[0].start{/if}" />
					<input id="break_duration_{$i}" placeholder="30" class="minutes_input_field" value="{if isset($data[0].start)}$data[0].start{/if}" />
					{t}minutes{/t} 
				</span>
				
				</td>
				
				
				
				</tr>
				
			</table>
			</td>
			<td></td>
		</tr>
		
		{/for}
	</table>
</div>

<script>
function expand_weekday(){
    $('.day.weekday').removeClass('hide')
 $('.group.weekday').addClass('hide')
}
function compress_weekday(){
    $('.day.weekday').addClass('hide')
 $('.group.weekday').removeClass('hide')
}

function expand_weekend(){
    $('.day.weekend').removeClass('hide')
 $('.group.weekend').addClass('hide')
}
function compress_weekend(){
    $('.day.weekend').addClass('hide')
 $('.group.weekend').removeClass('hide')
}

</script>