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
var websocket_connected=false;
var websocket_connected_connecting=false;



$(document).ready(function () {


    state = {
        module: '', section: '', parent: '', parent_key: '', object: '', key: ''
    }
    structure = {}


    change_view($('#_request').val())





    $(document).on( "keydown", function (e) {
        key_press(e)
    })


    //   $(document).scannerDetection(function (value) {
//
//        scanned_barcode(value)
//    });






    /*

        var    wsuri = (document.location.protocol === "http:" ? "ws:" : "wss:") + "//" + document.location.host + "/w3bs0012033";

        var connection = new autobahn.Connection({
            url: wsuri,
            realm: "aurora"
        });

        connection.onopen = function (session, details) {

            function real_time (args) {

                var data=args[0]
                for (var i in data.objects) {
                    if (state.object == data.objects[i].object && state.key == data.objects[i].key) {
                        for (var j in data.objects[i].update_metadata.class_html) {
                            $('.' + j).html(data.objects[i].update_metadata.class_html[j])
                        }

                        for (var key in  data.objects[i].update_metadata.hide) {
                            $('.' + data.objects[i].update_metadata.hide[key]).addClass('hide')
                        }

                        for (var key in data.objects[i].update_metadata.show) {

                            $('.' + data.objects[i].update_metadata.show[key]).removeClass('hide')
                        }
                    }
                }

                for (var i in data.sections) {
                    if (state.section == data.sections[i].section ) {
                        for (var j in data.sections[i].update_metadata.class_html) {
                            $('.' + j).html(data.sections[i].update_metadata.class_html[j])
                        }

                        for (var key in  data.sections[i].update_metadata.hide) {
                            $('.' + data.sections[i].update_metadata.hide[key]).addClass('hide')
                        }

                        for (var key in data.sections[i].update_metadata.show) {
                            $('.' + data.sections[i].update_metadata.show[key]).removeClass('hide')
                        }
                    }
                }
            }

            function real_time_private (args) {

                var _data=args[0]

                for (var i in _data.progress_bar) {
                    var data = _data.progress_bar[i]

                    console.log(data)

                    if (data.state == 'In Process') {

                        $('#' + data.id + ' .export_download').addClass('hide')

                        $('#' + data.id + ' .export_progress_bar_bg').removeClass('hide').html('&nbsp;' + data.progress_info)
                        $('#' + data.id + ' .export_progress_bar').css('width', data.percentage).removeClass('hide').attr('title', data.progress).html('&nbsp;' + data.progress_info);


                    } else if (data.state == 'Finish') {

                        // console.log('#'+data.id+' .download_export')


                        $('#' + data.id + ' .download_export').attr('href', '/download.php?file=' + data.download_key)
                        $('#' + data.id + ' .export_download').removeClass('hide').attr('title', data.result_info).on( 'click',function () {

                            download_exported_file(this)

                        });
                        $('#' + data.id + ' .export_progress_bar_bg').addClass('hide').html('')
                        $('#' + data.id + ' .export_progress_bar').css('width', '0px').removeClass('hide').attr('title', '').html('')


                        $('#' + data.id + ' .export_button').addClass('link').removeClass('disabled')


                        $('#' + data.id + ' .field_export').addClass('button').removeClass('disabled')
                        $('#' + data.id + ' .stop_export').addClass('hide')

                    }

                }

            }


            session.subscribe('real_time.'+$('#account_name').data('account_code').toLowerCase(), real_time).then(
                function (sub) {
                    console.log('subscribed to topic');
                },
                function (err) {
                    console.log('failed to subscribe to topic', err);
                }
            );



            session.subscribe('real_time.'+$('#account_name').data('account_code').toLowerCase()+'.'+$('#hello_user').data('user_key'), real_time_private).then(
                function (sub) {
                    console.log('subscribed to topic');
                },
                function (err) {
                    console.log('failed to subscribe to topic', err);
                }
            );




        };

        connection.onclose = function (reason, details) {
            console.log("Connection lost: " + reason);

           // setTimeout(connection.open(), 1000);
        }

        connection.open();

    */



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


function connect_websocket(){

    websocket_connected_connecting=true;

    if(location.host=='au.bali'){
        var wsuri='ws://'+location.hostname+'/ws';

    }else{
        var wsuri=   (document.location.protocol === "http:" ? "ws:" : "wss:") +   '//'+location.hostname+'/ws180718';

    }


    ws_connection = new ab.Session(wsuri,
        function() {


            websocket_connected_connecting=false;

            websocket_connected=true;


            // console.log('real_time.'+$('#account_name').data('account_code').toLowerCase())

            ws_connection.subscribe('real_time.'+$('#account_name').data('account_code').toLowerCase(), function(topic, data) {
                // console.log(state.object)
                for (var i in data.objects) {



                    if (state.object == data.objects[i].object && state.key == data.objects[i].key) {


                        for (var j in data.objects[i].update_metadata.class_html) {
                            $('.' + j).html(data.objects[i].update_metadata.class_html[j])
                        }

                        for (var key in  data.objects[i].update_metadata.hide) {
                            $('.' + data.objects[i].update_metadata.hide[key]).addClass('hide')
                        }

                        for (var key in data.objects[i].update_metadata.show) {
                            $('.' + data.objects[i].update_metadata.show[key]).removeClass('hide')
                        }

                        for (var j in data.objects[i].update_metadata.add_class) {
                            //console.log(j)
                            //console.log(data.objects[i].update_metadata.add_class[j])

                            $('.' + j).addClass(data.objects[i].update_metadata.add_class[j])
                        }
                    }
                }

                for (var i in data.sections) {
                    if (state.section == data.sections[i].section ) {
                        for (var j in data.sections[i].update_metadata.class_html) {
                            $('.' + j).html(data.sections[i].update_metadata.class_html[j])
                        }

                        for (var key in  data.sections[i].update_metadata.hide) {
                            $('.' + data.sections[i].update_metadata.hide[key]).addClass('hide')
                        }

                        for (var key in data.sections[i].update_metadata.show) {
                            $('.' + data.sections[i].update_metadata.show[key]).removeClass('hide')
                        }

                        for (var j in data.sections[i].update_metadata.add_class) {
                            $('.' + j).addClass(data.sections[i].update_metadata.add_class[j])
                        }
                    }
                }

                for (var i in data.tabs) {


                    //console.log(data.tabs)
                    if (state.tab == data.tabs[i].tab ) {

                        //console.log(data.rtext)

                        if(data.tabs[i].rtext!=undefined){
                            $('#rtext').html(data.tabs[i].rtext)
                        }

                        for (var j in data.tabs[i].cell) {
                            // console.log(j)
                            $('#table .' + j).html(data.tabs[i].cell[j])
                        }


                    }
                }

            });

            ws_connection.subscribe('real_time.'+$('#account_name').data('account_code').toLowerCase()+'.'+$('#hello_user').data('user_key'), function(topic, _data) {



                for (var i in _data.progress_bar) {
                    var data = _data.progress_bar[i]

                    //console.log(data)

                    if (data.state == 'In Process') {

                        $('#' + data.id + ' .export_download').addClass('hide')

                        $('#' + data.id + ' .export_progress_bar_bg').removeClass('hide').html('&nbsp;' + data.progress_info)
                        $('#' + data.id + ' .export_progress_bar').css('width', data.percentage).removeClass('hide').attr('title', data.progress).html('&nbsp;' + data.progress_info);


                    } else if (data.state == 'Finish') {

                        // console.log('#'+data.id+' .download_export')


                        $('#' + data.id + ' .download_export').attr('href', '/download.php?file=' + data.download_key)
                        $('#' + data.id + ' .export_download').removeClass('hide').attr('title', data.result_info).on( 'click',function () {

                            download_exported_file(this)

                        });
                        $('#' + data.id + ' .export_progress_bar_bg').addClass('hide').html('')
                        $('#' + data.id + ' .export_progress_bar').css('width', '0px').removeClass('hide').attr('title', '').html('')


                        $('#' + data.id + ' .export_button').addClass('link').removeClass('disabled')


                        $('#' + data.id + ' .field_export').addClass('button').removeClass('disabled')
                        $('#' + data.id + ' .stop_export').addClass('hide')

                    }

                }




            });


        },
        function() {
            websocket_connected_connecting=false;
            console.warn('WebSocket connection closed');
            websocket_connected=false;

        },
        {'skipSubprotocolCheck': true}
    );


}

function change_browser_history_state(request) {


    if (request == undefined) {
        return;
    }

    if (request.charAt(0) !== '/') {
        request = '/' + request
    }

    console.log(old_state_request + ' _> ' + request)





    if (old_state_request != request) {





        if (!$('#is_devel').val()) {

            var _tmp=$('#account_name');
            ga('set', 'contentGroup1', state.module.replace("_", " ").capitalize());
            ga('set', 'contentGroup2', state.section.replace("_", " ").capitalize());
            ga('set', 'contentGroup3', _tmp.data('user_handle').capitalize()+' '+_tmp.data('account_code'));







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

    if(event.state==null){
        console.log('null state!!!!!!')
    }else{
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

function change_menu_view(module){

    // console.log(module)

    switch (module){
        case '_dashboard':
            change_view('/dashboard')
            break;
        case 'customers':
            if(state.current_store){
                change_view('customers/'+state.current_store+'/dashboard')
            }else{
                change_view('customers/all')
            }
            break;
        case 'orders':
            if(state.current_store){
                change_view('orders/'+state.current_store+'/dashboard')
            }else{
                change_view('orders/all/by_store')
            }
            break;

        case 'delivery_notes':
            // if(state.current_store){
            //   change_view('delivery_notes/'+state.current_store)
            //}else{
            change_view('delivery_notes/all/')
            //  }
            break;

        case 'products':
            if(state.current_store){
                change_view('store/'+state.current_store)
            }else{
                change_view('stores')
            }
            break;
        case 'warehouses':
            if(state.current_warehouse){
                change_view('warehouse/'+state.current_warehouse+'/dashboard')
            }else{
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
            change_view('suppliers')
            break;
        case 'production':
            if(state.current_production){
                change_view('production/'+state.current_production)
            }else{
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

function change_view_if_has_link_class(element,_request, metadata){

    if($(element).hasClass('link')){
        change_view(_request, metadata)
    }

}

function change_view(_request, metadata) {

    /*
        evt=window.event;

        if (evt.type=='click' && evt.metaKey){



            window.open('/'+_request, '_blank');
            return;

        }
    */

    //console.log(websocket_connected)


    if (metadata == undefined || !metadata ) {
        metadata = {};
    }


    var request = "/ar_views.php?tipo=views&request=" + _request + '&metadata=' + JSON.stringify(metadata) + "&old_state=" + JSON.stringify(state)




    if (metadata.tab != undefined) {
        request = request + '&tab=' + metadata.tab;
    } else if (metadata.subtab != undefined) {
        request = request + '&subtab=' + metadata.subtab;
    }



    $.getJSON( request, {  } )
        .done(function( data ) {


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
                //console.log(data.structure)

                structure = data.structure
            }


            if (old_state_request == '') {
                old_state_request = data.state.request
            }




            if (metadata.post_operations == 'delivery_note.fast_track_packing') {

                $('#maintabs .tab').addClass('hide')


                $("div[id='tab_delivery_note.fast_track_packing']").removeClass('hide')
            } else if (metadata.post_operations == 'delivery_note.fast_track_packing_off') {

                $('#maintabs .tab').removeClass('hide')


                $("div[id='tab_delivery_note.fast_track_packing']").addClass('hide')
            }

            if(state.title!=undefined && state.title!=''){
                document.title = state.title;
            }else{
                document.title = 'Aurora';
            }


            change_browser_history_state(data.state.request)
            show_side_content($('#notifications').data('current_side_view'))
        })
        .fail(function( jqxhr, textStatus, error ) {
            var err = textStatus + ", " + error;
            //console.log( "Request Failed: " + err );
        });

    // console.log(state)

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
