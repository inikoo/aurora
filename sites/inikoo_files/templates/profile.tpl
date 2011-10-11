{include file='header.tpl'}

<div id="bd" style="padding:0px">
<div style="padding:0px 20px;">
{include file='contacts_navigation.tpl'}



<div id="no_details_title"  style="clear:left;xmargin:0 20px;display:none">
    <h1 style="padding-bottom:0px">{$customer->get('Customer Name')} <span style="color:SteelBlue">{$id}</span>
     
      
    </h1> 

{if $customer->get('Customer Tax Number')!=''}<h2 style="padding:0">{$customer->get('Customer Tax Number')}</h2>{/if}    
  </div>
  
 
     

       

     
<div  style="width:490px;float:left" >    
     
<table id="customer_data" border=0 style="width:100%">
    <tr>
        {if $customer->get('Customer Main Address Key')}<td valign="top">{$customer->get('Customer Main XHTML Address')}</td>{/if}
        <td  valign="top">
            <table border=0 style="padding:0">
                {if $customer->get('Customer Main Contact Key')}<tr><td colspan=2  class="aright">{$customer->get('Customer Main Contact Name')}</td ></tr>{/if}
                {if $customer->get('Customer Main Email Key')}<tr><td colspan=2  class="aright">{$customer->get('customer main XHTML email')}</td ><td><img alt="{t}Email{/t}" title="{t}Email{/t}"  src="art/icons/email.png"/></td>{if $customer->get('customer main Plain Email') == $login_stat.UserHandle}<td><img src="art/icons/user_go.png" title="{t}User Login{/t}" alt="{t}User Login{/t}"></td>{/if}<td style="color:#777;font-size:80%">{$customer->get_principal_email_comment()}</td></tr>{/if}
                {foreach from=$customer->get_other_emails_data() item=other_email }
                    <tr><td colspan=2   class="aright">{$other_email.xhtml}</td ><td><img alt="{t}Email{/t}" title="{t}Email{/t}"  src="art/icons/email.png"/></td>{if $other_email_login_handle[$other_email.email] == $other_email.email}<td><img src="art/icons/user_go.png"/></td>{/if}<td style="color:#777;font-size:80%">{$other_email.label}</td></tr>
                {/foreach}
                {if $customer->get('Customer Main Telephone Key')}<tr><td colspan=2 class="aright"  style="{if $customer->get('Customer Main XHTML Mobile') and $customer->get('Customer Preferred Contact Number')=='Telephone'}font-weight:800{/if}"   >{$customer->get('Customer Main XHTML Telephone')}</td ><td><img alt="{t}Telephone{/t}" title="{t}Telephone{/t}" src="art/icons/telephone.png"/></td><td style="color:#777;font-size:80%">{$customer->get_principal_telecom_comment('Telephone')}</td></tr>{/if}
                {foreach from=$customer->get_other_telephones_data() item=other_tel }
                    <tr><td colspan=2   class="aright">{$other_tel.xhtml}</td ><td><img alt="{t}Telephone{/t}" title="{t}Telephone{/t}"  src="art/icons/telephone.png"/></td><td style="color:#777;font-size:80%">{$other_tel.label}</td></tr>
                {/foreach}

                {if $customer->get('Customer Main Mobile Key')}<tr><td colspan=2 class="aright"  style="{if $customer->get('Customer Main XHTML Telephone') and $customer->get('Customer Preferred Contact Number')=='Mobile'}font-weight:800{/if}" >{$customer->get('Customer Main XHTML Mobile')}</td ><td><img alt="{t}Mobile{/t}" title="{t}Mobile{/t}" src="art/icons/phone.png"/></td><td style="color:#777;font-size:80%">{$customer->get_principal_telecom_comment('Mobile')}</td></tr>{/if}
                {foreach from=$customer->get_other_mobiles_data() item=other_tel }
                    <tr><td colspan=2   class="aright">{$other_tel.xhtml}</td ><td><img alt="{t}Mobile{/t}" title="{t}Mobile{/t}"  src="art/icons/phone.png"/></td><td style="color:#777;font-size:80%">{$other_tel.label}</td></tr>
                {/foreach}

                {if $customer->get('Customer Main FAX Key')}<tr><td colspan=2 class="aright">{$customer->get('Customer Main XHTML FAX')}</td ><td><img alt="{t}Fax{/t}" title="{t}Fax{/t}"  src="art/icons/printer.png"/></td><td style="color:#777;font-size:80%">{$customer->get_principal_telecom_comment('FAX')}</td></tr>{/if}
                {foreach from=$customer->get_other_faxes_data() item=other_tel }
                    <tr><td colspan=2   class="aright">{$other_tel.xhtml}</td ><td><img alt="{t}Fax{/t}" title="{t}Fax{/t}"  src="art/icons/printer.png"/></td><td style="color:#777;font-size:80%">{$other_tel.label}</td></tr>
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
<div id="overviews" style="border-top:1px solid #eee;width:800px">

<div id="orders_overview" style="float:left;;margin-right:40px;width:300px; display:none" >
  <h2 style="font-size:120%;left-align:0 ">{t}Contact Overview{/t}</h2>


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
  <h2 style="font-size:120%; text-align:left">{t}Orders Overview{/t}</h2>
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

<div id="sticky_note_div" class="sticky_note" style="width:270px; display:none">
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


<h2 style="clear:both;text-align:left">{t}Custom Fields{/t}</h2>

<div style="float:left;width:450px">
<table    class="show_info_product">

		  {foreach from=$customer_custom_fields key=name item=value}
		  <tr>
		  <td>{$name}:</td><td>{$value}</td>
		  </tr>
		  {/foreach}
		</table>
</div>  
  
  
<h2 style="clear:both; text-align:left">{t}Billing Details{/t}</h2>

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

<h2 style="clear:both;text-align:left">{t}Contact Details{/t}</h2>
<div style="float:both;width:450px">
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
		    <tr>
		      <td>{t}Company Registration Number{/t}:</td><td>{$customer->get('Customer Registration Number')}</td>
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

<div class="contact_cards" style="display:none" >
{foreach from=$customer->get_contact_cards() item=card}
{$card}
{/foreach}
</div>


<h2 style="clear:both; text-align:left">{t}Delivery Details{/t}</h2>

<div style="float:left;width:450px">
<table    class="show_info_product">

		   <tr >
		  		      <td>{t}Delivery Address{/t}:</td><td>{$customer->get('Customer XHTML Main Delivery Address')}</td>

		    </tr>
		</table>
</div>
<div style="clear:both"></div>

{if $customer_type}
<h2 style="clear:both; text-align:left">{t}Login Details{/t}</h2>
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

<div>








{include file='footer.tpl'}

