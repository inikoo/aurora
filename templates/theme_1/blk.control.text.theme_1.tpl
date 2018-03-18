{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 March 2018 at 12:26:55 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<style>
    #text_layout_ideas{
        width: 630px;
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        padding: 10px 20px 20px 20px;
        z-index: 3000;
    }
    #text_layout_ideas div.options {

        display: flex;


        flex-wrap: wrap
    }

    #text_layout_ideas div.options img {
        padding: 5px;
        width: 200px;height: 80px;

    }
</style>

<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div style="float:left;margin-right:20px;min-width: 200px;">



        <span   id="open_text_layout_ideas" onclick="change_text_template()" class="button unselectable"  ><i class="fa fa-columns" aria-hidden="true"></i>  {t}Change layout{/t}</span>
        <span style="margin-left:50px">{t}Margin{/t}:</span>  <i class="fa fa-long-arrow-up" aria-hidden="true" title="{t}top{/t}"></i> <input margin="top" class="image_margin" value="{if isset($block.top_margin)}{$block.top_margin}{else}20{/if}" style="width: 30px" placeholder="0"> <i class="fa fa-long-arrow-down" aria-hidden="true" title="{t}bottom{/t}"></i> <input  margin="bottom" class="image_margin" value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}20{/if}" style="width: 30px" placeholder="0">

    </div>
    <div style="clear: both"></div>
</div>


<div id="text_layout_ideas" class="hide">
    <div style="text-align: right;margin-bottom: 10px;padding-right: 5px">
        <i class="fa fa-window-close button" onclick="$('#text_layout_ideas').addClass('hide')"></i>
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

    $('#text_layout_ideas').on('click', 'img', function () {

        var template=$(this).attr('template')





        $("#preview")[0].contentWindow.change_text_template('{$key}',template);



        $('#text_layout_ideas').addClass('hide')

        $('#save_button').addClass('save button changed valid')


    });

    function change_text_template() {
        $('#text_layout_ideas').removeClass('hide')


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
            $("#preview").contents().find('#block_{$key}').find('._text').attr('top_margin',value).css( "padding-top",value+"px")
        }else if($(this).attr('margin')=='bottom'){
            $("#preview").contents().find('#block_{$key}').find('._text').attr('botton_margin',value).css( "padding-bottom",value+"px")
        }


        $('#save_button').addClass('save button changed valid')



        console.log(value)


    });


</script>