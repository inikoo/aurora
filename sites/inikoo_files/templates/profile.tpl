{include file='header.tpl'}

<div id="bd" style="padding:0px">
<div style="padding:0px 20px;">



<div class="top_page_menu">
<div class="buttons" style="float:left">

<button   onclick="window.location='client.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}Edit Profile{/t}</button>
<button  onclick="window.location='address_book.php'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>
<button  onclick="window.location='orders.php'" ><img src="art/icons/table.png" alt=""> {t}Orders{/t}</button>
<button  class="selected" onclick="window.location='profile.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}My Account{/t}</button>


</div>


<div style="clear:both"></div>
</div>


    <h2 class="client" style="text-align:left">{$customer->get('Customer Name')} <span style="color:SteelBlue">{$id}</span></h2> 


 
  
 
     

       

     
<div  id="main_frame" style="width:490px;float:left" >    



<table id="customer_data" border=1 style="width:100%;">
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


<div id="banner" style="height:72px;width:392px; border: 1px solid black;" >
Banner Text
</div>
  
 
  <div id="block_details"  style="clear:both;margin:20px 0 40px 0;padding:0 20px">


<h3 style="clear:both;text-align:left">{t}Custom Fields{/t}</h3>

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
   
  </div> 

<div>








{include file='footer.tpl'}

