<!DOCTYPE html>
<html lang="en">

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout Frames v3</title>

<script src="https://cdn.tailwindcss.com?plugins=forms"></script>

<script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>


{if $devel}
    <script src="https://js-sandbox.hokodo.co/hokodo-js/v1"/>
    </script>
{else}
    <script src="https://js.hokodo.co/hokodo-js/v1"></script>
{/if}


</head>

<body>


<div class=" sm:mx-auto sm:w-full sm:max-w-md  md:max-w-lg lg:max-w-xl  ">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10   ">

        <div>


            <div id="hokodoCompanySearch"></div>
            <button id="get_plans" type="button" data-updating_label="Updating details"
                    data-waiting_label="Processing request" data-label="Request credit"
                    class="{if $customer->get('hokodo_co_id')==''}hidden{/if}  mt-6 text-center px-3 py-4 border border-transparent text-base leading-4 w-full	 rounded-md shadow-sm text-gray-700 bg-[#64cdc8] hover:bg-[#7bd5d0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#64cdc8]">
                Request credit
            </button>

            <div id="hokodoCheckout"></div>

            <div id="rejected" class="hidden">
                <div class="inner">
                    <div>
                        <h2 class="text-xl pb-4	font-bold">Declined</h2>
                        <p class="font-semibold pb-4">
                          Apologies, we are unable to offer you any payment plans at this time. Please other payment method.
                        </p>
                        <p>
                            To appeal this decision please contact us at <a href="contact@hokodo.co" >contact@hokodo.co</a> or on +44 203 974 1620.
                        </p>
                    </div>
                </div>
            </div>
            <div id="failure" class="hidden">
                <div class="inner">
                    <div>
                        <h2 class="text-xl pb-4	font-bold">Payment Error</h2>
                        <p class="font-semibold pb-4">
                            Apologies, an error occurred when loading your selected payment method. Please try again or choose other payment method.
                        </p>
                        <p>
                            Please contact us at <a href="contact@hokodo.co" >contact@hokodo.co</a> or on +44 203 974 1620.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>


</body>
<script>

    const public_api_key = "{$public_api_key}";

    let first_render = {if $customer->get('hokodo_co_id')!=''}"Yes"
    {else}"No"{/if}

    let hokodo_order_id = '';

    //console.log(public_api_key);
    const hokodo = Hokodo(public_api_key);
    const elements = hokodo.elements();


    const companySearch = elements.create("companySearch"
            {if $customer->get('hokodo_co_id')!=''}
        , {
            companyId: "{$customer->get('hokodo_co_id')}",
        }
            {/if}
    );

    companySearch.on("companySelection", (selectedCompany) => {

        if (selectedCompany !== null) {
            if (first_render === 'No') {
                $('#get_plans').removeClass('hidden').addClass('waiting').html($('#get_plans').data('updating_label')).removeClass('bg-[#64cdc8] hover:bg-[#7bd5d0] shadow-sm')
                $.post("ar_web_hokodo_identify_customer_from_sdk.php",
                    {
                        data: selectedCompany,
                    },
                    function (raw_data, status) {
                        $('#get_plans').html($('#get_plans').data('label')).removeClass('waiting').addClass('bg-[#64cdc8] hover:bg-[#7bd5d0] shadow-sm')


                    });
            }
            first_render = 'No'
        } else {
            if (first_render === 'No') {
                $('#get_plans').addClass('hidden')
            }


        }

    });

    ;

    companySearch.mount("#hokodoCompanySearch");


    $("#get_plans").on('click', function () {

        if ($('#get_plans').hasClass('waiting')) {
            return;
        }

        $('#get_plans').html($('#get_plans').data('waiting_label')).addClass('waiting')

        $.getJSON("ar_web_hokodo_get_plans_for_sdk.php", function (data, status) {

            //  console.log(data)

            hokodo_order_id = response.data.order;

            const checkout = elements.create("checkout", {
                "paymentOffer": data.response
            });

            checkout.mount("#hokodoCheckout");

            checkout.on('ready', () => {
                const companySearch = elements.getElement("companySearch");
                companySearch.destroy();
                $('#get_plans').addClass('hidden')
            });

            checkout.on('success', () => {



                $.post("ar_web_hokodo_place_order_from_sdk.php",
                    {
                        hokodo_order_id: hokodo_order_id,
                        order_id: '{$order_id}',
                    },
                    function (raw_data) {

                        //console.log(raw_data)
                        //console.log(raw_data.status)
                        if (raw_data.status === 'ok') {
                            //console.log(raw_data)
                            window.parent.location.href = raw_data.url
                        } else {

                            // show error
                        }

                    },'json').fail(function(jqxhr, settings, ex) {


                    $('#companySearch').addClass('hidden')
                    $('#hokodoCheckout').addClass('hidden')

                    $('#get_plans').addClass('hidden')
                    $('#rejected').removeClass('hidden')

                });


            });



            checkout.on('rejected', () => {


                $('#companySearch').addClass('hidden')
                $('#hokodoCheckout').addClass('hidden')

                $('#get_plans').addClass('hidden')
                $('#rejected').removeClass('hidden')





            });

            checkout.on('failure', () => {


                $('#companySearch').addClass('hidden')
                $('#hokodoCheckout').addClass('hidden')

                $('#get_plans').addClass('hidden')

                if($('#rejected').hasClass('hidden')){
                    $('#failure').removeClass('hidden')
                }







            });


        });
    });


</script>
</html>