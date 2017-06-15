{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 June 2017 at 14:51:32 GMT+7, Phuket, Thailand
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div id="webpage_section_{$block_name}" class="webpage_section {if $show_block!=1}hide{/if}"   section="{$block_name}"  data-type="{$content['intro'].title}"  >
    <div class="container">

        <div class="one_half">


            <div>
                <div style="position:absolute;top:-20px;font-size:10px">562x280</div>
                <input style="display:none" type="file" class="standard_image" name="standard_image_{$block_name}" id="standard_image_{$block_name}"
                    data-parent=""
                    data-_parent="webpage"
                    data-parent_key="{$webpage->id}"
                    data-parent_object_scope="standard_image"
                    data-options='{ "width":"562", "height":"280"}'

                />
                <label for="standard_image_{$block_name}">
                    <img id="ws_intro_image" class="standard_image" src="{if $content['intro'].image==''}http://placehold.it/562x280{else}{$content['intro'].image}{/if}"  data-image_key="" data-src="{$content['intro'].image}" alt="" width="562" height="280" class="rimg"/>
                </label>
            </div>


        </div>

        <div class="one_half last">

            <div class="stcode_title5">
                <h3 class="nmb"><strong contenteditable="true" id="ws_intro_title">{$content['intro'].title}</strong></h3>
            </div>

            <h5 class="gray" contenteditable="true" id="ws_intro_sub_title">{$content['intro'].sub_title}</h5>

            <p contenteditable="true" id="ws_intro_text">{$content['intro'].text}</p>

            <div class="clearfix marb12"></div>
        </div>

    </div>

</div>

<script>

    function get_intro_section_data(){


        var intro_data = {
            'type': '50_50', 'image': '','image_key': '', 'title': '', 'sub_title': '', 'text': '', 'class_title': '', 'class_sub_title': '', 'class_text': ''
        }

        intro_data.type=$('#webpage_section_intro').data('type')

        intro_data.image=$('#ws_intro_image').data('src')
        intro_data.image_key=$('#ws_intro_image').data('image_key')

        intro_data.title=$('#ws_intro_title').html()
        intro_data.sub_title=$('#ws_intro_sub_title').html()
        intro_data.text=$('#ws_intro_text').html()

        return intro_data;

    }

</script>



