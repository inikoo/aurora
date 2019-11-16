{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 December 2017 at 11:12:13 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
<div id="add_part_to_location_form" style="float:right;" class="hide" data-metadata="{$data.metadata}">
    <span id="add_part_to_location_invalid_msg" class="hide">{t}Invalid value{/t}</span>

    <span>{$data.field_label}</span>
    <input style="margin-right:2px" id="add_part_to_location" class="item " value=""
           placeholder="{if isset($data.placeholder)}{$data.placeholder}{/if}">
    <input style="margin-right:2px" id="add_part_to_location_qty" class="qty width_50 " value="0" placeholder="{t}stock{/t}">
    <input style="margin-right:2px" id="add_part_to_location_note" class="qty width_100 " value="" placeholder="{t}note{/t}">

    <div id="add_part_to_location_results_container" class="search_results_container hide" style="width:400px;">

        <table id="add_part_to_location_results" style="background:white;font-size:90%">
            <tr class="hide" id="add_part_to_location_search_result_template" field="" item_key="" item_historic_key=""
                formatted_value="" onClick="select_add_part_to_location_option(this)">
                <td class="code" style="padding-left:5px;"></td>
                <td class="label" style="padding-left:5px;"></td>

            </tr>
        </table>

    </div>


    <i id="add_part_to_location_save" item_key="" item_historic_key="" class="save fa fa-cloud super_discreet"
       onClick="save_add_part_to_location()"></i>
    <i class="fa fa-times padding_left_10 padding_right_10 button" onClick="close_add_part_to_location()"></i>


</div>


<script>
    $("#add_part_to_location_form").on("input propertychange", function (evt) {


        if ($(evt.target).attr('id') == 'add_part_to_location') {
            var delay = 100;
            if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
            delayed_on_change_add_part_to_location_field($(this), delay)
        } else {
            validate_add_part_to_location()
        }
    });


    function delayed_on_change_add_part_to_location_field(object, timeout) {

        window.clearTimeout(object.data("timeout"));

        object.data("timeout", setTimeout(function () {

            get_items_select()
        }, timeout));
    }

    function get_items_select() {

        $('#add_part_to_location_form').removeClass('invalid')


        var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent($('#add_part_to_location').val()) + '&scope=parts' + '&metadata=' + atob($('#add_part_to_location_form').data("metadata")) + '&state=' + JSON.stringify(state)

        console.log(request)

        $.getJSON(request, function (data) {


            console.log(data)

            if (data.number_results > 0) {
                $('#add_part_to_location_results_container').removeClass('hide').addClass('show')
                $('#add_part_to_location').removeClass('invalid')

            } else {


                $('#add_part_to_location_results_container').addClass('hide').removeClass('show')

                //console.log(data)
                if ($('#add_part_to_location').val() != '') {
                    $('#add_part_to_location').addClass('invalid')
                } else {
                    $('#add_part_to_location').removeClass('invalid')
                }

                $('#save_add_part_to_location').attr('item_key', '')
                $('#save_add_part_to_location').attr('item_historic_key', '')

                validate_add_part_to_location()

            }


            $("#add_part_to_location_results .result").remove();

            var first = true;

            for (var result_key in data.results) {



                var clone = $("#add_part_to_location_search_result_template").clone()
                clone.prop('id', 'add_part_to_location_result_' + result_key);
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

                $("#add_part_to_location_results").append(clone)


            }

        })


    }


    function select_add_part_to_location_option(element) {


        $('#add_part_to_location').val($(element).attr('formatted_value'))
        $('#add_part_to_location_save').attr('item_key', $(element).attr('item_key'))
        $('#add_part_to_location_save').attr('item_historic_key', $(element).attr('item_historic_key'))



        $('#add_part_to_location_results_container').addClass('hide').removeClass('show')

        $('#add_part_to_location_qty').focus()


        validate_add_part_to_location()

        console.log($('#add_part_to_location_save').attr('item_key'))
        console.log($('#add_part_to_location_save').attr('item_historic_key'))


    }

    function validate_add_part_to_location() {


        var invalid = false;

        if ($('#add_part_to_location_qty').val() == '') {
            $('#add_part_to_location_qty').removeClass('invalid')

        } else {

            var qty_val = validate_signed_integer($('#add_part_to_location_qty').val(), 4294967295);
            if (!qty_val) {
                $('#add_part_to_location_qty').removeClass('invalid')
            } else {
                $('#add_part_to_location_qty').addClass('invalid')
                invalid = true
            }
        }

        if ($('#add_part_to_location').hasClass('invalid')) {
            invalid = true;
            console.log($('#add_part_to_location').hasClass('invalid'))

        }

      //  console.log(invalid)



        if (invalid) {
            $('#add_part_to_location_save').addClass('invalid').removeClass('super_discreet valid button changed')
        } else {
            $('#add_part_to_location_save').removeClass('invalid')

            if ($('#save_add_part_to_location').attr('item_key') != '' && $('#add_part_to_location_qty').val() != '') {
                $('#add_part_to_location_save').addClass('valid button changed').removeClass('super_discreet')
            } else {
                $('#add_part_to_location_save').removeClass('valid button changed').addClass('super_discreet')
            }

        }


    }

    $('#{$trigger}').on("click", function () {

        show_add_part_to_location_form()

    });


    function show_add_part_to_location_form() {

        $('#add_part_to_location_msg').html('').removeClass('error success')
        $('#add_part_to_location_form').removeClass('hide')
        $('.table_button').addClass('hide')

        $('#save_add_part_to_location').attr('item_key', '')
        $('#save_add_part_to_location').attr('item_historic_key', '')
        $('#add_part_to_location').val('').focus().removeClass('invalid')
        $('#add_part_to_location_qty').val('').removeClass('invalid')
        $('#add_part_to_location_save').addClass('super_discreet').removeClass('invalid valid button')

    }


    function close_add_part_to_location() {
        $('#add_part_to_location_form').addClass('hide')
        $('.table_button').removeClass('hide')
    }

    function save_add_part_to_location() {


        console.log($('#table').data("metadata"))


        $('#add_part_to_location_save').addClass('fa-spinner fa-spin');


        var table_metadata = $('#table').data("metadata")



        var request = '/ar_edit_stock.php?tipo=add_part_to_location&location_key=' + table_metadata.parent_key + '&part_sku=' + $('#add_part_to_location_save').attr('item_key')  + '&stock=' + $('#add_part_to_location_qty').val() + '&note=' + $('#add_part_to_location_note').val()
        console.log(request)

        //=====
        var form_data = new FormData();

        form_data.append("tipo", 'add_part_to_location')

        form_data.append("location_key", table_metadata.parent_key)
        form_data.append("part_sku", $('#add_part_to_location_save').attr('item_key'))
        form_data.append("stock", $('#add_part_to_location_qty').val())
        form_data.append("note", $('#add_part_to_location_note').val())

        var request = $.ajax({

            url: "/ar_edit_stock.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'

        })


        request.done(function (data) {

            $('#add_part_to_location_save').removeClass('fa-spinner fa-spin');

            console.log(data)
            if (data.state == 200) {

                $('#save_add_part_to_location').attr('item_key', '')
                $('#save_add_part_to_location').attr('item_historic_key', '')
                $('#add_part_to_location').val('').focus().removeClass('invalid')
                $('#add_part_to_location_qty').val('').removeClass('invalid')
                $('#add_part_to_location_save').addClass('super_discreet').removeClass('invalid valid button')



                $('.order_operation').addClass('hide')
                //$('.items_operation').addClass('hide')




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