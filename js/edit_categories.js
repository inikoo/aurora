/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 April 2018 at 18:50:07 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/



function disassociate_category_from_table(element,parent_key,child_key){



    var request = '/ar_edit.php?tipo=disassociate_category&parent_key=' +parent_key+'&child_key='+child_key;

    console.log(request)
    var form_data = new FormData();
    form_data.append("tipo", 'disassociate_category')
    form_data.append("parent_key", parent_key)
    form_data.append("child_key", child_key)

    var request = $.ajax({

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })

    request.done(function (data) {
        // console.log(data)
        if (data.state == 200) {

            // todo , just remove the row
            rows.fetch({
                reset: true
            });

        }

    })

    request.fail(function (jqXHR, textStatus) {
    });

}







