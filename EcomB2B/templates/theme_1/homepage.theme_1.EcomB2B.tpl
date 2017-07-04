{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2017 at 21:26:05 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{include file="theme_1/_head.theme_1.EcomB2B.tpl"}


<body xmlns="http://www.w3.org/1999/html">
<div class="wrapper_boxed">

    <div class="site_wrapper">

        {include file="theme_1/header.EcomB2B.tpl"}



        <div id="show_slider" class="{if !$content.show_slider}hide{/if}">


            {foreach from=$content.sliders key=key  item=slider}
                <div id="edit_mstslider_{$key}" class=" edit_mstslider hide"  style="height: 655px;background-image: url({$slider.image} ); background-size: auto 100%;  background-position: center;  ;     ">

                    <div class="ms-layer centext text1 white" style="position:relative;top:240px" data-effect="bottom(50)"><strong class="_slider_title" contenteditable="true" >{$slider.title}</strong></div>

                    <div class="ms-layer centext text2 white _slider_text" style="position:relative;top:259px" data-effect="bottom(50)" data-duration="2000" data-delay="500" data-ease="easeOutExpo" contenteditable="true" >{$slider.text}</div>

                    <div class="ms-layer centext sdbut" style="position:relative;top:305px" data-effect="bottom(100)" data-duration="2000" data-delay="900" data-ease="easeOutExpo"><a class="_slider_link_label"  href="#" contenteditable="true" >{$slider.link_label}</a>
                    </div>

                </div>
            {/foreach}


            <div class=" mstslider">

                <!-- masterslider -->
                <div class="master-slider ms-skin-default" id="masterslider">


                    {foreach from=$content.sliders  item=slider}
                        <div class="ms-slide slide-1" data-delay="7">

                            <!-- slide background -->
                            <img src="theme_1/masterslider/blank.gif" data-src="{$slider.image}" alt=""/>

                            <div class="ms-layer {$slider.title_class}   text1 " style="{$slider.title_style}" data-effect="bottom(50)" data-duration="900" data-delay="300" data-ease="easeOutExpo">
                                <strong>{$slider.title}</strong></div>

                            <div class="ms-layer {$slider.text_class}  text2 " style="{$slider.text_style}" data-effect="bottom(50)" data-duration="2000" data-delay="500" data-ease="easeOutExpo">{$slider.text}</div>

                            <div class="ms-layer {$slider.link_class}  sdbut" style="{$slider.link_style}" data-effect="bottom(100)" data-duration="2000" data-delay="900" data-ease="easeOutExpo"><a
                                        href="#">{$slider.link_label}</a></div>

                        </div>
                    {/foreach}


                </div><!-- end of masterslider -->

            </div><!-- end slider -->

            <div class="clearfix"></div>

        </div>


        <div id="show_features" class="{if !$content.show_features}hide{/if}">

            <div class="features_sec30">
                <div class="container">


                    {foreach from=$content.features.columns  item=feature_column name=feature_columns}
                        <div class="one_third {if $smarty.foreach.feature_columns.last}last{/if}">

                            {foreach from=$feature_column  item=feature_row name=feature_rows}
                                <div class="left"><span aria-hidden="true" class="{$feature_row.icon}"></span></div>
                                <div class="right">
                                    <h5 class="light">{$feature_row.title}</h5>
                                    <p>{$feature_row.text}</p>
                                </div>
                                <!-- end section -->

                                {if !$smarty.foreach.feature_rows.last}
                                    <div class="clearfix margin_top7"></div>
                                {/if}

                            {/foreach}


                        </div>
                    {/foreach}

                </div>
            </div><!-- end features section 30 -->


            <div class="clearfix"></div>


        </div>





        <div id="show_counter" class="{if !$content.show_counter}hide{/if}">

            <div class="features_sec31">
                <div class="container">

                    <div class="counters1 two">


                        {assign "column_class" "one_fourth"}

                        {foreach from=$content.counter.columns  key=key item=counter_column name=counter_columns}

                            <div class="{$column_class}  {if $smarty.foreach.counter_columns.last}last{/if}"><span id="counter_target_{$key}">0</span> <h4>{$counter_column.label}</h4></div>

                        {/foreach}




                    </div><!-- end counters1 section -->

                </div>
            </div><!-- end features section 31 -->

            <div class="clearfix"></div>



            <script>


                {foreach from=$content.counter.columns key=key  item=counter_column name=counter_columns}
                $('#counter_target_{$key}').animateNumber(
                    {
                        number: {$counter_column.number},

                        numberStep: function(now, tween) {
                            var floored_number = Math.floor(now),
                                target = $(tween.elem);

                            target.text(floored_number);
                        }
                    },
                    10000
                )

                {/foreach}




            </script>



        </div>

        <div id="show_catalogue" class="{if !$content.show_catalogue}hide{/if}">
            <div class="recent_works2">



                <div id="grid-container">
                    <div class="cbp-item">

                        <a href="#" title="custom title 2">
                        <div class="cbp-caption">
                            <div class="cbp-caption-defaultWrap">
                                <img src="http://placehold.it/280x130" alt="" width="100%">
                            </div>
                            <div class="cbp-caption-activeWrap">
                                <span>Caption that is active on hover</span>
                            </div>
                        </div>
                        </a>

                    </div>
                    <div class="cbp-item">
                        <a href="#" title="custom title 2">
                            <img src="http://placehold.it/280x130" alt="custom alt 2" width="100%">
                        </a>
                    </div>
                    <div class="cbp-item">
                        <a href="#" title="custom title 3">
                            <img src="http://placehold.it/280x260" alt="custom alt 3" width="100%">
                        </a>
                    </div>
                    <div class="cbp-item">
                        <a href="#" title="custom title 3">
                            <img src="http://placehold.it/280x130" alt="custom alt 3" width="100%">
                        </a>
                    </div>

                    <div class="cbp-item">
                        <a href="#" title="custom title 1">
                            <img src="http://placehold.it/280x130" alt="custom alt 1" width="100%">
                        </a>
                    </div>
                    <div class="cbp-item">
                        <a href="#" title="custom title 2">
                            <img src="http://placehold.it/280x130" alt="custom alt 2" width="100%">
                        </a>
                    </div>
                    <div class="cbp-item">
                        <a href="#" title="custom title 3">
                            <img src="http://placehold.it/280x260" alt="custom alt 3" width="100%">
                        </a>
                    </div>

                </div>

            </div><!-- end recent works -->

            <div class="clearfix"></div>

        </div>

        <div id="show_what_we_do" class="{if !$content.show_what_we_do}hide{/if}">

            <div class="features_sec32">
                <div class="container">

                    <div class="title2">
                        <h2><span class="line"></span><span class="text">What We Do</span><em>Aipsum therefore always</em></h2>
                    </div>

                    <div class="clearfix margin_top3"></div>

                    <div class="one_third">

                        <div class="box">

                            <span aria-hidden="true" class="icon-screen-desktop"></span>
                            <br/><br/>
                            <h5>Modern Design</h5>
                            <p>Mombined with handful model sentence structures to generate which looks.</p>

                        </div><!-- end section -->

                    </div><!-- end all sections -->

                    <div class="one_third">

                        <div class="box">

                            <span aria-hidden="true" class="icon-social-dropbox"></span>
                            <br/><br/>
                            <h5>Diffrent Websites</h5>
                            <p>Mombined with handful model sentence structures to generate which looks.</p>

                        </div><!-- end section -->

                    </div><!-- end all sections -->

                    <div class="one_third last">

                        <div class="box">

                            <span aria-hidden="true" class="icon-cup"></span>
                            <br/><br/>
                            <h5>Mega Menu</h5>
                            <p>Mombined with handful model sentence structures to generate which looks.</p>

                        </div><!-- end section -->

                    </div><!-- end all sections -->

                </div>
            </div><!-- end features section 3 -->

            <div class="clearfix"></div>

        </div>

        <div id="show_image" class="{if !$content.show_image}hide{/if}">

            <div class="container"><img src="http://placehold.it/1170x320" alt="" class="img_left rimg"></div>

            <div class="clearfix"></div>
        </div>

        <div id="show_register" class="{if !$content.show_register}hide{/if}">


            <div class="parallax_section4">
                <div class="container">

                    <h2>Great Value to Get the Aaika Theme on TF Only.</h2>

                    <p>Packages and web page editors search versions have over the years sometimes.</p>

                    <a href="#" class="button transp2">Read More</a>

                </div>
            </div><!-- end parallax section 4 -->

            <div class="clearfix"></div>

        </div>
        <div id="show_products" class="{if !$content.show_products}hide{/if}">

            <div class="features_sec4">
                <div class="container">

                    <div class="onecol_sixty">

                        <h3 class="unline"><i class="fa fa-comments"></i> Latest Blogs</h3>

                        <div class="clearfix"></div>

                        <div class="lblogs">

                            <div class="lbimg"><img src="http://placehold.it/280x130" alt=""/> <span><strong>DEC</strong> 14</span></div>

                            <h5>Have evolved many web sites</h5>

                            <a href="#" class="smlinks"><i class="fa fa-eye"></i> 45</a>
                            <a href="#" class="smlinks"><i class="fa fa-comment"></i> 18</a>
                            <a href="#" class="smlinks"><i class="fa fa-heart"></i> 89</a>

                            <p>Lorem Ipsum which looks reasonable the generated Ipsum therefore always.</p>

                            <a href="#" class="remobut">Read More</a>

                        </div><!-- end section -->

                        <div class="lblogs last">

                            <div class="lbimg"><img src="http://placehold.it/280x130" alt=""/> <span><strong>DEC</strong> 13</span></div>

                            <h5>Desktop publishing packages</h5>

                            <a href="#" class="smlinks"><i class="fa fa-eye"></i> 25</a>
                            <a href="#" class="smlinks"><i class="fa fa-comment"></i> 3</a>
                            <a href="#" class="smlinks"><i class="fa fa-heart"></i> 10</a>

                            <p>Lorem Ipsum which looks reasonable the generated Ipsum therefore always.</p>

                            <a href="#" class="remobut">Read More</a>

                        </div><!-- end section -->

                    </div><!-- end all sections -->

                    <div class="onecol_forty last">

                        <div class="peosays">

                            <h3 class="unline"><i class="fa fa-users"></i> What People Says</h3>

                            <div class="clearfix"></div>

                            <div id="owl-demo11" class="owl-carousel small four">

                                <div class="box">

                                    <div class="ppimg"><img src="http://placehold.it/80x80" alt=""/> <h6>Kelvin Leonard <em>www.websitenames.com</em></h6></div>

                                    <p>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their
                                        infancy
                                        generators on the versions have evolved over the years.</p>

                                    <span> Rating: &nbsp; <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> </span>

                                </div><!-- end slide -->

                                <div class="box">

                                    <div class="ppimg"><img src="http://placehold.it/80x80" alt=""/> <h6>Maci Cameron <em>www.websitenames.com</em></h6></div>

                                    <p>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their
                                        infancy
                                        generators on the versions have evolved over the years.</p>

                                    <span> Rating: &nbsp; <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> </span>

                                </div><!-- end slide -->

                                <div class="box">

                                    <div class="ppimg"><img src="http://placehold.it/80x80" alt=""/> <h6>Bruce Anderson <em>www.websitenames.com</em></h6></div>

                                    <p>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their
                                        infancy
                                        generators on the versions have evolved over the years.</p>

                                    <span> Rating: &nbsp; <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> </span>

                                </div><!-- end slide -->

                                <div class="box">

                                    <div class="ppimg"><img src="http://placehold.it/80x80" alt=""/> <h6>Katy Elizabeth <em>www.websitenames.com</em></h6></div>

                                    <p>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their
                                        infancy
                                        generators on the versions have evolved over the years.</p>

                                    <span> Rating: &nbsp; <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> </span>

                                </div><!-- end slide -->

                            </div>

                        </div>

                    </div>

                </div>
            </div><!-- end features section 4 -->

            <div class="clearfix"></div>
        </div>


        {include file="theme_1/footer.EcomB2B.tpl"}



    </div>

</div>







<!-- owl carousel -->
<script src="/theme_1/carouselowl/owl.carousel.js"></script>

<!-- basic slider -->
<script type="text/javascript" src="/theme_1/basicslider/bacslider.js"></script>
<script type="text/javascript">
    (function ($) {
        "use strict";

        $(document).ready(function () {
            $(".main-slider-container").sliderbac();
        });

    })(jQuery);
</script>


<!-- animations -->
<script src="/theme_1/animations/js/animations.min.js" type="text/javascript"></script>

<!-- slide panel -->
<script type="text/javascript" src="/theme_1/slidepanel/slidepanel.js"></script>



<!-- MasterSlider -->
<script src="/theme_1/masterslider/jquery.easing.min.js"></script>
<script src="/theme_1/masterslider/masterslider.min.js"></script>

<script type="text/javascript">
    (function ($) {
        "use strict";

        var slider = new MasterSlider();
        // adds Arrows navigation control to the slider.
        slider.control('arrows');
        slider.control('bullets');

        slider.setup('masterslider', {
            width: 1400,    // slider standard width
            height: 750,   // slider standard height
            space: 0, speed: 45, loop: false, preload: 0, autoplay: false, view: "basic"
        });

    })(jQuery);
</script>


<!-- tabs -->
<script src="/theme_1/tabs/assets/js/responsive-tabs.min.js" type="text/javascript"></script>

<!-- Accordion-->
<script type="/text/javascript" src="/theme_1//accordion/jquery.accordion.js"></script>
<script type="/text/javascript" src="/theme_1//accordion/custom.js"></script>


<!--
<script type="text/javascript" src="/theme_1/js/universal/custom.js"></script>
-->

<script type="text/javascript">
    jQuery(document).ready( function() {
        jQuery('#grid-container').cubeportfolio({
            // options
        });
    });
</script>




</body>

</html>

