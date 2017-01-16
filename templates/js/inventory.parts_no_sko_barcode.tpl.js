<script>


$('#table').on('input propertychange', '.sko_barcode', function() {


    var icon= $(this).next('i')
    var msg_span=$(this).closest('td').find('.sko_barcode_msg')
   icon.removeClass('fa-could').addClass('fa-spinner fa-spin')

    var request = '/ar_validation.php?tipo=check_for_duplicates&parent=account&parent_key=1&object=Part&key=0&field=Part SKO Barcode&value=' + $(this).val()

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



$('#table').on('click', '.save_sko_barcode', function() {


    var icon= $(this)
    var input= $(this).prev('input')

    var request = '/ar_edit.php?tipo=edit_field&object=Part&key='+input.attr('part_sku')+'&field=Part_SKO_Barcode&value='+input.val()+'&metadata={}'
console.log(request)
    $.getJSON(request, function (data) {


        icon.removeClass('valid_save button')

        if (data.state == 200) {


            $('.parts_with_no_sko_barcode').html(data.update_metadata.parts_with_no_sko_barcode)

        } else {

        }

    })


});









</script>