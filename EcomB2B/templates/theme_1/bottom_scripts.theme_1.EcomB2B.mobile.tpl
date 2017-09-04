{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 00:36:04 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}



{if $webpage->get('Webpage Code')=='basket.sys'}

<div class="address_form" >
    <form id="order_delivery_address_form" class="sky-form sky-form-modal">
        <header id="_title">{if isset($labels._delivery_address_label) and $labels._delivery_address_label!=''}{$labels._delivery_address_label}{else}{t}Delivery Address{/t}{/if}</header>

        <fieldset  class="{if $store->get('Store Can Collect')=='No'}hide{/if} "  >


            <section>
                <label class="checkbox"><input class="ignored " type="checkbox"   {if  $store->get('Store Can Collect')=='Yes' and  $order->get('Order For Collection')=='Yes'}checked{/if} name="order_for_collection" id="order_for_collection"><i></i>{if empty($content._order_for_collection)}{t}Order for collection{/t}{else}{$content._order_for_collection}{/if}</label>
                </a> </label>


            </section>



        </fieldset>




        <fieldset id="order_delivery_address_fields" class=" {if $order->get('Order For Collection')=='Yes'  and  $store->get('Store Can Collect')=='Yes'}hide{/if}" style="position:relative">



            <section id="order_delivery_addressLine1" class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}hide{/if}">

                <label for="file" class="input">
                    <label class="label">{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</label>

                    <input value="{$order->get('Order Delivery Address Line 1')}" type="text"  name="addressLine1" class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                </label>
            </section>

            <section id="order_delivery_addressLine2" class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <label for="file" class="input">
                    <label class="label">{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</label>

                    <input  value="{$order->get('Order Delivery Address Line 2')}"  type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                </label>
            </section>



            <div id="order_delivery_sortingCode" class="row {if 'sortingCode'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</label>

                        <input value="{$order->get('Order Delivery Address Sorting Code')}"  type="text" name="sortingCode" class="{if 'sortingCode'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="order_delivery_postalCode" class="row {if 'postalCode'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if isset($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) and $labels["postalCode_`$delivery_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}</label>

                        <input value="{$order->get('Order Delivery Address Postal Code')}"  type="text" name="postalCode" class="{if 'postalCode'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) and $labels["postalCode_`$delivery_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) and $labels["postalCode_`$delivery_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="order_delivery_dependentLocality" class="row {if 'dependentLocality'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if isset($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}</label>

                        <input value="{$order->get('Order Delivery Address Dependent Locality')}"  type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}</b>
                    </label>
                </section>

            </div>

            <div id="order_delivery_locality" class="row {if 'locality'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if isset($labels["locality_`$delivery_address_labels.locality.code`"]) and $labels["locality_`$delivery_address_labels.locality.code`"]!=''}{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}</label>

                        <input value="{$order->get('Order Delivery Address Locality')}"  type="text" name="locality" class="{if 'locality'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["locality_`$delivery_address_labels.locality.code`"]) and $labels["locality_`$delivery_address_labels.locality.code`"]!=''}{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels["locality_`$delivery_address_labels.locality.code`"]) and $labels["locality_`$delivery_address_labels.locality.code`"]!=''}{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}</b>
                    </label>
                </section>

            </div>


            <div id="order_delivery_administrativeArea" class="row {if 'administrativeArea'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if isset($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}</label>
                        <input value="{$order->get('Order Delivery Address Administrative Area')}"  type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}</b>
                    </label>
                </section>

            </div>


            <div class="row" >
                <section class="col col-5">
                    <label class="select">
                        <select id="order_delivery_country_select" name="country">
                            <option value="0" selected disabled>{if isset($labels.address_country) and $labels.address_country!=''}{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                            {foreach from=$countries item=country}
                                <option value="{$country.2alpha}" {if $country.2alpha==$order->get('Order Delivery Address Country 2 Alpha Code')}selected{/if} >{$country.name}</option>
                            {/foreach}


                            <select><i></i>
                    </label>
                </section>


            </div>


        </fieldset>


        <footer>
            <button type="submit" class="button "  id="save_order_delivery_address_button" >{if isset($labels._save) and $labels._save!=''}{$labels._save}{else}{t}Save{/t}{/if}  <i  class="margin_left_10 fa fa-fw fa-floppy-o" aria-hidden="true"></i> </button>
            <a href="#" class="button button-secondary modal-closer">{if isset($labels._close) and $labels._close!=''}{$labels._close}{else}{t}Close{/t}{/if}</a>
        </footer>
    </form>
</div>
<div class="address_form" >
    <form id="order_invoice_address_form" class="sky-form sky-form-modal">
        <header id="_title">{if isset($labels._invoice_address_label) and $labels._invoice_address_label!=''}{$labels._invoice_address_label}{else}{t}invoice Address{/t}{/if}</header>






        <fieldset id="order_invoice_address_fields" class=" " style="position:relative">



            <section id="order_invoice_addressLine1" class="{if 'addressLine1'|in_array:$invoice_used_address_fields}{else}hide{/if}">

                <label for="file" class="input">
                    <label class="label">{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</label>

                    <input value="{$order->get('Order invoice Address Line 1')}" type="text"  name="addressLine1" class="{if 'addressLine1'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                </label>
            </section>

            <section id="order_invoice_addressLine2" class="{if 'addressLine2'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <label for="file" class="input">
                    <label class="label">{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</label>

                    <input  value="{$order->get('Order invoice Address Line 2')}"  type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                </label>
            </section>



            <div id="order_invoice_sortingCode" class="row {if 'sortingCode'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</label>

                        <input value="{$order->get('Order invoice Address Sorting Code')}"  type="text" name="sortingCode" class="{if 'sortingCode'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="order_invoice_postalCode" class="row {if 'postalCode'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if isset($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) and $labels["postalCode_`$invoice_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}</label>

                        <input value="{$order->get('Order invoice Address Postal Code')}"  type="text" name="postalCode" class="{if 'postalCode'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) and $labels["postalCode_`$invoice_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) and $labels["postalCode_`$invoice_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="order_invoice_dependentLocality" class="row {if 'dependentLocality'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if isset($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}</label>

                        <input value="{$order->get('Order invoice Address Dependent Locality')}"  type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}</b>
                    </label>
                </section>

            </div>

            <div id="order_invoice_locality" class="row {if 'locality'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if isset($labels["locality_`$invoice_address_labels.locality.code`"]) and $labels["locality_`$invoice_address_labels.locality.code`"]!=''}{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}</label>

                        <input value="{$order->get('Order invoice Address Locality')}"  type="text" name="locality" class="{if 'locality'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["locality_`$invoice_address_labels.locality.code`"]) and $labels["locality_`$invoice_address_labels.locality.code`"]!=''}{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels["locality_`$invoice_address_labels.locality.code`"]) and $labels["locality_`$invoice_address_labels.locality.code`"]!=''}{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}</b>
                    </label>
                </section>

            </div>


            <div id="order_invoice_administrativeArea" class="row {if 'administrativeArea'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if isset($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}</label>

                        <input value="{$order->get('Order invoice Address Administrative Area')}"  type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}</b>
                    </label>
                </section>

            </div>


            <div class="row" >
                <section class="col col-5">
                    <label class="select">
                        <select id="order_invoice_country_select" name="country">
                            <option value="0" selected disabled>{if isset($labels.address_country) and $labels.address_country!=''}{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                            {foreach from=$countries item=country}
                                <option value="{$country.2alpha}" {if $country.2alpha==$order->get('Order invoice Address Country 2 Alpha Code')}selected{/if} >{$country.name}</option>
                            {/foreach}


                            <select><i></i>
                    </label>
                </section>


            </div>


        </fieldset>


        <footer>
            <button type="submit" class="button "  id="save_order_invoice_address_button" >{if isset($labels._save) and $labels._save!=''}{$labels._save}{else}{t}Save{/t}{/if}  <i  class="margin_left_10 fa fa-fw fa-floppy-o" aria-hidden="true"></i>  </button>
            <a href="#" class="button button-secondary modal-closer">{if isset($labels._close) and $labels._close!=''}{$labels._close}{else}{t}Close{/t}{/if}</a>
        </footer>
    </form>
</div>
<script>
    $("form").submit(function(e) {

        e.preventDefault();
        e.returnValue = false;

        // do things
    });


    var special_instructions_timeout

    $(document).on('input propertychange', "#special_instructions", function(ev){


        if (special_instructions_timeout) clearTimeout(special_instructions_timeout);

        value= $(this).val()

        special_instructions_timeout = setTimeout(function () {

            var ajaxData = new FormData();

            ajaxData.append("tipo", 'special_instructions')
            ajaxData.append("value",value)


            $.ajax({
                url: "/ar_web_basket.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                complete: function () {
                }, success: function (data) {



                    if (data.state == '200') {



                    } else if (data.state == '400') {
                    }



                }, error: function () {

                }
            });

        }, 400);






    });







    $(document).on('change', "#order_for_collection", function(ev){

        if($(this).is(':checked')){
            $('#order_delivery_address_fields').addClass('hide')

        }else{
            $('#order_delivery_address_fields').removeClass('hide')

        }
    });

    $( "#order_invoice_country_select" ).change(function() {

        var selected=$( "#invoice_country_select option:selected" )
        // console.log(selected.val())

        var request= "ar_web_addressing.php?tipo=address_format&country_code="+selected.val()+'&website_key={$website->id}'

        console.log(request)
        $.getJSON(request, function( data ) {
            console.log(data)
            $.each(data.hidden_fields, function(index, value) {
                $('#invoice_'+value).addClass('hide')
                $('#invoice_'+value).find('input').addClass('ignore')

            });

            $.each(data.used_fields, function(index, value) {
                $('#invoice_'+value).removeClass('hide')
                $('#invoice_'+value).find('input').removeClass('ignore')

            });

            $.each(data.labels, function(index, value) {
                $('#invoice_'+index).find('input').attr('placeholder',value)
                $('#invoice_'+index).find('b').html(value)

            });

            $.each(data.no_required_fields, function(index, value) {


                // console.log(value)

                $('#invoice_'+value+' input').rules( "remove" );




            });

            $.each(data.required_fields, function(index, value) {
                console.log($('#'+value))
                //console.log($('#'+value+' input').rules())

                $('#invoice_'+value+' input').rules( "add", { required: true});

            });


        });


    });





    $("#order_invoice_address_form").validate(
        {

            submitHandler: function(form)
            {




                var button=$('#save_order_invoice_address_button');

                if(button.hasClass('wait')){
                    return;
                }

                button.addClass('wait')
                button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')


                var register_data={ }

                $("#order_invoice_address_form input:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){
                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                $("#order_invoice_address_form select:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){


                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });



                var ajaxData = new FormData();

                ajaxData.append("tipo", 'invoice_address')
                ajaxData.append("data", JSON.stringify(register_data))


                $.ajax({
                    url: "/ar_web_basket.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    }, success: function (data) {


                        $('#sky-form-modal-overlay').fadeOut();
                        $('.sky-form-modal').fadeOut();
                        $('#page-transitions').removeClass('hide')
                        if (data.state == '200') {

                            for (var key in data.metadata.class_html) {


                                $('.' + key).html(data.metadata.class_html[key])
                            }



                        } else if (data.state == '400') {
                            swal("{t}Error{/t}!", data.msg, "error")
                        }


                        button.removeClass('wait')
                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                    }, error: function () {
                        button.removeClass('wait')
                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                    }
                });


            },

            // Rules for form validation
            rules:
                {



    {foreach from=$invoice_required_fields item=required_field }
    {$required_field}: { required: true },
    {/foreach}

    {foreach from=$invoice_no_required_fields item=no_required_field }
    {$no_required_field}:{   required: false},
    {/foreach}

    },

    // Messages for form validation
    messages:
    {


        administrativeArea:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        locality:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        dependentLocality:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        postalCode:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        addressLine1:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        addressLine2:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        sortingCode:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        }




    },

    // Do not change code below
    errorPlacement: function(error, element)
    {
        error.insertAfter(element.parent());
    }
    });


    $( "#order_delivery_country_select" ).change(function() {

        var selected=$( "#order_delivery_country_select option:selected" )
        // console.log(selected.val())

        var request= "ar_web_addressing.php?tipo=address_format&country_code="+selected.val()+'&website_key={$website->id}'

        console.log(request)
        $.getJSON(request, function( data ) {
            console.log(data)
            $.each(data.hidden_fields, function(index, value) {
                $('#order_delivery_'+value).addClass('hide')
                $('#order_delivery_'+value).find('input').addClass('ignore')

            });

            $.each(data.used_fields, function(index, value) {
                $('#order_delivery_'+value).removeClass('hide')
                $('#order_delivery_'+value).find('input').removeClass('ignore')

            });

            $.each(data.labels, function(index, value) {
                $('#order_delivery_'+index).find('input').attr('placeholder',value)
                $('#order_delivery_'+index).find('b').html(value)

            });

            $.each(data.no_required_fields, function(index, value) {


                // console.log(value)

                $('#order_delivery_'+value+' input').rules( "remove" );




            });

            $.each(data.required_fields, function(index, value) {
                console.log($('#'+value))
                //console.log($('#'+value+' input').rules())

                $('#order_delivery_delivery_'+value+' input').rules( "add", { required: true});

            });


        });


    });



    $("#order_delivery_address_form").validate(
        {

            submitHandler: function(form)
            {

                var button=$('#save_order_deivery_address_button');

                if(button.hasClass('wait')){
                    return;
                }

                button.addClass('wait')
                button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')



                var register_data={ }

                $("#order_delivery_address_form input:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){
                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                $("#order_delivery_address_form select:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){


                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                register_data['order_for_collection']=$('#order_for_collection').is(':checked')



                var ajaxData = new FormData();

                ajaxData.append("tipo", 'delivery_address')
                ajaxData.append("data", JSON.stringify(register_data))


                $.ajax({
                    url: "/ar_web_basket.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    }, success: function (data) {

                        $('#sky-form-modal-overlay').fadeOut();
                        $('.sky-form-modal').fadeOut();
                        $('#page-transitions').removeClass('hide')
                        if (data.state == '200') {

                            for (var key in data.metadata.class_html) {

                                console.log('.' + key)

                                console.log($('.' + key).html())
                                // $('.' + key).html('');
                                $('.' + key).html(data.metadata.class_html[key])
                            }


                            if(data.for_collection=='Yes'){
                                $('#delivery_label').addClass('hide')
                                $('#collection_label').removeClass('hide')

                            }else{
                                $('#delivery_label').removeClass('hide')
                                $('#collection_label').addClass('hide')
                            }

                          x
                        } else if (data.state == '400') {
                            swal("{t}Error{/t}!", data.msg, "error")
                        }


                        button.removeClass('wait')
                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                    }, error: function () {
                        button.removeClass('wait')
                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                    }
                });


            },

            // Rules for form validation
            rules:
                {



    {foreach from=$delivery_required_fields item=required_field }
    {$required_field}: { required: true },
    {/foreach}

    {foreach from=$delivery_no_required_fields item=no_required_field }
    {$no_required_field}:{   required: false},
    {/foreach}

    },

    // Messages for form validation
    messages:
    {


        administrativeArea:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        locality:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        dependentLocality:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        postalCode:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        addressLine1:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        addressLine2:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        sortingCode:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        }




    },

    // Do not change code below
    errorPlacement: function(error, element)
    {
        error.insertAfter(element.parent());
    }
    });




</script>
{/if}