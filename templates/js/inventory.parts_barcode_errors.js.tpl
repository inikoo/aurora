{*
    <!--
     About:
     Author: Raul Perusquia <raul@inikoo.com>
 Created:17 October 2017 at 17:39:36 GMT+8, Kuala Lumpur, Malaysia
     Copyright (c) 2017, Inikoo

     Version 3
    -->
*}


<script>


    $('#table').on('input propertychange', '.barcode_number', function () {

        var validation_messages = JSON.parse('{$validation_messages}');

        console.log(validation_messages)

        var icon = $(this).next('i')
        var msg_span = $(this).closest('td').find('.barcode_number_msg')
        icon.removeClass('fa-could').addClass('fa-spinner fa-spin')

        var validation = validate_ean_barcode($(this).val())

        if (validation) {
            icon.addClass('fa-could error').removeClass('fa-spinner fa-spin')
            msg_span.html(validation_messages[validation.type]);

            return;
        } else {
            msg_span.html('');
            icon.addClass('fa-could valid_save button').removeClass('fa-spinner fa-spin error')
        }


        var request = '/ar_validation.php?tipo=check_for_duplicates&parent=account&parent_key=1&object=Part&key=0&field=Part Barcode Number&value=' + $(this).val()

        $.getJSON(request, function (data) {


            if (data.state == 200) {
                if (data.validation == 'valid') {
                    msg_span.html('');
                    icon.addClass('fa-could valid_save button').removeClass('fa-spinner fa-spin error')
                } else {
                    icon.addClass('fa-could error').removeClass('fa-spinner fa-spin')
                    msg_span.html(data.msg);
                }

            }

        })


    });


    $('#table').on('click', '.save_barcode_number', function () {
        var icon = $(this)
        if(icon.hasClass('error')){
            return;
        }



        var input = $(this).prev('input')

        var request = '/ar_edit.php?tipo=edit_field&object=Part&key=' + input.attr('part_sku') + '&field=Part_Barcode&value=' + input.val() + '&metadata={}'
        console.log(request)
        $.getJSON(request, function (data) {


            icon.removeClass('valid_save button')

            console.log(data)

            if (data.state == 200) {

                icon.closest('tr').find('.barcode_number_error').html('')

                $('.parts_with_barcode_error').html(data.update_metadata.parts_with_barcode_errors)

            } else {

            }

        })


    });


</script>