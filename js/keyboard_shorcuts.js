/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 November 2015 at 20:19:47 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function key_press(e) {

    switch (e.keyCode) {
    case 37:
        navigate('left');
        break;
    case 38:
        navigate('up');
    case 39:
        navigate('right');
        break
        break;
    case 40:
        navigate('down');
        break
    case 13:
        enter_hit()

        break;
    }


}

function navigate(direction) {

    if (key_scope) {

        switch (key_scope.type) {
        case 'option':
            navigate_option(key_scope.field, direction);
            break;


        default:

        }

    }
}

function navigate_option(field, direction) {

    switch (direction) {
    case 'up':
        var element = $('#' + field + '_options  li.selected').prev()
        if (element.attr('id') != undefined) {
            select_option(field, element.attr('value'), element.attr('label'))
        }
        break;

    case 'down':
        var element = $('#' + field + '_options  li.selected').next()

        if (element.attr('id') != undefined) {
            select_option(field, element.attr('value'), element.attr('label'))
        }
        break;

    default:

    }
}


function enter_hit() {
    if (key_scope) {

        switch (key_scope.type) {
        case 'option':
        case 'string':
        case 'anything':
        case 'int_unsigned':
        case 'smallint_unsigned':
        case 'mediumint_unsigned':
        case 'int':
        case 'smallint':
        case 'mediumint':

            save_field(key_scope.object, key_scope.key, key_scope.field)
            break;


        default:

        }

    }

}
