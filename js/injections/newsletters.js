/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 February 2018 at 12:19:38 GMT+8 Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$("#new_newsletter").on( 'click',function () {
    new_newsletter(this)
})


function new_newsletter(element) {


    var object = 'EmailCampaign'
    var parent = $(element).attr('parent')
    var parent_key = $(element).attr('parent_key')
    var fields_data = {
        'Email Campaign Type': 'Newsletter'
     };


    var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + parent + '&parent_key=' + parent_key + '&fields_data=' + JSON.stringify(fields_data)
    console.log(request)
    var form_data = new FormData();
    form_data.append("tipo", 'new_object')
    form_data.append("object", object)
    form_data.append("parent", parent)
    form_data.append("parent_key", parent_key)
    form_data.append("fields_data", JSON.stringify(fields_data))

    var request = $.ajax({
        url: "/ar_edit.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'
    })

    request.done(function (data) {



        //console.log(data)
        if (data.state == 200) {


            change_view('newsletters/'+parent_key+'/'+ data.new_id
                //, { tab: ''}
                )

        }
        else if (data.state == 400) {

            swal(
                'Oops...',
                data.msg,
                'error'
            )
        }
    })

    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)
        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


    });


}