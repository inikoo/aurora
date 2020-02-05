<div id="add_item_to_portfolio_form" style="float:right;" class="hide" data-metadata="{$data.metadata}">
    <span id="add_item_to_portfolio_invalid_msg" class="hide">{t}Invalid value{/t}</span>

    <span>{$data.field_label}</span>
    <input style="margin-right:2px" id="add_item_to_portfolio" class="item " value=""
           placeholder="{t}code{/t}">
    <div id="add_item_to_portfolio_results_container" class="search_results_container hide" style="width:500px;">

        <table id="add_item_to_portfolio_results" style="background:white;font-size:90%;width: 100%">
            <tr class="hide" id="add_item_to_portfolio_search_result_template" data-field="" data-item_key=""
                data-formatted_value="" onClick="select_add_item_to_portfolio_option(this)">
                <td class="code" style="padding-left:5px;"></td>
                <td class="label" style="padding-left:5px;"></td>

            </tr>
        </table>

    </div>


    <i id="add_item_to_portfolio_save" data-item_key="" class="save fa fa-cloud super_discreet"
       onClick="save_add_item_to_portfolio()"></i>
    <i class="fa fa-times padding_left_10 padding_right_10 button" onClick="close_add_item_to_portfolio()"></i>


</div>


<script>



    $("#add_item_to_portfolio_form").on("input propertychange", function (evt) {


            var delay = 100;
            if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
            delayed_on_change_add_item_to_portfolio_field($(this), delay)
       
    });


    function delayed_on_change_add_item_to_portfolio_field(object, timeout) {

        window.clearTimeout(object.data("timeout"));

        object.data("timeout", setTimeout(function () {

            get_items_select()
        }, timeout));
    }

    function get_items_select() {

        $('#add_item_to_portfolio_form').removeClass('invalid')


        var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent($('#add_item_to_portfolio').val()) + '&scope=item' + '&metadata=' + atob($('#add_item_to_portfolio_form').data("metadata")) + '&state=' + JSON.stringify(state)
console.log(request)
        $.getJSON(request, function (data) {



            const offset=$('#add_item_to_portfolio_form').offset().left+$('#add_item_to_portfolio_form').width();
            if (data.number_results > 0) {
                $('#add_item_to_portfolio_results_container').removeClass('hide').addClass('show').offset({
                   'left':offset-$('#add_item_to_portfolio_results_container').width()
                })





                $('#add_item_to_portfolio').removeClass('invalid')

            } else {


                $('#add_item_to_portfolio_results_container').addClass('hide').removeClass('show')

                //console.log(data)
                if ($('#add_item_to_portfolio').val() != '') {
                    $('#add_item_to_portfolio').addClass('invalid')
                } else {
                    $('#add_item_to_portfolio').removeClass('invalid')
                }

                $('#save_add_item_to_portfolio').data('item_key', '')


            }


            $("#add_item_to_portfolio_results .result").remove();

            var first = true;

            for (var result_key in data.results) {



                var clone = $("#add_item_to_portfolio_search_result_template").clone();
                clone.prop('id', 'add_item_to_portfolio_result_' + result_key);
                clone.addClass('result').removeClass('hide')


                clone.data('item_key', data.results[result_key].value)

                clone.data('formatted_value', data.results[result_key].formatted_value)
                if (first) {
                    clone.addClass('selected')
                    first = false
                }

                clone.children(".label").html(data.results[result_key].description)
                clone.children(".code").html(data.results[result_key].code)

                $("#add_item_to_portfolio_results").append(clone)


            }


            $('#save_add_item_to_portfolio').data('item_key', '')
            $('#add_item_to_portfolio_save').addClass('super_discreet').removeClass('invalid valid button')



        })


    }


    function select_add_item_to_portfolio_option(element) {


        $('#add_item_to_portfolio').val($(element).data('formatted_value'))
        $('#add_item_to_portfolio_save').data('item_key', $(element).data('item_key'))



        $('#add_item_to_portfolio_results_container').addClass('hide').removeClass('show')


        $('#add_item_to_portfolio_save').addClass('valid button changed').removeClass('super_discreet')



    }

    $('#{$trigger}').on("click", function () {

        show_add_item_to_portfolio_form()

    });


    function show_add_item_to_portfolio_form() {

        $('#add_item_to_portfolio_msg').html('').removeClass('error success')
        $('#add_item_to_portfolio_form').removeClass('hide')
        $('.table_button').addClass('hide')

        $('#save_add_item_to_portfolio').data('item_key', '')
        $('#add_item_to_portfolio').val('').focus().removeClass('invalid')
        $('#add_item_to_portfolio_save').addClass('super_discreet').removeClass('invalid valid button')







    }


    function close_add_item_to_portfolio() {
        $('#add_item_to_portfolio_form').addClass('hide')
        $('.table_button').removeClass('hide')
    }

    function save_add_item_to_portfolio() {

        if(!$('#add_item_to_portfolio_save').hasClass('valid')){
            return
        }


        $('#add_item_to_portfolio_save').addClass('fa-spinner fa-spin');



        var table_metadata = $('#table').data("metadata")



      
        //=====
        var form_data = new FormData();

        form_data.append("tipo", 'add_product_to_portfolio')
        form_data.append("customer_key", table_metadata.parent_key)
        form_data.append("product_id", $('#add_item_to_portfolio_save').data('item_key'))
     

        var request = $.ajax({

            url: "/ar_edit_customers.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'

        })


        request.done(function (data) {

            $('#add_item_to_portfolio_save').removeClass('fa-spinner fa-spin');

            console.log(data)
            if (data.state == 200) {

                $('#save_add_item_to_portfolio').data('item_key', '')
                $('#add_item_to_portfolio').val('').focus().removeClass('invalid')
                $('#add_item_to_portfolio_save').addClass('super_discreet').removeClass('invalid valid button')





                rows.fetch({
                    reset: true
                });

                console.log(data.update_metadata)

                for (var key in data.update_metadata.class_html) {
                    console.log(key)
                    $('.' + key).html(data.update_metadata.class_html[key])
                }

                for (var key in data.update_metadata.hide) {
                    $('#' + data.update_metadata.hide[key]).addClass('hide')
                }
                for (var key in data.update_metadata.show) {
                    $('#' + data.update_metadata.show[key]).removeClass('hide')
                }






            } else if (data.state == 400) {
                Swal.fire({
                    type: 'error', title: data.msg
                })

            }

        })


        request.fail(function (jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)


        });


    }




</script>