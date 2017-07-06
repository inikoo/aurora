/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 July 2017 at 13:17:07 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function delete_blueprint(element,blueprint_key) {


    if($(element).hasClass('super_discreet')){
        return;
    }

    var tr = $('#delete_blueprint_button_' + blueprint_key).closest('tr')

    if (tr.hasClass('deleting_tr') || tr.hasClass('deleted_tr')) {
        return;
    }

    tr.addClass('deleting_tr')

    // tr.addClass('deleted_tr')
    //return;


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'object_operation')
    ajaxData.append("operation", 'delete')
    ajaxData.append("object", 'Email_Blueprint')
    ajaxData.append("key", blueprint_key)



    $.ajax({
        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                tr.removeClass('deleting_tr').addClass('deleted_tr')
                $('#delete_blueprint_button_' + blueprint_key).html(data.msg).closest('td').addClass('hide').html('')

            } else if (data.state == '400') {
                tr.removeClass('deleting_tr')
                swal(data.msg);
            }



        }, error: function () {

        }
    });



}


function select_blueprint(element,blueprint_key) {




    icon=$(element).find('i')


    if(icon.hasClass('fa-spin')){
        return
    }

    icon.addClass('fa-spinner fa-spin')


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'select_blueprint')
    ajaxData.append("role", '{$role}')
    ajaxData.append("scope", '{$scope}')
    ajaxData.append("scope_key", '{$scope_key}')

    ajaxData.append("blueprint", blueprint_key)




    $.ajax({
        url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

               change_view(state.request + '&tab=email_template')
            } else if (data.state == '400') {
                icon.removeClass('fa-spinner fa-spin')

                swal(data.msg);
            }



        }, error: function () {

        }
    });
}

function unlock_delete_blueprint(element){

   if( $(element).hasClass('fa-lock')){
       $(element).removeClass('fa-lock').addClass('fa-unlock').next('span').removeClass('super_discreet').addClass('button')
   }else{
       $(element).addClass('fa-lock').removeClass('fa-unlock').next('span').addClass('super_discreet').removeClass('button')

   }


}