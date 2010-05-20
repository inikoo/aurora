{include file='header.tpl'}
<div id="bd" >
 {include file='assets_navigation.tpl'}

  
  <div class="search_box" >
     <span class="search_title" style="padding-right:15px">{t}Part SKU{/t}:</span> <input size="8" class="text search" id="prod_search" value="" name="search"/><img align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
    <span  class="search_msg"   id="search_msg"    ></span> <span  class="search_sugestion"   id="search_sugestion"    ></span>
   
  </div>





<div class="data_table" style="clear:both;">
    <span class="clean_table_title">{t}Parts{/t}</span>
 <div style="clear:both;margin:0 0px;padding:0 0px ;border-bottom:1px solid #999"></div>
 <table style="float:left;margin:0 0 0 0px ;padding:0" class="options" >
	<tr>
	  <td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  {if $view_stock}<td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>{/if}
	</tr>
      </table>
       <table  id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view=='general' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td  {if $period=='all'}class="selected"{/if} period="all"  id="period_all" >{t}All{/t}</td>
	  <td {if $period=='year'}class="selected"{/if}  period="year"  id="period_year"  >{t}1Yr{/t}</td>
	  <td  {if $period=='quarter'}class="selected"{/if}  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
	  <td {if $period=='month'}class="selected"{/if}  period="month"  id="period_month"  >{t}1M{/t}</td>
	  <td  {if $period=='week'}class="selected"{/if} period="week"  id="period_week"  >{t}1W{/t}</td>
	</tr>
      </table>
       <table id="avg_options"  style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td {if $avg=='totals'}class="selected"{/if} avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	  <td {if $avg=='month'}class="selected"{/if}  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	  <td {if $avg=='week'}class="selected"{/if}  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>
	  <td {if $avg=='month_eff'}class="selected"{/if} style="display:none" avg="month_eff"  id="avg_month_eff"  >{t}M EAVG{/t}</td>
	  <td {if $avg=='week_eff'}class="selected"{/if} style="display:none"  avg="week_eff"  id="avg_week_eff"  >{t}W EAVG{/t}</td>
	</tr>
       </table>
    <div  class="clean_table_caption"  style="clear:both;">
   <div style="float:left;">
	   <div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div>
	 </div>
	 
	 <div class="clean_table_filter clean_table_filter_show" id="clean_table_filter_show0" {if $filter_show0}style="display:none"{/if}>{t}filter results{/t}</div>
	 <div class="clean_table_filter" id="clean_table_filter0" {if !$filter_show0}style="display:none"{/if}>
     <div class="clean_table_info" style="padding-bottom:1px; ">
	 <span id="filter_name0" style="margin-right:5px">{$filter_name0}:</span>
	 <input style="border-bottom:none;width:6em;" id='f_input0' value="{$filter_value0}" size=10/> <span class="clean_table_filter_show" id="clean_table_filter_hide0" style="margin-left:8px">{t}Hide filter{/t}</span>
	 <div id='f_container0'></div>
	  </div>
	   
	 </div>

	 <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
       </div>
  
  
    <div  id="table0"   class="data_table_container dtable btable with_total"> </div>
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


{include file='footer.tpl'}
