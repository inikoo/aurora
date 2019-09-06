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
        .value(function(d) { return d.count; })
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

    //region	fruit	count
    //East	Apples	30
    //South	Apples	100
    //Central	Apples	20


    d3.tsv("/data.tsv", type, function(error, data) {
        var current_web_visitors = d3.nest()
            .key(function(d) { return d.fruit; })
            .entries(data)
            .reverse();



        console.log(current_web_visitors[0])


        change(current_web_visitors[0])

        function change(region) {


            console.log(region.values)

            var total=0;
            $.each(region.values, function( index, value ) {
                total=total+value.count
            });

            svg.append("text")
                .attr("text-anchor", "middle")
                .attr('font-size', '4em')
                .attr('y', 20)
                .text(total);


            var path = svg.selectAll("path");
            var data0 = path.data(),
                data1 = pie(region.values);

            path = path.data(data1, key);

            path
                .transition()
                .duration(myDuration)
                .attrTween("d", arcTween)


            path
                .enter()
                .append("path")
                .each(function(d, i) {
                    var narc = findNeighborArc(i, data0, data1, key) ;
                    if(narc) {
                        this._current = narc;
                        this._previous = narc;
                    } else {
                        this._current = d;
                    }
                })
                .attr("fill", function(d,i) {
                    return color(d.data.region)
                })
                .transition()
                .duration(myDuration)
                .attrTween("d", arcTween)




            path
                .exit()
                .transition()
                .duration(myDuration)
                .attrTween("d", function(d, index) {

                    var currentIndex = this._previous.data.region;
                    var i = d3.interpolateObject(d,this._previous);
                    return function(t) {
                        return arc(i(t))
                    }

                })
                .remove()




        }
    });

    function key(d) {
        return d.data.region;
    }

    function type(d) {
        d.count = +d.count;
        return d;
    }

    function findNeighborArc(i, data0, data1, key) {
        var d;
        if(d = findPreceding(i, data0, data1, key)) {

            var obj = cloneObj(d)
            obj.startAngle = d.endAngle;
            return obj;

        } else if(d = findFollowing(i, data0, data1, key)) {

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

        return function(t) {
            return arc(i(t))
        }

    }


    function cloneObj(obj) {
        var o = {};
        for(var i in obj) {
            o[i] = obj[i];
        }
        return o;
    }
</script>