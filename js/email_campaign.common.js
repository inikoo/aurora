/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 February 2018 at 15:28:49 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2010, Inikoo
 Version 3.0*/





function send_email(recipient,recipient_key) {

    $('#save_email_template_dialog').addClass('hide')


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'send_email')
    ajaxData.append("recipient", recipient)
    ajaxData.append("recipient_key", recipient_key)

    ajaxData.append("html", $('#template_name2').data('htmlFile'))
    ajaxData.append("json", $('#template_name2').data('jsonFile'))
    ajaxData.append("text", $('#email_template_text').val())
    ajaxData.append("subject", $('#email_template_subject').val())




    //$('#save_email_template_dialog').closest('div').addClass('hide')



    $.ajax({
        url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {



                change_view(data.redirect)





            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });

}



function send_mailshot_now(element){
    $(element).find('i').removeClass('fa-paper-plane').addClass('fa-spinner fa-spin')


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'send_mailshot')
    ajaxData.append("key", $('#email_campaign').data('email_campaign_key'))







    $.ajax({
        url: "/ar_mailshot.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                $('#email_campaign\\.published_email').removeClass('hide')
                change_tab('email_campaign.published_email')


                for (var key in data.update_metadata.class_html) {
                    $('.' + key).html(data.update_metadata.class_html[key])
                }


                $('.email_campaign_operation').addClass('hide')

                console.log(data.update_metadata.operations)

                for (var key in data.update_metadata.operations) {
                    $('#' + data.update_metadata.operations[key]).removeClass('hide')
                }




                $('.timeline .li').removeClass('complete')

                $('#setup_mail_list_node').addClass('complete')
                $('#composed_email_node').addClass('complete')
                $('#scheduled_node').addClass('complete')
                $('#sending_node').addClass('complete')




            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }


        }, error: function () {

        }
    });


}




function resume_mailshot(element){

    $(element).find('i').removeClass('fa-play').addClass('fa-spinner fa-spin')


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'resume_mailshot')
    ajaxData.append("key", $('#email_campaign').data('email_campaign_key'))







    $.ajax({
        url: "/ar_mailshot.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                $('#email_campaign\\.published_email').removeClass('hide')
                change_tab('email_campaign.published_email')


                for (var key in data.update_metadata.class_html) {
                    $('.' + key).html(data.update_metadata.class_html[key])
                }


                $('.email_campaign_operation').addClass('hide')

                console.log(data.update_metadata.operations)

                for (var key in data.update_metadata.operations) {
                    $('#' + data.update_metadata.operations[key]).removeClass('hide')
                }

                $('#stopped_node').addClass('hide')



                $('.timeline .li').removeClass('complete')

                $('#setup_mail_list_node').addClass('complete')
                $('#composed_email_node').addClass('complete')
                $('#scheduled_node').addClass('complete')
                $('#sending_node').addClass('complete')




            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }


        }, error: function () {

        }
    });


}

