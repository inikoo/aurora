{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Fri 25 Oct 2019 21:24:48 +0800 MYT, Kuala Lumpur , Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type} {if !$data.show}hide{/if}" data-ar_url="/ar_web_catalogue.php" style="padding-top:0;padding-bottom:{$bottom_margin}px">

    <div class="portfolio_sub_block " >
        <div class="table_top">
            <span class="title">{if empty($data.labels.title)}{t}Catalogue{/t}{else}{$data.labels.title}{/if}</span>
        </div>
        <div class="tabs catalogue_tabs">
            <span  data-scope="departments" class="hide tab  departments">{if empty($data.labels.departements)}{t}Departments{/t}{else}{$data.labels.departements}{/if}</span>
            <span  data-scope="families" class="hide tab families">{if empty($data.labels.families)}{t}Families{/t}{else}{$data.labels.families}{/if}</span>
            <span  data-scope="products" class="hide tab products">{if empty($data.labels.products)}{t}Products{/t}{else}{$data.labels.products}{/if}</span>
        </div>

        <div id="table_container"></div>
    </div>


</div>

