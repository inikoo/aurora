<div class="branch" id="period_navigation" style="clear:none;width:620px;float:right;text-align:right;font-style:normal">

 <span id="other"  class="state_details {if  $quick_period=='other'}selected{/if}" style="margin-left:10px">{t}Other{/t}</span>
 <span id="ytd"  class="state_details {if  $quick_period=='ytd'}selected{/if}" style="margin-left:10px">{t}YTD{/t}</span>
 <span id="mtd" class="state_details {if  $quick_period=='mtd'}selected{/if}" style="margin-left:10px">{t}MTD{/t}</span>
 <span id="wtd" class="state_details {if  $quick_period=='wtd'}selected{/if}" style="margin-left:10px">{t}WTD{/t}</span>
 <span id="today"  class="state_details {if  $quick_period=='today'}selected{/if}" style="margin-left:10px">{t}today{/t}</span>
 <span id="yesterday" class="state_details {if  $quick_period=='yesterday'}selected{/if}" style="margin-left:10px">{t}yesterday{/t}</span>
 <span id="last_w"  class="state_details {if  $quick_period=='last_w'}selected{/if}" style="margin-left:10px">{t}last w{/t}</span>
 <span id="last_m"  class="state_details {if  $quick_period=='last_m'}selected{/if}" style="margin-left:10px">{t}last m{/t}</span>
 <span id="1w" class="state_details {if  $quick_period=='1w'}selected{/if}" style="margin-left:10px">{t}1w{/t}</span>
 <span id="10d" class="state_details {if  $quick_period=='10d'}selected{/if}" style="margin-left:10px">{t}10d{/t}</span>
 <span id="1m" class="state_details {if  $quick_period=='1m'}selected{/if}" style="margin-left:10px">{t}1m{/t}</span>
 <span id="1q"  class="state_details {if  $quick_period=='1q'}selected{/if}" style="margin-left:10px">{t}1q{/t}</span>
 <span id="1y" class="state_details {if  $quick_period=='1y'}selected{/if}" style="margin-left:10px">{t}1y{/t}</span>
 <span id="3y" class="state_details {if  $quick_period=='3y'}selected{/if}" style="margin-left:10px">{t}3y{/t}</span>
 <span id="all" class="state_details {if  $quick_period=='all'}selected{/if}" style="margin-left:10px">{t}All{/t}</span>

</div>


<div id="dialog_calendar_splinter"  class="xcal_menu_container" style="padding:5px 20px" >
 <div class="bd">


<div class="custom_dates" style="width:100%;margin-top:10px;border-top:1px solid#ccc;font-size:90%">
  
  <div style="margin-top:10px" id="cal1Container"></div>
<div style="float:left;padding:10px">
  <form action="orders.php?" method="GET" style="margin-top:10px">

 <table border=0 >
 <tr>
 <td>
    
      <span id="clear_interval" style="font-size:80%;color:#777;cursor:pointer;display:none">{t}clear{/t}</span>
      <input id="in" type="text" class="text" size="11" maxlength="10" name="from" value="{$from_little_edian}"/>
     
      </td>
       <td>
   	  <img style="cursor:pointer;height:15px;margin-top:px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="submit_interval"  alt="{t}Go{/t}" /> 
</td>
 <tr>
 <td>
      
      <input   style="left:0px;margin-top:5px" class="calpop" id="out" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to_little_edian}"/>
	  
      
   
   </td>
  <td></td>
</tr>
    </table>

 </form>


</div>
</div>
</div>

</div>


<div id="calendar_browser" style="padding:10px 20px 20px 15px">

<div  class="xcal_menu" id="calendar_div"    >

      {if isset($up)}<a class="prev" href="{$report_url}?{$up.url}" ><img src="art/icons/up.png" alt="&uarr;" title="{$up.title}"  /></a>{/if}

{if isset($tipo_title)}<span>{$tipo_title}</span>{/if} <span id="period">{$period}</span>
      {if isset($prev)}<a class="prev" href="{$report_url}?{$prev.url}" ><img src="art/icons/previous.png" alt="<" title="{$prev.title}"  /></a>{/if}
      {if isset($next)}<a class="next" href="{$report_url}?{$next.url}" ><img src="art/icons/next.png" alt=">" title="{$next.title}"  /></a>{/if}


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

</div>
</div>