/*Author: Raul Perusquia <raul@inikoo.com>
 Created:   28 November 2019  15:51::59  +0100, Malaga Spain
 Copyright (c) 2019, Inikoo
 Version 3.0*/

$(function () {
    const $tab = $('#tab');
    $tab.on('click', '#table_buttons .move_all_parts_from_location', function () {

        const form=$('.move_all_parts_from_location_inline_form');
        $(this).addClass('hide');
        form.removeClass('hide');
        form.find('.inline_input').trigger('focus');
        $('.move_all_parts_from_location_inline_msg').html('')


    });

    $tab.on('click', '.move_all_parts_from_location_inline_form .close_move_all_parts_from_location', function () {
        $('.move_all_parts_from_location').removeClass('hide');
        $('.move_all_parts_from_location_inline_form').addClass('hide');
        $('.move_all_parts_from_location_inline_msg').html('')
    });

    $tab.on('click', '.move_all_parts_from_location_inline_form .remove_from_location', function () {
        const icon=$(this).find('i');
        if(icon.hasClass('fa-square')){
            icon.removeClass('fa-square');
            icon.addClass('fa-check-square')

        }else{
            icon.addClass('fa-square');
            icon.removeClass('fa-check-square')

        }

    });



    $tab.on('click', '.move_all_parts_from_location_inline_form .item_option', function () {

        let container=$(this).closest('.move_all_parts_from_location_inline_form');
        container.find('.inline_input').val($(this).data('formatted_value'));
        container.find('.move_all_parts_from_location_save').data('location_key', $(this).data('location_key'));
        container.find('.search_results_container').addClass('hide').removeClass('show');

        validate_move_all_parts_from_location(container)


    });

    $tab.on('input propertychange', '.move_all_parts_from_location_inline_form .inline_input', function () {

        const element=$(this);
        const timeout=100;
        window.clearTimeout(element.data("timeout"));
        element.data("timeout", setTimeout(function () {

            element.removeClass('invalid');
            const request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(element.val()) + '&scope=locations';
            $.getJSON(request, function (data) {

                const container=$('.move_all_parts_from_location_inline_form');

                if (data.number_results > 0) {
                    container.find('.search_results_container').removeClass('hide').addClass('show');
                    container.find('.inline_input').removeClass('invalid')

                } else {

                    container.find('.search_results_container').addClass('hide').removeClass('show');

                    if (container.find('.inline_input').val() != '') {
                        container.find('.inline_input').addClass('invalid')
                    } else {
                        container.find('.inline_input').removeClass('invalid')
                    }

                    container.find('.move_all_parts_from_location_save').data('location_key', '');

                    validate_move_all_parts_from_location(container)

                }


                container.find(".move_part_to_location_res .result").remove();

                var first = true;

                for (var result_key in data.results) {



                    var clone = container.find(".add_item_search_result_template").clone();
                    clone.prop('id', 'add_item_result_' + result_key);
                    clone.addClass('item_option');

                    clone.addClass('result').removeClass('hide add_item_search_result_template');


                    clone.data('location_key', data.results[result_key].value);

                    clone.data('formatted_value', data.results[result_key].formatted_value);
                    if (first) {
                        clone.addClass('selected');
                        first = false
                    }

                    clone.children(".code").html(data.results[result_key].description);

                    container.find(".move_part_to_location_res").append(clone)


                }

            })


        }, timeout));

    });

    $tab.on('click', '.move_all_parts_from_location_inline_form .move_all_parts_from_location_save', function () {

        const save_button=$(this);

        let form=$(save_button).closest('.add_item_form');


        $(save_button).addClass('fa-spinner fa-spin');



        var form_data = new FormData();

        form_data.append("tipo", 'move_all_parts_from_location');
        form_data.append("from_location_key",$('.move_all_parts_from_location_inline_form').data('location_key'));
        form_data.append("to_location_key", $(save_button).data('location_key'));
        form_data.append("remove_after", ($('.remove_from_location i').hasClass('fa-check-square')?'Yes':'No'  ));


        var request = $.ajax({

            url: "/ar_edit_stock.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'

        });


        request.done(function (data) {

            $(save_button).removeClass('fa-spinner fa-spin');

            if (data.state == 200) {

                $(save_button).data('location_key', '');
                form.find('.inline_input').val('').removeClass('invalid');
                $(save_button).addClass('super_discreet').removeClass('invalid valid button');

                $('.move_all_parts_from_location').removeClass('hide');
                $('.move_all_parts_from_location_inline_form').addClass('hide');
                $('.move_all_parts_from_location_inline_msg').html(data.msg);

                rows.fetch({
                    reset: true
                });


            } else if (data.state == 400) {
                swal(data.msg);

            }

        });


        request.fail(function (jqXHR, textStatus) {
            console.log(textStatus);

            console.log(jqXHR.responseText)


        });


    });

    function validate_move_all_parts_from_location(container){

        let invalid = false;
        const input_location=$(container).find('.inline_input');
        const save_button=$(container).find('.move_all_parts_from_location_save');




        if (input_location.hasClass('invalid')) {
            invalid = true;
        }

        if (invalid) {
            save_button.addClass('invalid').removeClass('super_discreet valid button changed')
        } else {
            save_button.removeClass('invalid');

            if (save_button.data('location_key') != '' && input_location.val() != '' ) {
                save_button.addClass('valid button changed').removeClass('super_discreet')
            } else {
                save_button.removeClass('valid button changed').addClass('super_discreet')
            }

        }

    };

})
