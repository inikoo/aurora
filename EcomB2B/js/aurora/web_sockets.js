

$(function () {

    if($('body').data('ws')=='y') {


        connect_websocket();
       // subscribe_websocket_channel();
        
        
        setInterval(function () {
            if (!websocket_connected_connecting && !websocket_connected) {
                connect_websocket();
             //   subscribe_websocket_channel();
            }


        }, 10000);

        setInterval(function () {

            if (websocket_connected) {
                ws_connection.publish('ping', 'hi')
            }

        }, 97500);

    }
})

function connect_websocket() {

    websocket_connected_connecting = true;

    if (location.host == 'au.bali' ||location.host == 'ecom.bali' ) {
        var wsuri = 'ws://au.bali/ws';

    }else if (location.host == 'au.geko' ||location.host == 'ecom.geko' ) {
        var wsuri = 'ws://' + location.hostname + '/ws180718';

    } else {
        var wsuri = (document.location.protocol === "http:" ? "ws:" : "wss:") + '//' + location.hostname + '/ws180718';

    }

    ws_connection = new ab.Session(wsuri, function () {

        websocket_connected_connecting = false;
        websocket_connected = true;

        console.log($('body').data('ws_key'))

        ws_connection.subscribe( $('body').data('ws_key'), function (topic, _data) {


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
                    $('#' + data.id + ' .export_download').removeClass('hide').attr('title', data.result_info).on('click', function () {

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




    }, function () {
        websocket_connected_connecting = false;
        //console.warn('WebSocket connection closed');
        websocket_connected = false;

    }, {'skipSubprotocolCheck': true});


}

function subscribe_websocket_channel() {


}
