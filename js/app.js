/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapoure.
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function isMobile() {
    try {
        document.createEvent("TouchEvent");
        return true;
    } catch (e) {
        return false;
    }
}


var key_scope = false;
var old_state_request = '';
var websocket_connected = false;
var websocket_connected_connecting = false;


$(function () {

    state = {
        module: '', section: '', parent: '', parent_key: '', object: '', key: ''
    }
    structure = {}


    change_view($('#_request').val())


    $(document).on("keydown", function (e) {
        key_press(e)
    })


    connect_websocket();


    setInterval(function () {
        if (!websocket_connected_connecting && !websocket_connected) {
            connect_websocket();
        }


    }, 1000);

    setInterval(function () {

        if (websocket_connected) {
            ws_connection.publish('ping', 'hi')
        }

    }, 60000);


})


function change_browser_history_state(request) {


    if (request == undefined) {
        return;
    }

    if (request.charAt(0) !== '/') {
        request = '/' + request
    }


    if (old_state_request != request) {

       // console.log(old_state_request + ' -> ' + request)


        if (!$('#is_devel').val()) {

            const _tmp = $('body');
            ga('set', 'contentGroup1', state.module.replace("_", " ").capitalize());
            ga('set', 'contentGroup2', state.section.replace("_", " ").capitalize());
            ga('set', 'contentGroup3', _tmp.data('user_handle').capitalize() + ' ' + _tmp.data('account_code'));


            ga('set', 'page', request);
            ga('send', 'pageview');


        }

        //console.log(request)
        window.top.history.pushState({request: request}, '', request)


        old_state_request = request
    }
}

