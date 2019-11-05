{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  05 November 2019  18:10::40  +0100, Mijas Costa, Spain
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}

<style>
    body, html {
        height: 800px;
        margin: 0;
    }

    .wrapper_boxed, .site_wrapper, #blocks, ._block {
        width: 100%;
        margin: auto auto auto auto;
        height: 100%;
    }

    .big_img {
        background-image: url("{$data.image}");
        height: 100%;
        background-position: center;
        background-size: cover;
        position: relative;
        color: white;
        font-family: "Courier New", Courier, monospace;
        font-size: 25px;

    }

    .top_left {
        position: absolute;
        top: 15px;
        left: 16px;
    }

    .bottom_left {
        position: absolute;
        bottom: 20px;
        left: 16px;
    }

    .middle {
        position: absolute;
        top: 25%;
        left: 50%;
        transform: translate(-50%, -25%);
        text-align: center;
        width: 70%;
        line-height: normal;
    }



</style>

<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="_block {if !$data.show}hide{/if}"   >


<div class="block big_img" data-img="{$data.image}" >
    <div class="top_left">
        <span id="_title" contenteditable="true">{if !empty($data.labels._title)}{$data.labels._title}{else}{t}We're launching soon{/t}{/if}</span>

    </div>
    <div class="middle">
        <span id="_text" contenteditable="true">{if !empty($data.labels._text)}{$data.labels._text}{else}{t}Our website is under construction. We'll be here soon with our new awesome site{/t}{/if}</span>

    </div>
    <div class="bottom_left">
        <span id="_footer" contenteditable="true">{if !empty($data.labels._footer)}{$data.labels._footer}{else}{t}Thanks{/t}{/if}</span>
    </div>
</div>

</div>




