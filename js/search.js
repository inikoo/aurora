/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapoure.
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$(document).on('input propertychange', '#search', function() {
    var delay = 200;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
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
    search_field.data("timeout", setTimeout(function() {
        search(query)
    }, timeout));
}


function search(query) {



    var request = '/ar_search.php?tipo=search&query=' + fixedEncodeURIComponent(query) + '&state=' + JSON.stringify(state)
    //   console.log(request)
    $.getJSON(request, function(data) {
        console.log(data)

        if (data.number_results > 0) {
            $('#results_container_shifted').removeClass('hide')
        } else {
            $('#results_container_shifted').addClass('hide')

        }


        $("#results .result").remove();

        var first = true;

        for (var result_key in data.results) {

            var clone = $("#search_result_template").clone()
            clone.prop('id', 'result_' + result_key);
            clone.addClass('result').removeClass('hide')
            clone.attr('view', data.results[result_key].view)
            if (first) {
                clone.addClass('selected')
                first = false
            }

            clone.children(".label").html(data.results[result_key].label)
            clone.children(".details").html(data.results[result_key].details)

            $("#results").append(clone)


        }

    })
}


function clear_search() {

    $('#search').val('')
    $('#clear_search').addClass('hide')
    $('#results_container_shifted').removeClass('show')
    $("#results .result").remove();

}
