{include file='header.tpl'}
<div id="bd" style="padding:0 20px">
<span class="nav2 onright"  {if $next.id==0}style="display:none"{/if} ><a id="next" href="customer.php?id={$next.id}">{$next.code} &rarr; </a></span>
<span class="nav2 onright"  {if $prev.id==0}style="display:none"{/if}  ><a id="prev" href="customer.php?id={$prev.id}">&larr; {$prev.code}</a></span>
<span class="nav2 onleft"><a href="customers.php">{t}Customers List{/t}</a></span>


<span class="nav2"><a href="customers.php">{$home}</a></span>


  <div id="yui-main" >

  <div class="search_box" >
       <span class="search_title" style="padding-right:15px" tipo="customer_name">{t}Customer Name{/t}:</span> <br>
       <input size="8" class="text search" id="customer_search" value="" name="search"/><img align="absbottom" id="customer_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
       <span  class="product_search_msg"   id="customer_search_msg"    ></span> <span  class="search_sugestion"   id="customer_search_sugestion"    ></span>
       <br/>
       <span id="but_show_details" state="{$details}" atitle="{if $details==0}{t}Hide Details{/t}{else}{t}Show Details{/t}{/if}" class="state_details"   >{if $details==1}{t}Hide Details{/t}{else}{t}Show Details{/t}{/if}</span>
       <br/><span id="but_edit" title="{t}Edit Customer Data{/t}" class="state_details"   >{t}Edit Customer{/t}</span>

       <table style="float:right;margin-top:20px">
	 <tr><td class="but" id="note">{t}Quick Note{/t}</td></tr>
	 <tr style="display:none"><td class="but" id="long_note">{t}Long Note{/t}</td></tr>
	 <tr style="display:none"><td class="but" id="attach">{t}Attach File{/t}</td></tr>
	 <tr style="display:none"><td class="but" id="call" >{t}Call{/t}</td></tr>
	 <tr style="display:none"><td class="but" id="email" >{t}Email{/t}</td></tr>
	 <tr style="display:none"><td class="but" id="others" >{t}Other{/t}</td></tr>

       </table>
       
  </div>


    <div class="yui-b" >
       <h1>{$customer->data.name} <span style="color:SteelBlue">{$id}</span></h1> 
<table id="customer_data" style="width:500px" border=0>

<tr>
{if $customer->data.main_bill_address_id}<td valign="top">{$customer->data.main_bill_address}</td>{/if}
<td  valign="top">
<table border=0 style="padding:0">
{if $customer->data.main_contact_id}<tr><td colspan=2>{$customer->data.main_contact_name}</td ></tr>{/if}
{if $customer->data.main_email_id}<tr><td colspan=2>{$customer->data.main.email}</td ></tr>{/if}
{if $customer->data.main_tel_id}<tr><td colspan=2 class="aright">{$customer->data.main_tel}</td ></tr>{/if}

{foreach from=$telecoms item=telecom}
<tr><td >
{if $telecom[0]=='mob'}<img src="art/icons/phone.png"/ title="{t}Mobile Phone{/t}">
{elseif   $telecom[0]=='tel'}<img src="art/icons/telephone.png"/ title="{t}Telephone{/t}">
{elseif   $telecom[0]=='email'}<img src="art/icons/email.png"/ title="{t}Email Address{/t}">
{elseif   $telecom[0]=='fax'}<img src="art/icons/printer.png"/ title="{t}Fax{/t}">
{/if}
</td><td class="aright" style="padding-left:10px">{$telecom[1]}</td></tr>
{/foreach}
</table>
</td>
</tr>

</table>



<div >
  <h2 style="font-size:150%">{t}Orders Overview{/t}</h2>
  <table style="padding:0;margin:0;border-top:1px solid black;;border-bottom:1px solid black;width:500px">
    <tr><td>
	{if $customer->data.num_invoices==1}
	{$customer->data.name} {t}has place one order of{/t} {$total_net}.  
	{elseif $customer->data.num_invoices>1 } 
	{$customer->data.name} {t}has placed{/t} <b>{$invoices}</b> {t}orders so far{/t}, {t}which amounts to a total of{/t} <b>{$total_net}</b> {t}plus tax{/t} ({t}an average of{/t} {$total_net_average} {t}per order{/t}).
	{if $orders_interval}<br/>{t}This customer usually places an order every{/t} {$orders_interval}.{/if}
	{/if}
	
    </td></tr>
  </table>
</div>

      
 <div class="data_table" style="margin:25px 0">
    <span class="clean_table_title">{t}History{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span  id="rtext_rpp0" class="rtext_rpp"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="filter_div0"  ><div class="clean_table_info" ><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>


    </div>
  </div>
    <div class="yui-b">
    </div>

</div> 

<div id="dialog_note">
  <div id="note_msg"></div>
  <table >
    <tr><td colspan=2>
	<textarea id="note_input" onkeyup="change(event,this,'note')"></textarea>
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text button" onClick="close_dialog('note')" >{t}Cancel{/t} <img src="art/icons/cross.png"/></span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="save('note')" id="note_save"  class="unselectable_text button"     style="visibility:hidden;" >{t}Save{/t} <img src="art/icons/disk.png" ></span></td></tr>
</table>
</div>

<div id="dialog_long_note">
  <div id="long_note_msg"></div>
  <table >
    <tr><td colspan=2>
	<textarea id="long_note_input"></textarea>
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text button" onClick="close_dialog('long_note')" >{t}Cancel{/t} <img src="art/icons/cross.png"/></span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="save('long_note')" id="long_note_save"  class="unselectable_text button"   >{t}Save{/t} <img src="art/icons/disk.png" ></span></td></tr>
</table>
</div>



<div>

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

