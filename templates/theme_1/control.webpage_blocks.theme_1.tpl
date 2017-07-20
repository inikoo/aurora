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


<div id="blocks_showcase" class="hide" style="z-index: 2000;background-color: #fff;padding:20px;border:1px solid #ccc;width: 300px;position: absolute;">
    <div style="margin-bottom:5px">  <i  onClick="$('#blocks_showcase').addClass('hide')" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i>  </div>

    <table style="width:100%;border-bottom: 1px solid #ccc;margin-top: 10px">
        {foreach from=$blocks item=$block key=key}
        <tr><td style="border-top: 1px solid #ccc"  class="add_webpage_block button" block="{$key}" ><span  ><i class="fa {$block.icon} fa-fw " style="margin-right: 50px " aria-hidden="true"></i>  {$block.label}</span> </td></tr>
        {/foreach}
    </table>

</div>


<div class=" edit_block_buttons  "  >
    <ul id="columns" class="sortable2 columns " style="width:1100px;" >


    {foreach from=$content.blocks item=$block key=key}
        {assign var="block_type" value=$block['type']}
        {include file="theme_1/blk.control_label.theme_1.tpl" }
    {/foreach}
        <li class="column  unselectable button  new_block "  style="min-width:auto;padding:4px 16px 4px 16px;" ><i class="fa fa-plus" aria-hidden="true"></i></li>

    </ul>

    <div id="edit_columns" class="hide" style="height: 27px;margin-bottom:10px"  current_key=""  >

        <i style="float:right"  class="fa button delete_block  fa-trash-o" aria-hidden="true"></i>


        <div style="float:left;position: relative;top:2.5px">
            <i id="edit_prev_column" onClick="edit_prev_column(this)" key="" class="edit_column_button fa button fa-arrow-left " aria-hidden="true"></i>
            <i id="exit_edit_column" style="margin-left:5px;margin-right: 5px"  onClick="exit_edit_column()" key="" class="edit_column_button fa button fa-window-close fa-flip-horizontal " aria-hidden="true"></i>
            <i id="edit_next_column" style="margin-right: 10px" onClick="edit_next_column(this)" key="" class="edit_column_button fa button fa-arrow-right " aria-hidden="true"></i>
        </div>

        <div id="blk_control_container">
        {foreach from=$content.blocks item=$block key=key}


            {assign var="block_type" value=$block['type']}
            {include file="theme_1/blk.control.$block_type.theme_1.tpl" }
        {/foreach}





    </div>


    <div style="clear:both"></div>

</div>


