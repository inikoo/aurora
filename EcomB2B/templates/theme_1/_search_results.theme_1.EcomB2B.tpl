{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 July 2017 at 08:13:56 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



{counter start=-1 print=false assign="counter"}
    {foreach from=$results item="result" }

        <div class="content text_blocks text_template_13">

            <div class="text_block_container" style="position: relative;text-align: center">


                <a href="{$result.url}"
                    {if $result.scope=='Product'}
                    {counter print=false assign="counter"}
                   data-analytics='{
                   "id":"{$result.code}",
                   "name":"{$result.name|escape:quotes}",
                   "category":"{$result.family_code}",
                    {if isset($result.raw_price)}"price":"{$result.raw_price}",{/if}
                    "position":{$counter}
                    }'
                   data-list="Search"
                   onclick="go_product(this); return !ga.loaded;"
                    {/if}
                ><img style="max-height: 150px" src="{$result.image}"/></a>
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
