/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 July 2018 at 18:14:42 GMT+8 Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/

$(".new_marketing_mailshot").on( 'click',function () {
    new_mailshot(this)
})


function new_mailshot(element) {


    var object = 'EmailCampaign'
    var parent = $(element).attr('parent')
    var parent_key = $(element).attr('parent_key')
    var fields_data = {
        ' Type': $(element).attr('scope'),
        'List': $(element).attr('list'),
        'Asset': $(element).attr('asset'),
        'Email Campaign Name': $(element).attr('name'),
        ' Scope Type': $(element).attr('scope_type'),
    };


    var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + parent + '&parent_key=' + parent_key + '&fields_data=' + JSON.stringify(fields_data)


    var form_data = new FormData();
    form_data.append("tipo", 'new_object')
    form_data.append("object", object)
    form_data.append("parent", parent)
    form_data.append("parent_key", parent_key)
    form_data.append("fields_data", JSON.stringify(fields_data))

    var request = $.ajax({
        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'
    })

    request.done(function (data) {



        console.log(data)
        if (data.state == 200) {

            change_view(data.redirect,data.redirect_metadata)

          


        } else if (data.state == 400) {

            swal('Oops...', data.msg, 'error')
        }
    })

    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)
        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


    });


}