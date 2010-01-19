<div class="cal_menu" style="width:155px" >
      {if $up}<a class="prev" href="{$report_url}?{$up.url}" ><img src="art/icons/up.png" alt="&uarr;" title="{$up.title}"  /></a>{/if}

<span>{$tipo_title}</span> <span id="period">{$period}</span>
      {if $prev}<a class="prev" href="{$report_url}?{$prev.url}" ><img src="art/icons/previous.png" alt="<" title="{$prev.title}"  /></a>{/if}
      {if $next}<a class="next" href="{$report_url}?{$next.url}" ><img src="art/icons/next.png" alt=">" title="{$next.title}"  /></a>{/if}

{if $tipo=='f'}
<table>This year <a href="{$report_url}?tipo=y&y={$year}">{$year}</a></table>
{/if}
{if $tipo=='y'}
<table  class="calendar_year">
<tr>
<td><a href="{$report_url}?tipo=m&y={$period}&m=1">{$m[0]}</a></td>
<td><a href="{$report_url}?tipo=m&y={$period}&m=2">{$m[1]}</a></td>
<td><a href="{$report_url}?tipo=m&y={$period}&m=3">{$m[2]}</a></td>
</tr><tr>
<td><a href="{$report_url}?tipo=m&y={$period}&m=4">{$m[3]}</a></td>
<td><a href="{$report_url}?tipo=m&y={$period}&m=5">{$m[4]}</a></td>
<td><a href="{$report_url}?tipo=m&y={$period}&m=6">{$m[5]}</a></td>
</tr><tr>
<td><a href="{$report_url}?tipo=m&y={$period}&m=7">{$m[6]}</a></td>
<td><a href="{$report_url}?tipo=m&y={$period}&m=8">{$m[7]}</a></td>
<td><a href="{$report_url}?tipo=m&y={$period}&m=9">{$m[8]}</a></td>
</tr><tr>
<td><a href="{$report_url}?tipo=m&y={$period}&m=10">{$m[9]}</a></td>
<td><a href="{$report_url}?tipo=m&y={$period}&m=11">{$m[10]}</a></td>
<td><a href="{$report_url}?tipo=m&y={$period}&m=12">{$m[11]}</a></td>
</tr>
</table>
{/if}
{if $tipo=='w' or $tipo=='m' or $tipo=='d'}
<table  class="calendar_year">
<tr class="top">
<td>w</td>
<td>M</td>
<td>T</td>
<td>W</td>
<td>T</td>
<td>F</td>
<td>S</td>
<td>D</td>
</tr>
{foreach from=$w item=week}
<tr class="day">
<td><a href="{$report_url}?tipo=w&y={$week.year}&w={$week.number}">{$week.number}</a></td>
<td ><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_mon}&d={$week.mon}">{$week.mon}</a></td>
<td><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_tue}&d={$week.tue}">{$week.tue}</a></td>
<td><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_wed}&d={$week.wed}">{$week.wed}</a></td>
<td><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_thu}&d={$week.thu}">{$week.thu}</a></td>
<td><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_fri}&d={$week.fri}">{$week.fri}</a></td>
<td><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_sat}&d={$week.sat}">{$week.sat}</a></td>
<td><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_sun}&d={$week.sun}">{$week.sun}</a></td>
</tr>
{/foreach}
</table>


{/if}
<div class="custom_dates" >
<span id="show_custom_dates" class="state_details">{t}Custom Dates{/t}</span>
<div id="custom_dates_form" style="margin-top:5px;{if $tipo!='f'}display:none{/if}">
{t}From{/t}:<span style="position:relative;left:0px;"><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/>
 <img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /><br/>
 <span >{t}To:{/t}</span> <input   id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/>
 <img   id="calpop2" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /><br/>
 
 <span  class="state_details" id="go_free_report" style="margin-right:16px;position:relative;top:2px"/>Get Report</span>
	</span>
	<div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
	<div style="position:relative;right:-120px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>

</div>
</div>
</div>