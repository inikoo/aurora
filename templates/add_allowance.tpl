{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 December 2017 at 12:08:22 GMT Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
<div id="add_allowance_form" style="float:right;" class="hide" data-metadata="{$data.metadata}">
    <span id="add_allowance_invalid_msg" class="hide">{t}Invalid value{/t}</span>

    <span>{$data.field_label}</span>
    <input style="margin-right:2px" id="add_allowance" class="item " value=""
           placeholder="{if isset($data.placeholder)}{$data.placeholder}{/if}">
    <input style="margin-right:2px" id="add_allowance_qty" class="qty width_50 " value="" placeholder="%">
    <div id="add_allowance_results_container" class="search_results_container hide" style="width:400px;">

        <table id="add_allowance_results" border="0" style="background:white;font-size:90%">
            <tr class="hide" style="" id="add_allowance_search_result_template" field="" item_key="" item_historic_key=""
                formatted_value="" onClick="select_add_allowance_option(this)">
                <td class="code" style="padding-left:5px;"></td>
                <td class="label" style="padding-left:5px;"></td>

            </tr>
        </table>

    </div>


    <i id="add_allowance_save" item_key="" item_historic_key="" class="save fa fa-cloud super_discreet"
       onClick="save_add_allowance()"></i>
    <i class="fa fa-times padding_left_10 padding_right_10 button" onClick="close_add_allowance()"></i>


</div>


<script>
    $("#add_allowance_form").on("input propertychange", function (evt) {


        if ($(evt.target).attr('id') == 'add_allowance') {
            var delay = 100;
            if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
            delayed_on_change_add_allowance_field($(this), delay)
        } else {
            validate_add_allowance()
        }
    });


    function delayed_on_change_add_allowance_field(object, timeout) {

        window.clearTimeout(object.data("timeout"));

        object.data("timeout", setTimeout(function () {

            get_items_select()
        }, timeout));
    }

    function get_items_select() {

        $('#add_allowance_form').removeClass('invalid')


        var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent($('#add_allowance').val()) + '&scope=allowance_target' + '&metadata=' + atob($('#add_allowance_form').data("metadata")) + '&state=' + JSON.stringify(state)

        console.log(request)

        $.getJSON(request, function (data) {


            console.log(data)

            if (data.number_results > 0) {
                $('#add_allowance_results_container').removeClass('hide').addClass('show')
                $('#add_allowance').removeClass('invalid')

            } else {


                $('#add_allowance_results_container').addClass('hide').removeClass('show')

                //console.log(data)
                if ($('#add_allowance').val() != '') {
                    $('#add_allowance').addClass('invalid')
                } else {
                    $('#add_allowance').removeClass('invalid')
                }

                $('#save_add_allowance').attr('item_key', '')
                $('#save_add_allowance').attr('item_historic_key', '')

                validate_add_allowance()

            }


            $("#add_allowance_results .result").remove();

            var first = true;

            for (var result_key in data.results) {



                var clone = $("#add_allowance_search_result_template").clone()
                clone.prop('id', 'add_allowance_result_' + result_key);
                clone.addClass('result').removeClass('hide')


                clone.attr('item_key', data.results[result_key].value)
                clone.attr('item_historic_key', data.results[result_key].item_historic_key)

                clone.attr('formatted_value', data.results[result_key].formatted_value)
                // clone.attr('field', field)
                if (first) {
                    clone.addClass('selected')
                    first = false
                }

                clone.children(".label").html(data.results[result_key].description)
                clone.children(".code").html(data.results[result_key].code)

                $("#add_allowance_results").append(clone)


            }

        })


    }


    function select_add_allowance_option(element) {


        $('#add_allowance').val($(element).attr('formatted_value'))
        $('#add_allowance_save').attr('item_key', $(element).attr('item_key'))
        $('#add_allowance_save').attr('item_historic_key', $(element).attr('item_historic_key'))



        $('#add_allowance_results_container').addClass('hide').removeClass('show')

        $('#add_allowance_qty').focus()


        validate_add_allowance()

        console.log($('#add_allowance_save').attr('item_key'))
        console.log($('#add_allowance_save').attr('item_historic_key'))


    }

    function validate_add_allowance() {


        var invalid = false;

        if ($('#add_allowance_qty').val() == '') {
            $('#add_allowance_qty').removeClass('invalid')

        } else {

            var qty_val = validate_signed_integer($('#add_allowance_qty').val(), 4294967295);
            if (!qty_val) {
                $('#add_allowance_qty').removeClass('invalid')
            } else {
                $('#add_allowance_qty').addClass('invalid')
                invalid = true
            }
        }

        if ($('#add_allowance').hasClass('invalid')) {
            invalid = true;
            console.log($('#add_allowance').hasClass('invalid'))

        }

      //  console.log(invalid)



        if (invalid) {
            $('#add_allowance_save').addClass('invalid').removeClass('super_discreet valid button changed')
        } else {
            $('#add_allowance_save').removeClass('invalid')

            if ($('#save_add_allowance').attr('item_key') != '' && $('#add_allowance_qty').val() != '') {
                $('#add_allowance_save').addClass('valid button changed').removeClass('super_discreet')
            } else {
                $('#add_allowance_save').removeClass('valid button changed').addClass('super_discreet')
            }

        }


    }

    $('#{$trigger}').on("click", function () {

        show_add_allowance_form()

    });


    function show_add_allowance_form() {

        $('#add_allowance_msg').html('').removeClass('error success')
        $('#add_allowance_form').removeClass('hide')
        $('.table_button').addClass('hide')

        $('#save_add_allowance').attr('item_key', '')
        $('#save_add_allowance').attr('item_historic_key', '')
        $('#add_allowance').val('').focus().removeClass('invalid')
        $('#add_allowance_qty').val('').removeClass('invalid')
        $('#add_allowance_save').addClass('super_discreet').removeClass('invalid valid button')

    }


    function close_add_allowance() {
        $('#add_allowance_form').addClass('hide')
        $('.table_button').removeClass('hide')
    }

    function save_add_allowance() {


        console.log($('#table').data("metadata"))


        $('#add_allowance_save').addClass('fa-spinner fa-spin');


        var table_metadata = JSON.parse(atob($('#table').data("metadata")))



        var request = '/ar_edit_marketing.php?tipo=add_target_to_campaign&field=' + table_metadata.field + '&parent=' + table_metadata.parent + '&parent_key=' + table_metadata.parent_key + '&target_key=' + $('#add_allowance_save').attr('item_key')  + '&allowance=' + $('#add_allowance_qty').val()
        console.log(request)
         return;
        //=====
        var form_data = new FormData();

        form_data.append("tipo", 'add_target_to_campaign')
        form_data.append("field", table_metadata.field)
        form_data.append("parent", table_metadata.parent)
        form_data.append("parent_key", table_metadata.parent_key)
        form_data.append("target_key", $('#add_allowance_save').attr('item_key'))
        form_data.append("allowance", $('#add_allowance_qty').val())

        var request = $.ajax({

            url: "/ar_edit_marketing.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'

        })


        request.done(function (data) {

            $('#add_allowance_save').removeClass('fa-spinner fa-spin');

            console.log(data)
            if (data.state == 200) {

                $('#save_add_allowance').attr('item_key', '')
                $('#save_add_allowance').attr('item_historic_key', '')
                $('#add_allowance').val('').focus().removeClass('invalid')
                $('#add_allowance_qty').val('').removeClass('invalid')
                $('#add_allowance_save').addClass('super_discreet').removeClass('invalid valid button')



                $('.order_operation').addClass('hide')
                //$('.items_operation').addClass('hide')


                for (var key in data.metadata.operations) {
                    $('#' + data.metadata.operations[key]).removeClass('hide')
                }


                $('.Total_Amount').attr('amount', data.metadata.to_pay)
                $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)


                if (data.metadata.to_pay == 0 || data.metadata.payments == 0) {
                    $('.Order_Payments_Amount').addClass('hide')
                    $('.Order_To_Pay_Amount').addClass('hide')

                } else {
                    $('.Order_Payments_Amount').removeClass('hide')
                    $('.Order_To_Pay_Amount').removeClass('hide')

                }

                if (data.metadata.to_pay != 0 || data.metadata.payments == 0) {
                    $('.Order_Paid').addClass('hide')
                } else {
                    $('.Order_Paid').removeClass('hide')
                }

                if (data.metadata.to_pay <= 0) {
                    $('.payment_operation').addClass('hide')

                } else {
                    $('.payment_operation').removeClass('hide')
                }


                if (data.metadata.to_pay == 0) {
                    $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

                } else {
                    $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

                }


                if (data.metadata.items == 0) {
                    $('.payments').addClass('hide')
                    $('#submit_operation').addClass('hide')
                    $('#send_to_warehouse_operation').addClass('hide')





                }
                else {


                    $('.payments').removeClass('hide')
                    $('#submit_operation').removeClass('hide')
                    $('#send_to_warehouse_operation').removeClass('hide')
                }


                rows.fetch({
                    reset: true
                });

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