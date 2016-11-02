/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 June 2016 at 13:59:36 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function bridge_supplier(element) {


    if ($(element).hasClass('fa-chain-broken')) {
        var operation = 'unlink'
    } else {
        var operation = 'link'

    }


    var request = '/ar_edit.php?tipo=bridge&object=agent&key=' + $(element).attr('agent_key') + '&subject=supplier&subject_key=' + $(element).attr('supplier_key') + '&operation=' + operation


    $.getJSON(request, function (data) {

        if (data.state == 200) {

            $(element).addClass('hide')
            $(element).closest('tr').addClass('super_discreet deleted')
            for (var key in data.metadata.updated_showcase_fields) {
                $('.' + key).html(data.metadata.updated_showcase_fields[key])
            }


        } else if (data.state == 400) {


        }
    })

}
