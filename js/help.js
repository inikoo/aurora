/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 March 2016 at 18:20:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$(document).ready(function () {

    $('body').on('click', '.question', function () {


        show_answer($(this))
    });


})


function help() {

    var request = "/ar_help.php?tipo=help&state=" + JSON.stringify(state)

    $.getJSON(request, function (data) {

        if (typeof(data.title) != "undefined" && data.title !== null && data.title != '') {

            $('#help  .help_title').html(data.title).removeClass('hide');
        } else {
            $('#help  .help_title').html('').addClass('hide');
        }

        if (typeof(data.content) != "undefined" && data.content !== null && data.content != '') {

            $('#help  .content').html(data.content).removeClass('hide');

            if ($('#help  .content .question').length == 1) {
                show_answer($('#help  .content .question')[0])
            }

        } else {
            $('#help  .content').html('').addClass('hide');


        }


    })


}


function show_answer(element) {


    if ($(element).next().hasClass('hide')) {
        $('#help .answer').addClass('hide')

        $(element).find('.fa-caret-right.bullet').removeClass('fa-caret-right').addClass('fa-caret-down')
        $(element).next().removeClass('hide')
    } else {
        $('#help .answer').addClass('hide')

        $(element).find('.fa-caret-down.bullet').removeClass('fa-caret-down').addClass('fa-caret-right')


    }


}


function show_help() {

    if ($('#help').hasClass('hide')) {
        var value = 1
        $('#help').removeClass('hide')
        $('#help_button').addClass('selected')
        help()

    } else {
        var value = 0
        $('#help').addClass('hide')
        $('#help_button').removeClass('selected')

    }

    var request = "/ar_help.php?tipo=show_help&value=" + value

    $.getJSON(request, function (data) {
    })


}
