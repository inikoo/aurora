/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapore.
 Refurbished  29 December 2019  12:13::42  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function search(search_field, timeout) {

    const query = search_field.val();
    window.clearTimeout(search_field.data("timeout"));


    const result_container = $('.smart_search_result');
    const results = result_container.find('.search_results');


    console.log(results)


    search_field.data("timeout", setTimeout(function () {

        const request = '/ar_search.php?tipo=search&query=' + fixedEncodeURIComponent(query)  + '&search_index=' + results.data('search_index')  + '&mtime=' +results.data('mtime')+ '&state=' + JSON.stringify(state);

    console.log(request)

        $.getJSON(request, function (data) {



            if (data.query == '') {
               close_search();
                return;

            } else {
                result_container.removeClass('hide');
                $('.close_search').addClass('show')


            }

            result_container.find('.num').html(data.number_results);

            if(results.data('search_index')=='') {
                results.data('search_index', data.search_index)
            }

            results.data('mtime',data.mtime)

            results.empty();


            const tbody = $('<tbody>').addClass(data.class);

            for (var result_key in data.results) {


                const icon_classes = data.results[result_key]._source.icon_classes;
                let col_0 = $('<td>').addClass('icon');

                if(icon_classes!=''){
                    $.each(icon_classes.split(/\|/), function (index, value) {

                        let icon = $('<i>').addClass(value);
                        if (index > 0) {
                            icon.addClass('padding_left_5 small')
                        }
                        col_0.append(icon)
                    });
                }




                let col_1 = $('<td>').addClass('col_1').html(data.results[result_key]._source.label_1);
                let col_2 = $('<td>').addClass('col_2').html(data.results[result_key]._source.label_2);
                let col_3 = $('<td>').addClass('col_3').html(data.results[result_key]._source.label_3);
                let col_4 = $('<td>').addClass('col_4').html(data.results[result_key]._source.label_4);

                let row = $('<tr>').addClass('button search_result').data('url', data.results[result_key]._source.url).append(col_0).append(col_1).append(col_2).append(col_3).append(col_4);
                // row.append($('<td>').append((data.results[result_key]._source.label_1)))

                tbody.append(row)

            }

            results.append(tbody)

        })


    }, timeout));
}

function close_search(){
    $('.smart_search_input input').val('');

    $(' .smart_search_input  .close_search').removeClass('show');

    const result_container = $('.smart_search_result');
    result_container.addClass('hide');
    result_container.find('.search_results').empty().data('search_index','').data('mtime','');
}

$(function () {

    $('#navigation').on('input propertychange', ' .smart_search_input input',function () {

        const delay = 200;
        search($(this), delay)
    });



    $('#navigation  ').on('click', '.smart_search_result .search_result', function () {
        change_view($(this).data('url'))


    });


    $('#navigation  ').on('click', ' .smart_search_input  .close_search', function () {

        close_search()



    });


});