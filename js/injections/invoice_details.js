/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  25 March 2019 at 12:16:20 GMT+8 Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


async function delete_invoice(element) {
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

        save_object_operation('delete', element, {note: text})

    }else{
        Swal.fire({
            type: 'error',
            title: labels.no_message,
        })
    }
}