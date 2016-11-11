/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2016 at 18:27:03 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/

var key_scope = false;

$(document).ready(function () {


    state = {
        module: '',
        section: '',
        parent: '',
        parent_key: '',
        object: '',
        key: ''
    }
    structure = {}

    console.log()

    get_menu()


    console.log( $('#_request').val())


    change_view($('#_request').val())

    $(document).keydown(function (e) {
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

    console.log(request)

    window.top.history.pushState({
        request: request
    }, '', request)

}

window.addEventListener('popstate', function (event) {
    change_view(event.state.request)

});



function get_menu(metadata) {


    if (metadata == undefined) {
        metadata = {};
    }


    var request = "/ar_mobile_views.php?tipo=menu&metadata=" + JSON.stringify(metadata) + "&state=" + JSON.stringify(state)

    $.getJSON(request, function (data) {
        if (typeof(data.menu) != "undefined" && data.menu !== null) {
            $('#menu').html(data.menu);
        }




    });

}


function change_view(_request, metadata) {



    if (metadata == undefined) {
        metadata = {};
    }


    var request = "/ar_mobile_views.php?tipo=views&request=" + _request + '&metadata=' + JSON.stringify(metadata) + "&old_state=" + JSON.stringify(state)


    if (metadata.tab != undefined) {
        request = request + '&tab=' + metadata.tab;
    } else if (metadata.subtab != undefined) {
        request = request + '&subtab=' + metadata.subtab;
    }


    $.getJSON(request, function (data) {

      //  console.log(data.content);
        state = data.state;



        if (typeof(data.content) != "undefined" && data.content !== null) {
            $('#content').html(data.content);
        }

        if (typeof(data.title) != "undefined" && data.title !== null) {
            $('#title').html(data.title);
        }


     //   if (typeof(data.crumbs) != "undefined" && data.crumbs !== null) {
     //       $('#crumbs').html(data.crumbs);
     //   }




        change_browser_history_state(data.state.request)


    });

}


function logout() {
    window.location.href = "/logout.php";
}

function decodeEntities(a) {
    return a
}


function htmlEncode(value) {
    return $('<div/>').text(value).html();
}

var isAdvancedUpload = function () {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

ArraySort = function (array, sortFunc) {
    var tmp = [];
    var aSorted = [];
    var oSorted = {};

    for (var k in array) {
        if (array.hasOwnProperty(k)) tmp.push({
            key: k,
            value: array[k]
        });
    }

    tmp.sort(function (o1, o2) {


        return sortFunc(o1.value, o2.value);
    });

    if (Object.prototype.toString.call(array) === '[object Array]') {
        $.each(tmp, function (index, value) {
            aSorted.push(value.value);
        });
        return aSorted;
    }

    if (Object.prototype.toString.call(array) === '[object Object]') {
        $.each(tmp, function (index, value) {
            oSorted[value.key] = value.value;
        });
        return oSorted;
    }
};


function desktop_view(){

    var request = "/ar_mobile_views.php?tipo=desktop_view"




    $.getJSON(request, function (data) {
        console.log(data)
       // location.reload();


    });


}