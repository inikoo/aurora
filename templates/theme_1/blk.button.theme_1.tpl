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

            <div class="parallax_section4">
                <div class="container">
                    <h2 contenteditable="true" class="_title">{$data.title}</h2>
                    <p  contenteditable="true" class="_text">{$data.text}</p>
                    <a href="{$data.link}" class="button transp2 _button " contenteditable="true"  link="{$data.link}" >{$data.button_label}</a>

                </div>
            </div>
            <div class="clearfix"></div>
</div>

