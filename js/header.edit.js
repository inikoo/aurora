var pinned = false;

var menu_open = false;
var mouse_over_menu = false;
var mouse_over_menu_link = false;

//$("#bottom_header a").hoverIntent(menu_in, menu_out);
//$("#bottom_header a").hover(menu_in_fast, menu_out_fast);
//$("#_menu_blocks ._menu_block").hover(menu_block_in, menu_block_out);



function position_single_columns(){

    $('#_menu_blocks .single_column').each(function(i, obj) {


        $(this).removeClass('hide').css('visibility','hidden')


        var offset =  $('#menu_'+$(this).data('key')).offset();

        $(this).offset({
            left:offset.left
        })

        $(this).addClass('hide').css('visibility','visible')
    })

}


$(function () {

    $('#_menu_blocks').width($('#bottom_header').width())

    position_single_columns()




    document.addEventListener("paste", function (e) {
        e.preventDefault();
        var text = e.clipboardData.getData("text/plain");
        document.execCommand("insertHTML", false, text);
    });

    $(document).on('input paste', '[contenteditable=true]', function (e) {
        $('#save_button', window.parent.document).addClass('save button changed valid')
    });



    $('#_menu_blocks').on('mouseenter', '.vertical-menu a.item', function () {


            $(this).find('.aux').removeClass('hide')

    });

    $('#_menu_blocks').on('mouseleave', '.vertical-menu a.item', function () {

            $(this).find('.aux:not(.keep)').addClass('hide')

    });



    $('#bottom_header').on('mouseenter', '.down_cadet', function () {


        $(this).removeClass('fa-angle-down').addClass('fa-link')

    });

    $('#bottom_header').on('mouseleave', '.down_cadet', function () {

        $(this).addClass('fa-angle-down').removeClass('fa-link')

    });


    $('.editor').each(function(i, obj) {
        $(obj).froalaEditor({
            iconsTemplate: 'font_awesome_5',

            toolbarInline: true,
            charCounterCount: false,
            toolbarButtons: [ 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|',  'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsMD: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|',  'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsSM: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|',  'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsXS: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|',  'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            defaultImageDisplay: 'inline',
            fontSize: ['8', '10', '12', '14','16', '18', '30', '60', '96'],

            zIndex: 10000,
            pastePlain: true,

        }).on('froalaEditor.contentChanged', function (e, editor, keyupEvent) {
            $('#save_button', window.parent.document).addClass('save button changed valid')
        });

    });

    $('.sortable').sortable({
        handle: '.item_handle',
        connectWith: ".link_list",
        items: ".item",
        stop: function (event, ui) {

            $('#save_button',window.parent.document).addClass('save button changed valid')


        }
    });


    $(document).on("click", "img", function () {
        open_image_control_panel($(this))

    })


    $(document).on("input propertychange", ".menu_label ", function () {

        parent.change_menu_label( $(this).closest('a').data('key'),  $(this).html())
        $('#save_button',window.parent.document).addClass('save button changed valid')

    })

})




function open_image_control_panel(element) {



    if (!$('#image_control_panel').hasClass('hide')) {
        return
    }

    var image_options={ }


    $('#image_control_panel').removeClass('hide').offset({
        top:  $(element).offset().top, left: $(element).offset().left
    }).addClass('in_use').data('element', $(element))


    if($('#image_control_panel').offset().left+$('#image_control_panel').width()>$( '#bottom_header' ).width()){
        $('#image_control_panel').offset({
            left: $('#image_control_panel').offset().left-($('#image_control_panel').offset().left+$('#image_control_panel').width()-$( '#bottom_header' ).width())
        })
    }



return;
    switch ($(element).data('type')){
        case 'image':

            break;

        case 'image_and_text':
             break;

    }

    if(type=='images'){
        image_options['set_width']=$(element).data('width');

        $('#image_control_panel .caption_tr').removeClass('hide')
        $('#update_images_block_image').attr('name','images')
        $('#image_control_panel').find('.image_caption').val($(element).closest('figure').find('figcaption').html())


    }else if(type=='category_categories'){

        $('#update_images_block_image').attr('name','category_categories')
        $('#image_control_panel .caption_tr').addClass('hide')
        height=220

        switch($(element).attr('size_class')){
            case 'panel_1':
                $('#image_control_panel .image_size').html('(226x'+height+')')
                image_options['fit_to_canvas']='226x'+height+''

                break;
            case 'panel_2':
                $('#image_control_panel .image_size').html('(470x'+height+')')
                image_options['fit_to_canvas']='470x'+height+''

                break;
            case 'panel_3':
                $('#image_control_panel .image_size').html('(714x'+height+')')
                image_options['fit_to_canvas']='714x'+height+''

                break;
            case 'panel_4':
                $('#image_control_panel .image_size').html('(958x'+height+')')
                image_options['fit_to_canvas']='958x'+height+''

                break;
            case 'panel_5':
                $('#image_control_panel .image_size').html('(1202x'+height+')')
                image_options['fit_to_canvas']='1202x'+height+''

                break;
        }



    }else if(type=='category_products'){

        $('#update_images_block_image').attr('name','category_products')
        $('#image_control_panel .caption_tr').addClass('hide')

        var height=$(element).data('height');
        switch($(element).attr('size_class')){
            case 'panel_1':
                $('#image_control_panel .image_size').html('(226x'+height+')')
                image_options['fit_to_canvas']='226x'+height+''

                break;
            case 'panel_2':
                $('#image_control_panel .image_size').html('(470x'+height+')')
                image_options['fit_to_canvas']='470x'+height+''

                break;
            case 'panel_3':
                $('#image_control_panel .image_size').html('(714x'+height+')')
                image_options['fit_to_canvas']='714x'+height+''

                break;
            case 'panel_4':
                $('#image_control_panel .image_size').html('(958x'+height+')')
                image_options['fit_to_canvas']='958x'+height+''

                break;
            case 'panel_5':
                $('#image_control_panel .image_size').html('(1202x'+height+')')
                image_options['fit_to_canvas']='1202x'+height+''

                break;
        }



    }else{

        $('#image_control_panel .caption_tr').addClass('hide')


        $('#update_images_block_image').attr('name','blackboard_image')


    }


// top: .25 * ($(element).offset().top + $(element).height()) / 2

    $('#image_control_panel').removeClass('hide').offset({
        top:  $(element).offset().top, left: $(element).offset().left
    }).addClass('in_use').data('element', $(element))



    console.log($( '#blocks' ).width())
    console.log( $('#image_control_panel').offset().left+$('#image_control_panel').width())

    if($('#image_control_panel').offset().left+$('#image_control_panel').width()>$( '#blocks' ).width()){
        $('#image_control_panel').offset({
            left: $('#image_control_panel').offset().left-($('#image_control_panel').offset().left+$('#image_control_panel').width()-$( '#blocks' ).width())
        })
    }



    $('#image_control_panel').find('.image_control_panel_upload_td input').attr('block_key',block_key).data('options',image_options)


    $('#image_control_panel').find('.image_tooltip').val($(element).attr('alt'))
    $('#image_control_panel').find('.image_link').val($(element).attr('link'))





    $('#image_control_panel').attr('old_image_src', $(element).attr('src'))

    $('#image_control_panel').find('.caption_align i').addClass('super_discreet').removeClass('selected')
    $('#image_control_panel').find('.caption_align i.' + $(element).attr('display_class')).removeClass('super_discreet').addClass('selected')

    $('#image_control_panel').find('.image_upload').data('img', $(element))



}


function show_column(key) {


    position_single_columns()

    $('._menu_block').addClass('hide')


    $('a.menu').removeClass('active')
    $('#menu_block_menu_' + key).removeClass('hide')


    if($('#menu_' + key).hasClass('dropdown')){
        $('#menu_' + key).addClass('active')
    }





}



function hide_column(key) {

    $('._menu_block').addClass('hide')
    $('a.menu').removeClass('active')
}





$(document).on('click', '.down_cadet', function (e) {
    $('#input_container_link').removeClass('hide').offset({
        top: $(this).offset().top - 20, left: $(this).offset().left + $(this).width() + 5
    }).data('item', $(this))
        , $('#input_container_link').find('input').val($(this).attr('url')).focus()


    if ($('#input_container_link').offset().left + $('#input_container_link').width() > $(window).width()) {
        $('#input_container_link').offset({
            left: $(window).width() - $('#input_container_link').width() - 40
        })
    }

})



$(document).on('click', '.item_delete', function (e) {

    $(this).closest('a').remove()
    $('#save_button',window.parent.document).addClass('save button changed valid')

})


$(document).on('click', '.item_link,.image_link', function (e) {


    $('.aux.keep').removeClass('keep').addClass('hide')


    $(this).closest('a').find('.aux').addClass('keep').removeClass('hide')

    $('#input_container_link').removeClass('hide').offset({
        top: $(this).offset().top - 20, left: $(this).offset().left + $(this).width() + 5
    }).data('item', $(this))
        , $('#input_container_link').find('input').val($(this).attr('url')).focus()


    if ($('#input_container_link').offset().left + $('#input_container_link').width() > $(window).width()) {
        $('#input_container_link').offset({
            left: $(window).width() - $('#input_container_link').width() - 40
        })
    }


})


$(document).on('click', '.add_link', function (e) {

    $("#link_stem_cell").clone().attr('id', '').insertBefore($(this))
    $('#save_button',window.parent.document).addClass('save button changed valid')


})

$(document).on('click', '.add_single_column_link', function (e) {

    $("#single_column_link_stem_cell").clone().attr('id', '').insertBefore($(this).closest('li'))
    $('#save_button',window.parent.document).addClass('save button changed valid')

})


function close_item_edit_link() {
    $('#input_container_link').addClass('hide')


    $('#input_container_link').data('item').closest('a').find('.aux').removeClass('keep').addClass('hide')


    $('#input_container_link').data('item').attr('url', $('#input_container_link').find('input').val())

}


$(document).on('click', 'a', function (e) {
    if (e.which == 1 && !e.metaKey && !e.shiftKey) {

        return false
    }
})



$(document).on('click', '.menu_icon', function (e) {

    $('#icons_control_center').removeClass('hide').offset({
        top: $(this).offset().top - 69, left: $(this).offset().left + $(this).width()
    }).data('item', $(this))


})


$(document).on('click', '.item_icon', function (e) {


    $('#icons_control_center').removeClass('hide').offset({
        top: $(this).offset().top - 69, left: $(this).offset().left + $(this).width()
    }).data('item', $(this))


})

$('#icons_control_center').on('click', 'i', function (e) {

    //console.log($('#icons_control_center').data('item'))

    $('#icons_control_center').data('item').removeClass(function (index, className) {

        //console.log(className)

        //console.log((className.match (/\bfa-\S+/g) || []).join(' '))

        return (className.match(/\bfa-\S+/g) || []).join(' ');
    }).removeClass('fa fab fas far fal').addClass('fa-fw').addClass($(this).attr('icon'))


    if ($(this).attr('icon') == 'fa fa-ban') {
        $('#icons_control_center').data('item').addClass('error very_discreet').attr('icon', '')
    } else {
        $('#icons_control_center').data('item').removeClass('error very_discreet').attr('icon', $(this).attr('icon'))
    }


    $('#icons_control_center').addClass('hide')



    if($('#icons_control_center').data('item').closest('a').hasClass('_column')){
        parent.change_menu_icon($('#icons_control_center').data('item').closest('a').data('key'),$(this).attr('icon'))
    }


    $('#save_button', window.parent.document).addClass('save button changed valid')


})


$("body").on('DOMSubtreeModified', ".header", function () {
    $('#save_button',window.parent.document).addClass('save button changed valid')

});

function save_header() {

    if (!$('#save_button', window.parent.document).hasClass('save')) {
        return;
    }

    $('#save_button', window.parent.document).find('i').addClass('fa-spinner fa-spin')




    var menu = []




    $('._menu_block').each(function (i, obj) {

        var menu_header_elemet= $('#menu_'+$(obj).data('key'))




        switch ($(obj).data('column_type')){
            case 'three_columns':

                sub_columns=[]

                $('.submenu',obj).each(function (i, obj2) {

                    column = {}

                    var type = $(obj2).data('type')
                    column.type = type


                    if (type == 'departments' || type == 'families' || type == 'web_departments' || type == 'web_families') {
                        column.page = $(obj2).data('page')


                    } else if (type == 'items') {



                        column.items = []

                        $('.item',obj2).each(function (i, obj3) {


                            //console.log($(obj3).find('.item_link').attr('url'))

                            column.items.push({
                                label: $(obj3).find('._item_label').html(),
                                icon: $(obj3).find('.item_icon').attr('icon'),
                                url: $(obj3).find('.item_link').attr('url')
                            })

                        })


                    } else if (type == 'text') {


                        var text_block=$(obj2).find('.editor')
                        if ($(text_block).hasClass('fr-box')) {
                            var text = $(text_block).froalaEditor('html.get')
                        } else {
                            var text = $(text_block).html()
                        }
                        column.image = $(obj2).find('img').attr('src')
                        column.url = $(obj2).find('img').attr('link')
                        column.title = $(obj2).find('img').attr('alt')
                        column.text = text
                    } else if (type == 'image') {
                        column.image = $(obj2).find('img').attr('src')
                        column.url = $(obj2).find('img').attr('link')
                        column.title = $(obj2).find('img').attr('alt')

                    }

                    sub_columns.push(column)
                })

                menu.push({
                    type: $(obj).data('column_type'),
                    show: (menu_header_elemet.hasClass('hide') ? false : true),
                    label: menu_header_elemet.find('.menu_label').html(),
                    icon: menu_header_elemet.find('.menu_icon').attr('icon'),
                    sub_columns:sub_columns

                })
                break;
            case 'single_column':

                items=[]
                $('.item', obj).each(function (i, obj2) {
                    items.push({
                        type:'item',
                        label: $(obj2).find('._label').html(),
                        url:$(obj2).find('.item_link').attr('url')

                    })
                })

                menu.push({
                    type: $(obj).data('column_type'),
                    show: (menu_header_elemet.hasClass('hide') ? false : true),
                    label: menu_header_elemet.find('.menu_label').html(),
                    icon: menu_header_elemet.find('.menu_icon').attr('icon'),
                    items:items

                })
                break;
            case 'nothing':

                menu.push({
                    type: $(obj).data('column_type'),
                    show: (menu_header_elemet.hasClass('hide') ? false : true),
                    label: menu_header_elemet.find('.menu_label').html(),
                    icon: menu_header_elemet.find('.menu_icon').attr('icon'),


                })

                break;

        }












    })


    console.log(menu)
    return;

    var ajaxData = new FormData();

    ajaxData.append("tipo", 'save_header')
    ajaxData.append("header_key", $('webpage_data').data('header_key'))
    ajaxData.append("header_data", JSON.stringify(menu))


    $.ajax({
        url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')
            } else if (data.state == '400') {
                swal(data.msg);
            }


        }, error: function () {

        }
    });


}


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
    ajaxData.append("parent", 'header')
    ajaxData.append("parent_key", '{$header_key}')
    ajaxData.append("options", JSON.stringify($(this).data('options')))
    ajaxData.append("response_type", 'webpage')

    var element = $(this)

    $.ajax({
        url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


        complete: function () {

        }, success: function (data) {

            // console.log(data)

            if (data.state == '200') {

                //console.log(element)

                if (element.attr('id') == 'upload_logo') {
                    $('#logo,#logo_edit').css('background-image', 'url(' + data.image_src + ')')
                    $('#logo_visibility').addClass('fa-eye').removeClass('hide')
                    $('#logo').attr('bg', data.image_src)

                } else if (element.attr('id') == 'upload_header_background') {
                    $('#topHeader,#topHeader_edit').css('background-image', 'url(' + data.image_src + ')')
                    $('#topHeader').attr('bg', data.image_src)
                    $('#header_background_visibility').addClass('fa-eye').removeClass('hide')

                } else {
                    element.closest('.dart').find('img.rimg').attr('src', data.image_src).attr('image_key', data.img_key).data('src', data.image_src)

                }

                $('#save_button', window.parent.document).addClass('save button changed valid')

            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "{t}OK{/t}"
                });
            }

            element.val('')

        }, error: function () {

        }
    });


});


