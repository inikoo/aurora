$(function () {
    $('#tab').on('input propertychange', '#table .sko_barcode', function () {


        let icon = $(this).next('i');
        let msg_span = $(this).closest('td').find('.sko_barcode_msg');
        icon.removeClass('fa-could').addClass('fa-spinner fa-spin');

        let request = '/ar_validation.php?tipo=check_for_duplicates&parent=account&parent_key=1&object=Part&key=0&field=Part SKO Barcode&value=' + $(this).val();

        $.getJSON(request, function (data) {


            if (data.state === 200) {
                if (data['validation'] === 'valid') {
                    msg_span.html('');
                    icon.addClass('fa-could valid_save button').removeClass('fa-spinner fa-spin error')
                } else {
                    icon.addClass('fa-could error').removeClass('fa-spinner fa-spin');
                    msg_span.html(data.msg);
                }

            }

        })


    });


    $('#tab').on('click', '#table .save_sko_barcode', function () {


        let icon = $(this);

        if (icon.hasClass('error')) {
            return;
        }


        let input = $(this).prev('input');

        let request = '/ar_edit.php?tipo=edit_field&object=Part&key=' + input.attr('part_sku') + '&field=Part_SKO_Barcode&value=' + input.val() + '&metadata={}';
        $.getJSON(request, function (data) {


            icon.removeClass('valid_save button');

            if (data.state === 200) {


                $('.parts_with_no_sko_barcode').html(data['update_metadata']['parts_with_no_sko_barcode'])

            } else {

            }

        })


    });

});

