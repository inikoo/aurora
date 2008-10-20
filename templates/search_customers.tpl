{include file='header.tpl'}
<div id="bd" >
  <span class="nav2 onleft"><a href="contacts.php">{t}Contact's List{/t}</a></span>
  <span class="nav2 onleft"><a href="customers.php">{t}Customer's List{/t}</a></span>
    
  <div >
    
 <div class="search_box" style="margin-top:30px;font-size:90%;" id="the_search_box" >

   <table>
     <tr><td colspan="" style="text-align:right;border-bottom:1px solid #ccc" >Search over:</td></tr>
     <tr><td style="text-align:right">{t}All Customers{/t}</td><td><input checked="checked" name="geo_group" value="all" type="radio"></td></tr>
     <tr><td style="text-align:right">{$home} {t}Customers{/t}</td><td><input checked="checked" name="geo_group" value="home" type="radio"></td></tr>
     <tr><td style="text-align:right">{t}Foreign Customers{/t}</td><td><input checked="checked" name="geo_group" value="nohome" type="radio"></td></tr>

   </table>
 </div>
    
      <h2 style="margin:10px 20px 0 20px">{t}Advanced Search{/t}</h2>
      <div id="advanced_search" tipo=1 style="margin:0px 20px ;padding:0 20px;width:700px;border:1px solid #ccc;">

      <table>
	<form>
	<tr><td colspan="2"><b>Customers who..</b></td></tr>
      <tr><td>ordered this product(s)</td><td><input id="product_ordered1" value="" size="40" /></td><tr>
      <tr><td>but didn't order this product(s)</td><td><input id="product_not_ordered1" value="" size="40" /></td><tr>
      <tr><td>and did't receive this product(s)</td><td><input id="product_not_received1" value="" size="40" /></td><tr>
      <tr><td>during this period</td><td><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> </td><tr>
	<tr><td colspan="2"><b>..and</b></td></tr>
      <tr><td>ordered this product(s)</td><td><input id="product_ordered2" value="" size="40" /></td><tr>
      <tr><td>but didn't order this product(s)</td><td><input id="product_not_ordered2" value="" size="40" /></td><tr>
      <tr><td>and did't receive this product(s)</td><td><input id="product_not_received2" value="" size="40" /></td><tr>
      <tr><td>during this period</td><td><input id="v_calpop3" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop3" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop4" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop4" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> </td><tr>
	  <tr><td colspan="2" style="text-align:right"><span  class="state_details"  id="submit_advanced_search">Start Search</span></td></tr>
      </table>
      </form>
    </div>

    
    <div id="the_table" class="data_table" style="margin:20px 20px;clear:both">
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
