/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 July 2017 at 12:26:18 CEST, Tranava, Slovaquia
 Copyright (c) 2017, Inikoo
 Version 3.0*/
 
 
 
$(document).on('click', '.delete_block', function (e) {


    var key= $('#edit_columns').attr('current_key')

    $('#edit_mode_'+key).remove()
    $('#block_label_'+key).remove()
    $("#preview").contents().find("#block_"+key).remove()



    exit_edit_webpage_block_column()
    $('#save_button').addClass('save button changed valid')

})


$(document).on('click', '.new_block', function (e) {
    $('#blocks_showcase').removeClass('hide').offset({
        top:$(this).offset().top-69 ,
        left:$(this).offset().left+$(this).width()+30    }).data('item',$(this))
})



$(document).on('click', '.add_webpage_block', function (e) {



    var request = '/ar_website.php?tipo=webpage_block&code=' + $(this).attr('block') + '&theme=theme_1'
    console.log(request)
    console.log('cacaca')

    $.getJSON(request, function (data) {

        $('#blk_control_container').prepend(data.controls)

        $('#columns ').prepend(data.button)
        $("#preview").contents().find("#blocks").prepend(data.block)
        $('#blocks_showcase').addClass('hide')

        if (data.type == 'static_banner') {
            $('#preview')[0].contentWindow.create_static_banner()
        } else if (data.type == 'one_pack') {
            $('#preview')[0].contentWindow.set_up_one_column_editor(data.block_key)
        }


        $('#save_button').addClass('save button changed valid')


    });


})



$(document).on('click', '.slider_preview', function (e) {



    if(!$(this).hasClass('selected')){
        $('.slider_preview').removeClass('selected')
        $(this).addClass('selected')
        $('.slider_preview_options').addClass('hide')
        $('#slider_preview_options_'+$(this).attr('key')).removeClass('hide')

        // $('#preview')[0].contentWindow.change_slider($(this).attr('key'))

    }

})


    

    function edit_next_webpage_blocks_column(element){

        var next_key=parseFloat($('#exit_edit_column').attr('key'))+1
        exit_edit_webpage_block_column()
        var next= $('#edit_mode_'+next_key)


        console.log(next_key)

        if(next.length){
            edit_webpage_block_column(next_key)
        }else{
            edit_webpage_block_column(0)
        }
    }

    function edit_prev_webpage_blocks_column(element){

        var prev_key=parseFloat($('#exit_edit_column').attr('key'))-1


        exit_edit_webpage_block_column()


        if(prev_key>=0){


            edit_webpage_block_column(prev_key)
        }else{

//console.log( $('#edit_columns .edit_mode:last'))

            edit_webpage_block_column( $('#edit_columns .edit_mode:last').attr('key'))

        }


    }




  

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
            edit_webpage_block_column(key)
        }



    })





    function edit_webpage_block_column(key) {

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

    function exit_edit_webpage_block_column() {
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

    var webpage_scope_droppedFiles = false;


    $(document).on('change', '.image_upload', function (e) {



        var ajaxData = new FormData();

        //var ajaxData = new FormData( );
        if (webpage_scope_droppedFiles) {
            $.each(webpage_scope_droppedFiles, function (i, file) {
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
        ajaxData.append("parent_key", $('#blocks_showcase').attr('webpage_key')  )
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