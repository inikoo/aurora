{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0px 20px;">

{include file='contacts_navigation.tpl'}

<div  class="branch"> 
  <span  >{if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a  href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {$id}</span>
</div>


<div class="top_page_menu">
    <img onMouseover="this.src='art/previous_button.gif'"  onMouseout="this.src='art/previous_button.png'"   title="{t}Previous Customer{/t} {$prev.name}" onclick="window.location='customer.php?{$parent_info}id={$prev.id}{if $parent_list}&p={$parent_list}{/if}'"  src="art/previous_button.png" alt="<"  style="margin-right:10px;float:left;height:22px;cursor:pointer;{if !$parent_list}display:none{/if};position:relative;top:2px" />
    <div class="buttons" style="float:left">
        <button  onclick="window.location='customers.php?store={$store->id}'" ><img src="art/icons/house.png" alt=""> {t}Customers{/t}</button>
    </div>
    <img onMouseover="this.src='art/next_button.gif'"  onMouseout="this.src='art/next_button.png'"  title="{t}Next Customer{/t} {$next.name}"  onclick="window.location='customer.php?{$parent_info}id={$next.id}{if $parent_list}&p={$parent_list}{/if}'"   src="art/next_button.png" alt=">"  style="float:right;height:22px;cursor:pointer;{if !$parent_list}display:none;{/if}position:relative;top:2px"/ >
    <div class="buttons" style="float:right">
        <button  onclick="window.location='edit_customer.php?id={$customer->id}{if $parent_list}&p={$parent_list}{/if}'" ><img src="art/icons/vcard_edit.png" alt=""> {t}Edit{/t}</button>
        <button {if $customer->get('Sticky Note')}style="display:none"{/if} id="new_sticky_note" ><img src="art/icons/note.png" alt=""> {t}Note{/t}</button>

        <button id="note" ><img src="art/icons/add.png" alt=""> {t}History Note{/t}</button>
        <button id="attach" ><img src="art/icons/add.png" alt=""> {t}Attachment{/t}</button>
        <button {if $user->id!=1}style="display:none"{/if} id="take_order" ><img src="art/icons/add.png" alt=""> {t}Order{/t}</button>
        <button id="make_order"><img src="art/icons/database_go.png" alt=""> {t}QO Data{/t}</button>
        <button  onclick="window.open('customers_address_label.pdf.php?type=customer&id={$customer->id}&label=99012')" ><img src="art/icons/printer.png" alt=""> {t}Address{/t}</button>
    </div>
    <div style="clear:both"></div>
</div>


<input type="hidden" id="modify" value="{$modify}"/>
<input type="hidden" id="other_email_count" value="{$other_email_count}"/>
  


       

     
<div  style="width:520px;float:left;padding-top:0px" >    
     <h1  style="padding-bottom:0px"><span  style="color:SteelBlue">{$id}</span> <span id="customer_name_heading" style="padding:2px 7px;border:1px dotted #fff" onMouseover="Dom.setStyle('quick_edit_name_edit','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_name_edit','visibility','hidden')" ><span id="customer_name">{$customer->get('Customer Name')}</span> <img onMouseover="Dom.setStyle('customer_name_heading','border-color','#ccc')"  onMouseout="Dom.setStyle('customer_name_heading','border-color','#fff')" id="quick_edit_name_edit" style="cursor:pointer;visibility:hidden;padding-bottom:3px" src="art/icons/edit.gif"></span></h1>

<span id="waning_message" class="edit_td_alert">
{foreach from=$all_warning item=item key=key}

	{foreach from=$item item=msg key=msg_key}
	{$msg.$msg_key}
	{$msg}<br/>
	{/foreach}
{/foreach}
</span>	 
	 
<table id="customer_data" border=0 style="width:100%">
    <tr>
        {if $customer->get('Customer Main Address Key')}
            <td id="main_address_td" style="border:1px dotted #fff"  onMouseover="Dom.setStyle('quick_edit_main_address','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_main_address','visibility','hidden')">
            <img onMouseover="Dom.setStyle('main_address_td','border-color','#ccc')"  onMouseout="Dom.setStyle('main_address_td','border-color','#fff')" id="quick_edit_main_address" style="float:right;cursor:pointer;visibility:hidden" src="art/icons/edit.gif">

            <div  id="main_address" >{$customer->get('Customer Main XHTML Address')}</div>
            </td>
            {/if}
        <td  valign="top">
            <table border=0 style="padding:0">
                            {if $customer->get('Customer Tax Number')}<tr id="tax_tr" style="border:1px dotted #fff" onMouseover="Dom.setStyle('quick_edit_tax','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_tax','visibility','hidden')"><td id="main_name" colspan=2  class="aright">{$customer->get('Customer Tax Number')}</td ><td><img id="quick_edit_name" alt="{t}Name{/t}" title="{t}Name{/t}"  src="art/icons/user_suit.png"/></td><td  ><img onMouseover="Dom.setStyle('tax_tr','border-color','#ccc')"  onMouseout="Dom.setStyle('tax_tr','border-color','#fff')" id="quick_edit_tax" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td></tr>{/if}

            
                {if $customer->get('Customer Main Contact Key')}
                <tr id="main_contact_name_tr" style="border:1px dotted #fff" onMouseover="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','hidden')">
                    <td id="main_contact_name" colspan=2  class="aright">{$customer->get('Customer Main Contact Name')}</td >
                    <td><img  alt="{t}Name{/t}" title="{t}Name{/t}"  src="art/icons/user_suit.png"/></td>
                    <td  ><img onMouseover="Dom.setStyle('main_contact_name_tr','border-color','#ccc')"  onMouseout="Dom.setStyle('main_contact_name_tr','border-color','#fff')" id="quick_edit_main_contact_name_edit" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
                </tr>
                {/if}
                {if $customer->get('Customer Main Email Key')}<tr id="main_email_tr" style="border:1px dotted #fff" onMouseover="Dom.setStyle('quick_edit_email','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_email','visibility','hidden')"><td id="main_email" colspan=2  class="aright">{$customer->get('customer main XHTML email')}</td ><td><img alt="{t}Email{/t}" title="{t}Email{/t}"   src="art/icons/email.png"/></td>{if $customer->get('customer main Plain Email') == $login_stat.UserHandle}<td><img src="art/icons/user_go.png" title="{t}User Login{/t}" alt="{t}User Login{/t}"></td>{/if}<td id="email_label{$customer->get('Customer Main Email Key')}"style="color:#777;font-size:80%">{$customer->get_principal_email_comment()}</td><td><img onMouseover="Dom.setStyle('main_email_tr','border-color','#ccc')"  onMouseout="Dom.setStyle('main_email_tr','border-color','#fff')" id="quick_edit_email" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td></tr>{/if}
                {foreach from=$customer->get_other_emails_data() item=other_email key=key}
                    <tr id="other_email_tr" style="border:1px dotted #fff" onMouseover="Dom.setStyle('quick_edit_other_email{$key}','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_other_email{$key}','visibility','hidden')"><td id="email{$key}" colspan=2   class="aright">{$other_email.xhtml}</td ><td><img alt="{t}Email{/t}" title="{t}Email{/t}" src="art/icons/email.png"/></td>{if $other_email_login_handle[$other_email.email] == $other_email.email}<td><img src="art/icons/user_go.png"/></td>{/if}<td id="email_label{$key}" style="color:#777;font-size:80%">{$other_email.label}</td><td><img onMouseover="Dom.setStyle('other_email_tr','border-color','#ccc')"  onMouseout="Dom.setStyle('other_email_tr','border-color','#fff')" id="quick_edit_other_email{$key}" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td></tr>
                {/foreach}
                {if $customer->get('Customer Main Telephone Key')}<tr id="main_telephone_tr" style="border:1px dotted #fff" onMouseover="Dom.setStyle('quick_edit_main_telephone','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_main_telephone','visibility','hidden')"><td id="main_telephone" colspan=2 class="aright"  style="{if $customer->get('Customer Main XHTML Mobile') and $customer->get('Customer Preferred Contact Number')=='Telephone'}font-weight:800{/if}"   >{$customer->get('Customer Main XHTML Telephone')}</td ><td><img alt="{t}Main Telephone{/t}" title="{t}Main Telephone{/t}" src="art/icons/telephone.png"/></td><td id="telephone_label{$customer->get('Customer Main Telephone Key')}" style="color:#777;font-size:80%">{$customer->get_principal_telecom_comment('Telephone')}</td><td><img onMouseover="Dom.setStyle('main_telephone_tr','border-color','#ccc')"  onMouseout="Dom.setStyle('main_telephone_tr','border-color','#fff')" id="quick_edit_main_telephone" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td></tr>{/if}
                {foreach from=$customer->get_other_telephones_data() item=other_tel key=key}
                   <tr id="other_telephone_tr" style="border:1px dotted #fff" onMouseover="Dom.setStyle('quick_edit_other_telephone{$key}','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_other_telephone{$key}','visibility','hidden')"><td id="telephone{$key}" colspan=2   class="aright">{$other_tel.xhtml}</td ><td><img alt="{t}Telephone{/t}" title="{t}Telephone{/t}" src="art/icons/telephone.png"/></td><td id="telephone_label{$key}" style="color:#777;font-size:80%">{$other_tel.label}</td><td><img onMouseover="Dom.setStyle('other_telephone_tr','border-color','#ccc')"  onMouseout="Dom.setStyle('other_telephone_tr','border-color','#fff')" id="quick_edit_other_telephone{$key}" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td></tr>
                {/foreach}

                {if $customer->get('Customer Main Mobile Key')}<tr id="main_mobile_tr" style="border:1px dotted #fff" onMouseover="Dom.setStyle('quick_edit_main_mobile','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_main_mobile','visibility','hidden')"><td id="main_mobile" colspan=2 class="aright"  style="{if $customer->get('Customer Main XHTML Telephone') and $customer->get('Customer Preferred Contact Number')=='Mobile'}font-weight:800{/if}" >{$customer->get('Customer Main XHTML Mobile')}</td ><td><img alt="{t}Mobile{/t}" title="{t}Mobile{/t}" src="art/icons/phone.png"/></td><td id="mobile_label{$customer->get('Customer Main Mobile Key')}" style="color:#777;font-size:80%">{$customer->get_principal_telecom_comment('Mobile')}</td><td><img onMouseover="Dom.setStyle('main_mobile_tr','border-color','#ccc')"  onMouseout="Dom.setStyle('main_mobile_tr','border-color','#fff')" id="quick_edit_main_mobile" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td></tr>{/if}
                {foreach from=$customer->get_other_mobiles_data() item=other_tel key=key}
                    <tr id="other_mobile_tr" style="border:1px dotted #fff" onMouseover="Dom.setStyle('quick_edit_other_mobile{$key}','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_other_mobile{$key}','visibility','hidden')"><td id="mobile{$key}" colspan=2   class="aright">{$other_tel.xhtml}</td ><td><img alt="{t}Mobile{/t}" title="{t}Mobile{/t}" src="art/icons/phone.png"/></td><td id="mobile_label{$key}" style="color:#777;font-size:80%">{$other_tel.label}</td><td><img onMouseover="Dom.setStyle('other_mobile_tr','border-color','#ccc')"  onMouseout="Dom.setStyle('other_mobile_tr','border-color','#fff')" id="quick_edit_other_mobile{$key}" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td></tr>
                {/foreach}

                {if $customer->get('Customer Main FAX Key')}<tr id="main_fax_tr" style="border:1px dotted #fff" onMouseover="Dom.setStyle('quick_edit_main_fax','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_main_fax','visibility','hidden')"><td id="main_fax" colspan=2 class="aright">{$customer->get('Customer Main XHTML FAX')}</td ><td><img alt="{t}Fax{/t}" title="{t}Fax{/t}"  src="art/icons/printer.png"/></td><td id="fax_label{$customer->get('Customer Main FAX Key')}" style="color:#777;font-size:80%">{$customer->get_principal_telecom_comment('FAX')}</td><td><img onMouseover="Dom.setStyle('main_fax_tr','border-color','#ccc')"  onMouseout="Dom.setStyle('main_fax_tr','border-color','#fff')" id="quick_edit_main_fax" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td></tr>{/if}
                {foreach from=$customer->get_other_faxes_data() item=other_tel key=key}
                    <tr id="other_fax_tr" style="border:1px dotted #fff" onMouseover="Dom.setStyle('quick_edit_other_fax{$key}','visibility','visible')"  onMouseout="Dom.setStyle('quick_edit_other_fax{$key}','visibility','hidden')"><td id="fax{$key}" colspan=2   class="aright">{$other_tel.xhtml}</td ><td><img alt="{t}Fax{/t}" title="{t}Fax{/t}"  src="art/icons/printer.png"/></td><td id="fax_label{$key}" style="color:#777;font-size:80%">{$other_tel.label}</td><td><img onMouseover="Dom.setStyle('other_fax_tr','border-color','#ccc')"  onMouseout="Dom.setStyle('other_fax_tr','border-color','#fff')" id="quick_edit_other_fax{$key}" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td></tr>
                {/foreach}

				{foreach from=$show_case key=name item=value}
				{if $value!=''}
				<tr>
				<td colspan=2 class="aright">{$value}</td><td <td colspan=2 class="aleft" style="color:#777;font-size:80%">{$name}</td>
				</tr>
				{/if}
				{/foreach}
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
<div id="overviews" style="width:800px">

<div id="orders_overview" style="float:left;;margin-right:40px;width:300px" >
  <h2 style="font-size:120% ">{t}Contact Overview{/t}</h2>


  <table style="padding:0;margin:0;border-top:1px solid black;;border-bottom:1px solid black;min-width:300px">
  <tr>
  <td>

  {if $customer->get('Customer Type by Activity')=='Losing'}
  
    {elseif $customer->get('Customer Type by Activity')=='Lost'}
 <span style="font-weight:800">{t}Lost Customer{/t}</span> ({$customer->get('Lost Date')})

  
  {else}
 {t}Contact Since{/t}: {$customer->get('First Contacted Date')}
  {/if}
  
  </td></tr>
  <tr><td>{if $customer_type}User is registered in the site{/if}</td></tr>
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
<div id="customer_overview"  style="float:left;width:400px" >
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

<div id="sticky_note_div" class="sticky_note" style="margin-top:3px;width:370px">
<img id="sticky_note" style="float:right;cursor:pointer"src="art/icons/edit.gif">
<div  id="sticky_note_content" style="padding:10px 15px 10px 15px;">{$customer->get('Sticky Note')}</div>
</div>



<div style="clear:both"></div>
</div>



















  <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='details'}selected{/if}"  id="details">  <span> {t}Details{/t}</span></span></li>
    <li> <span class="item {if $view=='history'}selected{/if}"  id="history">  <span> {t}History, Notes{/t}</span></span></li>
	{if $customer_type}
	<li> <span class="item {if $view=='login_stat'}selected{/if}"  id="login_stat">  <span> {t}Login Status{/t}</span></span></li>
	{/if}
    <li {if !$customer->get('Customer Orders')}style="display:none"{/if}> <span class="item {if $view=='products'}selected{/if}" id="products"  ><span>  {t}Products Ordered{/t}</span></span></li>
    <li {if !$customer->get('Customer Orders')}style="display:none"{/if}> <span class="item {if $view=='orders'}selected{/if}"  id="orders">  <span> {t}Order Details{/t}</span></span></li>
	
 </ul>
  <div  style="clear:both;width:100%;border-bottom:1px solid #ccc">

  </div>
  
 
  <div id="block_details"  style="{if $view!='details'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


<h2 style="clear:both">{t}Custom Fields{/t}</h2>

<div style="float:left;width:450px">
<table    class="show_info_product">

		  {foreach from=$customer_custom_fields key=name item=value}
		  <tr>
		  <td>{$name}:</td><td>{$value}</td>
		  </tr>
		  {/foreach}
		</table>
</div>  
  
  
<h2 style="clear:both">{t}Billing Details{/t}</h2>

<div style="float:left;width:450px">
<table    class="show_info_product">


  <tr>
		    <td>{t}Tax Category Code{/t}:</td><td>{$customer->get('Customer Tax Category Code')}</td>
		    </tr>
		 <tr style="{if $hq_country!='ES'}display:none;{/if};border-top:1px solid #ccc">
		    <td>Recargo Equivalencia</td><td>{$customer->get('Recargo Equivalencia')}</td>
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

<h2 style="clear:both">{t}Contact Details{/t}</h2>


<div class="contact_cards" style="xdisplay:none" >
{foreach from=$customer->get_contact_cards() item=card}
{$card}
{/foreach}
</div>


<h2 style="clear:both">{t}Delivery Address{/t}</h2>

<div style="float:left;width:450px">
<table    class="show_info_product">

		   <tr >
		  		      <td>{t}Delivery Address{/t}:</td><td id="main_delivery_address">{$customer->get('Customer XHTML Main Delivery Address')}</td>

		    </tr>
		</table>
</div>
<div style="clear:both"></div>

{if $customer_type}
<h2 style="clear:both">{t}Login Details{/t}</h2>
<div style="float:left;width:450px">
<table    class="show_info_product">

		<tr ><td>{t}Last Login{/t}:</td><td>{$login_stat.UserLastLogin}</td></tr>
		<tr ><td>{t}User Login Count{/t}:</td><td>{$login_stat.UserLoginCount}</td></tr>
		<tr><td>{t}User Last Login IP{/t}:</td><td>{$login_stat.UserLastLoginIP}</td></tr>
		<tr><td>{t}User Failed Login Count{/t}:</td><td>{$login_stat.UserFailedLoginCount}</td></tr>
		<tr><td>{t}User Last Failed Login IP{/t}:</td><td>{$login_stat.UserLastFailedLoginIP}</td></tr>
		<tr><td>{t}User Last Failed Login{/t}:</td><td>{$login_stat.UserLastFailedLogin}</td></tr>
		</table>
</div>
{/if}

<div style="clear:both"></div>

</div>
 
 <div id="block_history" class="data_table" style="{if $view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
      <span class="clean_table_title">{t}History/Notes{/t}</span>
           <div id="table_type" class="table_type">
        <div  style="font-size:90%"   id="transaction_chooser" >

            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Changes}selected{/if} label_customer_history_changes"  id="elements_changes" table_type="changes"   >{t}Changes History{/t} (<span id="elements_changes_number">{$elements_number.Changes}</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Orders}selected{/if} label_customer_history_orders"  id="elements_orders" table_type="orders"   >{t}Order History{/t} (<span id="elements_orders_number">{$elements_number.Orders}</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Notes}selected{/if} label_customer_history_notes"  id="elements_notes" table_type="notes"   >{t}Staff Notes{/t} (<span id="elements_notes_number">{$elements_number.Notes}</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Attachments}selected{/if} label_customer_history_attachments"  id="elements_attachments" table_type="attachments"   >{t}Attachments{/t} (<span id="elements_notes_number">{$elements_number.Attachments}</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Emails}selected{/if} label_customer_history_emails"  id="elements_emails" table_type="emails"   >{t}Emails{/t} (<span id="elements_notes_number">{$elements_number.Emails}</span>)</span>

        </div>
     </div>
          <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>

      
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>

 <div id="block_login_stat" class="data_table" style="{if $view!='login_stat'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

      {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3  }
      <div  id="table3"   class="data_table_container dtable btable "> </div>
 
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

