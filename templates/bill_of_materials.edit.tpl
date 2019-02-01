{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 January 2019 at 17:25:06 GMT+8, Kuala Lumpir Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<script>



    $(document).on('input propertychange', '.bill_of_materials_item', function (evt) {

        if ($(this).val() == $(this).attr('ovalue')) {
            $(this).closest('span').find('i.save').removeClass('fa-exclamation-circle error changed  valid').addClass('fa-cloud')

        } else {




            if (!validate_signed_integer($(this).val(), 4294967295) || $(this).val() == '') {
                $(this).closest('span').find('i.save').removeClass(' fa-exclamation-circle error').addClass('fa-cloud changed valid')

                $(this).addClass('discreet')
            } else {
                $(this).closest('span').find('i.save').removeClass('fa-cloud valid').addClass('fa-exclamation-circle error changed')


            }
        }
    });

    function save_bill_of_materials_item_change(element){




        $(element).addClass('fa-spinner fa-spin');


        var item_data=$(element).closest('span').data('settings')

        var table_metadata = $('#table').data("metadata")



        var form_data = new FormData();

        form_data.append("tipo", 'edit_item_in_order')
        form_data.append("field", item_data.field)
        form_data.append("parent", table_metadata.parent)
        form_data.append("parent_key", table_metadata.parent_key)
        form_data.append("item_key", item_data.item_key)

        form_data.append("qty", $(element).closest('span').find('input').val())

        var request = $.ajax({

            url: "/ar_edit_orders.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'

        })


        request.done(function (data) {

            $(element).removeClass('fa-spinner fa-spin');

            if (data.state == 200) {




                for (var key in data.metadata.class_html) {
                    $('.' + key).html(data.metadata.class_html[key])
                }

                for (var key in data.metadata.hide) {
                    $('#' + data.metadata.hide[key]).addClass('hide')
                }
                for (var key in data.metadata.show) {
                    $('#' + data.metadata.show[key]).removeClass('hide')
                }






            } else if (data.state == 400) {
                alert('error')

            }

        })


        request.fail(function (jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)


        });

    }


</script>

