{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 05-09-2019 21:54:58 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}

<style>

.current_users{
    font-family: Ubuntu, "Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, sans-serif;
}

   </style>

<div class="current_users">

</div>
<script>



    var myDuration = 600;



    var width = 300,
        height = 300,
        radius = Math.min(width, height) / 2;
    var color = d3.scaleOrdinal(d3.schemeCategory20);
    var pie = d3.pie()
        .value(function(d) { return d.users; })
        .sort(null);

    var arc = d3.arc()
        .innerRadius(radius - 60)
        .outerRadius(radius - 30);

    var svg = d3.select(".current_users").append("svg")
        .attr("width", width)
        .attr("height", height)
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")")
        .append("g")



    ;


    svg.append("text")
        .attr("text-anchor", "middle")
        .attr('font-size', '4em')
        .attr('y', 20)


    $(".current_users").data('svg',svg)
    $(".current_users").data('pie',pie)


    var request = '/ar_real_time.php?tipo=website_users&website_key={$website->id}'
    $.getJSON(request, function (data) {



        website_analytics_render_website_users_pie(
            data.total_users,
            data.users
        )



    })






</script>