<div id="dialog_note" style="padding:20px 20px 10px 20px">
  <div id="note_msg"></div>
  <table  >
  <tr>
  <td >
  <div class="buttons"  id="note_type"  prefix="note_type_" value="deletable">
    <button  id="note_type_permanent" onclick="radio_changed(this)" name="permanent">{t}Permanent{/t}</button>
    <button class="selected" id="note_type_deletable" onclick="radio_changed(this)" name="deletable">{t}Deletable{/t}</button>
  </div>
  
  </td>
  </tr>
  
 
  </tr>
  
    <tr><td >
	<textarea style="width:200px;height:100px" id="note_input" onkeyup="change(event,this,'note')"></textarea>
      </td>
    </tr>
	<tr >
	<td>
	<div class="buttons">
		<button  onclick="save('note')" id="note_save"  class="positive disabled"   >{t}Save{/t}</span>

	<button  onClick="close_dialog('note')"  class="negative">{t}Cancel{/t}</button>

	</div>
	
		</td>
	</tr>
</table>
</div>


<div id="dialog_edit_note" style="position:absolute;top-100px;left:-500px">
  <div id="edit_note_msg"></div>
  <input type="hidden" value="" id="edit_note_history_key">
    <input type="hidden" value="" id="record_index">


  <table style="padding:10px;margin:10px" >

