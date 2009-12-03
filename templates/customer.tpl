{include file='header.tpl'}
<div id="bd" style="padding:0 20px">
<span class="nav2 onright"  {if $next.id==0}style="display:none"{/if} ><a id="next" href="customer.php?id={$next.id}">{$next.name} &rarr; </a></span>
<span class="nav2 onright"  {if $prev.id==0}style="display:none"{/if}  ><a id="prev" href="customer.php?id={$prev.id}">&larr; {$prev.name}</a></span>
<span class="nav2 onleft"><a href="customers.php">{t}Customers List{/t}</a></span>


<span class="nav2"><a href="customers.php">{$home}</a></span>


  <div id="yui-main" >

  <div class="search_box" >
       <span class="search_title" style="padding-right:15px" tipo="customer_name">{t}Customer Name{/t}:</span> <br>
       <input size="8" class="text search" id="customer_search" value="" name="search"/><img align="absbottom" id="customer_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
       <span  class="product_search_msg"   id="customer_search_msg"    ></span> <span  class="search_sugestion"   id="customer_search_sugestion"    ></span>
       <br/>
       <span id="but_show_details" state="{$details}" atitle="{if $details==0}{t}Hide Details{/t}{else}{t}Show Details{/t}{/if}" class="state_details"   >{if $details==1}{t}Hide Details{/t}{else}{t}Show Details{/t}{/if}</span>

       


       <br/><a id="but_edit" href="customer.php?edit={$customer->id}" title="{t}Edit Customer Data{/t}" class="state_details" href=""   >{t}Edit Customer{/t}</a>

       <table style="float:right;margin-top:20px">
	 <tr><td class="but" id="note">{t}Quick Note{/t}</td></tr>
	 <tr><td class="but" id="attach">{t}Attach File{/t}</td></tr>

	 <tr><td class="but" id="take_order">{t}Take Order{/t}</td></tr>

	 <tr style="display:none"><td class="but" id="long_note">{t}Long Note{/t}</td></tr>
	 <tr style="display:none"><td class="but" id="attach">{t}Attach File{/t}</td></tr>
	 <tr style="display:none"><td class="but" id="call" >{t}Call{/t}</td></tr>
	 <tr style="display:none"><td class="but" id="email" >{t}Email{/t}</td></tr>
	 <tr style="display:none"><td class="but" id="others" >{t}Other{/t}</td></tr>

       </table>
       
  </div>


<table border=0 cellpadding="2" style="float:right;margin-top:5px;" class="view_options">
      <tr style="border-bottom:1px solid #ddd">
	
	<th><img src="art/icons/information.png" title="{t}Product Details{/t}"/></th>
	<th><img src="art/icons/chart_line.png" title="{t}Charts{/t}"/></th>
	<th><img  src="art/icons/cart.png" title="{t}Orders{/t}"/></th>
	<th><img src="art/icons/user_green.png" title="{t}Customers{/t}"/></th>
	<th><img src="art/icons/package.png" title="{t}Stock{/t}"/></th>
      </tr>
      <tr style="height:18px;border-bottom:1px solid #ddd">
	<td  id="change_view_details" 
	     {if $display.details==0}title="{t}Show Product Details{/t}" atitle="{t}Hide Product Details{/t}"{else}atitle="Hide Product Details"  title="{t}Hide Product Details{/t}"{/if} >
	  <img {if $hide.details==0}style="opacity:0.2"{/if} src="art/icons/tick.png"  id="but_logo_details"  /></td>
	{if $view_orders}
	<td  id="change_view_plot" state="{$display.plot}" block="plot"  
	     {if $display.plot==0} title="{t}Show Charts{/t}" atitle="{t}Hide Charts{/t}"{else} atitle="{t}Show Charts{/t}" title="{t}Hide Charts{/t}"{/if} >
	  <img {if $display.plot==0}style="opacity:0.2"{/if} src="art/icons/tick.png"  id="but_logo_plot"  /></td>
	
	<td  state="{$display.orders}" block="orders"  id="change_view_orders" 
	     {if $display.orders==0}title="{t}Show Orders{/t}" atitle="{t}Hide Orders{/t}" {else} atitle="{t}Show Orders{/t}" title="{t}Hide Orders{/t}" {/if} >
	  <img {if $display.orders==0}style="opacity:0.2"{/if} src="art/icons/tick.png"    id="but_logo_orders"   /></td>
	{/if}
	<td  state="{$display.customers}" block="customers"   id="change_view_customers" {if $display.customers==0}title="{t}Show Customers who have ordered this product{/t}" atitle="{t}Hide Customers who have ordered this product{/t}"{else}atitle="{t}Show Customers who have ordered this product{/t}" title="{t}Hide Customers who have ordered this product{/t}"{/if} ><img {if $display.customers==0}style="opacity:0.2"{/if} src="art/icons/tick.png"    id="but_logo_customers"   /></td>
	<td   state="{$display.stock_history}" block="stock_history"  id="change_view_stock_history" {if $display.stock_history==0}title="{t}Show Stock History{/t}" atitle="{t}Hide Stock History{/t}"{else}atitle="{t}Show Stock History{/t}" title="{t}Hide Stock History{/t}"{/if} ><img {if $display.stock_history==0}style="opacity:0.2"{/if} src="art/icons/tick.png"    id="but_logo_stock_history"   /></td>
	
      </tr>


      
    </table>



    <div class="yui-b" >
       <h1>{$customer->get('Customer Name')} <span style="color:SteelBlue">{$id}</span></h1> 
