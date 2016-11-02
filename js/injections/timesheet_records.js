/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 November 2015 at 19:42:10 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function toggle_ignore_record(key) {
    var value = $('#used_' + key).attr('value')
    $('#used_' + key).removeClass('fa-check-square-o fa-square-o')
    $('#used_' + key).addClass('fa-spinner fa-spin')


    var request = '/ar_edit.php?tipo=edit_field&object=timesheet_record&key=' + key + '&field=Timesheet_Record_Ignored&value=' + fixedEncodeURIComponent(value)
    //console.log(request)
    $.getJSON(request, function (data) {

        $('#used_' + key).removeClass('fa-spinner fa-spin')

        if (data.state == 200) {
            if (data.value == 'Yes') {
                $('#used_' + key).addClass('fa-square-o')
                $('#used_' + key).attr('value', 'No')

            } else {
                $('#used_' + key).addClass('fa-check-square-o')
                $('#used_' + key).attr('value', 'Yes')

            }
            for (var record_key in data.other_fields.records_data) {
                $('#action_type_' + record_key).html(data.other_fields.records_data[record_key].action_type)
            }

            for (var field in data.other_fields.updated_data) {
                $('.' + field).html(data.other_fields.updated_data[field])
            }
            for (var field in data.other_fields.updated_titles) {
                $('.' + field).prop('title', data.other_fields.updated_titles[field])
            }

            $("#inline_new_object_msg").html('').removeClass('success error')

        } else if (data.state == 400) {
            if (value == 'Yes') $('#used_' + key).addClass('fa-check-square-o')
            else $('#used_' + key).addClass('fa-square-o')

        }
    })

}