<tr>

<td colspan=2>
<div  id="edit_note_date" class="buttons left" prefix="note_date_" value="keep_date">
<button class="selected"  id="note_date_keep_date" onclick="radio_changed(this)" name="keep_date">{t}Keep Date{/t}</button>
<button  id="note_date_update_date" onclick="radio_changed(this)" name="update_date">{t}Update Date{/t}</button>
</div>
</td>
</tr>


  
    <tr><td colspan=2>
	<textarea style="width:200px;height:100px" id="edit_note_input" onkeyup="change(event,this,'edit_note')"></textarea>
      </td>
    <tr>
    <tr >
    
    <td colspan=2>
<div  class="buttons">
<button class="positive"  onclick="save('edit_note')" id="edit_note_save">{t}Save{/t}</button>
<button  class="negative"  onClick="close_dialog('edit_note')"  >{t}Cancel{/t}</button>
</div>
</td>


      </tr>
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



<div id="dialog_new_sticky_note" style="padding:20px 20px 0px 20px">
  <div id="new_sticky_note_msg"></div>
  <table >
 
    <tr>
    <td>
	<textarea style="width:200px;height:100px" id="new_sticky_note_input" onkeyup="change(event,this,'new_sticky_note')"></textarea>
    </td>
    <tr>
    <td>
    <div class="buttons">
        <button  onclick="save('new_sticky_note')" id="new_sticky_note_save"  class="positive disabled"     >{t}Save{/t}</button>

     <button class="negative" onClick="close_dialog('new_sticky_note')" >{t}Cancel{/t}</button>
    </div>
    </td>
    </tr>
   