function save_email_campaign_operation(element) {

    var data = $(element).data("data")



    var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))

    var dialog_name = data.dialog_name
    var field = data.field
    var value = data.value
    var object = object_data.object
    var key = object_data.key


    if (!$('#' + dialog_name + '_save_buttons').hasClass('button')) {
        console.log('#' + dialog_name + '_save_buttons')
        return;
    }

    $('#' + dialog_name + '_save_buttons').removeClass('button');
    $('#' + dialog_name + '_save_buttons i').addClass('fa-spinner fa-spin')
    $('#' + dialog_name + '_save_buttons .label').addClass('hide')


    var metadata = {}

    //console.log('#' + dialog_name + '_dialog')

    $('#' + dialog_name + '_dialog  .option_input_field').each(function () {
        var settings = $(this).data("settings")



        if (settings.type == 'datetime') {
            metadata[settings.field] = $('#' + settings.id).val() + ' ' + $('#' + settings.id + '_time').val()

        }


    });



    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value + '&metadata=' + JSON.stringify(metadata)



    console.log(request)
     // return;
    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_field')
    form_data.append("object", object)
    form_data.append("key", key)
    form_data.append("field", field)
    form_data.append("value", value)
    form_data.append("metadata", JSON.stringify(metadata))

    var request = $.ajax({

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {

        $('#' + dialog_name + '_save_buttons').addClass('button');
        $('#' + dialog_name + '_save_buttons i').removeClass('fa-spinner fa-spin')
        $('#' + dialog_name + '_save_buttons .label').removeClass('hide')


        if (data.state == 200) {

            close_dialog(dialog_name)






            if (data.value == 'Cancelled') {
                change_view(state.request, {
                    reload_showcase: true
                })
            }



            switch (data.update_metadata.state){
                case 'ComposingEmail':
                    $('#email_campaign\\.workshop').removeClass('hide')
                    change_tab('email_campaign.workshop')
                    break;


            }




            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }


            $('.email_campaign_operation').addClass('hide')
           // $('.items_operation').addClass('hide')




            for (var key in data.update_metadata.operations) {

                console.log('#' + data.update_metadata.operations[key])

                $('#' + data.update_metadata.operations[key]).removeClass('hide')
            }




            $('.timeline .li').removeClass('complete')


                if (data.update_metadata.state_index >= 20) {
                    $('#setup_mail_list_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 30) {
                    $('#composed_email_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 80) {
                    $('#packed_done_node').addClass('complete')
                }
                if (data.update_metadata.state_index >=90) {
                    $('#approved_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 100) {
                    $('#dispatched_node').addClass('complete')
                }




        } else if (data.state == 400) {


            swal($('#_labels').data('labels').error, data.msg, "error")
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}



function open_send_test_email_dialog(htmlFile){


    if($('#email_template_text_controls').hasClass('hide')){
        $('#send_email_dialog').removeClass('hide').css({ top:'170px',left:'210px' })
    }else{
        $('#send_email_dialog').removeClass('hide').css({ top:'64px',left:'160px' })
    }



    $('#send_email_to').data('html',htmlFile)
    $('#send_email_to').val($('#email_template_data').data('send_email_to'))


    $('#send_email_ok').addClass('hide')


}

function open_save_as_blueprint_dialog(jsonFile){



    $('#save_as_blueprint_dialog').removeClass('hide')
    $('#save_as_blueprint_dialog').find('input').val('').focus().data('jsonFile',jsonFile).data('htmlFile','')

}




function send_test_email(){

    $('#send_email').addClass('fa-spinner fa-spin').removeClass('valid changed fa-paper-plane')


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'send_test_email')
    ajaxData.append("email_template_key", $('#email_template_data').data('email_template_key'))
    ajaxData.append("html", $('#send_email_to').data('html'))
    ajaxData.append("email",$('#send_email_to').val())



    $.ajax({
        url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                $('#send_email').removeClass('fa-spinner fa-spin').addClass('valid changed fa-paper-plane')
                $('#send_email_dialog').addClass('hide')



                if($('#email_template_text_controls').hasClass('hide')){
                    $('#send_email_ok').removeClass('hide').css({ top:'170px',left:'210px' })
                }else{
                    $('#send_email_ok').removeClass('hide').css({ top:'64px',left:'160px' })
                }


                for (var key in data.update_metadata.class_html) {
                    $('.' + key).html(data.update_metadata.class_html[key])
                }



                for (var key in data.update_metadata.hide) {
                    $('.' + data.update_metadata.hide[key]).addClass('hide')
                }

                for (var key in data.update_metadata.show) {

                    $('.' + data.update_metadata.show[key]).removeClass('hide')
                }





            } else if (data.state == '400') {
                swal("{t}Error{/t}", data.msg, "error");

            }



        }, error: function () {

        }
    });

}


function save_email_template(jsonFile,htmlFile) {


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'publish_email_template')
    ajaxData.append("email_template_key", $('#email_template_data').data('email_template_key'))
    ajaxData.append("json",jsonFile)
    ajaxData.append("html",htmlFile)
    ajaxData.append("subject",$("#email_template_subject").val())
    ajaxData.append("text",$("#email_template_text").val())



    $.ajax({
        url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                console.log(data)

                $('#email_template_info').html(data.email_template_info)
                if(data.published){
                    $('#publish_email_template_from_text_controls').addClass('super_discreet').removeClass('button')
                }else{
                    $('#publish_email_template_from_text_controls').removeClass('super_discreet').addClass('button')

                }

                for (var key in data.update_metadata.class_html) {
                    $('.' + key).html(data.update_metadata.class_html[key])
                }



                for (var key in data.update_metadata.hide) {
                    $('.' + data.update_metadata.hide[key]).addClass('hide')
                }

                for (var key in data.update_metadata.show) {

                    $('.' + data.update_metadata.show[key]).removeClass('hide')
                }

                $('.email_campaign_operation').addClass('hide')

                for (var key in data.update_metadata.operations) {
                    $('#' + data.update_metadata.operations[key]).removeClass('hide')
                }

                // hack for email campaigns showcase
                if(data.update_metadata.state_index==30){

                    $('#email_campaign\\.workshop').addClass('hide')
                    $('#email_campaign\\.published_email').removeClass('hide')

                    change_tab('email_campaign.published_email')

                    $('#composed_email_node').addClass('complete')



                }





            } else if (data.state == '400') {

            }



        }, error: function () {

        }
    });

}


