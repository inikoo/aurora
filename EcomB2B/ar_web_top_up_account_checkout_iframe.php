<?php

include_once 'ar_web_common_logged_in.php';
/** @var Public_Customer $customer */

$account = get_object('Account', 1);

$website = get_object('Website', $_SESSION['website_key']);


if (empty($_REQUEST['top_up']) or !is_numeric($_REQUEST['top_up']) or $_REQUEST['top_up'] <= 0) {
    $response = array(
        'state' => 400,
        'html'  => '<div style="margin:100px auto;text-align: center">Wrong top up amount</div>'


    );
    echo json_encode($response);
    exit;
}


$to_pay = $_REQUEST['top_up'];


$sql =
    "SELECT `Payment Account Store Payment Account Key`,`login` FROM `Payment Account Store Bridge` B left join `Payment Account Dimension` PAD on PAD.`Payment Account Key`=B.`Payment Account Store Payment Account Key`  
WHERE `Payment Account Store Website Key`=? AND `Payment Account Store Status`='Active' AND `Payment Account Store Show in Cart`='Yes' and `Payment Account Block`='Checkout' ";
/** @var TYPE_NAME $db */
$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $website->id
    ]
);
$payment_account_key_key = false;
while ($row = $stmt->fetch()) {
    $public_key = $row['login'];
    $payment_account_key = $row['Payment Account Store Payment Account Key'];
}

if (!$payment_account_key) {
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Frames v2</title>
    <style>*, *::after,
        *::before {
            box-sizing: border-box
        }

        html {
            padding: 1rem;
            background-color: #FFF;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif
        }

        #payment-form {
            width: 31.5rem;
            margin: 0 auto
        }

        iframe {
            width: 100%
        }

        .one-liner {
            display: flex;
            flex-direction: column
        }

        #pay-button {
            border: none;
            border-radius: 3px;
            color: #FFF;
            font-weight: 500;
            height: 40px;
            width: 100%;
            background-color: #13395E;
            box-shadow: 0 1px 3px 0 rgba(19, 57, 94, 0.4)
        }

        #pay-button:active {
            background-color: #0B2A49;
            box-shadow: 0 1px 3px 0 rgba(19, 57, 94, 0.4)
        }

        #pay-button:hover {
            background-color: #15406B;
            box-shadow: 0 2px 5px 0 rgba(19, 57, 94, 0.4)
        }

        #pay-button:disabled {
            background-color: #697887;
            box-shadow: none
        }

        #pay-button:not(:disabled) {
            cursor: pointer
        }

        .card-frame {
            border: solid 1px #13395E;
            border-radius: 3px;
            width: 100%;
            margin-bottom: 8px;
            height: 40px;
            box-shadow: 0 1px 3px 0 rgba(19, 57, 94, 0.2)
        }

        .card-frame.frame--rendered {
            opacity: 1
        }

        .card-frame.frame--rendered.frame--focus {
            border: solid 1px #13395E;
            box-shadow: 0 2px 5px 0 rgba(19, 57, 94, 0.15)
        }

        .card-frame.frame--rendered.frame--invalid {
            border: solid 1px #D96830;
            box-shadow: 0 2px 5px 0 rgba(217, 104, 48, 0.15)
        }

        .success-payment-message {
            color: #13395E;
            line-height: 1.4
        }


        @media only screen and (min-width: 600px) {


            .one-liner {
                flex-direction: row
            }

            .card-frame {
                width: 318px;
                margin-bottom: 0
            }

            #pay-button {
                width: 175px;
                margin-left: 8px
            }

        }

        @media screen and (max-width: 450px) {


            #payment-form {
                width: 98%;

            }
        }


        .hide {
            display: none;
        }

    </style>

    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
</head>

<body>

<!-- add frames script -->
<script src="https://cdn.checkout.com/js/framesv2.min.js"></script>


<form id="payment-form" method="POST" action="">
    <div class="one-liner">
        <div class="card-frame">
            <!-- form will be added here -->
        </div>
        <!-- add submit button -->
        <button id="pay-button" disabled>
            PAY <?php
            echo $to_pay ?>
        </button>
    </div>
    <p class="processing hide" style="color: darkgray">
        <svg width="51px" height="16px" viewBox="0 0 51 50">

            <rect y="0" width="13" height="50" fill="#1fa2ff">
                <animate attributeName="height" values="50;10;50" begin="0s" dur="1s" repeatCount="indefinite"/>
                <animate attributeName="y" values="0;20;0" begin="0s" dur="1s" repeatCount="indefinite"/>
            </rect>

            <rect x="19" y="0" width="13" height="50" fill="#12d8fa">
                <animate attributeName="height" values="50;10;50" begin="0.2s" dur="1s" repeatCount="indefinite"/>
                <animate attributeName="y" values="0;20;0" begin="0.2s" dur="1s" repeatCount="indefinite"/>
            </rect>

            <rect x="38" y="0" width="13" height="50" fill="#06ffcb">
                <animate attributeName="height" values="50;10;50" begin="0.4s" dur="1s" repeatCount="indefinite"/>
                <animate attributeName="y" values="0;20;0" begin="0.4s" dur="1s" repeatCount="indefinite"/>
            </rect>

        </svg>
        Processing
    </p>
    <p class="success-payment-message">


    </p>
</form>


<script>
    var payButton = document.getElementById("pay-button");
    var form = document.getElementById("payment-form");

    Frames.init({
        publicKey: "<?php echo $public_key?>"
    });


    Frames.addEventHandler(
        Frames.Events.CARD_VALIDATION_CHANGED,
        function (event) {
            // console.log("CARD_VALIDATION_CHANGED: %o", event);

            payButton.disabled = !Frames.isCardValid();
        }
    );


    Frames.addEventHandler(
        Frames.Events.CARD_TOKENIZED,
        function (event) {

            console.log('xxxx');

            const ajaxData = new FormData();

            ajaxData.append("tipo", 'top_up_pay_checkout')
            ajaxData.append("payment_account_key",<?php echo $payment_account_key?> )
            ajaxData.append("token", event.token)

            ajaxData.append("amount", <?php echo $to_pay ?>)


            $.ajax({
                url: "/ar_web_top_up.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                complete: function () {
                }, success: function (data) {



                    if (data.state == '200') {


                        window.top.location.replace("balance.sys");

                    } else if (data.state == '201') {
                        window.top.location.href = data.redirect;

                    } else if (data.state == '400') {
                        window.top.location.href = 'top_up.sys?error=payment&t=4';


                        // var el = document.querySelector(".success-payment-message");
                        // el.innerHTML = data.msg

                    }


                }, error: function () {
                    var el = document.querySelector(".success-payment-message");
                    el.innerHTML = 'Error, please try again'

                }
            });


        }
    );

    form.addEventListener("submit", function (event) {
        payButton.disabled = true // disables pay button once submitted

        console.log('ccc');

        $('.processing').removeClass('hide')
        event.preventDefault();
        Frames.submitCard();
    });
</script>

</body>

</html>



