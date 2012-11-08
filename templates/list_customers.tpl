{include file='header.tpl'}
<div id="bd" >
  <span class="nav2 onleft"><a href="contacts.php">{t}Contact's List{/t}</a></span>
  <span class="nav2 onleft"><a href="customers.php">{t}Customer's List{/t}</a></span>
    
  <div >
    
 <div class="search_box" style="margin-top:30px;font-size:90%;" id="the_search_box" >

   <table>
     <tr><td colspan="" style="text-align:right;border-bottom:1px solid #ccc" >Search over:</td></tr>
     <tr><td style="text-align:right">{t}All Customers{/t}</td><td><input checked="checked" name="geo_group" id="geo_group_all" value="all" type="radio"></td></tr>
     <tr><td style="text-align:right">{$home} {t}Customers{/t}</td><td><input  name="geo_group"  id="geo_group_home" value="home" type="radio"></td></tr>
     <tr><td style="text-align:right">{t}Foreign Customers{/t}</td><td><input  name="geo_group"  id="geo_group_nohome" value="nohome" type="radio"></td></tr>
     <tr><td colspan="" style="text-align:right;border-bottom:1px solid #ccc;height:30px;vertical-align:bottom" >Only Customers:</td></tr>
     <tr><td style="text-align:right">{t}with Email{/t}</td><td><input   id="with_email"  type="checkbox"></td></tr>
     <tr><td style="text-align:right">{t}with Telephone{/t}</td><td><input    id="with_tel"  type="checkbox"></td></tr>

   </table>
 </div>
    
      <h2 style="margin:10px 20px 0 20px">{t}Advanced Search{/t}</h2>
      <div id="advanced_search" tipo=1 style="margin:0px 20px ;padding:0 20px;width:700px;border:1px solid #ccc;">

      <table >
	<form>
	<tr><td colspan="2"><b>Customers who..</b></td></tr>
      <tr><td>ordered this product(s)</td><td><input id="product_ordered1" value="" size="40" /></td><tr>
      <tr><td>but didn't order this product(s)</td><td><input id="product_not_ordered1" value="" size="40" /></td><tr>
      <tr><td>and did't receive this product(s)</td><td><input id="product_not_received1" value="" size="40" /></td><tr>
      <tr><td>during this period</td><td><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> </td><td  style="text-align:right;vertical-align:middle"><span  style="margin-left:20px;border:1px solid #ccc;padding:4px 5px;cursor:pointer" id="submit_advanced_search">Start Search <img style="vertical-align:bottom" src="art/icons/zoom.png"/></span>
</td></tr>
      </table>
      </form>
    </div>
      
      <div style="padding:30px 40px;display:none" id="searching">
	{t}Search in progress{/t} <img src="art/progressbar.gif"/>
      </div>

    
    <div id="the_table" class="data_table" style="margin:20px 20px;clear:both;display:none" >
      
      <h2 >{t}{$table_title}{/t}</h2>
      <div id="short_menu" class="nodetails" style="clear:both;width:100%;margin-bottom:0px">
      <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	  <td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='contact'}class="selected"{/if}  id="contact"  >{t}Contact{/t}</td>
	</tr>
      </table>
      <table style="float:right;margin:0 0 0 0px ;padding:0" >
	<tr>
	  <td>{t}Export the result list as{/t}:</td>
	  <td><img src="art/icons/page_excel.png" style="vertical-align:bottom"/><a  href="csv.php?tipo=cas" class="state_details" style="color:black;position:relative;bottom:1px">{t}CSV file{/t}</a></td>
	</tr>
      </table>
    </div>



      
      <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	<div class="clean_table_filter" id="clean_table_filter0" style="display:none"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
	  <div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
	</div>
	<div  id="table0"   class="data_table_container dtable btable"> </div>
      </div>

    </div>
  </div>
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
