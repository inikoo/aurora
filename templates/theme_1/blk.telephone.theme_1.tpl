{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 July 2017 at 01:52:34 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    .telephone_block {
        float: left;
        width: 100%;
        padding-bottom:7px;
        background: #fff;
        text-align: center;
    }
    .telephone_block h2 {

        font-weight: 300;
        margin-bottom: 40px;
        color: #333;
    }
    .telephone_block strong {
        color: #fff;
        font-size: 35px;
        font-weight: 600;
        background: #333;
        padding: 5px 20px;
        margin-right: 20px;
    }
    .telephone_block em {

        font-size: 25px;
        color: #999;
        font-weight: normal;
    }



</style>


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "40"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "40"}{/if}

<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">
      
        <div class="telephone_block two">


                <h2 class="_title" contenteditable="true">{$data._title}</h2>

                <strong class="_telephone" contenteditable="true"   >{$data._telephone}</strong>
                    <em class="_text" contenteditable="true">{$data._text}</em>
                    <!--  {if $data._telephone=='#tel'}{$store->get('Telephone')}{else}{$data._telephone}{/if}   -->
        </div>


        
        <div class="clear"></div>
      
</div>

