/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  25 March 2019 at 17:40:35 GMT+8Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


async function un_dispatch_delivery_note(element) {
    var labels = $(element).data('labels');

    var _ref = await Swal.fire({
        title: labels.title,
        text: labels.text,
        type: 'warning',
        input: 'textarea',
        inputPlaceholder: labels.placeholder,
        confirmButtonText: labels.button_text
    }),
        text = _ref.value;

    if (text) {

        save_object_operation('un_dispatch', element, {note: text})

    }else{
        Swal.fire({
            type: 'error',
            title: labels.no_message,
        })
    }
}