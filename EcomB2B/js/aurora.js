/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:30:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/




$(function() {



    $("form").on('submit', function (e) {

        e.preventDefault();
        e.returnValue = false;

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

