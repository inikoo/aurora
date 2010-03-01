{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}
{include file='calendar_splinter.tpl'}

<h1 style="clear:left">{$title}</h1>

<div style="width:350px;margin-top:20px">

<table class="options" >

<td {if $top==10}class="selected"{/if} id="top10" top=10>10</td>
<td {if $top==25}class="selected"{/if}  id="top25" top=25   >25</td>
<td {if $top==100}class="selected"{/if}  id="top100" top=100 >100</td>
<td {if $top==200}class="selected"{/if}  id="top200" top=200 >200</td>

</table>
{t}Top{/t}:
</div>

<div style="clear:left;width:350px;margin-top:20px" >

<table class="options" >

<td  {if $criteria=='net_balance'}class="selected"{/if} id="net_balance"  >{t}Balance{/t}</td>
	  <td {if $criteria=='invoices'}class="selected"{/if}  id="invoices"  >{t}Number of Invoices{/t}</td>
	  
</table>
{t}Based in{/t}:
</div>

<div id="the_table" class="data_table" style="clear:both">
  <span class="clean_table_title">Customers List</span>
<div style="float:right"><span class="state_details" id="export" output="{$export.type}" >{$export.label}</span></div>
 
  
  <div  class="clean_table_caption"  style="clear:both;display:none">
    <div style="float:left;"><div id="table_info0" class="clean_table_info" style="display:none"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    
	<div  style="display:none" class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator"></span></div></div>

      </div>
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>
</div>
{include file='footer.tpl'}

<div id="export_menu" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
     
      {foreach from=$export_menu key=key item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_export_type('export','{$key}','{$menu.label}')"> {$menu.title}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


