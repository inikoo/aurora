/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 November 2018 at 12:58:06 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/



function validate_signed_integer(value, max_value) {

    if (!$.isNumeric(value)) {
        return {
            class: 'invalid', type: 'not_integer'
        }
    }

    if (value > max_value) {
        return {
            class: 'invalid',

            type: 'too_big'
        }
    }

    if (value < 0) {
        return {
            class: 'invalid',

            type: 'negative'
        }
    }
    if (Math.floor(value) != value) {


        return {
            class: 'invalid',

            type: 'not_integer'
        }
    }

    return false
}
