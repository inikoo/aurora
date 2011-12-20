<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />

<div class="top_page_menu" style="padding:0px 20px 5px 20px">
<div class="buttons" style="float:left">
<button  class="{if $view=='change_password'}selected"{/if}"onclick="window.location='profile.php?view=change_password'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Change Password{/t}</button>
<button  class="{if $view=='edit_billing'}selected"{/if}" onclick="window.location='profile.php?view=edit_billing'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Edit Billing Detail{/t}</button>
<button  class="{if $view=='edit_details'}selected"{/if}"onclick="window.location='profile.php?view=edit_details'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Edit Profile{/t}</button>
<button  class="{if $view=='address_book'}selected"{/if}"onclick="window.location='profile.php?view=address_book'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>
<button  class="{if $view=='orders'}selected"{/if}" onclick="window.location='profile.php?view=orders'" ><img src="art/icons/table.png" alt=""> {t}Orders{/t}</button>
<button  class="{if $view=='contact'}selected"{/if}" onclick="window.location='profile.php?view=contact'" ><img src="art/icons/chart_pie.png" alt=""> {t}My Account{/t}</button>


</div>


<div style="clear:both"></div>
</div>

<div id="contact_block" {if $view!='contact'}style="display:none"{/if}>
<div style="padding:0px 20px;float:left">
<h2>{t}Contact Details{/t}</h2>
<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">
<h3>{$page->customer->get('Customer Name')} ({$page->customer->get_formated_id()})</h3> 

<table id="customer_data" border=0 style="width:100%;margin-top:20px">
<tr><td colspan=2  class="aright">{$page->customer->get('Customer Main Contact Name')}</td ></tr>
<tr><td>
<div class="buttons">
<button   onclick="window.location='client.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}Edit Profile{/t}</button>

</div>
</td></tr>
</table>

</div>
</div>
<div style="padding:0px 20px;float:right">
<h2>{t}Delivery Address{/t}</h2>
<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">

<table id="customer_data" border=0 style="width:100%;margin-top:0px">
<tr><td>{$page->customer->get('Customer XHTML Main Delivery Address')}</td ></tr>
<tr><td>
<div class="buttons">
<button  onclick="window.location='address_book.php'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>

</div>
</td></tr>
</table>

</div>
</div>
<div style="padding:0px 20px;float:right">
<h2>{t}Billing Details{/t}</h2>
<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">

<table id="customer_data" border=0 style="width:100%;margin-top:0px">
<tr><td>{$page->customer->get('Customer XHTML Main Billing Address')}</td ></tr>
<tr><td>
<div class="buttons">
<button  onclick="window.location='address_book.php'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>
<button  onclick="window.location='address_book.php'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Edit Billing Details{/t}</button>

</div>
</td></tr>
</table>

</div>
</div>
<div style="padding:0px 20px;float:right;clear:right">
<h2>{t}Account Details{/t}</h2>
<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">

<table id="customer_data" border=0 style="width:100%;margin-top:0px">
<tr><td>{t}Handle{/t}:</td><td   class="aright">{$user->get('User Handle')}</td ></tr>
<tr><td>{t}Password{/t}:</td><td   class="aright">********</td ></tr>

<tr><td colspan=2>
<div class="buttons">
<button  onclick="window.location='address_book.php'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Change Password{/t}</button>

</div>
</td></tr>
</table>

</div>
</div>     
</div>
       
<div id="orders_block" {if $view!='orders'}style="display:none"{/if}>
</div>     
<div id="address_book_block" {if $view!='address_book'}style="display:none"{/if}>
</div>     
<div id="edit_billing_block" {if $view!='edit_billing'}style="display:none"{/if}>
</div>     
<div id="change_password_block" {if $view!='change_password'}style="display:none"{/if}>

<div id="dialog_change_password"    class="dialog_inikoo logged"  >
<h2>{t}Change Password{/t}</h2>
<div style="border:1px solid #ccc;padding:20px;width:400px;float:left">




<table border=0 id="change_password_form" >


<tr style="display:none;width:120px"><td class="label" >{t}Current Password{/t}: </td><td><input type="password" id="current_password_password1"></td></tr>
<tr><td style="width:120px" class="label">{t}New Password{/t}: </td><td><input type="password" id="change_password_password1"></td></tr>
<tr><td style="width:120px" class="label">{t}Confirm pwd{/t}: </td><td><input type="password" id="change_password_password2"></td></tr>
<input id="epwcp1" value="{$epwcp1}" type="hidden"/>
<input id="epwcp2" value="{$rnd}" type="hidden"/>


<tr  id="tr_change_password_buttons"  class="button space" >
<td colspan=2>

<div class="buttons" id="change_password_buttons" >
<button id="submit_change_password" class="negative">{t}Submit Changes{/t}</button> 
<button  id="cancel_change_password" class="positive">{t}Cancel{/t}</button>
</div>
</td></tr>
<tr id="tr_change_password_wait"  style="display:none" class="button" ><td colspan=2><img style="weight:24px" src="art/loading.gif"> <span style="position:relative;top:-5px">{t}Submitting changes{/t}</span></td></tr>




</table>
</div>
<div id="change_password_ok" class="ok_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
{t}Your password has been changed{/t}.
</div>
<div id="change_password_error_no_password" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
{t}Write new password{/t}.
</div>
<div id="change_password_error_password_not_march" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
{t}Passwords don't match{/t}.
</div>
<div id="change_password_error_password_too_short" class="error_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
{t}Password is too short{/t}.
</div>
<div id="processing_change_password" class="info_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
<img style="vertical-align:top" src="art/loading.gif" alt=""> {t}Processing Request{/t}
</div>

</div>





</div>     
<div id="edit_details_block" {if $view!='edit_details'}style="display:none"{/if}>
</div>     





