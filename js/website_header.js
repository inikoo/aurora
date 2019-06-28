/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 May 2018 at 22:02:42 BST, Sheffield UK
 Copyright (c) 2018, Inikoo
 Version 3.0*/

droppedFiles = false;



$(function () {


    set_logo_position();
    set_header_style();




    $('#search_hanger').draggable({
        containment: "parent",
            axis: "y",
            stop: function() {
                $('#save_button', window.parent.document).addClass('save button changed valid')
            }
    }
    )

    document.addEventListener("paste", function (e) {
        e.preventDefault();
        var text = e.clipboardData.getData("text/plain");
        document.execCommand("insertHTML", false, text);
    });



    $('.header_text').draggable({


        containment: "parent",
        drag: function() {
            update_coordinates($(this))
        }

    })




    $(document).on('input paste', '[contenteditable=true]', function (e) {
        $('#save_button', window.parent.document).addClass('save button changed valid')
    });

    $(document).on('input paste', '.header_text_edit', function (e) {

        $('#'+$(this).closest('tr').data('key')).children().html($(this).html())

    });

    $(document).on('input propertychange paste', '.link_input input', function (e) {

        $('#'+$(this).closest('tr').data('key')).data('link',$(this).val())
        $('#save_button', window.parent.document).addClass('save button changed valid')

    });



    $('a').on( 'click',function (e) {

        e.preventDefault();
    });





    $("#color_picker_dialog input").spectrum({
        flat: false,
        showInput: true,
        allowEmpty: false,

        showAlpha: true,
        showPalette: true,
        showInitial: true,
        showButtons: true,
        hideAfterPaletteSelect: false,
        preferredFormat: "hex3",
        palette: [["#000", "#444", "#666", "#999", "#ccc", "#eee", "#f3f3f3", "#fff"], ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"], ["#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9", "#ead1dc"], ["#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6", "#d5a6bd"], ["#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3", "#c27ba0"], ["#c00", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"], ["#900", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"], ["#600", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763", "#20124d", "#4c1130"]],
        move: function (color) {


            var color_edit_dialog = $(this).closest('div')

            if (color == null) {
                change_color('', color_edit_dialog.data('element'), color_edit_dialog.data('scope'))
                change_color('', color_edit_dialog.data('element_color_picker'), 'color')

            } else {
                change_color(color.toRgbString(), color_edit_dialog.data('element'), color_edit_dialog.data('scope'))
                change_color(color.toRgbString(), color_edit_dialog.data('element_color_picker'), 'color')

            }

        },
        change: function (color) {

            console.log('show')
            console.log(color)

            $('#color_picker_dialog').addClass('hide')

            var color_edit_dialog = $(this).closest('div')

            if (color == null) {
                change_color('', color_edit_dialog.data('element'), color_edit_dialog.data('scope'))
                change_color('', color_edit_dialog.data('element_color_picker'), 'color')

            } else {
                change_color(color.toRgbString(), color_edit_dialog.data('element'), color_edit_dialog.data('scope'))
                change_color(color.toRgbString(), color_edit_dialog.data('element_color_picker'), 'color')

            }

        },
        hide: function (color) {


        }

    });


    $(document).on('click', '#text_styles_main_header td', function (e) {

        change_text_style($('#text_styles_main_header').data('element'),$(this).text())

        $('#text_styles_main_header').addClass('hide')
        $('#save_button', window.parent.document).addClass('save button changed valid')

    })

    $(document).on('click', '#text_styles_search_header td', function (e) {

        change_text_style($('#text_styles_search_header').data('element'),$(this).text())

        $('#text_styles_search_header').addClass('hide')
        $('#save_button', window.parent.document).addClass('save button changed valid')

    })



    $(document).on('click', '.color_picker', function (e) {


        var offset = $(this).offset()


        $('#color_picker_dialog').removeClass('hide').offset({
            'top': offset.top, 'left': offset.left
        }).data('scope', $(this).data('scope')).data('element', $(this).closest('.element_for_color').data('element')).data('element_color_picker', $(this).find('i:last-child'))

        $("#color_picker_dialog input").spectrum("show");
        $(".sp-container").offset({
            'top': $('#color_picker_dialog').offset().top
        })


        $("#color_picker_dialog input").spectrum("set", $(this).find('i:last-child').data('color'));

        return false
    });

    $(document).on('click', '.up_margins', function (e) {


        $('input',$(this).closest('.margins_container')).each(function( index,input ) {



            value=parseInt($(input).val())

            if(isNaN(value)){
                value=0;
            }

            //  console.log(value)

            $(input).val( value+1)

            //  console.log($(input))

            change_margins(input)
        })

    });
    $(document).on('click', '.down_margins', function (e) {



        $('input',$(this).closest('.margins_container')).each(function( index,input ) {


            value=parseInt($(input).val())

            if(isNaN(value)){
                value=0;
            }

            console.log(value)

            value=value-1
            if(value<0){
                value=0;
            }


            $(input).val( value)
            change_margins(input)
        })

    });


});



function set_header_style() {



    $('#main_settings').find('.header_height').val(parse_margin_value(styles['#top_header height'][2]))

    $('#main_settings').find('.scope_header_color i:last').css('color', styles['#top_header color'][2]).data('color', styles['#top_header color'][2])
    $('#main_settings').find('.scope_header_background-color i:last').css('color', styles['#top_header background-color'][2]).data('color', styles['#top_header background-color'][2])

    $('#main_settings').find('.logo_width').val(parse_margin_value(styles['#header_logo flex-basis'][2]))





}



function parse_margin_value(value) {

    value = parseInt(value)

    if (isNaN(value)) {
        value = '';
    }

    return value


}

function change_color(color, element, scope) {



    if($('#'+scope).data('type')!=undefined){

        $('#'+$('#'+scope).data('key')).css('color',color)

    }else{
        switch (scope) {



            case 'header_background-color':
                styles['#top_header background-color'][2] = color
                $('#top_header').css('background-color', color);
                break;

            default:

                $(element).css(scope, color);

                break;

        }
    }





    $('#save_button', window.parent.document).addClass('save button changed valid')


}


$(document).on('input propertychange,change', '.edit_margin', function (evt) {


    change_margins(this)


});

function change_margins(input) {

    if (!validate_signed_integer($(input).val(), 1000)) {
        $(input).removeClass('error')
        var value = $(input).val()

    } else {
        value = 0;

        $(input).addClass('error')
    }

    var element = $(input).closest('.element_for_margins').data('element')
    var scope = $(input).closest('.margins_container').data('scope')

    console.log(scope)

    switch (scope) {
        case 'header_height':
            console.log(value);

            var height = value + 'px'

            $('#top_header').css({
                'height': height,
            })


            styles['#top_header height'][2] = height;


            set_logo_position();



            break
        case 'logo_width':





            var width = value + 'px'

            $('#header_logo').css({
                'flex-basis': width,
            })


            set_logo_position();
            styles['#header_logo flex-basis'][2] = width;
            break;


            break
        case 'position_x':

            var key=$(input).closest('tr').data('key')
            var max_width=$('#main_header').width()-$('#'+key).width()

           // console.log(max_width)
           // console.log($('#'+key).width())

            if(max_width<value){
                $(input).addClass('error')

            }else{
                $(input).removeClass('error')
                var left = value + 'px'

                $('#'+key).css('left',left)
            }

break;
            break
        case 'position_y':

            var key=$(input).closest('tr').data('key')
            var max_height=$('#main_header').height()-$('#'+key).height()

            // console.log(max_width)
            // console.log($('#'+key).width())

            if(max_height<value){
                $(input).addClass('error')

            }else{
                $(input).removeClass('error')
                var top = value + 'px'

                $('#'+key).css('top',top)
            }

            break;


        default:
    }


    // element.css(scope+'-'+$(input).data('margin'), value + "px")


    $('#save_button', window.parent.document).addClass('save button changed valid')

}

function save_header(){

    if (!$('#save_button', window.parent.document).hasClass('save')) {
        return



    }


    var settings={
        'search_top':$('#search_hanger').offset().top,
        'logo_website':  $('#website_logo').attr('src'),
        'logo_top_margin':  $('#website_logo').css('margin-top')
    }

    if($('#favicon', window.parent.document).attr('src')!='/art/favicon_empty.png'){
        settings.favicon= $('#favicon', window.parent.document).attr('src')
    }

    var header_texts=[]
    $('#main_header .header_text ').each(function (i, header_text) {

        header_texts.push({
                'text':$.trim($(header_text).text()),
                'type':$(header_text).children().attr('type'),
                'top':$(header_text).position().top,
                'left':$(header_text).position().left,
                'color':$(header_text).css('color'),
                'link':$(header_text).data('link')

            }

        )
    })


    settings['header_texts']=header_texts;



    var header_texts=[]
    $('#search_header .header_text ').each(function (i, header_text) {

        header_texts.push({
                'text':$.trim($(header_text).text()),
                'type':$(header_text).children().attr('type'),
                'top':$(header_text).position().top,
                'left':$(header_text).position().left,
                'color':$(header_text).css('color'),
                'link':$(header_text).data('link')

            }

        )
    })


    settings['search_texts']=header_texts;



    console.log(settings)
   // return;

    var ajaxData = new FormData();

    ajaxData.append("tipo", 'update_website_styles')
    ajaxData.append("key",$('#webpage_data').data('website_key') )
    ajaxData.append("styles", JSON.stringify(styles))
    ajaxData.append("settings", JSON.stringify(settings))





    $.ajax({
        url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }


        }, error: function () {

        }
    });




}


