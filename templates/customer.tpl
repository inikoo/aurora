{include file='header.tpl'}

<div id="bd" style="padding:0px">

 


<div style="padding:0px 20px;">

  {include file='contacts_navigation.tpl'}

  <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1 style="padding-bottom:0px">{$customer->get('Customer Name')} <span style="color:SteelBlue">{$id}</span>
     
      
    </h1> 

{if $customer->get('Customer Tax Number')!=''}<h2 style="padding:0">{$customer->get('Customer Tax Number')}</h2>{/if}    
  </div>
  
 
     
<table class="quick_button" style="clear:both;float:right;margin-top:0px;">
    <tr><td  ><a href="pdf_customer.php?id={$customer->id}">{t}Print Address{/t}</a></td></tr>

    <tr><td  id="note">{t}Quick Note{/t}</td></tr>
    <tr id="new_sticky_note_tr" ><td id="new_sticky_note">{t}Sticky Note{/t}</td></tr>
    <tr id="sticky_note_bis_tr" ><td id="sticky_note_bis">{t}Sticky Note{/t}</td></tr>

    <tr style="display:none"><td  id="attach">{t}Attach File{/t}</td></tr>
    <tr style="display:none"><td  id="link">{t}Link File{/t}</td></tr>
    <tr style="display:none"><td id="take_order">{t}Take Order{/t}</td></tr>
    <tr style="display:none"><td  id="long_note">{t}Long Note{/t}</td></tr>
    <tr style="display:none"><td id="call" >{t}Call{/t}</td></tr>
    <tr style="display:none"><td  id="email" >{t}Email{/t}</td></tr>
    <tr style="display:none"><td id="others" >{t}Other{/t}</td></tr>
    <tr><td id="make_order">QO Data</td></tr>
</table>
       

     
<div  style="width:500px;float:left" >    
     
<table id="customer_data" border=0 style="width:100%">
    <tr>
        {if $customer->get('Customer Main Address Key')}<td valign="top">{$customer->get('Customer Main XHTML Address')}</td>{/if}
        <td  valign="top">
            <table border=0 style="padding:0">
                {if $customer->get('Customer Main Contact Key')}<tr><td colspan=2  class="aright">{$customer->get('Customer Main Contact Name')}</td ></tr>{/if}
                {if $customer->get('Customer Main Email Key')}<tr><td colspan=2  class="aright">{$customer->get('customer main XHTML email')}</td ><td><img alt="{t}Email{/t}" title="{t}Email{/t}"  src="art/icons/email.png"/></td></tr>{/if}
                {if $customer->get('Customer Main Telephone Key')}<tr><td colspan=2 class="aright">{$customer->get('Customer Main XHTML Telephone')}</td ><td><img alt="{t}Telephone{/t}" title="{t}Telephone{/t}" src="art/icons/telephone.png"/></td></tr>{/if}
                {if $customer->get('Customer Main Mobile Key')}<tr><td colspan=2 class="aright">{$customer->get('Customer Main XHTML Mobile')}</td ><td><img alt="{t}Mobile{/t}" title="{t}Mobile{/t}" src="art/icons/phone.png"/></td></tr>{/if}

                {if $customer->get('Customer Main FAX Key')}<tr><td colspan=2 class="aright">{$customer->get('Customer Main XHTML FAX')}</td ><td><img alt="{t}Fax{/t}" title="{t}Fax{/t}"  src="art/icons/printer.png"/></td></tr>{/if}
            </table>
        </td>
    </tr>
    
  {if $customer->get('Customer Billing Address Link')!='Contact' or $customer->get('Customer Delivery Address Link')!='Contact' }  
  <tbody>  
  <tr style="font-size:90%;height:30px;vertical-align:bottom">
  <td style=";vertical-align:bottom">{t}Billing{/t}:</td>
   <td style=";vertical-align:bottom">{t}Delivery{/t}:</td>
    </tr>
  <tr style="font-size:90%;border-top:1px solid #ccc">
  <td >
  
 
 
  <span>{$customer->get('Customer Fiscal Name')}</span><br/>
  <div>
  {if ($customer->get('Customer Billing Address Link')=='Contact')   }
   <span>{t}Billing Address Same as contact address{/t}</span> 
   {else}
   {$customer->billing_address_xhtml()}
   {/if}
  </div>