</table>
</div>

<div id="dialog_sticky_note" style="padding:20px 20px 0px 20px">
  <div id="sticky_note_msg"></div>
  <table>
 
    <tr><td>
    
	<textarea style="width:260px;height:125px" id="sticky_note_input"  onkeyup="change(event,this,'sticky_note')">{$customer->get('Customer Sticky Note')}</textarea>
      </td>
    <tr>
    <td>
	<div class="buttons">
        <button class="positive" onClick="save('sticky_note')">{t}Save{/t}</button>
        <button class="negative" onClick="close_dialog('sticky_note')">{t}Cancel{/t}</button>
</div>
</td></tr>
	
</table>
</div>




<div id="dialog_attach" style="padding:20px 20px 0px 20px">
  <div id="attach_msg"></div>
  
<input type="hidden" value='customer' id='attachment_scope'>
<input type="hidden" value='{$customer->id}' id='attachment_scope_key'>
<table>
<form enctype="multipart/form-data" method="post" id="upload_attach_form">
<input type="hidden" name="attach_customer_key" value="{$customer->id}" />
 <tr><td>{t}File{/t}:</td><td><input id="upload_attach_file" style="border:1px solid #ddd;" type="file" name="attach"/></td></tr>

<tr><td>{t}Caption{/t}</td><td><input style="width:100%" value='' id='attachment_caption' name="caption"></td>
</form>
 <tr><td colspan=2>
  <div class="buttons">