function update_coordinates(element){


    $('#header_text_'+element.attr('id')).find('.x')
    $('#header_text_'+element.attr('id')).find('.x').val(element.position().left).removeClass('error')
    $('#header_text_'+element.attr('id')).find('.y').val(element.position().top).removeClass('error')
    $('#save_button', window.parent.document).addClass('save button changed valid')
}


function open_header_text_edit_link(element){

    var tr =$(element).closest('tr')

    if(tr.find('.link_input').hasClass('hide')){
        tr.find('.style').addClass('hide')
        tr.find('.link_input').removeClass('hide').attr('colspan',4)
    }else{
        tr.find('.style').removeClass('hide')
        tr.find('.link_input').addClass('hide').attr('colspan',1)
    }



}




function open_text_styles_main_header(element){


    $('#text_styles_main_header').removeClass('hide').offset({
        top:  $(element).offset().top - 0.5* $('#text_styles_main_header').height()+7 , left: $(element).offset().left
    }).data('element',$(element))


}

function open_text_styles_search_header(element){


    $('#text_styles_search_header').removeClass('hide').offset({
        top:  $(element).offset().top - 0.5* $('#text_styles_search_header').height()+7 , left: $(element).offset().left
    }).data('element',$(element))


}


function delete_header_text(element){


  $('#'+$(element).closest('tr').data('key')).remove();
    $(element).closest('tr').remove()
    $('#save_button', window.parent.document).addClass('save button changed valid')

}

