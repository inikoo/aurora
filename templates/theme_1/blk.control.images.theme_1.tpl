{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 December 2017 at 09:18:33 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<style>
    #images_layout_ideas{
        width: 630px;
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        padding: 10px 20px 20px 20px;
        z-index: 3000;
    }
    #images_layout_ideas div.options {

        display: flex;


        flex-wrap: wrap
    }

    #images_layout_ideas div.options img {
        padding: 5px;
        width: 200px;height: 80px;

    }
</style>

<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div style="float:left;margin-right:20px;min-width: 200px;">
        <div style="float:left;min-width: 200px;position: relative;top:2px">
            <i class="fa fa-fw {$block.icon}" style="margin-left:10px" aria-hidden="true" title="{$block.label}"></i>
            <span class="label">{$block.label}</span>
        </div>


        <span style="position: relative;top:1px"  id="open_images_layout_ideas" onclick="change_images_template()" class="button"  ><i class="fa fa-lightbulb-o" aria-hidden="true"></i>  {t}Layout ideas{/t}</span>


    </div>
    <div style="clear: both"></div>
</div>


<div id="images_layout_ideas" class="hide">
    <div style="text-align: right;margin-bottom: 10px;padding-right: 5px">
        <i class="fa fa-window-close button" onclick="$('#images_layout_ideas').addClass('hide')"></i>
    </div>

    <div class="options">
        <img class="button" template="1" src="/art/images_layout_1.png">
        <img class="button" template="2" src="/art/images_layout_2.png">
        <img class="button" template="3" src="/art/images_layout_3.png">
        <img class="button" template="4" src="/art/images_layout_4.png">
        <img class="button" template="12" src="/art/images_layout_12.png">
        <img class="button" template="21" src="/art/images_layout_21.png">
        <img class="button" template="13" src="/art/images_layout_13.png">
        <img class="button" template="31" src="/art/images_layout_31.png">
        <img class="button" template="211" src="/art/images_layout_211.png">


    </div>

</div>

<script>

    $('#images_layout_ideas').on('click', 'img', function () {

        var template=$(this).attr('template')
        $('#open_images_layout_ideas').attr('src','/art/images_layout_'+template+'.png').attr('template',template)
        $('#images_layout_ideas').addClass('hide')
    });

    function change_images_template() {
        $('#images_layout_ideas').removeClass('hide')

    }
</script>