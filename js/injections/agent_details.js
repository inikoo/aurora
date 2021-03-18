/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  28 April 2016 at 11:34:07 GMT+8, Lovina, Bali, Indonesia
 Copyright (c) 2015, Inikoo
 Version 3.0*/




function post_create_action(data) {

    var clone = $('#' + data.clone_from + '_display').clone()
    clone.prop('id', data.field + '_display').removeClass('hide');

    if (data.clone_from == 'Agent_Other_Email') {
        value = clone.find('.Agent_Other_Email_mailto').prop('id', data.field + '_mailto').html('<a href="mailto:' + data.value + '" >' + data.value + '</a>')

    } else if (data.clone_from == 'Agent_Other_Telephone') {
        clone.find('span').html(data.formatted_value)
    }


    $('#' + data.clone_from + '_display').before(clone)

}

