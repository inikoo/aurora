{include file='header.tpl'}
<div id="time2_picker" class="time_picker_div"></div>
<div id="bd" >


<input id="supplier_delivery_note_key" value="{$supplier_dn->id}" type="hidden"/>

<div class="order_actions" >
    <span class="state_details" onClick="location.href='supplier.php?id={$supplier->get('Supplier Key')}'" style="float:left;margin-top:2px" >{t}Supplier Page{/t}</span>

  <span class="state_details" id="delete_dn">{t}Delete{/t}</span>
  <span class="state_details" id="save_inputted_dn" style="margin-left:20px">{t}Save Delivery Note{/t}</span>
</div>


<div class="prodinfo" style="margin-top:2px;font-size:85%;border:1px solid #ddd;padding:10px">
    <div style="border:0px solid red;width:290px;float:right">
    <table  border=0  class="order_header"  style="margin-right:30px;float:right">
      <tr><td class="aright" style="padding-right:40px">{t}Created{/t}:</td><td>{$supplier_dn->get('Creation Date')}</td></tr>
    
    </table>
    </div>
    
    
    <h1 style="padding:0px 0 10px 0;width:500px;xborder:1px solid red" id="po_title">{t}Supplier Delivery Note{/t}: {$supplier_dn->get('Supplier Delivery Note Public ID')} ({$supplier_dn->get('Supplier Delivery Note Current State')})</h1>
    <table border=0 >
      <tr><td>{t}Supplier Delivery Note Key{/t}:</td><td class="aright">{$supplier_dn->get('Supplier Delivery Note Key')}</td></tr>
      <tr><td>{t}Supplier{/t}:</td><td class="aright"><a href="supplier.php?id={$supplier->get('Supplier Key')}">{$supplier->get('Supplier Name')}</a></td></tr>
      <tr><td>{t}Items{/t}:</td><td class="aright" id="distinct_products">{$supplier_dn->get('Number Items')}</td></tr>
    </table>

  
    <table style="clear:both;border:none;display:none" class="notes">
      
      <tr><td style="border:none">{t}Notes{/t}:</td><td style="border:none"><textarea id="v_note" rows="2" cols="60" ></textarea></td></tr>
    </table>
    
  <div style="clear:both"></div>
  
</div>





<div id="the_table" class="data_table" style="margin:20px 0px;clear:both">
  <span class="clean_table_title">{t}Supplier Products{/t}</span>
  	<div id="table_type">
	  <span id="take_values_from_pos" style="float:right;color:brown;display:none" class="table_type state_details">{t}Take values from Purchase Order{/t}</span>
	  
	</div>



  <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
      <span   style="float:right;margin-left:20px" class="state_details" state="{$show_all}"  id="show_all"  atitle="{if !$show_all}{t}Show only Purcase Order products + Additions{/t}{else}{t}Show all products available{/t}{/if}"  >{if $show_all}{t}Show only Purcase Order products + Additions{/t}{else}{t}Show all products available{/t}{/if}</span>     
      

      
      <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
	<tr><td  {if $view=='used_in'}class="selected"{/if} id="general" >{t}Used In{/t}</td>
	  <td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>
	  <td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>
	</tr>
      </table>
      <table id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td  {if $period=='all'}class="selected"{/if} period="all"  id="period_all" >{t}All{/t}</td>
	  <td {if $period=='year'}class="selected"{/if}  period="year"  id="period_year"  >{t}1Yr{/t}</td>
	  <td  {if $period=='quarter'}class="selected"{/if}  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
	  <td {if $period=='month'}class="selected"{/if}  period="month"  id="period_month"  >{t}1M{/t}</td>
	  <td  {if $period=='week'}class="selected"{/if} period="week"  id="period_week"  >{t}1W{/t}</td>
	</tr>
      </table>
      <table  id="avg_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td {if $avg=='totals'}class="selected"{/if} avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	  <td {if $avg=='month'}class="selected"{/if}  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	  <td {if $avg=='week'}class="selected"{/if}  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>
	  <td {if $avg=='month_eff'}class="selected"{/if} style="display:none" avg="month_eff"  id="avg_month_eff"  >{t}M EAVG{/t}</td>
	  <td {if $avg=='week_eff'}class="selected"{/if} style="display:none"  avg="week_eff"  id="avg_week_eff"  >{t}W EAVG{/t}</td>
	</tr>
      </table>
    </div>

  

  	   {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}

  <div  id="table0"  style="font-size:80%" class="data_table_container dtable btable "> </div>
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


<div id="delete_dialog" class="nicebox">
<div class="db">
  <div id="delete_dialog_msg" class="dialog_msg" style="padding:0 0 10px 0 ">{t}Note: this action can not be undone{/t}.</div>
  <table style="width:250px">
   
    <tr><td style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0">
	<span class="state_details" onClick="close_dialog('delete')"  >{t}Cancel{/t}</span>
	<span style="margin-left:50px" class="state_details" onClick="delete_order()"  >{t}Delete Supplier Delivery Note{/t}</span>
    
    </td>
</tr>
  </table>
  </div>
</div>









{include file='footer.tpl'}

