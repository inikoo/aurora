{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 July 2017 at 08:13:56 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}




    {foreach from=$results item="result" }

        <div class="blog_post">
            <div class="blog_postcontent">

                <ul class="post_meta_links" >
                    <li><a href="{$result.url}" class="date">{$result.code}</a></li>
                    <li class="hide post_by"><i>by:</i> <a href="#">Adam Harrison</a></li>
                    <li class="hide post_categoty"><i>in:</i> <a href="#">Web tutorials</a></li>
                    <li class="hide post_comments"><i>tags:</i> <a href="#">tag1</a><a href="#">tag2</a></li>
                </ul>
                <div class="clearfix"></div>
                <div class="margin_top1"></div>

                <div class="three_fourth">
                    <div class="pull-left " style="width: 150px" >
                    <img src="{$result.image}"  style="max-width: 100px;max-height: 100px" alt=""   style="margin-right: 50px;"  />
                    </div>
                    <p  style="padding-left: 150px;margin-left: 150px" >
                    <h5 style="margin-bottom: 10px"><a href="{$result.url}">{$result.title}</a></h5>
                        {$result.description}
                    </p>
                </div>
                <div class="hide one_fourth last">
                    {t}Price{/t} £1.00 <br>
                    <input> <button>Buy</button>

                </div>
            </div>
        </div><!-- /# end post -->

        <div class="clearfix divider_line9 lessm"></div>

    {/foreach}
