{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 April 2018 at 20:44:38 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>



    .page_not_found {
        padding: 50px 30px 58px 30px;
        margin: 0 auto;
        width: 59%;
        background-color: #fff;
        border: 1px solid #eee;
        border-bottom: 5px solid #eee;
        text-align: center;
    }
    .page_not_found strong {
        display: block;
        font-size: 145px;
        line-height: 100px;
        color: #e3e3e3;
        font-weight: normal;
        margin-bottom: 10px;
        text-shadow: 5px 5px 1px #fafafa;
    }
    .page_not_found b {
        display: block;
        font-size: 40px;
        line-height: 50px;
        color: #999;
        margin: 0;
        font-weight: 300;
    }
    .page_not_found em {
        display: block;
        font-size: 18px;
        line-height: 59px;
        color: #e54c4c;
        margin: 0;
        font-style: normal;
    }

    .separator{
        margin-bottom:40px
    }

</style>


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}" style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >


    <div class="page_not_found">

        <strong id="_strong_title" contenteditable="true">{$data.labels._strong_title}</strong>
        <br/>
        <b id="_title"  contenteditable="true">{$data.labels._title}</b>
        <em id="_text"  contenteditable="true">{$data.labels._text}</em>
        <p id="_home_guide"  contenteditable="true">{$data.labels._home_guide}</p>
        <div class="clear separator"></div>
        <a href="#" class="real_button"><span class="fa fa-home fa-lg"></span>&nbsp; <span id="_home_label"  contenteditable="true">{$data.labels._home_label}</span></a>

    </div>


</div>



<script>




</script>




