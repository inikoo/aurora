/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 June 2016 at 11:03:00 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function open_new_product_family(element, store_key, label) {


    $('#edit_table_dialog').removeClass('hide')

    $('.spreadsheet_edit_label').html(label)


    $("#edit_table_dialog").offset({
        top: $(element).closest('tr').offset().top, left: $(element).offset().left + 30
    }).data('metadata',{ store_key:store_key})


   var upload_data= $("#table_edit_items_file_upload").data('data')



    upload_data.parent='store'
    upload_data.parent_key=store_key
    $("#table_edit_items_file_upload").data('data',upload_data)


}