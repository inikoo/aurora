{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 April 2018 at 15:17:42 BST, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    .booking_in_barcode_feedback_block {
        margin-left: 70px
    }
</style>

<div style="border-bottom:1px solid #ccc;padding:20px;position: relative;min-height: 60px">

    <div style="float: left;width: 800px">
        <table>
            <tr class="{if $delivery->get('Supplier Delivery Currency Code')==$account->get('Currency Code')}hide{/if}">
                <td>
                    {t}Exchange{/t} 1{$account->get('Currency Code')}=
                </td>
                <td style="padding-left: 10px">
                    <input id="edit_exchange" value="{math equation="1/x" x=$delivery->get('Supplier Delivery Currency Exchange') format="%.5f"}">{$delivery->get('Supplier Delivery Currency Code')}
                </td>
            </tr>

            <tr style="height: 10px">
                <td colspan="2"></td>

            </tr>
            <tr class="{if $delivery->get('Supplier Delivery Currency Code')==$account->get('Currency Code')}hide{/if}">
                <td>
                    {t}Set extra costs{/t} ({$delivery->get('Supplier Delivery Currency Code')})
                </td>
                <td style="padding-left: 10px">
                    <input class="edit_extra_cost_for_distribution" id="edit_extra_delivery_currency" value=""> <span onclick="toggle_exclude_zero_placed_items(this)" class="{if $number_zero_placed_items==0}hide{/if} small button "><i
                                class="far fa-check-square exclude_zeros"></i> {t}exclude 0 SKOs in{/t}</span> <span onclick="distribute_extra_costs(this)" data-type="extra_amount_input" title="{t}distribute equally each item {/t}"
                                                                                                 class="save margin_left_10"> {t}Distribute{/t} <i class="fas fa-arrows-alt-v"></i> </span>
                </td>
            </tr>
            <tr>
                <td>
                    {t}Set extra costs{/t} ({$account->get('Currency Code')})
                </td>
                <td style="padding-left: 10px">
                    <input class="edit_extra_cost_for_distribution" id="edit_extra_account_currency" value=""> <span onclick="toggle_exclude_zero_placed_items(this)" class="{if $number_zero_placed_items==0}hide{/if} small button ">
                        <i class="far fa-check-square exclude_zeros"></i> {t}exclude 0 SKOs in{/t}</span> <span onclick="distribute_extra_costs(this)" data-type="extra_amount_account_currency"
                                                                                                 title="{t}distribute equally each item {/t}" class="save margin_left_10"> {t}Distribute{/t} <i
                                class="fas fa-arrows-alt-v"></i> </span>

                </td>
            </tr>

        </table>

    </div>

    <span id="save_button" class="save valid changed" style="float:right" onClick="save_costing()"> {t}Finish costing{/t} <i class="fa fa-cloud  " aria-hidden="true"></i></span>


    <div style="clear: both">


    </div>

</div>

<div style="clear: both">


</div>

<script>


    function toggle_exclude_zero_placed_items(element){

        var icon=$(element).find('i')

        if(icon.hasClass('fa-square')){
            icon.removeClass('fa-square').addClass('fa-check-square')
            $(element).removeClass('discreet')
        }else{
            icon.addClass('fa-square').removeClass('fa-check-square')
            $(element).addClass('discreet')

        }
        var input=$(element).closest('td').find('input')
        var save=$(element).closest('td').find('.save')

        if(input.val()!=''  && !save.hasClass('invalid') ){
            save.addClass('valid changed')

        }


    }


    $(document).on('input propertychange,change', '.edit_extra_cost_for_distribution', function (evt) {


        if ($(this).val() == '') {
            value = 0
        } else {
            value = $(this).val()
        }


        if (!validate_number(value, 0)) {
            $(this).removeClass('error')
            $(this).closest('td').find('.save').addClass('valid changed').removeClass('invalid')


        } else {
            $(this).addClass('error')
            $(this).closest('td').find('.save').removeClass('valid').addClass('invalid changed')

            value = 0
        }


    });


    function distribute_extra_costs(element) {

        if (!$(element).hasClass('valid')) {
            return;
        }

       if($(element).closest('td').find('.exclude_zeros').hasClass('fa-check-square')){
           var exclude_zeros=true;

       }else{
           var exclude_zeros=false;

       }



        value = $(element).closest('td').find('input').val()


        if(exclude_zeros){
            var numItems = $('.total_paid_amount:not(.zero)').length
        }else{
            var numItems = $('.total_paid_amount').length
        }


        console.log(numItems)


        if (numItems == 0) {
            return;
        }

        item_value = Math.floor((value / numItems) * 100) / 100


        console.log(value / numItems)

        console.log(item_value)


        var diff = (value - (item_value * numItems)).toFixed(2)


        diff = Math.floor((diff) * 100) / 100

        cents = diff * 100;


        if (cents > numItems) {
            var extra_cents_in_first_row = (cents - numItems) * 0.01
        } else {
            var extra_cents_in_first_row = 0

        }


        console.log(diff)


        // console.log($(element).data('type'))

        $('input.' + $(element).data('type')).each(function (i, obj) {




            if(exclude_zeros && $(obj).closest('tr').find('.total_paid_amount').hasClass('zero')){
                return true;
            }

            var sku = $(obj).data('sku')


            if (cents > 0) {
                _value = parseFloat(parseFloat(item_value) + parseFloat(0.01)).toFixed(2)
                cents = cents - 1
            } else {
                _value = parseFloat(item_value).toFixed(2);
            }

            if (i == 0) {
                _value = parseFloat(_value + extra_cents_in_first_row).toFixed(2);
            }



            $(obj).removeClass('error').val(_value)


            if ($(element).data('type') == 'extra_amount_input') {
                $('#total_paid_' + sku).data('extra_amount', _value);

            } else {
                $('#total_paid_' + sku).data('extra_amount_account_currency', _value);

            }


            update_costing_item_total(sku)


        });


        //  console.log(Math.floor(item_value * 100)/100)


        update_costing_totals();


        $(element).closest('td').find('.save').removeClass('valid changed')


        $('#save_button').addClass('save button changed valid')


    }


    $(document).on('input propertychange,change', '.extra_amount_input', function (evt) {


        if ($(this).val() == '') {
            value = 0
        } else {
            value = $(this).val()
        }
        if (!validate_number(value, 0)) {
            $(this).removeClass('error')
        } else {
            $(this).addClass('error')
            value = 0
        }

        var sku = $(this).data('sku')
        $('#total_paid_' + sku).data('extra_amount', parseFloat(value));
        update_costing_item_total(sku)


        update_costing_totals();
        $('#save_button').addClass('save button changed valid')


    });


    $(document).on('input propertychange,change', '#edit_exchange', function (evt) {


        edit_exchange(this)


    });


    $(document).on('input propertychange,change', '.items_amount', function (evt) {

        if ($(this).val() == '') {
            value = 0
        } else {
            value = $(this).val()
        }


        if (!validate_number(value, 0)) {
            $(this).removeClass('error')


        } else {
            $(this).addClass('error')
            value = 0
        }


        var sku = $(this).data('sku')
        $('#total_paid_' + sku).data('items_amount', parseFloat(value));
        update_costing_item_total(sku)

        update_costing_totals();

        $('#save_button').addClass('save button changed valid')


    });


    $(document).on('input propertychange,change', '.extra_amount_account_currency', function (evt) {

        if ($(this).val() == '') {
            value = 0
        } else {
            value = $(this).val()
        }


        if (!validate_number(value, 0)) {
            $(this).removeClass('error')


        } else {
            $(this).addClass('error')
            value = 0
        }


        var sku = $(this).data('sku')
        $('#total_paid_' + sku).data('extra_amount_account_currency', parseFloat(value));
        update_costing_item_total(sku)

        update_costing_totals();

        $('#save_button').addClass('save button changed valid')


    });


    function edit_exchange(input) {

        if (!validate_number($(input).val(), 0) && parseFloat($(input).val()) != 0) {
            $(input).removeClass('error')
            var value = parseFloat($(input).val())


            $('.total_paid_amount').each(function (i, obj) {

                $(obj).data('exchange', value)

                var sku = $(obj).data('sku')

                update_costing_item_total(sku)


            });

            update_costing_totals();

            $('#save_button').addClass('valid changed')


        } else {


            $(input).addClass('error')
        }


    }


    function update_costing_item_total(sku) {

        element = $('#total_paid_' + sku)


        var sko_in = parseFloat(element.data('sko_in'))
        var items_amount = parseFloat(element.data('items_amount'))
        var extra_amount = parseFloat(element.data('extra_amount'))
        var extra_amount_account_currency = parseFloat(element.data('extra_amount_account_currency'))
        var exchange = parseFloat(element.data('exchange'))


        var paid = (items_amount + extra_amount) / exchange + extra_amount_account_currency

        //console.log(extra_amount)

        //console.log(paid)

        element.html(paid).formatCurrency({
            symbol: '{$currency_symbol}'})


        if (sko_in != 0) {
            $('#sko_cost_' + sku).html(paid / sko_in).formatCurrency({
                symbol: '{$currency_symbol}'})
            $('#sko_cost_' + sku).html($('#sko_cost_' + sku).html() + '/sko')
        } else {
            $('#sko_cost_' + sku).html('')

        }

    }


    function update_costing_totals() {

        var items = 0;
        var extra_cost = 0;
        var total = 0;
        var subtotal_account_currency = 0;
        var extra_cost_account_currency = 0;
        var total_account_currency = 0;


        $('.total_paid_amount').each(function (i, obj) {


            var element = $(obj)

            var sko_in = parseFloat(element.data('sko_in'))
            var items_amount = parseFloat(element.data('items_amount'))
            var extra_amount = parseFloat(element.data('extra_amount'))

            var extra_amount_account_currency = parseFloat(element.data('extra_amount_account_currency'))
            var exchange = parseFloat(element.data('exchange'))


            items += items_amount;
            extra_cost += extra_amount
            total += items_amount + extra_amount
            subtotal_account_currency += (items_amount + extra_amount) / exchange

            extra_cost_account_currency += extra_amount_account_currency

            // console.log(total)


        });

        total_account_currency = subtotal_account_currency + extra_cost_account_currency

        $('.Supplier_Delivery_Items_Amount').html(items).formatCurrency({
            symbol: '{$currency_symbol}'})
        $('.Supplier_Delivery_Extra_Costs_Amount').html(extra_cost).formatCurrency({
            symbol: '{$currency_symbol}'})
        $('.Supplier_Delivery_Total_Amount').html(total).formatCurrency({
            symbol: '{$currency_symbol}'})
        $('.Supplier_Delivery_AC_Subtotal_Amount').html(subtotal_account_currency).formatCurrency({
            symbol: '{$currency_symbol}'})
        $('.Supplier_Delivery_AC_Extra_Costs_Amount').html(extra_cost_account_currency).formatCurrency({
            symbol: '{$currency_symbol}'})
        $('.Supplier_Delivery_AC_Total_Amount').html(total_account_currency).formatCurrency({
            symbol: '{$currency_symbol}'})


    }


    function save_costing() {


        if (!$('#save_button').hasClass('valid')) {
            return;
        }


        $('#save_button').removeClass('valid').find('i').addClass('fa-spin fa-spinner')


        var items_data = {}
        $('.total_paid_amount').each(function (i, obj) {

            var sku = $(obj).data('sku')
            items_data[sku] = [$('#items_amount_' + sku).val(), $('#extra_amount_' + sku).val(), $('#extra_amount_account_currency_' + sku).val(),]


        })

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'set_delivery_costing')
        ajaxData.append("key", {$delivery->id})
        ajaxData.append("items_data", JSON.stringify(items_data))
        ajaxData.append("exchange", $('#edit_exchange').val())


        console.log(items_data)

        $.ajax({
            url: "/ar_edit_stock.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {

                console.log(data)

                if (data.state == '200') {

                    change_view(state.request, {
                        'reload_showcase': 1})

                    //$('#save_button').removeClass('save').find('i').removeClass('fa-spinner fa-spin')
                } else if (data.state == '400') {
                    swal(data.msg);
                }


            }, error: function () {

            }
        });


    }


</script>