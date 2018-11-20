/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  19 November 2018 at 17:19:15 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/



function toggle_email_campaign_abandoned_cart_type(element){

    $('.Email_Campaign_Abandoned_Cart_Type').addClass('very_discreet_on_hover').removeClass('valid').find('i.radio').removeClass('fa-dot-circle').addClass('fa-circle')

    $(element).find('i.radio').addClass('fa-dot-circle').removeClass('fa-circle').addClass('fa-spin fa-spinner')

    var form_data = new FormData();


    //return;
    //=====
    form_data.append("tipo",'edit_field')
    form_data.append("object", 'Email Campaign')
    form_data.append("key", $('#email_campaign').data('object').key)
    form_data.append("field", 'Email_Campaign_Abandoned_Cart_Type')
    form_data.append("value", $(element).attr('value'))

    var request = $.ajax({

        url: "/ar_edit.php" , data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {

        $(element).find('i.radio').removeClass('fa-spin fa-spinner')
console.log(data)
//$('.Number_Estimated_Emails').


        for (var key in data.update_metadata.class_html) {
            $('.' + key).html(data.update_metadata.class_html[key])
        }


        switch (data.value) {
            case 'Inactive':

                $('#Email_Campaign_Abandoned_Cart_Days_Inactive_in_Basket_field').removeClass('hide')
                $('#Email_Campaign_Abandoned_Cart_Days_Last_Updated_field').addClass('hide')

                break;
            case 'Last_Updated':
                $('#Email_Campaign_Abandoned_Cart_Days_Inactive_in_Basket_field').addClass('hide')
                $('#Email_Campaign_Abandoned_Cart_Days_Last_Updated_field').removeClass('hide')


                break;
        }


    })

    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });




}