function add_header_text(){

    new_text=$('<div class="header_text" data-link="" style="position: absolute;left:20px"><h1 type="H1">Webpage title</h1></div>')
    new_text.uniqueId()
    new_text.appendTo($('#main_header'))
    new_text.draggable({
        containment: "parent",
        drag: function() {
            update_coordinates($(this))
        }

    })

    new_text=$('<tr id="header_text_'+new_text.attr('id')+'" data-key="'+new_text.attr('id')+'" >' +
        '<td><i onclick="delete_header_text(this)" class="fa fa-trash-alt like_button"></i></td>  ' +

        '<td><span class="margins_container unselectable  " data-scope="position_x"><i class="fa fa-minus-circle down_margins"></i> <input class="x edit_margin" value="'+ new_text.position().left+'"> <i class="fa fa-plus-circle up_margins"></i></span></td>' +
        '<td><span class="margins_container unselectable  " data-scope="position_y"><i class="fa fa-minus-circle down_margins"></i> <input class="y edit_margin" value="'+ new_text.position().top+'" > <i class="fa fa-plus-circle up_margins"></i></span></td>' +
        '<td class="style"><i onclick="open_header_text_edit_link(this)" class="link like_button fa discreet fa-link"></i></td>'+
        '<td class="link_input hide"><i  onclick="open_header_text_edit_link(this)" class="like_button fa fa-window-close padding_right_10"></i> <input style="width: 400px" val="" placeholder="https://"/> </td>'+
        '<td class="style"><span onclick="open_text_styles_main_header(this)" class="like_button type">H1</span></td>'+
        '<td class="style"><span id="header_text_color_'+new_text.attr('id')+'" data-key="'+new_text.attr('id')+'" data-type="header_text" data-scope="header_text_color_'+new_text.attr('id')+'" class="fa-stack color_picker scope_header_color like_button"> <i class="fas fa-circle fa-stack-1x "></i> <i data-color=#555" style="color:#555" class="fas fa-circle fa-stack-1x "></i> </span></td>'+

        '<td class="style text"><span class="header_text_edit" contenteditable="true">Webpage title</span></td></tr>')
    new_text.appendTo($('#header_texts_list'))

   $('#save_button', window.parent.document).addClass('save button changed valid')
}




