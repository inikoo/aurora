/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 April 2018 at 15:10:08 GMT+8, Plane Kuala Lumpur Malaysia, Muscat Oman , (Arabian Sea)
 Copyright (c) 2017, Inikoo
 Version 3.0*/

function menu_in() {


    menu_open = true;
    mouse_over_menu_link = true;


    var menu_block = $('#menu_block_menu_' + $(this).data('key'))


    menu_block.removeClass('hide')
    if (menu_block.hasClass('single_column')) {

        var offset = $(this).offset();
        console.log(offset)
        menu_block.offset({
            left: offset.left
        })

    }


    $('.menu').removeClass('active')
    $(this).addClass('active')


}

function menu_out() {


    mouse_over_menu_link = false
    if (!mouse_over_menu) {


        $('#menu_block_menu_' + $(this).data('key')).addClass('hide')
        $('#menu_' + $(this).data('key')).removeClass('active')


    }


}


function menu_block_in() {


    menu_open = true;
    mouse_over_menu = true;
    $(this).removeClass('hide')
    $('.menu').removeClass('active')
    $('#menu_' + $(this).data('key')).addClass('active')


}

function menu_block_out() {


    mouse_over_menu = false;
    var element = $(this)
    setTimeout(function () {
        if (mouse_over_menu_link == false) {
            element.addClass('hide')
            $('#menu_' + element.data('key')).removeClass('active')


            menu_open = false;
        }
    }, 100);
}


function menu_in_fast() {


    mouse_over_menu_link = true;
    if (menu_open == true) {
        $('._menu_block').addClass('hide')
        $('#menu_block_menu_' + $(this).data('key')).removeClass('hide')
        $('.menu').removeClass('active')
        $('#menu_' + $(this).data('key')).addClass('active')

        $('#_menu_blocks').width($('#top_header').width())

    }


}

function menu_out_fast() {


    mouse_over_menu_link = false;
}
            
            