/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  17 December 2019  01:25::31  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/


$(function () {

    $('#tab #table').on(' paste', '.table_item_editable.editing', function (e) {

        clipboardData = event.clipboardData || window.clipboardData || event.originalEvent.clipboardData;
        e.preventDefault();


        var text = clipboardData.getData("text/plain");
        document.execCommand("insertHTML", false, text);




    });

    $('#tab #table').on('input paste', '.table_item_editable.editing', function () {

        if($(this).html()!= $(this).attr('ovalue')){

            $(this).addClass('edited');
            $('#inline_edit_table_items_save_button').addClass('changed valid')
        }else{
            $(this).removeClass('edited');
            process_table_item_editable_changes()
        }



    })

});




function process_table_item_editable_changes(){

    $('.table_item_editable.editing').each(function(i, obj) {
        if($(obj).html()!= $(obj).attr('ovalue')){
            $('#inline_edit_table_items_save_button').addClass('changed valid');
            return false;
        }
    });

}


function close_table_edit_view(element){

    if( $( element  ).hasClass('super_discreet')){
        return;
    }

    $('#table .table_item_editable').attr('contenteditable',false).removeClass('editing edited success');

    $('#inline_edit_table_items_buttons').addClass('hide');
    $('#show_edit_table_dialog_button').removeClass('hide')




}

function process_table_item_editing_changes(){

    $('.table_item_editable.editing').each(function(i, obj) {
        if($(obj).hasClass('edited')){



            return false;
        }
    });
    $('#inline_edit_table_items_save_button').removeClass('fa-spinner fa-spin').addClass('save');
    $('#inline_edit_table_items_close_button').removeClass('super_discreet').addClass('button')


}

function undo_changes_after_error(element){

    var item_editable= $(element).closest('tr').find('.table_item_editable');

    item_editable.html( item_editable.attr('ovalue')).removeClass('error edited');

    $(element).prev('.error_msg').remove();
    $(element).remove()

}


function save_table_items(){


    if(! $('#inline_edit_table_items_save_button').hasClass('valid')){
        return;
    }

    $('#inline_edit_table_items_save_button').removeClass('changed valid save').addClass('fa-spinner fa-spin');
    $('#inline_edit_table_items_close_button').addClass('super_discreet').removeClass('button');

    $('.table_item_editable.editing').each(function(i, obj) {
        if($(obj).html()!= $(obj).attr('ovalue')){





            var ajaxData = new FormData();

            ajaxData.append("tipo", 'edit_field');
            ajaxData.append("object", $('#inline_edit_table_items_buttons').data('object'));



            ajaxData.append("field",  $(obj).data('field')  );
            ajaxData.append("key",  $(obj).closest('tr').find('.item_data').data('key')  );
            ajaxData.append("value",  $(obj).html()  );



            var item_class= $(obj).data('item_class');
            var key= $(obj).closest('tr').find('.item_data').data('key');

            $.ajax({
                url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


                complete: function () { },
                success: function (data) {
                    if (data.state == '200') {



                        $('#item_data_'+key).closest('tr').find('.'+item_class).addClass('success').removeClass('edited').html(data.formatted_value).attr('ovalue',data.formatted_value);
                        process_table_item_editing_changes()


                    } else if (data.state == '400') {

                        $('#item_data_'+key).closest('tr').find('.'+item_class).addClass('error').after('<i onclick="swal(\''+data.msg+'\')" class="fa error_msg button fa-exclamation-circle error padding_left_10" aria-hidden="true"></i> <i onclick="undo_changes_after_error(this)" class="fa button fa-undo" title="Undo" aria-hidden="true"></i>')


                    }



                }, error: function () {

                }
            });




        }
    });

    process_table_item_editing_changes()

}