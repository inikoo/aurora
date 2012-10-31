{include file='header.tpl'}
<div id="bd">

{include file='assets_navigation.tpl'}
<div class="branch"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr;  {/if} <a href="marketing.php?store={$store->id}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr;  <a href="store_deals.php?store={$store->id}">{t}Offers{/t}</a></span> &rarr; {t}New Offer{/t}</span>
</div>



'Order Total Net Amount AND Order Number',
'Order Items Net Amount AND Shipping Country',
'Order Interval',
'Product Quantity Ordered',
'Family Quantity Ordered',
'Total Amount',
'Order Number',
'Total Amount AND Shipping Country',
'Total Amount AND Order Number',
'Voucher'

	<div class="top_page_menu">
  <div class="buttons" style="float:left">
<span class="main_title">{t}New Offer{/t}</span>
    </div>


<div class="buttons">
<button class="negative" onclick="window.location='store_offers.php?store={$store->id}'" >{t}Cancel{/t}</button>
</div>




<div style="clear:both"></div>
</div>

<table class="edit" style="margin-top:20px">
<tr>
<td class="label">{t}Offer Name{/t}:</td>
  <td  style="text-align:left;width:400px">
     <div   >
       <input style="text-align:left;width:370px" id="deal_name" value='' ovalue="" >
       <div id="deal_name_Container"  ></div>
     </div>
   </td>
   <td>
  <div style="float:left;width:180px" id="deal_name_msg" class="edit_td_alert"></div>
</tr>
<td class="label">{t}Offer Description{/t}:</td>
  <td  style="text-align:left;width:400px">
     <div   >
       <textarea style="text-align:left;width:370px" id="deal_name" value='' ovalue="" ></textarea>
       <div id="deal_name_Container"  ></div>
     </div>
   </td>
   <td>
  <div style="float:left;width:180px" id="deal_name_msg" class="edit_td_alert"></div>
</tr>
</table>


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



 </div>

{include file='footer.tpl'}
