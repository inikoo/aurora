/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 November 2016 at 20:14:27 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$(document).on('input propertychange', '#fixed-header-drawer-exp', function () {
    var delay = 200;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_search($(this), delay)
});


function delayed_search(search_field, timeout) {

    var query = search_field.val()

    key_scope = {
        type: 'search'
    }
    if (query.length == 0) {

        $('#search_results').addClass('hide')
        $('#content').removeClass('hide')
        window.clearTimeout(search_field.data("timeout"));

        return;
    }

    window.clearTimeout(search_field.data("timeout"));
    search_field.data("timeout", setTimeout(function () {
        search(query)
    }, timeout));
}


function search(query) {



    var request = '/ar_search.php?tipo=search&query=' + fixedEncodeURIComponent(query) + '&state=' + JSON.stringify(state)
    //   console.log(request)
    $.getJSON(request, function (data) {
        console.log(data)

        $('#search_results').removeClass('hide')
        $('#content').addClass('hide')

        if (data.number_results > 0) {

            $('#no_results_msg').addClass('hide')
            $('#results_msg').removeClass('hide')
        } else {
           $('#no_results_msg').removeClass('hide')
            $('#results_msg').addClass('hide')

        }


        $("#results .result").remove();



        for (var result_key in data.results) {

            var clone = $("#search_result_template").clone()
            clone.prop('id', 'result_' + result_key);
            clone.addClass('result').removeClass('hide')
            clone.attr('view', data.results[result_key].view)


            if (data.show_stores != undefined && data.show_stores && data.results[result_key].store != '') {
                clone.children(".store").html(data.results[result_key].store).removeClass('hide')

            }

            clone.find(".label").html(data.results[result_key].label)
            clone.find(".details").html(data.results[result_key].details)

            $("#results").append(clone)


        }

    })
}


function close_search_results(){
    $('#search_results').addClass('hide')
    $('#content').removeClass('hide')
    $('#fixed-header-drawer-exp').blur().val('')
    $('#search_container').removeClass('ui-focus').removeClass('is-dirty')
    $('#fixed-header-drawer-exp').closest('div').removeClass('ui-focus')

}

function go_to_search_result(view){

    close_search_results()
    change_view(view)

}


function clear_search() {

    $('#search').val('')
    $('#clear_search').addClass('hide')
    $('#results_container_shifted').removeClass('show')
    $("#results .result").remove();

}
