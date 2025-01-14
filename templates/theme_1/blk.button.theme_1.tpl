{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 July 2017 at 00:13:06 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    .fancy_button {
        float: left;
        width: 100%;
        text-align: center;
        padding: 140px 0px;
        background-color: #333;
        background-attachment: fixed;
        background-origin: initial;
        background-clip: initial;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: 100% 0;
        background-position: center; }

    .fancy_button h2 {
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        line-height: 38px; }

    .fancy_button p {
        color: #fff;
        font-size: 16px;
        margin-bottom: 50px; }

</style>

<div id="block_{$key}" block="{$data.type}" class=" _block {if !$data.show}hide{/if} ">

    <div class="fancy_button  button_block " button_bg="{$data.bg_image}" style="background-image:url('{if $data.bg_image!=''}{$data.bg_image}{else}https://placehold.co/1240x750.png{/if}')">
        <div class="container">
            <h2 contenteditable="true" class="_title">{$data.title}</h2>
            <p contenteditable="true" class="_text">{$data.text}</p>
            <a href="{$data.link}" class="real_button _button " contenteditable="true" link="{$data.link}">{$data.button_label}</a>

        </div>
    </div>
    <div class="clean"></div>
</div>

