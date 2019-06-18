/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2017 at 20:56:55 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/


$('body').on('input propertychange', '.new_replacement_item', function (evt) {

    var error;

    if($(this).val()!='' && validate_number($(this).val(),0,$(this).attr('max'))){
        $(this).addClass('error')
        error=true;
    }else{
        $(this).removeClass('error')
        error=false;
    }


    var    feedback_element    = $(this).closest('tr').find('.set_feedback_button').removeClass('hide')

    if( $(this).val()>0 && !error){
        feedback_element.removeClass('hide')
    }else{
        feedback_element.addClass('hide')



    }


    update_new_replacement_totals()


});



$('body').on('dblclick', 'span.new_replacement_ordered_quantity', function () {

    var input = $(this).closest('tr').find('.new_replacement_item')



    var max = parseFloat(input.attr('max'));



    change_item_replacement(max, input)
});

$('body').on('click', 'span.new_replacement_ordered_quantity', function () {

    var input = $(this).closest('tr').find('.new_replacement_item')

    var add_amount = 1
    var current_amount = input.val()
    if (current_amount == '') {
        current_amount = 0;
    }
    current_amount = parseFloat(current_amount)

    var to_add_amount = (current_amount + add_amount)

    var max = parseFloat(input.attr('max'));

    console.log(input.attr('max'))

    console.log(max)

    if (max < to_add_amount) {
        to_add_amount = max
    }


    change_item_replacement(to_add_amount, input)
});




function change_item_replacement(amount, input) {

    input.val(amount)
    var error;
    if(validate_number(input.val(),0,input.attr('max'))){
        input.addClass('error')
        error=true;
    }else{
        input.removeClass('error')
        error=false;
    }


    var    feedback_element    = input.closest('tr').find('.set_feedback_button').removeClass('hide')

    if(input.val()>0 && !error){
        feedback_element.removeClass('hide')
    }else{
        feedback_element.addClass('hide')
    }


    update_new_replacement_totals()

}



function save_replacement(){

    if (!$('.open_create_replacement_dialog_button').hasClass('valid')) {
        return;
    }

    $('.open_create_replacement_dialog_button').removeClass('valid').addClass('fa-spinner fa-spin')


    var transactions = [];


    $('.new_replacement_item').each(function (i, obj) {

        if($(obj).val()!='' && !$(obj).hasClass('error') && !$(obj).hasClass('hided') ) {

            var transaction = {
                type: $(obj).attr('transaction_type'), id: $(obj).attr('transaction_id'), amount: $(obj).val(),
                feedback: $(obj).closest('tr').find('.set_feedback_button').data('feedback')


            }

            transactions.push(transaction);



        }

    });

    var _data =$('#order').data('object')


    request='ar_edit_orders.php?tipo=create_replacement&key='+_data.key+'&transactions='+JSON.stringify(transactions)


    console.log(request)
    var ajaxData = new FormData();



    ajaxData.append("tipo", 'create_replacement')
    ajaxData.append("key", _data.key)
    ajaxData.append("transactions", JSON.stringify(transactions))

    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                change_view('delivery_notes/'+data.store_key+'/'+data.replacement_key);

            } else if (data.state == '400') {
                $('.open_create_replacement_dialog_button').addClass('valid').removeClass('fa-spinner fa-spin')

                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });



}



function update_new_replacement_totals() {



    var number_affected_items=0;
    var number_affected_items_with_feedback=0;

    //var data = $('#order').data('object')



    $('.new_replacement_item').each(function (i, obj) {
        if ($(obj).val() != ''  && !$(obj).hasClass('hide')  && !$(obj).hasClass('error')  ) {

            if($(obj).hasClass('item')){
                number_affected_items++;

                var    feedback_data    = $(obj).closest('tr').find('.set_feedback_button').data('feedback')
                if(    typeof(feedback_data) != "undefined" && feedback_data !== null && typeof(feedback_data) == "object"){

                    number_affected_items_with_feedback++

                }



            }

        }

    });




    if(number_affected_items>0 && number_affected_items==number_affected_items_with_feedback){
        $('.open_create_replacement_dialog_button').addClass('changed valid')
    }else{
        $('.open_create_replacement_dialog_button').removeClass('changed valid')

    }



    $('.affected_items').html(number_affected_items)






}

function close_feedback(){

    $('.feedback_form').addClass('hide')

    $('.close_feedback').removeClass('hide')
    $('.feedback_form textarea').val('')
    $('.feedback_form .scope ').each(function (i, scope_element) {
        $(scope_element).addClass('very_discreet_on_hover')
        $(scope_element).find('i').addClass('fa-square').removeClass('fa-check-square')
    })

    validate_feedback()

}


function feedback_scope_clicked(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-square')){
        $(element).removeClass('very_discreet_on_hover')
        icon.removeClass('fa-square').addClass('fa-check-square')
    }else{
        $(element).addClass('very_discreet_on_hover')
        icon.addClass('fa-square').removeClass('fa-check-square')
    }

    validate_feedback()
}


$(document).on('input propertychange paste', '.feedback_form textarea', function (e) {
    validate_feedback()

});



$(document).on('click', '.set_feedback_button', function (e) {

    if(!$('.feedback_form').hasClass('hide')){
        return;
    }


    var itf=$(this).data('itf');

    $('.feedback_form').data('itf',itf)


    var offset = $(this).offset()

    $('.feedback_form').removeClass('hide').offset({
        'top': offset.top+$(this).height(),'left': offset.left+$(this).width()- $('.feedback_form').width()-7
    })

    var feedback_data=$('#set_feedback_'+itf).data('feedback')




    if(    typeof(feedback_data) != "undefined" && feedback_data !== null && typeof(feedback_data) == "object"){

        $.each( feedback_data.scopes, function( key, value ) {
            $('.feedback_form  .'+ value).trigger( "click" );


        })
        $('.feedback_form textarea').val(feedback_data.feedback)

    }


})


$(document).on('click', '.feedback_form .save_feedback', function (e) {

    if(!$(this).hasClass('valid')){
        return;
    }

    var scopes=[];

    var formatted_feedback='<i class="fa fa-comment-alt-exclamation"></i> '+$('.feedback_form textarea').val()+' <span class="italic">(';

    $('.feedback_form .scope ').each(function (i, scope_element) {
        if($(scope_element).find('i').hasClass('fa-check-square')){
            scopes.push($(scope_element).data('scope'))
            formatted_feedback+=$(scope_element).data('label')+', '
        }

    })
    formatted_feedback=formatted_feedback.replace(/, $/g,")")


    var itf=$('.feedback_form').data('itf');

    var feedback={ original_itf:itf,  scopes:scopes, feedback:$('.feedback_form textarea').val()}

    $('#set_feedback_'+itf).data('feedback',feedback).html($('.feedback_form').data('feedback_set_label')).removeClass('very_discreet_on_hover italic')

    $('#feedback_description_'+itf).html(formatted_feedback).removeClass('hide')

    close_feedback()
    update_new_replacement_totals();

})


$(document).on('input propertychange paste', '.feedback_form textarea', function (e) {
    validate_feedback()

});

function validate_feedback(){

    var scope_ok=false;

    $('.feedback_form .scope ').each(function (i, scope_element) {
        if($(scope_element).find('i').hasClass('fa-check-square')){
            scope_ok=true;
            return false;
        }

    })


    if(scope_ok && $('.feedback_form textarea').val()!='' ){
        $('.feedback_form .save').addClass('valid changed')
        $('.close_feedback').addClass('hide')
    }else{
        $('.feedback_form .save').removeClass('valid changed')
        $('.close_feedback').removeClass('hide')
    }


}