function add_search_text(){

    new_text=$('<div class="header_text" data-link="" style="position: absolute;left:20px"><small type="N-">Text</small></div>')
    new_text.uniqueId()
    new_text.appendTo($('#search_header'))
    new_text.draggable({
        containment: "parent",
        drag: function() {
            update_coordinates($(this))
        }

    })

    new_text=$('<tr id="header_text_'+new_text.attr('id')+'" data-key="'+new_text.attr('id')+'" >' +
        '<td><i onclick="delete_header_text(this)" class="fa fa-trash-alt like_button"></i></td>  ' +

        '<td><span class="margins_container unselectable  " data-scope="position_x"><i class="fa fa-minus-circle down_margins"></i> <input class="x edit_margin" value="'+ new_text.position().left+'"> <i class="fa fa-plus-circle up_margins"></i></span></td>' +
        '<td><span class="margins_container unselectable  " data-scope="position_y"><i class="fa fa-minus-circle down_margins"></i> <input class="y edit_margin" value="'+ new_text.position().top+'" > <i class="fa fa-plus-circle up_margins"></i></span></td>' +
        '<td class="style"><i onclick="open_header_text_edit_link(this)" class="link like_button fa discreet fa-link"></i></td>'+
        '<td class="link_input hide"><i  onclick="open_header_text_edit_link(this)" class="like_button fa fa-window-close padding_right_10"></i> <input style="width: 400px" val="" placeholder="https://"/> </td>'+
        '<td class="style"><span onclick="open_text_styles_search_header(this)" class="like_button type">N-</span></td>'+
        '<td class="style"><span id="header_text_color_'+new_text.attr('id')+'" data-key="'+new_text.attr('id')+'" data-type="header_text" data-scope="header_text_color_'+new_text.attr('id')+'" class="fa-stack color_picker scope_header_color like_button"> <i class="fas fa-circle fa-stack-1x "></i> <i data-color=#555" style="color:#555" class="fas fa-circle fa-stack-1x "></i> </span></td>'+

        '<td class="style text"><span class="header_text_edit" contenteditable="true">Text</span></td></tr>')
    new_text.appendTo($('#search_texts_list'))

    $('#save_button', window.parent.document).addClass('save button changed valid')



}


function  change_text_style(element,style){



    var key=$(element).closest('tr').data('key')

    switch (style){
        case 'H1++':


            $('#'+key).children().replaceWith(function() {
                return $("<h1>", {
                        "class": 'huge',
                        html: $(this).html(),
                        type: style
                });
            });

            break;

        case 'H1+':


            $('#'+key).children().replaceWith(function() {
                return $("<h1>", {
                    "class": 'big',
                    html: $(this).html(),
                    type: style
                });
            });

            break;

        case 'H1':


            $('#'+key).children().replaceWith(function() {
                return $("<h1>", {

                    html: $(this).html(),
                    type: style
                });
            });

            break;
        case 'H2':


            $('#'+key).children().replaceWith(function() {
                return $("<h2>", {

                    html: $(this).html(),
                    type: style
                });
            });

            break;
        case 'H3':


            $('#'+key).children().replaceWith(function() {
                return $("<h3>", {

                    html: $(this).html(),
                    type: style
                });
            });

            break;
        case 'N':


            $('#'+key).children().replaceWith(function() {
                return $("<span>", {

                    html: $(this).html(),
                    type: style
                });
            });

            break;
        case 'N b':


            $('#'+key).children().replaceWith(function() {
                return $("<span>", {
                    "class": 'bold',
                    html: $(this).html(),
                    type: style
                });
            });

            break;

        case 'N-':
            console.log($('#'+key))

            $('#'+key).children().replaceWith(function() {
                return $("<small>", {

                    html: $(this).html(),
                    type: style
                });
            });

            break;
        case 'N- b':
            console.log($('#'+key))

            $('#'+key).children().replaceWith(function() {
                return $("<small>", {
                    "class": 'bold',
                    html: $(this).html(),
                    type: style
                });
            });

            break;


    }



    $(element).html(style)

}
