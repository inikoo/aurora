/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapore.
 Refurbished  29 December 2019  12:13::42  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/



function close_search(action) {

    let closing = false;

    if(ajax_search_Req != 'clean' && ajax_search_Req.readyState < 4) {
        ajax_search_Req.abort();
    }
    ajax_search_Req='clean';

    let search_index, mtime;

    $('.smart_search_input input').val('');

    $(' .smart_search_input  .close_search').removeClass('show');

    const result_container = $('.smart_search_result');
    result_container.addClass('hide');

    let search_results = result_container.find('.search_results')

    if (search_results.data('search_index') != '') {
        closing = true;
        search_index = search_results.data('search_index');
        mtime = search_results.data('mtime');
    }
    search_results.empty().data('search_index', '').data('mtime', '');

    if (closing) {
        const ajaxData = new FormData();
        ajaxData.append("search_index", search_index)
        ajaxData.append("mtime", mtime)
        ajaxData.append("action", action)
        ajaxData.append("click_url", '')
        ajaxData.append("click_pos", '')


        $.ajax({
            url: "/ar_search_analytics.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false
        })
    }

}


function search(search_field, timeout) {


    const query = search_field.val();
    window.clearTimeout(search_field.data("timeout"));
    const result_container = $('.smart_search_result');
    const results = result_container.find('.search_results');

    if(query == ''){
        close_search('empty');
        return;
    }



    search_field.data("timeout", setTimeout(function () {

        const request = '/ar_search.php?tipo=search&query=' + fixedEncodeURIComponent(query) + '&search_index=' + results.data('search_index') + '&mtime=' + results.data('mtime') + '&state=' + JSON.stringify(state);






        ajax_search_Req = $.ajax({
            url: request,
            type: 'GET',

            dataType: 'JSON',
            beforeSend : function() {
                if(ajax_search_Req != 'clean' && ajax_search_Req.readyState < 4) {
                    ajax_search_Req.abort();
                }
            },
            success: function(data) {

                if (data.query == '') {
                    close_search('empty');
                    return;

                } else {
                    result_container.removeClass('hide');
                    $('.close_search').addClass('show')
                }

                result_container.find('.num').html(data.number_results);

                if (results.data('search_index') == '') {
                    results.data('search_index', data.search_index)
                }

                results.data('mtime', data.mtime)

                results.empty();


                const tbody = $('<tbody>').addClass(data.class);
                var pos = 1;
                for (var result_key in data.results) {


                    const icon_classes = data.results[result_key]._source.icon_classes;
                    let col_0 = $('<td>').addClass('icon');

                    if (icon_classes != '') {
                        $.each(icon_classes.split(/\|/), function (index, value) {

                            let icon = $('<i>').addClass(value);
                            if (index > 0) {
                                icon.addClass('padding_left_5 small')
                            }
                            col_0.append(icon)
                        });
                    }



                    let col_store = $('<td>').addClass('col_store').html(data.results[result_key]._source.store_label);

                    let col_1 = $('<td>').addClass('col_1').html(data.results[result_key]._source.label_1);
                    let col_2 = $('<td>').addClass('col_2').html(data.results[result_key]._source.label_2);
                    let col_3 = $('<td>').addClass('col_3').html(data.results[result_key]._source.label_3);
                    let col_4 = $('<td>').addClass('col_4').html(data.results[result_key]._source.label_4);

                    let row = $('<tr>').addClass('button search_result').data('search_index', data.search_index).data('mtime', data.mtime).data('position', pos).data('url', data.results[result_key]._source.url).append(col_store).append(col_0).append(col_1).append(col_2).append(col_3).append(col_4);
                    // row.append($('<td>').append((data.results[result_key]._source.label_1)))

                    tbody.append(row)
                    pos++;
                }

                results.append(tbody)
            },
            error: function(xhr, ajaxOptions, thrownError) {
                if(thrownError == 'abort' || thrownError == 'undefined') return;
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        }); //end ajaxReq


    }, timeout));
}


$(function () {






    $('#navigation').on('input propertychange', ' .smart_search_input input', function () {

        const delay = 300;
        search($(this), delay)
    });



    $('#navigation  ').on('click', '.smart_search_result .search_result', function () {

        const url = $(this).data('url');
        const pos = $(this).data('position');
        const search_index = $(this).data('search_index');
        const mtime = $(this).data('mtime');


        change_view(url)

        const ajaxData = new FormData();
        ajaxData.append("search_index", search_index)
        ajaxData.append("mtime", mtime)
        ajaxData.append("action", 'click')
        ajaxData.append("click_url", url)
        ajaxData.append("click_pos", pos)


        ajax_search_Req=$.ajax({
            url: "/ar_search_analytics.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false

        })


    })


    $('#navigation  ').on('click', ' .smart_search_input  .close_search', function () {

        close_search('close')


    });


});