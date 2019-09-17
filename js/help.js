/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 March 2016 at 18:20:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$(document).ready(function () {

    $('body').on('click', '.question', function () {


        show_answer($(this))
    });


    $( "#whiteboard .content" ).on( "focusin", function() {

        if($(this).data('empty')){
            $(this).html('');
        }

    } )



    $('#whiteboard').on('input paste','.content', function (e) {

        delayed_save_whiteboard_content($(this), 400)





    });


})


function delayed_save_whiteboard_content(object, timeout) {


    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {
        save_whiteboard_content(object)
    }, timeout));


}

function save_whiteboard_content(object){
  //  console.log($('#whiteboard_content').html())


  //  console.log($(object).html())


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'save_whiteboard')
    ajaxData.append("state", JSON.stringify(state))
    ajaxData.append("content",$(object).html() )
    ajaxData.append("block",$(object).data('block') )


    $.ajax({
        url: "/ar_edit_help.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {


            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });


}


function get_real_time_users() {

    var request = "/ar_real_time.php?tipo=users";

    $.getJSON(request, function (data) {
        render_real_time_users_table(data.users_data)

    })


}

function help() {

    var request = "/ar_help.php?state=" +JSON.stringify(state)

    $.getJSON(request, function (data) {

        if (typeof(data.help.title) != "undefined" && data.help.title !== null && data.help.title != '') {

            $('#help  .help_title').html(data.help.title).removeClass('hide');
        } else {
            $('#help  .help_title').html('').addClass('hide');
        }

        if (typeof(data.help.content) != "undefined" && data.help.content !== null && data.help.content != '') {

            $('#help  .content').html(data.help.content).removeClass('hide');

            if ($('#help  .content .question').length == 1) {
                show_answer($('#help  .content .question')[0])
            }

        } else {
            $('#help  .content').html('').addClass('hide');


        }



        $('#whiteboard_content').html(data.whiteboard.content).removeClass('hide').data('empty',data.whiteboard.empty);
        $('#whiteboard_content_tab').html(data.whiteboard.content_tab).removeClass('hide').data('empty',data.whiteboard.empty_tab);





        $('#whiteboard_content_title').html(data.whiteboard.page_title);
        $('#whiteboard_content_tab_title').html(data.whiteboard.tab_title);

        if(data.whiteboard.has_tab){
            $('#whiteboard_content_tab').removeClass('hide')
        }else{
            $('#whiteboard_content_tab').addClass('hide')
        }




    })


}

/*
function whiteboard() {

    var request = "/ar_help.php?tipo=whiteboard&state=" + JSON.stringify(state)

   // console.log(request)

    $.getJSON(request, function (data) {


        if (data.status==200) {




            $('#whiteboard_content').html(data.content).removeClass('hide').data('empty',data.empty);
            $('#whiteboard_content_tab').html(data.content_tab).removeClass('hide').data('empty',data.empty_tab);





            $('#whiteboard_content_title').html(data.page_title);
            $('#whiteboard_content_tab_title').html(data.tab_title);

            if(data.has_tab){
                $('#whiteboard_content_tab').removeClass('hide')
            }else{
                $('#whiteboard_content_tab').addClass('hide')
            }



        } else {



        }


    })


}
*/

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


function show_side_content(type) {


    $('.side_content').addClass('hide')
    $('.side_content_icon').removeClass('selected')


    $('#notifications .'+type+'_button').addClass('selected')

    $('.side_content.' + type).removeClass('hide')
    switch (type) {
        case 'help':
            help()
            break;
        case 'real_time_users':
            get_real_time_users()

            break;
    }


    var request = "/ar_edit_help.php?tipo=side_block&value=" + type

    $.getJSON(request, function (data) {
    })


}
