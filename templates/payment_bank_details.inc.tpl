{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 August 2017 at 10:08:22 CEST, Trnava , Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<p id="_bank_header"> {$content._bank_header}</p>

<br>
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
    <br/>
{/if}


<br><p id="_bank_footer">{$content._bank_footer}</p>


