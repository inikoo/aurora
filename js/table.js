/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 18:17:38 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo
 Version 3.0*/



var HtmlCell = Backgrid.HtmlCell = Backgrid.Cell.extend({

    /** @property */
    className: "html-cell",

    initialize: function () {
        Backgrid.Cell.prototype.initialize.apply(this, arguments);
    },

    render: function () {
        this.$el.empty();


        var rawValue = this.model.get(this.column.get("name"));
        var formattedValue = this.formatter.fromRaw(rawValue, this.model);
        this.$el.append(formattedValue);
        this.delegateEvents();
        return this;
    }
});

var HeaderHtmlCell = Backgrid.HeaderCell.extend({

    /** @property */
    className: "html-cell",

    render: function () {



        this.column.set("label",'')
        this.constructor.__super__.render.apply(this, arguments);

        if (this.column.get('title')) {
            this.$el.attr('title', this.column.get('title'))

        }
        this.$el.find('button').html(this.column.get("html_label")+' '+this.$el.find('button').html())

       // this.$el.html(this.column.get("html_label")+' '+this.$el.html())
        return this;
    }




});

var rightHeaderHtmlCell = Backgrid.HeaderCell.extend({

    /** @property */
    className: "html-cell align-right",


    render: function () {

        this.constructor.__super__.render.apply(this, arguments);
        this.$el.addClass('align-right');

        if (this.column.get('title')) {
            this.$el.attr('title', this.column.get('title'))

        }
        if (this.column.get('html_label')) {
            this.$el.find('button').html(this.column.get("html_label") + ' ' + this.$el.find('button').html())
        }
        // this.$el.html(this.column.get("html_label")+' '+this.$el.html())
        return this;



    }
});


