/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2016 at 23:15:29 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo
 Version 3.0*/

function change_view(_request, metadata) {

    //console.log(metadata)
    if (metadata == undefined) {
        metadata = {};
    }

    var request = "/ar_setup.php?tipo=views&request=" + _request + '&metadata=' + JSON.stringify(metadata) + "&old_state=" + JSON.stringify(state)
    $.getJSON(request, function(data) {



        state = data.state;

        //console.log(data.state)
        if (typeof(data.navigation) != "undefined" && data.navigation !== null && data.navigation != '') {
            // $('#navigation').removeClass('hide')
            $('#navigation').html(data.navigation);
        } else {
            // $('#navigation').addClass('hide')
        }

        if (typeof(data.tabs) != "undefined" && data.tabs !== null) {
            $('#tabs').html(data.tabs);
        }
        if (typeof(data.menu) != "undefined" && data.menu !== null) {
            $('#menu').html(data.menu);


        }

        if (typeof(data.logout_label) != "undefined" && data.logout_label !== null) {
            $('#logout_label').html(data.logout_label);


        }



        if (typeof(data.view_position) != "undefined" && data.view_position !== null) {

            $('#view_position').html(data.view_position);
        }

        if (typeof(data.object_showcase) != "undefined" && data.object_showcase !== null && data.object_showcase != '') {
            $('#object_showcase').removeClass('hide')
            $('#object_showcase').html(data.object_showcase);
        } else {
            $('#object_showcase').addClass('hide')

        }
        if (typeof(data.tab) != "undefined" && data.tab !== null) {

            $('#tab').html(data.tab);
        }




        if (typeof(data.structure) != "undefined" && data.structure !== null) {
            structure = data.structure
        }



        change_browser_history_state(data.state.request)
        help()

    });

}

function logout() {
    window.location.href = "/logout.php";
}

function save_setup(object, fields_data) {


    // used only for debug
    var request = '/ar_setup.php?tipo=setup&step=' + $('#fields').attr('step') + '&parent=' + $('#fields').attr('parent') + '&parent_key=' + $('#fields').attr('parent_key') + '&object=' + $('#fields').attr('object') + '&key=' + $('#fields').attr('key') + '&fields_data=' + JSON.stringify(fields_data)
    console.log(request)
    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'setup')
    form_data.append("step", $('#fields').attr('step'))

    form_data.append("parent", $('#fields').attr('parent'))
    form_data.append("parent_key", $('#fields').attr('parent_key'))
    form_data.append("object", $('#fields').attr('object'))
    form_data.append("key", $('#fields').attr('key'))
    form_data.append("fields_data", JSON.stringify(fields_data))

    var request = $.ajax({

        url: "/ar_setup.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })

    request.done(function(data) {


        console.log(data)
        $('#' + object + '_save_icon').addClass('fa-cloud');
        $('#' + object + '_save_icon').removeClass('fa-spinner fa-spin');
        $('#save_label').removeClass('hide')
        $('#saving_label').addClass('hide')
        $('#fields').removeClass('waiting');
        if (data.state == 200) {

            if (data.redirect) {
                change_view(data.redirect)
            } else {

                $('#result').html(data.pcard).removeClass('hide')

                $('#fields').addClass('hide')




                for (var field in data.updated_data) {
                    $('.' + field).html(data.updated_data[field])
                }

                post_new_actions(object, data)
            }

        } else if (data.state == 400) {
            $('#fields').addClass('error');
            $('#' + object + '_msg').html(data.msg).removeClass('hide')

        }

    })

    request.fail(function(jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)
        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        $('#fields').removeClass('waiting').addClass('valid')
        $('#save_label').removeClass('hide')
        $('#saving_label').addClass('hide')

        $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


    });


}

function skip() {




    // used only for debug
    var request = '/ar_setup.php?tipo=skip&step=' + state.section
    console.log(request)
    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'skip')
    form_data.append("step", state.section)


    var request = $.ajax({

        url: "/ar_setup.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })

    request.done(function(data) {


        console.log(data)

        if (data.state == 200) {

            change_view(data.redirect)

        } else if (data.state == 400) {
            $('#msg').addClass('error');
            $('#msg').html(data.msg).removeClass('hide')

        }

    })

    request.fail(function(jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


        $('#msg').html('Server error please contact Aurora support').addClass('error')


    });




}


function help() {

    var request = "/ar_setup.php?tipo=help&state=" + JSON.stringify(state)

    $.getJSON(request, function(data) {

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

$(document).ready(function() {



    state = {
        module: '',
        section: '',
        parent: '',
        parent_key: '',
        object: '',
        key: ''
    }
    structure = {}

    change_view($('#_request').val())



    $(document).keydown(function(e) {
        key_press(e)
    });




})


function change_browser_history_state(request) {


    if (request == undefined) {
        return;
    }

    if (request.charAt(0) !== '/') {
        request = '/' + request
    }

    window.top.history.pushState({
        request: request
    }, '', request)

}

window.addEventListener('popstate', function(event) {
    change_view(event.state.request)

});

