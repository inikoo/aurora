/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapoure.
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function new_object_init() {
    $(".value").each(function(index) {

        var field = $(this).attr('field')
        var value = $('#' + field).val()
        var field_data = $('#' + field + '_container')


        if(field_data.length==0){
            if(field=='Register_Date_From'){
                field_data = $('#Register_Date_container')
            }else if(field=='Register_Date_To'){
                field_data = $('#Register_Date_container')
            }
        }


        var type = field_data.attr('field_type')



        if(field_data.hasClass('address_value')){
            var required = field_data.closest('tbody.address_fields').attr('_required')
        }else{
            var required = field_data.attr('_required')
        }

        var server_validation = field_data.attr('server_validation')
        var parent = field_data.attr('parent')
        var parent_key = field_data.attr('parent_key')
        var _object = field_data.attr('object')
        var key = field_data.attr('key')



        var validation = validate_field(field, value, type, required, server_validation, parent, parent_key, _object, key)


        if (validation.class == 'invalid' && value == '') {
            validation.class = 'potentially_valid'
        }
        //console.log(field)
        // console.log('#' + field + '_field')


        if(type!='date_interval'){
            $('#' + field + '_field').removeClass('invalid potentially_valid valid').addClass(validation.class)
        }




    });
    var form_validation = get_form_validation_state()
    process_form_validation(form_validation)
}

function toggle_options(field) {
    if ($('#' + field + '_options').hasClass('hide')) {

        $('#' + field + '_options').removeClass('hide')
    } else {
        $('#' + field + '_options').addClass('hide')
    }

}




function show_options(field) {
    $('#' + field + '_options').removeClass('hide')
}

function hide_options(field) {
    $('#' + field + '_options').addClass('hide')
}

function get_form_validation_state() {


    if($('#fields').attr('object')=='Mailshot'){
        return get_mailshot_form_validation_state();
    }else{
        form_validation = 'valid';

        $(".value").each(function (index) {

            var field = $(this).attr('field')
            //console.log(index)
            //console.log($(this).attr('id'))

            if($(this).attr('field_type')=='date_interval'   ){
                component_validation = 'valid'

            }else{
                if ($('#' + field + '_field').hasClass('invalid')) {
                    component_validation = 'invalid'



                } else if ($('#' + field + '_field').hasClass('valid')) {
                    component_validation = 'valid'
                } else {
                    component_validation = 'potentially_valid'

                }
            }





             if (component_validation != 'valid') {
                 console.log('#' + field + '_field')
                 console.log($('#' + field + '_field'))
                 console.log(field + ' ' + component_validation)
             }
            //if (component_validation == 'invalid' || component_validation == 'potentially_valid')
            if (component_validation == 'invalid') {
                form_validation = 'invalid';
            }

            if (form_validation == 'invalid') {
                return;
            }

            if (component_validation == 'potentially_valid') {
                form_validation = 'potentially_valid';
            }


        });


        return form_validation
    }



}

function process_form_validation(validation, submitting) {

    if (submitting && validation == 'potentially_valid') {
        validation = 'invalid'
    }
    $('#fields .controls').removeClass('invalid valid potentially_valid').addClass(validation).addClass('changed')




    if (validation == 'invalid') {
        $(".value").each(function (index) {

            var field = $(this).attr('field')


            if (!$('#' + field + '_field').hasClass('valid') &&   $('#' + field + '_field').length>0    ) {

                $('#' + field + '_field').addClass('invalid')





            }

        })
    }

    reset_controls()

    post_process_form_validation();

}

function post_process_form_validation(){


    if($('#fields').attr('Object')=='Customers_List'){
        post_process_new_customer_list_form_validation()
    }else if($('#fields').attr('Object')=='Supplier'){
        post_process_new_supplier_form_validation()
    }



}