<button class="positive"   onClick="save('attach')" >{t}Upload{/t}</button>
<button   onClick="close_dialog('attach')" class="negative" >{t}Cancel{/t}</button><br/>

</div>
  </td></tr>
</table>


</div>


</div>


<div id="dialog_make_order" style="padding:20px 20px 0px 20px">
  <div id="long_note_msg"></div>

  <table >

<input type="hidden" id="make_order_customer_id" value="{$customer->id}">
    <tr><td colspan=2>{t}Payment Method{/t}:</td></tr><tr><td colspan=2>
	<select id="make_order_payment_method">
	{if $hq_country=='ES'}
	 <option>{t}Tarjeta{/t}</option>
	  <option>{t}Paypal{/t}</option>
	  <option>{t}Ingreso{/t}</option>
	  <option>{t}Contra Reembolso{/t}</option>
	  <option>{t}Transferencia{/t}</option>
	  	  <option>{t}Efectivo{/t}</option>

	{else}
	
	  <option>{t}Credit Card{/t}</option>
	  <option>{t}Paypal{/t}</option>
	  <option>{t}Bank Transfer{/t}</option>
	  <option>{t}Cheque{/t}</option>
	  <option>{t}Cash{/t}</option>
	  <option>{t}Account{/t}</option>
	  <option>{t}Postal Order{/t}</option>
	  <option>{t}Cash on delivery{/t}</option>
{/if}

	</select>
    </td></tr>
    
    
     <tr  style="{if $hq_country=='ES'}display:none{/if}"  ><td colspan=2>{t}Special Offer{/t}:</td></tr><tr><td colspan=2>
	<select id="offer">
	<option value="none">None</option>
	  <option value="gift focus">Gift Focus</option>
	  <option value="garden" >Garden</option>
	  <option value="Party2011" >Party2011</option>
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
    <tr>
      <td colspan=2>
    <div class="buttons">
        <button  onclick="make_order()" id="make_order_save"  class="positive"   >{t}Export{/t}</button>
    	<button  class="negative" onClick="close_dialog('make_order')" >{t}Cancel{/t}</button>


    </div>  