function move_column_label(pre, post) {

    //console.log(pre+' '+post)

    if (post > pre) {


        $('#_columns ._column:eq(' + pre + ')').insertAfter('#_columns ._column:eq(' + post + ')');
    } else {


        $('#_columns ._column:eq(' + pre + ')').insertBefore('#_columns ._column:eq(' + post + ')');
    }
    $('#save_button',window.parent.document).addClass('save button changed valid')

}


function delete_column(key) {

    hide_column(key);
    $('#menu_' + key).remove();
    $('#menu_block_menu_' + key).remove();
    $('#save_button',window.parent.document).addClass('save button changed valid')

}

function move_column(key, pre, post) {

    //console.log(key+' '+pre+' '+post)


    if (post > pre) {


        $('#menu_block_menu_' + key + ' .submenu:eq(' + pre + ')').insertAfter('#menu_block_menu_' + key + ' .submenu:eq(' + post + ')');
    } else {


        $('#menu_block_menu_' + key + ' .submenu:eq(' + pre + ')').insertBefore('#menu_block_menu_' + key + ' .submenu:eq(' + post + ')');
    }
    $('#save_button',window.parent.document).addClass('save button changed valid')

}




function hide_column_label(key) {

    //console.log('#menu_'+key)
    $('#menu_' + key).addClass('hide')
    $('#save_button',window.parent.document).addClass('save button changed valid')

}

