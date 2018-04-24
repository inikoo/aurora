{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:17 July 2017 at 10:46:13 GMT+8, Kuala Lumpur, Malaysia
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

<div id="block_{$key}" >
    <div class="fancy_button" style="{if $data.bg_image!==''}background-image:url('{$data.bg_image}'){/if}">
        <div class="container">
            <h2>{$data.title}</h2>
            <p>{$data.text}</p>
            <a href="{$data.link}" class="real_button">{$data.button_label}</a>
        </div>
    </div>
    <div class="clean"></div>
</div>