</td>
<td>

 
  <div>
  {if ($customer->get('Customer Delivery Address Link')=='Contact') or ( $customer->get('Customer Delivery Address Link')=='Billing'  and  ($customer->get('Customer Main Address Key')==$customer->get('Customer Billing Address Key'))   )   }
     
     <span style="font-weight:600">{t}Same as contact address{/t}</span> 

     
     {elseif $customer->get('Customer Delivery Address Link')=='Billing'}
     
     <span style="font-weight:600">{t}Same as billing address{/t}</span> 

     
     {else}
     {$customer->delivery_address_xhtml()}
    
     
     {/if}
  </div>

  </td>
  </tr>
  </tbody>  
 {/if}
    
    
</table>
<div id="overviews" style="border-top:1px solid #eee;width:900px">

<div id="orders_overview" style="float:left;;margin-right:40px;width:300px" >
  <h2 style="font-size:120% ">{t}Contact Overview{/t}</h2>


  <table style="padding:0;margin:0;border-top:1px solid black;;border-bottom:1px solid black;min-width:300px">
  <tr><td>
 
  
  {if $customer->get('Customer Type by Activity')=='Losing'}
  
    {elseif $customer->get('Customer Type by Activity')=='Lost'}
 <span style="font-weight:800">{t}Lost Customer{/t}</span> ({$customer->get('Lost Date')})

  
  {else}
 {t}Contact Since{/t}: {$customer->get('First Contacted Date')}
  {/if}
  
  </td></tr>
  
  <tr><td>{$correlation_msg}</td></tr>
  
{if  $customer->get('Customer Send Newsletter')=='No' or $customer->get('Customer Send Email Marketing')=='No' or $customer->get('Customer Send Postal Marketing')=='No'}

   <tr><td>
   <div style="font-size:90%">
   {if $customer->get('Customer Send Newsletter')=='No'}<img alt="{t}Attention{/t}" width='14' src="art/icons/exclamation.png" /> <span>{t}Don't send newsletters{/t}<span><br/>{/if}
   {if $customer->get('Customer Send Email Marketing')=='No'}<img alt="{t}Attention{/t}" width='14' src="art/icons/exclamation.png" /> <span>{t}Don't send marketing by email{/t}<span><br/>{/if}
   {if $customer->get('Customer Send Postal Marketing')=='No'}<img alt="{t}Attention{/t}" width='14' src="art/icons/exclamation.png" /> <span>{t}Don't send marketing by post{/t}<span><br/>{/if}
   </div>
	</td></tr>
{/if}
  </table>

</div>

{if $customer->get('Customer Orders')>0}
<div id="customer_overview"  style="float:left;width:500px" >
  <h2 style="font-size:120% ">{t}Orders Overview{/t}</h2>
  <table style="padding:0;margin:0;border-top:1px solid black;;border-bottom:1px solid black;">
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
{/if}

</div>
</div>

<div id="sticky_note_div" style="border:1px solid #eee;background:#e0eefd;float:left;width:300px;padding:0;margin-left:20px">
<img id="sticky_note" style="float:right;cursor:pointer"src="art/icons/edit.gif">
<div  id="sticky_note_content" style="padding:10px 15px 10px 15px;">{$customer->get('Sticky Note')}</div>
</div>



<div style="clear:both"></div>
</div>



















  <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='details'}selected{/if}"  id="details">  <span> {t}Details{/t}</span></span></li>
    <li> <span class="item {if $view=='history'}selected{/if}"  id="history">  <span> {t}History, Notes{/t}</span></span></li>
    <li {if !$customer->get('Customer Orders')}style="display:none"{/if}> <span class="item {if $view=='products'}selected{/if}" id="products"  ><span>  {t}Products Ordered{/t}</span></span></li>
    <li {if !$customer->get('Customer Orders')}style="display:none"{/if}> <span class="item {if $view=='orders'}selected{/if}"  id="orders">  <span> {t}Order Details{/t}</span></span></li>
 </ul>
  <div  style="clear:both;width:100%;border-bottom:1px solid #ccc">

  </div>
  
 
  <div id="block_details"  style="{if $view!='details'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
<h2>{t}Contact Details{/t}</h2>
<div style="float:left;width:450px">
<table    class="show_info_product">
  <tr>
		    <td>{t}Customer Type{/t}:</td><td>{$customer->get('Customer Type')}</td>
		    </tr>
		    
		    {if $customer->get('Customer Type')=='Company'}
		    <tr>
		      <td>{t}Company Name{/t}:</td><td>{$customer->get('Customer Name')}</td>
		    </tr>
		     <tr>
		      <td>{t}Company Tax Number{/t}:</td><td>{$customer->get('Customer Tax Number')}</td>
		    </tr>
		  {/if}
		  <tr style="border-top:1px solid #ccc">
		  
		      <td>{t}Contact Name{/t}:</td><td>{$customer->get('Customer Main Contact Name')}</td>
		    </tr>
		   <tr>
		      <td>{t}Contact Email{/t}:</td><td>{$customer->get('Customer Main XHTML Email')}</td>
		    </tr>
		  <tr>
		      <td>{t}Contact Telephone{/t}:</td><td>{$customer->get('Customer Main XHTML Telephone')}</td>
		    </tr>
		  
		  <tr>
		      <td>{t}Contact Fax{/t}:</td><td>{$customer->get('Customer Main XHTML FAX')}</td>
		    </tr>
		  
		</table>
