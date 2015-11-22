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

    $('#date_chooser div').removeClass('selected')
    $('#' + period).addClass('selected')

    var parameters = jQuery.parseJSON(rows.parameters);
    parameters.period = period;

    rows.parameters = JSON.stringify(parameters)

    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
    rows.fetch({
        reset: true
    });
    if(with_elements)
    get_elements_numbers(rows.tab, rows.parameters)

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


