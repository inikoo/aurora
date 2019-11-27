/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 April 2018 at 19:35:01 BST, Sheffield UK
 Copyright (c) 2018, Inikoo
 Version 3.0*/

$(function () {
    $('#text_block_style').draggable({
        handle: ".handle", containment: ".site_wrapper"
    });

    $("#color_picker_dialog input").spectrum({
       flat: false,
        showInput: true,
        allowEmpty: true,

        showAlpha: true,
        showPalette: true,
        showInitial: true,
        showButtons: true,
        hideAfterPaletteSelect:false,
        preferredFormat: "hex3",
        palette: [["#000", "#444", "#666", "#999", "#ccc", "#eee", "#f3f3f3", "#fff"], ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"], ["#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9", "#ead1dc"], ["#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6", "#d5a6bd"], ["#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3", "#c27ba0"], ["#c00", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"], ["#900", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"], ["#600", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763", "#20124d", "#4c1130"]],
        move: function(color) {


            var color_edit_dialog=$(this).closest('div')

            if(color==null){
                change_color('',color_edit_dialog.data('element'),color_edit_dialog.data('scope'))
                change_color('',color_edit_dialog.data('element_color_picker'),'color')

            }else{
                change_color(color.toRgbString(),color_edit_dialog.data('element'),color_edit_dialog.data('scope'))
                change_color(color.toRgbString(),color_edit_dialog.data('element_color_picker')  ,'color')

            }

        },
        change: function(color) {

            console.log('show')
           console.log(color)

            $('#color_picker_dialog').addClass('hide')

            var color_edit_dialog=$(this).closest('div')

            if(color==null){
                change_color('',color_edit_dialog.data('element'),color_edit_dialog.data('scope'))
                change_color('',color_edit_dialog.data('element_color_picker'),'color')

            }else{
                change_color(color.toRgbString(),color_edit_dialog.data('element'),color_edit_dialog.data('scope'))
                change_color(color.toRgbString(),color_edit_dialog.data('element_color_picker')  ,'color')

            }

        },
        hide: function(color) {



        }

    });

    $('.color_picker').on( "click", function() {

        var offset = $(this).offset()


        $('#color_picker_dialog').removeClass('hide').offset({
            'top': offset.top, 'left': offset.left
        }).data('scope',$(this).data('scope')).data('element',$(this).closest('.element_for_color').data('element')).data('element_color_picker',$(this).find('i:last-child'))

        $("#color_picker_dialog input").spectrum("show");
        $(".sp-container").offset({
            'top':$('#color_picker_dialog').offset().top
        })



        $("#color_picker_dialog input").spectrum("set",$(this).find('i:last-child').data('color') );

        return false
    });

    $('.up_margins').on( "click", function() {
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


    $('.down_margins').on( "click", function() {
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


function open_edit_text_style(element) {
    var offset = $(element).offset()
    $('#text_block_style').removeClass('hide').offset({
        'top': offset.top, 'left': offset.left - $('#text_block_style').width() + $(element).width()
    }).data('element',$(element).closest('div').find('.text_block'))


    text_block=$(element).closest('.text_block_container').find('.text_block')


    var color=text_block.css('color')
   // console.log(color)
    if(color!='rgba(0, 0, 0, 0)'){


        $('#text_block_style .scope_color i:last-child').css('color',color).data('color',color)
    }else{
        $('#text_block_style .scope_color i:last-child').data('color','')
    }

    var color=text_block.css('background-color')
    if(color!='rgba(0, 0, 0, 0)'){
        $('#text_block_style .scope_background-color i:last-child').css('color',color).data('color',color)
    }else{
        $('#text_block_style .scope_background-color i:last-child').data('color','')
    }

    var color=text_block.css('border-color')
    if(color!='rgba(0, 0, 0, 0)'){
        $('#text_block_style .scope_border-color i:last-child').css('color',color).data('color',color)
    }else{
        $('#text_block_style .scope_border-color i:last-child').data('color','')
    }

    console.log($(text_block))

    $('#text_block_style').find('.margin input.top').val(parse_margin_value(text_block.css('margin-top')))
    $('#text_block_style').find('.margin input.bottom').val(parse_margin_value(text_block.css('margin-bottom')))
    $('#text_block_style').find('.margin input.left').val(parse_margin_value(text_block.css('margin-left')))
    $('#text_block_style').find('.margin input.right').val(parse_margin_value(text_block.css('margin-right')))

    $('#text_block_style').find('.padding input.top').val(parse_margin_value(text_block.css('padding-top')))
    $('#text_block_style').find('.padding input.bottom').val(parse_margin_value(text_block.css('padding-bottom')))
    $('#text_block_style').find('.padding input.left').val(parse_margin_value(text_block.css('padding-left')))
    $('#text_block_style').find('.padding input.right').val(parse_margin_value(text_block.css('padding-right')))


    $('#text_block_style').find('.border-width input.top').val(parse_margin_value(text_block.css('border-top-width')))
    $('#text_block_style').find('.border-width input.bottom').val(parse_margin_value(text_block.css('border-bottom-width')))
    $('#text_block_style').find('.border-width input.left').val(parse_margin_value(text_block.css('border-left-width')))
    $('#text_block_style').find('.border-width input.right').val(parse_margin_value(text_block.css('border-right-width')))


}

function parse_margin_value(value){

    value=parseInt(value)

    if(isNaN(value)){
        value='';
    }

    return value


}

function change_color(color,element,scope){

    $(element).css(scope,color);
    $('#save_button',window.parent.document).addClass('save button changed valid')



}



$(document).on('input propertychange,change', '.edit_block_margin', function (evt) {

    change_block_margins(this)


});

function change_block_margins(input){

    if (!validate_signed_integer($(input).val(), 300)) {
        $(input).removeClass('error')
        var value = $(input).val()
    } else {
        value = 0;
        $(input).addClass('error')
    }

    var element=$(input).closest('.element_for_margins').data('element')
    var scope=$(input).closest('.margins_container').data('scope')



    element.css(scope+'-'+$(input).data('margin'), value + "px")



    $('#save_button',window.parent.document).addClass('save button changed valid')

}