</td>
      
     
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

<div id="dialog_quick_edit_Customer_Tax_Number" style="padding:10px">
<input type="hidden" value="" id="Customer_Fiscal_Name">
	<table style="margin:10px">
	<tr>
	<td>{t}Contact Name:{/t}</td>
	<td>
	<div style="width:220px">
	<input type="text" id="Customer_Tax_Number" value="{$customer->get('Customer Tax Number')}" ovalue="{$customer->get('Customer Tax Number')}" valid="0">
	<div id="Customer_Tax_Number_Container"  ></div>
	</div>	
	</td>

	</tr>
	<tr><td colspan=2>
	<div class="buttons" style="margin-top:10px">
	<span  id="Customer_Tax_Number_msg" class="edit_td_alert"></span>
	<button class="positive" id="save_quick_edit_tax_number">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_tax_number">{t}Cancel{/t}</button>

	</div>
	</td></tr>
	</table>

</div>

<div id="dialog_quick_edit_Customer_Main_Contact_Name" style="padding:10px">
	<table style="margin:10px">
	<tr>
	<td>{t}Contact Name:{/t}</td>
	<td>
	<div style="width:220px">
	<input type="text" id="Customer_Main_Contact_Name" value="{$customer->get('Customer Main Contact Name')}" ovalue="{$customer->get('Customer Main Contact Name')}" valid="0">
	<div id="Customer_Main_Contact_Name_Container"  ></div>
	</div>	
	</td>

	</tr>
	<tr><td colspan=2>
	<div class="buttons" style="margin-top:10px">
	<span  id="Customer_Main_Contact_Name_msg" class="edit_td_alert"></span>
	<button class="positive" id="save_quick_edit_main_contact_name">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_main_contact_name">{t}Cancel{/t}</button>

	</div>
	</td></tr>
	</table>

</div>

<div id="dialog_quick_edit_Customer_Name" style="padding:10px">
	<table style="margin:10px">
	
	<tr>
	<td>{t}Customer Name:{/t}</td>
	<td>
	<div style="width:220px">
	<input type="text" id="Customer_Name" value="{$customer->get('Customer Name')}" ovalue="{$customer->get('Customer Name')}" valid="0">
	<div id="Customer_Name_Container"  ></div>
	</div>	
	</td>

	</tr>
	<tr><td colspan=2>
	<div class="buttons" style="margin-top:10px">
	<span id="Customer_Name_msg" ></span>
	<button class="positive" id="save_quick_edit_name">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_name">{t}Cancel{/t}</button>

	</div>
	</td></tr>
	</table>

</div>

<div id="dialog_quick_edit_Customer_Main_Email" style="padding:10px">
	<table style="margin:10px">
	
	
	{if $customer->get_principal_email_comment()}
	<tr>
	<td>{t}Comment:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Main_Email_comment" value="{$customer->get_principal_email_comment()}" ovalue="{$customer->get_principal_email_comment()}" valid="0">
		<div id="Customer_Main_Email_comment_Container"  ></div>
	</div>	
	</td>
	</tr>
	{/if}
	<tr>
	<td>{t}Contact Email:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Main_Email" value="{$customer->get('customer main Plain Email')}" ovalue="{$customer->get('customer main Plain Email')}" valid="0">
		<div id="Customer_Main_Email_Container"  ></div>
	</div>	
	</td>
	</tr>
	
	<tr><td colspan=2>
	<div class="buttons" style="margin-top:10px">
	<span id="Customer_Main_Email_msg" ></span>
	<button class="positive" id="save_quick_edit_email">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_email">{t}Cancel{/t}</button>
	</div>
	</td>
	</tr>
	</table>