<table id="customer_data" style="width:500px" border=0>

<tr>
{if $customer->get('Customer Main Address Key')}<td valign="top">{$customer->get('Customer Main XHTML Address')}</td>{/if}
<td  valign="top">
<table border=0 style="padding:0">
{if $customer->get('Customer Main Contact Key')}<tr><td colspan=2  class="aright">{$customer->get('Customer Main Contact Name')}</td ></tr>{/if}
{if $customer->get('Customer Main Email Key')}<tr><td colspan=2  class="aright">{$customer->get('customer main XHTML email')}</td ><td><img src="art/icons/email.png"/></td></tr>{/if}

{if $customer->get('Customer Main Telephone Key')}<tr><td colspan=2 class="aright">{$customer->get('Customer Main Telephone')}</td ><td><img src="art/icons/telephone.png"/></td></tr>{/if}


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
	{if $customer->get('Customer Orders')==1}
	{$customer->get('Customer Name')} {t}has place one order{/t}.  
	{elseif $customer->get('Customer Orders')>1 } 
	{$customer->get('customer name')} {t}has placed{/t} <b>{$customer->get('Customer Orders')}</b> {t}orders so far{/t}, {t}which amounts to a total of{/t} <b>{$customer->get('Net Balance')}</b> {t}plus tax{/t} ({t}an average of{/t} {$customer->get('Total Net Per Order')} {t}per order{/t}).
	{if $customer->get('Customer Orders Invoiced')}<br/>{t}This customer usually places an order every{/t} {$customer->get('Order Interval')}.{/if}
	{else}
	Customer has not place any order yet.
	{/if}
	
    </td></tr>
  </table>
</div>

      
<div class="data_table" style="margin:25px 0">
  <span class="clean_table_title">{t}History/Notes{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span  id="rtext_rpp0" class="rtext_rpp"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="filter_div0"  >
	<div class="clean_table_info" >
	  <span id="filter_name0">{$filter_name}</span>: 
	  <input style="border-bottom:none;width:10em" id='f_input0' value="{$filter_value}" size="10" />
	  <div id='f_container'></div></div></div>
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

<div id="dialog_attach">
  <div id="attach_msg"></div>
  <table >
     <tr><td colspan=2>

	  {t}Note{/t}:<br/> <input type="text" id="attach_note"/>

    </td><tr>
    <tr><td colspan=2>
	<form action="upload.php" enctype="multipart/form-data" method="post" id="attach_form">


	  <input type="file" name="testFile"/>

	</form>
    </td><tr>
	
    <tr class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="state_details" onClick="close_dialog('attach')" >{t}Cancel{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="save('attach')" id="upload_attach"  class="state_details"     xstyle="visibility:hidden;" >{t}Upload{/t}</span></td></tr>
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

