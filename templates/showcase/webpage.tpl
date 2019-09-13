{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 June 2016 at 10:36:01 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<div class="webpage_showcase" style="padding: 0px">



    <div class="container" style="min-height:200px;padding:5px 0px">


        <div class="block picture">

            <div style="clear:both">
            </div>
            <div class="data_container">
                {assign "image_key" $webpage->properties('desktop_screenshot')}
                <div style="height: auto" id="main_image" class="wraptocenter main_image ">
                    <img border=1 style="border:1px solid #ccc" src="/{if $image_key}wi.php?id={$image_key}&amp;s=270x270{else}art/webpage_empty_screenshot.jpg{/if}"> </span>
                </div>
            </div>
            <div style="clear:both">
            </div>


        </div>

        <div class="block sales_data">
        </div>

        <div class="block info">
            <div id="overviews">


            </div>
        </div>
        <div style="clear:both">
        </div>
    </div>

</div>