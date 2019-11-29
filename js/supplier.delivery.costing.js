/*Author: Raul Perusquia <raul@inikoo.com>
 Refactored:   26 November 2019  10:23::42  +0100, Málaga, Spain
 Copyright (c) 2019, Inikoo
 Version 3.0*/

$(function () {

    const $tab = $('#tab');

    $tab.on('input propertychange,change', '.control_supplier_delivery_costing .edit_extra_cost_for_distribution', function () {

        let value;
        if ($(this).val() === '') {
            value = 0
        } else {
            value = $(this).val()
        }

        if (!validate_number(value, 0)) {
            $(this).removeClass('error');
            $(this).closest('tr').find('.save').addClass('valid changed').removeClass('invalid')
        } else {
            $(this).addClass('error');
            $(this).closest('tr').find('.save').removeClass('valid').addClass('invalid changed')
        }

    });

    $tab.on('click', '.control_supplier_delivery_costing .distribute_extra_costs', function () {


        if (!$(this).hasClass('valid')) {
            return;
        }

        const value_to_distribute = $(this).closest('tr').find('input').val();
        const exclude_zeros = !!$(this).closest('tr').find('.exclude_zeros').hasClass('fa-check-square');
        const type = $(this).data('type');

        if ($(this).data('distribution_type') === 'equal') {
            distribute_by_equally(type, value_to_distribute, exclude_zeros);
        } else if($(this).data('distribution_type') === 'cost')
        {
            distribute_by_cost(type, value_to_distribute, exclude_zeros);
        }

        update_costing_totals();

        $(this).closest('td').find('.save');


        $('.save_button').addClass('save button changed valid');

        function distribute_by_equally(type, value_to_distribute, exclude_zeros) {

            let numItems;

            if (exclude_zeros) {
                numItems = $('.total_paid_amount:not(.zero)').length
            } else {
                numItems = $('.total_paid_amount').length
            }

            if (numItems == 0) {
                return;
            }

            const item_value = Math.floor((value_to_distribute / numItems) * 100) / 100;


            console.log(value_to_distribute)

            let diff = (value_to_distribute - (item_value * numItems)).toFixed(2);


            diff = Math.floor((diff) * 100) / 100;


            let cents = diff * 100;

            let extra_cents_in_first_row;
            if (cents > numItems) {
                extra_cents_in_first_row = (cents - numItems) * 0.01
            } else {
                extra_cents_in_first_row = 0

            }


            $('input.' + type).each(function (i, obj) {

                let _value;

                if (exclude_zeros && $(obj).closest('tr').find('.total_paid_amount').hasClass('zero')) {
                    return true;
                }

                const sku = $(obj).data('sku');


                if (cents > 0) {
                    _value = parseFloat(parseFloat(item_value) + parseFloat(0.01)).toFixed(2);
                    cents = cents - 1
                } else {
                    _value = parseFloat(item_value).toFixed(2);
                }

                if (i == 0) {
                    _value = parseFloat(_value + extra_cents_in_first_row).toFixed(2);
                }


                console.log(_value)

                $(obj).removeClass('error').val(_value);


                if (type == 'extra_amount_input') {
                    $('#total_paid_' + sku).data('extra_amount', _value);

                } else {
                    $('#total_paid_' + sku).data('extra_amount_account_currency', _value);

                }


                update_costing_item_total(sku)


            });


        }


        function distribute_by_cost(type, value_to_distribute, exclude_zeros) {


            let total_amount = 0;

            let items ={ };

            $('input.items_amount').each(function (i, obj) {
                let item_amount = Number($(obj).val());
                items[$(obj).data('sku')] = item_amount

                total_amount += item_amount;

            })


            if(total_amount===0){
                return;
            }

            let value_to_distribute_approx=0;
            $.each( items, function( i, val ) {
                items[i]=Math.round(value_to_distribute*val/total_amount * 100) / 100
                value_to_distribute_approx+=items[i];

            });

            console.log(items)


            let diff = Math.round((value_to_distribute-value_to_distribute_approx)* 100)/100;

            let sku_with_biggest_value=0;
            let biggest_value=0;
            $.each( items, function( i, val ) {
                if(val>biggest_value){
                    sku_with_biggest_value=i;
                    biggest_value=val;
                }
            });

            if(diff<0){
                if(sku_with_biggest_value>0 && biggest_value>(-1*diff)){
                    items[sku_with_biggest_value]=   Math.round((items[sku_with_biggest_value]+diff)* 100)/100;  ;
                }
            }else{
                items[sku_with_biggest_value]=   Math.round((items[sku_with_biggest_value]+diff)* 100)/100;  ;

            }





            $('input.' + type).each(function (i, obj) {

                const sku = $(obj).data('sku');
                const amount= items[sku];

                $(obj).removeClass('error').val(amount);

                if (type == 'extra_amount_input') {
                    $('#total_paid_' + sku).data('extra_amount', amount);

                } else {
                    $('#total_paid_' + sku).data('extra_amount_account_currency', amount);

                }
                update_costing_item_total(sku)


            });


        }




    });





    $tab.on('input propertychange,change', 'table.supplier_delivery_costing_table  .extra_amount_input', function (evt) {


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
        $('.save_button').addClass('save button changed valid')


    });


    $tab.on('input propertychange,change', '.control_supplier_delivery_costing .edit_exchange', function (evt) {


        edit_exchange(this)


    });


    $tab.on('input propertychange,change', 'table.supplier_delivery_costing_table .items_amount', function (evt) {

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

        $('.save_button').addClass('save button changed valid')


    });


    $tab.on('input propertychange,change', 'table.supplier_delivery_costing_table .extra_amount_account_currency', function (evt) {


        let value;
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

        $('.save_button').addClass('save button changed valid')


    });


    $tab.on('click', '.control_supplier_delivery_costing_container .save_button', function () {

        if (!$(this).hasClass('valid')) {
            return;
        }


        $(this).removeClass('valid').find('i').addClass('fa-spin fa-spinner')


        var items_data = {

        }
        $('.total_paid_amount').each(function (i, obj) {

            var sku = $(obj).data('sku')
            items_data[sku] = [$('#items_amount_' + sku).val(), $('#extra_amount_' + sku).val(), $('#extra_amount_account_currency_' + sku).val(),]


        })

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'set_delivery_costing')
        ajaxData.append("key", $('.control_supplier_delivery_costing').data('delivery_key'))
        ajaxData.append("items_data", JSON.stringify(items_data))
        ajaxData.append("exchange", $('.edit_exchange').val())



        $.ajax({
            url: "/ar_edit_stock.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {

                console.log(data)

                if (data.state == '200') {

                    change_view(state.request, {
                        'reload_showcase': 1})

                } else if (data.state == '400') {
                    swal(data.msg);
                }


            }, error: function () {

            }
        });





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

            $('.save_button').addClass('valid changed')


        } else {


            $(input).addClass('error')
        }


    }


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

    function update_costing_item_total(sku) {

        const element = $('#total_paid_' + sku);

        const sko_in = parseFloat(element.data('sko_in'));
        const items_amount = parseFloat(element.data('items_amount'));
        const extra_amount = parseFloat(element.data('extra_amount'));
        const extra_amount_account_currency = parseFloat(element.data('extra_amount_account_currency'));
        const exchange = parseFloat(element.data('exchange'));


        const paid = (items_amount + extra_amount) / exchange + extra_amount_account_currency;

        const currency_symbol = $('.control_supplier_delivery_costing').data('currency_symbol');


        element.html(paid).formatCurrency({
            symbol: currency_symbol
        });


        if (sko_in != 0) {
            $('#sko_cost_' + sku).html(paid / sko_in).formatCurrency({
                symbol: currency_symbol
            });
            $('#sko_cost_' + sku).html($('#sko_cost_' + sku).html() + '/sko')
        } else {
            $('#sko_cost_' + sku).html('')

        }

    }

    function update_costing_totals() {

        const currency_symbol = $('.control_supplier_delivery_costing').data('currency_symbol');


        let items = 0;
        let extra_cost = 0;
        let total = 0;
        let subtotal_account_currency = 0;
        let extra_cost_account_currency = 0;


        $('.total_paid_amount').each(function (i, obj) {


            var element = $(obj);

            var items_amount = parseFloat(element.data('items_amount'));
            var extra_amount = parseFloat(element.data('extra_amount'));

            var extra_amount_account_currency = parseFloat(element.data('extra_amount_account_currency'));
            var exchange = parseFloat(element.data('exchange'));


            items += items_amount;
            extra_cost += extra_amount;
            total += items_amount + extra_amount;
            subtotal_account_currency += (items_amount + extra_amount) / exchange;

            extra_cost_account_currency += extra_amount_account_currency

            // console.log(total)


        });

        total_account_currency = subtotal_account_currency + extra_cost_account_currency;

        $('.Supplier_Delivery_Items_Amount').html(items).formatCurrency({
            symbol: currency_symbol
        });
        $('.Supplier_Delivery_Extra_Costs_Amount').html(extra_cost).formatCurrency({
            symbol: currency_symbol
        });
        $('.Supplier_Delivery_Total_Amount').html(total).formatCurrency({
            symbol: currency_symbol
        });
        $('.Supplier_Delivery_AC_Subtotal_Amount').html(subtotal_account_currency).formatCurrency({
            symbol: currency_symbol
        });
        $('.Supplier_Delivery_AC_Extra_Costs_Amount').html(extra_cost_account_currency).formatCurrency({
            symbol: currency_symbol
        });
        $('.Supplier_Delivery_AC_Total_Amount').html(total_account_currency).formatCurrency({
            symbol: currency_symbol
        })


    }


});
