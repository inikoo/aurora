{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 13:19:19 GMT+8, Kuala Lumpur,  Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tpl"}

<body xmlns="http://www.w3.org/1999/html">
{include file="analytics.tpl"}

<div class="wrapper_boxed">

    <div class="site_wrapper">
            {include file="theme_1/header.theme_1.EcomB2B.tpl"}


        <div class="content_fullwidth less2">

            <div class="container">

                <div class="error_pagenotfound">

                    <strong id="_strong_title">{$content._strong_title}</strong>
                    <br/>
                    <b id="_title">{$content._title}</b>

                    <em id="_text">{$content._text}</em>


                    <div id="_link_div" class="hide">
                            <p id="_link_guide">{$content._link_guide}</p>

                            <div class="clearfix margin_top3"></div>

                            <a href="" class="but_medium1"><span style="font-style:italic">{t}Webpage name{/t}</span> <span style="margin-left:5px" class="fa fa-share fa-lg"></span></a>

                        </div>

                        <div id="_home_div">
                            <p id="_home_guide">{$content._home_guide}</p>

                            <div class="clearfix margin_top3"></div>

                            <a href="/" class="but_medium1"><span class="fa fa-home fa-lg"></span>&nbsp; <span id="_home_label">{$content._home_label}</span></a>
                        </div>


                </div><!-- end error page notfound -->

            </div>


            <div class="clearfix marb12"></div>


        </div>
    
     {include file="theme_1/footer.theme_1.EcomB2B.tpl"}
    </div>


   

</body>
{include file="theme_1/bottom_scripts.theme_1.EcomB2B.tpl"}</body>

</html>

