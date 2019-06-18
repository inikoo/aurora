/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  16 January 2019 at 15:54:54 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/



function toggle_allow_data_entry_picking_aid(element){

    var icon=$(element).find('i')

    var field=$(element).attr('field')

    if(field=='data_entry_picking_aid') {

        if (icon.hasClass('fa-toggle-on')) {
            var value = 'No'
        } else if (icon.hasClass('fa-toggle-off')) {
            var value = 'Yes'
        } else {

            return
        }
        icon.removeClass('fa-toggle-on fa-toggle-off ').addClass(' fa-spinner fa-spin')

    }else{


        var value= $(element).data('value')
        $('.data_entry_picking_aid_state_after_save i').addClass('fa-spinner fa-spin').addClass('fa-check-circle fa-circle')

    }





    var ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", 'Store')

    ajaxData.append("key", $('#fields').attr('key'))
    ajaxData.append("field", field)

    ajaxData.append("value", value)


    console.log(value)

    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {


                if(field=='data_entry_picking_aid'){


                    if(value=='Yes'){
                        icon.addClass('fa-toggle-on').removeClass(' fa-spinner fa-spin')
                        icon.next('span').removeClass('discreet')
                        $('.'+$(element).attr('field')).removeClass('error discreet')

                        $('.data_entry_picking_aid_defaults').removeClass('hide')

                    }else{
                        icon.addClass('fa-toggle-off').removeClass(' fa-spinner fa-spin')
                        icon.next('span').addClass('discreet')
                        $('.'+$(element).attr('field')).addClass('error discreet')
                        $('.data_entry_picking_aid_defaults').addClass('hide')

                    }
                }else{

                    $('.data_entry_picking_aid_state_after_save').addClass('very_discreet')
                    $('.data_entry_picking_aid_state_after_save i').removeClass('fa-spinner fa-spin').addClass('fa-circle')


                    if(value>=10){
                        $('.data_entry_picking_aid_state_after_save.level_10').removeClass('very_discreet')
                        $('.data_entry_picking_aid_state_after_save.level_10 i').removeClass('fa-circle').addClass('fa-check-circle')

                    }
                    if(value>=20){
                        $('.data_entry_picking_aid_state_after_save.level_20').removeClass('very_discreet')
                        $('.data_entry_picking_aid_state_after_save.level_20 i').removeClass('fa-circle').addClass('fa-check-circle')

                    }
                    if(value>=30){
                        $('.data_entry_picking_aid_state_after_save.level_30').removeClass('very_discreet')
                        $('.data_entry_picking_aid_state_after_save.level_30 i').removeClass('fa-circle').addClass('fa-check-circle')

                    }



                }



                if (data.other_fields) {
                    for (var key in data.other_fields) {

                        //   console.log(data.other_fields[key])

                        update_field(data.other_fields[key])
                    }
                }

                if (data.deleted_fields) {
                    for (var key in data.deleted_fields) {
                        delete_field(data.deleted_fields[key])
                    }
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

              //  $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });


}






function toggle_invoice_show(element){

    var icon=$(element).find('i')

    var field=$(element).data('field')


        if (icon.hasClass('fa-check-square')) {
            var value = 'No'
        }else{

            var value = 'Yes'
        }
        icon.removeClass('fa-check-square fa-square ').addClass(' fa-spinner fa-spin')






    var ajaxData = new FormData();

    ajaxData.append("tipo", 'edit_field')
    ajaxData.append("object", 'Store')

    ajaxData.append("key", $('#fields').attr('key'))
    ajaxData.append("field", field)

    ajaxData.append("value", value)


   // console.log(value)

    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {

            if (data.state == '200') {


                if (value == 'Yes') {
                    icon.addClass('fa-check-square').removeClass(' fa-spinner fa-spin')

                } else {
                    icon.addClass('fa-square').removeClass(' fa-spinner fa-spin')


                }


                if (data.other_fields) {
                    for (var key in data.other_fields) {

                        //   console.log(data.other_fields[key])

                        update_field(data.other_fields[key])
                    }
                }

                if (data.deleted_fields) {
                    for (var key in data.deleted_fields) {
                        delete_field(data.deleted_fields[key])
                    }
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

                //  $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }


        }, error: function () {

        }

    }
    )


}