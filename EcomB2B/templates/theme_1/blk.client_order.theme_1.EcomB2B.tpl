{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  09 February 2020  00:34::46  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

<style>
.order_showcase{
    display:flex;
    border-bottom:1px solid #ccc;
    padding:5px 15px

}

    .client_name{
        font-weight:400;
        color:#555
    }

</style>

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}

{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type}  {if !$data.show}hide{/if}" style="padding-bottom:{$bottom_margin}px">

    <div class="order_showcase" style="margin-bottom:{$top_margin}px;padding-top:10px">






    </div>
    
</div>

