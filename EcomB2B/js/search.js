/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 April 2018 at 20:55:34 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/


/*

$(function() {



    $("form").on('submit', function (e) {

        e.preventDefault();
        e.returnValue = false;

    });

   





});
*/

function search(query){


    var request = "/ar_web_search.php?tipo=search&query=" +encodeURIComponent(query)
    console.log(request)

    $.getJSON(request, function (data) {

      $('#search_results').html(data.results)


    })

}

