{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 December 2017 at 12:08:22 GMT Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
<div id="add_allowance_to_order_recursion_deal_form" style="float:right;" class="hide" data-metadata="{$data.metadata}">
    <span id="add_allowance_to_order_recursion_deal_invalid_msg" class="hide">{t}Invalid value{/t}</span>

    <span>{$data.field_label}</span>
    <input style="margin-right:2px" id="add_allowance_to_order_recursion_deal" class="item " value=""
           placeholder="{if isset($data.placeholder)}{$data.placeholder}{/if}">
    <input style="margin-right:2px" id="add_allowance_to_order_recursion_deal_qty" class="qty width_50 " value="" placeholder="%">
    <div id="add_allowance_to_order_recursion_deal_results_container" class="search_results_container hide" style="width:400px;">

        <table id="add_allowance_to_order_recursion_deal_results" border="0" style="background:white;font-size:90%">
            <tr class="hide" style="" id="add_allowance_to_order_recursion_deal_search_result_template" field="" item_key="" item_historic_key=""
                formatted_value="" onClick="select_add_allowance_to_order_recursion_deal_option(this)">
                <td class="code" style="padding-left:5px;"></td>
                <td class="label" style="padding-left:5px;"></td>

            </tr>
        </table>

    </div>


    <i id="add_allowance_to_order_recursion_deal_save" item_key="" item_historic_key="" class="save fa fa-cloud super_discreet"
       onClick="save_add_allowance_to_order_recursion_deal()"></i>
    <i class="fa fa-times padding_left_10 padding_right_10 button" onClick="close_add_allowance_to_order_recursion_deal()"></i>


</div>


<script>
    $("#add_allowance_to_order_recursion_deal_form").on("input propertychange", function (evt) {


        if ($(evt.target).attr('id') == 'add_allowance_to_order_recursion_deal') {
            var delay = 100;
            if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
            delayed_on_change_add_allowance_to_order_recursion_deal_field($(this), delay)
        } else {
            validate_add_allowance_to_order_recursion_deal()
        }
    });


    function delayed_on_change_add_allowance_to_order_recursion_deal_field(object, timeout) {

        window.clearTimeout(object.data("timeout"));

        object.data("timeout", setTimeout(function () {

            get_items_select()
        }, timeout));
    }

    function get_items_select() {

        $('#add_allowance_to_order_recursion_deal_form').removeClass('invalid')


        var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent($('#add_allowance_to_order_recursion_deal').val()) + '&scope=allowance_target' + '&metadata=' + atob($('#add_allowance_to_order_recursion_deal_form').data("metadata")) + '&state=' + JSON.stringify(state)

        console.log(request)

        $.getJSON(request, function (data) {


            console.log(data)

            if (data.number_results > 0) {
                $('#add_allowance_to_order_recursion_deal_results_container').removeClass('hide').addClass('show')
                $('#add_allowance_to_order_recursion_deal').removeClass('invalid')

            } else {


                $('#add_allowance_to_order_recursion_deal_results_container').addClass('hide').removeClass('show')

                //console.log(data)
                if ($('#add_allowance_to_order_recursion_deal').val() != '') {
                    $('#add_allowance_to_order_recursion_deal').addClass('invalid')
                } else {
                    $('#add_allowance_to_order_recursion_deal').removeClass('invalid')
                }

                $('#save_add_allowance_to_order_recursion_deal').attr('item_key', '')
                $('#save_add_allowance_to_order_recursion_deal').attr('item_historic_key', '')

                validate_add_allowance_to_order_recursion_deal()

            }


            $("#add_allowance_to_order_recursion_deal_results .result").remove();

            var first = true;

            for (var result_key in data.results) {



                var clone = $("#add_allowance_to_order_recursion_deal_search_result_template").clone()
                clone.prop('id', 'add_allowance_to_order_recursion_deal_result_' + result_key);
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

                $("#add_allowance_to_order_recursion_deal_results").append(clone)


            }

        })


    }


    function select_add_allowance_to_order_recursion_deal_option(element) {


        $('#add_allowance_to_order_recursion_deal').val($(element).attr('formatted_value'))
        $('#add_allowance_to_order_recursion_deal_save').attr('item_key', $(element).attr('item_key'))
        $('#add_allowance_to_order_recursion_deal_save').attr('item_historic_key', $(element).attr('item_historic_key'))



        $('#add_allowance_to_order_recursion_deal_results_container').addClass('hide').removeClass('show')

        $('#add_allowance_to_order_recursion_deal_qty').focus()


        validate_add_allowance_to_order_recursion_deal()

        console.log($('#add_allowance_to_order_recursion_deal_save').attr('item_key'))
        console.log($('#add_allowance_to_order_recursion_deal_save').attr('item_historic_key'))


    }

    function validate_add_allowance_to_order_recursion_deal() {


        var invalid = false;

        if ($('#add_allowance_to_order_recursion_deal_qty').val() == '') {
            $('#add_allowance_to_order_recursion_deal_qty').removeClass('invalid')

        } else {

            var qty_val = validate_signed_integer($('#add_allowance_to_order_recursion_deal_qty').val(), 4294967295);
            if (!qty_val) {
                $('#add_allowance_to_order_recursion_deal_qty').removeClass('invalid')
            } else {
                $('#add_allowance_to_order_recursion_deal_qty').addClass('invalid')
                invalid = true
            }
        }

        if ($('#add_allowance_to_order_recursion_deal').hasClass('invalid')) {
            invalid = true;
            console.log($('#add_allowance_to_order_recursion_deal').hasClass('invalid'))

        }

      //  console.log(invalid)



        if (invalid) {
            $('#add_allowance_to_order_recursion_deal_save').addClass('invalid').removeClass('super_discreet valid button changed')
        } else {
            $('#add_allowance_to_order_recursion_deal_save').removeClass('invalid')

            if ($('#save_add_allowance_to_order_recursion_deal').attr('item_key') != '' && $('#add_allowance_to_order_recursion_deal_qty').val() != '') {
                $('#add_allowance_to_order_recursion_deal_save').addClass('valid button changed').removeClass('super_discreet')
            } else {
                $('#add_allowance_to_order_recursion_deal_save').removeClass('valid button changed').addClass('super_discreet')
            }

        }


    }

    $('#{$trigger}').on("click", function () {

        show_add_allowance_to_order_recursion_deal_form()

    });


    function show_add_allowance_to_order_recursion_deal_form() {

        $('#add_allowance_to_order_recursion_deal_msg').html('').removeClass('error success')
        $('#add_allowance_to_order_recursion_deal_form').removeClass('hide')
        $('.table_button').addClass('hide')

        $('#save_add_allowance_to_order_recursion_deal').attr('item_key', '')
        $('#save_add_allowance_to_order_recursion_deal').attr('item_historic_key', '')
        $('#add_allowance_to_order_recursion_deal').val('').focus().removeClass('invalid')
        $('#add_allowance_to_order_recursion_deal_qty').val('').removeClass('invalid')
        $('#add_allowance_to_order_recursion_deal_save').addClass('super_discreet').removeClass('invalid valid button')

    }


    function close_add_allowance_to_order_recursion_deal() {
        $('#add_allowance_to_order_recursion_deal_form').addClass('hide')
        $('.table_button').removeClass('hide')
    }

    function save_add_allowance_to_order_recursion_deal() {


        console.log($('#table').data("metadata"))


        $('#add_allowance_to_order_recursion_deal_save').addClass('fa-spinner fa-spin');


        var table_metadata = JSON.parse(atob($('#table').data("metadata")))



        var request = '/ar_edit_marketing.php?tipo=add_target_to_campaign&field=' + table_metadata.field + '&parent=' + table_metadata.parent + '&parent_key=' + table_metadata.parent_key + '&target_key=' + $('#add_allowance_to_order_recursion_deal_save').attr('item_key')  + '&allowance=' + $('#add_allowance_to_order_recursion_deal_qty').val()

        //=====
        var form_data = new FormData();

        form_data.append("tipo", 'add_target_to_campaign')
        form_data.append("field", table_metadata.field)
        form_data.append("parent", table_metadata.parent)
        form_data.append("parent_key", table_metadata.parent_key)
        form_data.append("target_key", $('#add_allowance_to_order_recursion_deal_save').attr('item_key'))
        form_data.append("allowance", $('#add_allowance_to_order_recursion_deal_qty').val())

        var request = $.ajax({

            url: "/ar_edit_marketing.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'

        })


        request.done(function (data) {

            $('#add_allowance_to_order_recursion_deal_save').removeClass('fa-spinner fa-spin');

            console.log(data)
            if (data.state == 200) {

                $('#save_add_allowance_to_order_recursion_deal').attr('item_key', '')
                $('#save_add_allowance_to_order_recursion_deal').attr('item_historic_key', '')
                $('#add_allowance_to_order_recursion_deal').val('').focus().removeClass('invalid')
                $('#add_allowance_to_order_recursion_deal_qty').val('').removeClass('invalid')
                $('#add_allowance_to_order_recursion_deal_save').addClass('super_discreet').removeClass('invalid valid button')





                rows.fetch({
                    reset: true
                });






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