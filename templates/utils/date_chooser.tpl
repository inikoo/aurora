<div id="date_chooser" class="date_chooser" ">
	<div id="f" class="interval {if  $period=='f'}selected{/if}" ><img src="/art/icons/mini-calendar_interval.png"  /> {t}Interval{/t}</div> 
	<div id="day" class="day {if  $period=='day'}selected{/if}" ><img src="/art/icons/mini-calendar.png"  /> {t}Day{/t}</div> 
	<div onclick="change_period('ytd')"period="ytd" id="ytd" class="fixed_interval {if  $period=='ytd'}selected{/if}" >{t}YTD{/t}</div> 
	<div onclick="change_period('mtd')"period="mtd" id="mtd" class="fixed_interval {if  $period=='mtd'}selected{/if}" >{t}MTD{/t}</div> 
	<div onclick="change_period('wtd')"period="wtd" id="wtd" class="fixed_interval {if  $period=='wtd'}selected{/if}" >{t}WTD{/t}</div> 
	<div onclick="change_period('today')" period="today" id="today" class="fixed_interval {if  $period=='today'}selected{/if}" >{t}Today{/t}</div> 
	<div onclick="change_period('yesterday')" period="yesterday" id="yesterday" class="fixed_interval {if  $period=='yesterday'}selected{/if}" >{t}Y'day{/t}</div> 
	<div onclick="change_period('last_w')" period="last_w" id="last_w" class="fixed_interval {if  $period=='last_w'}selected{/if}" >{t}Last W{/t}</div> 
	<div onclick="change_period('last_m')" period="last_m" id="last_m" class="fixed_interval {if  $period=='last_m'}selected{/if}" >{t}Last M{/t}</div> 
	<div onclick="change_period('1w')" period="1w" id="1w" class="fixed_interval {if  $period=='1w'}selected{/if}" >{t}1W{/t}</div> 
	<div onclick="change_period('10d')" period="10d" id="10d" class="fixed_interval {if  $period=='10d'}selected{/if}" >{t}10d{/t}</div> 
	<div onclick="change_period('1m')" period="1m" id="1m" class="fixed_interval {if  $period=='1m'}selected{/if}" >{t}1m{/t}</div> 
	<div onclick="change_period('1q')" period="1q" id="1q" class="fixed_interval {if  $period=='1q'}selected{/if}" >{t}1q{/t}</div> 
	<div onclick="change_period('1y')" period="1y" id="1y" class="fixed_interval {if  $period=='1y'}selected{/if}" >{t}1Y{/t}</div> 
	<div onclick="change_period('3y')" period="3y" id="3y" class="fixed_interval {if  $period=='3y'}selected{/if}" >{t}3Y{/t}</div> 
	<div onclick="change_period('all')" period="all"  id="all" class="fixed_interval {if  $period=='all'}selected{/if}" >{t}All{/t}</div>
</div>