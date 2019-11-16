{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:17 July 2017 at 10:46:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div style="{if $data.bg_image!==''}background-image:url('{$data.bg_image}'){/if}">
    <div class="content center" style="text-align: center;padding:20px 15px">
        <h3 class="color-white center">{$data.title}</h3>
        <p style="text-align: center" class="single_line_height center color-white">{$data.text}</p>
        <a href="{$data.link}" class="button button-round button-blue">{$data.button_label}</a>

    </div>
</div>

<div style="clear: both"></div>
