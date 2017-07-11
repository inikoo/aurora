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





<div class=" edit_block_buttons  "  >
    <ul id="columns" class="sortable2 columns " style="width:1100px;" >



    {foreach from=$content.blocks item=$block key=key}

        <li class="column  unselectable  {if !$block.show}very_discreet{/if}"  key="{$key}" block="{$block.type}" >
            <span class="button open_edit">
            <i class="fa   {$block.icon}" aria-hidden="true"></i>
             <span class="label  ">{$block.label}</span>
            </span>
            <i class="fa button  {if $block.show}fa-eye{else}fa-eye-slash{/if} block_show" aria-hidden="true"></i>
            <i class="fa handle2 fa-arrows" aria-hidden="true"></i>
        </li>
    {/foreach}
    </ul>

    <div id="edit_columns" class="hide" style="height: 27px;margin-bottom:10px" >

        <div style="float:left;position: relative;top:2.5px">
            <i id="edit_prev_column" onClick="edit_prev_column(this)" key="" class="edit_column_button fa button fa-arrow-left " aria-hidden="true"></i>
            <i id="exit_edit_column" style="margin-left:5px;margin-right: 5px"  onClick="exit_edit_column(this)" key="" class="edit_column_button fa button fa-window-close fa-flip-horizontal " aria-hidden="true"></i>
            <i id="edit_next_column" style="margin-right: 10px" onClick="edit_next_column(this)" key="" class="edit_column_button fa button fa-arrow-right " aria-hidden="true"></i>
        </div>


        {foreach from=$content.blocks item=$block key=key}
            <div id="edit_mode_{$key}" class=" edit_mode "  type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px" >
                <div style="float:left;margin-right:20px;min-width: 200px;">




                    <div style="float:left;min-width: 200px;position: relative;top:2px">
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

                        {elseif $block.type=='iframe'}

                            <div id="iframe_height_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc">
                                 {t}Height{/t} <input value="{$block.height}" style="width: 30px">px <i class="apply_changes fa button fa-check-square" style="margin-left: 10px" aria-hidden="true"></i>
                            </div>
                            <div id="iframe_src_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc">
                                {t}Src{/t} https://<input value="{$block.src}" style="width: 900px">  <i class="apply_changes  fa button fa-check-square" style="margin-left: 10px" aria-hidden="true"></i>
                            </div>

                            <span >
                                {t}Width{/t} 1240px {t}Height{/t} <span id="iframe_height_{$key}" class="button iframe_height" style="border:1px solid #ccc;padding:2px 4px">{$block.height}px</span> r=<span class="iframe_ratio">{math equation="w/h" w=1240 h=$block.height format="%.2f"}</span>

                                <span  style="margin-left:20px"> src:<span id="iframe_src{$key}" class="button iframe_src"   style="border:1px solid #ccc;padding:2px 4px;">https://{$block.src|truncate:60}</span>
                            </span>

                        {/if}




                </div>

                <div style="clear: both"></div>

            </div>
        {/foreach}



    </div>


    <div style="clear:both"></div>

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

            $("#preview").contents().find("#block_"+$(this).closest('.column').attr('key')).addClass('hide')

            //$('#preview')[0].contentWindow.hide_column_label(key);
            //$('#preview')[0].contentWindow.hide_column(key);

        }else{
            var key=$(this).addClass('fa-eye').removeClass('fa-eye-slash').closest('li').removeClass('very_discreet').attr('key')

            $("#preview").contents().find("#block_"+$(this).closest('.column').attr('key')).removeClass('hide')

            if( $(this).closest('.column').attr('block') =='iframe'){
                $("#preview")[0].contentWindow.resize_banners();
            }

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

        $('.edit_block').addClass('hide')


        $('#columns').addClass('hide')
        $('#edit_columns').removeClass('hide')

        $('.edit_mode').addClass('hide')
        $('#edit_mode_'+key).removeClass('hide')


        $('#exit_edit_column').attr('key',key)

        //$('#preview')[0].contentWindow.show_column(key);

        $('.options_dialog').addClass('hide')

        $('#save_button').addClass('hide')


    }

    function exit_edit_column(element) {
        $('#columns').removeClass('hide')
        $('#edit_columns').addClass('hide')


        $('.options_dialog').addClass('hide')

        $('#save_button').removeClass('hide')


        //$('#preview')[0].contentWindow.hide_column($(element).attr('key'));


    }



    // =============================== Slider ======================================================

    // =============================== Iframe ======================================================

    $(document).on('click', '.iframe_height', function (e) {

        $('.edit_block').addClass('hide')
        $('#iframe_height_edit_block').data('element',$(this)).removeClass('hide').offset({
            left: $(this).offset().left
        }).find('input').focus()





    })


    $(document).on('click', '.iframe_src', function (e) {
        $('.edit_block').addClass('hide')
        $('#iframe_src_edit_block').data('element',$(this)).removeClass('hide').find('input').focus()
    })

    $(document).on('click', '.apply_changes', function (e) {


        $('.edit_block').addClass('hide')

        switch ($(this).closest('div').attr('id')){
            case 'iframe_height_edit_block':

                value=parseInt($(this).prev('input').val())

                $(this).closest('div').data('element').html(value+'px').next('span.iframe_ratio').html((1240/value).toFixed(2))

                $("#preview").contents().find("#block_"+$(this).closest('.edit_mode').attr('key')).attr('h',value);
                $("#preview")[0].contentWindow.resize_banners();


                break;
            case 'iframe_src_edit_block':

                var value=$(this).prev('input').val()


                var tmp=$('<div>' + value + '</div>').find('iframe').attr('src');



                if(tmp==undefined) {
                    value = value.replace(/(^\w+:|^)\/\//, '');
                }else{
                    value = tmp.replace(/(^\w+:|^)\/\//, '');

                }

                console.log(value)

                $(this).closest('div').data('element').html('https://'+ truncateWithEllipses(  value,60)   )
                $("#preview").contents().find("#block_"+$(this).closest('.edit_mode').attr('key')).find('iframe').attr('src','https://'+value);

                console.log($("#preview").contents().find("#block_"+$(this).closest('.edit_mode').attr('key')).find('iframe'))

                break;

        }

        $('#save_button').addClass('save button changed valid')


    })



</script>