function save_as_blueprint(icon) {

    var input=$(icon).closest('div').find('input')

    var ajaxData = new FormData();




    ajaxData.append("tipo", 'save_blueprint')
    ajaxData.append("email_template_key", $('#email_template_data').data('email_template_key'))

    ajaxData.append("json", input.data('jsonFile'))
    ajaxData.append("html", input.data('htmlFile'))

    ajaxData.append("name", input.val())

    // element.closest('div').addClass('hide')

    $.ajax({
        url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                $('.save_as_blueprint_dialog').addClass('hide')



            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });

}


$(document).on('click', "#email_template_text_button,#email_template_html_button", function() {
    if($('#email_template_html_container').hasClass('hide')){
        $('#email_template_html_container').removeClass('hide')
        $('#email_template_text_container').addClass('hide')
        $("#email_template_text_button").removeClass('hide')
        $("#email_template_html_button").addClass('hide')

    }else{
        $('#email_template_html_container').addClass('hide')
        $('#email_template_text_container').removeClass('hide')
        $("#email_template_text_button").addClass('hide')
        $("#email_template_html_button").removeClass('hide')

    }
});


$(document).on('input propertychange', ".template_name", function() {

    if($(this).val()!=''){

        $(this).next('i').addClass('changed valid')

    }else{
        $(this).next('i').removeClass('changed valid')
    }
})


$(document).on('input propertychange', ".save_template", function() {
    if($(this).hasClass('valid')){
        save_as_another_template($(this).prev('input'))
    }
})







$(document).on('input propertychange', "#email_template_text", function() {


    if($("#email_template_text").val()==''){
        $('#email_template_text_button').addClass('error very_discreet')
    }else{
        $('#email_template_text_button').removeClass('error very_discreet')
    }


});

$(document).on('input propertychange', "#email_template_subject", function() {
    if($("#email_template_subject").val()==''){
        $('#email_template_subject').addClass('error ')
    }else{
        $('#email_template_subject').removeClass('error ')
    }

});

/*





function update_email_template_type(value){

    var ajaxData = new FormData();

    ajaxData.append("tipo", 'set_email_template_type')
    ajaxData.append("email_template_key", $('#email_template_data').data('email_template_key'))
    ajaxData.append("value",value)





    $.ajax({
        url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {


                $('.popup_dialog').addClass('hide');

                if(value=='HTML'){

                    if(data.has_html_json){
                        $('#email_template_add_html_section').addClass('hide')
                        $('#email_template_text_container').addClass('hide')
                        $('#email_template_text_controls').addClass('hide')

                        $('#email_template_set_as_text').removeClass('hide')
                        $('#email_template_html_container').removeClass('hide')
                        $('#change_template').removeClass('hide')



                        $('#email_template_text_button').removeClass('hide')
                        $('#email_template_html_button').addClass('hide')

                    }else{
                        change_view(state.request + '{$email_template_redirect}')
                    }







                }else{
                    $('#email_template_add_html_section').removeClass('hide')
                    $('#email_template_text_container').removeClass('hide')
                    $('#email_template_text_controls').removeClass('hide')

                    $('#email_template_set_as_text').addClass('hide')
                    $('#email_template_html_container').addClass('hide')
                    $('#change_template').addClass('hide')

                    $('#email_template_text_button').addClass('hide')
                    $('#email_template_html_button').addClass('hide')


                }


            } else if (data.state == '400') {

            }



        }, error: function () {

        }
    });




}

*/