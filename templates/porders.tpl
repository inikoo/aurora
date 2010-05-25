{include file='header.tpl'}
<div id="bd" >
 <div style="float:right;border: 0px solid #ddd;text-align:right;padding:10px">
    <form  id="prod_search_form" action="orders.php" method="GET" >
      <label>{t}Order Search{/t}:</label><input size="12" class="text search" id="prod_search" value="" name="search"/><img onclick="document.getElementById('prod_search_form').submit()"align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search">
    </form>
    <form action="orders.php?" method="GET" style="margin-top:10px">
      <div style="position:relative;left:18px">{t}Interval{/t}: <input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	<img style="position:relative;right:26px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="submit_interval"  xonclick="document.forms[1].submit()" alt="{t}Go{/t}" /> 
      </div>
    </form>
    <div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
    <div style="position:relative;right:-80px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>
    <span  class="state_details"  id="show_details">{t}Orders Overview{/t}</span>
  </div>

  <div id="top" class="top_bar">
    <div id="short_menu" class="nodetails" style="{if $show_details}display:none;{/if}width:100%;margin-bottom:0px">
      <table style="float:left;margin:0 0 0 20px ;padding:0"  class="options" >
	<tr>
	  <td  {if $view=='all'}class="selected"{/if} id="all" >{t}Orders{/t}</td>
	  <td {if $view=='invoices'}class="selected"{/if}  id="invoices"  >{t}Invoices{/t}</td>
	  <td style="padding:0;font-weight:100;color:#777;padding:0 0 0 25px;cursor:default;;border:none"><span  class="state_details"  id="show_details">{t}show only{/t}</span></td>

	  <td  style="" {if $view=='in_process'}class="selected"{/if}  id="in_process"  >{t}In Process{/t}</td>
	  <td  style="" {if $view=='cancelled'}class="selected"{/if}  id="cancelled"  >{t}Cancelled{/t}</td>

	  

	</tr>
      </table>
    </div>
    
    <div id="details" class="details" style="{if !$show_details}display:none;{/if}">
	<div id="details_all" style="font-size:90%;margin-top:10px" {if $view!='all'} style="display:none"{/if}></div>
	<div id="details_invoices" style="font-size:90%;margin-top:10px" {if $view!='invoides'} style="display:none"{/if}></div>
	<div id="details_in_process" style="font-size:90%;margin-top:10px" {if $view!='in_process'} style="display:none"{/if}></div>
	<div id="details_cancelled" style="font-size:90%;margin-top:10px" {if $view!='cancelled'} style="display:none"{/if}></div>
	</div>
  </div>
  
  
  
  
  <div class="data_table" style="margin:25px 20px;">
    <span class="clean_table_title">{t}{$table_title}{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter"  id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
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

{include file='footer.tpl'}
