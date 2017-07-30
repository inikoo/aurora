{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 July 2017 at 09:02:44 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tpl"}

<body xmlns="http://www.w3.org/1999/html">


<div class="wrapper_boxed">

    <div class="site_wrapper">

        {include file="theme_1/header.EcomB2B.tpl"}

        <div class="content_fullwidth less2">
            <div class="container">

                <div class="error_pagenotfound">

                    <strong id="_strong_title" >{$content._strong_title}</strong>
                    <br/>
                    <b id="_title"  >{$content._title}</b>

                    <em id="_text"  >{$content._text}</em>

                    <p id="_home_guide"  >{$content._home_guide}</p>

                    <div class="clearfix margin_top3"></div>

                    <a href="index.php" class="but_medium1"><span class="fa fa-home fa-lg"></span>&nbsp; <span id="_home_label"  >{$content._home_label}</span></a>

                </div><!-- end error page notfound -->

            </div>
        </div>
        <div class="clearfix marb12"></div>
        {include file="theme_1/footer.EcomB2B.tpl"}
    </div>

</div>
</body>
</html>






 