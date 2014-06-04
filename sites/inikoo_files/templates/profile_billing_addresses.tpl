{include file='profile_header.tpl' select='billing_addresses'} 
<input type="hidden" id="subject" value="Customer">
<input type="hidden" id="subject_key" value="{$customer->id}">
<input type="hidden" id="default_country_2alpha" value="{$default_country_2alpha}">
<div style="padding:20px">
{include file='edit_billing_address_splinter.tpl'}
</div>