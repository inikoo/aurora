{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 August 2016 at 18:07:06 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<div id="chartdiv" style="padding:10px;width:95%; height:600px;margin-bottom:80px;" data-data="{$data}"></div>


<script>

    var data = JSON.parse(atob($('#chartdiv').data("data")))


    var chart = AmCharts.makeChart("chartdiv",
            {
                "type": "stock",
                "pathToImages": "/art/amcharts/",
//"theme": "light",

                //"color": "#fff",
                "dataSets": [

                    {
                        "title": "GBP",
                        "fieldMappings": [
                            {
                                "fromField": "Open",
                                "toField": "open"
                            },
                            {
                                "fromField": "Volume",
                                "toField": "volume"
                            }],
                        "compared": false,
                        "categoryField": "Date",
                        "dataLoader": {


                            "url": "/ar_timeseries.php?tipo=asset_sales&parent=" + data.parent + '&parent_key=' + data.parent_key + "&from=&to=",
                            "format": "csv",
                            "showCurtain": true,
                            "showErrors": true,
                            "async": true,
                            "reverse": true,
                            "delimiter": ",",
                            "useColumnNames": true
                        }
                    }
                ],
                "dataDateFormat": "YYYY-MM-DD",

                "panels": [
                    {
                        "title": data.title_value,
                        "percentHeight": 70,

                        "stockGraphs": [{
                            "type": "line",
                            "id": "g1",
                            "valueField": "open",
                            "periodValue": 'Sum',


                            //  "closeField": "close",
                            //  "highField": "high",
                            //  "lowField": "low",
                            //  "valueField": "close",
                            // "lineColor": "#fff",
                            // "fillColors": "#fff",
                            // "negativeLineColor": "#db4c3c",
                            // "negativeFillColors": "#db4c3c",
                            // "fillAlphas": 1,
//        "comparedGraphLineThickness": 2,
                            "columnWidth": 0.7,
                            "useDataSetColors": false,
                            //   "comparable": true,
                            //  "compareField": "close",
                            "showBalloon": true,
                            "proCandlesticks": true
                        }],

                        "stockLegend": {
                            "valueTextRegular": undefined,
                            //      "periodValueTextComparing": "[[percents.value.close]]%"
                        }

                    },

                    {
                        "title": "Volume",
                        "percentHeight": 30,
                        "marginTop": 1,
                        "columnWidth": 0.6,
                        "showCategoryAxis": false,

                        "stockGraphs": [{
                            "valueField": "volume",
                            "type": "column",
                            "showBalloon": true,
                            "periodValue": 'Sum',

                            "fillAlphas": 1,
                            "lineColor": "#fff",
                            "fillColors": "#fff",
                            //  "negativeLineColor": "#db4c3c",
                            //  "negativeFillColors": "#db4c3c",
                            "useDataSetColors": false
                        }],

                        "stockLegend": {
                            "markerType": "none",
                            "markerSize": 0,
                            "labelText": "",
                            "periodValueTextRegular": "[[value.Sum]]"
                        },

                        "valueAxes": [{
                            "usePrefixes": true
                        }]
                    }
                ],

                "panelsSettings": {
                    //    "color": "#fff",
                    "plotAreaFillColors": "#333",
                    "plotAreaFillAlphas": 1,
                    "marginLeft": 60,
                    "marginTop": 5,
                    "marginBottom": 5
                },

                "chartScrollbarSettings": {
                    "graph": "g1",
                    "graphType": "line",
                    "usePeriod": "WW",
                    "backgroundColor": "#333",
                    "graphFillColor": "#666",
                    "graphFillAlpha": 0.5,
                    "gridColor": "#555",
                    "gridAlpha": 1,
                    "selectedBackgroundColor": "#444",
                    "selectedGraphFillAlpha": 1
                },

                "categoryAxesSettings": {


                    //  "equalSpacing": true,
                    "gridColor": "#555",
                    "gridAlpha": 1
                },

                "valueAxesSettings": {
                    "gridColor": "#555",
                    "gridAlpha": 1,
                    "inside": false,
                    "showLastLabel": true
                },

                "chartCursorSettings": {
                    "pan": true,
                    "valueLineEnabled": true,
                    "valueLineBalloonEnabled": true
                },

                "legendSettings": {
                    //"color": "#fff"
                },

                "stockEventsSettings": {
                    "showAt": "high",
                    "type": "pin"
                },

                "balloon": {
                    "textAlign": "left",
                    "offsetY": 10
                },

                "periodSelector": {
                    "position": "bottom",
                    "periods": [{
                        "period": "DD",
                        "count": 10,
                        "label": "10D"
                    }, {
                        "period": "MM",
                        "count": 1,
                        "label": "1M"
                    }, {
                        "period": "MM",
                        "count": 6,
                        "label": "6M"
                    }, {
                        "period": "YYYY",
                        "count": 1,
                        "label": "1Y"
                    }, {
                        "period": "YYYY",
                        "count": 2,
                        "selected": true,
                        "label": "2Y"
                    },
                        /* {
                         "period": "YTD",
                         "label": "YTD"
                         },*/
                        {
                            "period": "MAX",
                            "label": "MAX"
                        }
                    ]
                }
            });

</script>