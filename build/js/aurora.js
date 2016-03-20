/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapoure.
 Copyright (c) 2015, Inikoo
 Version 3.0*/

var key_scope = false;

$(document).ready(function() {



    state = {
        module: '',
        section: '',
        parent: '',
        parent_key: '',
        object: '',
        key: ''
    }
    structure = {}

    change_view($('#_request').val())

    $(document).keydown(function(e) {
        key_press(e)
    });

})


function change_browser_history_state(request) {


    if (request == undefined) {
        return;
    }

    if (request.charAt(0) !== '/') {
        request = '/' + request
    }

    window.top.history.pushState({
        request: request
    }, '', request)

}

window.addEventListener('popstate', function(event) {
    change_view(event.state.request)

});

function change_tab(tab) {
    $('#maintabs .tab').removeClass('selected')
    $('#tab_' + tab.replace(/(:|\.|\[|\])/g, "\$1")).addClass('selected')
    change_view(state.request + '&tab=' + tab)
}

function change_subtab(subtab) {
    $('#maintabs .subtab').removeClass('selected')
    $('#subtab_' + subtab.replace(/(:|\.|\[|\])/g, "\$1")).addClass('selected')
    change_view(state.request + '&subtab=' + subtab)
}




function change_view(_request, metadata) {

    //console.log(metadata)
    if (metadata == undefined) {
        metadata = {};
    }

    var request = "/ar_views.php?tipo=views&request=" + _request + '&metadata=' + JSON.stringify(metadata) + "&old_state=" + JSON.stringify(state)

    $.getJSON(request, function(data) {

        //console.log(data);
        state = data.state;

        //console.log(data.state)
        if (typeof(data.navigation) != "undefined" && data.navigation !== null && data.navigation != '') {
            // $('#navigation').removeClass('hide')
            $('#navigation').html(data.navigation);
        } else {
            // $('#navigation').addClass('hide')
        }

        if (typeof(data.tabs) != "undefined" && data.tabs !== null) {
            $('#tabs').html(data.tabs);
        }

        if (typeof(data.menu) != "undefined" && data.menu !== null) {

            $('#menu').html(data.menu);


        }

        if (typeof(data.logout_label) != "undefined" && data.logout_label !== null) {
            $('#logout_label').html(data.logout_label);


        }



        if (typeof(data.view_position) != "undefined" && data.view_position !== null) {

            $('#view_position').html(data.view_position);
        }

        if (typeof(data.object_showcase) != "undefined" && data.object_showcase !== null && data.object_showcase != '') {
            $('#object_showcase').removeClass('hide')
            $('#object_showcase').html(data.object_showcase);
        } else {
            $('#object_showcase').addClass('hide')

        }
        if (typeof(data.tab) != "undefined" && data.tab !== null) {

            $('#tab').html(data.tab);
        }




        if (typeof(data.structure) != "undefined" && data.structure !== null) {
            structure = data.structure
        }



        change_browser_history_state(data.state.request)
        help()

    });

}




function logout() {
    window.location.href = "/logout.php";
}

function decodeEntities(a) {
    return a
}
/*
var decodeEntities = (function() {


  var element = document.createElement('div');

  function decodeHTMLEntities (str) {
    if(str && typeof str === 'string') {
      str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
      str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
      element.innerHTML = str;
      str = element.textContent;
      element.textContent = '';
    }

    return str;
  }

  return decodeHTMLEntities;
})();
*/

function htmlEncode(value) {
    return $('<div/>').text(value).html();
}

var isAdvancedUpload = function() {
        var div = document.createElement('div');
        return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
    }();

/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 November 2015 at 20:19:47 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function key_press(e) {

    switch (e.keyCode) {
    case 37:
        navigate(e, 'left');
        break;
    case 38:
        navigate(e, 'up');
    case 39:
        navigate(e, 'right');
        break
        break;
    case 40:
        navigate(e, 'down');
        break
    case 13:
        enter_hit(e)

        break;
    }


}

function navigate(e, direction) {

    if (key_scope) {

        switch (key_scope.type) {
        case 'search':
            navigate_search(e, direction);
            break;
        case 'option':
            navigate_option(e, key_scope.field, direction);
            break;
        case 'dropdown_select':
            navigate_dropdown_select(e, key_scope.field, direction);

        default:

        }

    }
}

function navigate_dropdown_select(e, field, direction) {

    switch (direction) {
    case 'up':
        e.preventDefault();
        var element = $('#' + field + '_results .result.selected').prev()
        if (element.attr('id') != undefined) {
            $('#' + field + '_results .result.selected').removeClass('selected');
            element.addClass('selected');
        }
        break;

    case 'down':
        e.preventDefault();
        var element = $('#' + field + '_results .result.selected').next()
        if (element.attr('id') != undefined) {
            $('#' + field + '_results .result.selected').removeClass('selected');
            element.addClass('selected');
        }
        break;

    default:

    }
}


function navigate_search(e, direction) {

    switch (direction) {
    case 'up':
        e.preventDefault();
        var element = $('#results .result.selected').prev()
        if (element.attr('id') != undefined) {
            $('#results .result.selected').removeClass('selected');
            element.addClass('selected');
        }
        break;

    case 'down':
        e.preventDefault();
        var element = $('#results .result.selected').next()
        if (element.attr('id') != undefined) {
            $('#results .result.selected').removeClass('selected');
            element.addClass('selected');
        }
        break;

    default:

    }
}

function navigate_option(e, field, direction) {

    switch (direction) {
    case 'up':
        e.preventDefault();
        var element = $('#' + field + '_options  li.selected').prev()
        if (element.attr('id') != undefined) {
            select_option(field, element.attr('value'), element.attr('label'))
        }
        break;

    case 'down':
        e.preventDefault();
        var element = $('#' + field + '_options  li.selected').next()
        if (element.attr('id') != undefined) {
            select_option(field, element.attr('value'), element.attr('label'))
        }
        break;

    default:

    }
}


function enter_hit(e) {

    if (key_scope) {

        switch (key_scope.type) {
        case 'search':
            var view = $("#results .result.selected").attr('view')
            if (view) {
                change_view(view)
            }
            break;

        case 'dropdown_select':

            var field = $("#"+key_scope.field+"_results .result.selected").attr('field')
            var value = $("#"+key_scope.field+"_results .result.selected").attr('value')
            var formatted_value = $("#"+key_scope.field+"_results .result.selected").attr('formatted_value')


            if (field) {
                select_dropdown_option(field, value, formatted_value)
            }
            break;

        case 'option':
        case 'radio_option':
        case 'string':
        case 'telephone':
        case 'email':
        case 'anything':
        case 'int_unsigned':
        case 'smallint_unsigned':
        case 'mediumint_unsigned':
        case 'int':
        case 'smallint':
        case 'mediumint':
        case 'date':
        case 'pin':
        case 'password':
            save_field(key_scope.object, key_scope.key, key_scope.field)
            break;
        case 'pin_with_confirmation':
        case 'password_with_confirmation':
            if ($('#' + key_scope.field + '_confirm').hasClass('hide')) {
                confirm_field(key_scope.field)

            } else {
                save_field(key_scope.object, key_scope.key, key_scope.field)

            }
            break;

        default:

        }

    }

}

/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapoure.
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$(document).on('input propertychange', '#search', function() {
    var delay = 200;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_search($(this), delay)
});


function delayed_search(search_field, timeout) {

    var query = search_field.val()

    key_scope = {
        type: 'search'
    }
    if (query.length > 0) {
        $('#clear_search').removeClass('hide')
    } else {
        $('#clear_search').addClass('hide')

    }

    window.clearTimeout(search_field.data("timeout"));
    search_field.data("timeout", setTimeout(function() {
        search(query)
    }, timeout));
}


function search(query) {



    var request = '/ar_search.php?tipo=search&query=' + fixedEncodeURIComponent(query) + '&state=' + JSON.stringify(state)
    //   console.log(request)
    $.getJSON(request, function(data) {
        console.log(data)

        if (data.number_results > 0) {
            $('#results_container_shifted').removeClass('hide')
        } else {
            $('#results_container_shifted').addClass('hide')

        }


        $("#results .result").remove();

        var first = true;

        for (var result_key in data.results) {

            var clone = $("#search_result_template").clone()
            clone.prop('id', 'result_' + result_key);
            clone.addClass('result').removeClass('hide')
            clone.attr('view', data.results[result_key].view)
            if (first) {
                clone.addClass('selected')
                first = false
            }

            clone.children(".label").html(data.results[result_key].label)
            clone.children(".details").html(data.results[result_key].details)

            $("#results").append(clone)


        }

    })
}


function clear_search() {

    $('#search').val('')
    $('#clear_search').addClass('hide')
    $('#results_container_shifted').removeClass('show')
    $("#results .result").remove();

}

/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 18:17:38 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/







function show_filter() {
    $('#show_filter').addClass('hide')
    $('.filter').removeClass('hide')
    $('#filter input').focus()

}

function show_results_per_page() {
    var $results_per_page = $('#results_per_page')
    if ($results_per_page.hasClass('showing_options')) {
        $results_per_page.removeClass('showing_options')
        $('.results_per_page').addClass('hide')
    } else {
        $results_per_page.addClass('showing_options')
        $('.results_per_page').removeClass('hide')

    }

}

