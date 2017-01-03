<div id="add_item_form" style="float:right;" class="hide" data-metadata="{$data.metadata}">
    <span id="add_item_invalid_msg" class="hide">{t}Invalid value{/t}</span>

    <span>{$data.field_label}</span>
    <input style="margin-right:2px" id="add_item" class="item " value=""
           placeholder="{if isset($data.placeholder)}{$data.placeholder}{/if}">
    <input style="margin-right:2px" id="add_item_qty" class="qty width_50 " value="" placeholder="{t}qty{/t}">
    <div id="add_item_results_container" class="search_results_container hide" style="width:400px;">

        <table id="add_item_results" border="0" style="background:white;font-size:90%">
            <tr class="hide" style="" id="add_item_search_result_template" field="" item_key="" item_historic_key=""
                formatted_value="" onClick="select_add_item_option(this)">
                <td class="code" style="padding-left:5px;"></td>
                <td class="label" style="padding-left:5px;"></td>

            </tr>
        </table>

    </div>


    <i id="add_item_save" item_key="" item_historic_key="" class="save fa fa-cloud super_discreet"
       onClick="save_add_item()"></i>
    <i class="fa fa-times padding_left_10 padding_right_10 button" onClick="close_add_item()"></i>


</div>


<script>
    $("#add_item_form").on("input propertychange", function (evt) {


        if ($(evt.target).attr('id') == 'add_item') {

            var delay = 100;
            if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
            delayed_on_change_add_item_field($(this), delay)
        } else {
            validate_add_item()
        }
    });


    function delayed_on_change_add_item_field(object, timeout) {

        window.clearTimeout(object.data("timeout"));

        object.data("timeout", setTimeout(function () {

            get_items_select()
        }, timeout));
    }

    function get_items_select() {

        $('#add_item_form').removeClass('invalid')


        var request = '/ar_find.php?tipo=find_object&query=' + fixedEncodeURIComponent($('#add_item').val()) + '&scope=item' + '&metadata=' + atob($('#add_item_form').data("metadata")) + '&state=' + JSON.stringify(state)

        $.getJSON(request, function (data) {

console.log('ss')
            if (data.number_results > 0) {
                $('#add_item_results_container').removeClass('hide').addClass('show')
                $('#add_item').removeClass('invalid')

            } else {


                $('#add_item_results_container').addClass('hide').removeClass('show')

                //console.log(data)
                if ($('#add_item').val() != '') {
                    $('#add_item').addClass('invalid')
                } else {
                    $('#add_item').removeClass('invalid')
                }

                $('#save_add_item').attr('item_key', '')
                $('#save_add_item').attr('item_historic_key', '')

                validate_add_item()

            }


            $("#add_item_results .result").remove();

            var first = true;

            for (var result_key in data.results) {

                var clone = $("#add_item_search_result_template").clone()
                clone.prop('id', 'add_item_result_' + result_key);
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

                $("#add_item_results").append(clone)


            }

        })


    }


    function select_add_item_option(element) {


        $('#add_item').val($(element).attr('formatted_value'))
        $('#add_item_save').attr('item_key', $(element).attr('item_key'))
        $('#add_item_save').attr('item_historic_key', $(element).attr('item_historic_key'))



        $('#add_item_results_container').addClass('hide').removeClass('show')

        $('#add_item_qty').focus()


        validate_add_item()

        console.log($('#add_item_save').attr('item_key'))


    }

    function validate_add_item() {


        var invalid = false;

        if ($('#add_item_qty').val() == '') {
            $('#add_item_qty').removeClass('invalid')

        } else {

            var qty_val = validate_signed_integer($('#add_item_qty').val(), 4294967295);
            if (!qty_val) {
                $('#add_item_qty').removeClass('invalid')
            } else {
                $('#add_item_qty').addClass('invalid')
                invalid = true
            }
        }

        if ($('#add_item').hasClass('invalid')) {
            invalid = true;
            console.log($('#add_item').hasClass('invalid'))

        }

      //  console.log(invalid)



        if (invalid) {
            $('#add_item_save').addClass('invalid').removeClass('super_discreet valid button')
        } else {
            $('#add_item_save').removeClass('invalid')

            if ($('#save_add_item').attr('item_key') != '' && $('#add_item_qty').val() != '') {
                $('#add_item_save').addClass('valid button').removeClass('super_discreet')
            } else {
                $('#add_item_save').removeClass('valid button').addClass('super_discreet')
            }

        }


    }

    $('#{$trigger}').on("click", function () {

        show_add_item_form()

    });


    function show_add_item_form() {

        $('#add_item_msg').html('').removeClass('error success')
        $('#add_item_form').removeClass('hide')
        $('.table_button').addClass('hide')

        $('#save_add_item').attr('item_key', '')
        $('#save_add_item').attr('item_historic_key', '')
        $('#add_item').val('').focus().removeClass('invalid')
        $('#add_item_qty').val('').removeClass('invalid')
        $('#add_item_save').addClass('super_discreet').removeClass('invalid valid button')

    }


    function close_add_item() {
        $('#add_item_form').addClass('hide')
        $('.table_button').removeClass('hide')
    }

    function save_add_item() {


        $('#add_item_save').addClass('fa-spinner fa-spin');
        var table_metadata = JSON.parse(atob($('#table').data("metadata")))



        var request = '/ar_edit.php?tipo=edit_item_in_order&field=' + table_metadata.field + '&parent=' + table_metadata.parent + '&parent_key=' + table_metadata.parent_key + '&item_key=' + $('#add_item_save').attr('item_key') + '&item_historic_key=' + $('#add_item_save').attr('item_historic_key') + '&qty=' + $('#add_item_qty').val()
        console.log(request)
        // return;
        //=====
        var form_data = new FormData();

        form_data.append("tipo", 'edit_item_in_order')
        form_data.append("field", table_metadata.field)
        form_data.append("parent", table_metadata.parent)
        form_data.append("parent_key", table_metadata.parent_key)
        form_data.append("item_key", $('#add_item_save').attr('item_key'))
        form_data.append("item_historic_key", $('#add_item_save').attr('item_historic_key'))
        form_data.append("qty", $('#add_item_qty').val())

        var request = $.ajax({

            url: "/ar_edit.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'

        })


        request.done(function (data) {

            $('#add_item_save').removeClass('fa-spinner fa-spin');

            console.log(data)
            if (data.state == 200) {

                $('#save_add_item').attr('item_key', '')
                $('#save_add_item').attr('item_historic_key', '')
                $('#add_item').val('').focus().removeClass('invalid')
                $('#add_item_qty').val('').removeClass('invalid')
                $('#add_item_save').addClass('super_discreet').removeClass('invalid valid button')


                rows.fetch({
                    reset: true
                });

                for (var key in data.metadata.class_html) {
                    console.log(key)
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