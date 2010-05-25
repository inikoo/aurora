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
 
  
  {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
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