function change_results_per_page(results_per_page) {
    $('.results_per_page').removeClass('selected')
    $('#results_per_page_' + results_per_page).addClass('selected')
    rows.setPageSize(results_per_page)

    $('#results_per_page').attr('title', $('#results_per_page').attr('title').replace(/\(.*\)/g, '(' + results_per_page + ')'))


}


function change_period(period) {

    var parameters = jQuery.parseJSON(rows.parameters);

    if (period == 'date') {

        parameters.from = $('#select_date').val()
        parameters.to = $('#select_date').val()



    } else if (period == 'interval') {

        var components = $('#select_interval_from').val().split(/\//)
        parameters.from = components[2] + '-' + components[0] + '-' + components[1]
        components = $('#select_interval_to').val().split(/\//)
        parameters.to = components[2] + '-' + components[0] + '-' + components[1]


    } else {

    }

    $('#date_chooser div').removeClass('selected')
    $('#' + period).addClass('selected')

    $('#select_date_control_panel').addClass('hide')
    $('#select_interval_control_panel').addClass('hide')


    parameters.period = period;

    rows.parameters = JSON.stringify(parameters)

    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
    rows.fetch({
        reset: true
    });
    if (with_elements) get_elements_numbers(rows.tab, rows.parameters)

}





function get_elements_numbers(tab, parameters) {
    var request = "/ar_elements.php?tab=" + tab + "&parameters=" + parameters
    $.getJSON(request, function(data) {
        if (data.state == 200) {
            for (element in data.elements_numbers) {

                for (item in data.elements_numbers[element]) {
                    $("#element_qty_" + item).html('(' + data.elements_numbers[element][item] + ')')


                }
            }
        }
    })

}

function show_elements_types() {

    var button = $('#element_type')
    var icon = $('#element_type .fa')
    if (icon.hasClass('fa-bars')) {
        icon.removeClass('fa-bars')
        icon.addClass('fa-chevron-up')

        var offset = button.position();
        var height = button.height();

        $('#elements_chooser').removeClass('hide').offset({
            top: offset.top + height,
            left: offset.left
        })


    } else {
        icon.addClass('fa-bars')
        icon.removeClass('fa-chevron-up')
        $('#elements_chooser').addClass('hide')

    }



}


function change_elements_type(elements_type) {

    $('#elements .elements_group').addClass('hide')

    $("#elements_group_" + elements_type).removeClass('hide')


    $('#elements_chooser  div').removeClass('selected')

    $('#element_group_option_' + elements_type).addClass('selected')


    $('#elements_chooser  i').removeClass('fa-circle')
    $('#elements_chooser  i').addClass('fa-circle-o')

    $('#element_group_option_' + elements_type + ' i').addClass('fa-circle')
    $('#element_group_option_' + elements_type + ' i').removeClass('fa-circle-o')



    var icon = $('#element_type .fa')

    icon.addClass('fa-bars')
    icon.removeClass('fa-chevron-up')
    $('#elements_chooser').addClass('hide')


    var parameters = jQuery.parseJSON(rows.parameters);
    parameters.elements_type = elements_type;

    rows.parameters = JSON.stringify(parameters)

    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
    rows.fetch({
        reset: true
    });




}

function change_table_element(event, item) {


    if (event.altKey) {



        $('#elements i').removeClass('fa-check-square-o')
        $('#elements i').addClass('fa-square-o')
        $('#elements .element').removeClass('selected')

        $("#element_" + item).addClass('selected')
        $("#element_checkbox_" + item).removeClass('fa-square-o')
        $("#element_checkbox_" + item).addClass('fa-check-square-o')

    } else {
        if ($("#element_" + item).hasClass('selected')) {
            $("#element_" + item).removeClass('selected')
            $("#element_checkbox_" + item).removeClass('fa-check-square-o')
            $("#element_checkbox_" + item).addClass('fa-square-o')
        } else {

            $("#element_" + item).addClass('selected')

            $("#element_checkbox_" + item).removeClass('fa-square-o')
            $("#element_checkbox_" + item).addClass('fa-check-square-o')
        }
    }



    var parameters = jQuery.parseJSON(rows.parameters);
    $("#elements_group_" + parameters.elements_type + " .element").each(function(index) {
        //console.log($(this).attr('item_key') + ": " + $(this).hasClass('selected'));
        parameters['elements'][parameters.elements_type]['items'][$(this).attr('item_key')]['selected'] = $(this).hasClass('selected')

        // alert(parameters['elements'][rows.parameters.elements_type])
    });



    rows.parameters = JSON.stringify(parameters)

    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters

  rows.fetch({
        reset: true
    });
    
}




function show_export_dialog() {

    if ($('#export_dialog').hasClass('hide')) {
        $('#export_dialog').removeClass('hide')
        $("#export_dialog").css('left', -1 * $("#export_dialog").width());
        $("#export_dialog").css('top', $("#show_export_dialog").height());
    } else {
        hide_export_dialog()
    }

}

function hide_export_dialog() {
    $('#export_dialog').addClass('hide')
    hide_export_config_dialog()
    
    
    $('.export_download').addClass('hide').attr('title', '').click(function() {})
    $('.export_progress_bar_bg').addClass('hide').html('')
    $('.export_progress_bar').css('width', '0px').removeClass('hide').attr('title', '').html('')

    
}



function open_export_config(){
 if ($('#export_dialog_config').hasClass('hide')) {
        $('#export_dialog_config').removeClass('hide')
        $("#export_dialog_config").css('left', -1 * ($("#export_dialog_config").width() +40 + $("#export_dialog").width()));
        $("#export_dialog_config").css('top', $("#show_export_dialog").height());
    } else {
        hide_export_config_dialog()
    }
}

function hide_export_config_dialog() {
    $('#export_dialog_config').addClass('hide')
    
    
    
    
    
}

function toggle_export_field(key) {

    var field_element = $('#field_export_' + key)

    if (field_element.hasClass('fa-check-square-o')) {
        field_element.removeClass('fa-check-square-o')
        field_element.addClass('fa-square-o')

    } else {
        field_element.addClass('fa-check-square-o')
        field_element.removeClass('fa-square-o')
    }
}

function export_table(type) {
    $('#export_progress_bar_bg_' + type).removeClass('hide').html('&nbsp;' + $('#export_queued_msg').html())

    $('#export_table_excel').removeClass('link').addClass('disabled')
    $('#export_table_csv').removeClass('link').addClass('disabled')
    $('.field_export').removeClass('button').addClass('disabled')
    $('#stop_export_table_' + type).removeClass('hide')
    $('#stop_export_table_' + type).attr('stop', 0);

    var fields = []
    $('#export_dialog_config .field_export i').each(function(index, obj) {
        if ($(obj).hasClass('fa-check-square-o')) fields.push($(obj).attr('key'))
    });

    var request = "/ar_export.php?ar_file=" + rows.ar_file + "&tipo=" + rows.tipo + "&parameters=" + rows.parameters + '&type=' + type + '&state=' + JSON.stringify(state) + '&fields=' + JSON.stringify(fields)

    // console.log(request)
    $.getJSON(request, function(data) {
        if (data.state == 200) {
            get_export_process_bar(data.fork_key, data.tipo, type);
        }
    })

}


function stop_export(type) {
    $('#stop_export_table_' + type).attr('stop', 1);
}

function get_export_process_bar(fork_key, tag, type) {
    request = '/ar_fork.php?tipo=get_process_bar&fork_key=' + fork_key + '&tag=' + tag
    $.getJSON(request, function(data) {
        if (data.state == 200) {


            if ($('#stop_export_table_' + type).attr('stop') == 1) {

                $('.export_download').addClass('hide').attr('title', '').click(function() {})
                $('.export_progress_bar_bg').addClass('hide').html('')
                $('.export_progress_bar').css('width', '0px').removeClass('hide').attr('title', '').html('')
                $('#export_table_excel').addClass('link').removeClass('disabled')
                $('#export_table_csv').addClass('link').removeClass('disabled')
                $('.field_export').addClass('button').removeClass('disabled')
                $('#stop_export_table_' + type).addClass('hide')
                return;

            }

            if (data.fork_state == 'Queued') {
                setTimeout(function() {
                    get_export_process_bar(data.fork_key, data.tag, type)
                }, 100);


            } else if (data.fork_state == 'In Process') {

                $('#export_download_' + type).addClass('hide')

                $('#export_progress_bar_bg_' + type).removeClass('hide').html('&nbsp;' + data.download_info)
                $('#export_progress_bar_' + type).css('width', data.percentage).removeClass('hide').attr('title', data.progress).html('&nbsp;' + data.download_info);
                setTimeout(function() {
                    get_export_process_bar(data.fork_key, data.tag, type)
                }, 250);

            } else if (data.fork_state == 'Finished') {

                $('#download_' + type).attr('href', '/download.php?file=' + data.result)
                $('#export_download_' + type).removeClass('hide').attr('title', data.result_info).click(function() {
                    $("#download_" + type)[0].click();
                });
                $('#export_progress_bar_bg_' + type).addClass('hide').html('')
                $('#export_progress_bar_' + type).css('width', '0px').removeClass('hide').attr('title', '').html('')





                $('#export_table_excel').addClass('link').removeClass('disabled')
                $('#export_table_csv').addClass('link').removeClass('disabled')
                $('.field_export').addClass('button').removeClass('disabled')
                $('#stop_export_table_' + type).addClass('hide')



            }



        }
    })


}

/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 November 2015 at 12:20:32 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function validate_field(field, new_value, field_type, required, server_validation_settings, parent, parent_key, object, key) {

    var validation = client_validation(field_type, required, new_value, field)



    if (validation.class == 'valid' && server_validation_settings != '') {

        var settings = JSON.parse(server_validation_settings)


        if (settings.tipo == 'check_for_duplicates' && new_value == '') {
            return validation;
        }

        var validation = {
            class: 'waiting',
            type: ''
        }

        server_validation(settings, parent, parent_key, object, key, field, new_value)
    }



    return validation;
}


function validate_address(field) {
    var valid_state = {
        class: 'valid',
        type: ''
    }

    var invalid_fields = 0;

    $('#' + field + ' input.address_input_field').each(function(i, obj) {


        var tr = $(obj).closest('tr')

        var asterisk = tr.find('.fa-asterisk')

        if (!asterisk.hasClass('hide')) {
            // console.log($(obj).attr('field_name'))
            if ($(obj).val() == '') {
                invalid_fields++;
                tr.find('.show_buttons').removeClass('super_discret success').addClass('error')


                if ($(obj).attr('field_name') == 'Address Recipient') {
                    valid_state_type = 'missing_recipient'
                } else if ($(obj).attr('field_name') == 'Address Line 1') {
                    valid_state_type = 'missing_addressLine1'
                } else if ($(obj).attr('field_name') == 'Address Postal Code') {
                    valid_state_type = 'missing_postalCode'
                } else {
                    valid_state_type = 'missing_field'
                }

                valid_state = {
                    class: 'invalid',
                    type: valid_state_type
                }
            } else {
                tr.find('.show_buttons').addClass(' success').removeClass('super_discret error')

            }

        }


    });

    if (invalid_fields > 1) {
        valid_state = {
            class: 'invalid',
            type: 'missing_fields'
        }
    }


    return valid_state;
}


function client_validation(type, required, value, field) {

    //console.log(type + ' ' + required)
    var valid_state = {
        class: 'valid',
        type: ''
    }



    if (value == '') {
        if (required) {
            return {
                class: 'invalid',
                type: 'empty'
            }


        } else {
            return {
                class: 'valid',
                type: ''
            }

        }

    }


    switch (type) {


    case 'string':
        break;

    case 'handle':

        if (value.length < 4) {
            return {
                class: 'potentially_valid',

                type: 'short'
            }
        }

        break;

    case 'pin':

        if (value.length < 4) {
            return {
                class: 'potentially_valid',

                type: 'short'
            }
        }

        break;
    case 'password':

        if (value.length < 6) {
            return {
                class: 'potentially_valid',

                type: 'short'
            }
        }

        break;

    case 'password_with_confirmation':

        if (value.length < 6) {
            return {
                class: 'potentially_valid',

                type: 'short'
            }
        }

        break;

    case 'date':
        break;

    case 'telephone':




        if (value.length == 1) {
            if ($.isNumeric(value)) {
                return {
                    class: 'potentially_valid',
                    type: 'short'
                }
            } else {
                return {
                    class: 'invalid',
                    type: 'invalid'
                }
            }

        } else {


            if (!$('#' + field).intlTelInput("isValidNumber")) {
                var error = $('#' + field).intlTelInput("getValidationError");
                //   console.log(error)
                if (error == intlTelInputUtils.validationError.TOO_SHORT) {
                    return {
                        class: 'potentially_valid',
                        type: 'short'
                    }
                } else if (error == intlTelInputUtils.validationError.TOO_LONG) {
                    return {
                        class: 'invalid',
                        type: 'long'
                    }
                } else if (error == intlTelInputUtils.validationError.NOT_A_NUMBER) {
                    return {
                        class: 'invalid',
                        type: 'invalid'
                    }
                } else if (error == intlTelInputUtils.validationError.INVALID_COUNTRY_CODE) {
                    return {
                        class: 'invalid',
                        type: 'invalid_code'
                    }
                }

            }
        }

        break;


    case 'email':
    case 'new_email':



        var tmp = value.replace(/"[^"]*"/g, '')
        if (tmp.match(/"/g)) {
            // console.log('has quote')
        } else {
            //  console.log('dont has quote')
            if (tmp.match(/\s/g)) {


                return {
                    class: 'invalid',
                    type: 'spaces'
                }
            }





            if (tmp.match(/\(|\)|\,|:|;|<|>|\[|\]/g)) {




                if (tmp.match(/,/g)) {


                    return {
                        class: 'invalid',
                        type: 'comma'
                    }
                } else {


                    return {
                        class: 'invalid',
                        type: 'invalid_character'
                    }
                }

            }
            if (tmp.match(/^([^@]*@){2,}[^@]*$/g)) {
                console.log('error')

                return {
                    class: 'invalid',
                    type: 'double_at'
                }

            }





        }



        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,63})?$/



        if (!emailReg.test(value)) {

            return {
                class: 'potentially_valid',
                type: 'invalid'
            }
        }

        break;

    case 'time':


        var timelReg = /^[0-9\:]+$/
        if (!timelReg.test(value)) {

            return {
                class: 'invalid',
                type: 'invalid'
            }
        }

        if (value.length > 5) {

            return {
                class: 'invalid',
                type: 'invalid'
            }
        } else if (value.length == 1) {

            var partial_timeReg = /^[0-9]$/
            if (!partial_timeReg.test(value)) {
                return {
                    class: 'invalid',
                    type: 'invalid'
                }
            }

        } else if (value.length == 2) {


            var partial_timeReg = /^(1?[0-9]|2[0-3])|[0-9]:$/
            if (!partial_timeReg.test(value)) {
                return {
                    class: 'invalid',

                    type: 'invalid'
                }
            }


        } else if (value.length == 4) {


            var timelReg = /^(1?[0-9]|2[0-3]|0[0-9])[0-5][0-9]$/
            if (timelReg.test(value)) {

                return {
                    class: 'valid',
                    type: 'valid'
                }
            }

            var timelReg = /^(1?[0-9]|2[0-3]|0[0-9]):[0-5]$/
            if (timelReg.test(value)) {

                return {
                    class: 'potentially_valid',
                    type: 'invalid'
                }
            }


        }


        var timelReg = /^(1?[0-9]|2[0-3]|0[0-9]):[0-5][0-9]$/
        if (!timelReg.test(value)) {

            if (value.length == 5 || value.length == 4) {

                return {
                    class: 'invalid',
                    type: 'invalid'
                }
            } else {

                return {
                    class: 'potentially_valid',
                    type: 'invalid'
                }
            }
        } else {
            return {
                class: 'valid',
                type: ''
            }

        }

    case 'smallint_unsigned':
        var res = validate_signed_integer(value, 65535)
        if (res) return res
        break;
    case 'int_unsigned':
        var res = validate_signed_integer(value, 4294967295)
        if (res) return res
        break;
    case 'minutes_in_day':
        var res = validate_signed_integer(value, 1440)
        if (res) return res
        break;

    case 'minutes_in_break':

        if (value == 0) {

            return {
                class: 'invalid',
                type: 'invalid_break_duration'
            }
        }

        var res = validate_signed_integer(value, 1440)
        if (res) return res
        break;

    case 'seconds_in_day':
        var res = validate_signed_integer(value, 86400)
        if (res) return res
        break;
    case 'seconds_in_hour':
        var res = validate_signed_integer(value, 3600)
        if (res) return res
        break;

    case 'day_of_month':

        if (value == 0) {

            return {
                class: 'invalid',
                type: 'invalid_day_of_month'
            }
        }

        var res = validate_signed_integer(value, 31)
        if (res) return res
        break;

    case 'amount':

        var regex = /^[1-9]\d*(((,\d{3}){1})?(\.\d{0,2})?)$/;
        if (!regex.test(value)) {
            /// console.log('ccc')
            return {
                class: 'invalid',
                type: 'invalid_amount'
            }
        }
        break
    default:

    }


    return valid_state;
}

function validate_signed_integer(value, max_value) {

    if (!$.isNumeric(value)) {
        return {
            class: 'invalid',
            type: 'not_integer'
        }
    }

    if (value > max_value) {
        return {
            class: 'invalid',

            type: 'too_big'
        }
    }

    if (value < 0) {
        return {
            class: 'invalid',

            type: 'negative'
        }
    }

    if (Math.floor(value) != value) {
        return {
            class: 'invalid',

            type: 'not_integer'
        }
    }

    return false
}


function server_validation(settings, parent, parent_key, object, key, field, value) {



    console.log(settings.setup)

    if (settings.parent != null) {
        parent = settings.parent;
    }
    if (settings.parent_key != null) {
        parent_key = settings.parent_key;
    }

    if (settings.parent_key_field != null) {
        parent_key = $('#' + settings.parent_key_field).val()
    }


    $("#" + field + '_editor').addClass('waiting')

    if (settings.setup != null) {
            var request = '/ar_setup.php?tipo=' + settings.tipo + '&parent=' + parent + '&parent_key=' + parent_key + '&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value

    } else {

        var request = '/ar_validation.php?tipo=' + settings.tipo + '&parent=' + parent + '&parent_key=' + parent_key + '&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value
    }

    console.log(request)


    $.getJSON(request, function(data) {



        $("#" + field + '_field').removeClass('waiting invalid valid')


        $('#' + field + '_save_button').removeClass('fa-spinner fa-spin').addClass('fa-cloud')

        if (!$('#' + field + '_formatted_value').hasClass('hide')) {

            return;
        }

        if (data.state == 200) {

            var validation = data.validation
            var msg = data.msg

        } else {
            var validation = 'invalid'
            var msg = "Error, can't verify value on server"

        }


        $('#' + field + '_msg').html(msg)

        $('#' + field + '_field').addClass(validation)



        if ($('#fields').hasClass('new_object')) {
            var form_validation = get_form_validation_state()
            process_form_validation(form_validation)

        }


    })



}

/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 17:50:46 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function open_edit_this_field(scope) {
    open_edit_field($('#fields').attr('object'), $('#fields').attr('key'), $(scope).closest('tr').attr('field'))
}


function open_edit_field(object, key, field) {



    var type = $('#' + field + '_container').attr('field_type')
    //var offset = $('#' + field + '_label').position();
    $('#' + field + '_formatted_value').addClass('hide')
    $('#' + field + '_edit_button').addClass('hide')

    $('#' + field + '_reset_button').removeClass('hide')


    $('#' + field + '_msg').html('').removeClass('success error')



    switch (type) {
    case 'string':
    case 'handle':
    case 'textarea':
    case 'email':
    case 'new_email':
    case 'numeric':
    case 'amount':
    case 'int_unsigned':
    case 'smallint_unsigned':
    case 'mediumint_unsigned':
    case 'int':
    case 'smallint':
    case 'mediumint':
    case 'pin':
    case 'password':
    case 'dimensions':
        $('#' + field).removeClass('hide')
        $('#' + field).focus()
        $('#' + field + '_save_button').removeClass('hide')
        break;
    case 'dropdown_select':
        $('#' + field + '_dropdown_select_label').removeClass('hide')
        $('#' + field).focus()
        $('#' + field + '_save_button').removeClass('hide')
        break;
    case 'telephone':
    case 'new_telephone':

        $('#' + field).removeClass('hide')
        $('#' + field).focus()
        $('#' + field + '_save_button').removeClass('hide')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
            'display': 'block'
        })

    case 'address':
    case 'country':
    case 'new_delivery_address':



        $('#' + field).removeClass('hide')

        $('#' + field + '_save_button').removeClass('hide')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
            'display': 'block'
        })

        break;

    case 'other_delivery_address':
        $('#' + field + '_field').removeClass('hide')

        $('#show_new_delivery_address_field').addClass('hide')
        $('#other_delivery_addresses_field').addClass('hide')


        $('#' + field).removeClass('hide')

        $('#' + field + '_save_button').removeClass('hide')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
            'display': 'block'
        })

        break;


    case 'pin_with_confirmation':
    case 'password_with_confirmation':
        $('#' + field).removeClass('hide')
        $('#' + field + '_confirm_button').removeClass('hide')
        $('#' + field).focus()

        break;

    case 'option':
        $('#' + field + '_options').removeClass('hide')
        $('#' + field + '_formatted').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')


        break;
    case 'radio_option':
        $('#' + field + '_formatted').removeClass('hide')
        $('#' + field + '_options').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')

        break;
    case 'date':

        $('#' + field + '_formatted').removeClass('hide')
        $('#' + field + '_datepicker').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')

        break;

    case 'working_hours':
        $('#working_hours').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')

        break;
    case 'salary':
        $('#salary').removeClass('hide')
        $('#' + field + '_save_button').removeClass('hide')

        break;
    case 'parts_list':
        $('#parts_list').removeClass('hide')
        break;
    default:

    }
    key_scope = {
        type: type,
        object: object,
        key: key,
        field: field
    };
}

