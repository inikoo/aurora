/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 December 2015 at 13:39:18 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function toggle_check_record(key) {

    var check_box = $('#check_' + key)


    if (check_box.hasClass('checked')) {
        check_box.removeClass('checked success').addClass('unchecked disabled').html('<i class="fa fa-star-o">')

    } else {
        check_box.removeClass('unchecked disabled').addClass('checked success').html('<i class="fa fa-star">')

    }

}