var integerHeaderCell = Backgrid.HeaderCell.extend({
    className: "align-right",


    render: function () {

        this.constructor.__super__.render.apply(this, arguments);
        this.$el.addClass('align-right');
        return this;
    }
});





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

    var parameters = JSON.parse(rows.parameters);

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
    $.getJSON(request, function (data) {
        if (data.state == 200) {
            for (element in data.elements_numbers) {

                for (item in data.elements_numbers[element]) {
                   //  console.log(item)
                    $("#element_qty_" + item).html('(' + data.elements_numbers[element][item] + ')')


                }
            }

            if(data.class_html!=undefined) {

                for (var key in data.class_html) {
                    $('.' + key).html(data.class_html[key])
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

function close_columns_frequency_options() {
    var icon = $('#columns_frequency .fa')

    icon.addClass('fa-bars')
    icon.removeClass('fa-chevron-up')
    $('#columns_frequency_chooser').addClass('hide')

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
            top: $('#columns_period').position().top + height, left: offset.left - $('#columns_period_chooser').width() + icon.width() + 20
        })


    }

}

function show_columns_frequency_options() {

    var button = $('#columns_frequency')
    var icon = $('#columns_frequency .fa')
    if (!icon.hasClass('fa-bars')) {

        close_columns_frequency_options()
    } else {
        icon.removeClass('fa-bars')
        icon.addClass('fa-chevron-up')

        var offset = $('#columns_frequency .fa').position();
        var height = button.height();

        console.log($('#columns_frequency').position().top + height)
        console.log(offset.left - $('#columns_frequency_chooser').width() + icon.width() + 20)

        $('#columns_frequency_chooser').removeClass('hide').offset({
            top: $('#columns_frequency').position().top + height, left: offset.left - $('#columns_frequency_chooser').width() + icon.width() + 20
        })


    }

}

function show_f_options(element) {

    if (!$('#filter_field').hasClass('button')) return

    if ($('#f_options_chooser').hasClass('hide')) {

        var offset = $('#filter_field').position();
        var height = $('#filter_field').height();

        $('#f_options_chooser').removeClass('hide').offset({
            top: offset.top + height, left: offset.left - 27.2
        })

    } else {
        $('#f_options_chooser').addClass('hide')
    }


}

function change_f_option(option) {

    if (!$(option).hasClass('selected')) {
        $('#f_options_chooser div').removeClass('selected')
        $('#f_options_chooser div i').removeClass('fa-circle').addClass('fa-circle')
        $(option).addClass('selected')
        $(option).find('i').addClass('fa-circle')
        $('#filter').attr('f_field', $(option).attr('f_field')).find('input').val('')


        $('#filter_field .label').html($(option).find('.label').html())

        rows.fetch({
            reset: true
        });
        show_f_options()

    }
}


function show_elements_types() {

    const button = $('#element_type')
    const icon = $('#element_type .fa')

    if (icon.hasClass('fa-bars')) {
        $('#f_options_chooser').addClass('hide')
        icon.removeClass('fa-bars').addClass('fa-chevron-up')

        const offset = button.position();
        const height = button.height();

        $('#elements_chooser').removeClass('hide').offset({
            top: offset.top + height, left: offset.left
        })


    } else {
        icon.addClass('fa-bars').removeClass('fa-chevron-up')

        $('#elements_chooser').addClass('hide')

    }


}

function change_columns_frequency(frequency, frequency_label) {

    $('#columns_frequency span.label').html(frequency_label)


    var parameters = JSON.parse(rows.parameters);
    parameters.frequency = frequency;

    rows.parameters = JSON.stringify(parameters)

    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
    rows.fetch({
        reset: true
    });

    close_columns_frequency_options()
    $('#columns_frequency_chooser div').removeClass('selected')
    $('#columns_frequency_chooser .fa').removeClass('fa-circle').addClass('fa-circle')
    $('#element_group_option_' + frequency).addClass('selected')
    $('#element_group_option_' + frequency + ' .fa').removeClass('fa-circle').addClass('fa-circle')

}

function change_columns_period(period, period_label) {

    $('#columns_period span.label').html(period_label)


    var parameters = JSON.parse(rows.parameters);
    parameters.f_period = period;

    rows.parameters = JSON.stringify(parameters)

    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
    rows.fetch({
        reset: true
    });

    close_columns_period_options()
    $('#columns_period_chooser div').removeClass('selected')
    $('#columns_period_chooser .fa').removeClass('fa-circle').addClass('fa-circle')
    $('#element_group_option_' + period).addClass('selected')
    $('#element_group_option_' + period + ' .fa').removeClass('fa-circle').addClass('fa-circle')

}

function change_elements_type(elements_type) {

    $('#elements .elements_group').addClass('hide')

    $("#elements_group_" + elements_type).removeClass('hide')


    $('#elements_chooser  div').removeClass('selected')

    $('#element_group_option_' + elements_type).addClass('selected')


    $('#elements_chooser  i').removeClass('fa').addClass('far')

    $('#element_group_option_' + elements_type + ' i').addClass('fa')
    $('#element_group_option_' + elements_type + ' i').removeClass('far')


    $('#element_type .fa').addClass('fa-bars').removeClass('fa-chevron-up')


    $('#elements_chooser').addClass('hide')


    var parameters = JSON.parse(rows.parameters);
    parameters.elements_type = elements_type;

    rows.parameters = JSON.stringify(parameters)

    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
    rows.fetch({
        reset: true
    });


}

var element_delay = 300, element_clicks = 0, element_timer = null;


$(document).on("click",'#elements .element', function(e){

    element_clicks++;  //count clicks

    var item= $(this).data('item')

    if(element_clicks === 1) {

        element_timer = setTimeout(function() {
            change_table_element('click',item)
            element_clicks = 0;             //after action performed, reset counter

        }, element_delay);

    } else {


        clearTimeout(element_timer);    //prevent single-click action
        change_table_element('double_click', item)
        element_clicks = 0;             //after action performed, reset counter
    }

})
    .on("dblclick", function(e){
        e.preventDefault();  //cancel system double-click event
    });


function change_table_element(event, item) {



    if (event=='double_click') {


        $('#elements i.element_checkbox').removeClass('fa-check-square').addClass('fa-square')
        $('#elements .element').removeClass('selected')

        $("#element_" + item).addClass('selected')
        $("#element_checkbox_" + item).removeClass('fa-square')
        $("#element_checkbox_" + item).addClass('fa-check-square')

    } else {



        if ($("#element_" + item).hasClass('selected')) {
            $("#element_" + item).removeClass('selected')
            $("#element_checkbox_" + item).removeClass('fa-check-square')
            $("#element_checkbox_" + item).addClass('fa-square')
        } else {

            $("#element_" + item).addClass('selected')

            $("#element_checkbox_" + item).removeClass('fa-square')
            $("#element_checkbox_" + item).addClass('fa-check-square')
        }
    }


    var parameters = JSON.parse(rows.parameters);
    $("#elements_group_" + parameters.elements_type + " .element").each(function (index) {
        //console.log($(this).attr('item_key') + ": " + $(this).hasClass('selected'));
        parameters['elements'][parameters.elements_type]['items'][$(this).attr('item_key')]['selected'] = $(this).hasClass('selected')

        // alert(parameters['elements'][rows.parameters.elements_type])
    });


    rows.parameters = JSON.stringify(parameters)

    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters

    rows.fetch({
        reset: true
    });


    post_table_rendered()

}


function show_export_dialog_left_button_expanded(element) {
    show_export_dialog_left_button(element)

    open_export_config_left_button($(element).closest('div').next('.export_dialog_container').find('.export_dialog_config'))


}

function show_export_dialog_left_button(element) {


    var export_dialog =$(element).closest('div').next('.export_dialog_container').find('.export_dialog')


    if (export_dialog.hasClass('hide')) {
        export_dialog.removeClass('hide')
        export_dialog.css('top', $(element).closest('.square_button').height());
    } else {
        hide_export_dialog(export_dialog)
    }
}


function show_download_edit_items_dialog(element) {



    var export_dialog =$(element).closest('div.edit_table_dialog').next('.export_dialog_container').find('.export_dialog')
    var export_dialog_config =$(element).closest('div.edit_table_dialog').next('.export_dialog_container').find('.export_dialog_config')

    if (export_dialog.hasClass('hide')) {
        export_dialog.removeClass('hide')
        export_dialog_config.removeClass('hide')

        export_dialog.css('top', $(element).closest('.square_button').height());



        export_dialog.offset({ top:  $('#edit_table_dialog').offset().top,
            left:  $('#edit_table_dialog').offset().left + $('#edit_table_dialog').width()-export_dialog.width()  })

        export_dialog_config.offset({ top:  $('#edit_table_dialog').offset().top, left:  export_dialog.offset().left -export_dialog_config.width() -30    })



    } else {
        hide_export_dialog(export_dialog)
    }



}


function hide_export_dialog(export_dialog) {

    $(export_dialog).addClass('hide')



    hide_export_config_dialog($(export_dialog).closest('.export_dialog_container').find('.export_dialog_config'))




    $(export_dialog).find('.export_download').addClass('hide').attr('title', '').on( 'click',function () { })
    $(export_dialog).find('.export_progress_bar_bg').addClass('hide').html('')
    $(export_dialog).find('.export_progress_bar').css('width', '0px').removeClass('hide').attr('title', '').html('')
    $(export_dialog).find('.stop_export').addClass('hide')
    $(export_dialog).find('.close_export').addClass('hide')


}





function show_edit_table_dialog() {


    if ($('#edit_table_dialog').hasClass('hide')) {
        $('#edit_table_dialog').removeClass('hide')


        var button=$('#show_edit_table_dialog_button').closest('div')

        $("#edit_table_dialog").offset({
            top: button.offset().top+button.height(),
            left:  $('#show_edit_table_dialog_button').offset().left-$('#edit_table_dialog').width()+20 })


    } else {
        $('#edit_table_dialog').addClass('hide')
    }
}


function table_edit_view(){
    $('#table .table_item_editable').attr('contenteditable',true).addClass('editing')
   $('#inline_edit_table_items_buttons').removeClass('hide')
    $('#show_edit_table_dialog_button').addClass('hide')

    $('.table_item_editable.editing').each(function(i, obj) {

        $(obj).attr('ovalue',$(obj).html())

    });



    show_edit_table_dialog()


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

function open_export_config_left_button(export_dialog_config) {

    const export_dialog=$(export_dialog_config).closest('.export_dialog_container').find('.export_dialog')


    if ($(export_dialog_config).hasClass('hide')) {


        export_dialog.find('.export_button').addClass('hide')


        $(export_dialog_config).removeClass('hide')



        $(export_dialog_config).css('left',export_dialog.outerWidth(true)-1);
        $(export_dialog_config).css('top', export_dialog.position().top);
    } else {
        hide_export_config_dialog(export_dialog_config)

    }
}

function hide_export_config_dialog(export_dialog_config) {
    $(export_dialog_config).addClass('hide')
    const export_dialog=$(export_dialog_config).closest('.export_dialog_container').find('.export_dialog')

    export_dialog.find('.export_button').removeClass('hide')


}


function toggle_all_export_fields(element) {

    var container=$(element).closest('table')

    if ($(element).hasClass('fa-check-square')) {
        $(element).removeClass('fa-check-square')
        $(element).addClass('fa-square')




        container.find('.export_fields i').removeClass('fa-check-square')
        container.find('.export_fields i').addClass('fa-square')


    } else {
        $(element).addClass('fa-check-square')
        $(element).removeClass('fa-square')
        container.find('.export_fields i').addClass('fa-check-square')
        container.find('.export_fields i').removeClass('fa-square')
    }


}

function toggle_export_field(element) {




    field_element=$(element)

    if (field_element.hasClass('fa-check-square')) {


       field_element.removeClass('fa-check-square')
        field_element.addClass('fa-square')

    } else {
        console.log('unckeced')


        field_element.addClass('fa-check-square')
        field_element.removeClass('fa-square')
    }
}

function export_table(element) {


    var type=$(element).data('type')

    var export_container=$(element).closest('.export_dialog_container')
    $(element).removeClass('link').addClass('disabled')
    export_container.find('.field_export').removeClass('button').addClass('disabled')
    export_container.find('.stop_export').removeClass('hide').data('stop', 0);



    var fields = []
    export_container.find('.field_export i').each(function (index, obj) {
        if ($(obj).hasClass('fa-check-square')) fields.push($(obj).attr('key'))
    });

    var request = $(element).data('ar_url')+"?ar_file=" + rows.ar_file + "&tipo=" + rows.tipo + "&parameters=" + rows.parameters + '&type=' + type + '&state=' + JSON.stringify(state) + '&fields=' + JSON.stringify(fields)

    $.getJSON(request, function (data) {
        if (data.state == 200) {
            export_container.find('.export_progress_bar_bg').removeClass('hide').html('&nbsp;' + data.txt)
            export_container.attr('id','download_'+data.download_key).data('download_key',data.download_key)
        }
    })

}







function get_editable_data(element) {


    var type=$(element).data('type')

    _data=$(element).data('data')



    var export_container=$(element).closest('.export_dialog_container')
    $(element).removeClass('link').addClass('disabled')
    export_container.find('.field_export').removeClass('button').addClass('disabled')
    export_container.find('.stop_export').removeClass('hide').data('stop', 0);

    var fields = []
    export_container.find('.export_fields .field_export i').each(function (index, obj) {
        if ($(obj).hasClass('fa-check-square')) fields.push($(obj).attr('key'))
    });



    var metadata=$('#edit_table_dialog').data('metadata')



    var request = "/ar_export_edit_template.php?parent="+_data.parent+"&parent_key="+_data.parent_key+"&parent_code="+_data.parent_code+"&objects="+_data.object+"&fields=" + JSON.stringify(fields) + '&type=' + type + '&metadata=' + JSON.stringify(metadata)



    $.getJSON(request, function (data) {
        if (data.state == 200) {
            export_container.find('.export_progress_bar_bg').removeClass('hide').html('&nbsp;' + data.txt)
            export_container.closest('.export_dialog_container').attr('id','download_'+data.download_key)
        }
    })




}




function change_export_as(element,type){

    $(element).closest('.export_type_options').find('span').addClass('very_discreet')
    $(element).removeClass('very_discreet')

    var export_button= $(element).closest('.export_dialog_container').find('.export_button')
    export_button.data('type',type)


    if(type=='Excel'){
        export_button.find('i').addClass('fa-file-excel').removeClass('fa-table')
        export_button.find('span.excel').removeClass('hide')
        export_button.find('span.csv').addClass('hide')

    }else{
        export_button.find('i').removeClass('fa-file-excel').addClass('fa-table')
        export_button.find('span.excel').addClass('hide')
        export_button.find('span.csv').removeClass('hide')

    }

}


function stop_export(element) {

    const export_dialog =$(element).closest('.export_dialog_container').find('.export_dialog')

    $(element).data('stop', 1);
    var request = "/ar_stop_export.php?tipo=stop&download_key="+$(element).closest('.export_dialog_container').data('download_key')

    $.getJSON(request, function (data) {
        if (data.state == 200) {
            hide_export_dialog(export_dialog)
        }
    })



}

function download_exported_file(element) {

    var export_container=$(element).closest('.export_dialog_container')

    export_container.find('.download_export')[0].click();




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

    $.getJSON(request, function (data) {

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

function post_table_rendered(el) {


    switch (state.tab) {

        case 'order.input_picking_sheet':
            validate_data_entry_picking_aid()
            break;
        case 'location.parts':
            $('.table_edit_cell').awesomeCursor('pencil', {
                color:   'rgba(0, 0, 0, 0.5)',
                hotspot: 'bottom left'
            })
            break;
        case 'intrastat':

            get_intrastat_totals();
            break;
        case 'intrastat_orders':

            get_intrastat_orders_totals();
            break;
        case 'intrastat_products':

            get_intrastat_products_totals();
            break;
        case 'intrastat_imports':

            get_intrastat_imports_totals();
            break;
        case 'intrastat_deliveries':

            get_intrastat_deliveries_totals();
            break;
        case 'intrastat_parts':

            get_intrastat_parts_totals();
            break;
        case 'ec_sales_list':

            get_ec_sales_list_totals();
            break;
        case 'store.payment_accounts':

            console.log(el)
            // $('#nav_title').
            break;
        case 'refund.new.items':


            $('.new_refund_item').each(function (i, obj) {
                if ($(obj).val() != ''   ) {


                    var error;


                    if($(obj).val()!='' && validate_number($(obj).val(),0,$(obj).attr('max'))){
                        $(obj).addClass('error')
                        error=true;
                    }else{
                        $(this).removeClass('error')
                        error=false;
                    }

                    var    feedback_element    = $(obj).closest('tr').find('.set_otf_feedback_button').removeClass('hide')

                    if( $(obj).val()>0 && !error){
                        feedback_element.removeClass('hide')
                    }else{
                        feedback_element.addClass('hide')
                    }

                    update_new_refund_totals()


                }

            });

            break;


    }

}

function export_omega_invoices(){

    window.open('/invoices.omega.txt.php', '_blank')
}
