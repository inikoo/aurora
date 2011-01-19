<div  class="cal_menu_container" style="xborder:1px solid black" >
<span id="show_calendar_div" onClick="show_calendar_div()" class="state_details">{t}Other Dates{/t} &darr;</span>
<span id="hide_calendar_div" onClick="hide_calendar_div()" class="state_details" style="display:none">{t}Close{/t} &uarr;</span>

<div  class="cal_menu" id="calendar_div"  style="display:none";   >

      {if $up}<a class="prev" href="{$report_url}?{$up.url}" ><img src="art/icons/up.png" alt="&uarr;" title="{$up.title}"  /></a>{/if}

<span>{$tipo_title}</span> <span id="period">{$period}</span>
      {if $prev}<a class="prev" href="{$report_url}?{$prev.url}" ><img src="art/icons/previous.png" alt="<" title="{$prev.title}"  /></a>{/if}
      {if $next}<a class="next" href="{$report_url}?{$next.url}" ><img src="art/icons/next.png" alt=">" title="{$next.title}"  /></a>{/if}

{if $tipo=='f'}

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
<td class="week">w</td>
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
<td class="week"><a href="{$report_url}?tipo=w&y={$week.year}&w={$week.number}">{$week.number}</a></td>
<td {if $week.mon_selected}class="selected"{/if}><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_mon}&d={$week.mon}">{$week.mon}</a></td>
<td {if $week.tue_selected}class="selected"{/if}><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_tue}&d={$week.tue}">{$week.tue}</a></td>
<td {if $week.wed_selected}class="selected"{/if}><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_wed}&d={$week.wed}">{$week.wed}</a></td>
<td {if $week.thu_selected}class="selected"{/if}><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_thu}&d={$week.thu}">{$week.thu}</a></td>
<td {if $week.fri_selected}class="selected"{/if}><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_fri}&d={$week.fri}">{$week.fri}</a></td>
<td {if $week.sat_selected}class="selected"{/if}><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_sat}&d={$week.sat}">{$week.sat}</a></td>
<td {if $week.sun_selected}class="selected"{/if}><a href="{$report_url}?tipo=d&y={$week.year}&m={$week.m_sun}&d={$week.sun}">{$week.sun}</a></td>
</tr>
{/foreach}
</table>


{/if}
<div class="custom_dates" style="width:100%;border:0px solid red;font-size:90%">
<span id="show_custom_dates" class="state_details">{t}Choose custom dates{/t}</span>

 
    <form action="orders.php?" method="GET" style="margin-top:10px">
      <div style="position:relative;left:18px"><span id="clear_interval" style="font-size:80%;color:#777;cursor:pointer;display:none">{t}clear{/t}</span>
      <input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/>
      <img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
      <span class="calpop"></span>
      <br/>
      <input   style="left:0px;margin-top:5px" class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/>
      <img   style="left:-18px" id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	  <br/>
	  <img style="position:relative;right:16px;cursor:pointer;height:15px;margin-top:5px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="submit_interval"  xonclick="document.forms[1].submit()" alt="{t}Go{/t}" /> 
      </div>
    </form>
    <div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
    <div style="position:relative;right:-80px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>
    








</div>
</div>


</div>









<div style="text-align:right;width:700px;margin-right:20px">

<span id="quick_all" class="state_details" style="margin-left:12px" >{t}All{/t}</span>
<span id="quick_this_year" class="state_details" style="margin-left:12px">{t}This Year{/t}</span>
<span id="quick_this_month" class="state_details" style="margin-left:12px">{t}This Month{/t}</span>
<span id="quick_this_week" class="state_details" style="margin-left:12px">{t}This Week{/t}</span>
<span id="quick_yesterday" class="state_details" style="margin-left:12px">{t}Yesterday{/t}</span>
<span id="quick_today" class="state_details"style="margin-left:12px" >{t}Today{/t}</span>

</div>