function save_new_object(object, form_type) {

    var form_validation = get_form_validation_state()


    process_form_validation(form_validation, true)



    if ($('tr.controls').hasClass('valid') && !$('tr.controls').hasClass('waiting')) {


        $('tr.controls').addClass('waiting')

        const  _object = object.replace(" ", "_");

        $('#' + _object + '_save_icon').removeClass('fa-cloud').addClass('fa-spinner fa-spin');





        var fields_data = {};
        var re = new RegExp('_', 'g');

        var form_data = new FormData();

        $(".value").each(function (index) {



            var field = $(this).attr('field')
            var field_type = $(this).attr('field_type')

            if (field_type == 'time') {
                value = clean_time($('#' + field).val())
            }else if (field_type == 'date' || field_type == 'date_interval') {

                if($('#' + field+'_value').val()!='') {
                    value = $('#' + field+'_value').val() + ' ' + $('#' + field + '_time').val()
                }else{
                    value=''
                }



            } else if (field_type == 'password' || field_type == 'password_with_confirmation' || field_type == 'password_with_confirmation_paranoid' || field_type == 'pin' || field_type == 'pin_with_confirmation' || field_type == 'pin_with_confirmation_paranoid') {
                value = sha256_digest($('#' + field).val())
            } else if (field_type == 'attachment') {
                form_data.append("file", $('#' + field).prop("files")[0])
                value = ''
            }else if (field_type == 'country_select') {
                value = $('#' + field).countrySelect("getSelectedCountryData").code

            } else if (field_type == 'telephone') {
                value = $('#' + field).intlTelInput("getNumber");

            }else if (field_type == 'parts_list') {
                var part_list_data = [];

                $('#parts_list_items  tr.part_tr').each(function (i, obj) {

                    //   if (!$(obj).find('.sku').val()) return true;

                    if ($(obj).hasClass('very_discreet')) {
                        var ratio = 0;
                    } else {
                        var ratio = $(obj).find('.parts_per_product').val()
                    }
                    var part_data = {
                        'Key': $(obj).find('.product_part_key').val(), 'Part SKU': $(obj).find('.sku').val(), 'Ratio': ratio, 'Note': $(obj).find('.note').val(),

                    }
                    part_list_data.push(part_data)

                });

                value = JSON.stringify(part_list_data)

            }  else if (field_type == 'user_permissions') {
                var user_groups = [];
                var stores = [];

                $('.permissions  .permission_type i').each(function (i, obj) {
                    if ($(obj).hasClass('fa-check-square') || $(obj).hasClass('fa-dot-circle')  ) {
                        user_groups.push($(obj).closest('.permission_type').data('group_id'))
                    }

                });
                $('.permissions  .permission_store i').each(function (i, obj) {
                    if ($(obj).hasClass('fa-check-square') || $(obj).hasClass('fa-dot-circle')  ) {
                        stores.push($(obj).closest('.permission_store').data('store_key'))
                    }

                });

                value = JSON.stringify({user_groups: user_groups, stores:stores})

            } else if (field_type == 'subscription') {
                var icon = $(this).find('i')
                if (icon.hasClass('fa-toggle-on')) {
                    value = 'Yes'

                } else {
                    value = 'No'
                }

            }  else if (field_type == 'elements') {
                var icon = $(this).find('i')
                if (icon.hasClass('fa-check-square')) {
                    value = 'Yes'

                } else {
                    value = 'No'
                }

            } else if (field_type == 'with_field') {
                var icon = $(this).find('i')
                if (icon.hasClass('fa-toggle-on')) {
                    value = 'Yes'

                }else if (icon.hasClass('fa-toggle-off')) {
                    value = 'No'

                } else {
                    value = ''
                }
            } else if(field_type == 'toggle') {

                //console.log('#' + field+'_field')
               // console.log( '#' + field+'_toggle')

                var value = $('#' + field+'_field').find(  '.' + field+'_toggle').hasClass('fa-toggle-on')
            }else if(field_type == 'button_radio_options') {
                var value = $('#' + field+'_field').hasClass('selected')
            }else if(field_type == 'input_with_field') {
                var value = $('#' + field+'_field').val()
            }else if(field_type == 'asset') {
                var value = $('#Asset_Key').val()
            }else if(field_type == 'list') {
                var value = $('#List_Key').val()
            }else {
                var value = $('#' + field).val()
            }
            //console.log($(this).attr('id') + ' ' + field + ' ' + $(this).closest('tr').hasClass('hide'))
            fields_data[field.replace(re, ' ')] = value
        });

        console.log(fields_data)


        if (form_type == 'setup') {
            save_setup(object, fields_data)
            return;
        }


        if (object == 'Attachment') {
            var ar_file = 'ar_upload.php';
            var tipo = 'upload_attachment';
        } else {
            var ar_file = 'ar_edit.php';
            var tipo = 'new_object';

        }





        // used only for debug
        //var request = '/' + ar_file + '?tipo=' + (form_type != '' ? form_type : tipo) + '&object=' + object + '&parent=' + $('#fields').attr('parent') + '&parent_key=' + $('#fields').attr('parent_key') + '&fields_data=' + JSON.stringify(fields_data)
        //console.log(request)




        form_data.append("tipo", (form_type != '' ? form_type : tipo))
        form_data.append("object", object)
        form_data.append("parent", $('#fields').attr('parent'))
        form_data.append("parent_key", $('#fields').attr('parent_key'))
        form_data.append("fields_data", JSON.stringify(fields_data))



        var request = $.ajax({

            url: "/" + ar_file, data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

        })

        request.done(function (data) {





            $('tr.controls').removeClass('waiting').removeClass('valid');
            if (data.state == 200) {


                if (data.pcard == '' && data.redirect != '') {

                    console.log(data.redirect)
                   // console.log(data.redirect_metadata)
                    change_view(data.redirect,data.redirect_metadata)
                    return;


                }

                $('#result').html(data.pcard).removeClass('hide')

                $('#fields').addClass('hide')


                for (var field in data.updated_data) {
                    $('.' + field).html(data.updated_data[field])
                }

                post_new_actions(object, data)

                $('#' + _object + '_save_icon').addClass('fa-cloud');
                $('#' + _object + '_save_icon').removeClass('fa-spinner fa-spin');


            } else if (data.state == 400) {
                $('#' + _object + '_save_icon').addClass('fa-cloud');
                $('#' + _object + '_save_icon').removeClass('fa-spinner fa-spin');
                $('tr.controls').addClass('error');


                $('#' + _object + '_msg').html(data.msg).removeClass('hide')
                $('#save_msg').html(data.msg).removeClass('hide')


            }

        })

        request.fail(function (jqXHR, textStatus) {

            $('#' + _object + '_save_icon').addClass('fa-cloud');
            $('#' + _object + '_save_icon').removeClass('fa-spinner fa-spin');

            $('#' + _object + '_save_icon').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
            $('tr.controls').removeClass('waiting valid').addClass('error')


            $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


        });


    }


}

