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


        <span   id="open_images_layout_ideas" onclick="change_images_template()" class="button"  ><i class="fa fa-lightbulb-o" aria-hidden="true"></i>  {t}Layout ideas{/t}</span>
        <span style="margin-left:50px">{t}Margin{/t}:</span>  {t}top{/t} <input margin="top" class="image_margin" value="{if isset($block.top_margin)}{$block.top_margin}{/if}" style="width: 40px" placeholder="0"> {t}bottom{/t} <input  margin="bottom" class="image_margin" value="{if isset($block.bottom_margin)}{$block.bottom_margin}{/if}" style="width: 40px" placeholder="0">

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


        console.log(template)

        $('#preview').contents().find('.blk_images').html($('#preview').contents().find('#template_'+template).html())






        $('#images_layout_ideas').addClass('hide')

        $('#save_button').addClass('save button changed valid')


    });

    function change_images_template() {
        $('#images_layout_ideas').removeClass('hide')


        $('#preview').contents().find('#header').removeClass('hide')



    }

    $(document).on('input propertychange', '.image_margin', function (evt) {


        if(!validate_signed_integer($(this).val(),50)){
            $(this).removeClass('error')
            var value=$(this).val()

        }else{
            value=0;

            $(this).addClass('error')
        }


        if($(this).attr('margin')=='top'){
            $("#preview").contents().find('#block_{$key}').find('.blk_images').attr('top_margin',value).css( "margin-top",value+"px")
        }else if($(this).attr('margin')=='bottom'){
            $("#preview").contents().find('#block_{$key}').find('.blk_images').attr('botton_margin',value).css( "margin-bottom",value+"px")
        }


        $('#save_button').addClass('save button changed valid')



        console.log(value)


    });


</script>