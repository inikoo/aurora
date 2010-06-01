{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}


   
      <h2 style="clear:both">{t}Customers Lists{/t}</h2>
<div style="border:1px solid #ccc;padding:20px;width:690px">
      <table >
	<form>
	<tr><td colspan="2"><b>Customers who..</b></td></tr>
      <tr><td>ordered this product(s)</td><td><input id="product_ordered1" value="" style="width:500px" /></td><tr>
      <tr style="display:none"><td>but didn't order this product(s)</td><td><input id="product_not_ordered1" value="" style="width:400px" /></td><tr>
      <tr style="display:none"><td>and did't receive this product(s)</td><td><input id="product_not_received1" value="" size="40" /></td><tr>
      <tr><td>during this period</td><td><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   />
      <div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
    <div style="position:relative;right:-80px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>
      </div>
      </td></tr>
      <tr style="height:40px"><td colspan=2   style="text-align:right;vertical-align:bottom;"><span  style="margin-right:280px;border:1px solid #ccc;padding:4px 5px;cursor:pointer" id="submit_search">Start Search <img style="vertical-align:bottom" src="art/icons/zoom.png"/></span></td></tr>
      </table>
      </form>
       </table>
</div>      
    <div style="padding:30px 40px;display:none" id="searching">
	{t}Search in progress{/t} <img src="art/progressbar.gif"/>
    </div>

    
    <div id="the_table" class="data_table" style="margin-top:20px;clear:both;display:none" >
    <span class="clean_table_title">Customers List</span>
 <div id="table_type">
         <a  style="float:right"  class="table_type state_details"  href="customers_list_csv.php" >{t}Export (CSV){/t}</a>

     </div>


  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>

      <div id="short_menu" class="nodetails" style="clear:both;width:100%;margin-bottom:0px">
      <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	  <td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='contact'}class="selected"{/if}  id="contact"  >{t}Contact{/t}</td>
	</tr>
      </table>
      
    </div>



      
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=true }
     	<div  id="table0"   class="data_table_container dtable btable "> </div>
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
<div class="search_box" style="margin-top:30px;font-size:90%;display:none" id="the_search_box" >

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
{include file='footer.tpl'}
