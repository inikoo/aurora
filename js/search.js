/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapoure.
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$(document).on('input propertychange', '.smart_search_input input', function () {
    var delay = 200;
    delayed_search($(this), delay)
});


function delayed_search(search_field, timeout) {

    var query = search_field.val()


    key_scope = {
        type: 'search'
    }
    if (query.length > 0) {
        $('#clear_search').removeClass('hide')
    } else {
        $('#clear_search').addClass('hide')

    }

    window.clearTimeout(search_field.data("timeout"));
    search_field.data("timeout", setTimeout(function () {
        search(query)
    }, timeout));
}



function clear_search() {

    $('#search').val('')
    $('#clear_search').addClass('hide')
    $('#results_container_shifted').removeClass('show')
    $("#results .result").remove();

}