function close_edit_this_field(scope) {
    close_edit_field($(scope).closest('tr').attr('field'))
}


function close_edit_field(field) {
    var type = $('#' + field + '_container').attr('field_type')

    $('#' + field + '_formatted_value').removeClass('hide')


    $('#' + field + '_edit_button').removeClass('hide')
    $('#' + field + '_reset_button').addClass('hide')


    $('#' + field + '_save_button').addClass('hide')



    switch (type) {
    case 'string':
    case 'handle':
    case 'email':
    case 'int_unsigned':
    case 'smallint_unsigned':
    case 'mediumint_unsigned':
    case 'int':
    case 'smallint':
    case 'mediumint':
    case 'pin':
    case 'password':
    case 'textarea':
    case 'numeric':
    case 'amount':
    case 'dimensions':


        $('#' + field).addClass('hide')


        //$('#' + field + '_editor').removeClass('changed')
        break;
     case 'dropdown_select':
        $('#' + field + '_dropdown_select_label').addClass('hide')
       // $('#' + field + '_save_button').removeClass('hide')
        break;

 
    case 'new_email':
        $('#new_email_formatted_value').html('')
        $('#new_email_value').val('')
        $('#new_email').val('')
        on_changed_value('new_email', '')
        $('#new_email_field').addClass('hide')
        $('#show_new_email_field').removeClass('hide')
        break;
    case 'telephone':
        $('#' + field).addClass('hide')
        $('#' + field + '_editor').removeClass('changed')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
            'display': 'none'
        })

        break;
    case 'new_telephone':


        $('#' + field).addClass('hide')
        $('#' + field + '_editor').removeClass('changed')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
            'display': 'none'
        })

        $('#new_telephone_field').addClass('hide')
        $('#show_new_telephone_field').removeClass('hide')

        break;

    case 'address':
    case 'country':



        $('#' + field).addClass('hide')

        $('#' + field + '_save_button').addClass('hide')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
            'display': 'none'
        })
        break;

    case 'other_delivery_address':

        $('#' + field + '_field').addClass('hide')

        $('#show_new_delivery_address_field').removeClass('hide')
        $('#other_delivery_addresses_field').removeClass('hide')

        $('#' + field).addClass('hide')

        $('#' + field + '_save_button').addClass('hide')
        $('#' + field + '_field .intl-tel-input .flag-container').css({
            'display': 'none'
        })



        break;

    case 'new_delivery_address':

        $('#new_delivery_address_field').addClass('hide')

        $('#show_new_delivery_address_field').removeClass('hide')


        break;

    case 'pin_with_confirmation':
    case 'password_with_confirmation':


        $('#' + field).addClass('hide')
        $('#' + field + '_editor').removeClass('changed')
        $('#' + field + '_confirm_button').addClass('hide')



        $('#' + field + '_confirm').addClass('hide')

        break;
    case 'option':


        $('#' + field + '_options').addClass('hide')
        $('#' + field + '_formatted').addClass('hide')

        $('#' + field + '_options li.selected').removeClass('selected')
        $('#' + field + '_option_' + $('#' + field + '_value').val()).addClass('selected')

        $('#' + field + '_formatted').val($('#' + field + '_formatted_value').html())
        $("#" + field + '_editor').removeClass('changed')

        break;
    case 'radio_option':


        $('#' + field + '_options').addClass('hide')
        $('#' + field + '_formatted').addClass('hide')




        $('#' + field + '_options li').attr('is_selected', 0)
        $('#' + field + '_options li  .checkbox').removeClass('fa-check-square-o').addClass('fa-square-o')

        var values = $('#' + field + '_value').val().split(",");

        for (var i = 0; i < values.length; i++) {

            $('#' + field + '_option_' + values[i]).attr('is_selected', 1)
            $('#' + field + '_option_' + values[i] + ' .checkbox').addClass('fa-check-square-o').removeClass('fa-square-o')




        }
        $("#" + field + '_editor').removeClass('changed')

        break;
    case 'date':
        $('#' + field + '_formatted').addClass('hide')
        $('#' + field + '_datepicker').addClass('hide')


        $('#' + field + '_formatted').val($('#' + field + '_formatted_value').html())
        $("#" + field + '_editor').removeClass('changed')
        var date = chrono.parseDate($('#' + field + '_formatted').val())

        var value = date.toISOString().slice(0, 10)
        $('#' + field + '_datepicker').datepicker("setDate", date);



        break;
    case 'working_hours':
        $('#working_hours').addClass('hide')

        break;
    case 'salary':
        $('#salary').addClass('hide')
        break;
    case 'parts_list':
        $('#parts_list').addClass('hide')
        break;
    default:

    }

    $('#' + field + '_editor').removeClass('invalid valid')


    if (!$('#' + field + '_msg').hasClass('success')) {
        $('#' + field + '_msg').html('').addClass('hide')
    }

    key_scope = false

}

