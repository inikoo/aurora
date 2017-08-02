/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:30:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/

$(function() {

    $('#logout').on("click", function () {

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'logout')

        ajaxData.append("webpage_key", $('#webpage_data').attr('webpage_key'))

        $.ajax({
            url: "/ar_web_logout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {

            }, success: function (data) {

                // console.log(data)

                if (data.state == '200') {

                    location.reload();

                } else if (data.state == '400') {
                    swal(data.msg);
                }


            }, error: function () {

            }
        });


    });


    $('#header_search_icon').on("click", function () {

        window.location.href = "search.sys?q=" + encodeURIComponent($('#header_search_input').val());


    });

    $('#search_icon').on("click", function () {

        search($('#search_input').val());


    });


    $("#header_search_input").on('keyup', function (e) {
        if (e.keyCode == 13) {
            window.location.href = "search.sys?q=" + encodeURIComponent($(this).val());
        }
    });

    $(document).on('keyup', '#search_input', function (e) {
        if (e.keyCode == 13) {
            search($(this).val())
        }
    });

});


function search(query){


    var request = "/ar_web_search.php?tipo=search&query=" +encodeURIComponent(query)
    console.log(request)

    $.getJSON(request, function (data) {

      $('#search_results').html(data.results)


    })

}

