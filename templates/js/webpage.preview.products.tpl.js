 
    var save_panel_image_caption_timer=false;
    var save_panel_image_link_timer=false;

    function product_allowDrop(ev) {
        ev.preventDefault();
    }

    function product_drag(ev) {

       // console.log(ev.target.id)

        ev.dataTransfer.setData("id", ev.target.id);

        ev.dataTransfer.setData("stack_index", ev.target.getAttribute('stack_index'));
        ev.dataTransfer.setData("product_id", ev.target.getAttribute('product_id'));

        ev.dataTransfer.setData("product_code", ev.target.getAttribute('product_code'));


        ev.dataTransfer.setData("html",  ev.target.innerHTML );



    }

    function product_drop(ev) {



        ev.preventDefault();
        var product_showcase=  $( ev.target).closest('div.product_wrap').find('.product_showcase')
        var target_stack_index=product_showcase.attr('stack_index')



        console.log(target_stack_index)

        if(target_stack_index==ev.dataTransfer.getData("stack_index")){
            return;
        }else{

            //var tmp_html = product_showcase.html();
            //var tmp_product_code = product_showcase.attr('product_code');
            //var tmp_product_id = product_showcase.attr('product_id');

            //product_showcase.html(ev.dataTransfer.getData("html"))
            //product_showcase.attr('product_code',ev.dataTransfer.getData("product_code"))
            //product_showcase.attr('product_id',ev.dataTransfer.getData("product_id"))

           // change_next(  ev.dataTransfer.getData("stack_index") , tmp_html ,tmp_product_code,tmp_product_id,product_showcase.closest('.product_wrap').next().find('.product_showcase'))



            console.log(target_stack_index)

            if(target_stack_index<ev.dataTransfer.getData("stack_index")){
                var new_stack_index=(parseFloat(target_stack_index)+0.5)
            }else{
                var new_stack_index=(parseFloat(target_stack_index)+1.5)

            }


            save_stack_index(new_stack_index,ev.dataTransfer.getData("product_id"))

        }



    }

    function change_next(pivot_id,html,product_code,product_id,element){

        if(element.attr('id')==undefined   ){
            return;
        }

        var tmp_html = element.html();
        var tmp_product_code = element.attr('product_code');
        var tmp_product_id = element.attr('product_id');

        element.html(html)
        element.attr('product_code',product_code)
        element.attr('product_id',product_id)

        if( element.attr('stack_index')==pivot_id  ){
            return;
        }
        change_next(pivot_id,tmp_html,tmp_product_code,tmp_product_id,element.closest('.product_wrap').next().find('.product_showcase'))


    }

    function  save_stack_index(stack_index,product_id) {

        var request = '/ar_edit_website.php?tipo=edit_category_stack_index&key=' + {$category->id} + '&stack_index=' +stack_index + '&subject_key='+product_id+ '&webpage_key='+{$webpage->id}
        console.log(request)
        $.getJSON(request, function (data) {

            if(data.state==200){

                $('#products_helper').html(data.products)
                $('#add_panel').removeClass('active')
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
    
    
    
    $('.product_header_text').dblclick(function() {





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

         saveParams: {
             webpage_key:  {$category->webpage->id},
             key: $(this).closest('.product_showcase').attr('index_key'),
             tipo: ($(this).hasClass('related_product')?'update_webpage_related_product': 'update_product_category_index'),
             type: 'header_text',



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

    })



    $('#add_panel').click(function(){

        if(!$(this).hasClass('active')) {
            $(this).addClass('active')
            $('#products .product_overlay').removeClass('hide')
            $('.panel_controls').removeClass('hide')

        }else{
            $(this).removeClass('active')
            $('#products .product_overlay').addClass('hide')
            $('.panel_controls').addClass('hide')
        }
    })



    $('#products_helper').on('mouseover', '.panel', function() {




        if(!$('#add_panel').hasClass('active') && !$(this).hasClass('editing')  ){
            $(this).find('.panel_controls').removeClass('hide')
        }



    })

    $('#products_helper').on('mouseout', '.panel', function() {
        if(!$('#add_panel').hasClass('active') && !$(this).hasClass('editing')  ){
            $(this).find('.panel_controls').addClass('hide')
        }


    })


    $('#products_helper').on('click', '.product_overlay .panel_type div', function() {



      //  $('.product_overlay .panel_type div').click(function() {


        $(this).closest('#products').find('.panel_type div').removeClass('selected')
        $(this).closest('#products').find('.panel_size').addClass('super_discreet')

        $(this).addClass('selected')


        var wrap=$(this).closest('.product_wrap')

        if(wrap.attr('max_free_slots')>1){
            wrap.find('.panel_size').removeClass('super_discreet').find('div').addClass('button')
        }else{
            add_panel(wrap.attr('stack_index'),1,$(this).attr('type'))
        }

    });

    $('#products_helper').on('click', '.product_overlay .panel_size div', function() {

      //  $('.product_overlay .panel_size div').click(function() {

        var wrap=$(this).closest('.product_wrap')


        if($(this).parent().hasClass('super_discreet'))return


        console.log($(this).closest('.product_overlay').find('.panel_type'))

        add_panel(wrap.attr('stack_index'),$(this).attr('size'),$(this).closest('.product_overlay').find('.panel_type div.selected').attr('type'))


    });


    function add_panel(stack_index,size,type){

        var datetime = new Date();

        var block='panel'+datetime.getTime()

        var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=products&block=' + block + '&type=add_panel&value='
            + JSON.stringify({
                'stack_index':stack_index,'size':size,'type':type})


        console.log(type)

        $.getJSON(request, function (data) {

            if (data.state == 200) {

                $('#products_helper').html(data.products)
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


    $('#products_helper').on('click', '.panel_settings.buttons .button', function() {






    var block=$(this).closest('.panel').attr('id')
    //console.log($(this).attr('type'))
      switch($(this).attr('type')){

          case 'update_text':

              var panel= $(this).closest('.panel');

              panel.addClass('editing')

              panel.find('.edit_toolbar').removeClass('hide')

              panel.find('.panel_controls').addClass('hide')


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
                      parent_key: {$webpage->id},
                      parent_object_scope:JSON.stringify({
                          scope: 'content', section: 'panels', block:  panel.attr('id')

                      }),
                      response_type: 'froala'

                  },
                  imageUploadMethod: 'POST',
                  imageMaxSize: 5 * 1024 * 1024,
                  imageAllowedTypes: ['jpeg', 'jpg', 'png','gif'],


                  saveParams: {
                      parent : 'webpage',
                      parent_key:  {$category->webpage->id},
                      section: 'panels',
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
              var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=panels&block=' + block + '&type=remove_panel&value='
              $.getJSON(request, function (data) {

                  if (data.state == 200) {

                      $('#products_helper').html(data.products)
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

        var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=panels&block=' + block + '&type='+type+'&value=' + input.val()
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


    $('#products_helper').on('input propertychange', '.panel .caption', function() {



    //    $(".panel .caption").on('input propertychange', function(){

        $(this).closest('.panel').find('.caption_icon').removeClass('faa-smooth_flash animated faa-slow fa-comment').addClass('fa-spinner fa-spin')


        var element=$(this).find('input')
        if(save_panel_image_caption_timer)
           clearTimeout(save_panel_image_caption_timer);



        save_panel_image_caption_timer = setTimeout(function(){
            save_panel_image_option('caption',element)
        }, 400);


    })


    $('#products_helper').on('input propertychange', '.panel .link_url', function() {


        //   $(".panel .link_url").on('input propertychange', function(){

        $(this).closest('.panel').find('.link_icon').removeClass('faa-smooth_flash animated faa-slow fa-link').addClass('fa-spinner fa-spin')


        var element=$(this).find('input')
        if(save_panel_image_link_timer)
            clearTimeout(save_panel_image_link_timer);



        save_panel_image_link_timer = setTimeout(function(){
            save_panel_image_option('link',element)
        }, 400);


    })

    $('#products_helper').on('change', '.input_file_panel', function() {


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





        ajaxData.append("tipo", 'upload_images')
        ajaxData.append("parent", 'old_page')
        ajaxData.append("parent_key", '{$webpage->id}')
        ajaxData.append("parent_object_scope", JSON.stringify({
            scope: 'content', section: 'panels', block: $(this).attr('panel_key')

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


    $('#products_helper').on('click', '.edit_toolbar i', function() {
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

                var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=panels&block=' + block + '&type='+type+'&value=' + encodeURIComponent(btoa(editor.getValue()))
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


            var request = '/ar_edit_website.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=panels&block=' + block + '&type=remove_panel&value='
            $.getJSON(request, function (data) {

                if (data.state == 200) {

                    $('#products_helper').html(data.products)
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