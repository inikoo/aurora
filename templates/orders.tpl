{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
    <div class="yui-b">
      <h2>{t}Orders{/t}</h2>
      <div class="yui-b" style="border:1px solid #ccc;text-align:left;margin:0px;padding:10px;height:60px;margin: 0 0 10px 0">
	<div style="float:right;border: 0px solid #ddd;text-align:right">
	 {include file='order_search.tpl'}
	<br/>
	<form action="orders.php?" method="GET">
<div style="position:relative;left:18px"><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	  <img style="position:relative;right:26px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" onclick="document.forms[1].submit()" alt="{t}Go{/t}" /> 
</div>
	</form>
	<div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
	<div style="position:relative;right:-80px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>
	</div>
	</div>


        <div class="data_table" style="margin-top:25px">
	<span class="clean_table_title">{t}{$table_title}{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div id="table_info0" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg0"></span></div></div>
	  <div class="clean_table_filter"><div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
	</div>
	<div  id="table0"   class="data_table_container dtable btable "> </div>
      </div>



</div>

    </div>
  </div>
</div>
</div>

<div id="filtermenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

{include file='footer.tpl'}
