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
                    // console.log(item)
                    $("#element_qty_" + item).html('(' + data.elements_numbers[element][item] + ')')


                }
            }
        }
    })

}



function close_columns_period_options() {
    var icon = $('#columns_period .fa')

    icon.addClass('fa-bars')
    icon.removeClass('fa-chevron-up')
    $('#columns_period_chooser').addClass('hide')

}


function show_columns_period_options() {




    var button = $('#columns_period')
    var icon = $('#columns_period .fa')

    if (!icon.hasClass('fa-bars')) {

        close_columns_period_options()
    } else {
        icon.removeClass('fa-bars')
        icon.addClass('fa-chevron-up')

        var offset = $('#columns_period .fa').position();
        var height = button.height();

        //  console.log(icon.width())
        $('#columns_period_chooser').removeClass('hide').offset({
            top: $('#columns_period').position().top + height,
            left: offset.left - $('#columns_period_chooser').width() + icon.width() + 20
        })




    }

}

function show_f_options(element) {

    if (!$('#filter_field').hasClass('button')) return

    if ($('#f_options_chooser').hasClass('hide')) {

        var offset = $('#filter_field').position();
        var height = $('#filter_field').height();

        $('#f_options_chooser').removeClass('hide').offset({
            top: offset.top + height,
            left: offset.left - 27.2
        })

    } else {
        $('#f_options_chooser').addClass('hide')
    }


}

function change_f_option(option) {

    if (!$(option).hasClass('selected')) {
        $('#f_options_chooser div').removeClass('selected')
        $('#f_options_chooser div i').removeClass('fa-circle').addClass('fa-circle-o')
        $(option).addClass('selected')
        $(option).find('i').addClass('fa-circle')
        $('#filter').attr('f_field',$(option).attr('f_field')).find('input').val('')
        
        
        $('#filter_field .label').html( $(option).find('.label').html())
        
        rows.fetch({
                    reset: true
                });
        show_f_options()
        
    }
}


function show_elements_types() {

    var button = $('#element_type')
    var icon = $('#element_type .fa')
    if (icon.hasClass('fa-bars')) {
      $('#f_options_chooser').addClass('hide')
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


function change_columns_period(period, period_label) {

    $('#columns_period span.label').html(period_label)



    var parameters = jQuery.parseJSON(rows.parameters);
    parameters.f_period = period;

    rows.parameters = JSON.stringify(parameters)

    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
    rows.fetch({
        reset: true
    });

    close_columns_period_options()
    $('#columns_period_chooser div').removeClass('selected')
    $('#columns_period_chooser .fa').removeClass('fa-circle').addClass('fa-circle-o')
    $('#element_group_option_' + period).addClass('selected')
    $('#element_group_option_' + period + ' .fa').removeClass('fa-circle-o').addClass('fa-circle')

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



function open_export_config() {
    if ($('#export_dialog_config').hasClass('hide')) {
        $('#export_dialog_config').removeClass('hide')
        $("#export_dialog_config").css('left', -1 * ($("#export_dialog_config").width() + 40 + $("#export_dialog").width()));
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


function edit_category_subject(element) {

    if ($(element).hasClass('wait')) return;

    if ($(element).hasClass('fa-unlink')) {
        $(element).removeClass('fa-unlink very_discreet').addClass('fa-link wait')
        operation = 'link';
    } else {
        $(element).addClass('fa-unlink very_discreet wait').removeClass('fa-link')
        operation = 'unlink';
    }

    var request = '/ar_edit.php?tipo=edit_category_subject&category_key=' + state.key + '&subject_key=' + $(element).attr('key') + '&operation=' + operation

    $.getJSON(request, function(data) {

        $(element).removeClass('wait')

        if (data.state == 200) {


        } else if (data.state == 400) {
            if (operation == 'link') {
                $(element).addClass('fa-unlink very_discreet wait').removeClass('fa-link')
            } else {
                $(element).removeClass('fa-unlink very_discreet').addClass('fa-link wait')

            }

        }


    })



}
