{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 September 2017 at 16:29:01 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<p class="single_line_height" style="font-size: 85%"> {$data.labels._bank_header}</p>

<p class="single_line_height" style="font-size: 85%;margin-top:5px">
<span>{if isset($labels._bank_beneficiary_label) and $labels._bank_beneficiary_label!=''}{$labels._bank_beneficiary_label}{else}{t}Beneficiary{/t}{/if}</span>: {$bank_payment_account->get("Payment Account Recipient Holder")}
<br/>
<span>{if isset($labels._bank_account_number_label) and $labels._bank_account_number_label!=''}{$labels._bank_account_number_label}{else}{t}Account Number{/t}{/if}</span>: {$bank_payment_account->get("Payment Account Recipient Bank Account Number")}
<br/>
{if $bank_payment_account->get("Payment Account Recipient Bank IBAN")!=''}<span>IBAN</span>: {$bank_payment_account->get("Payment Account Recipient Bank IBAN")}<br/>{/if}


<span>{if isset($labels.website_localized_label) and $labels.website_localized_label!=''}{$labels.website_localized_label}{else}{t}Bank{/t}{/if}</span>:
<b>{$bank_payment_account->get("Payment Account Recipient Bank Name")}</b><br/>
{if $bank_payment_account->get("Payment Account Recipient Bank Code")!=''}
    <span>{if isset($labels._bank_sort_code) and $labels._bank_sort_code!=''}{$labels._bank_sort_code}{else}{t}Bank Code{/t}{/if}</span>: {$bank_payment_account->get("Payment Account Recipient Bank Code")}
    <br/>
{/if}
{if $bank_payment_account->get("Payment Account Recipient Bank Swift")!=''}<span>Swift</span>: {$bank_payment_account->get("Payment Account Recipient Bank Swift")}<br/>{/if}
{if $bank_payment_account->get("Payment Account Recipient Address")!=''}
    <span>{if isset($labels._bank_address_label) and $labels._bank_address_label!=''}{$labels._bank_address_label}{else}{t}Address{/t}{/if}</span>: {$bank_payment_account->get("Payment Account Recipient Address")}

{/if}
</p>

<p class="single_line_height" style="font-size: 85%;;margin-top:5px"> {$data.labels._bank_footer}</p>