function delayed_on_change_field(object, timeout) {
    var field = object.attr('id');
    //console.log(object)
    var field_element = $('#' + field);
    var new_value = field_element.val()

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function() {
        on_changed_value(field, new_value)
    }, timeout));
}

function on_changed_confirm_value(field, confirm_value) {

    if (confirm_value != '') {
        $("#" + field + '_editor').addClass('changed')

    } else {
        $("#" + field + '_editor').removeClass('changed')

    }
    $('#' + field + '_editor').removeClass('invalid valid')

    var value = $('#' + field).val()
    if (value == confirm_value) {
        validation = 'valid'
    } else if (value.substring(0, confirm_value.length) == confirm_value) {
        validation = 'potentially_valid'
    } else {
        validation = 'invalid'
    }






    $('#' + field + '_editor').addClass(validation)

    if (validation == 'invalid') {
        if ($('#' + field + '_no_match_invalid_msg').length) {
            var msg = $('#' + field + '_no_match_invalid_msg').html()
        } else {
            var msg = $('#not_match_invalid_msg').html()
        }

        msg = msg + ' '

    } else {
        var msg = '';
    }
    $('#' + field + '_msg').html(msg)


}

function on_changed_value(field, new_value) {

   // console.log('changed: ' + field)

    var object = $('#fields').attr('object');

    if ($('#' + object + '_save').hasClass('hide')) {
        reset_controls()
    }
    var field_data = $('#' + field + '_container')
    var type = field_data.attr('field_type')

    if (type == 'date') {
        new_value = new_value + ' ' + $('#' + field + '_time').val()
    }


    if (new_value != $('#' + field + '_value').val()) {
        var changed = true;
    } else {
        var changed = false;
    }


    $('#' + field + '_field').removeClass('invalid valid potentially_valid')



    $('#' + field + '_field').addClass('changed')

    var validation = validate(field, new_value)
    process_validation(validation, field, false)









}