function show_column_label(key) {
    $('#menu_' + key).removeClass('hide')
    $('#save_button',window.parent.document).addClass('save button changed valid')

}

function change_column(type, key, subkey) {
    console.log(type+' x '+key+' y '+subkey)




    var ul=$('#submenu_'+key+'_'+subkey);





    if (ul.data(type)==type) {
        return;
    }

    if (type == 'departments' || type == 'families' || type == 'web_departments' || type == 'web_families') {
        var clone = $('#catalogue_stem_cell').clone()
    } else {
        var clone = $('#' + type + '_stem_cell').clone()
    }




    if (type == 'text' || type == 'image') {
        var id = 'update_image_' + key + '_' + subkey
        clone.find('input').attr('id', id)
        clone.find('label').attr('for', id)

    }


    ul.removeClass('departments families web_departments web_families items text image vertical-menu')

    if (type == 'items'||  type == 'departments' || type == 'families' || type == 'web_departments' || type == 'web_families') {

        ul.addClass('vertical-menu')
    }
    if (type == 'image' ) {

        ul.addClass('image')
    }
    if (type == 'text' ) {

        ul.addClass('text')

    }

    //ul.addClass(type)
    ul.html(clone.html())

    if (type == 'text' ) {
        ul.find('.new_editor').froalaEditor({
            iconsTemplate: 'font_awesome_5',

            toolbarInline: true,
            charCounterCount: false,
            toolbarButtons: [ 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|',  'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsMD: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|',  'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsSM: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|',  'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsXS: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|',  'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            defaultImageDisplay: 'inline',
            fontSize: ['8', '10', '12', '14','16', '18', '30', '60', '96'],

            zIndex: 10000,
            pastePlain: true,

        }).on('froalaEditor.contentChanged', function (e, editor, keyupEvent) {
            $('#save_button', window.parent.document).addClass('save button changed valid')
        });

    }



    ul.data('type', type)

    if (type == 'departments' || type == 'families' || type == 'web_departments' || type == 'web_families') {

        var page = '0-10'
        var page_label = '1-10'

        ul.attr('page', page)
        ul.attr('page_label', page_label)

        $.getJSON("ar_products.php?tipo=store_categories&key="+$('#webpage_data').data('store_key')+"&type=" + type + "&page=" + page, function (data) {

            //console.log(data.items)

            for (i = 0; i < data.items.length; i++) {
                //console.log(data.items[i])


                var html = '<a href="' + data.items[i].url + '">' + '<i class="fa fa-fw fa-caret-right"></i> ' + '<span >' + data.items[i].label + '</span></a>';


                ul.append(html)


            }


        });

    } else {
        ul.removeAttr('page')
        ul.removeAttr('page_label')
    }

    $('#save_button',window.parent.document).addClass('save button changed valid')

}

function edit_catalogue_paginator(page, page_label, key, subkey) {


    var submenu = $('#submenu_' + key + '_' + subkey )


    //submenu.data('page', page)
    //submenu.data('page_label', page_label)


    //console.log('#_3col_'+key+' ._3c_col:eq(' + subkey + ')')


    submenu.find('a').remove()

    $.getJSON("ar_products.php?tipo=store_categories&key="+$('#webpage_data').data('store_key')+"&type=" + submenu.data('type') + "&page=" + page, function (data) {

        //console.log(data.items)

        for (i = 0; i < data.items.length; i++) {
            //console.log(data.items[i])


            var html = '<a href="' + data.items[i].url + '">' + '<i class="fa fa-fw fa-caret-right"></i> ' + '<span >' + data.items[i].label + '</span></a>';


            submenu.append(html)


        }

        $('#save_button',window.parent.document).addClass('save button changed valid')

    });



}

function edit_column_type(type, key) {


    var menu = $('#menu_' + key)

    var menu_block = $('#menu_block_menu_' + key)

    menu_block.removeClass('vertical-menu single_column sortable menu_block')


    menu_block.data('column_type',type)


    switch(type){
        case 'three_columns':

            menu_block.addClass('menu_block')

            menu.addClass('dropdown active').removeClass('only_link')


            var clone = $('#three_columns_stem_cell').clone();
            clone.find('._1').attr('id','submenu_'+menu_block.data('key')+'_0').removeClass('_1')
            clone.find('._2').attr('id','submenu_'+menu_block.data('key')+'_1').removeClass('_1')
            clone.find('._3').attr('id','submenu_'+menu_block.data('key')+'_2').removeClass('_1')


            menu_block.html(clone.html()).css('left','0px')
            break;
        case 'single_column':
            menu_block.addClass('vertical-menu single_column sortable')
            menu.addClass('dropdown active').removeClass('only_link')

            menu_block.html($('#single_column_stem_cell').clone().html())




            var offset =  $('#menu_'+key).offset();

            menu_block.offset({
                left:offset.left
            })



            $('.sortable').sortable({
                handle: '.item_handle',
                connectWith: ".link_list",
                items: ".item"
            });
            break;
        case 'nothing':

            menu.removeClass('dropdown active').addClass('only_link')
            menu_block.html('')
            break;


    }




$('#menu_'+key).data('column_type',type)



    $('#save_button',window.parent.document).addClass('save button changed valid')
}



function add_column(key, label) {

    add_link_label=$('#webpage_data').data('add_link_label')

    $('#_columns').append($('<a  id="menu_' + key + '" class="menu _column dropdown"  data-column_type="single_column" data-key="'+key+'"> <i class="menu_icon fa fa-ban error very_discreet " icon="" ></i>  <span class="menu_label" contenteditable="true">' + label + '</span> <i class="down_cadet fal fa-fw fa-angle-down"></i>  </a>'))
$('#_menu_blocks').append($('<div id="menu_block_menu_'+key+'" class="_menu_block  hide vertical-menu single_column sortable" data-key="'+key+'"><a class="add_link like_button" href=""><i  class="fa item_icon fa-fw fa-plus"></i> <span class="_item_label">'+add_link_label+'</span></a></div>'))


    $('#save_button',window.parent.document).addClass('save button changed valid')

}