<script>


$('#table').on('input propertychange', '.sko_weight', function() {

    var icon= $(this).next('i')

    validation = client_validation('numeric_unsigned', false, $(this).val(), '')

    if (validation.class == 'valid' && $(this).val()!='') {
        icon.addClass('fa-could valid_save button').removeClass('fa-spinner fa-spin error')
    }else{
        icon.addClass('fa-could error').removeClass('fa-spinner fa-spin')
    }


});



$('#table').on('click', '.save_sko_weight', function() {


    var icon= $(this)
    var msg=$(this).closest('tr').find('.sko_weight_msg')

    if(icon.hasClass('error')){
        return;
    }


    var input= $(this).prev('input')

    var request = '/ar_edit.php?tipo=edit_field&object=Part&key='+input.attr('part_sku')+'&field=Part_Package_Weight&value='+input.val()+'&metadata={}'
console.log(request)
    $.getJSON(request, function (data) {


        icon.removeClass('valid_save button')

        if (data.state == 200) {



            msg.html('')

           // $('.parts_with_no_sko_weight').html(data.update_metadata.parts_with_no_sko_weight)

        } else {

        }

    })


})









</script>