function post_new_actions(object, data) {

   // console.log(data)

    switch (object) {
        case 'Timesheet_Record':

            rows.fetch({
                reset: true
            });
            break;


        default:

    }


}

function save_inline_new_object(trigger) {


    var object = $('#inline_new_object').attr('object')
    var parent = $('#inline_new_object').attr('parent')
    var parent_key = $('#inline_new_object').attr('parent_key')
    var field = $('#inline_new_object').attr('field')
    var field_edit = $('#' + field + '_container').attr('field_type')


    if (!$('#inline_new_object').hasClass('valid')) {

        return;
    }

    $('#inline_new_object').removeClass('valid ')
    $('#' + object + '_save').removeClass('fa-cloud').addClass('fa-spinner fa-spin')


    var fields_data = {};
    var re = new RegExp('_', 'g');

    if (field_edit == 'time') {
        value = clean_time($('#' + field).val())


        value = $('#' + field + '_date').val() + ' ' + value
        // value = fixedEncodeURIComponent(value)
    } else {
        var value = $('#' + field).val()
        //var value = fixedEncodeURIComponent($('#' + field).val())
    }


    fields_data[field.replace(re, ' ')] = value
    //var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + parent + '&parent_key=' + parent_key + '&fields_data=' + JSON.stringify(fields_data)
    //console.log(request)
    var form_data = new FormData();
    form_data.append("tipo", 'new_object')
    form_data.append("object", object)
    form_data.append("parent", parent)
    form_data.append("parent_key", parent_key)
    form_data.append("fields_data", JSON.stringify(fields_data))

    var request = $.ajax({
        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'
    })

    request.done(function (data) {


        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

        //console.log(data)
        if (data.state == 200) {


            //     $('#inline_new_object').addClass('hide')
            //     $('#icon_' + trigger).addClass('fa-plus')
            //     $('#icon_' + trigger).removeClass('fa-times')
            $('#' + field).val('').attr('has_been_valid', 0)
            $('#inline_new_object').addClass('potentially_valid')

            toggle_inline_new_object_form(trigger)
            $('#inline_new_object_msg').html(data.msg).addClass('success').removeClass('hide')


            for (var updated_fields in data.updated_data) {
                $('.' + updated_fields).html(data.updated_data[updated_fields])
            }

            rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
            rows.fetch({
                reset: true
            });

            if (with_elements) get_elements_numbers(rows.tab, rows.parameters)

            if (data.metadata != undefined && data.metadata.updated_showcase_fields != '') {
                for (var key in data.metadata.updated_showcase_fields) {
                    $('.' + key).html(data.metadata.updated_showcase_fields[key])
                }
            }

        } else if (data.state == 400) {
            $('#inline_new_object_msg').removeClass('invalid valid potentially_valid')
            $('#inline_new_object_msg').html(data.msg).addClass('invalid')
            $('#inline_new_object').addClass('invalid')


        }
    })

    request.fail(function (jqXHR, textStatus) {
        //console.log(textStatus)

       // console.log(jqXHR.responseText)
        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


    });


}

