{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 March 2018 at 12:26:55 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div class="unselectable" style="float:left;margin-right:20px;min-width: 200px;">



        <span   id="open_text_layout_ideas" onclick="change_text_template()" class="button unselectable"  ><i class="fa fa-columns" aria-hidden="true"></i>  {t}Change layout{/t}</span>
        <span style="margin-left:50px">{t}Margin{/t}:</span>
        <input data-margin="top" class=" edit_margin top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}"  placeholder="0"><input data-margin="bottom" class=" edit_margin bottom" value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}" style="" placeholder="0">

    </div>
    <div style="clear: both"></div>

</div>




<script>

    $('#text_layout_ideas').on('click', 'img', function () {

        var template=$(this).attr('template')





        $("#preview")[0].contentWindow.change_text_template('{$key}',template);



        $('#text_layout_ideas').addClass('hide')

        $('#save_button').addClass('save button changed valid')


    });


    function change_text_template() {

        console.log('caca')

        $('#text_layout_ideas').removeClass('hide')


        $('#preview').contents().find('#header').removeClass('hide')



    }

</script>