</div>

<div class="contact_cards"  >
{foreach from=$customer->get_contact_cards() item=card}
{$card}
{/foreach}
</div>
<h2 style="clear:both">{t}Billing Details{/t}</h2>

<div style="float:left;width:450px">
<table    class="show_info_product">
  <tr>
		    <td>{t}Tax Category Code{/t}:</td><td>{$customer->get('Customer Tax Category Code')}</td>
		    </tr>
		
		  <tr style="border-top:1px solid #ccc">
		  		      <td>{t}Usual Payment Method{/t}:</td><td>{$customer->get('Customer Usual Payment Method')}</td>

		    </tr>
		    {if $customer->get('Customer Usual Payment Method')!=$customer->get('Customer Last Payment Method')}
		   <tr>
		   		      <td>{t}Last Payment Method{/t}:</td><td>{$customer->get('Customer Last Payment Method')}</td>

		    </tr>
		 {/if}
		   <tr style="border-top:1px solid #ccc">
		  		      <td>{t}Billing Address{/t}:</td><td>{$customer->get('Customer XHTML Billing Address')}</td>

		    </tr>
		</table>
</div>

<h2 style="clear:both">{t}Delivery Details{/t}</h2>

<div style="float:left;width:450px">
<table    class="show_info_product">

		   <tr >
		  		      <td>{t}Delivery Address{/t}:</td><td>{$customer->get('Customer XHTML Main Delivery Address')}</td>

		    </tr>
		</table>
</div>

<div style="clear:both"></div>

</div>
 
 <div id="block_history" class="data_table" style="{if $view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
      <span class="clean_table_title">{t}History/Notes{/t}</span>
           <div id="table_type" class="table_type">
        <div  style="font-size:90%"   id="transaction_chooser" >

            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Changes}selected{/if} label_customer_history_changes"  id="elements_changes" table_type="changes"   >{t}Changes History{/t} (<span id="elements_changes_number">{$elements_number.Changes}</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Orders}selected{/if} label_customer_history_orders"  id="elements_orders" table_type="orders"   >{t}Order History{/t} (<span id="elements_orders_number">{$elements_number.Orders}</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Notes}selected{/if} label_customer_history_notes"  id="elements_notes" table_type="notes"   >{t}Staff Notes{/t} (<span id="elements_notes_number">{$elements_number.Notes}</span>)</span>

        </div>
     </div>
          <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>

      
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>


<div id="block_products" class="data_table" style="{if $view!='products'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

 
	<div style="float:left" id="plot1">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "465", "380", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=customer_departments_pie&customer_key={$customer->id}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot1");
		// ]]>
	</script>

<div style="float:left" id="plot2">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "465", "380", "8", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=customer_families_pie&customer_key={$customer->id}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot2");
		// ]]>
	</script>
	
	
      <span class="clean_table_title" style="clear:both">{t}Product Families Ordered{/t}</span>
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
  <table style="padding:10px;margin:10px" >
  <tr id="note_type" class="options_list" prefix="note_type_" value="deletable">
  <td class="" id="note_type_permanent" onclick="radio_changed(this)" name="permanent" >{t}Permanent{/t}
  </td>
  <td class="selected" id="note_type_deletable" onclick="radio_changed(this)" name="deletable" >{t}Deletable{/t}
  </td>
  </tr>
  
    <tr><td colspan=2>
	<textarea style="width:200px;height:100px" id="note_input" onkeyup="change(event,this,'note')"></textarea>
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text button" onClick="close_dialog('note')" >{t}Cancel{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="save('note')" id="note_save"  class="unselectable_text button"     style="visibility:hidden;" >{t}Save{/t}</span></td></tr>
</table>
</div>