<script>








    function edit_next_column(element){

        var next_key=parseFloat($('#exit_edit_column').attr('key'))+1
        exit_edit_column()
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


        exit_edit_column()


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
            $('#preview')[0].contentWindow.move_block(pre,post);
            $('#save_button').addClass('save button changed valid')
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
        $('#edit_columns').removeClass('hide').attr('current_key',key)

        $('.edit_mode').addClass('hide')
        $('#edit_mode_'+key).removeClass('hide')


        $('#exit_edit_column').attr('key',key)

        //$('#preview')[0].contentWindow.show_column(key);

        $('.options_dialog').addClass('hide')

        $('#save_button').addClass('hide')


        $("#preview").contents().find('._block').addClass('hide')
        $("#preview").contents().find('#block_'+key).removeClass('hide')




    }

    function exit_edit_column() {
        $('#columns').removeClass('hide')
        $('#edit_columns').addClass('hide').attr('current_key','')


        $('.options_dialog').addClass('hide')

        $('#save_button').removeClass('hide')


        $("#preview").contents().find('._block').removeClass('hide')


    }



    // =============================== Slider ======================================================

    // =============================== Iframe ======================================================

    $(document).on('click', '.iframe_height', function (e) {

        $('.edit_block').addClass('hide')
        $('#iframe_height_edit_block_'+$(this).attr('key')).data('element',$(this)).removeClass('hide').offset({
            left: $(this).offset().left
        }).find('input').focus()





    })


    $(document).on('click', '.iframe_src', function (e) {
        $('.edit_block').addClass('hide')
        $('#iframe_src_edit_block_'+$(this).attr('key')).data('element',$(this)).removeClass('hide').find('input').focus()
    })

    $(document).on('click', '.apply_changes', function (e) {


        $('.edit_block').addClass('hide')

        switch ($(this).closest('div').attr('name')){
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
    // =============================== Image ======================================================

    $(document).on('click', '.image_link', function (e) {

        $('.edit_block').addClass('hide')
        $('#image_link_edit_block_'+$(this).attr('key')).data('element',$(this)).removeClass('hide').offset({
            left: $(this).offset().left
        }).find('input').focus()





    })


    $(document).on('click', '.image_tooltip', function (e) {
        $('.edit_block').addClass('hide')
        $('#image_tooltip_edit_block_'+$(this).attr('key')).data('element',$(this)).removeClass('hide').find('input').focus()
    })

    $(document).on('click', '.apply_changes', function (e) {


        $('.edit_block').addClass('hide')

        switch ($(this).closest('div').attr('name')){
            case 'image_link_edit_block':

                value=$(this).prev('input').val()

                $(this).closest('div').data('element').find('span').html(value)
                if(value==''){
                    $(this).closest('div').data('element').find('span').addClass('hide')
                    $(this).closest('div').data('element').find('i').addClass('very_discreet')
                    $("#preview").contents().find("#block_"+$(this).closest('.edit_mode').attr('key')).find('img').removeClass('like_button');

                }else{
                    $(this).closest('div').data('element').find('span').removeClass('hide')
                    $(this).closest('div').data('element').find('i').removeClass('very_discreet')
                    $("#preview").contents().find("#block_"+$(this).closest('.edit_mode').attr('key')).find('img').addClass('like_button');


                }


                $("#preview").contents().find("#block_"+$(this).closest('.edit_mode').attr('key')).find('img').attr('link',value);


                break;
            case 'image_tooltip_edit_block':

                var value=$(this).prev('input').val()

                $(this).closest('div').data('element').find('span').html(value)
                if(value==''){
                    $(this).closest('div').data('element').find('span').addClass('hide')

                    $(this).closest('div').data('element').find('i').addClass('very_discreet fa-comment-o').removeClass('fa-comment')

                }else{
                    $(this).closest('div').data('element').find('span').removeClass('hide')

                    $(this).closest('div').data('element').find('i').removeClass('very_discreet fa-comment-o').addClass('fa-comment')


                }

                $("#preview").contents().find("#block_"+$(this).closest('.edit_mode').attr('key')).find('img').attr('title',value).attr('alt',value);

                break;

        }

        $('#save_button').addClass('save button changed valid')


    })

    var droppedFiles = false;


    $(document).on('change', '.image_upload', function (e) {



        var ajaxData = new FormData();

        //var ajaxData = new FormData( );
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                ajaxData.append('files', file);
                return false;
            });
        }


        $.each($(this).prop("files"), function (i, file) {
            ajaxData.append("files[" + i + "]", file);
            return false;
        });


        ajaxData.append("tipo", 'upload_images')
        ajaxData.append("parent", 'webpage')
        ajaxData.append("parent_key", '{$webpage->id}')
        ajaxData.append("options", JSON.stringify($(this).data('options')))
        ajaxData.append("response_type", 'webpage')

        var element=$(this)

        $.ajax({
            url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {

            }, success: function (data) {

                // console.log(data)

                if (data.state == '200') {



                    if(element.attr('name')=='update_image_block'){

                        console.log("#block_"+$(this).attr('block_key'))


                        $("#preview").contents().find("#block_"+element.attr('block_key')).find('img').attr('src',data.image_src);


                    }else if(element.attr('name')=='button_bg'){


                        console.log('xx')
                        $("#preview").contents().find("#block_"+element.attr('block_key')).find('div.button_block').css('background-image', 'url(' + data.image_src + ')').attr('button_bg', data.image_src);

                        //   $("#preview").contents().find('._block').removeClass('hide')

                    }

                    $('#save_button', window.parent.document).addClass('save button changed valid')

                } else if (data.state == '400') {
                    swal(
                        data.msg
                    );
                }

                element.val('')

            }, error: function () {

            }
        });


    });

    // =============================== Counter ======================================================

    $(document).on('click', '.counter_link', function (e) {


        console.log($('#counter_link_edit_block_'+$(this).attr('key')+'_'+$(this).attr('column_key')))
        console.log($(this).attr('column_key'))

        $('.edit_block').addClass('hide')
        $('#counter_link_edit_block_'+$(this).attr('key')+'_'+$(this).attr('column_key')).data('element',$(this)).removeClass('hide').offset({
            left: $(this).offset().left
        }).find('input').focus()





    })


    $(document).on('input propertychange', '.counter_number', function (evt) {


        value=parseInt($(this).val())



        var counter_id=$("#preview").contents().find("#block_"+$(this).closest('.edit_mode').attr('key')+' .counters1 ._counter:eq('+$(this).attr('column_key')  +') '  ).attr('number',value).find('span').attr('id')
        $('#preview')[0].contentWindow.stop_counter(counter_id)

        $("#preview").contents().find("#block_"+$(this).closest('.edit_mode').attr('key')+' .counters1 ._counter:eq('+$(this).attr('column_key')  +') '  ).attr('number',value).find('span').html(value)


        $('#save_button').addClass('save button changed valid')


    });



    $(document).on('click', '.apply_changes', function (e) {


        $('.edit_block').addClass('hide')

        switch ($(this).closest('div').attr('name')){
            case 'counter_link_edit_block':

                value=$(this).prev('input').val()

                console.log(value)

                if(value==''){
                    $(this).closest('div').data('element').addClass('very_discreet')

                }else{
                    $(this).closest('div').data('element').removeClass('very_discreet')


                }



                $("#preview").contents().find("#block_"+$(this).closest('.edit_mode').attr('key')+' .counters1 ._counter:eq('+$(this).closest('div').data('element').attr('column_key')  +') '  ).attr('link',value);



                break;


        }

        $('#save_button').addClass('save button changed valid')


    })

    // =============================== Button ======================================================

    $(document).on('click', '.button_link', function (e) {


        console.log('xxx')

        $('.edit_block').addClass('hide')
        $('#button_link_edit_block_'+$(this).attr('key')).data('element',$(this)).removeClass('hide').offset({
            left: $(this).offset().left
        }).find('input').focus()





    })




    $(document).on('click', '.apply_changes', function (e) {


        $('.edit_block').addClass('hide')

        switch ($(this).closest('div').attr('name')){
            case 'button_link_edit_block':

                value=$(this).prev('input').val()

                $(this).closest('div').data('element').find('span').html(value)
                if(value==''){
                    $(this).closest('div').data('element').find('span').addClass('hide')
                    $(this).closest('div').data('element').find('i').addClass('very_discreet')

                }else{
                    $(this).closest('div').data('element').find('span').removeClass('hide')
                    $(this).closest('div').data('element').find('i').removeClass('very_discreet')


                }


                $("#preview").contents().find("#block_"+$(this).closest('.edit_mode').attr('key')).find('a').attr('link',value);


                break;


        }

        $('#save_button').addClass('save button changed valid')


    })


</script>