function validate(field, value) {

    var field_data = $('#' + field + '_container')



    $('#' + field + '_field').addClass('waiting_validation changed')
    $('#' + field + '_save_button').removeClass('fa-cloud').addClass('fa-spinner fa-spin')

    var server_validation = field_data.attr('server_validation')
    var parent = field_data.attr('parent')
    var parent_key = field_data.attr('parent_key')
    var _object = field_data.attr('object')
    var key = field_data.attr('key')
    var type = field_data.attr('field_type')
    var required = field_data.attr('_required')


    if (type == 'salary') {
        return validate_salary_components();
    } else {

        return validate_field(field, value, type, required, server_validation, parent, parent_key, _object, key)
    }


}


function process_validation(validation, field, final_value) {

    var field_data = $('#' + field + '_container')
    var type = field_data.attr('field_type')



    if (validation.class == 'potentially_valid' && final_value) {
        validation.class = 'invalid';

        if (type == 'salary') {

            $('#salary  input.salary_input_field').each(function(i, obj) {
                // console.log($(obj))
                if ($(obj).hasClass('potentially_valid')) {
                    $(obj).removeClass('potentially_valid').addClass('invalid')
                }


            });

        }

    }

    if (validation.class == 'waiting') return;

    $('#' + field + '_field').removeClass('waiting_validation')
    $('#' + field + '_save_button').removeClass('fa-spinner fa-spin').addClass('fa-cloud')


    var msg = '';

    if (validation.class == 'valid') {

        $('#' + field).attr('has_been_valid', 1)
        $('#' + field + '_field').removeClass('invalid potentially_valid').addClass('valid')
        //console.log('#' + field + '_field')
    } else if (validation.class == 'invalid') {

        $('#' + field + '_field').removeClass('valid').addClass('invalid')

        //  console.log($('#' + field + '_' + validation.type + '_invalid_msg'))
        if ($('#' + field + '_' + validation.type + '_invalid_msg').length) {
            msg = $('#' + field + '_' + validation.type + '_invalid_msg').html()
        } else {

            msg = $('#invalid_msg').html()
        }
    }

    $('#' + field + '_msg').html(msg)


    if ($('#fields').hasClass('new_object')) {


        $('#' + $('#' + field + '_container').attr('object') + '_msg').html('')

        var form_validation = get_form_validation_state()
        process_form_validation(form_validation)
    }

    if ($('#inline_new_object').attr('object') != undefined) {
        //console.log(validation.class)
        $('#inline_new_object_msg').removeClass('invalid valid potentially_valid')
        $('#inline_new_object_msg').html(msg).addClass(validation.class).removeClass('hide')

        $('#inline_new_object').removeClass('invalid valid potentially_valid')
        $('#inline_new_object').addClass(validation.class)

    }




}


function show_invalid_messages(field) {
    var validation = validate(field, $('#' + field).val(), $('#' + field + '_container'))

    process_validation(validation, field, true)

}


function select_option(field, value, label) {
    $('#' + field).val(value)
    $('#' + field + '_formatted').val(label)
    $('#' + field + '_options li').removeClass('selected')


    $('#' + field + '_option_' + value.replace(".", "\.")).addClass('selected')

    if ($('#' + field + '_container').hasClass('new')) {
        $('#' + field + '_options').addClass('hide')
    }

    on_changed_value(field, value)

}

function select_radio_option(field, value, label) {

    var checkbox_option = $('#' + field + '_option_' + value);


    if (checkbox_option.attr('is_selected') == 1) {
        $('#' + field + '_option_' + value + ' .checkbox').removeClass('fa-check-square-o').addClass('fa-square-o')
        checkbox_option.attr('is_selected', 0)
    } else {

        $('#' + field + '_option_' + value + ' .checkbox').addClass('fa-check-square-o').removeClass('fa-square-o')
        checkbox_option.attr('is_selected', 1)

    }

    var count_selected = 0;
    var selected = [];
    var selected_formatted = [];


    $('#' + field + '_options li').each(function() {
        if ($(this).attr('is_selected') == 1) {
            count_selected++;
            selected.push($(this).attr('value'))
            selected_formatted.push($(this).attr('label'))
        }
    });


    if ($('#' + field + '_container').hasClass('new')) {
        $('#' + field + '_formatted').val(selected_formatted.sort().join())
    }

    $('#' + field).val(selected.sort().join())
    on_changed_value(field, selected.sort().join())


}

function set_this_as_main(scope) {

    set_as_main($('#fields').attr('object'), $('#fields').attr('key'), $(scope).closest('tr').attr('field'))

}

function set_as_main(object, key, field) {
    var request = '/ar_edit.php?tipo=set_as_main&object=' + object + '&key=' + key + '&field=' + field


    $.getJSON(request, function(data) {


        //$('#' + field + '_save_button').addClass('fa-star').removeClass('fa-spinner fa-spin')
        if (data.state == 200) {


            if (data.action == 'new_field') {
                if (data.new_fields) {

                    for (var key in data.new_fields) {

                        create_new_field(data.new_fields[key])

                    }
                }
            }



            if (data.directory_field != '') {
                $('#' + data.directory_field + '_directory').html(data.directory)

                if (data.items_in_directory == 0) {
                    $('#' + data.directory_field + '_field').addClass('hide')
                } else {
                    $('#' + data.directory_field + '_field').removeClass('hide')

                }
            }

            if (data.other_fields) {
                for (var key in data.other_fields) {
                    update_field(data.other_fields[key])
                }
            }

            post_set_as_main(data)

        } else if (data.state == 400) {


        }
    })
}

function post_set_as_main(data) {

}

function save_this_field(scope) {
    save_field($('#fields').attr('object'), $('#fields').attr('key'), $(scope).closest('tr').attr('field'))
}

function save_this_address(scope) {
    save_field($('#fields').attr('object'), $('#fields').attr('key'), $(scope).closest('table').attr('field'))
}