<div id="dialog_edit_note" style="position:absolute;top-100px;left:-500px">
  <div id="edit_note_msg"></div>
  <input type="hidden" value="" id="edit_note_history_key">
    <input type="hidden" value="" id="record_index">


  <table style="padding:10px;margin:10px" >
  <tr id="edit_note_date" class="options_list" prefix="note_date_" value="keep_date">
 
  <td class="selected" id="note_date_keep_date" onclick="radio_changed(this)" name="keep_date" >{t}Keep Date{/t}
  </td>
   <td class="" id="note_date_update_date" onclick="radio_changed(this)" name="update_date" >{t}Update Date{/t}
  </td>
  </tr>
  
    <tr><td colspan=2>
	<textarea style="width:200px;height:100px" id="edit_note_input" onkeyup="change(event,this,'edit_note')"></textarea>
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text button" onClick="close_dialog('edit_note')" >{t}Cancel{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="save('edit_note')" id="edit_note_save"  class="unselectable_text button"     style="" >{t}Save{/t}</span></td></tr>
</table>
</div>


<div id="dialog_export">
  <div id="export_msg"></div>
  <table style="padding:10px;margin:20px 10px 10px 10px" >
 <tr><td><a href="export_data.php?subject=customer&subject_key={$customer->id}&source=db">{t}Export Data (using last map){/t}</a></td></tr>
 <tr><td><a href="export_data_maps.php?subject=customer&subject_key={$customer->id}&source=db">{t}Export from another map{/t}</a></td></tr>
 <tr><td><a href="export_wizard.php?subject=customer&subject_key={$customer->id}">{t}Export Wizard (new map){/t}</a></td></tr>

</table>
</div>



<div id="dialog_new_sticky_note">
  <div id="new_sticky_note_msg"></div>
  <table style="padding:10px;margin:10px" >
 
    <tr><td colspan=2>
	<textarea style="width:200px;height:100px" id="new_sticky_note_input" onkeyup="change(event,this,'new_sticky_note')"></textarea>
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text button" onClick="close_dialog('new_sticky_note')" >{t}Cancel{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="save('new_sticky_note')" id="new_sticky_note_save"  class="unselectable_text button"     style="visibility:hidden;" >{t}Save{/t}</span></td></tr>
</table>
</div>

<div id="dialog_sticky_note">
  <div id="sticky_note_msg"></div>
  <table style="padding:10px;margin:10px" >
 
    <tr><td colspan=2>
    
	<textarea style="width:260px;height:125px" id="sticky_note_input"  onkeyup="change(event,this,'sticky_note')">{$customer->get('Customer Sticky Note')}</textarea>
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text button" onClick="close_dialog('sticky_note')" >{t}Cancel{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="save('sticky_note')" id="sticky_note_save"  class="unselectable_text button"     style="" >{t}Save{/t}</span></td></tr>
</table>
</div>


<div id="dialog_link">
  <div id="link_msg"></div>
  <table >
     <tr><td colspan=2>

	  {t}Link Note{/t}:<br/> <input type="text" id="link_note"/>

    </td><tr>
    <tr><td colspan=2>
	<form action="upload.php" enctype="multipart/form-data" method="post" id="link_form">


	  <input type="file" name="testFile" id="link_file" />

	</form>
    </td><tr>
	
    <tr class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="state_details" onClick="close_dialog('link')" >{t}Cancel{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="save('link')" id="upload_link"  class="state_details"     xstyle="visibility:hidden;" >{t}Upload{/t}</span></td></tr>
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

{*}
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
{*}

<div id="dialog_make_order">
  <div id="long_note_msg"></div>
  <table >
<input type="hidden" id="make_order_customer_id" value="{$customer->id}">
    <tr><td colspan=2>{t}Payment Method{/t}:</td></tr><tr><td colspan=2>
	<select id="make_order_payment_method">
	  <option>Credit Card</option>
	  <option>Paypal</option>
	  <option>Bank Transfer</option>
	  <option>Cheque</option>
	  <option>Cash</option>
	  <option>Account</option>
	  <option>Postal Order</option>
	</select>
    </td></tr>
<tr><td colspan=2>Gold Reward:</td></tr><tr><td colspan=2>
	<select id="gold_reward">
	  
	  <option value="Standard Order"  >No</option>
	<option value="Gold Reward Member"  {if $gold_reward}selected="selected"{/if}>Yes</option>
	</select>
    </td></tr>

    <tr><td colspan=2>{t}Courier{/t}:</td></tr><tr><td colspan=2><input  id="make_order_courier"  /></td></tr>
    
    <tr><td colspan=2>{t}Special Instructions{/t}:</td></tr>
    <tr><td colspan=2>
	<textarea id="make_order_special_instructions"></textarea>
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%">
      
      
      
      <td style="text-align:center;width:50%">
	<span  class="unselectable_text state_details" onClick="close_dialog('make_order')" >{t}Cancel{/t}</span></td>
      <td style="text-align:center;width:50%">
	<span  onclick="make_order()" id="make_order_save"  class="unselectable_text state_details"   >{t}Export{/t}</span></td></tr>
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

