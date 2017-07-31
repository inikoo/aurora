{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2017 at 20:00:44 CEST, Trnava, Slovakia
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
            <div class="content text-center mx-auto">


                <input id="search_input" placeholder="search" style="width:80%;padding:5px 10px;font-size:140%" value="{$search_query}"/> <i id="search_icon" class="fa fa-search" style="margin-left:10px;font-size:140%;cursor:pointer" aria-hidden="true"></i>

                <div class="clearfix divider_line9 lessm"></div>

            </div>

            </div>
            <div class="container">

                <div id="search_results" class="">






                </div>


                <div class="clearfix marb12"></div>


            </div>


            <script>
                {if $search_query!=''}

                    search($('#search_input').val())

                {/if}
            </script>

        </div>


        <div class="clearfix marb12"></div>

        {include file="theme_1/footer.EcomB2B.tpl"}


    </div>

</div>


</body>

</html>



