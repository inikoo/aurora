/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapoure.
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function isMobile() {
    try{ document.createEvent("TouchEvent"); return true; }
    catch(e){ return false; }
}



var key_scope = false;
var old_state_request = '';







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

    console.log(old_state_request + ' _> ' + request)

    if (old_state_request != request) {

        window.top.history.pushState({
            request: request
        }, '', request)

        old_state_request = request
    }
}

window.addEventListener('popstate', function (event) {
    change_view(event.state.request)

});

function change_tab(tab, metadata) {
    $('#maintabs .tab').removeClass('selected')
    $('#tab_' + tab.replace(/(:|\.|\[|\])/g, "\$1")).addClass('selected')
    change_view(state.request + '&tab=' + tab, metadata)
}


function change_subtab(subtab) {
    $('#maintabs .subtab').removeClass('selected')
    $('#subtab_' + subtab.replace(/(:|\.|\[|\])/g, "\$1")).addClass('selected')
    change_view(state.request + '&subtab=' + subtab)
}


function get_widget_details(element, widget, metadata) {

    if (metadata == undefined) {
        metadata = {};
    }

    $('.widget').css('opacity', .4)
    $(element).css('opacity', 1)

    var request = "/ar_views.php?tipo=widget_details&widget=" + widget + '&metadata=' + JSON.stringify(metadata)

    console.log(request)

    $.getJSON(request, function (data) {


        $('#widget_details').html(data.widget_details).removeClass('hide');

    });

}

function change_view(_request, metadata) {

    if (metadata == undefined) {
        metadata = {};
    }



    var request = "/ar_views.php?tipo=views&request=" + _request + '&metadata=' + JSON.stringify(metadata) + "&old_state=" + JSON.stringify(state)

    if (metadata.tab != undefined) {
        request = request + '&tab=' + metadata.tab;
    } else if (metadata.subtab != undefined) {
        request = request + '&subtab=' + metadata.subtab;
    }


    $.getJSON(request, function (data) {

        //console.log(data);
        state = data.state;

        //console.log(data.state)
        if (typeof(data.navigation) != "undefined" && data.navigation !== null && data.navigation != '') {
            // $('#navigation').removeClass('hide')
            $('#navigation').html(data.navigation);
        } else {
            // $('#navigation').addClass('hide')
        }

        if (typeof(data.tabs) != "undefined" && data.tabs !== null) {
            $('#tabs').html(data.tabs);
        }

        if (typeof(data.menu) != "undefined" && data.menu !== null) {

            $('#menu').html(data.menu);


        }

        if (typeof(data.logout_label) != "undefined" && data.logout_label !== null) {
            $('#logout_label').html(data.logout_label);


        }


        if (typeof(data.view_position) != "undefined" && data.view_position !== null) {

            $('#view_position').html(data.view_position);
        }


        if (typeof(data.object_showcase) != "undefined" && data.object_showcase !== null) {


            if (data.object_showcase == '_') {
                $('#object_showcase').addClass('hide').html('')
            } else {

                $('#object_showcase').removeClass('hide')
                $('#object_showcase').html(data.object_showcase);
            }
        } else {
            //  $('#object_showcase').addClass('hide')
        }

        if (typeof(data.tab) != "undefined" && data.tab !== null) {

            $('#tab').html(data.tab);
        }


        if (typeof(data.structure) != "undefined" && data.structure !== null) {
            console.log(data.structure)

            structure = data.structure
        }


        if (old_state_request == '') {
            old_state_request = data.state.request
        }

        change_browser_history_state(data.state.request)
        help()

    });

}


function logout() {
    window.location.href = "/logout.php";
}

function decodeEntities(a) {
    return a
}
/*
 var decodeEntities = (function() {


 var element = document.createElement('div');

 function decodeHTMLEntities (str) {
 if(str && typeof str === 'string') {
 str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
 str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
 element.innerHTML = str;
 str = element.textContent;
 element.textContent = '';
 }

 return str;
 }

 return decodeHTMLEntities;
 })();
 */

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

function fixedEncodeURIComponent(str) {
    return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
}

jQuery.fn.putCursorAtEnd = function() {

    return this.each(function() {

        // Cache references
        var $el = $(this),
            el = this;

        // Only focus if input isn't already
        if (!$el.is(":focus")) {
            $el.focus();
        }

        // If this function exists... (IE 9+)
        if (el.setSelectionRange) {

            // Double the length because Opera is inconsistent about whether a carriage return is one character or two.
            var len = $el.val().length * 2;

            // Timeout seems to be required for Blink
            setTimeout(function() {
                el.setSelectionRange(len, len);
            }, 1);

        } else {

            // As a fallback, replace the contents with itself
            // Doesn't work in Chrome, but Chrome supports setSelectionRange
            $el.val($el.val());

        }

        // Scroll to the bottom, in case we're in a tall textarea
        // (Necessary for Firefox and Chrome)
        this.scrollTop = 999999;

    });

};