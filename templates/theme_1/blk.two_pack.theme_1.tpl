{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 July 2017 at 01:42:28 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} ">

    <div class="clearfix marb12"></div>

        <div class="container">

            <div class="one_half">

                <input style="display:none" type="file" block_key="{$key}" name="{$data.type}" id="update_image_{$key}" class="image_upload" data-options='{ }'/>
                <label style="margin-left:10px;font-weight: normal;cursor: pointer;width:100%"  for="update_image_{$key}">
                    <img   class="button _image rimg" image_key="{$data._image_key}"  src="{if $data._image!=''}{$data._image}{else}/art/image_562x280.png{/if}" alt="" >
                </label>

            </div>

            <div class="one_half last">

                <div class="stcode_title5">
                    <h3 class="nmb"><strong class="_title" contenteditable="true">{$data._title}</strong></h3>
                </div>

                <h5 class="gray _subtitle"  contenteditable="true">{$data._subtitle}</h5>

                <p class="_text" contenteditable="true">{$data._text}</p>

                <div class="clearfix marb12"></div>

            </div>

        </div>


        <div class="clearfix"></div>
</div>

