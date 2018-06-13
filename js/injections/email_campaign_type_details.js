/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  12 June 2018 at 21:01:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/



function toggle_schedule_days(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-toggle-on')){
        var value='No'
    }else if(icon.hasClass('fa-toggle-off')){
        var value='Yes'
    }else{

        return
    }
    icon.removeClass('fa-toggle-on fa-toggle-off').addClass(' fa-spinner fa-spin')




    var ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", 'Email_Campaign_Type')

    ajaxData.append("key", $('#fields').attr('key'))
    ajaxData.append("field", $(element).attr('field'))

    ajaxData.append("value", value)


    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {


                if(value){
                    icon.addClass('fa-toggle-on').removeClass(' fa-spinner fa-spin')
                    icon.next('span').removeClass('discreet')
                    $('.'+$(element).attr('field')).removeClass('error discreet')

                }else{
                    icon.addClass('fa-toggle-off').removeClass(' fa-spinner fa-spin')
                    icon.next('span').addClass('discreet')
                    $('.'+$(element).attr('field')).addClass('error discreet')

                }


            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });


}



