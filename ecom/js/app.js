/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 18:21:08 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo
 Version 3.0*/


$(document).ready(function() {


    var request=$('#_request').val()

    view = {
        webpage_key: '',
        request:request 
    }
  


    load_marginals()
    load_content($('#_request').val())

    $(document).keydown(function(e) {
        key_press(e)
    });

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

window.addEventListener('popstate', function(event) {
    load_content(event.view.request)

});



function load_content(_request, metadata) {


    if (metadata == undefined) {
        metadata = {};
    }

    var request = "/ar_views.php?tipo=content&request=" + _request + '&metadata=' + JSON.stringify(metadata) + "&old_view=" + JSON.stringify(view)

    $.getJSON(request, function(data) {

        console.log(data);
        view = data.view;




        if (typeof(data.content) != "undefined" && data.content !== null) {
            $('#content').html(data.content);
        }





        change_browser_history_state(view.request)


    });

}

function load_marginals(_request, metadata) {


    if (metadata == undefined) {
        metadata = {};
    }

    var request = "/ar_views.php?tipo=marginals&request=" + _request + '&metadata=' + JSON.stringify(metadata)

    $.getJSON(request, function(data) {

    
        if (typeof(data.header) != "undefined" && data.header !== null) {
            $('#header').html(data.header);
        }

        if (typeof(data.footer) != "undefined" && data.footer !== null) {
            $('#footer').html(data.footer);
        }






    });

}






