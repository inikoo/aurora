 
    var save_panel_image_caption_timer=false;
    var save_panel_image_link_timer=false;

    


    $('#add_panel').click(function(){

        if(!$(this).hasClass('active')) {
            $(this).addClass('active')
            $('#items_container .item_overlay').removeClass('hide')
            $('.panel_controls').removeClass('hide')

        }else{
            $(this).removeClass('active')
            $('#items_container .item_overlay').addClass('hide')
            $('.panel_controls').addClass('hide')
        }
    })



    $('#items_container').on('mouseover', '.panel', function() {




        if(!$('#add_panel').hasClass('active') && !$(this).hasClass('editing')  ){
            $(this).find('.panel_controls').removeClass('hide')
        }



    })

    $('#items_container').on('mouseout', '.panel', function() {
        if(!$('#add_panel').hasClass('active') && !$(this).hasClass('editing')  ){
            $(this).find('.panel_controls').addClass('hide')
        }


    })


    $('#items_container').on('click', '.item_overlay .panel_type div', function() {



      //  $('.item_overlay .panel_type div').click(function() {


        $(this).closest('#products').find('.panel_type div').removeClass('selected')
        $(this).closest('#products').find('.panel_size').addClass('super_discreet')

        $(this).addClass('selected')


        var wrap=$(this).closest('.item_wrap')

        if(wrap.attr('max_free_slots')>1){
            wrap.find('.panel_size').removeClass('super_discreet').find('div').addClass('button')
        }else{
            add_panel(wrap.attr('stack_index'),1,$(this).attr('type'),  $(this).closest('.section').attr('section_key'))
        }

    });

    $('#items_container').on('click', '.item_overlay .panel_size div', function() {

      //  $('.item_overlay .panel_size div').click(function() {

        var wrap=$(this).closest('.item_wrap')


        if($(this).parent().hasClass('super_discreet'))return




        add_panel(wrap.attr('stack_index'),$(this).attr('size'),$(this).closest('.item_overlay').find('.panel_type div.selected').attr('type'),  $(this).closest('.section').attr('section_key') )


    });


    function add_panel(stack_index,size,type,section){

        if($('#items_container').hasClass('category_blocks')){

            add_panel_in_categories(stack_index,size,type,section)
            
        }else{

            add_panel_in_products(stack_index,size,type)
        }

    }


    function add_panel_in_categories(stack_index,size,type,section_key){

        var datetime = new Date();

        var block='panel'+datetime.getTime()

        var request = '/ar_edit_website.php?tipo=add_panel&webpage_key=' + $('#webpage_preview').attr('webpage_key') +'&section_key='+section_key+'&value='
            + JSON.stringify({
                'id':'panel'+datetime.getTime(),
                'stack_index':stack_index,
                'size':size,
                'type':type}
                )


        console.log(type)

        $.getJSON(request, function (data) {

            if (data.state == 200) {

                $('#items_container').html(data.products)
                $('#add_panel').removeClass('active')



                if(data.items_html !=undefined){

                    for(key in data.items_html){
                        console.log(key)
                        $('#section_items_'+key).html(data.items_html[key])

                    }

                }

                if(data.overview_items_html !=undefined){

                    for(key in data.overview_items_html){
                        console.log(key)
                        $('#section_overview_items_'+key).html(data.overview_items_html[key])

                    }

                }


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


    function add_panel_in_products(stack_index,size,type){

        var datetime = new Date();

        var block='panel'+datetime.getTime()

        var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + $('#webpage_preview').attr('webpage_key') +'&section=products&block=' + block + '&type=add_panel&value='
            + JSON.stringify({
                'stack_index':stack_index,'size':size,'type':type})


        console.log(type)

        $.getJSON(request, function (data) {

            if (data.state == 200) {

                $('#items_container').html(data.products)
                $('#add_panel').removeClass('active')

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


    $('#items_container').on('click', '.panel_settings.buttons .button', function() {






    var block=$(this).closest('.panel').attr('id')
    //console.log($(this).attr('type'))
      switch($(this).attr('type')){

          case 'update_text':


              console.log('cacac')


              var panel= $(this).closest('.panel');

              panel.addClass('editing')

              panel.find('.edit_toolbar').removeClass('hide')

              panel.find('.panel_controls').addClass('hide')

              if( $('#items_container').hasClass('cats')    ){
                  var section='panels_in_section'
              }else{
                  var section='panels'
              }

              panel.find('.panel_content').froalaEditor({


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



                  imageUploadURL: '/ar_upload.php',
                  imageUploadParams: {
                      tipo: 'upload_images',
                      parent: 'old_page',
                      parent_key: $('#webpage_preview').attr('webpage_key') ,
                      parent_object_scope:JSON.stringify({
                          scope: 'content', section: section, block:  panel.attr('id')

                      }),
                      response_type: 'froala'

                  },
                  imageUploadMethod: 'POST',
                  imageMaxSize: 5 * 1024 * 1024,
                  imageAllowedTypes: ['jpeg', 'jpg', 'png','gif'],



                  saveParams: {
                      parent : 'webpage',
                      parent_key:  $('#webpage_preview').attr('webpage_key') ,
                      section: section,
                      block: $(this).closest('.panel').attr('id'),
                      tipo: 'webpage_content_data',
                      type: 'text',



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






      break;
          case 'delete_panel':
              console.log('delete')

              if( $('#items_container').hasClass('cats')    ){
                  var section='panels_in_section'
              }else{
                  var section='panels'
              }


              var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + $('#webpage_preview').attr('webpage_key') +'&section='+section+'&block=' + block + '&type=remove_panel&value='
              $.getJSON(request, function (data) {

                  if (data.state == 200) {

                      $('#items_container').html(data.products)
                      $('#add_panel').removeClass('active')



                      if(data.items_html !=undefined){

                          for(key in data.items_html){
                              console.log(key)
                              $('#section_items_'+key).html(data.items_html[key])

                          }

                      }

                      if(data.overview_items_html !=undefined){

                          for(key in data.overview_items_html){
                              console.log(key)
                              $('#section_overview_items_'+key).html(data.overview_items_html[key])

                          }

                      }



                      if ($('#publish').find('i').hasClass('fa-rocket')) {
                          if (data.publish) {
                              $('#publish').addClass('changed valid')
                          } else {
                              $('#publish').removeClass('changed valid')
                          }
                      }

                  }

              })
              break;

          case 'update_caption':
              var caption=$(this).closest('.panel').find('.caption')
              if(caption.hasClass('hide')){
                  caption.removeClass('hide')
                  caption.find('input').focus().putCursorAtEnd()


                  $(this).find('i').addClass('faa-smooth_flash animated faa-slow')

                  var link=$(this).closest('.panel').find('.link_url')
                  link.addClass('hide')
                  link.find('i').removeClass('faa-smooth_flash animated faa-slow')



              }else{
                  caption.addClass('hide')
                  $(this).find('i').removeClass('faa-smooth_flash animated faa-slow')
              }
              break;
          case 'update_link':
              var link=$(this).closest('.panel').find('.link_url')
              if(link.hasClass('hide')){
                  link.removeClass('hide')
                  link.find('input').focus().putCursorAtEnd()

                  $(this).find('i').addClass('faa-smooth_flash animated faa-slow')

                  var caption=$(this).closest('.panel').find('.caption')
                  caption.addClass('hide')
                  caption.find('i').removeClass('faa-smooth_flash animated faa-slow')


              }else{
                  link.addClass('hide')
                  $(this).find('i').removeClass('faa-smooth_flash animated faa-slow')
              }
              break;
          case 'update_code':

              var panel= $(this).closest('.panel');

              panel.addClass('editing')

              panel.find('.code_editor_container').removeClass('hide')
              panel.find('.edit_toolbar').removeClass('hide')

              panel.find('.panel_controls').addClass('hide')
              panel.find('iframe').addClass('hide')


              var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('code_editor_'+panel.attr('code_key')),
                  {
                      lineNumbers: true,
                      styleActiveLine: true,
                      matchBrackets: true,
                      theme: 'dracula'
                  }
              );

              $('#'+'code_editor_'+panel.attr('code_key')).data('CodeMirrorInstance', myCodeMirror);

              break;

      }



    });




    function save_panel_image_option(type,input) {

        var block=input.closest('.panel').attr('id')
        var input_icon=input.closest('.panel').find('.'+type+'_icon')

        if( $('#items_container').hasClass('cats')    ){
            var section='panels_in_section'
        }else{
            var section='panels'
        }


        var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + $('#webpage_preview').attr('webpage_key')  +'&section='+section+'&block=' + block + '&type='+type+'&value=' + input.val()
        console.log(request)

        $.getJSON(request, function (data) {

            if (data.state == 200) {

                if(type=='caption'){
                    icon_class='fa-comment'
                }else{
                    icon_class='fa-link'
                }
                
                input_icon.addClass('faa-smooth_flash animated faa-slow '+icon_class).removeClass('fa-spinner fa-spin')


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


    $('#items_container').on('input propertychange', '.panel .caption', function() {



    //    $(".panel .caption").on('input propertychange', function(){

        $(this).closest('.panel').find('.caption_icon').removeClass('faa-smooth_flash animated faa-slow fa-comment').addClass('fa-spinner fa-spin')


        var element=$(this).find('input')
        if(save_panel_image_caption_timer)
           clearTimeout(save_panel_image_caption_timer);



        save_panel_image_caption_timer = setTimeout(function(){
            save_panel_image_option('caption',element)
        }, 400);


    })


    $('#items_container').on('input propertychange', '.panel .link_url', function() {


        //   $(".panel .link_url").on('input propertychange', function(){

        $(this).closest('.panel').find('.link_icon').removeClass('faa-smooth_flash animated faa-slow fa-link').addClass('fa-spinner fa-spin')


        var element=$(this).find('input')
        if(save_panel_image_link_timer)
            clearTimeout(save_panel_image_link_timer);



        save_panel_image_link_timer = setTimeout(function(){
            save_panel_image_option('link',element)
        }, 400);


    })

    $('#items_container').on('change', '.input_file_panel', function() {


   // $('.input_file_panel').on('change', function (e) {

        console.log('caca')

        var ajaxData = new FormData();

        //var ajaxData = new FormData( );
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                ajaxData.append('files', file);
            });
        }


        $.each($('#file_upload_'+$(this).attr('panel_key')).prop("files"), function (i, file) {
            ajaxData.append("files[" + i + "]", file);
        });



        var section_key=$(this).closest('.section').attr('section_key');
        if( $('#items_container').hasClass('cats')    ){
            var section='panels_in_section'
        }else{
            var section='panels'
        }

        ajaxData.append("tipo", 'upload_images')
        ajaxData.append("parent", 'old_page')
        ajaxData.append("parent_key", $('#webpage_preview').attr('webpage_key') )
        ajaxData.append("parent_object_scope", JSON.stringify({
            scope: 'content', section: section, block: $(this).attr('panel_key'),section_key:section_key

        }))

        var image = $(this).closest('.panel').find('img')


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


    $('#items_container').on('click', '.edit_toolbar i', function() {
        if($(this).hasClass('fa-window-close')){

            if($(this).hasClass('text')) {


                var content= $(this).closest('.panel').find('.panel_content').froalaEditor('html.get');
                $(this).closest('.panel').find('.panel_content').froalaEditor('destroy')

                console.log(content)

              //  $(this).closest('.panel').find('.panel_content').html(content+'<img  src="/image_root.php?id=1301678" style="width: 300px;">');
                $(this).closest('.panel').find('.panel_content').addClass('fr-view')
                $(this).closest('.panel').find('.edit_toolbar').addClass('hide')
                $(this).closest('.panel').removeClass('editing')

            }else if($(this).hasClass('code')) {

                var panel= $(this).closest('.panel')

               panel.find('.edit_toolbar').addClass('hide')
                panel.removeClass('editing')



                var block= panel.attr('id')
                var type='code';

                var iframe= panel.find('iframe')


                var editor=  $('#'+'code_editor_'+ panel.attr('code_key')).data('CodeMirrorInstance');


                btoa(editor.getValue())

                var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + $('#webpage_preview').attr('webpage_key')  +'&section=panels&block=' + block + '&type='+type+'&value=' + encodeURIComponent(btoa(editor.getValue()))
                console.log(request)

               $.getJSON(request, function (data) {

                    if (data.state == 200) {


                        iframe.attr( 'src', function ( i, val ) { return val; });

/*
                        if(type=='caption'){
                            icon_class='fa-comment'
                        }else{
                            icon_class='fa-link'
                        }

                        input_icon.addClass('faa-smooth_flash animated faa-slow '+icon_class).removeClass('fa-spinner fa-spin')
*/

                        if ($('#publish').find('i').hasClass('fa-rocket')) {
                            if (data.publish) {
                                $('#publish').addClass('changed valid')
                            } else {
                                $('#publish').removeClass('changed valid')
                            }
                        }

                    }

                })


                $(this).closest('.panel').find('iframe').removeClass('hide')
                $(this).closest('.panel').find('.code_editor_container').addClass('hide')

            }




        }else if($(this).hasClass('fa-trash')){


            var block=  $(this).closest('.panel').attr('id')

            if( $('#items_container').hasClass('cats')    ){
                var section='panels_in_section'
            }else{
                var section='panels'
            }


            var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + $('#webpage_preview').attr('webpage_key') +'&section='+section+'&block=' + block + '&type=remove_panel&value='
            $.getJSON(request, function (data) {

                if (data.state == 200) {

                    $('#items_container').html(data.products)
                    $('#add_panel').removeClass('active')
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

    }
    )