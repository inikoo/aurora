<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Frames v2</title>


    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
</head>

<body>

<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">

        <div>

            <div id="company-type-form" class="{if  $customer->get('hokodo_type')!=''}hidden{/if} ">
            <label for="company-type"
                   class="  block text-sm font-medium text-gray-700"> {t}Company type{/t}</label>
            <div id="company-type" class=" mt-1 grid grid-cols-2 gap-3">

                <div id="company-type-registered-company"   data-value="registered-company" class="option
                {if $company_type=='registered-company'}checked  bg-indigo-600 border-transparent text-white {else}cursor-pointer bg-white border-gray-200 text-gray-900 hover:bg-gray-50{/if}
                border rounded-md py-3 px-3 flex items-center justify-center text-sm font-medium  sm:flex-1  focus:outline-none">

                    <p  >{t}Registered company{/t}</p>
                </div>

                <div id="company-type-sole-trader" data-value="sole-trader" class="option
                {if $company_type=='sole-trader'}checked bg-indigo-600 border-transparent text-white {else}cursor-pointer bg-white border-gray-200 text-gray-900 hover:bg-gray-50{/if}
                border rounded-md py-3 px-3 flex items-center justify-center text-sm font-medium  sm:flex-1  focus:outline-none">
                    <p >{t}Sole trader{/t}</p>
                </div>
            </div>
            </div>

            <div  id="saved-registered-company" class="pt-5 id_from {if  $customer->get('hokodo_type')!='registered-company'  }hidden{/if}">
                <label for="search-company" class="block text-sm font-medium text-gray-700">{t}Company information{/t}
                    <span id="reset-company" style="cursor:pointer" class=" ml-3 text-blue-600 pointer">{t}reset{/t}</span>
                </label>
                <div class="mt-1">
                    {$saved_company_info}
                </div>
                <div class="mt-3">
                    <button id="request-credit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{t}Request credit{/t}</button>

                </div>


            </div>

            <div  id="saved-sole-trader" class="pt-5 id_from {if  $customer->get('hokodo_type')!='sole-trader'  }hidden{/if}">
                <label for="search-company" class="block text-sm font-medium text-gray-700">{t}Sole trader information{/t}
                    <span id="reset-sole" style="cursor:pointer" class=" ml-3 text-blue-600 pointer">{t}reset{/t}</span>
                </label>
                <div class="mt-1">
                    {$saved_solo_info}

                </div>
                <div class="mt-3">
                    <button id="request-credit-sole" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{t}Request credit{/t}</button>

                </div>


            </div>
            <div id="sole-trader-form" class="mt-5 id_from {if $company_type!='sole-trader' or  $customer->get('hokodo_type')}hidden{/if}">

                <form id="sole-trader-form" class="space-y-6" action="#" method="POST">

                    <div>
                        <label for="trading_name" class="block text-sm font-medium text-gray-700"> Trading name </label>
                        <div class="mt-1">
                            <input id="trading_name" name="trading_name" type="text"  required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="trading_address" class="block text-sm font-medium text-gray-700"> Trading address (Building and street)</label>
                        <div class="mt-1">
                            <input id="trading_address" name="trading_address" type="text" placeholder="{t}Building and street{/t}" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="trading_address_city" class="block text-sm font-medium text-gray-700"> Trading address (City/Town)</label>
                        <div class="mt-1">
                            <input id="trading_address_city" name="trading_address_city" type="text" placeholder="{t}City/Town{/t}" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="trading_address_postcode" class="block text-sm font-medium text-gray-700"> Trading address (Post code)</label>
                        <div class="mt-1">
                            <input id="trading_address_postcode" name="trading_address_postcode" type="text" placeholder="{t}Post code{/t}" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="proprietor_name" class="block text-sm font-medium text-gray-700"> Proprietor name </label>
                        <div class="mt-1">
                            <input id="proprietor_name" name="proprietor_name" type="text"  required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>


                    <div>
                        <label for="proprietor_address" class="block text-sm font-medium text-gray-700"> Proprietor address line 1</label>
                        <div class="mt-1">
                            <input id="proprietor_address" name="proprietor_address" type="text" placeholder="{t}Building{/t}" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="proprietor_address2" class="block text-sm font-medium text-gray-700"> Proprietor address line 2</label>
                        <div class="mt-1">
                            <input id="proprietor_address2" name="proprietor_address2" type="text" placeholder="{t}Street{/t}" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="proprietor_address_city" class="block text-sm font-medium text-gray-700"> Proprietor address (City/Town)</label>
                        <div class="mt-1">
                            <input id="proprietor_address_city" name="proprietor_address_city" type="text" placeholder="{t}City/Town{/t}" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="proprietor_address_postcode" class="block text-sm font-medium text-gray-700"> Proprietor address (Post code)</label>
                        <div class="mt-1">
                            <input id="proprietor_address_postcode" name="proprietor_address_postcode" type="text" placeholder="{t}Post code{/t}" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                  

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700"> Proprietor's date of birth (e.g. 1969-12-31)  </label>
                        <div class="mt-1">
                            {literal}
                            <input type="text" id="birth_date"  name="birth_date"  placeholder="YYYY-MM-DD" required pattern="(?:19|20)(?:(?:[13579][26]|[02468][048])-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))|(?:[0-9]{2}-(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:29|30))|(?:(?:0[13578]|1[02])-31)))"    autofocus autocomplete="nope">
                            {/literal}

                        </div>
                    </div>



                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Apply for credit</button>
                    </div>


                    <div class="text-xs text-gray-500">
                        Our payment partner Hokodo uses soft searches with credit reference agencies. These do not affect your credit rating. More information <a target="_blank" class="text-indigo-600" href="https://www.experian.co.uk/legal/crain/">here</a>
                    </div>

                </form>



            </div>


            <div  id="registered-company-form" class="pt-5 id_from {if $company_type!='registered-company' or $customer->get('hokodo_type')  }invisible{/if}">
                <label for="search-company" class="block text-sm font-medium text-gray-700">{t}Company information{/t}
                    <span id="reset-search-company" class="hidden ml-3 text-blue-600 pointer">{t}reset{/t}</span>
                </label>
                <div class="mt-1"> 
                    <select class="w-full search-company">
                    </select>

                </div>
            </div>








            <div class="processing mt-3 hidden">
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


            <div id="place_order_form" class="hidden">

            <fieldset id="plans" class="mt-4">
                <legend class="sr-only">Payment plans</legend>
                <div id="plans_container" class="relative bg-white rounded-md -space-y-px">

                </div>
            </fieldset>


            <div id="mock_place_order" class="mt-4">
                <button  class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-300 bg-gray-100">{t}Place order{/t}</button>
            </div>

            <div  id="submit_place_order" class="mt-4 hidden">
                <button  class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{t}Place order{/t}</button>
            </div>

            </div>

        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    jQuery(function() {
        $('#company-type .option').on('click', function (e) {

            if( !$('.processing').hasClass('hidden')){
                return;
            }

         if(! $(this).hasClass('checked'))
         {



             $('#company-type .option').removeClass('checked  bg-indigo-600 border-transparent text-white')
                 .addClass('cursor-pointer bg-white border-gray-200 text-gray-900 hover:bg-gray-50')

             $(this).addClass('checked  bg-indigo-600 border-transparent text-white')
                 .removeClass('cursor-pointer bg-white border-gray-200 text-gray-900 hover:bg-gray-50')


             $('.id_from').addClass('hidden')
             $('#'+$(this).data('value')+'-form').removeClass('hidden invisible')
             $('#place_order_form').addClass('hidden')
             $(".search-company").val('').trigger('change')


         }

        });



        $('.search-company').select2({
            ajax: {
                url: 'ar_web_hokodo_companies.php',
                dataType: 'json',
                placeholder: "{t}Search your company{/t}",
                delay: 250,
                allowClear: true,
                data: function (params) {
                    let query = {
                        name: params.term,
                        country: '{$customer->get("Customer Invoice Address Country 2 Alpha Code")}'
                    };

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                }

            }
        });

        $('.search-company').on('select2:select', function (e) {
            let data_company = e.params.data;


            $('#reset-search-company').removeClass('hidden')
            $('.processing').removeClass('hidden')

            $.post("ar_web_hokodo_create_org.php",
                {
                    company_id: data_company.id,
                    company_name: data_company.text,
                },
                function (raw_data, status) {

                    $('.processing').addClass('hidden')

                    const data= JSON.parse(raw_data)

                    if(data.status==='ok'){
                        $('#place_order_form').removeClass('hidden')
                        $('#plans_container').html(data.plans)
                        $('#plans_container').data('order_id',data.order_id)
                    }else{
                        $('#plans_container').html(data.plans)
                    }


                });




          //  $('#create_org').removeClass('hidden').data('co', data.id)

        });

        $('.search-company').on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();

        });

        $('#reset-search-company').on('click', function (e) {
            $('#reset-search-company').addClass('hidden')
            $(".search-company").val('').trigger('change')

            $('#saved-registered-company').addClass('hidden')
            $('#company-type-form').removeClass('hidden')
            $('#registered-company-form').removeClass('invisible hidden')

            $('#place_order_form').addClass('hidden');

        });

        $('#reset-company').on('click', function (e) {
            $('#saved-registered-company').addClass('hidden')
            $('#company-type-form').removeClass('hidden')
            $('#registered-company-form').removeClass('invisible hidden')

            $('#place_order_form').addClass('hidden');
            $(".search-company").val('').trigger('change')






        });

        $('#reset-sole').on('click', function (e) {
            $('#saved-sole-trader').addClass('hidden')
            $('#company-type-form').removeClass('hidden')
            $('#sole-trader-form').removeClass('hidden')


        });

        $('body').on('change', 'input:radio[name="paying-plan"]', function() {
            if (this.checked ) {

                $('#mock_place_order').addClass('hidden')
                $('#submit_place_order').removeClass('hidden').data('url',this.value).data('id',$(this).data('id'))


            }
        });



        $('#submit_place_order').on('click', function (e) {

            const payment_url=$(this).data('url');
            const payment_id=$(this).data('id');
            const order_id=$('#plans_container').data('order_id');


            $.post("ar_web_hokodo_create_payment.php",
                {
                    payment_id: payment_id,
                    payment_url: payment_url,
                    order_id:order_id
                },
                function (raw_data, status) {
                    window.parent.location.href = payment_url
                });
        });





        $("#sole-trader-form").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            let actionUrl = 'ar_web_hokodo_create_sole_trader.php';


            $.ajax({
                type: "POST",
                url: actionUrl,
                data: {
                    'trading_name': $('#trading_name').val(),
                    'trading_address': $('#trading_address').val(),
                    'trading_address_city': $('#trading_address_city').val(),
                    'trading_address_postcode': $('#trading_address_postcode').val(),

                    'proprietor_name': $('#proprietor_name').val(),
                    'proprietor_address': $('#proprietor_address').val(),
                    'proprietor_address2': $('#proprietor_address2').val(),

                    'proprietor_address_city': $('#proprietor_address_city').val(),
                    'proprietor_address_postcode': $('#proprietor_address_postcode').val(),



                    'birth_date': $('#birth_date').val()
                },
                success: function(raw_data)
                {
                    const data= JSON.parse(raw_data)
                    $('#sole-trader-form').addClass('hidden');
                    $('#place_order_form').removeClass('hidden')
                    $('#plans_container').html(data.plans)
                    $('#plans_container').data('order_id',data.order_id)

                }
            });

        });


        $('#request-credit').on('click', function (e) {


            $.post("ar_web_hokodo_create_order_saved.php",
                {

                },
                function (raw_data, status) {
                    const data= JSON.parse(raw_data)
                    $('#sole-trader-form').addClass('hidden');
                    $('#registered-company-form').addClass('hidden');
                    $('#request-credit').addClass('hidden');
                    $('#place_order_form').removeClass('hidden')
                    $('#plans_container').html(data.plans)
                    $('#plans_container').data('order_id',data.order_id)


                });
        });


        $('#request-credit-sole').on('click', function (e) {


            $.post("ar_web_hokodo_create_order_saved.php",
                {

                },
                function (raw_data, status) {
                    const data= JSON.parse(raw_data)
                    $('#sole-trader-form').addClass('hidden');
                    $('#registered-company-form').addClass('hidden');
                    $('#place_order_form').removeClass('hidden')
                    $('#plans_container').html(data.plans)
                    $('#plans_container').data('order_id',data.order_id)

                });
        });




    })

</script>
</body>
</html>