<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Payplan</title>


    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>


</head>

<body>


<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">


    <div id="error" class=" mt-3  hidden">
        <p class=" hide" style="color: darkgray">
            {if !empty($labels._pastpay_error)}{{$labels._pastpay_error}}{else}Sorry you can not use this payment method, try other one{/if}

        </p>
    </div>


    <img src="art/pastpay.png" alt="pastpay" />


    <div id="pastpay_dialog" class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">

        <div class="pb-5">
            <p>{if !empty($labels._pastpay_text)}{{$labels._pastpay_text}}{/if}</p>
        </div>

        <fieldset id="plans_container" data-order_id="{$order->id}">
            <legend class="sr-only">{t}Plans{/t}</legend>


            <div class="space-y-4">
                <!--
                  Checked: "border-transparent", Not Checked: "border-gray-300"
                  Active: "border-indigo-600 ring-2 ring-indigo-600"
                -->

                {foreach from=$plans item=plan name=foo}
                    <label
                            {if $smarty.foreach.foo.first}
                                class="border-indigo-600  ring-2 ring-indigo-600  relative block cursor-pointer rounded-lg border bg-white px-6 py-4 shadow-sm focus:outline-none sm:flex sm:justify-between"

                            {else}
                                class="border-gray-300  relative block cursor-pointer rounded-lg border bg-white px-6 py-4 shadow-sm focus:outline-none sm:flex sm:justify-between"

                            {/if}
                    >
                        <input id="plan-{$plan.term}-option"   type="radio"   name="paying-plan" value="{$plan.term}" class="sr-only" aria-labelledby="plan-{$plan.term}-label"
                               aria-describedby="plan-{$plan.term}-description-0 plan-{$plan.term}-description-1">
                        <span class="flex items-center">
                                <span class="flex flex-col text-sm">
                                    <span id="plan-{$plan.term}-label" class="font-medium text-gray-900">{$plan.term_formatted}</span>
                                    <span id="plan-{$plan.term}-description-0" class="text-gray-500">
                                        <span class="block sm:inline">{t}Cost{/t}</span>
                                        <span class="hidden sm:mx-1 sm:inline" aria-hidden="true">&middot;</span>
                                        <span class="block sm:inline">{$plan.charge_amount_formatted}</span>
                                    </span>
                                </span>
                                </span>
                        <span id="plan-{$plan.term}-description-1" class="mt-2 flex text-sm sm:ml-4 sm:mt-0 sm:flex-col sm:text-right">
                                    <span class="font-medium text-gray-900">{$plan.charge_formatted}</span>
                                </span>
                        <!--
                          Active: "border", Not Active: "border-2"
                          Checked: "border-indigo-600", Not Checked: "border-transparent"
                        -->
                        <span class="pointer-events-none absolute -inset-px rounded-lg border-2" aria-hidden="true"></span>
                    </label>
                {/foreach}

            </div>
        </fieldset>


            <div id="choose_plans" class="space-y-6" action="#" method="POST">

                <div id="mock_place_order" class="hidden">
                    <button type="button" disabled
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-400 bg-indigo-200 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {t}Select a plan{/t}
                    </button>
                </div>
                <div id="submit_place_order" data-term="30">
                    <button
                            class=" w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {if !empty($labels._place_order_from_pastpay)}{{$labels._place_order_from_pastpay}}{else}Apply for credit{/if}
                    </button>

                </div>


                <div id="processing" class=" mt-3  hidden">
                    <p class=" hide" style="color: darkgray">
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
                        {t}Processing{/t}
                    </p>
                </div>

                <div class="text-xs text-gray-500">
                    {if !empty($labels._pastpay_footer_info)}{{$labels._pastpay_footer_info}}{/if}
                </div>
            </div>
    </div>
</div>


<script>
  jQuery(function() {



    $('body').on('change', 'input:radio[name="paying-plan"]', function() {
      $('label').addClass('border-gray-300').removeClass('border-indigo-600 ring-2 ring-indigo-600');
      $(this).closest('label').addClass('border-indigo-600 ring-2 ring-indigo-600');
      if (this.checked) {
        $('#submit_place_order').data('term',this.value);
        // $('#mock_place_order').addClass('hidden');
        //  $('#submit_place_order').removeClass('hidden').data('term', this.value);

      }
    });

    $('#submit_place_order').on('click', function(e) {

      const term = $(this).data('term');
      const order_id = $('#plans_container').data('order_id');

      $('#submit_place_order').addClass('hidden');
      $('#processing').removeClass('hidden');


      $.ajax({
               type   : 'POST',
               url    : 'ar_web_pastpay_get_redirect_url.php',
               data   : {
                 term    : term,
                 order_id: order_id,
               },
               success: function(raw_data) {
                 $('#processing').addClass('hidden');

                // console.log(raw_data)
                 const data = JSON.parse(raw_data);

                 if(data.status==='ok'){
                   window.parent.location.href = data.redirect;

                 }else{
                    $('#pastpay_dialog').addClass('hidden')
                   $('#error').removeClass('hidden');
                 }


               },
             });

    });

  });

</script>
</body>
</html>