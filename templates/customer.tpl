{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0px 20px">
  {include file='contacts_navigation.tpl'}
  <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1 style="padding-bottom:0px">{$customer->get('Customer Name')} <span style="color:SteelBlue">{$id}</span>
      {if $next.id>0}<a class="prev" href="customer.php?id={$prev.id}" ><img src="art/icons/previous.png" alt="<" title="{$prev.name}"  /></a>{/if}
      {if $next.id>0}<a class="next" href="customer.php?id={$next.id}" ><img src="art/icons/next.png" alt=">" title="{$next.name}"  /></a>{/if}
      
    </h1> 

{if $customer->get('Customer Tax Number')!=''}<h2 style="padding:0">{$customer->get('Customer Tax Number')}</h2>{/if}    
  </div>
  
  
     
  
  <table class="quick_button" style="clear:both;float:right;margin-top:0px;">
    <tr><td  id="note">{t}Quick Note{/t}</td></tr>
    <tr><td  id="attach">{t}Attach File{/t}</td></tr>
    
    <tr><td id="take_order">{t}Take Order{/t}</td></tr>
    <tr><td id="make_order">{t}Make Order{/t}</td></tr>


    <tr style="display:none"><td  id="long_note">{t}Long Note{/t}</td></tr>
    <tr style="display:none"><td  id="attach">{t}Attach File{/t}</td></tr>
    <tr style="display:none"><td id="call" >{t}Call{/t}</td></tr>
<tr style="display:none"><td  id="email" >{t}Email{/t}</td></tr>
    <tr style="display:none"><td id="others" >{t}Other{/t}</td></tr>
    
  </table>
       

     
     
     
<table id="customer_data" style="width:500px" border=0>

<tr>
{if $customer->get('Customer Main Address Key')}<td valign="top">{$customer->get('Customer Main XHTML Address')}</td>{/if}
<td  valign="top">
<table border=0 style="padding:0">
{if $customer->get('Customer Main Contact Key')}<tr><td colspan=2  class="aright">{$customer->get('Customer Main Contact Name')}</td ></tr>{/if}
{if $customer->get('Customer Main Email Key')}<tr><td colspan=2  class="aright">{$customer->get('customer main XHTML email')}</td ><td><img src="art/icons/email.png"/></td></tr>{/if}

{if $customer->get('Customer Main Telephone Key')}<tr><td colspan=2 class="aright">{$customer->get('Customer Main XHTML Telephone')}</td ><td><img src="art/icons/telephone.png"/></td></tr>{/if}


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

</div>
  <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='history'}selected{/if}"  id="details">  <span> {t}History, Notes{/t}</span></span></li>
    <li> <span class="item {if $view=='orders'}selected{/if}"  id="discounts">  <span> {t}Orders{/t}</span></span></li>
    <li> <span class="item {if $view=='products'}selected{/if}" id="pictures"  ><span>  {t}Products Ordered{/t}</span></span></li>

  </ul>
  <div  style="clear:both;width:100%;border-bottom:1px solid #ccc">
  </div>
  
  
  
 <div id="block_history" class="data_table" style="{if $view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
      <span class="clean_table_title">{t}History/Notes{/t}</span>
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>


<div id="block_products" class="data_table" style="{if $view!='products'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
      <span class="clean_table_title">{t}Products Ordered{/t}</span>
 {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
       <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>

<div id="block_orders" class="data_table" style="{if $view!='orders'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
      <span class="clean_table_title">{t}Orders{/t}</span>
 {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }
       <div  id="table2"   class="data_table_container dtable btable "> </div>
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


<div id="dialog_make_order">
  <div id="long_note_msg"></div>
  <table >
    <tr><td colspan=2>{t}Courier{/t}:</td><tr><tr><td colspan=2><input /></td></tr>
    
    <tr><td colspan=2>{t}Special Instructions{/t}:</td></tr>
    <tr><td colspan=2>
	<textarea id="make_order_special_instructions"></textarea>
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%">
      
      
      
      <td style="text-align:center;width:50%">
	<span  class="unselectable_text state_details" onClick="close_dialog('make_order')" >{t}Cancel{/t}</span></td>
      <td style="text-align:center;width:50%">
	<span  onclick="window.open('customer_csv.php?id={$customer->get('Customer Key')}','Download');close_dialog('make_order')" id="make_order_save"  class="unselectable_text state_details"   >{t}Export{/t}</span></td></tr>
  </table>
</div>



<div>

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

<div id="filtermenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
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

<div id="rppmenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


{include file='footer.tpl'}

