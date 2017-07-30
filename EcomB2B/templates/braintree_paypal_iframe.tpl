<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en_GB' xml:lang='en_GB' xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Paypal</title>

    {foreach from=css_files item=css_file}
        <link rel="stylesheet" href="{$css_file}">

    {/foreach}
    {foreach from=js_files item=js_file}
        <script type="text/javascript" src="{$js_file}"></script>
    {/foreach}
</head>
<body class="yui-skin-sam inikoo">
<div id="possessing_payment_message" style="display:none"  ><i class="fa fa-spinner fa-spin"></i> {t}Processing Payment{/t} </div>

    <input type="hidden" id="braintree_clientToken_paypal" value="{$braintree_clientToken_paypal}"> 

    <input type="hidden" id="order_key" value="{$order_key}"> 
    <input type="hidden" id="paypal_amount" value="{$amount}"> 
    <input type="hidden" id="paypal_currency" value="{$currency}"> 
    <input type="hidden" id="braintree_paypal_account_key" value="{$braintree_account_key}"> 
    <div id="braintree-paypal-container">
        <div id="braintree-paypal-button"></div>
    </div>
    <div id="braintree-paypal_message">
    </div>
</body>
</html>
