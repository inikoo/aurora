{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 July 2017 at 00:13:06 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} ">

            <div class="parallax_section4  button_block " button_bg="{$data.bg_image}" style="background-image:url('{if $data.bg_image!=''}{$data.bg_image}{else}https://placehold.it/1240x750{/if}')">
                <div class="container">
                    <h2 contenteditable="true" class="_title">{$data.title}</h2>
                    <p  contenteditable="true" class="_text">{$data.text}</p>
                    <a href="{$data.link}" class="button transp2 _button " contenteditable="true"  link="{$data.link}" >{$data.button_label}</a>

                </div>
            </div>
            <div class="clearfix"></div>
</div>

