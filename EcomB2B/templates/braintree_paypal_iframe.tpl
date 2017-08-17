<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en_GB' xml:lang='en_GB' xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Paypal</title>

    <script src="/theme_1/local/jquery.js"></script>

    <script type="text/javascript" src="/js/braintree.js"></script>


    <style>
    #braintree-paypal-container{
        width:500px;float:left;}
    #braintree-paypal-button{
        margin-left:50px}


    #braintree-paypal_message{
        float:left;
        padding:20px;
        margin-left:10px;
        border-radius: 5px;


    }



    #braintree-paypal_message.error{
        border:1px solid tomato;
        background-color: #ffecec;
    }

    #braintree-paypal_message.info{
        border:1px solid #ccc;
        font-size:110%;
    }


    .link{
        font-weight:800;cursor:pointer}
    .link:hover{
        text-decoration: underline;}
</style>

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

<script>


        braintree.setup($('#braintree_clientToken_paypal').val(), "paypal", {
            singleUse: true,
            amount: $('#paypal_amount').val(),
            currency: $('#paypal_currency').val(),
            container: "braintree-paypal-button",
            onPaymentMethodReceived: function(obj) {


                $('#braintree-paypal-container').css('display', 'none')


                $('#braintree-paypal_message').html($('#possessing_payment_message').html())
                $('#braintree-paypal_message').addClass('info')


                console.log(obj)
                var ajaxData = new FormData();
                ajaxData.append("tipo", 'place_order_pay_braintree_paypal')

                ajaxData.append("payment_account_key",$('#braintree_paypal_account_key').val() )
                ajaxData.append("order_key",$('#order_key').val() )
                ajaxData.append("amount",$('#paypal_amount').val() )
                ajaxData.append("currency",$('#paypal_currency').val() )
                ajaxData.append("nonce",obj.nonce )

                $.ajax({
                    url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    },
                    success: function (data) {




                        if (data.state == '200') {
                            $('.ordered_products_number').html('0')
                            $('.order_total').html('')

                            window.location.replace("thanks.sys?order_key="+data.order_key);


                            window.parent.document.location="thanks.sys?order_key="+data.order_key


                        } else if (data.state == '400') {

                            swal({ title:"{t}Error{/t}!", text:data.msg, type:"error", html: true}
                            )

                        }



                    }

                }
                );
        }

        }
        )




    </script>
</html>
