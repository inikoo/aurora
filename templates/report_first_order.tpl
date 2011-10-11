{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}
{include file='calendar_splinter.tpl'}

<h1 style="clear:left">{t}First Order Analysis{/t} ({$period})</h1>

<table>
<tr><td>{t}Stores{/t}:</td>
<td>
<table class="options" >
{foreach from=$stores_data key=k item=v}
    <td id="store_{$v.store_key}" key="{$v.store_key}" onClick="choose_store(this)" >{$v.store_name} ({$v.number_first_orders})</td>
{/foreach}
</table>
</td>
</tr>

<tr id="department_chooser_tr"><td>{t}Department{/t}:</td>
<td>
  <div style="width:20em;position:relative;top:00px" >
  <input id="store_key" type="hidden" value="0"/>
    <input id="from" type="hidden" value="{$from}"/>
  <input id="to" type="hidden" value="{$to}"/>


  <input id="department_key" type="hidden" value="0"/>
		<input id="department" style="text-align:left;width:23em" type="text">
		<div id="department_Container"  ></div>
	      </div>
</td>
</tr>
<tr id="department_choosed_tr" style="display:none"><td>{t}Department{/t}:</td>
<td><span  id="choosed_department"></span> <span style="font-size:90%">(<span class="state_details" id="change_department">{t}Change{/t}</span>)</span>
  
	   
</td>
</tr>

<tr id="share_tr" style="display:none">
<td>{t}Share of order{/t}:</td>
<td>
<table style="float:left" class="options" >
    <td id="share_80"  >100%-80% (<span style="border:none;padding:0" id="share_80_orders">0</span>)</td>
    <td id="share_60"  >80%-60% (<span style="border:none;padding:0" id="share_60_orders">0</span>)</td>
    <td id="share_40"  >60%-40% (<span style="border:none;padding:0" id="share_40_orders">0</span>)</td>
    <td id="share_20"  >40%-20% (<span style="border:none;padding:0" id="share_20_orders">0</span>)</td>
    <td id="share_00"  >20%-00% (<span style="border:none;padding:0" id="share_00_orders">0</span>)</td>

</table>
</td>
</tr>

</table>
 
</div>


{include file='footer.tpl'}