function save_field(object, key, field) {

    var field_data = $('#' + field + '_container')

    var type = field_data.attr('field_type')
    required = field_data.attr('_required')
    var field_element = $('#' + field);
    var value = field_element.val()





    if (!$("#" + field + '_field').hasClass('changed')) {

        console.log('no_change')
        return;
    }

    if (!$("#" + field + '_field').hasClass('valid')) {
        console.log('invalid x')

        show_invalid_messages(field)

        return;
    }
    if (!$("#" + field + '_save_button').hasClass('fa-cloud')) {
        console.log('waiting for validation')

        return;
    }
    $('#' + field + '_save_button').removeClass('fa-cloud').addClass('fa-spinner fa-spin')


    if ($("#" + field + '_save_button').hasClass('potentially_valid')) {

        var server_validation = field_data.attr('server_validation')
        var parent = field_data.attr('parent')
        var parent_key = field_data.attr('parent_key')
        var _object = field_data.attr('object')
        var key = field_data.attr('key')


        var validation = validate_field(field, value, type, required, server_validation, parent, parent_key, _object, key)



        $('#' + field).attr('has_been_valid', 1)

        if ((type == 'password_with_confirmation' || type == 'in_with_confirmation') && !$('#' + field + '_confirm').hasClass('hide')) {

            if ($('#' + field + '_no_match_invalid_msg').length) {
                var msg = $('#' + field + '_no_match_invalid_msg').html()
            } else {
                var msg = $('#not_match_invalid_msg').html()
            }

            msg = msg + $('#' + field + '_cancel_confirm_button').html()


        } else {

            if ($('#' + field + '_' + validation.type + '_invalid_msg').length) {
                var msg = $('#' + field + '_' + validation.type + '_invalid_msg').html()
            } else {
                var msg = $('#invalid_msg').html()
            }
        }

        $('#' + field + '_msg').html(msg)
        $('#' + field + '_field').addClass('invalid')
        $('#' + field + '_save_button').addClass('fa-cloud').removeClass('fa-spinner fa-spin')

        return;
    }


    var metadata = {};

    if (type == 'date') {
        value = value + ' ' + $('#' + field + '_time').val()
    } else if (type == 'address' || type == 'new_delivery_address') {
        value = get_address_value(field)
    } else if (type == 'password' || type == 'password_with_confirmation' || type == 'password_with_confirmation_paranoid' || type == 'pin' || type == 'pin_with_confirmation' || type == 'pin_with_confirmation_paranoid') {
        value = sha256_digest(value)
    } else if (type == 'telephone') {
        value = $('#' + field).intlTelInput("getNumber");
        metadata = {

            'extra_fields': [{
                field: field + '_Formatted',
                value: fixedEncodeURIComponent($('#' + field).intlTelInput("getNumber", intlTelInputUtils.numberFormat.INTERNATIONAL))
            }]

        }
    }

    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value) + '&metadata=' + JSON.stringify(metadata)
    $.getJSON(request, function(data) {




        $('#' + field + '_save_button').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        if (data.state == 200) {

            $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')




            $('#' + field + '_value').val(data.value)




            $('.' + field).html(data.formatted_value)
            if (type == 'option') {
                $('#' + field + '_options li .current_mark').removeClass('current')
                $('#' + field + '_option_' + value + ' .current_mark').addClass('current')


            } else if (type == 'radio_option') {
                $('#' + field + '_options li .current_mark').removeClass('current')
                $('#' + field + '_option_' + value + ' .current_mark').addClass('current')


            } else if (type == 'dropdown_select') {
                //  $('#' + field + '').removeClass('current')
                $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')


            } else {
                $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')

            }



            if (data.action == 'deleted') {

                $('#' + field + '_edit_button').parent('.show_buttons').css('visibility', 'hidden')
                $('#' + field + '_label').find('.button').addClass('hide')

            }


            if (data.directory_field != '') {
                $('#' + data.directory_field + '_directory').html(data.directory)

                if (data.items_in_directory == 0) {
                    $('#' + data.directory_field + '_field').addClass('hide')
                } else {
                    $('#' + data.directory_field + '_field').removeClass('hide')
                }

            }
            if (data.action == 'new_field') {
                if (data.new_fields) {

                    for (var key in data.new_fields) {

                        create_new_field(data.new_fields[key])

                    }
                }
            }


            close_edit_field(field)

            if (data.other_fields) {
                for (var key in data.other_fields) {
                    update_field(data.other_fields[key])
                }
            }

            if (data.deleted_fields) {
                for (var key in data.deleted_fields) {
                    delete_field(data.deleted_fields[key])
                }
            }

            post_save_actions(field, data)

        } else if (data.state == 400) {
            $('#' + field + '_editor').removeClass('valid potentially_valid').addClass('invalid')

            $('#' + field + '_msg').html(data.msg).removeClass('hide')

        }
    })
}

function post_save_actions(field, data) {

    switch (field) {
    case 'User_Preferred_Locale':
        change_view(state.request, {
            'reload': true
        })
        break;


    default:

    }


}

function create_new_field(_data) {






    // console.log(_data)
    var clone_field = _data.field
    var clone = $('#' + _data.clone_from + '_field').clone()
    clone.prop('id', clone_field + '_field');

    clone.attr('field', clone_field);

    //clone.attr('field', clone_field);
    $('#' + _data.clone_from + '_field').after(clone)



    clone.find('.label').prop('id', clone_field + '_label')
    clone.find('i.reset_button').prop('id', clone_field + '_reset_button')
    clone.find('i.edit_button').prop('id', clone_field + '_edit_button')
    clone.find('td.container').prop('id', clone_field + '_container')

    clone.find('span.editor').prop('id', clone_field + '_editor')

    clone.find('span.formatted_value').prop('id', clone_field + '_formatted_value').removeClass(_data.clone_from).addClass(clone_field).html(_data.formatted_value)
    clone.find('input.unformatted_value').prop('id', clone_field + '_value').val(_data.value)


    if (_data.edit == 'string' || _data.edit == 'email' || _data.edit == 'new_email' || _data.edit == 'int_unsigned' || _data.edit == 'smallint_unsigned' || _data.edit == 'mediumint_unsigned' || _data.edit == 'int' || _data.edit == 'smallint' || _data.edit == 'mediumint' || _data.edit == 'anything' || _data.edit == 'numeric') {
        clone.removeClass('hide')
        clone.find('input.input_field').prop('id', clone_field).val(_data.value)
        clone.find('i.save').prop('id', clone_field + '_save_button').addClass(_data.edit)

        clone.find('span.msg').prop('id', clone_field + '_msg')

    } else if (_data.edit == 'address') {



        clone.find('table').prop('id', clone_field).attr('field', clone_field)



        //console.log(clone_field)
        clone.find('tr.recipient ').prop('id', clone_field + '_recipient')
        clone.find('tr.organization ').prop('id', clone_field + '_organization')
        clone.find('tr.addressLine1 ').prop('id', clone_field + '_addressLine1')
        clone.find('tr.addressLine2 ').prop('id', clone_field + '_addressLine2')
        clone.find('tr.sortingCode ').prop('id', clone_field + '_sortingCode')
        clone.find('tr.postalCode ').prop('id', clone_field + '_postalCode')
        clone.find('tr.dependentLocality ').prop('id', clone_field + '_dependentLocality')
        clone.find('tr.locality ').prop('id', clone_field + '_locality')
        clone.find('tr.administrativeArea ').prop('id', clone_field + '_administrativeArea')
        clone.find('tr.country ').prop('id', clone_field + '_country')
        clone.find('input.country_select ').prop('id', clone_field + '_country_select')


        var address_fields = jQuery.parseJSON(_data.value)

        $('#' + clone_field + '_recipient  input ').val(decodeEntities(address_fields['Address Recipient']))
        $('#' + clone_field + '_organization  input ').val(decodeEntities(address_fields['Address Organization']))
        $('#' + clone_field + '_addressLine1  input ').val(decodeEntities(address_fields['Address Line 1']))
        $('#' + clone_field + '_addressLine2  input ').val(decodeEntities(address_fields['Address Line 2']))
        $('#' + clone_field + '_sortingCode  input ').val(decodeEntities(address_fields['Address Sorting Code']))
        $('#' + clone_field + '_postalCode  input ').val(decodeEntities(address_fields['Address Postal Code']))
        $('#' + clone_field + '_dependentLocality  input ').val(decodeEntities(address_fields['Address Dependent Locality']))
        $('#' + clone_field + '_locality  input ').val(decodeEntities(address_fields['Address Locality']))
        $('#' + clone_field + '_administrativeArea  input ').val(decodeEntities(address_fields['Address Administrative Area']))
        var initial_country = address_fields['Address Country 2 Alpha Code'].toLowerCase();



        $('#' + clone_field + '_country_select').intlTelInput({
            initialCountry: initial_country,
            preferredCountries: $('#preferred_countries').val().split(',')
        });

        $('#' + clone_field + '_country_select').on("country-change", function(event, arg) {


            var country_name = $('#' + clone_field + '_country_select').intlTelInput("getSelectedCountryData").name
            var country_code = $('#' + clone_field + '_country_select').intlTelInput("getSelectedCountryData").iso2.toUpperCase()


            if (country_name.match(/\)\s+\(.+\)$/)) {
                country_name = country_name.replace(/\)\s+\(.+\)$/, ")")
            } else {
                country_name = country_name.replace(/\s+\(.+\)$/, "")

            }




            $('#' + clone_field + '_country  input.address_input_field ').val(country_code)



            update_address_fields(clone_field, country_code, hide_recipient_fields = false)
            $('#' + clone_field + '_country_select').val(country_name)
            if (arg != 'init') {

                on_changed_address_value(clone_field, clone_field + '_country', country_code)
            }

        });

        $('#' + clone_field + '_country_select').trigger("country-change", 'init');



    }

    if (_data.label != undefined) {
        $('#' + clone_field + '_label').html(_data.label)
    }





}


function delete_field(data) {
    var field = data.field

    $('#' + field + '_field').addClass('hide')
}

function update_field(data) {

    var field = data.field
    var type = $('#' + field + '_container').attr('field_type')


    if (data.render) {
        $('#' + field + '_field').removeClass('hide')
    } else {
        $('#' + field + '_field').addClass('hide')
        close_edit_field(field)
    }

    if (data.label != undefined) {
        $('#' + field + '_label').html(data.label)
    }


    if (data.value != undefined) {

        if (type == 'date') {
            $('.' + field).html(data.formatted_value)
            $("#" + field + "_datepicker").datepicker("setDate", new Date(data.formatted_value));
            $("#" + field).val(data.value)
            $("#" + field + '_formatted').val(data.formatted_value)
        }
        if (type == 'option') {
            //console.log(data.formatted_value)
            $("#" + field + '_formatted_value').html(data.formatted_value)


            $('#' + field).val(data.value)
            $('#' + field + '_options li').removeClass('selected').removeClass('current')


            $('#' + field + '_option_' + data.value.replace(".", "\.")).addClass('selected').addClass('current')
        } else {

            $('.' + field).html(data.formatted_value)
            $("#" + field).val(data.value)
        }
    }

    post_update_field(data)

}

function post_update_field(data) {

}

function hide_edit_field_msg(field) {
    $('#' + field + '_msg').html('').addClass('hide')
}

