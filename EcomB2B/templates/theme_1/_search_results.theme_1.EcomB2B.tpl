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

        <div class="content text_blocks text_template_13">

            <div class="text_block_container" style="position: relative;text-align: center">

                <a href="{$result.url}"><img style="max-height: 150px" src="{$result.image}"/></a>
            </div>

            <div class="text_block_container" style="position: relative">

                <div class="text_block">
                    <h5 style="margin-bottom: 10px"><a href="{$result.url}">{$result.title}</a></h5>
                    {$result.description}
                    </p>
                </div>
            </div>

        </div>
        <div class="clear"></div>

    {/foreach}
