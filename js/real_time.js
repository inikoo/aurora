/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  09-09-2019 00:06:06 MYT Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function connect_websocket() {

    websocket_connected_connecting = true;

    if (location.host == 'au.bali') {
        var wsuri = 'ws://' + location.hostname + '/ws';

    } else {
        var wsuri = (document.location.protocol === "http:" ? "ws:" : "wss:") + '//' + location.hostname + '/ws180718';

    }


    ws_connection = new ab.Session(wsuri, function () {


        websocket_connected_connecting = false;

        websocket_connected = true;


        // console.log('real_time.'+$('#account_name').data('account_code').toLowerCase())

        ws_connection.subscribe('real_time.' + $('#account_name').data('account_code').toLowerCase(), function (topic, data) {


            for (var i in data.d3) {
                if (data.d3[i]['type'] == 'current_website_users') {
                    if (state.tab == 'website.analytics' && state.key == data.d3[i]['website_key']) {
                        website_analytics_render_website_users_pie(data.d3[i]['website_key'], data.d3[i]['data'].total_users, data.d3[i]['data'].users)
                        website_analytics_render_website_users_table(data.d3[i]['website_key'], data.d3[i]['data'].users_data)
                    }


                }
            }


            for (var i in data.objects) {


                if (state.object == data.objects[i].object && state.key == data.objects[i].key) {


                    for (var j in data.objects[i].update_metadata.class_html) {
                        $('.' + j).html(data.objects[i].update_metadata.class_html[j])
                    }

                    for (var j in data.objects[i].update_metadata.titles) {
                        $('.' + j).attr('title', data.objects[i].update_metadata.titles[j])
                    }

                    for (var key in data.objects[i].update_metadata.hide) {
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
                if (state.section == data.sections[i].section) {
                    for (var j in data.sections[i].update_metadata.class_html) {
                        $('.' + j).html(data.sections[i].update_metadata.class_html[j])
                    }

                    for (var key in data.sections[i].update_metadata.hide) {
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
                if (state.tab == data.tabs[i].tab) {

                    //console.log(data.rtext)

                    if (data.tabs[i].rtext != undefined) {
                        $('#rtext').html(data.tabs[i].rtext)
                    }

                    for (var j in data.tabs[i].cell) {
                        // console.log(j)
                        $('#table .' + j).html(data.tabs[i].cell[j])
                    }


                }
            }

        });

        ws_connection.subscribe('real_time.' + $('#account_name').data('account_code').toLowerCase() + '.' + $('#hello_user').data('user_key'), function (topic, _data) {


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
        console.warn('WebSocket connection closed');
        websocket_connected = false;

    }, {'skipSubprotocolCheck': true});


}

function website_analytics_render_website_users_pie(website_key, total, values) {


    var svg = $(".current_website_users_" + website_key).data('svg')
    var pie = $(".current_website_users_" + website_key).data('pie')


    svg.selectAll("text").text(total);


    var path = svg.selectAll("path");
    var data0 = path.data(), data1 = pie(values);

    path = path.data(data1, key);

    path
        .transition()
        .duration(myDuration)
        .attrTween("d", arcTween)


    path
        .enter()
        .append("path")
        .each(function (d, i) {
            var narc = findNeighborArc(i, data0, data1, key);
            if (narc) {
                this._current = narc;
                this._previous = narc;
            } else {
                this._current = d;
            }
        })
        .attr("fill", function (d, i) {
            return color(d.data.device)
        })
        .transition()
        .duration(myDuration)
        .attrTween("d", arcTween)


    path
        .exit()
        .transition()
        .duration(myDuration)
        .attrTween("d", function (d, index) {

            var currentIndex = this._previous.data.device;
            var i = d3.interpolateObject(d, this._previous);
            return function (t) {
                return arc(i(t))
            }

        })
        .remove()


    function key(d) {
        return d.data.device;
    }


    function findNeighborArc(i, data0, data1, key) {
        var d;
        if (d = findPreceding(i, data0, data1, key)) {

            var obj = cloneObj(d)
            obj.startAngle = d.endAngle;
            return obj;

        } else if (d = findFollowing(i, data0, data1, key)) {

            var obj = cloneObj(d)
            obj.endAngle = d.startAngle;
            return obj;

        }

        return null


    }

    // Find the element in data0 that joins the highest preceding element in data1.
    function findPreceding(i, data0, data1, key) {
        var m = data0.length;
        while (--i >= 0) {
            var k = key(data1[i]);
            for (var j = 0; j < m; ++j) {
                if (key(data0[j]) === k) return data0[j];
            }
        }
    }

    // Find the element in data0 that joins the lowest following element in data1.
    function findFollowing(i, data0, data1, key) {
        var n = data1.length, m = data0.length;
        while (++i < n) {
            var k = key(data1[i]);
            for (var j = 0; j < m; ++j) {
                if (key(data0[j]) === k) return data0[j];
            }
        }
    }

    function arcTween(d) {

        var i = d3.interpolate(this._current, d);

        this._current = i(0);

        return function (t) {
            return arc(i(t))
        }

    }


    function cloneObj(obj) {
        var o = {};
        for (var i in obj) {
            o[i] = obj[i];
        }
        return o;
    }


}


function website_analytics_render_website_users_table(website_key, web_users_data) {

    var table = $('.current_website_users_table_' + website_key)


    var to_delete_customer_keys = [];
    $('.current_website_users_table_' + website_key + ' tr').each(function (index) {

        to_delete_customer_keys.push($(this).data('customer_key'))
    });


    console.log(to_delete_customer_keys)
    $.each(web_users_data, function (key, data) {

        to_delete_customer_keys.splice($.inArray(data.customer_key, to_delete_customer_keys), 1);

        var tr = table.find('tr.rt_wu_' + data.customer_key)
        if (tr.length) {

            tr.find('.webpage').html(data.webpage_label)
            tr.find('.amount').html(data.order_net_formatted)

        } else {
            table.append('<tr data-customer_key="' + data.customer_key + '"  class="rt_wu_' + data.customer_key + '">' + '<td class="device">' + data.device_label + '</td>' +

                '<td class="location">' + data.location + '</td>' + '<td class="customer">' + data.customer_label + '</td>' + '<td class="amount" data-amount="' + data.order_net + '">' + data.order_net_formatted + '</td>' + '<td class="webpage"> ' + data.webpage_label + '</td>' +

                '</tr>')
        }
    });


    $.each(to_delete_customer_keys, function (index, to_delete_customer_key) {
        table.find('.rt_wu_' + to_delete_customer_key).remove()
    });

}

