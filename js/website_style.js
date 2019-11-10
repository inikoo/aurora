/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2018 at 16:19:12 BST, Sheffield UK
 Copyright (c) 2018, Inikoo
 Version 3.0*/


$(function () {






    $('#search_hanger').draggable({
        containment: "parent" }
    )

    document.addEventListener("paste", function (e) {
        e.preventDefault();
        var text = e.clipboardData.getData("text/plain");
        document.execCommand("insertHTML", false, text);
    });


    $(document).on('input paste', '[contenteditable=true]', function (e) {
        $('#save_button', window.parent.document).addClass('save button changed valid')
    });


    $('a').on( 'click',function (e) {

        e.preventDefault();
    });




    $( '.empty' ).on( "mouseenter mouseleave",  function (e) {
        $(this).css("background-color", e.type === "mouseenter" ? styles['.empty:hover background-color'][2] : styles['.empty background-color'][2])
        $(this).css("color", e.type === "mouseenter" ? styles['.empty:hover color'][2] : styles['.empty color'][2])
    } );


    $('.ordered').on("mouseenter mouseleave",function (e) {
        $(this).css("background-color", e.type === "mouseenter" ? styles['.ordered:hover background-color'][2] : styles['.ordered background-color'][2])
        $(this).css("color", e.type === "mouseenter" ? styles['.ordered:hover color'][2] : styles['.ordered color'][2])

    });
    $('.out_of_stock').on("mouseenter mouseleave",function (e) {
        $(this).css("background-color", e.type === "mouseenter" ? styles['.out_of_stock:hover background-color'][2] : styles['.out_of_stock background-color'][2])
        $(this).css("color", e.type === "mouseenter" ? styles['.out_of_stock:hover color'][2] : styles['.out_of_stock color'][2])

    });
    $('.launching_soon').on("mouseenter mouseleave",function (e) {
        $(this).css("background-color", e.type === "mouseenter" ? styles['.launching_soon:hover background-color'][2] : styles['.launching_soon background-color'][2])
        $(this).css("color", e.type === "mouseenter" ? styles['.launching_soon:hover color'][2] : styles['.launching_soon color'][2])

    });


    $('#bottom_header .button').on("mouseenter mouseleave",function (e) {
        $(this).css("background-color", e.type === "mouseenter" ? styles['#bottom_header .button:hover background-color'][2] : styles['#bottom_header .button background-color'][2])
        $(this).css("color", e.type === "mouseenter" ? styles['#bottom_header .button:hover color'][2] : styles['#bottom_header .button color'][2])

    });

    $('#bottom_header a.menu.dropdown').on("mouseenter mouseleave",function (e) {
        $(this).css("background-color", e.type === "mouseenter" ? styles['#bottom_header a.menu.dropdown:hover background-color'][2] : styles['#bottom_header a.menu background-color'][2])
        $(this).css("color", e.type === "mouseenter" ? styles['#bottom_header a.menu.dropdown:hover color'][2] : styles['#bottom_header a.menu color'][2])

    });

    $('.vertical-menu a').on("mouseenter mouseleave",function (e) {
        $(this).css("background-color", e.type === "mouseenter" ? styles['.vertical-menu a:hover background-color'][2] : styles['.vertical-menu a background-color'][2])
        $(this).css("color", e.type === "mouseenter" ? styles['.vertical-menu a:hover color'][2] : styles['.vertical-menu a color'][2])

    });


    $('.object_control_panel').draggable({
        handle: ".handle", containment: ".site_wrapper"
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

    $('.color_picker').on("click", function () {

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


});


function open_button_style() {

    $('.object_control_panel').addClass('hide')


    var offset = $('#basket_go_to_checkout').offset()
    $('#button_style').removeClass('hide').offset({
        'top': $('#basket_go_to_checkout').offset().top, 'left': offset.left - $('#button_style').width() - 17
    })


    $('#button_style').find('.scope_color i:last').css('color', styles['.sky-form .button color'][2]).data('color', styles['.sky-form .button color'][2])
    $('#button_style').find('.scope_background-color i:last').css('color', styles['.sky-form .button background-color'][2]).data('color', styles['.sky-form .button background-color'][2])

}


function open_navigation_style() {

    $('.object_control_panel').addClass('hide')


    var offset = $('#basket_go_to_checkout').offset()
    $('#navigation_style').removeClass('hide').offset({
        'top': $('.navigation').offset().top, 'left': offset.left - $('#navigation_style').width()
    })


    $('#navigation_style').find('.navigation_bottom_border').val(parse_margin_value(styles['.top_body border-bottom-width'][2]))


    $('#navigation_style').find('.scope_navigation_color i:last').css('color', styles['.top_body color'][2]).data('color', styles['.top_body color'][2])
    $('#navigation_style').find('.scope_navigation_background-color i:last').css('color', styles['.top_body background-color'][2]).data('color', styles['.top_body background-color'][2])
    $('#navigation_style').find('.scope_navigation_border_bottom_color i:last').css('color', styles['.top_body border-bottom-color'][2]).data('color', styles['.top_body border-bottom-color'][2])


}

function open_edit_body_style(element) {

    $('.object_control_panel').addClass('hide')


    var offset = $('#basket_go_to_checkout').offset()
    $('#body_style').removeClass('hide').offset({
        'top': $(element).offset().top, 'left': offset.left - $('#body_style').width()
    })


    $('#body_style').find('.scope_body_color i:last').css('color', styles['body color'][2]).data('color', styles['body color'][2])
    $('#body_style').find('.scope_body_background-color i:last').css('color', styles['.site_wrapper background-color'][2]).data('color', styles['.site_wrapper background-color'][2])


    $('#body_style').find('.scope_outside_background-color i:last').css('color', styles['body background-color'][2]).data('color', styles['body background-color'][2])


}


function open_header_style() {

    $('.object_control_panel').addClass('hide')


    var offset = $('#logout').offset()

    console.log(offset)

    $('#header_style').removeClass('hide').offset({
        'top': $('#top_header').height(), 'left': offset.left
    })

    $('#header_style').find('.header_height').val(parse_margin_value(styles['#top_header height'][2]))

    $('#header_style').find('.scope_header_color i:last').css('color', styles['#top_header color'][2]).data('color', styles['#top_header color'][2])
    $('#header_style').find('.scope_header_background-color i:last').css('color', styles['#top_header background-color'][2]).data('color', styles['#top_header background-color'][2])


}


function open_menu_style() {

    $('.object_control_panel').addClass('hide')

    var offset = $('#logout').offset()


    $('#menu_bar_style').removeClass('hide').offset({
        'top': offset.top + 36, 'left': offset.left - 30
    })




    $('#menu_bar_style').find('.scope_menu_bar_background-color i:last').css('color', styles['#bottom_header background-color'][2]).data('color', styles['#bottom_header background-color'][2])


    $('#menu_bar_style').find('.scope_menu_button_color i:last').css('color', styles['#bottom_header .button color'][2]).data('color', styles['#bottom_header .button color'][2])
    $('#menu_bar_style').find('.scope_menu_button_background-color i:last').css('color', styles['#bottom_header .button background-color'][2]).data('color', styles['#bottom_header .button background-color'][2])
    $('#menu_bar_style').find('.scope_menu_button_hover_color i:last').css('color', styles['#bottom_header .button:hover color'][2]).data('color', styles['#bottom_header .button:hover color'][2])
    $('#menu_bar_style').find('.scope_menu_button_hover_background-color i:last').css('color', styles['#bottom_header .button:hover background-color'][2]).data('color', styles['#bottom_header .button:hover background-color'][2])

    $('#menu_bar_style').find('.scope_menu_color i:last').css('color', styles['#bottom_header a.menu color'][2]).data('color', styles['#bottom_header a.menu color'][2])
    $('#menu_bar_style').find('.scope_menu_hover_color i:last').css('color', styles['#bottom_header a.menu.dropdown:hover color'][2]).data('color', styles['#bottom_header a.menu.dropdown:hover color'][2])

    $('#menu_bar_style').find('.scope_menu_background-color i:last').css('color', styles['#bottom_header a.menu background-color'][2]).data('color', styles['#bottom_header a.menu background-color'][2])
    $('#menu_bar_style').find('.scope_menu_hover_background-color i:last').css('color', styles['#bottom_header a.menu:hover background-color'][2]).data('color', styles['#bottom_header a.menu:hover background-color'][2])


    $('#menu_bar_style').find('.scope_submenu_background-color i:last').css('color', styles['.menu_block background-color'][2]).data('color', styles['.menu_block background-color'][2])
    $('#menu_bar_style').find('.scope_submenu_color i:last').css('color', styles['.menu_block color'][2]).data('color', styles['.menu_block color'][2])

    $('#menu_bar_style').find('.scope_submenu_item_color i:last').css('color', styles['.vertical-menu a color'][2]).data('color', styles['.vertical-menu a color'][2])
    $('#menu_bar_style').find('.scope_submenu_item_background-color i:last').css('color', styles['.vertical-menu a background-color'][2]).data('color', styles['.vertical-menu a background-color'][2])

    $('#menu_bar_style').find('.scope_submenu_item_hover_color i:last').css('color', styles['.vertical-menu a:hover color'][2]).data('color', styles['.vertical-menu a:hover color'][2])
    $('#menu_bar_style').find('.scope_submenu_item_hover_background-color i:last').css('color', styles['.vertical-menu a:hover background-color'][2]).data('color', styles['.vertical-menu a:hover background-color'][2])


}


function open_footer_style() {

    $('.object_control_panel').addClass('hide')


    var offset = $('footer').offset()


    $('#footer_style').removeClass('hide').offset({
        'top': offset.top - $('#footer_style').height() - 3, 'left': 100
    })


    $('#footer_style').find('.scope_footer_color i:last').css('color', styles['footer color'][2]).data('color', styles['footer color'][2])
    $('#footer_style').find('.scope_footer_background-color i:last').css('color', styles['footer background-color'][2]).data('color', styles['footer background-color'][2])

    $('#footer_style').find('.scope_lower_footer_color i:last').css('color', styles['footer .copyright color'][2]).data('color', styles['footer .copyright color'][2])
    $('#footer_style').find('.scope_lower_footer_background-color i:last').css('color', styles['footer .copyright background-color'][2]).data('color', styles['footer .copyright background-color'][2])


}

function open_edit_product_wrap_style(element) {


    $('.object_control_panel').addClass('hide')


    var offset = $(element).offset()
    $('#product_wrap_style').removeClass('hide').offset({
        'top': offset.top, 'left': offset.left + $(element).width() + 1
    }).data('element', element)




    footer_tag = $(element).data('element')

    console.log(footer_tag)

    if(footer_tag=='.add_to_portfolio'){
        $('#product_wrap_style .order_button_background_tr').addClass('hide')
    }else{
        $('#product_wrap_style .order_button_background_tr').removeClass('hide')

    }


    $('#product_wrap_style').find('.border-width input.top').val(parse_margin_value(styles['.product_block border-top-width'][2]))
    $('#product_wrap_style').find('.border-width input.bottom').val(parse_margin_value(styles['.product_block border-bottom-width'][2]))
    $('#product_wrap_style').find('.border-width input.left').val(parse_margin_value(styles['.product_block border-left-width'][2]))
    $('#product_wrap_style').find('.border-width input.right').val(parse_margin_value(styles['.product_block border-right-width'][2]))


    $('#product_wrap_style .scope_product_container_color i:last-child').css('color', styles['.product_wrap color'][2]).data('color', styles['.product_wrap color'][2])

    $('#product_wrap_style .scope_border-color i:last-child').css('color', styles['.product_block border-color'][2]).data('color', styles['.product_block border-color'][2])


    $('#product_wrap_style').find('.scope_price_color i:last').css('color', styles['.product_price color'][2]).data('color', styles['.product_price color'][2])

    $('#product_wrap_style').find('.scope_footer_color i:last').css('color', styles[footer_tag + ' color'][2]).data('color', styles[footer_tag + ' color'][2])
    $('#product_wrap_style').find('.scope_footer_hover_color i:last').css('color', styles[footer_tag + ':hover color'][2]).data('color', styles[footer_tag + ':hover color'][2])

    $('#product_wrap_style').find('.scope_footer_background-color i:last').css('color', styles[footer_tag + ' background-color'][2]).data('color', styles[footer_tag + ' background-color'][2])


    $('#product_wrap_style').find('.scope_footer_hover_background-color i:last').css('color', styles[footer_tag + ':hover background-color'][2]).data('color', styles[footer_tag + ':hover background-color'][2])


}


function parse_margin_value(value) {

    value = parseInt(value)

    if (isNaN(value)) {
        value = '';
    }

    return value


}

function change_color(color, element, scope) {


    switch (scope) {
        case 'product_container_color':


            $('.product_wrap').css('color', color);
            styles['.product_wrap color'][2] = color

            break;
        case 'price_color':


            $('.product_price').css('color', color);
            styles['.product_price color'][2] = color

            break;


        case 'real_footer_color':
            $('footer').css('color', color);
            styles['footer color'][2] = color
            break;
        case 'real_footer_background-color':
            $('footer').css('background-color', color);
            styles['footer background-color'][2] = color
            break;

        case 'lower_footer_color':
            $('footer .copyright').css('color', color);
            styles['footer .copyright color'][2] = color
            break;
        case 'lower_footer_background-color':
            $('footer .copyright').css('background-color', color);
            styles['footer .copyright background-color'][2] = color
            break;


        case 'menu_bar_background-color':
            $('#bottom_header').css('background-color', color);
            styles['#bottom_header background-color'][2] = color
            break;


        case 'menu_button_color':
            $('#bottom_header .button').css('color', color);
            styles['#bottom_header .button color'][2] = color
            break;
        case 'menu_button_background-color':
            $('#bottom_header .button').css('background-color', color);
            styles['#bottom_header .button background-color'][2] = color
            break;

        case 'menu_button_hover_color':
            $('#bottom_header .button:hover').css('color', color);
            styles['#bottom_header .button:hover color'][2] = color
            break;
        case 'menu_button_hover_background-color':
            $('#bottom_header .button:hover').css('background-color', color);
            styles['#bottom_header .button:hover background-color'][2] = color
            break;


        case 'footer_color':


            $(element).find($(element).data('element')).css('color', color);
            styles[$(element).data('element') + ' color'][2] = color

            break;
        case 'footer_hover_color':
            styles[$(element).data('element') + ':hover color'][2] = color
            break;
        case 'footer_background-color':


            $(element).find($(element).data('element')).css('background-color', color);
            styles[$(element).data('element') + ' background-color'][2] = color


            break;
        case 'footer_hover_background-color':
            styles[$(element).data('element') + ':hover background-color'][2] = color

        case 'button_color':


            $('.sky-form .button').css('color', color);
            styles['.sky-form .button color'][2] = color
            break;
        case 'button_background-color':


            $('.sky-form .button').css('background-color', color);
            styles['.sky-form .button background-color'][2] = color
            break;

        case 'scope_outside_background-color':
            styles['body background-color'][2] = color
            $('body').css('background-color', color);
            break;

        case 'body_background-color':
            styles['.site_wrapper background-color'][2] = color
            $('.site_wrapper').css('background-color', color);
            break;

        case 'body_color':
            styles['body color'][2] = color
            $('body').css('color', color);
            break;
        case 'navigation_color':
            styles['.top_body color'][2] = color
            $('.top_body').css('color', color);
            break;
        case 'navigation_background-color':
            styles['.top_body background-color'][2] = color
            $('.top_body').css('background-color', color);
            break;
        case 'navigation_border_bottom_color':
            styles['.top_body border-bottom-color'][2] = color
            $('.top_body').css('border-bottom-color', color);
            break;

        case 'menu_color':

            styles['#bottom_header a.menu color'][2] = color
            $('#bottom_header a.menu').css('color', color);


            break;
        case 'menu_hover_color':
            styles['#bottom_header a.menu.dropdown:hover color'][2] = color
            styles['#bottom_header a.menu.active color'][2] = color

            break;

        case 'menu_background-color':
            styles['#bottom_header a.menu background-color'][2] = color
            $('#bottom_header a.menu').css('background-color', color);
            break;
        case 'menu_hover_background-color':
            styles['#bottom_header a.menu.dropdown:hover background-color'][2] = color
            styles['#bottom_header a.menu.active background-color'][2] = color

            styles['.menu_block border-color'][2] = color
            styles['.single_column border-color'][2] = color



            $('.single_column').css('border-color', color);

            $('.menu_block').css('border-color', color);

            break;

        case 'submenu_background-color':
            styles['.menu_block background-color'][2] = color
            $('.menu_block').css('background-color', color);
            break;
        case 'submenu_color':
            styles['.menu_block color'][2] = color
            $('.menu_block').css('color', color);
            break;
        case 'submenu_item_color':
            styles['.vertical-menu a color'][2] = color
            $('.vertical-menu a').css('color', color);
            break;
        case 'submenu_item_background-color':
            styles['.vertical-menu a background-color'][2] = color
            $('.vertical-menu a').css('background-color', color);
            break;
        case 'submenu_item_hover_color':
            styles['.vertical-menu a:hover color'][2] = color
            break;
        case 'submenu_item_hover_background-color':
            styles['.vertical-menu a:hover background-color'][2] = color
            break;
        case 'header_color':
            styles['#top_header color'][2] = color
            $('#top_header').css('color', color);
            break;
        case 'header_background-color':
            styles['#top_header background-color'][2] = color
            $('#top_header').css('background-color', color);
            break;

        default:
            console.log('XXXXXxxXXXXX')
            console.log(scope)

            $(element).css(scope, color);

            break;

    }


    $('#save_button', window.parent.document).addClass('save button changed valid')


}


$(document).on('input propertychange,change', '.edit_margin', function (evt) {


    change_margins(this)


});

function change_margins(input) {

    if (!validate_signed_integer($(input).val(), 300)) {
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



            //delta = 60 - value;


          //  $('#header_title').css('max-width', (330 - delta) + 'px')
          //  $('.search_container').css('padding-left', (559 + delta) + 'px')


            break
        case 'navigation_bottom_border':
            $('.top_body').css('border-bottom-width', value + 'px')

            styles['.top_body border-bottom-width'][2] = value + 'px'

            break;

        default:
            element.css(scope + '-' + $(input).data('margin'), value + "px")
    }


    // element.css(scope+'-'+$(input).data('margin'), value + "px")


    $('#save_button', window.parent.document).addClass('save button changed valid')

}

function save_styles() {

    if (!$('#save_button', window.parent.document).hasClass('save')) {
        return;
    }

    $('#save_button', window.parent.document).find('i').addClass('fa-spinner fa-spin')


    var settings = {
        'content_background_type': $('.content_background .background_type').data('type'), 'background_type': $('.background .background_type').data('type')
    }

    if ($('#favicon', window.parent.document).attr('src') != '/art/favicon_empty.png') {
        settings.favicon = $('#favicon', window.parent.document).attr('src')
    }


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'update_website_styles')
    ajaxData.append("key", $('#webpage_data').data('website_key'))
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

