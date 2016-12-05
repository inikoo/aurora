{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 331 May 2016 at 11:11:16 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<section id="sidebar-main" class="col-md-12">
    <div id="content"><h1>Contact Us</h1>

        <div class="contact-location hidden-xs">

            <div id="contact-map"></div>

        </div>

        <div class="wrap-contact">

            <div class="row">

                <div class="col-ld-4 col-md-4 col-sm-12 hidden-xs">

                    <div class="contact-info">

                        <h2>Our Location</h2>

                        <div class="content body-page">


                            <div class="media">

                                <i class="fa fa-home pull-left"></i>

                                <div class="media-body">

                                    Floral Store<br> Address 1
                                </div>

                            </div>

                            <div class="media">

                                <i class="fa fa-phone pull-left"></i>

                                <div class="media-body">

                                    <!-- <strong></strong> -->

                                    123456789

                                </div>

                            </div>


                            <div class="media">

                                <i class="fa fa-envelope-o pull-left"></i>

                                <div class="media-body">

                                    <!-- <strong></strong> -->

                                    1234567890
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="contact-customhtml">

                        <div class="content body-page">

                            <p><b>
                                    <small>This is a CMS block edited from admin panel.</small>
                                    <br>

                                    <small>You can insert any content here.</small>
                                </b></p>


                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non dui at sapien
                                tempor gravida ut vel arcu. Nullam ac eros eros, et ullamcorper leo.</p>


                            <p><b>
                                    <small>Customer Service:</small>
                                </b><br>

                                <a href="mailto:info@yourstore.com">info@yourstore.com</a></p>


                            <p><b>
                                    <small>Returns and Refunds:</small>
                                </b><br>

                                <a href="mailto:returns@yourstore.com">returns@yourstore.com</a></p>

                        </div>

                    </div>


                </div><!-- end1 -->

                <div class="col-ld-8 col-md-8 col-sm-12">

                    <div class="wrapform">

                        <form action="http://demopavothemes.com/pav_floral/index.php?route=information/contact"
                              method="post" enctype="multipart/form-data" class="form-horizontal ">

                            <fieldset class="">

                                <h2>Contact Form</h2>

                                <div class="content body-page">

                                    <div class="form-group">

                                        <label class="col-sm-2" for="input-name">Your Name</label>

                                        <div class="col-sm-10">

                                            <input type="text" name="name" value="" id="input-name"
                                                   class="form-control"/>

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <label class="col-sm-2" for="input-email">E-Mail Address</label>

                                        <div class="col-sm-10">

                                            <input type="text" name="email" value="" id="input-email"
                                                   class="form-control"/>

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <label class="col-sm-2" for="input-enquiry">Enquiry</label>

                                        <div class="col-sm-10">

                                            <textarea name="enquiry" rows="10" id="input-enquiry"
                                                      class="form-control"></textarea>

                                        </div>

                                    </div>

                                    <div class="form-group required">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <div class="g-recaptcha"
                                                 data-sitekey="6LcTyAYTAAAAAD3hKJNuJVIZbRjJRo33MbF4qF7n"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="buttons">

                                    <div class="right"><input type="submit" value="Submit"
                                                              class="button btn btn-theme-default"/></div>

                                </div>

                            </fieldset>

                        </form>

                    </div>

                </div>

                <!-- end -->

            </div><!-- end -->

        </div>

    </div>

</section>


{*
     <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=en"></script>

    <script type="text/javascript" src="catalog/view/javascript/gmap/gmap3.min.js"></script>

    <script type="text/javascript" src="catalog/view/javascript/gmap/gmap3.infobox.js"></script>

    <script type="text/javascript">

        var mapDiv, map, infobox;

        var lat = 40.705423;

        var lon = -74.008616;

        jQuery(document).ready(function($) {

            mapDiv = $("#contact-map");

            mapDiv.height(400).gmap3({

                map:{

                    options:{

                        center:[lat,lon],

                        zoom: 15

                    }

                },

                marker:{

                    values:[

                        {latLng:[lat, lon], data:"79-99 Beaver Street, New York, NY 10005, USA"},

                    ],

                    options:{

                        draggable: false

                    },

                    events:{

                          mouseover: function(marker, event, context){

                            var map = $(this).gmap3("get"),

                                infowindow = $(this).gmap3({get:{name:"infowindow"}});

                            if (infowindow){

                                infowindow.open(map, marker);

                                infowindow.setContent(context.data);

                            } else {

                                $(this).gmap3({

                                infowindow:{

                                    anchor:marker, 

                                    options:{content: context.data}

                                }

                              });

                            }

                        },

                        mouseout: function(){

                            var infowindow = $(this).gmap3({get:{name:"infowindow"}});

                            if (infowindow){

                                infowindow.close();

                            }

                        }

                    }

                }

            });

        });

    </script>
*}
<!--

$ospans: allow overrides width of columns base on thiers indexs. format array( column-index=>span number ), example array( 1=> 3 )[value from 1->12]

-->


