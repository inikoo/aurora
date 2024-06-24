{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2017 at 20:00:44 CEST, Trnava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{if !$data.show}hide{/if}" style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

    <div class="container">
        <div class="content text-center mx-auto">
            <input id="search_input" placeholder="search" style="width:80%;padding:5px 10px;font-size:140%" value=""/>
            <i id="search_icon" class="fa fa-search" style="margin-left:10px;font-size:140%;cursor:pointer" aria-hidden="true"></i>
        </div>
    </div>
    
    <div class="container" style="clear: both;margin-top: 30px">
        <div id="search_results" >
        </div>
    </div>

</div>

