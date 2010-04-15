{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}


{include file='calendar_splinter.tpl'}




<h1 style="clear:left">{t}Sales without tax{/t} ({$period})</h1>


<div class="data_table" style="clear:both;">
    <span   class="clean_table_title">{t}Invoices{/t}</span>
   
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:0px solid #999"></div>
    
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      

      <div class="clean_table_filter" id="clean_table_filter0">
	
	<div class="clean_table_info" style="padding-bottom:1px;" >
	  <span id="filter_name0" style="margin-right:5px">{$filter_name0}:</span>
	  <input style="border-bottom:none;width:6em;border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/>
	  <div id='f_container0'></div>
	</div>
      </div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable with_total"> </div>
  </div>


<div class="data_table" style="clear:both;">
    <span   class="clean_table_title">{t}Customers{/t}</span>
   
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:0px solid #999"></div>
    
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="rtext_rpp" id="rtext_rpp1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
      

      <div class="clean_table_filter" id="clean_table_filter1">
	
	<div class="clean_table_info" style="padding-bottom:1px;" >
	  <span id="filter_name1" style="margin-right:5px">{$filter_name1}:</span>
	  <input style="border-bottom:none;width:6em;border-bottom:none" id='f_input1' value="{$filter_value1}" size=10/>
	  <div id='f_container1'></div>
	</div>
      </div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
    </div>
    <div  id="table1"   class="data_table_container dtable btable with_total"></div>
  </div>
</div> 


 
</div>
<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
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

<div id="rppmenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
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


{include file='footer.tpl'}

