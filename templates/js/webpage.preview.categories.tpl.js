$('#overview_container,#items_container').on('dblclick', '.category_image img', function () {



    var section_key=$(this).closest('.section').attr('section_key');
    var item_key = $(this).closest('.item_showcase').attr('item_key');
    var index_key = $(this).closest('.item_showcase').attr('index_key');


    console.log(index_key)

    $('#item_image_uploader').attr('section_key',section_key).attr('item_key',item_key).attr('index_key',index_key).click()



})




$('.item_header_text').dblclick(function() {





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
        pastePlain: true,
        saveURL: '/ar_edit_website.php',

        saveMethod: 'POST',

        saveParams: {
            parent: 'website',
            parent_key:  $('#webpage_preview').attr('webpage_key'),
          //  key: $(this).closest('.item_showcase').attr('index_key'),
            block:$(this).closest('.item_showcase').attr('item_key'),
            tipo: 'webpage_content_data',
            section: 'panels_in_section',
            type: 'item_header_text',



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



$('#page_content').on('change', '.input_file_item', function() {


    

    var ajaxData = new FormData();

    //var ajaxData = new FormData( );
    if (droppedFiles) {
        $.each(droppedFiles, function (i, file) {
            ajaxData.append('files', file);
        });
    }


    $.each($(this).prop("files"), function (i, file) {
        ajaxData.append("files[" + i + "]", file);
    });



    var section_key=$(this).attr('section_key');
    var item_key = $(this).attr('item_key');
   var index_key = $(this).attr('index_key');

    ajaxData.append("tipo", 'upload_images')
    ajaxData.append("parent", 'old_page')
    ajaxData.append("parent_key", $('#webpage_preview').attr('webpage_key') )
    ajaxData.append("parent_object_scope", JSON.stringify({
        scope: 'content',item_key:item_key,section:'items'

    }))


console.log(index_key)

    var image = $('#item_image_'+index_key)


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

$('#page_content').on('click', '.make_category_no_public', function() {



    var item_key = $(this).closest('.item_wrap').attr('item_key');
    var section_key = $(this).closest('.section').attr('section_key');

    var request = '/ar_edit_website.php?tipo=update_object_public&object=category&object_key=' +item_key +'&webpage_key='+$('#webpage_preview').attr('webpage_key')+'&section_key='+section_key+'&value=No'
    console.log(request)

    $.getJSON(request, function (data) {

        if(data.state==200){


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