function confirm_field(field) {

    $('#' + field).addClass('hide')
    $('#' + field + '_confirm_button').addClass('hide')

    $('#' + field + '_confirm').removeClass('hide')
    $('#' + field + '_save_button').removeClass('hide')
    $('#' + field + '_editor').removeClass('invalid valid changed')

    $('#' + field + '_confirm').focus()

}

function cancel_confirm_field(field) {

    $('#' + field).removeClass('hide')
    $('#' + field + '_confirm_button').removeClass('hide')

    $('#' + field + '_confirm').addClass('hide')
    $('#' + field + '_save_button').addClass('hide')
    $('#' + field + '_editor').removeClass('invalid valid changed')
    $('#' + field + '_msg').html('')
    $('#' + field).val('')
    $('#' + field).attr('has_been_valid', 0)
    $('#' + field).focus()


}

function fixedEncodeURIComponent(str) {
    return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
}

function addZero2dateComponent(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}


function clean_time(value) {

    var Reg = /^[0-9]{4}$/
    if (Reg.test(value)) {
        time_components = [value.substring(0, 2), value.substring(2, 4)]
    } else {
        var time_components = value.split(':');
    }

    //console.log(time_components)
    var hours = addZero2dateComponent(parseInt(time_components[0]))
    var minutes = addZero2dateComponent(parseInt(time_components[1]))
    var seconds = '00';
    return hours + ':' + minutes + ':' + seconds


}



function add_minutes_to_time(time, minutes) {

    var time_components = time.split(':');
    var d = new Date(2000, 0, 1, time_components[0], parseInt(time_components[1]) + parseInt(minutes), time_components[2])
    time = addZero2dateComponent(d.getHours()) + ':' + addZero2dateComponent(d.getMinutes()) + ':00'
    return time;

}


function update_address_fields(field, country_code, hide_recipient_fields) {

    var request = '/ar_address.php?tipo=fields_data&country_code=' + country_code
    console.log(request)
    $.getJSON(request, function(data) {

        //console.log(field)
        if (data.state == 200) {
            for (var key in data.fields) {
                var field_tr = $('#' + field + '_' + key)
                var field_data = data.fields[key]

                field_tr.find('.label').html(field_data.label)
                //console.log(field_data)
                if (field_data.required) {




                    field_tr.find('.fa-asterisk').removeClass('hide')

                } else {
                    field_tr.find('.fa-asterisk').addClass('hide')

                }





                if (!field_data.render || (hide_recipient_fields && (key == 'recipient' || key == 'organization'))) {
                    field_tr.addClass('hide')
                } else {
                    field_tr.removeClass('hide')
                }
                field_tr.insertBefore('#' + field + '_country')


            }
        } else if (data.state == 400) {


        }
    })

}

function delayed_on_change_address_field(object, timeout) {

    var field = object.closest('table').attr('field');

    var address_field = object.closest('tr').attr('id');
    //console.log(object)
    var new_value = object.val()

    window.clearTimeout(object.data("timeout"));
    object.data("timeout", setTimeout(function() {
        on_changed_address_value(field, address_field, new_value)
    }, timeout));
}


function get_address_value(field) {
    var value = {
        "Address Recipient": null,
        "Address Organization": null,
        "Address Line 1": null,
        "Address Line 2": null,
        "Address Sorting Code": null,
        "Address Postal Code": null,
        "Address Dependent Locality": null,
        "Address Locality": null,
        "Address Administrative Area": null,
        "Address Country 2 Alpha Code": null
    }

    $('#' + field + ' input.address_input_field').each(function(i, obj) {
        var tmp = $(obj)
        if (tmp.val() != '') {
            //value[tmp.attr('field_name')] = htmlEncode(tmp.val())
            value[tmp.attr('field_name')] = tmp.val()

        }


    });
    //console.log(value)
    return JSON.stringify(value);
}



function on_changed_address_value(field, address_field, new_address_field_value) {


    var new_value = get_address_value(field);

    //console.log(new_value)
    //console.log($('#' + field + '_value').val())
    if (new_value != $('#' + field + '_value').val()) {
        $("#" + field + '_editor').addClass('changed')
        //console.log("#" + field + '_editor')
        var changed = true;




    } else {
        $("#" + field + '_editor').removeClass('changed')
        var changed = false;
    }



    $('#' + field + '_save_button').removeClass('invalid valid potentially_valid')
    $('#' + field + '_msg').removeClass('invalid valid potentially_valid')
    $("#" + field + '_validation').removeClass('invalid valid potentially_valid')


    if (changed) {


        $('#' + field + '_save_button').removeClass('fa-cloud').addClass('fa-spinner fa-spin')



        var validation = validate_address(field)


        process_validation(validation, field, true)






    } else {
        $('#' + field + '_msg').html('')
    }




}




function set_directory_item_as_main(field) {
    set_as_main($('#fields').attr('object'), $('#fields').attr('key'), field)

}

function show_directory_item_edit(field) {

    open_edit_field($('#fields').attr('object'), $('#fields').attr('key'), field)
}

function delete_directory_item(directory_field, field) {


    var request = '/ar_edit.php?tipo=delete_object_component&object=' + $('#fields').attr('object') + '&key=' + $('#fields').attr('key') + '&field=' + field

    $.getJSON(request, function(data) {


        //$('#' + field + '_save_button').addClass('fa-star').removeClass('fa-spinner fa-spin')
        if (data.state == 200) {

            if (data.directory_field != '') {
                $('#' + data.directory_field + '_directory').html(data.directory)

                if (data.items_in_directory == 0) {
                    $('#' + data.directory_field + '_field').addClass('hide')
                } else {
                    $('#' + data.directory_field + '_field').removeClass('hide')

                }
            }





/*
            if (data.other_fields) {
                for (var key in data.other_fields) {
                    update_field(data.other_fields[key])
                }
            }

            post_set_as_main(data)
*/
        } else if (data.state == 400) {


        }
    })

}

function show_sticky_note_edit_dialog(anchor) {
    console.log('==============')
    if ($('#edit_sticky_note_dialog').hasClass('hide')) {
        $('#edit_sticky_note_dialog').removeClass('hide')
        $('#sticky_note_value').focus()

        if (anchor == 'sticky_note_button') {
            var position = $('#' + anchor).position();


            $('#edit_sticky_note_dialog').css({
                'left': position.left - $('#edit_sticky_note_dialog').width(),
                'top': position.top + $('#' + anchor).height()
            })
        } else {
            var position = $('#showcase_sticky_note .sticky_note').position();
            $('#edit_sticky_note_dialog').css({
                'left': position.left,
                'top': position.top
            })
        }


    } else {

        close_sticky_note_dialog()
    }


}

function close_sticky_note_dialog() {
    $('#edit_sticky_note_dialog').addClass('hide')
}

function save_sticky_note() {


    var value = $('#sticky_note_value').val()
    var object = $('#edit_sticky_note_dialog').attr('object')
    var key = $('#edit_sticky_note_dialog').attr('key')
    var field = $('#edit_sticky_note_dialog').attr('field')

    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value)
    console.log(request)
    $.getJSON(request, function(data) {


        if (data.state == 200) {
            console.log(data)
            $('#sticky_note_value').val(data.value)
            $('#showcase_sticky_note .sticky_note').html(data.formatted_value)
            if (data.value == '') {
                $('#showcase_sticky_note').addClass('hide')
                $('#sticky_note_button').removeClass('hide')
            } else {
                $('#showcase_sticky_note').removeClass('hide')
                $('#sticky_note_button').addClass('hide')

            }

            close_sticky_note_dialog()
        } else if (data.state == 400) {


        }
    })

}

function delayed_on_change_dropdown_select_field(object, timeout) {
    var field = object.attr('id');

    var field_element = $('#' + field);
    var new_value = field_element.val()


    key_scope = {
        type: 'dropdown_select',
        field: field_element.attr('field')
    };


    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function() {

        get_dropdown_select(field, new_value)
    }, timeout));
}


function get_dropdown_select(dropdown_input, new_value) {

    var scope = $('#' + dropdown_input).attr('scope')
    var field = $('#' + dropdown_input).attr('field')
    var request = '/ar_find.php?tipo=find_object&query=' + fixedEncodeURIComponent(new_value) + '&scope=' + scope + '&state=' + JSON.stringify(state)

    $.getJSON(request, function(data) {


        if (data.number_results > 0) {
            $('#' + field + '_results_container').removeClass('hide').addClass('show')
        } else {



            $('#' + field + '_results_container').addClass('hide').removeClass('show')
            $('#' + field).val('')
            on_changed_value(field, '')
        }


        $("#" + field + "_results .result").remove();

        var first = true;

        for (var result_key in data.results) {

            var clone = $("#" + field + "_search_result_template").clone()
            clone.prop('id', field + '_result_' + result_key);
            clone.addClass('result').removeClass('hide')
            clone.attr('value', data.results[result_key].value)
            clone.attr('formatted_value', data.results[result_key].formatted_value)
            clone.attr('field', field)
            if (first) {
                clone.addClass('selected')
                first = false
            }

            clone.children(".code").html(data.results[result_key].code)

            clone.children(".label").html(data.results[result_key].description)

            $("#" + field + "_results").append(clone)


        }

    })


}


function select_dropdown_option(field, value, formatted_value) {

    $('#' + field + '_dropdown_select_label').val(formatted_value)
    $('#' + field).val(value)
    on_changed_value(field, value)

    $('#' + field + '_results_container').addClass('hide').removeClass('show')

}

