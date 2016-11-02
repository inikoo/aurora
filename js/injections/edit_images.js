/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 March 2016 at 12:57:37 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function delete_image(image_bridge_key) {

    var tr = $('#delete_image_button_' + image_bridge_key).closest('tr')

    if (tr.hasClass('deleting_tr') || tr.hasClass('deleted_tr')) {
        return;
    }

    tr.addClass('deleting_tr')

    // tr.addClass('deleted_tr')
    //return;
    var request = '/ar_edit.php?tipo=delete_image&image_bridge_key=' + image_bridge_key


    $.getJSON(request, function (data) {


        if (data.state == 200) {

            tr.removeClass('deleting_tr').addClass('deleted_tr')
            $('#delete_image_button_' + image_bridge_key).html(data.msg).closest('td').addClass('inmmune')


            if (data.number_images == 0) {

                //$('div.main_image').addClass('hide')
                //$('form.main_image').removeClass('hide')
                //$('div.main_image img').attr('src', '/art/nopic.png')
                $('#main_image').addClass('hide')
                $('#add_image_form').removeClass('hide')

            } else {
                $('div.main_image').removeClass('hide')
                $('form.main_image').addClass('hide')
                $('div.main_image img').attr('src', '/image_root.php?id=' + data.main_image_key + '&size=small')

            }


        } else if (data.state == 400) {
            tr.removeClass('deleting_tr')

        }
    })
}


function set_as_principal(image_bridge_key) {

    var tr = $('#set_as_principal_image_button_' + image_bridge_key).closest('tr')

    if (tr.hasClass('deleting_tr') || tr.hasClass('set_as_principald_tr')) {
        return;
    }

    //  tr.addClass('deleting_tr')

    // tr.addClass('set_as_principald_tr')
    //return;
    var request = '/ar_edit.php?tipo=set_as_principal_image&image_bridge_key=' + image_bridge_key


    $.getJSON(request, function (data) {


        if (data.state == 200) {

            // tr.removeClass('deleting_tr').addClass('set_as_principald_tr')
            //  $('#set_as_principal_image_button_' + image_bridge_key).html(data.msg).closest('td').addClass('inmmune')


            rows.fetch({
                reset: true
            });


            if (data.number_images == 0) {

                //$('div.main_image').addClass('hide')
                //$('form.main_image').removeClass('hide')
                //$('div.main_image img').attr('src', '/art/nopic.png')
                $('#main_image').addClass('hide')
                $('#add_image_form').removeClass('hide')

            } else {
                $('div.main_image').removeClass('hide')
                $('form.main_image').addClass('hide')
                $('div.main_image img').attr('src', '/image_root.php?id=' + data.main_image_key + '&size=small')

            }


        } else if (data.state == 400) {
            //  tr.removeClass('deleting_tr')

        }
    })
}
