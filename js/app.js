/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapoure.
 Copyright (c) 2015, Inikoo
 Version 3.0*/




function change_browser_history_state(request) {

    window.top.history.pushState({}, '', '/' + request)

}



function change_view(_request) {

    var request = "/ar_views.php?tipo=views&request=" + _request + "&old_state=" + JSON.stringify(state)


    $.getJSON(request, function(data) {
        state = data.state;

        if (typeof(data.navigation) != "undefined" && data.navigation !== null) {



            $('#navigation').html(data.navigation);
        }
        if (typeof(data.tabs) != "undefined" && data.tabs !== null) {
            $('#tabs').html(data.tabs);
        }
        if (typeof(data.menu) != "undefined" && data.menu !== null) {
            $('#menu').html(data.menu);


        }

        if (typeof(data.view_position) != "undefined" && data.view_position !== null) {

            $('#view_position').html(data.view_position);
        }
        
        if (typeof(data.object_showcase) != "undefined" && data.object_showcase !== null) {

            $('#object_showcase').html(data.object_showcase);
        }
if (typeof(data.table) != "undefined" && data.table !== null) {

            $('#table').html(data.table);
        }




        if (typeof(data.structure) != "undefined" && data.structure !== null) {
            structure = data.structure
        }



        change_browser_history_state(data.state.request)


    });

}

$(document).ready(function() {



    state = {
        module: '',
        section: '',
        parent: '',
        parent_key: '',
        object: '',
        key: ''
    }
    structure = {}

    change_view($('#_request').val())




})