function toggle_inline_new_object_form(trigger) {


    var field = $('#inline_new_object').attr('field')
    var field_edit = $('#' + field + '_container').attr('field_type')

    $('#inline_new_object_msg').html('').removeClass('error success')

    if ($('#icon_' + trigger).hasClass('fa-plus') || $('#icon_' + trigger).hasClass('fa-link')) {


        if ($('#icon_' + trigger).hasClass('fa-link')) {
            var icon = 'fa-link';
        } else {
            var icon = 'fa-plus';
        }

        $('#inline_form').removeClass('hide')


        $('#inline_new_object').removeClass('hide')
        $('#icon_' + trigger).removeClass('fa-plus').removeClass('fa-link').addClass('fa-times')


        $('#icon_' + trigger).attr('icon', icon)

        $('#inline_form input.inline_input').focus()


    } else {
        $('#inline_new_object').addClass('hide')
        $('#inline_form').addClass('hide')

        $('#icon_' + trigger).addClass($('#icon_' + trigger).attr('icon')).removeClass('fa-times')

    }

}

function clone_it() {
    $('#result').html('')

    $('#fields').removeClass('hide');
    $('#result').addClass('hide');

}

function change_to_new_object_view() {
    var object = $('#fields').attr('object');
    request = $('#' + object + '_go_new').attr('request');
    change_view(request);
}

function reset_controls() {
    var object = $('#fields').attr('object');

    $('#' + object + '_save').removeClass('hide');
    $('.new_object_results').addClass('hide')
    $('#' + object + '_msg').html('').addClass('hide').removeClass('success');
    $('#save_msg').html('').addClass('hide').removeClass('success');
    $('#' + object + '_go_new').attr('request', '')
}