</div>

{foreach from=$customer->get_other_emails_data() item=other_email key=key}
<div id="dialog_quick_edit_Customer_Email{$key}" style="padding:10px">
	<table style="margin:10px">
	{if $other_email.label}
	<tr>
	<td>{t}Comment:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Email{$key}_comment" value="{$other_email.label}" ovalue="{$other_email.label}" valid="0">
	<div id="Customer_Email{$key}_comment_Container"  ></div>
	</div>	
	</td></tr>
	{/if}
	
	<tr>
	<td>{t}Other Email:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Email{$key}" value="{$other_email.email}" ovalue="{$other_email.email}" valid="0">
	<div id="Customer_Email{$key}_Container"  ></div>
	</div>	
	</td></tr>
	<tr><td  colspan=2 >
	<div class="buttons" style="margin-top:10px">
	<span id="Customer_Email{$key}_msg" ></span>
	<button class="positive" onclick="save_quick_edit_other_email({$key})">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_email{$key}">{t}Cancel{/t}</button>
	</div>
	</td>
	</tr>	
	</table>

</div>
{/foreach}


<div id="dialog_quick_edit_Customer_Main_Telephone" style="padding:10px">
	<table style="margin:10px">
	{if $customer->get_principal_telecom_comment('Telephone')}
	<tr>
	<td>{t}Comment:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Main_Telephone_comment" value="{$customer->get_principal_telecom_comment('Telephone')}" ovalue="{$customer->get_principal_telecom_comment('Telephone')}" valid="0">
	<div id="Customer_Main_Telephone_comment_Container"></div>
	</div>	
	</td></tr>
	{/if}
	<tr>
	<td>{t}Telephone:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Main_Telephone" value="{$customer->get('Customer Main XHTML Telephone')}" ovalue="{$customer->get('Customer Main XHTML Telephone')}" valid="0">
	<div id="Customer_Main_Telephone_Container"></div>
	</div>	
	</td></tr>
	<tr><td colspan=2>
	<div class="buttons" style="margin-top:10px">
	<span id="Customer_Main_Telephone_msg" ></span>
	<button class="positive" id="save_quick_edit_telephone">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_telephone">{t}Cancel{/t}</button>
	</div>
	</td>
	</tr>
	</table>

</div>

{foreach from=$customer->get_other_telephones_data() item=other_telephone key=key}
<div id="dialog_quick_edit_Customer_Telephone{$key}" style="padding:10px">
	<table style="margin:10px">
	{if $other_tel.label}
	<tr>
	<td>{t}Other Telephone:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Telephone{$key}_comment" value="{$other_tel.label}" ovalue="{$other_tel.label}" valid="0">
	<div id="Customer_Telephone{$key}_comment_Container"></div>
	</div>
	</td></tr>
	{/if}
	<tr>
	<td>{t}Other Telephone:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Telephone{$key}" value="{$other_telephone.number}" ovalue="{$other_telephone.number}" valid="0">
	<div id="Customer_Telephone{$key}_Container"></div>
	</div>
	</td></tr>
	<tr><td colspan=2>
		<div class="buttons" style="margin-top:10px">
	<span id="Customer_Telephone{$key}_msg" ></span>
	<button class="positive" onclick="save_quick_edit_other_telephone({$key})">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_telephone{$key}">{t}Cancel{/t}</button>
	</div>
	</td>
	</tr>
	</table>
</div>
{/foreach}

<div id="dialog_quick_edit_Customer_Main_Mobile" style="padding:10px">
	<table style="margin:10px">

	{if $customer->get_principal_telecom_comment('Mobile')}
	<tr>
	<td>{t}Comment:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Main_Mobile_comment" value="{$customer->get_principal_telecom_comment('Mobile')}" ovalue="{$customer->get_principal_telecom_comment('Mobile')}" valid="0">
	<div id="Customer_Main_Mobile_comment_Container"></div>
	</div>	
	</td></tr>
	{/if}
	<tr>
	<td>{t}Mobile:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Main_Mobile" value="{$customer->get('Customer Main XHTML Mobile')}" ovalue="{$customer->get('Customer Main XHTML Mobile')}" valid="0">
	<div id="Customer_Main_Mobile_Container"></div>
	</div>	
	</td></tr>
	<tr><td colspan=2>
	<div class="buttons" style="margin-top:10px">
	<span id="Customer_Main_Mobile_msg" ></span>
	<button class="positive" id="save_quick_edit_mobile">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_mobile">{t}Cancel{/t}</button>
	</div>
	</td>
	</tr>
	</table>

</div>

