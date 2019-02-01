/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 February 2019 at 13:05:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/
 
 

    function remove_recipient(element) {

        var mixed_recipients_container=$(element).closest('.mixed_recipients_container');

        if ($(element).closest('tr').hasClass('new')) {

            $(element).closest('tr').remove()



            mixed_recipients_container.data('added', mixed_recipients_container.data('added')-1)


        } else {
            if ($(element).closest('tr').hasClass('very_discreet')) {

                mixed_recipients_container.data('removed',mixed_recipients_container.data('removed')-1)
                
                $(element).closest('tr').removeClass('very_discreet')
                $(element).closest('tr').find('.recipient').removeClass('strikethrough')

            } else {



                $(element).closest('tr').addClass('very_discreet')
                $(element).closest('tr').find('.recipient').addClass('strikethrough')
                mixed_recipients_container.data('removed',mixed_recipients_container.data('removed')+1)

            }
        }


        

        validate_mixed_recipients_list(mixed_recipients_container)

    }

    function delayed_on_change_user_recipient_dropdown_select_field(object, timeout) {
       
        var new_value = $(object).val()


        window.clearTimeout(object.data("timeout"));

        object.data("timeout", setTimeout(function () {

            get_user_recipient_dropdown_select(object, new_value)
        }, timeout));
    }

    function get_user_recipient_dropdown_select(object, new_value) {

        var parent_key = $(object).attr('parent_key')
        var parent = $(object).attr('parent')
        var scope = $(object).attr('scope')


        var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(new_value) + '&scope=' + scope + '&parent=' + parent + '&parent_key=' + parent_key + '&state=' + JSON.stringify(state)
        console.log(request)
        $.getJSON(request, function (data) {

            var results_container = $(object).closest('td.mixed_recipients').find('.search_results_container')


            if (data.number_results > 0) {
                results_container.removeClass('hide').addClass('show')
            } else {


                results_container.addClass('hide').removeClass('show')

            }

            results_container.find('.result').remove();



            var first = true;

            for (var result_key in data.results) {

                var clone = results_container.find('.search_result_template').clone()


                clone.addClass('result').removeClass('hide search_result_template')
                clone.attr('value', data.results[result_key].value)
                clone.attr('formatted_value', data.results[result_key].formatted_value)
                clone.data('metadata', data.results[result_key].metadata)

                if (first) {
                    clone.addClass('selected')
                    first = false
                }

                clone.children(".code").html(data.results[result_key].code)
                clone.children(".label").html(data.results[result_key].description)

                results_container.find(".results").append(clone)
                    }

        })


    }

    function select_dropdown_user_recipient(element) {

        var mixed_recipients_container=$(element).closest('.mixed_recipients_container');


        $(element).closest('.user_tr').removeClass('in_process')
      //  $(element).closest('.user_tr').find('.fa-trash-alt').removeClass('fa-trash').addClass('fa-trash')

        $(element).closest('td.mixed_recipients').find('.User_Handle').html($(element).attr('formatted_value')).removeClass('hide')
        $(element).closest('td.mixed_recipients').find('.User_Handle_value').remove()

        $(element).closest('td.mixed_recipients').find('.user_key').val($(element).attr('value'))


        $(element).closest('td.mixed_recipients').find('.search_results_container').remove()


        mixed_recipients_container.data('added', mixed_recipients_container.data('added')+1)
        
        
        validate_mixed_recipients_list(mixed_recipients_container)

    }

    function add_user_to_mixed_recipients(element) {

        var mixed_recipients_container=$(element).closest('.mixed_recipients_container');



        var clone = mixed_recipients_container.find('.new_user_recipient').clone()
        clone.prop('id','')
        clone.removeClass('hide new_user_recipient')


        mixed_recipients_container.find(".users_recipients_items").append(clone);

        clone.find('.User_Handle_value').focus()


    }

    function add_external_email_to_mixed_recipients(element){

        var mixed_recipients_container=$(element).closest('.mixed_recipients_container');


        var clone = mixed_recipients_container.find('.new_external_email_recipient').clone()
        clone.prop('id', '')
        clone.removeClass('hide new_external_email_recipient').find('input').addClass('external_email_mixed_recipients_value')
        mixed_recipients_container.find(".external_emails_recipients_items").append(clone);
        clone.find('.external_email_mixed_recipients_value').focus()

        mixed_recipients_container.data('added',mixed_recipients_container.data('added')+1)
        validate_mixed_recipients_list(mixed_recipients_container)
    }

    function save_mixed_recipients(element) {
        var mixed_recipients_container=$(element).closest('.mixed_recipients_container');

        save_field($('#fields').attr('object'), $('#fields').attr('key'),mixed_recipients_container.data('field'))
    }

    $(document).on('input propertychange', '.external_email_mixed_recipients_value', function (evt) {

        var email=$(this).val();
        var validation_data=client_validation('email',true,email,'')




        $(this).removeClass('invalid potentially_valid valid').addClass(validation_data.class)



        var mixed_recipients_container=$(this).closest('.mixed_recipients_container');

        validate_mixed_recipients_list(mixed_recipients_container)

    });


    $(document).on('input propertychange', '.mixed_recipients_container .User_Handle_value', function (evt) {


        var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_user_recipient_dropdown_select_field($(evt.target), delay)
    });

    function validate_mixed_recipients_list(mixed_recipients_container) {



        var validation = 'valid';

        $('.external_email_mixed_recipients_value',mixed_recipients_container).each(function (i, obj) {

           if($(obj).hasClass('invalid')){
               validation = 'invalid';
               return false
           }
            if($(obj).hasClass('potentially_valid')){
                validation = 'potentially_valid';
                return false
            }

        })



        console.log(validation)

        var save_element=  mixed_recipients_container.find('.save')

        var save_field=  $('#'+mixed_recipients_container.data('field')+'_field')


        save_element.removeClass('invalid valid potentially_valid').addClass(validation)
        save_field.removeClass('invalid valid potentially_valid').addClass(validation)



        if( mixed_recipients_container.data('added')>0 || mixed_recipients_container.data('removed')>0){
            save_element.addClass('changed')
            save_field.addClass('changed')
        }else{
            save_element.removeClass('changed valid')
            save_field.removeClass('changed valid')

        }


    }

