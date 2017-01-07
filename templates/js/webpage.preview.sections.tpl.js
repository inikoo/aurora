
var dragging='';

var save_section_header = false;




$('#items_views').on('click', '.overview_view', function () {


       $('#items_container').addClass('hide')
        $('#overview_container').removeClass('hide')

    $('#add_item_dialog').addClass('hide')

})


    $('#items_views').on('click', '.box_view', function () {


    $('#items_container').removeClass('hide')
   $('#overview_container').addClass('hide')
    $('#add_item_dialog').addClass('hide')

})

$('#items_views').on('click', '.add_section', function () {

    add_section();
})

$('#add_item_dialog').on('click', '.fa-window-close', function () {
    $('#add_item_dialog').addClass('hide')
})
$('#overview_container').on('click', '.tail_drop_zone', function () {



    var section_key = $(this).closest('.section').attr('section_key');


    var offset1 = $('#section_overview_'+section_key+'_container').offset();

    var offset2 = $(this).offset();



    _top=parseFloat(offset1.top)+(parseFloat(offset2.top)-parseFloat(offset1.top))

    console.log(_top)

    $('#add_item_dialog').removeClass('hide').css('top',_top).css('left',offset1.left+0).attr('section_key',section_key)
    $('#add_item_dropdown_select_label').val('').focus()

    $('#add_item_results_container').addClass('hide').removeClass('show')



})

$('#items_container').on('click', '.tail_drop_zone', function () {



    var section_key = $(this).closest('.section').attr('section_key');


    var offset1 = $('#section_'+section_key+'_container').offset();

    var offset2 = $(this).closest('div').offset();





    _top=parseFloat(offset1.top)+(parseFloat(offset2.top)-parseFloat(offset1.top))

    console.log(offset1)
    console.log(offset2)
    console.log(_top)

    $('#add_item_dialog').removeClass('hide').css('top',_top).css('left',offset1.left+0).attr('section_key',section_key).val('')
    $('#add_item_dropdown_select_label').val('').focus()
    $('#add_item_results_container').addClass('hide').removeClass('show')


})




function overview_items_allowDrop(ev) {
    ev.preventDefault();
}

function overview_items_ondragstart(ev) {

    $('#overview_container .section_header.page_break').removeAttr('contenteditable')
    $('#items_container .section_header.page_break').removeAttr('contenteditable')

   // ev.dataTransfer.setData("id", ev.target.id);
    ev.dataTransfer.setData("item_key", ev.target.getAttribute('item_key'));

    console.log(ev.target.getAttribute('item_type'))

    if(ev.target.getAttribute('item_type')=='Guest') {

        $(ev.target).closest('.section').find('.remove_drop_zone').removeClass('invisible')
    }


}

function overview_items_drop(ev) {

    console.log($( ev.target))

    var target_key=$( ev.target).closest('div.item_dragabble').attr('item_key');
    var section_key=$( ev.target).closest('.section').attr('section_key')

    console.log(target_key)

    if(target_key==ev.dataTransfer.getData("item_key")){
        return;
    }

    save_stack_index(ev.dataTransfer.getData("item_key"),target_key,section_key)

}

function overview_delete_items_drop(ev){


    var request = '/ar_edit_website.php?tipo=delete_webpage_item&item_key=' +ev.dataTransfer.getData("item_key") +'&webpage_key='+$('#webpage_preview').attr('webpage_key')
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

}

function overview_items_ondragend(ev) {

    $( ev.target).closest('.section').find('.remove_drop_zone').addClass('invisible')

    $('#overview_container .section_header.page_break').attr('contenteditable', true)
    $('#items_container .section_header.page_break').attr('contenteditable', true)

}

$(".overview_item_droppable").droppable({
    //  accept: '.overview_item_dragabble',
    drop: function (event, ui) {
        var id = ui.draggable.attr("id");
        alert(id);
    }
})



function move_section_allowDrop(ev) {
    ev.preventDefault();
}


function move_section_ondragstart(ev) {
$('.section_overview_container.page_break').addClass('highlight')
    $(ev.target).closest('.section').removeClass('highlight')


    ev.dataTransfer.setData("section_key", ev.target.getAttribute('section_key'));

}


function move_section_ondragend(ev) {
    $('.section_overview_container.page_break').removeClass('highlight')

}

