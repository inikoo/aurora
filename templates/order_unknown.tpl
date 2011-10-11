{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
    <div class="yui-b">
      <div style="text-align:right">
	<span class="state_details" id="cancel">Cancel</span>
	<span class="state_details" id="done" style="margin-left:20px">Send to Warehouse</span>

      </div>
      <div class="yui-b" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 0px 0 10px 0">

       <div style="xborder:1px solid #ddd;width:350px;float:left"> 
        <h1 style="padding:0 0 10px 0">{t}Order{/t} {$order->get('Order Public ID')}</h1>
        <h2 style="padding:0"><a href="customer.php?id={$order->get('order customer key')}">{$order->get('order customer name')} (ID:{$customer->get('Customer ID')})</a></h2>
        {$contact}<br/>
           {if $tel!=''}{t}Tel{/t}: {$tel}<br/>{/if}
	<div style="float:left;line-height: 1.0em;margin:5px 20px 0 0;color:#444;font-size:80%;width:140px"><span style="font-weight:500;color:#000">{t}Contact Address{/t}</span>:<br/><b>{$customer->get('Customer Main Contact Name')}</b><br/>{$customer->get('Customer Main XHTML Address')}</div>
	<div style="float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px"><span style="font-weight:500;color:#000">{t}Shipping Address{/t}</span>:<br/>{$order->get('Order XHTML Ship Tos')}</div>
	
<div style="clear:both"></div>
       </div>

          <div style="border:0px solid #ddd;width:190px;float:right">
	 <table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	   
	   <tr    ><td  class="aright" >{t}Items Gross{/t}</td><td width=100 class="aright" id="order_items_gross">{$order->get('Items Gross Amount')}</td></tr>
	   <tr  {if $order->get('Order Items Discount Amount')==0 }style="display:none"{/if}   ><td  class="aright" >{t}Discounts{/t}</td><td width=100 class="aright"  id="order_items_discount">-{$order->get('Items Discount Amount')}</td></tr>
	   
	  
	   <tr><td  class="aright" >{t}Items Net{/t}</td><td width=100 class="aright" id="order_net">{$order->get('Items Net Amount')}</td></tr>
	 
	   <tr  {if $order->get('Order Net Credited Amount')==0}style="display:none"{/if}><td  class="aright" >{t}Credits{/t}</td><td width=100 class="aright">{$order->get('Net Credited Amount')}</td></tr>
	   
	   {if  $order->get('Order Charges Net Amount')}<tr><td  class="aright" >{t}Charges{/t}</td><td width=100 class="aright">{$order->get('Charges Net Amount')}</td></tr>{/if}
	   <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}Shipping{/t}</td><td width=100 class="aright">{$order->get('Shipping Net Amount')}</td></tr>
	   <tr><td  class="aright" >{t}Net{/t}</td><td width=100 class="aright">{$order->get('Total Net Amount')}</td></tr>
	   
	   
	   <tr style="border-bottom:1px solid #777"><td  class="aright" >{t}VAT{/t}</td><td width=100 class="aright">{$order->get('Total Tax Amount')}</td></tr>
	   <tr><td  class="aright" >{t}Total{/t}</td><td width=100 class="aright"><b>{$order->get('Total Amount')}</b></td></tr>
	   
	 </table>
       </div>

       <div style="border:0px solid red;width:290px;float:right">
	 {if $note}<div class="notes">{$note}</div>{/if}
	 <table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right" >
	   
	   <tr><td>{t}Order Date{/t}:</td><td class="aright">{$order->get('Date')}</td></tr>
	   
	  
	 </table>
	 
       </div>
       
       
       <div style="clear:both"></div>
     </div>
<div class="data_table"  style="clear:both">
     <span id="table_title" class="clean_table_title">{t}Items{/t}</span>

     <div id="table_type">
       <span id="table_type_list" style="float:right;color:brown" class="table_type state_details {if $table_type=='list'}state_details_selected{/if}">{t}Recomendations{/t}</span>
       <span id="table_type_list" style="float:right;margin-right:10px" class="table_type state_details {if $table_type=='list'}state_details_selected{/if}">{t}List{/t}</span>
       <span id="table_type_thumbnail" style="float:right;margin-right:10px" class="table_type state_details {if $table_type=='thumbnails'}state_details_selected{/if}">{t}Thumbnails{/t}</span>
     </div>
     
     

     
    <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
      <span   style="float:right;margin-left:20px" class="state_details" state="{$show_all}"  id="show_all"  atitle="{if !$show_all}{t}Show only ordered{/t}{else}{t}Show all products available{/t}{/if}"  >{if $show_all}{t}Show only ordered{/t}{else}{t}Show all products available{/t}{/if}</span>     
      


    <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
       <tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	 <td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Discounts{/t}</td>
	 <td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Properties{/t}</td>
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
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;display:none"></div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  
</div>
      
      
    </div>
    {if $items_out_of_stock}
    <div style="clear:both;margin:30px 0" >
      <h2>{t}Items Out of Stock{/t}</h2>
      <div  id="table1" class="dtable btable" style="margin-bottom:0"></div>
    </div>
    {/if}
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



</div> 


<div id="dialog_cancel">
  <div style="text-align:left;margin-left:5px">{t}Reason of cancellation{/t}</div>
  <div id="cancel_msg"></div>
  
  <table >
    <tr><td colspan=2>
	<textarea style="height:100px" id="cancel_input" onkeyup="change(event,this,'cancel')"></textarea>
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text state_details" onClick="close_dialog('cancel')" >{t}Go Back{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="save('cancel')" id="cancel_save"  class="unselectable_text state_details"     style="visibility:hidden;" >{t}Continue{/t}</span></td></tr>
</table>
</div>





{include file='footer.tpl'}
