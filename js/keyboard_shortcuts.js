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
            case 'search':
                navigate_search(e, direction);
                break;
            case 'option':
                navigate_option(e, key_scope.field, direction);
                break;
            case 'dropdown_select':
                navigate_dropdown_select(e, key_scope.field, direction);

            default:

        }

    }
}

function navigate_dropdown_select(e, field, direction) {

    switch (direction) {
        case 'up':
            e.preventDefault();
            var element = $('#' + field + '_results .result.selected').prev()
            if (element.attr('id') != undefined) {
                $('#' + field + '_results .result.selected').removeClass('selected');
                element.addClass('selected');
            }
            break;

        case 'down':
            e.preventDefault();
            var element = $('#' + field + '_results .result.selected').next()
            if (element.attr('id') != undefined) {
                $('#' + field + '_results .result.selected').removeClass('selected');
                element.addClass('selected');
            }
            break;

        default:

    }
}


function navigate_search(e, direction) {

    switch (direction) {
        case 'up':
            e.preventDefault();
            var element = $('#results .result.selected').prev()
            if (element.attr('id') != undefined) {
                $('#results .result.selected').removeClass('selected');
                element.addClass('selected');
            }
            break;

        case 'down':
            e.preventDefault();
            var element = $('#results .result.selected').next()
            if (element.attr('id') != undefined) {
                $('#results .result.selected').removeClass('selected');
                element.addClass('selected');
            }
            break;

        default:

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
            case 'search':
                var view = $("#results .result.selected").attr('view')
                if (view) {
                    change_view(view)
                }
                break;

            case 'dropdown_select':

                const  element =$("#" + key_scope.field + "_results .result.selected")

                const field = element.attr('field')
                const value = element.attr('value')
                const formatted_value = element.attr('formatted_value')
                const shortcut=element.data('shortcut')


                if(shortcut=='select_operator') {
                    select_dropdown_handler(operator, element)
                    post_select_dropdown_operator_handler('operator', element)
                }else if(shortcut=='select_packer'){
                    select_dropdown_handler('packer',element);
                    post_select_dropdown_picker_packer_handler('packer',element)
                }else if(shortcut=='select_picker'){
                    select_dropdown_handler('picker',element);
                    post_select_dropdown_picker_packer_handler('picker',element)
                }else if(shortcut=='select_packer_picking_aid'){
                    select_dropdown_handler('packer',element);
                    validate_data_entry_picking_aid();
                }else if(shortcut=='select_picker_picking_aid'){
                    select_dropdown_handler('picker',element);
                    validate_data_entry_picking_aid();
                }else{
                    if (field) {
                        select_dropdown_option(field, value, formatted_value)
                    }
                }


                break;

            case 'option':
            case 'option_multiple_choices':
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