window.addEventListener('popstate', function (event) {

    // console.log(event)

    if (event.state == null) {
        console.log('null state!!!!!!')
    } else {
        change_view(event.state.request)
    }


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

    //console.log(request)

    $.getJSON(request, function (data) {


        $('#widget_details').html(data.widget_details).removeClass('hide');

    });

}

function change_menu_view(module) {


    switch (module) {
        case '_dashboard':
            change_view('/dashboard')
            break;
        case 'customers':
            if (state.current_store && jQuery.inArray(state.current_store, state.stores) !== -1) {
                change_view('customers/' + state.current_store + '/dashboard')
            } else {
                change_view('customers/all')
            }
            break;
        case 'orders':
            if (state.current_store) {
                change_view('orders/' + state.current_store + '/dashboard')
            } else {
                change_view('orders/all/by_store')
            }
            break;

        case 'delivery_notes':

            change_view('delivery_notes/all/')
            break;

        case 'products':
            if (state.current_store) {
                change_view('store/' + state.current_store)
            } else {
                change_view('stores')
            }
            break;
        case 'mailroom':
            if (state.current_store) {
                change_view('mailroom/' + state.current_store)
            } else {
                change_view('mailroom/all')
            }
            break;
        case 'offers':
            if (state.current_store) {
                change_view('offers/' + state.current_store+'/categories')
            } else {
                change_view('offers/by_store')
            }
            break;
        case 'websites':
            if (state.current_website) {
                change_view('website/' + state.current_website)
            } else {
                change_view('websites')
            }
            break;
        case 'warehouses':
            if (state.current_warehouse) {
                change_view('warehouse/' + state.current_warehouse + '/dashboard')
            } else {
                change_view('warehouses')
            }
            break;
        case 'accounting':
            change_view('invoices/per_store')
            break;
        case 'inventory':
            change_view('inventory/dashboard')
            break;
        case 'suppliers':
            change_view('suppliers/dashboard')
            break;
        case 'production':
            if (state.current_production) {
                change_view('production/' + state.current_production)
            } else {
                change_view('production/all')
            }
            break;

        case 'hr':
        case 'reports':
        case 'profile':
        case 'account':
        case 'users':
        case 'agent_parts':
            // console.log(module)
            change_view(module)
            break;
        case 'agent_client_orders':
            change_view('orders')
            break;
        case 'agent_client_deliveries':
            change_view('agent_deliveries')
            break;
        case 'agent_suppliers':
            change_view('suppliers')
            break;
        case 'agent_profile':
            change_view('profile')
            break;

    }
}

function change_view_if_has_link_class(element, _request, metadata) {

    if ($(element).hasClass('link')) {
        change_view(_request, metadata)
    }

}


function change_view(_request, metadata) {

    $.urlParam = function (name, str) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(str);
        if (results == null) {
            return null;
        } else {
            return results[1] || 0;
        }
    }

    var tmp = '?__R__=' + _request

    $('#tabs').removeClass('hide')


    _request = $.urlParam('__R__', tmp)
    var tab = $.urlParam('tab', tmp)
    var subtab = $.urlParam('subtab', tmp)

    if (metadata == undefined || !metadata) {
        metadata = {};
    }


    var request_data = {
        tipo: 'views', request: _request, metadata: JSON.stringify(metadata), old_state: JSON.stringify(state)
    }


    if (tab != null) {
        request_data.tab = tab
    }
    if (subtab != null) {
        request_data.subtab = subtab
    }

    if (metadata.tab != undefined) {
        request_data.tab = metadata.tab
    } else if (metadata.subtab != undefined) {
        request_data.subtab = metadata.subtab
    }


    $.ajax({
        url: '/ar_views.php', type: 'GET', dataType: 'json', data: request_data, success: function (data) {


            if (data.state == 200) {

                state = data.app_state;

                console.log(data.nav[0])

                if (typeof (data.nav[1]) != "undefined" && data.nav[1] !== null && data.nav[1] != '') {
                    $('#top_menu').html(data.nav[1]);
                }
                if (typeof (data.nav[2]) != "undefined" && data.nav[2] !== null && data.nav[2] != '') {
                    $('#au_header').html(data.nav[2]);
                }


                if (typeof (data.nav[3]) != "undefined" && data.nav[3] !== null && data.nav[3] != '') {
                    $('#web_navigation').html(data.nav[3]);
                } else {
                    $('#web_navigation').html('')
                }

                if (typeof (data.tabs) != "undefined" && data.tabs !== null) {
                    $('#tabs').html(data.tabs);

                }

                if (typeof (data.menu) != "undefined" && data.menu !== null) {

                    $('#menu').html(data.menu);


                }

                if (typeof (data.logout_label) != "undefined" && data.logout_label !== null) {
                    $('#logout_label').html(data.logout_label);
                }


                if (typeof (data.view_position) != "undefined" && data.view_position !== null) {
                    $('#view_position').html(data.view_position);
                }


                if (typeof (data.object_showcase) != "undefined" && data.object_showcase !== null) {
                    if (data.object_showcase == '_') {
                        $('#object_showcase').addClass('hide').html('')
                    } else {

                        $('#object_showcase').removeClass('hide')
                        $('#object_showcase').html(data.object_showcase);
                    }
                }


                if (typeof (data.tab) != "undefined" && data.tab !== null) {
                    $('#tab').html(data.tab);
                }

                if (typeof (data.structure) != "undefined" && data.structure !== null) {
                    //console.log(data.structure)
                    structure = data.structure
                }

                if (old_state_request == '') {
                    old_state_request = data.app_state.request
                }

                if (metadata.post_operations == 'delivery_note.fast_track_packing') {

                    $('#maintabs .tab').addClass('hide')


                    $("div[id='tab_delivery_note.fast_track_packing']").removeClass('hide')
                } else if (metadata.post_operations == 'delivery_note.fast_track_packing_off') {

                    $('#maintabs .tab').removeClass('hide')


                    $("div[id='tab_delivery_note.fast_track_packing']").addClass('hide')
                }

                if (state.title != undefined && state.title != '') {
                    document.title = state.title;
                } else {
                    document.title = 'Aurora';
                }


                change_browser_history_state(data.app_state.request)
                show_side_content($('#notifications').data('current_side_view'))
            } else {
                swal({
                    title: "Error A123"
                });
            }

        }
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
            key: k, value: array[k]
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

jQuery.fn.putCursorAtEnd = function () {

    return this.each(function () {

        // Cache references
        var $el = $(this), el = this;

        // Only focus if input isn't already
        if (!$el.is(":focus")) {
            $el.focus();
        }

        // If this function exists... (IE 9+)
        if (el.setSelectionRange) {

            // Double the length because Opera is inconsistent about whether a carriage return is one character or two.
            var len = $el.val().length * 2;

            // Timeout seems to be required for Blink
            setTimeout(function () {
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

function truncateWithEllipses(text, max) {
    return text.substr(0, max - 1) + (text.length > max ? '&hellip;' : '');
}

