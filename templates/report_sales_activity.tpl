{include file='header.tpl'} 
<div id="bd">
	{include file='reports_navigation.tpl'} 
	<h1 style="clear:left">
		{$title}
	</h1>
	<table class="report_sales1" border="0">
		

<tr class="title">
<td colspan="11" rowspan="3"> 
<table style="width:100%" border="0">
	<tr>
		<td></td>
		<td>Prevs Due</td>
		<td>Received</td>
		<td>Cancelled</td>
		<td>In Process</td>
		<td>In Warehouse</td>
		<td>Ready</td>
		<td>Dispatched</td>
	</tr>
	{foreach from=$activity_data item=data name=foo } 
	<tr class="geo">
		<td>{$data.store}{if isset($data.substore)}{$data.substore}{/if}</td>
		<td>{if isset($data.prevs_due)}{$data.prevs_due}{/if}</td>
		<td>{if isset($data.received)}{$data.received}{/if}</td>
		<td>{if isset($data.cancelled)}{$data.cancelled}{/if}</td>
		<td>{if isset($data.in_process)}{$data.in_process}{/if}</td>
		<td>{if isset($data.in_warehouse)}{$data.in_warehouse}{/if}</td>
		<td>{if isset($data.ready)}{$data.ready}{/if}</td>
		<td>{if isset($data.dispached)}{$data.dispached}{/if}</td>
	</tr>
	{/foreach} 
</table>
</td>
</tr>
</table>
</div>
<div id="period_menu" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Period{/t}:</li>
			{foreach from=$period_menu item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_period('{$menu.period}')"> {$menu.label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="compare_menu" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}compare{/t}:</li>
			{foreach from=$compare_menu item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_compare('{$menu.compare}')"> {$menu.label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='footer.tpl'} 