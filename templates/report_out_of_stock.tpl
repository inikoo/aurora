{include file='header.tpl'}
<div id="bd" >
<div class="branch" style="width:280px;float:left;margin:0"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a> &rarr; <a  href="reports.php">{t}Reports{/t}</a> &rarr; {t}Out of Stock{/t}</span>
</div>
{include file='calendar_splinter.tpl'}
	<div style="clear:both"></div>
	<h1 style="margin-top:10px">
		{$title}, <span class="id">{$period}</span> <img id="show_calendar_browser" style="cursor:pointer;vertical-align:text-bottom;position:relative;top:-3px;{if $tipo=='f'}display:none{/if}" src="art/icons/calendar.png" alt="calendar" />
	</h1>







<div id="transactions" class="data_table" style="clear:both;margin-top:15px;{if $view!='transactions'}display:none{/if}">
  <span class="clean_table_title">Transactions with Out of Stock</span>
  <div style="float:right">
<span  onClick="change_view('parts')"  class="state_details" id="group_by_part" >{t}Group by Part{/t}</span>
<span style="margin-left:20px"class="state_details" id="export0" output="{$export0.type}" >{$export0.label}</span></div>
   <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
  {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>


<div id="parts"  class="data_table" style="clear:both;margin-top:15px;{if $view!='parts'}display:none{/if}">
  <span  class="clean_table_title">Parts Marked as Out of Stock</span>
  <div style="float:right">
<span  onClick="change_view('transactions')" class="state_details" id="show_individual_transactions" >{t}Show Individual Transactions{/t}</span>
<span style="margin-left:20px"class="state_details" id="export1" output="{$export1.type}" >{$export1.label}</span></div>
   <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
  {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1}
      <div  id="table1"   class="data_table_container dtable btable "> </div>
    </div>
</div>

<div id="filtermenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


<div id="filtermenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


{include file='footer.tpl'}




