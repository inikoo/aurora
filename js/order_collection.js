/*Author: Raul Perusquia <raul@inikoo.com>
 Refactored:  25 November 2019  22:19::34  +0100 Mijas Costa, Spain
 Copyright (c) 2018, Inikoo
 Version 3.0*/


$(document).on('click', '.order_delivery_directory_item', function () {

    const delivery_options =$('.delivery_options');
    const icon=$(this).find('.radio_icon');

    if(icon.hasClass('fa-scrubber')){
        return;
    }

    if(delivery_options.hasClass('wait')){
        return;
    }

    delivery_options.addClass('wait');

    icon.addClass('fa-spinner fa-spin').removeClass('fa-circle');

    const object_data = $('#object_showcase div.order').data("object");


    const order_key = object_data.key;

    const form_data = new FormData();
    const type=$(this).data('type');

    if(type==='collection'){
        form_data.append("tipo", 'set_order_for_collection');
        form_data.append("order_key", order_key)
    }else{
        form_data.append("tipo", 'use_delivery_address_form_directory');
        form_data.append("order_key", order_key);
        form_data.append("other_delivery_address_key", $(this).data('other_delivery_address_key'));
        form_data.append("type", $(this).data('type'))

    }


    const request = $.ajax({

        url: "/ar_edit_order_collection.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    });


    request.done(function (data) {


        delivery_options.removeClass('wait');


        if (data.state == 200) {


            delivery_options.find('.radio_icon').removeClass('fa-scrubber').addClass('fa-circle');
            icon.addClass('fa-scrubber').removeClass('fa-spinner fa-spin');



            $('.Total_Amount').attr('amount', data.metadata.to_pay);
            $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay);



            $('#Shipping_Net_Amount_input').val(data.metadata.shipping).attr('ovalue',data.metadata.shipping);
            $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue',data.metadata.charges);

            if (data.other_fields) {
                for (var key in data.other_fields) {
                    update_field(data.other_fields[key])
                }
            }


            if (data.metadata.to_pay == 0) {
                $('.Order_Payments_Amount').addClass('hide');
                $('.Order_To_Pay_Amount').addClass('hide')

            } else {
                $('.Order_Payments_Amount').removeClass('hide');
                $('.Order_To_Pay_Amount').removeClass('hide')

            }

            if (data.metadata.to_pay != 0 || data.metadata.payments == 0) {
                $('.Order_Paid').addClass('hide')
            } else {
                $('.Order_Paid').removeClass('hide')
            }

            if (data.metadata.to_pay <= 0) {
                $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
            } else {
                $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
            }


            if (data.metadata.to_pay == 0) {
                $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

            } else {
                $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

            }


            $('#payment_nodes').html(data.metadata.payments_xhtml);


            for (let key in data.metadata.class_html) {
                $('.' + key).html(data.metadata.class_html[key])
            }



            for (let key in data.metadata.hide) {
                $('.' + data.metadata.hide[key]).addClass('hide')
            }

            for (let key in data.metadata.show) {
                $('.' + data.metadata.show[key]).removeClass('hide')
            }
            for (let key in data.metadata.add_class) {

                $('.' + key).addClass( data.metadata.add_class[key])
            }
            for (let key in data.metadata.remove_class) {
                $('.' + key).removeClass( data.metadata.remove_class[key])
            }


            if(type=='delivery'){

                $('#Order_Delivery_Address_recipient').find('input.address_input_field').val(data.Order_Delivery_Address_recipient);
                $('#Order_Delivery_Address_organization').find('input.address_input_field').val(data.Order_Delivery_Address_organization);
                $('#Order_Delivery_Address_addressLine1').find('input.address_input_field').val(data.Order_Delivery_Address_addressLine1);
                $('#Order_Delivery_Address_addressLine2').find('input.address_input_field').val(data.Order_Delivery_Address_addressLine2);
                $('#Order_Delivery_Address_postalCode').find('input.address_input_field').val(data.Order_Delivery_Address_postalCode);
                $('#Order_Delivery_Address_sortingCode').find('input.address_input_field').val(data.Order_Delivery_Address_sortingCode);
                $('#Order_Delivery_Address_dependentLocality').find('input.address_input_field').val(data.Order_Delivery_Address_dependentLocality);

                $('#Order_Delivery_Address_locality').find('input.address_input_field').val(data.Order_Delivery_Address_locality);
                $('#Order_Delivery_Address_administrativeArea').find('input.address_input_field').val(data.Order_Delivery_Address_administrativeArea);
                $('#Order_Delivery_Address_administrativeArea').find('input.address_input_field').val(data.Order_Delivery_Address_country);
                on_changed_address_value("Order_Delivery_Address", 'Order_Delivery_Address_country', data.Order_Delivery_Address_country.toLowerCase())

            }



        } else if (data.state == 400) {

            icon.addClass('fa-circle').removeClass('fa-spinner fa-spin');

            swal($('#_labels').data('labels').error, data.msg, "error")
        }

    });


    request.fail(function () {

        delivery_options.removeClass('wait');
        icon.addClass('fa-circle').removeClass('fa-spinner fa-spin');

    });



});
