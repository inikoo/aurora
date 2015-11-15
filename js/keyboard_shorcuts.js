/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 November 2015 at 20:19:47 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function key_press(e) {

    switch (e.keyCode) {
    case 37:
        navigate(e, 'left');
        break;
    case 38:
        navigate(e, 'up');
    case 39:
        navigate(e, 'right');
        break
        break;
    case 40:
        navigate(e, 'down');
        break
    case 13:
        enter_hit(e)

        break;
    }


}

function navigate(e, direction) {

    if (key_scope) {

        switch (key_scope.type) {
        case 'option':
            navigate_option(e, key_scope.field, direction);
            break;


        default:

        }

    }
}

function navigate_option(e, field, direction) {

    switch (direction) {
    case 'up':
        e.preventDefault();
        var element = $('#' + field + '_options  li.selected').prev()
        if (element.attr('id') != undefined) {
            select_option(field, element.attr('value'), element.attr('label'))
        }
        break;

    case 'down':
        e.preventDefault();
        var element = $('#' + field + '_options  li.selected').next()
        if (element.attr('id') != undefined) {
            select_option(field, element.attr('value'), element.attr('label'))
        }
        break;

    default:

    }
}


function enter_hit(e) {
    if (key_scope) {

        switch (key_scope.type) {
        case 'option':
        case 'radio_option':
        case 'string':
        case 'telephone':
        case 'email':
        case 'anything':
        case 'int_unsigned':
        case 'smallint_unsigned':
        case 'mediumint_unsigned':
        case 'int':
        case 'smallint':
        case 'mediumint':
        case 'date':
        case 'pin':
        case 'password':
            save_field(key_scope.object, key_scope.key, key_scope.field)
            break;
        case 'pin_with_confirmation':
        case 'password_with_confirmation':
            if ($('#' + key_scope.field + '_confirm').hasClass('hide')) {
                confirm_field(key_scope.field)

            } else {
                save_field(key_scope.object, key_scope.key, key_scope.field)

            }
            break;

        default:

        }

    }

}