/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2015 13:56:03 GMT+8 Singapoure.
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function toggle_options(field) {
    if ($('#' + field + '_options').hasClass('hide')) $('#' + field + '_options').removeClass('hide')
    else $('#' + field + '_options').addClass('hide')

}


function show_options(field) {
    $('#' + field + '_options').removeClass('hide')
}

function hide_options(field) {
    $('#' + field + '_options').addClass('hide')
}

function get_form_validation_state(submitting) {

    form_validation = 'valid';

    $(".value").each(function(index) {

        var field = $(this).attr('field')





        if ($('#' + field + '_field').hasClass('invalid')) {
            component_validation = 'invalid'
        } else if ($('#' + field + '_field').hasClass('valid')) {
            component_validation = 'valid'
        } else {
            component_validation = 'potentially_valid'
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




function process_form_validation(validation, submitting) {

    if (submitting && validation == 'potentially_valid') {
        validation = 'invalid'
    }
    $('#fields').removeClass('invalid valid potentially_valid').addClass(validation)

    //console.log(validation)
}


function check_if_form_is_valid() {



    var object = $('#fields').attr('object');
    var valid = true;
    $(".value").each(function(index) {
        var field = $(this).attr('field')
        var value = $('#' + field).val()

        //console.log(field + ' ' + $("#" + field).hasClass('valid') + ' ' + $("#" + field).hasClass('potentially_valid'))
        if (!$("#" + field + '_validation').hasClass('valid')) {


            valid = false;
        }


    });


    if (valid) {
        $('#' + object + '_save').addClass('valid').removeClass('invalid')

    } else {
        $('#' + object + '_save').removeClass('valid').addClass('invalid')

    }

}

function save_new_object(object, form_type) {

    var form_validation = get_form_validation_state()
    process_form_validation(form_validation, true)



    if ($('#fields').hasClass('valid')) {


        $('#fields').removeClass('valid').addClass('waiting')
        $('#' + object + '_save_icon').removeClass('fa-cloud');
        $('#' + object + '_save_icon').addClass('fa-spinner fa-spin');
        $('#save_label').addClass('hide')
        $('#saving_label').removeClass('hide')

        var fields_data = {};
        var re = new RegExp('_', 'g');

        var form_data = new FormData();

        $(".value").each(

        function(index) {
            var field = $(this).attr('field')
            var field_type = $(this).attr('field_type')

            if (field_type == 'time') {
                value = clean_time($('#' + field).val())
            } else if (field_type == 'password' || field_type == 'password_with_confirmation' || field_type == 'password_with_confirmation_paranoid' || field_type == 'pin' || field_type == 'pin_with_confirmation' || field_type == 'pin_with_confirmation_paranoid') {
                value = sha256_digest($('#' + field).val())
            } else if (field_type == 'attachment') {
                form_data.append("file", $('#' + field).prop("files")[0])
                value = ''
            } else {
                var value = $('#' + field).val()
            }
            //console.log($(this).attr('id') + ' ' + field)
            fields_data[field.replace(re, ' ')] = value
        });



        if (form_type == 'setup') {
            save_setup(object, fields_data)
            return;
        }

        // used only for debug
        var request = '/ar_edit.php?tipo=new_object&object=' + object + '&parent=' + $('#fields').attr('parent') + '&parent_key=' + $('#fields').attr('parent_key') + '&fields_data=' + JSON.stringify(fields_data)
        console.log(request)
        //=====
        form_data.append("tipo", (form_type != '' ? form_type : 'new_object'))
        form_data.append("object", object)
        form_data.append("parent", $('#fields').attr('parent'))
        form_data.append("parent_key", $('#fields').attr('parent_key'))
        form_data.append("fields_data", JSON.stringify(fields_data))

        var request = $.ajax({

            url: "/ar_edit.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'

        })

        request.done(function(data) {


            console.log(data)
            $('#' + object + '_save_icon').addClass('fa-cloud');
            $('#' + object + '_save_icon').removeClass('fa-spinner fa-spin');
            $('#save_label').removeClass('hide')
            $('#saving_label').addClass('hide')
            $('#fields').removeClass('waiting');
            if (data.state == 200) {
                $('#result').html(data.pcard).removeClass('hide')

                $('#fields').addClass('hide')




                for (var field in data.updated_data) {
                    $('.' + field).html(data.updated_data[field])
                }

                post_new_actions(object, data)

            } else if (data.state == 400) {
                $('#fields').addClass('error');
                $('#' + object + '_msg').html(data.msg).removeClass('hide')

            }

        })

        request.fail(function(jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)
            $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
            $('#fields').removeClass('waiting').addClass('valid')
            $('#save_label').removeClass('hide')
            $('#saving_label').addClass('hide')

            $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


        });






    }




}

function post_new_actions(object, data) {

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
        url: "/ar_edit.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'
    })

    request.done(function(data) {



        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin');

        //console.log(data)
        if (data.state == 200) {


            $('#inline_new_object_msg').html(data.msg).addClass('success')
            $('#inline_new_object').addClass('hide')
            $('#icon_' + trigger).addClass('fa-plus')
            $('#icon_' + trigger).removeClass('fa-times')
            $('#' + field).val('').attr('has_been_valid', 0)
            $('#inline_new_object').addClass('potentially_valid')


            for (var updated_fields in data.updated_data) {
                $('.' + updated_fields).html(data.updated_data[updated_fields])
            }

            rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
            rows.fetch({
                reset: true
            });




        } else if (data.state == 400) {
            $('#inline_new_object_msg').removeClass('invalid valid potentially_valid')
            $('#inline_new_object_msg').html(data.msg).addClass('invalid')
            $('#inline_new_object').addClass('invalid')


        }
    })

    request.fail(function(jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)
        $('#' + object + '_save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        $('#inline_new_object_msg').html('Server error please contact Aurora support').addClass('error')


    });


}

function toggle_inline_new_object_form(trigger) {

    var field = $('#inline_new_object').attr('field')
    var field_edit = $('#' + field + '_container').attr('field_type')

    $('#inline_new_object_msg').html('').removeClass('error success')

    if ($('#icon_' + trigger).hasClass('fa-plus')) {
        $('#inline_new_object').removeClass('hide')
        $('#icon_' + trigger).removeClass('fa-plus').addClass('fa-times')



        $('#' + field).val('')

    } else {
        $('#inline_new_object').addClass('hide')
        $('#icon_' + trigger).addClass('fa-plus').removeClass('fa-times')

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
    $('.results').addClass('hide')
    $('#' + object + '_msg').html('').addClass('hide').removeClass('success');
    $('#' + object + '_go_new').attr('request', '')
}


function update_new_address_fields(field, country_code, hide_recipient_fields, arg) {

    var request = '/ar_address.php?tipo=fields_data&country_code=' + country_code

    $.getJSON(request, function(data) {


        if (data.state == 200) {
            for (var key in data.fields) {
                var field_tr = $('#' + field + '_' + key + '_field')
                var container_tr = $('#' + field + '_' + key + '_container')
                var field_data = data.fields[key]

                field_tr.find('.label').html(field_data.label)
                console.log(field_data)
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

            $(".address_value").each(function(index) {



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


                if (field_data.attr('_required') == 1) {
                    var required = true
                } else {
                    var required = false
                }

                var validation = validate_field(field, value, type, required, server_validation, parent, parent_key, _object, key)
                //console.log(validation)
                if (arg == 'init') {

                    if (validation.class == 'invalid' && value == '') {
                        validation.class = 'potentially_valid'
                    }
                }

                $('#' + field + '_field').removeClass('invalid potentially_valid valid').addClass(validation.class)



            });



        } else if (data.state == 400) {


        }
    })

}

/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 March 2016 at 18:20:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

$(document).ready(function() {

    $('body').on('click', '.question', function() {


        show_answer($(this))
    });


})


function help() {

    var request = "/ar_help.php?tipo=help&state=" + JSON.stringify(state)

    $.getJSON(request, function(data) {

        if (typeof(data.title) != "undefined" && data.title !== null && data.title != '') {

            $('#help  .help_title').html(data.title).removeClass('hide');
        } else {
            $('#help  .help_title').html('').addClass('hide');
        }

        if (typeof(data.content) != "undefined" && data.content !== null && data.content != '') {

            $('#help  .content').html(data.content).removeClass('hide');
        } else {
            $('#help  .content').html('').addClass('hide');
        }





    })



}


function show_answer(element) {


    if ($(element).next().hasClass('hide')) {
        $('#help .answer').addClass('hide')

$(element).find('.fa-caret-right.bullet').removeClass('fa-caret-right').addClass('fa-caret-down')
        $(element).next().removeClass('hide')
    } else {
        $('#help .answer').addClass('hide')

$(element).find('.fa-caret-down.bullet').removeClass('fa-caret-down').addClass('fa-caret-right')


    }


}


function show_help() {

    if ($('#help').hasClass('hide')) {
        var value = 1
        $('#help').removeClass('hide')
        $('#help_button').addClass('selected')
        help()

    } else {
        var value = 0
        $('#help').addClass('hide')
        $('#help_button').removeClass('selected')

    }

    var request = "/ar_help.php?tipo=show_help&value=" + value

    $.getJSON(request, function(data) {})



}
