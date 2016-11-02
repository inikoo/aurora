/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 18:21:08 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo
 Version 3.0*/


$(document).ready(function () {


    var request = $('#_request').val()

    view = {
        webpage_key: '',
        request: request
    }


    load_marginals()
    load_content($('#_request').val(), {})

    $(document).keydown(function (e) {
        key_press(e)
    });

})

$(document).on('click', 'a', function (e) {
    e.preventDefault();


    change_node($(this).attr('href'))


})


function change_browser_history_state(request) {


    if (request == undefined) {
        return;
    }

    if (request.charAt(0) !== '/') {
        request = '/' + request
    }

    window.top.history.pushState({
        request: request
    }, '', request)

}

window.addEventListener('popstate', function (event) {
    load_content(event.view.request)

});


function change_node(_request) {

    if (metadata == undefined) {
        metadata = {};
    }
    load_content(_request, metadata)
}

function load_content(_request, metadata) {


    var request = "/ar_pviews.php?tipo=content&request=" + _request
    // + '&metadata=' + fixedEncodeURIComponent(JSON.stringify(metadata)) 
    //+ "&old_view=" + fixedEncodeURIComponent(JSON.stringify(view))
    $.getJSON(request, function (data) {

        console.log(data);
        view = data.view;


        if (typeof(data.body_classes) != "undefined" && data.body_classes !== null) {
            $('body').removeClass().addClass(data.body_classes);
        }

        if (typeof(data.content) != "undefined" && data.content !== null) {
            $('#webpage_content').html(data.content);
        }
        if (typeof(data.breadcrumbs) != "undefined" && data.breadcrumbs !== null) {
            $('#breadcrumb').html(data.breadcrumbs);
        }


        change_browser_history_state(view.request)


    });

}

function load_marginals(_request, metadata) {


    if (metadata == undefined) {
        metadata = {};
    }

    var request = "/ar_pviews.php?tipo=marginals&request=" + _request + '&metadata=' + JSON.stringify(metadata)

    $.getJSON(request, function (data) {


        if (typeof(data.header) != "undefined" && data.header !== null) {
            $('#header').html(data.header);
        }

        if (typeof(data.footer) != "undefined" && data.footer !== null) {
            $('#footer').html(data.footer);
        }


    });

}


function key_press() {

}

function fixedEncodeURIComponent(str) {
    return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
}
