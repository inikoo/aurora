/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 March 2019 at 11:22:06 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2019, Inikoo
 Version 3.0*/


$(document).on('click', '.open_sticky_note', function () {

    $(this).addClass('hide');

    let scope;

    if ($(this).hasClass('order_sticky_note')) {
        scope = 'order_sticky_note'
    } else if ($(this).hasClass('delivery_note_sticky_note')) {
        scope = 'delivery_note_sticky_note'
    } else if ($(this).hasClass('customer_sticky_note')) {
        scope = 'customer_sticky_note'
    } else if ($(this).hasClass('order_customer_sticky_note')) {
        scope = 'order_customer_sticky_note'
    } else {
        scope = 'object_sticky_note'
    }


    $('.sticky_notes').find('.' + scope).removeClass('hide')

});


$(document).on('click', '.delete_sticky_note', function () {

    let scope = $(this).closest('.sticky_note_container').data('scope');
    $(this).closest('.sticky_note_container').addClass('hide').find('.sticky_note').html('');


    $('#au_header').find('.' + scope).removeClass('hide');
    save_sticky_note(scope)

});


$(document).on('click', '.copy_to_delivery_note_sticky_note', function () {


    let note = $(this).closest('.sticky_note_container').find('.sticky_note').html();


    let delivery_note_div = $(this).closest('.sticky_notes').find('.delivery_note_sticky_note .sticky_note');

    let delivery_note = delivery_note_div.html();

    $('#au_header').find('.delivery_note_sticky_note').addClass('hide');
    $('.sticky_notes').find('.delivery_note_sticky_note').removeClass('hide');


    if (delivery_note === '') {
        delivery_note_div.html(note)

    } else {
        delivery_note_div.html(delivery_note_div.html() + '<br>' + note)

    }

    save_sticky_note('delivery_note_sticky_note')
});


function save_sticky_note(scope) {


    let element = $('.sticky_notes .' + scope);


    let object = element.data('object');
    let key = element.data('key');
    let field = element.data('field');
    let value = element.find('.sticky_note').html();

    //   let request = '/ar_edit.php?tipo=edit_field&object='+object+'&key='+key+'&field='+field+'&value='+value+'&metadata={}';
    // console.log(request)

    let form_data = new FormData();

    form_data.append("tipo", 'edit_field');
    form_data.append("field", field);
    form_data.append("object", object);
    form_data.append("key", key);
    form_data.append("value", value);
    let request = $.ajax({

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    });


    request.done(function (data) {

        if (data.state === 200) {

            $(element).find('.save').removeClass('fa-cloud').addClass('fa-check')

        } else {
            sweetAlert(data.msg);

        }

    });


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus);

        console.log(jqXHR.responseText)


    });

}