function move_section_drop(ev){

    var section_key=ev.dataTransfer.getData("section_key")
    var target_section_key=$( ev.target).closest('.section').attr('section_key')

    var request = '/ar_edit_website.php?tipo=update_webpage_section_order&section_key=' +section_key + '&target_key=' +target_section_key +'&webpage_key='+$('#webpage_preview').attr('webpage_key')
    console.log(request)
    $.getJSON(request, function (data) {

        if(data.state==200){


            $('#overview_container').html(data.overview)
            $('#items_container').html(data.items)




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

function overview_items_drop(ev) {

    console.log($( ev.target))

    var target_key=$( ev.target).closest('div.item_dragabble').attr('item_key');
    var section_key=$( ev.target).closest('.section').attr('section_key')

    console.log(target_key)

    if(target_key==ev.dataTransfer.getData("item_key")){
        return;
    }

    save_stack_index(ev.dataTransfer.getData("item_key"),target_key,section_key)

}


function  save_stack_index(item_key,target_key,section_key) {

    var request = '/ar_edit_website.php?tipo=update_webpage_items_order&item_key=' +item_key + '&target_key=' +target_key +'&webpage_key='+$('#webpage_preview').attr('webpage_key')+'&target_section_key='+section_key
    console.log(request)
    $.getJSON(request, function (data) {

        if(data.state==200){


            if(data.items_html !=undefined){

                for(key in data.items_html){
                    console.log(data.items_html[key])
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
}

function delete_section(element){

    var section_key=$(element).closest('.section').attr('section_key')

    var request = '/ar_edit_website.php?tipo=delete_webpage_section&&webpage_key='+$('#webpage_preview').attr('webpage_key')+'&section_key='+section_key
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

            $( '#section_'+section_key+'_container' ).remove();
            $( '#section_overview_'+section_key+'_container' ).remove();


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

function add_section(element){

    var request = '/ar_edit_website.php?tipo=add_webpage_section&&webpage_key='+$('#webpage_preview').attr('webpage_key')
    console.log(request)
    $.getJSON(request, function (data) {

        if(data.state==200){



            $('#overview_container').append(data.new_overview_section_html);
            $('#items_container').append(data.new_section_html);



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

function select_dropdown_item(element) {

    field = $(element).attr('field')
    value = $(element).attr('value')

    if(value==0){
        console.log('cacacaca')


        return;
    }


    section_key =  $('#add_item_dialog').attr('section_key')

    formatted_value = $(element).attr('formatted_value')
    metadata = $(element).data('metadata')


    $('#' + field + '_dropdown_select_label').val(formatted_value)


    $('#' + field).val(value)

    $('#' + field + '_results_container').addClass('hide').removeClass('show')


    var request = '/ar_edit_website.php?tipo=add_webpage_item&&webpage_key='+$('#webpage_preview').attr('webpage_key')+'&section_key='+section_key+'&item_key='+value
    console.log(request)




    $.getJSON(request, function (data) {

        if(data.state==200){

            $('#add_item_dialog').addClass('hide')

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




}


$('#overview_container,#items_container').on('keyup', '[contenteditable]', function () {
    var element = $(this)


    if(element.attr('field')==undefined){
        return true;
    }





    if (save_section_header) clearTimeout(save_section_header);
    save_section_header = setTimeout(function () {
        update_section_header(element);
    }, 400);

})

$('#overview_container,#items_container').on('blur', '[contenteditable]', function () {


    if($(this).attr('field')==undefined){
        return true;
    }


    update_section_header($(this))


})

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


    // $('.input_file_panel').on('change', function (e) {

    console.log('caca')

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




function update_section_header(element) {


    var section_key = element.closest('.section').attr('section_key');
    var type = element.attr('field');

    var request = '/ar_edit_website.php?tipo=update_webpage_section_data&parent=page&parent_key=' + $('#webpage_preview').attr('webpage_key') + '&section_key=' + section_key + '&type=' + type + '&value=' + element.html()

    console.log(element)

    // console.log(request)
    $.getJSON(request, function (data) {

        if (data.state == 200) {


            if(element.hasClass('editable_overview_view')){



                $('#section_'+section_key).find('.title').html(data.data.title)
                $('#section_'+section_key).find('.sub_title').html(data.data.subtitle)

            }else{
                console.log(data.data.title)
                console.log($('section_overview_'+section_key).find('span.title'))


                $('#section_overview_'+section_key).find('span.title').html(data.data.title)
                $('#section_overview_'+section_key).find('.sub_title').html(data.data.subtitle)

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



