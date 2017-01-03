     var save_webpage_content_header_state_timer=false;
    var save_image_caption=false;

 
 
 var droppedFiles = false;

    $('#file_upload').on('change', function (e) {


            var ajaxData = new FormData();

            //var ajaxData = new FormData( );
            if (droppedFiles) {
                $.each(droppedFiles, function (i, file) {
                    ajaxData.append('files', file);
                });
            }


            $.each($('#file_upload').prop("files"), function (i, file) {
                ajaxData.append("files[" + i + "]", file);

            });





                ajaxData.append("tipo", 'upload_images')
                ajaxData.append("parent", 'old_page')
                ajaxData.append("parent_key", '{$webpage->id}')
                ajaxData.append("parent_object_scope", JSON.stringify({
                    scope: 'content', section: $('#image_edit_toolbar').attr('section'), block: $('#image_edit_toolbar').attr('block')

                })
                )

                var image = $('#' + $('#image_edit_toolbar').attr('block') + ' img')


            $.ajax({
                url: "/ar_upload.php",
                type: 'POST',
                data: ajaxData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,


                complete: function () {

                },
                success: function (data) {

                    console.log(data)

                    if (data.state == '200') {
                    console.log(data.image_src)
                        image.attr('src',data.image_src)

                        if($('#publish').find('i').hasClass('fa-rocket')) {

                            if (data.publish) {
                                $('#publish').addClass('changed valid')
                            } else {
                                $('#publish').removeClass('changed valid')
                            }
                        }




                    } else if (data.state == '400') {

                    }


                },
                error: function () {

                }
            });



    });
    
    
    function save_webpage_content_header_state(){

        var css='';

        webpage_content_header_state={}


                css+='#description_block{ ' +
                    'height:'+$('#description_block').height()+'px}'



        $( ".webpage_content_header" ).each(function( index ) {
            webpage_content_header_state.top= $( this ).offset().top-$('#description_block').offset().top-1
            webpage_content_header_state.left= $( this ).offset().left-$('#description_block').offset().left-1
            webpage_content_header_state.width=$(this).width()
            webpage_content_header_state.height=$(this).height()


            css+='#'+$( this ).attr('id')+'{ position:absolute;margin-left:0px; top:'+webpage_content_header_state.top+'px;left:'+webpage_content_header_state.left+'px;width:'+webpage_content_header_state.width+'px;height:'+webpage_content_header_state.height+'px}'

        });



        var request = '/ar_edit_website.php?tipo=edit_webpage&key=' + {$category->webpage->id} + '&field=css&value=' + btoa(css)
       // console.log(request)
        $.getJSON(request, function (data) {
            console.log(data)
            if(data.state==200){



                if($('#publish').find('i').hasClass('fa-rocket')) {

                    if (data.publish) {
                        $('#publish').addClass('changed valid')
                    } else {
                        $('#publish').removeClass('changed valid')
                    }
                }

            }

        })


}

    $('#description_block').resizable(

            {
                minWidth:935,
                maxWidth:935,
                stop: function (event, ui) {




                    if(save_webpage_content_header_state_timer)
                        clearTimeout(save_webpage_content_header_state_timer);
                    save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                }

            }

    );
    
    
    
   $('.toggle_description_block').click(function(){
        if($('#description_block_on').hasClass('hide')){

            $('#description_block_on').removeClass('hide')
            $('#description_block_off').addClass('hide')
            $('#description_block').removeClass('hide');

            var type='remove_class'

        }else{
            $('#description_block_on').addClass('hide')
            $('#description_block_off').removeClass('hide')
            $('#description_block').addClass('hide');
            var type='add_class'

        }



       var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=description_block&block=&type='+type+'&value=hide'


       $.getJSON(request, function (data) {

           if (data.state == 200) {



               if ($('#publish').find('i').hasClass('fa-rocket')) {
                   if (data.publish) {
                       $('#publish').addClass('changed valid')
                   } else {
                       $('#publish').removeClass('changed valid')
                   }
               }

           }

       })


       }
   )
   
   
    $('#page_content').on( "dblclick", ".webpage_content_header_image", function() {


var position=$(this).position();


        $('#image_edit_toolbar').removeClass('hide').css({
            position: 'absolute',
            left:position.left - 25 + "px",
            top: position.top + 5 + "px"
        }).attr('block',$(this).attr('id'))

        $(this).draggable( 'disable' ).resizable('destroy').addClass('editing')


    })


    $('#image_edit_toolbar .fa-window-close').click(function() {
        $('#image_edit_toolbar').addClass('hide')
        $('#'+$('#image_edit_toolbar').attr('block')).removeClass('editing').draggable( 'enable' ).resizable(
            {
                stop: function (event, ui) {
                    if(save_webpage_content_header_state_timer)
                        clearTimeout(save_webpage_content_header_state_timer);
                    save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                }

            }

        );

    })


    $('#image_edit_toolbar .fa-trash,#text_edit_toolbar .fa-trash').click(function() {

      var toolbar=$(this).closest('.edit_toolbar')

        toolbar.addClass('hide')
        $('#'+toolbar.attr('block')).remove()

        var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=description_block&block='+toolbar.attr('block')+'&type=remove_block&value='


        $.getJSON(request, function (data) {

            if (data.state == 200) {



                if ($('#publish').find('i').hasClass('fa-rocket')) {
                    if (data.publish) {
                        $('#publish').addClass('changed valid')
                    } else {
                        $('#publish').removeClass('changed valid')
                    }
                }

            }

        })



    })


    $('.create_text').click(function() {


    var section=$(this).closest('div.section');

        var datetime = new Date();


    text = $('<div class="webpage_content_header webpage_content_header_text" style="width:150px;height:100px;text-align:center" ><h1>Bla bla</h1><p>bla bla bla</p></div>')
        .attr('id', 'text'+datetime.getTime())
        .draggable(
        {
            containment: "#description_block",
            scroll: false,
            stop: function (event, ui) {

                if(save_webpage_content_header_state_timer)
                    clearTimeout(save_webpage_content_header_state_timer);
                save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);



            }
        }
    )
        .resizable(
            {
                stop: function (event, ui) {

                    // console.log(this.id)
                    // console.log(ui.size)

                    if(save_webpage_content_header_state_timer)
                        clearTimeout(save_webpage_content_header_state_timer);
                    save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                }

            }
        ) .appendTo(section)



    var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=description_block&block='+text.attr('id')+'&type=text&value='+text.html()


    $.getJSON(request, function (data) {

        if (data.state == 200) {



            if ($('#publish').find('i').hasClass('fa-rocket')) {
                if (data.publish) {
                    $('#publish').addClass('changed valid')
                } else {
                    $('#publish').removeClass('changed valid')
                }
            }

        }

    })




})

    $('.create_image').click(function() {


        var section=$(this).closest('div.section');
        var datetime = new Date();

        var img = $('<div class="webpage_content_header webpage_content_header_image" ><img src="/art/nopic.png" style="width:100%"></div>')
            .attr('id', 'image'+datetime.getTime())
            .draggable(
            {
                containment: "#description_block",
                scroll: false,
                stop: function (event, ui) {

                    if(save_webpage_content_header_state_timer)
                        clearTimeout(save_webpage_content_header_state_timer);
                    save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);



                }
            }
        )
            .resizable(
                {
                    stop: function (event, ui) {

                        // console.log(this.id)
                        // console.log(ui.size)

                        if(save_webpage_content_header_state_timer)
                            clearTimeout(save_webpage_content_header_state_timer);
                        save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                    }

                }
            ) .appendTo(section)



        var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=description_block&block='+img.attr('id')+'&type=add_image&value='+img.find('img').attr('src')


        $.getJSON(request, function (data) {

            if (data.state == 200) {



                if ($('#publish').find('i').hasClass('fa-rocket')) {
                    if (data.publish) {
                        $('#publish').addClass('changed valid')
                    } else {
                        $('#publish').removeClass('changed valid')
                    }
                }

            }

        })




    })


    $('#image_edit_toolbar .fa-comment').click(function() {

        var caption= $(this).closest('.edit_toolbar').find('div.caption')

        if(caption.hasClass('hide')) {

            caption.removeClass('hide').css({
                position: 'absolute', left: $(this).position().left + 25 + "px", top: $(this).position().top - 10 + "px"
            }).find('input').val($('#' + $(this).closest('.edit_toolbar').attr('block') + ' img').attr('title'))
        }else{

            clearTimeout(save_image_caption);
            save_caption()
            caption.addClass('hide')

        }


    })


    $("#caption_input").on('input propertychange', function(){



        $(this).closest('.edit_toolbar').find('.caption_icon').removeClass('fa-comment').addClass('fa-spinner fa-spin')

        if(save_image_caption)
            clearTimeout(save_image_caption);
        save_image_caption = setTimeout(function(){ save_caption(); }, 400);


    })


    function save_caption() {

        var caption=$('#caption_input').val()
        var block=$('#image_edit_toolbar').attr('block')

        var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=description_block&block=' + block + '&type=caption&value=' + caption

        $.getJSON(request, function (data) {

            if (data.state == 200) {

                $('#image_edit_toolbar').find('.caption_icon').addClass('fa-comment').removeClass('fa-spinner fa-spin')
                $('#'+block+' img').attr('title',caption)

                if ($('#publish').find('i').hasClass('fa-rocket')) {
                    if (data.publish) {
                        $('#publish').addClass('changed valid')
                    } else {
                        $('#publish').removeClass('changed valid')
                    }
                }

            }

        })

    }


    $('#page_content').on( "dblclick", ".webpage_content_header_text", function() {



           if(! $('#text_edit_toolbar').hasClass('hide')){
               console.log('caca')
               return
           }

            var position=$(this).position();


        $(this).draggable( 'disable' ).resizable('destroy').addClass('editing')

            console.log($(this).position())

        $('#text_edit_toolbar').removeClass('hide').css({
            position: 'absolute',
            left:position.left - 25 + "px",
            top: position.top + 5 + "px"
        }).attr('block',$(this).attr('id'))


        $(this).froalaEditor({


            toolbarInline: true,
            charCounterCount: false,
            toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', 'color', 'emoticons','insertLink', 'insertImage','insertVideo', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'undo', 'redo'],
            toolbarButtonsMD: ['bold', 'italic', 'underline', 'strikeThrough', 'color', 'emoticons','insertLink', 'insertImage','insertVideo', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'undo', 'redo'],
            toolbarButtonsSM: ['bold', 'italic', 'underline', 'strikeThrough', 'color', 'emoticons','insertLink', 'insertImage','insertVideo', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'undo', 'redo'],
            toolbarButtonsXS: ['bold', 'italic', 'underline', 'strikeThrough', 'color', 'emoticons','insertLink', 'insertImage','insertVideo', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'undo', 'redo'],
            defaultImageDisplay: 'inline',

            saveInterval: 500,
            saveParam: 'value',

            saveURL: '/ar_edit_website.php',

            saveMethod: 'POST',
            zIndex: 1000,
            saveParams: {
                tipo: 'webpage_content_data',
                parent: 'page',
                parent_key:  {$category->webpage->id},
                section: 'description_block',
                block: $(this).attr('id'),
                type: 'text'

            }


        }).on('froalaEditor.save.after', function (e, editor, response) {


            var data=jQuery.parseJSON(response)

            if(data.state==200){



                if($('#publish').find('i').hasClass('fa-rocket')) {

                    if (data.publish) {
                        $('#publish').addClass('changed valid')
                    } else {
                        $('#publish').removeClass('changed valid')
                    }
                }

            }
        })











    });





    $('#text_edit_toolbar .fa-window-close').click(function() {

        $('#text_edit_toolbar').addClass('hide')

       var block= $('#'+$('#text_edit_toolbar').attr('block'))

        block.froalaEditor('destroy')
        block.addClass('fr-view')



        block.draggable( 'enable' ).removeClass('editing')

        block.resizable(
            {
                stop: function (event, ui) {
                    if(save_webpage_content_header_state_timer)
                        clearTimeout(save_webpage_content_header_state_timer);
                    save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                }

            }

        );

     //



    });



    $('.webpage_content_header')
            .draggable(
                    {
                        containment: "#description_block",
                        scroll: false,
                        stop: function (event, ui) {

                            if(save_webpage_content_header_state_timer)
                                clearTimeout(save_webpage_content_header_state_timer);
                            save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);



                        }
                    }
            )
            .resizable(
                    {
                        stop: function (event, ui) {


                            if(save_webpage_content_header_state_timer)
                                clearTimeout(save_webpage_content_header_state_timer);
                            save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                        }

                    }
            );




    