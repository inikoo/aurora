{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:17 July 2017 at 10:46:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block ">

            <div class="parallax_section4" style="{if $data.bg_image!==''}background-image:url('{$data.bg_image}'){/if}">
                <div class="container">
                    <h2>{$data.title}</h2>
                    <p>{$data.text}</p>
                    <a href="{$data.link}" class="button transp2 _button "  >{$data.button_label}</a>

                </div>
            </div>
            <div class="clearfix"></div>
</div>

