<div id="{$calendar_id}_period_container" class="branch" style="clear:none;width:660px;float:right;text-align:right;font-style:normal">
	<span id="{$calendar_id}_day" class="state_details {if  $period=='day'}selected{/if}" style="margin-left:10px"><img src="art/icons/mini-calendar.png" style="position:relative;bottom:2.5px" /> {t}Day{/t}</span> 
	<span id="{$calendar_id}_f" class="state_details {if  $period=='f'}selected{/if}" style="margin-left:10px"><img src="art/icons/mini-calendar_interval.png" style="position:relative;bottom:2.5px" /> {t}Interval{/t}</span> 
	<span period="ytd" id="{$calendar_id}_ytd" class="state_details {if  $period=='ytd'}selected{/if}" style="margin-left:10px">{t}YTD{/t}</span> 
	<span  period="mtd" id="{$calendar_id}_mtd" class="state_details {if  $period=='mtd'}selected{/if}" style="margin-left:10px">{t}MTD{/t}</span> 
	<span period="wtd" id="{$calendar_id}_wtd" class="state_details {if  $period=='wtd'}selected{/if}" style="margin-left:10px">{t}WTD{/t}</span> 
	<span period="today" id="{$calendar_id}_today" class="state_details {if  $period=='today'}selected{/if}" style="margin-left:10px">{t}today{/t}</span> 
	<span period="yesterday" id="{$calendar_id}_yesterday" class="state_details {if  $period=='yesterday'}selected{/if}" style="margin-left:10px">{t}yesterday{/t}</span> 
	<span period="last_w" id="{$calendar_id}_last_w" class="state_details {if  $period=='last_w'}selected{/if}" style="margin-left:10px">{t}last w{/t}</span> 
	<span period="last_m" id="{$calendar_id}_last_m" class="state_details {if  $period=='last_m'}selected{/if}" style="margin-left:10px">{t}last m{/t}</span> 
	<span period="1w" id="{$calendar_id}_1w" class="state_details {if  $period=='1w'}selected{/if}" style="margin-left:10px">{t}1w{/t}</span> 
	<span period="10d" id="{$calendar_id}_10d" class="state_details {if  $period=='10d'}selected{/if}" style="margin-left:10px">{t}10d{/t}</span> 
	<span period="1m" id="{$calendar_id}_1m" class="state_details {if  $period=='1m'}selected{/if}" style="margin-left:10px">{t}1m{/t}</span> 
	<span period="1q" id="{$calendar_id}_1q" class="state_details {if  $period=='1q'}selected{/if}" style="margin-left:10px">{t}1q{/t}</span> 
	<span period="1y" id="{$calendar_id}_1y" class="state_details {if  $period=='1y'}selected{/if}" style="margin-left:10px">{t}1y{/t}</span> 
	<span period="3y" id="{$calendar_id}_3y" class="state_details {if  $period=='3y'}selected{/if}" style="margin-left:10px">{t}3y{/t}</span> 
	<span period="all"  id="{$calendar_id}_all" class="state_details {if  $period=='all'}selected{/if}" style="margin-left:10px">{t}All{/t}</span> 
</div>

<div id="{$calendar_id}_dialog_calendar_splinter" style="padding:5px 20px">
	<div class="bd">
		<div class="custom_dates" style="width:100%;margin-top:10px;border-top:1px solid#ccc;font-size:90%">
			<div style="margin-top:10px" id="{$calendar_id}_cal1Container">
			</div>
			<div style="float:left;padding:10px">
					<table border="0">
						<tr>
							<td> <span id="{$calendar_id}_clear_interval" style="font-size:80%;color:#777;cursor:pointer;display:none">{t}clear{/t}</span> 
							<input id="{$calendar_id}_in" type="text" class="text" size="11" maxlength="10" name="from" value="{$from_little_edian}" />
							</td>
							<td> <img style="cursor:pointer;height:15px;margin-top:px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="{$calendar_id}_submit_interval" alt="{t}Go{/t}" /> </td>
						</tr>
						<tr>
							<td> 
							<input style="left:0px;margin-top:5px" class="calpop" id="{$calendar_id}_out" size="11" maxlength="10" type="text" class="text" size="8" name="to" value="{$to_little_edian}" />
							</td>
							<td></td>
						</tr>
					</table>
			</div>
		</div>
	</div>
</div>
<div id="{$calendar_id}_dialog_calendar_date_splinter" style="padding:5px 20px">
	<div class="bd">
		<div class="custom_dates" style="width:100%;margin-top:10px;border-top:1px solid#ccc;font-size:90%">
			<div style="margin-top:10px" id="{$calendar_id}_cal2Container">
			</div>
			<div style="float:left;padding:10px">
					<table border="0">
						<tr>
							<td> <span id="{$calendar_id}_clear_interval_date" style="font-size:80%;color:#777;cursor:pointer;display:none">{t}clear{/t}</span> 
							<input id="{$calendar_id}_pick_date" type="text" class="text" size="11" maxlength="10" name="from" value="{$from_little_edian}" />
							</td>
							<td> <img style="cursor:pointer;height:15px;margin-top:px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="{$calendar_id}_submit_choose_day" alt="{t}Go{/t}" /> </td>
						</tr>
					</table>
			</div>
		</div>
	</div>
</div>
