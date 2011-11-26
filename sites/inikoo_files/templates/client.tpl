{include file='header.tpl'}

<div id="bd" style="padding:0px">
<div style="padding:0px 20px;">



<div class="top_page_menu">
<div class="buttons" style="float:left">
<button  class="selected" onclick="window.location='client.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}Edit Profile{/t}</button>
<button  onclick="window.location='address_book.php'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>
<button  onclick="window.location='orders.php'" ><img src="art/icons/table.png" alt=""> {t}Orders{/t}</button>
<button   onclick="window.location='profile.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}My Account{/t}</button>

</div>


<div style="clear:both"></div>
</div>


    <h2 class="client" style="text-align:left">{$customer->get('Customer Name')} <span style="color:SteelBlue">{$id}</span></h2> 


<div id="waning_msg" style="background:#F6CECE" >
{$warning_msg}
</div>


<form method="POST" action="update_details.php">
<input type="hidden" value="contact_details" name="submit">
<input type="hidden" value="{$customer->id}" name="customer_key">
<input type="hidden" value="site_edit_customer" name="tipo">
<table class="edit" border=0 style="clear:both;margin-bottom:40px;width:100%">
<tr><td class="label" style="width:150px">{t}Tax Number: {/t}</td><td style="text-align:left;width:300px">
<div>
<input style="text-align:left;width:100%" type="text" id="tax_number" name="tax_number" value="{$customer->get('Customer Tax Number')}" ovalue="{$customer->get('Customer Tax Number')}">
       <div id="tax_number_Container"  ></div>
     </div></td>
<td id="tax_number_msg"  class="edit_td_alert"></td>
</tr>
{if $customer->get('Customer Type')=='Company'}
<tr><td class="label">{t}Company Name: {/t}</td><td style="text-align:left">
<div>
<input style="text-align:left;width:100%" type="text" id="name"  name="name" value="{$customer->get('Customer Company Name')}" ovalue="{$customer->get('Customer Company Name')}">
       <div id="name_Container"  ></div>
     </div></td>
<td id="name_msg"  class="edit_td_alert"></td>
</tr>
{/if}
<tr><td class="label">{t}Contact Name: {/t}</td><td style="text-align:left">
<div>
<input style="text-align:left;width:100%" type="text" id="contact"  name="contact" value="{$customer->get('Customer Main Contact Name')}" ovalue="{$customer->get('Customer Main Contact Name')}">
       <div id="contact_Container"  ></div>
     </div></td>
<td id="contact_msg"  class="edit_td_alert"></td>
</tr>

<tr style="display:none"><td class="label">{t}Contact Email: {/t}</td><td style="text-align:left">
<div>
<input style="text-align:left;width:100%" type="text" id="email"  name="email" value="{$customer->get('Customer Main Plain Email')}" ovalue="{$customer->get('Customer Main Plain Email')}">
       <div id="email_Container"  ></div>
     </div></td>
<td id="email_msg"  class="edit_td_alert"></td>
</tr>
<tr><td class="label">{t}Contact Telephone: {/t}</td><td style="text-align:left">
<div>
<input style="text-align:left;width:100%" type="text" id="telephone"  name="telephone" value="{$customer->get('Customer Main XHTML Telephone')}" ovalue="{$customer->get('Customer Main XHTML Telephone')}">
       <div id="telephone_Container"  ></div>
     </div></td><td id="telephone_msg"  class="edit_td_alert"></td>
</tr>
<tr><td class="label">{t}Contact Mobile: {/t}</td><td style="text-align:left">
<div>
<input style="text-align:left;width:100%" type="text" id="mobile" name="mobile" value="{$customer->get('Customer Main XHTML Mobile')}" ovalue="{$customer->get('Customer Main XHTML Mobile')}">
       <div id="mobile_Container"  ></div>
     </div></td>
<td id="mobile_msg"  class="edit_td_alert"></td></tr>
<tr><td class="label">{t}Contact FAX: {/t}</td><td style="text-align:left">
<div>
<input style="text-align:left;width:100%" type="text" id="fax" name="fax" value="{$customer->get('Customer Main XHTML FAX')}" ovalue="{$customer->get('Customer Main XHTML FAX')}">
       <div id="fax_Container"  ></div>
     </div></td>
<td id="fax_msg"  class="edit_td_alert"></td></tr>
<tr colspan=2><td></td><td colspan="2" style="float:right">
<div class="buttons" style="float:left">
<button  id="reset" type="reset"><img src="art/icons/chart_pie.png" alt=""> {t}Reset{/t}</button>
<button  id="submit" type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Submit{/t}</button>

</div>


</td></tr>
</table>
</form>

</div>


  

</div> 

<div>
{include file='footer.tpl'}

