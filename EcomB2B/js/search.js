/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 April 2018 at 20:55:34 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/



function search(query){


    var request = "/ar_web_search.php?tipo=search&query=" +encodeURIComponent(query)
   // console.log(request)

    $.getJSON(request, function (data) {

        $('#search_results').html(data.results)

        for(var i = 0; i < data.analytics.length; i++) {
            var product = data.analytics[i];



            ga('auTracker.ec:addImpression', {
                'id': product.id,
                'name': product.name,
                'category': product.category,
                'price': product.price,
                'list': 'Search Results',
                'position': i+1
            });
        }

        ga('auTracker.send', 'pageview');

    })

}