{foreach from=$customer->get_other_mobiles_data() item=other_mobile key=key}
<div id="dialog_quick_edit_Customer_Mobile{$key}" style="padding:10px">
	<table style="margin:10px">
	{if $other_mobile.label}
	<tr>
	<td>{t}Comment:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Mobile{$key}_comment" value="{$other_mobile.label}" ovalue="{$other_mobile.label}" valid="0">
	<div id="Customer_Mobile{$key}_comment_Container"></div>
	</div>
	</td></tr>
	{/if}
	
	<tr>
	<td>{t}Other Mobile:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Mobile{$key}" value="{$other_mobile.number}" ovalue="{$other_mobile.number}" valid="0">
	<div id="Customer_Mobile{$key}_Container"></div>
	</div>
	</td></tr>
	<tr><td colspan=2>
	<div class="buttons" style="margin-top:10px">
	<span id="Customer_Mobile{$key}_msg" ></span>
	<button class="positive" onclick="save_quick_edit_other_mobile({$key})">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_other_mobile{$key}">{t}Cancel{/t}</button>
	</div>
	</td>
	</tr>

	</table>

</div>
{/foreach}

<div id="dialog_quick_edit_Customer_Main_FAX" style="padding:10px">
	<table style="margin:10px">
	{if $customer->get_principal_telecom_comment('FAX')}
	<tr>
	<td>{t}Comment:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Main_FAX_comment" value="{$customer->get_principal_telecom_comment('FAX')}" ovalue="{$customer->get_principal_telecom_comment('FAX')}" valid="0">
	<div id="Customer_Main_FAX_comment_Container"></div>
	</div>	
	</td></tr>
	{/if}
	
	<tr>
	<td>{t}Fax:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_Main_FAX" value="{$customer->get('Customer Main XHTML FAX')}" ovalue="{$customer->get('Customer Main XHTML FAX')}" valid="0">
	<div id="Customer_Main_FAX_Container"></div>
	</div>	
	</td></tr>
	<tr><td colspan=2>
	<div class="buttons" style="margin-top:10px">
	<span id="Customer_Main_FAX_msg" ></span>
	<button class="positive" id="save_quick_edit_fax">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_fax">{t}Cancel{/t}</button>
	</div>
	</td>
	</tr>
	</table>

</div>


{foreach from=$customer->get_other_faxes_data() item=other_fax key=key}
<div id="dialog_quick_edit_Customer_FAX{$key}" style="padding:10px">
	<table style="margin:10px">

	{if $other_fax.label}
	<tr>
	<td>{t}Comment:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_FAX{$key}_comment" value="{$other_fax.label}" ovalue="{$other_fax.label}" valid="0">
	<div id="Customer_FAX{$key}_comment_Container"></div>
	</div>	
	</td></tr>
	{/if}
	
	<tr>
	<td>{t}Other FAX:{/t}</td>
	<td>
	<div style="width:200px">
	<input type="text" id="Customer_FAX{$key}" value="{$other_fax.number}" ovalue="{$other_fax.number}" valid="0">
	<div id="Customer_FAX{$key}_Container"></div>
	</div>	
	</td></tr>
	<tr><td colspan=2>
	<div class="buttons" style="margin-top:10px">
	<span id="Customer_FAX{$key}_msg" ></span>
	<button class="positive" onclick="save_quick_edit_other_fax({$key})">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_other_fax{$key}">{t}Cancel{/t}</button>
	</div>
	</td>
	</tr>
	
	</table>

</div>
{/foreach}



<div id="dialog_quick_edit_Customer_Main_Address" style="float:left;xborder:1px solid #ddd;width:430px;margin-right:20px;padding-bottom:50px">

<table border=0 style="margin:10px; width:100%">
{include file='edit_address_splinter.tpl' address_identifier='contact_' hide_type=true hide_description=true  show_components=true}
</table>
<div style="display:none" id='contact_current_address' ></div>
<div style="display:none" id='contact_address_display{$customer->get("Customer Main Address Key")}' ></div>
</div>

<div id="dialog_country_list" style="position:absolute;left:-1000;top:0">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Country List{/t}</span>
            
            {include file='table_splinter.tpl' table_id=100 filter_name=$filter_name100 filter_value=$filter_value100}
            <div  id="table100"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>

 <div id="dialog_comment">
  <div id="comment_msg"></div>
    <input type="hidden" value="" id="comment_scope"/>

  <input type="hidden" value="" id="comment_scope_key"/>
  <input type="hidden" value="" id="comment"/>
  
  
    <input type="hidden" value="{$customer->get_principal_telecom_comment('Telephone')}" id="comment_telephone"/>
        <input type="hidden" value="{$customer->get_principal_telecom_comment('FAX')}" id="comment_fax"/>
    <input type="hidden" value="{$customer->get_principal_telecom_comment('Mobile')}" id="comment_mobile"/>
    <input type="hidden" value="{$customer->get_principal_email_comment()}" id="comment_email"/>

</div>
 
{include file='footer.tpl'}

