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

    table.current_website_users{
        float: left;margin-left: 0px;margin-top: 40px;
        border-top:1px solid #999;
        width: 850px;
    }
    table.current_website_users td{
        padding:2px 15px;border-bottom:1px solid #ccc
    }

table.current_website_users .location img{
   position: relative;top:1px
}

table.current_website_users .device{
    text-align: center;width: 30px;
}

table.current_website_users .amount{
    text-align: right;padding-right: 50px;
}

</style>

<div style="width:330px;float: left;" class="current_website_users_{$website->id}">

</div>

<table class="current_website_users current_website_users_table_{$website->id}">
</table>


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

    var svg = d3.select(".current_website_users_{$website->id}").append("svg")
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


    $(".current_website_users_{$website->id}").data('svg',svg)
    $(".current_website_users_{$website->id}").data('pie',pie)


    var request = '/ar_real_time.php?tipo=website_users&website_key={$website->id}'
    $.getJSON(request, function (data) {



        website_analytics_render_website_users_pie(
            {$website->id},
            data.total_users,
            data.users
        )


        website_analytics_render_website_users_table(
                {$website->id},
            data.users_data
        )





    })



</script>