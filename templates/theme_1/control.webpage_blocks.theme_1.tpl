{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2017 at 08:35:49 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>



</style>

<div class=" edit_block_buttons  " style="width:1100px;" >
    <ul id="columns" class="sortable2 columns " >



    {foreach from=$content.blocks item=$block key=key}

        <li class="column  unselectable  {if !$block.show}very_discreet{/if}"  key="{$key}">
            <span class="button open_edit">
            <i class="fa   {$block.icon}" aria-hidden="true"></i>
             <span class="label  ">{$block.label}</span>
            </span>
            <i class="fa button  {if $block.show}fa-eye{else}fa-eye-slash{/if} block_show" aria-hidden="true"></i>
            <i class="fa handle2 fa-arrows" aria-hidden="true"></i>
        </li>
    {/foreach}
    </ul>

    <div id="edit_columns" class="xhide">

        <div style="float:left">
            <i id="edit_prev_column" onClick="edit_prev_column(this)" key="" class="edit_column_button fa button fa-arrow-left " aria-hidden="true"></i>
            <i id="exit_edit_column" style="margin-left:5px;margin-right: 5px"  onClick="exit_edit_column(this)" key="" class="edit_column_button fa button fa-window-close fa-flip-horizontal " aria-hidden="true"></i>
            <i id="edit_next_column" style="margin-right: 10px" onClick="edit_next_column(this)" key="" class="edit_column_button fa button fa-arrow-right " aria-hidden="true"></i>
        </div>


        {foreach from=$content.blocks item=$block key=key}
            <div id="edit_mode_{$key}" class=" edit_mode "  type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px" >
                <div style="float:left;margin-right:20px;min-width: 200px;">




                    <div style="float:left;min-width: 200px">
                    <i class="fa fa-fw {$block.icon}" style="margin-left:10px" aria-hidden="true" title="{$block.label}"></i>
                    <span class="label">{$block.label}</span>
                    </div>



                        {if $block.type=='sliders'}


                            <div style="float:left">
                            {foreach from=$block.sliders key=slider_key  item=slider name=sliders}
                                <div   key="{$slider_key}"  style="" class="button slider_preview   "></div>




                            {/foreach}
                            </div>


                            {foreach from=$block.sliders key=slider_key  item=slider name=sliders}
                            <div id="slider_preview_options_{$slider_key}" class="hide slider_preview_options" style="float:left;height: 22px;line-height: 22px">
                                <span class="button" style="margin-left:50px;margin-right: 20px"><i class="fa fa-television" aria-hidden="true"></i> {t}Background{/t}</span>

                                <i class="fa fa-align-center" aria-hidden="true" style="margin-right: 5px" ></i>
                                <i class="fa fa-link" aria-hidden="true"  style="margin-right: 5px"></i>
                                <i class="fa fa-youtube-play" aria-hidden="true" title="{t}Button{/t}"  style="margin-right: 5px"></i>
                                <i class="fa fa-arrows-alt " aria-hidden="true" title="{t}Click anywhere{/t}"  style="margin-right: 5px"></i>

                            </div>
                            {/foreach}


                        {/if}

                </div>

                <div style="clear: both"></div>

            </div>
        {/foreach}



    </div>


    <div style="clear:both"></div>

</div>


<div class="hide">

<span id="edit_slider" class="webpage_block_label active_label  {if !$content.show_slider==1}very_discreet{else}button{/if}">
    <i class="fa fa-fw fa-smile-o discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Intro{/t} </span>
<i id="show_slider" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_slider==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
</span>






<span class="edit_features_buttons edit_block_buttons  ">
        <i class="fa fa-th-large discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Features{/t}
    <i id="show_features" onClick="change_webpage_element_visibility(this)" class=" fa button fa-check {if $content.show_features==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_counter_buttons edit_block_buttons  ">
        <i class="fa fa-sort-numeric-asc   discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Counter{/t}
    <i id="show_counter" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_counter==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_catalogue_buttons edit_block_buttons  ">
        <i class="fa fa-shopping-bag   discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Catalogue{/t}
    <i id="show_catalogue" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_counter==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_what_we_do_buttons edit_block_buttons  ">
        <i class="fa fa-diamond discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Why us{/t}
    <i id="show_what_we_do" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_counter==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_image_buttons edit_block_buttons  ">
        <i class="fa fa-picture-o discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Image{/t}
    <i id="show_image" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_image==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_register_buttons edit_block_buttons  ">
        <i class="fa fa-sign-in  discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Register{/t}
    <i id="show_register" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_register==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_products_buttons edit_block_buttons  ">
        <i class="fa fa-cube  discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Products{/t}
    <i id="show_products" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_products==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>

</div>
<script>

    $(document).on('click', '.slider_preview', function (e) {



        if(!$(this).hasClass('selected')){
            $('.slider_preview').removeClass('selected')
            $(this).addClass('selected')
            $('.slider_preview_options').addClass('hide')
            $('#slider_preview_options_'+$(this).attr('key')).removeClass('hide')

            // $('#preview')[0].contentWindow.change_slider($(this).attr('key'))

        }

    })



    function edit_next_column(element){

        var next_key=parseFloat($('#exit_edit_column').attr('key'))+1
        exit_edit_column($('#exit_edit_column'))
        var next= $('#edit_mode_'+next_key)


        console.log(next_key)

        if(next.length){
            edit_column(next_key)
        }else{
            edit_column(0)
        }
    }

    function edit_prev_column(element){

        var prev_key=parseFloat($('#exit_edit_column').attr('key'))-1


        exit_edit_column($('#exit_edit_column'))


        if(prev_key>=0){


            edit_column(prev_key)
        }else{

//console.log( $('#edit_columns .edit_mode:last'))

            edit_column( $('#edit_columns .edit_mode:last').attr('key'))

        }


    }

    $('.sortable2').sortable({
        handle:'.handle2',
        start: function (event, ui) {
            pre = ui.item.index();
        }, stop: function (event, ui) {

            post = ui.item.index();
            $('#preview')[0].contentWindow.move_column_label(pre,post);
        }


    });


    $(document).on('click', '.block_show', function (e) {



        if($(this).hasClass('fa-eye')){
            var key=$(this).removeClass('fa-eye').addClass('fa-eye-slash').closest('li').addClass('very_discreet').attr('key')


            //$('#preview')[0].contentWindow.hide_column_label(key);
            //$('#preview')[0].contentWindow.hide_column(key);

        }else{
            var key=$(this).addClass('fa-eye').removeClass('fa-eye-slash').closest('li').removeClass('very_discreet').attr('key')
            //$('#preview')[0].contentWindow.show_column_label(key);
            //$('#preview')[0].contentWindow.show_column(key);
        }


        $('#save_button').addClass('save button changed valid')


    })


    $(document).on('click', '.open_edit', function (e) {

        var key=$(this).closest('li').attr('key')


        if($(this).closest('li').find('.block_show').hasClass('fa-eye')){
            edit_column(key)
        }



    })





    function edit_column(key) {




        $('#columns').addClass('hide')
        $('#edit_columns').removeClass('hide')

        $('.edit_mode').addClass('hide')
        $('#edit_mode_'+key).removeClass('hide')


        $('#exit_edit_column').attr('key',key)

        //$('#preview')[0].contentWindow.show_column(key);

        $('.options_dialog').addClass('hide')




    }

    function exit_edit_column(element) {
        $('#columns').removeClass('hide')
        $('#edit_columns').addClass('hide')


        $('.options_dialog').addClass('hide')


        //$('#preview')[0].contentWindow.hide_column($(element).attr('key'));


    }




    $("#edit_slider").hover(function () {
        if (!$(this).hasClass('editing')) {
            $(this).find('i').removeClass("fa-smile-o").addClass('fa-pencil-square-o')
        }
    }, function () {

        if (!$(this).hasClass('editing')) {
            $(this).find('i').removeClass("fa-pencil-square-o discreet").addClass('fa-smile-o')
        }


    });


    $("#edit_slider").click(function () {
        if (!$(this).hasClass('editing')) {
            $('.edit_block_buttons').addClass('hide')

            $(this).closest('.edit_block_buttons').removeClass('hide')

            $('#edit_slider_buttons').removeClass('hide')

            $(this).addClass('editing').find('i').removeClass("discreet fa-smile-o ").addClass('fa-pencil-square-o')
            $('#preview')[0].contentWindow.edit_slider()


        } else {
            $('.edit_block_buttons').removeClass('hide')


            $('#edit_slider_buttons').addClass('hide')



            $(this).removeClass('editing').find('i').removeClass("discreet fa-pencil-square-o ").addClass('fa-smile-o')
            $('#preview')[0].contentWindow.close_edit_slider()

        }

    });


    function change_webpage_element_visibility(element) {


        if ($(element).hasClass('success')) {
            $(element).removeClass('success').addClass('very_discreet')

            $(element).prev('.webpage_block_label').addClass('very_discreet')

            $('#preview')[0].contentWindow.change_webpage_element_visibility($(element).attr('id'), 'hide')


        } else {
            $(element).addClass('success').removeClass('very_discreet')
            $('#preview')[0].contentWindow.change_webpage_element_visibility($(element).attr('id'), 'show')
            $(element).prev('.webpage_block_label').removeClass('very_discreet')


        }


    }

</script>