function update_new_address_fields(field, country_code, hide_recipient_fields, arg) {

    var request = '/ar_address.php?tipo=fields_data&country_code=' + country_code

    $.getJSON(request, function (data) {


        if (data.state == 200) {
            for (var key in data.fields) {
                var field_tr = $('#' + field + '_' + key + '_field')
                var container_tr = $('#' + field + '_' + key + '_container')
                var field_data = data.fields[key]

                field_tr.find('.label').html(field_data.label)
                // console.log(field_data)
                if (field_data.required) {
                    //console.log('xxx #' + field + '_' + key + '_container')
                    container_tr.attr('_required', 1);
                    field_tr.find('.fa-asterisk').addClass('required')

                } else {
                    container_tr.attr('_required', 0);
                    field_tr.find('.fa-asterisk').removeClass('required')

                }


                if (!field_data.render || (hide_recipient_fields && (key == 'recipient' || key == 'organization'))) {
                    field_tr.addClass('hide')
                } else {
                    field_tr.removeClass('hide')
                }
                field_tr.insertBefore('#' + field + '_country_field')


            }

            $(".address_value").each(function (index) {


                var field = $(this).attr('field')
                // console.log(field)
                var value = $('#' + field).val()

                var field_data = $('#' + field + '_container')

                //console.log('#' + field + '_container')
                var type = field_data.attr('field_type')
                var server_validation = field_data.attr('server_validation')
                var parent = field_data.attr('parent')
                var parent_key = field_data.attr('parent_key')
                var _object = field_data.attr('object')
                var key = field_data.attr('key')

                /*
                 if (field_data.attr('_required') == 1) {
                 var required = true
                 } else {
                 var required = false
                 }

                 */
                if (field_data.hasClass('address_value')) {
                    var required = field_data.closest('tbody.address_fields').attr('_required')

                } else {
                    var required = field_data.attr('_required')
                }


                var validation = validate_field(field, value, type, required, server_validation, parent, parent_key, _object, key)


                /*
                 if (arg == 'init') {

                 if (validation.class == 'invalid' && value == '') {

                 validation.class = 'potentially_valid'
                 }
                 }
                 */
//                console.log(field + ' ' + field_data.attr('_required') + ' ' + validation.class)
                if (field_data.attr('_required') == 1 && value == '' && validation.class == 'valid') {
                    validation.class = 'valid attention'
                }


                $('#' + field + '_field').removeClass('invalid potentially_valid valid').addClass(validation.class)


            });


        } else if (data.state == 400) {


        }
    })

}

function update_related_fields(country_data) {

    // console.log(country_data.iso2)
    $('#fields  .telephone_input_field').each(function (index) {

        if ($(this).attr('has_been_changed') == 0) {
            $(this).intlTelInput("setCountry", country_data.iso2);
        }
    })

    $('#fields  .address_fields').each(function (index) {
        if ($(this).attr('has_been_changed') == 0) {

            var field = $(this).attr('field')
            var country_select = $("#" + field + "_country_select")

            country_select.countrySelect("selectCountry", country_data.iso2);

            update_new_address_fields(field, country_data.iso2, hide_recipient_fields = true)
            $('#' + field + '_country  ').val(country_data.iso2.toUpperCase())

        }
    })





        if ($('#Supplier_Products_Origin_Country_Code').attr('has_been_changed') == 0) {
            $('#Supplier_Products_Origin_Country_Code').countrySelect("selectCountry", country_data.iso2);
        }
        // console.log(country_data)
        if ($('#Supplier_Default_Currency_Code').attr('has_been_changed') == 0) {
            $('#Supplier_Default_Currency_Code').countrySelect("selectCountryfromCode", country_data.currency);
        }


}

function show_field_options(element) {

    $(element).addClass('hide')

    $('#' + $(element).attr('field') + '_options_ul').removeClass('hide')

}

function select_option_for_new_object(element, field, value, formatted_value) {


    $('#' + field).val(value).addClass('hide')
    $('#' + field + '_formatted').val(formatted_value).removeClass('hide')


    $('#' + field + '_options li').removeClass('selected')
    $(element).addClass('selected')


    $('#' + field + '_options_ul').addClass('hide')


    on_changed_value(field, value)

}

