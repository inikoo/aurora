/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 August 2018 at 21:02:12 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


function confirm_item(transaction_key) {

    var request = '/ar_agents_edit.php?tipo=confirm_item&key=' + transaction_key
    $.getJSON(request, function (data) {
        common_post_agent_order_operation(data)
    })

}

function mark_as_received(transaction_key) {

    var request = '/ar_agents_edit.php?tipo=mark_as_received&key=' + transaction_key
    $.getJSON(request, function (data) {
        common_post_agent_order_operation(data)
    })

}


function unmark_as_received(transaction_key) {

    var request = '/ar_agents_edit.php?tipo=unmark_as_received&key=' + transaction_key
    $.getJSON(request, function (data) {
        common_post_agent_order_operation(data)
    })

}

function undo_mark_as_received(transaction_key) {

    var request = '/ar_agents_edit.php?tipo=undo_mark_as_received&key=' + transaction_key
    $.getJSON(request, function (data) {
        common_post_agent_order_operation(data)
    })

}


function unconfirm_item(transaction_key) {


    var request = '/ar_agents_edit.php?tipo=unconfirm_item&key=' + transaction_key
    $.getJSON(request, function (data) {
        common_post_agent_order_operation(data)
    })

}

function common_post_agent_order_operation(data) {

    for (var key in data.updated_metadata.class_html) {
        console.log('.' + key)
        $('.' + key).html(data.updated_metadata.class_html[key])
    }


    for (var key in data.updated_metadata.hide) {
        $('#' + data.updated_metadata.hide[key]).addClass('hide')
    }
    for (var key in data.updated_metadata.show) {
        $('#' + data.updated_metadata.show[key]).removeClass('hide')
    }

}

function select_item_problem_bis(element) {
    select_item_problem($(element).closest('tr').find('i'))
}

function select_item_problem(element) {

    var tr = $(element).closest('tr')




    if (tr.hasClass('super_discreet')) {
        return;
    }

    var table = $(element).closest('table');

    if ($(element).hasClass('fa-square')) {
        $(element).removeClass('fa-square').addClass('fa-check-square')





        switch ($(element).data('type')) {

            case 'price_increase':

                table.find('.problem_discontinued').addClass('super_discreet').find('i').removeClass('fa-check-square').addClass('fa-square')
                tr.find('input').removeClass('invisible')
                break;
            case 'low_stock':

                table.find('.problem_discontinued').addClass('super_discreet').find('i').removeClass('fa-check-square').addClass('fa-square')
                table.find('.problem_long_wait').addClass('super_discreet').find('i').removeClass('fa-check-square').addClass('fa-square')
                table.find('.problem_min_order').addClass('super_discreet').find('i').removeClass('fa-check-square').addClass('fa-square')


                tr.find('input').removeClass('invisible')
                break;
            case 'long_wait':

                table.find('.problem_discontinued').addClass('super_discreet').find('i').removeClass('fa-check-square').addClass('fa-square')
                table.find('.problem_low_stock').addClass('super_discreet').find('i').removeClass('fa-check-square').addClass('fa-square')
                table.find('.problem_min_order').addClass('super_discreet').find('i').removeClass('fa-check-square').addClass('fa-square')

                tr.find('input').removeClass('invisible')
                break;
            case 'min_order':

                table.find('.problem_discontinued').addClass('super_discreet').find('i').removeClass('fa-check-square').addClass('fa-square')
                table.find('.problem_low_stock').addClass('super_discreet').find('i').removeClass('fa-check-square').addClass('fa-square')
                table.find('.problem_long_wait').addClass('super_discreet').find('i').removeClass('fa-check-square').addClass('fa-square')

                tr.find('input').removeClass('invisible')
                break;
            case 'discontinued':

                $('tr.problem', table).each(function (i, obj) {
                    if (!$(obj).is(tr)) {
                        $(obj).addClass('super_discreet')
                        $(obj).find('i').removeClass('fa-check-square').addClass('fa-square')
                    }


                });


            default:
        }


    } else {
        $(element).removeClass('fa-check-square').addClass('fa-square')


        switch ($(element).data('type')) {
            case 'discontinued':
                table.find('tr').removeClass('super_discreet')
                break;
            case 'price_increase':

                tr.find('input').addClass('invisible')
                break;
            case 'low_stock':

                tr.find('input').addClass('invisible')
                table.find('.problem_long_wait').removeClass('super_discreet')
                table.find('.problem_min_order').removeClass('super_discreet')

                break;
            case 'long_wait':

                tr.find('input').addClass('invisible')
                table.find('.problem_low_stock').removeClass('super_discreet')
                table.find('.problem_min_order').removeClass('super_discreet')

                break;
            case 'min_order':

                tr.find('input').addClass('invisible')
                table.find('.problem_low_stock').removeClass('super_discreet')
                table.find('.problem_long_wait').removeClass('super_discreet')

                break;

            default:
        }

        var any_checked = false;
        $('tr.problem', table).each(function (i, obj) {
            if ($(obj).find('i').hasClass('fa-check-square')) {
                console.log()
                any_checked = true;
            }
        });


        if (!any_checked) {
            table.find('.problem_discontinued').removeClass('super_discreet')

        }


    }

    validate_item_problem_changes()

}


function save_item_problems() {

    var table = $('#item_problems_dialog').find('table');


    var ajaxData = new FormData();


    ajaxData.append("tipo", 'update_item_problems')
    ajaxData.append("value", get_item_problem_metadata())
    ajaxData.append("transaction_key", table.data('transaction_key'))


    $.ajax({
        url: "/ar_agents_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {

            if (data.state == '200') {


                $('#item_problems_dialog').addClass('hide')
                common_post_agent_order_operation(data)

            } else if (data.state == '400') {
                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }


        }, error: function () {

        }
    });

}

function log_problem_with_supplier_item(element) {

    var dialog = $('#item_problems_dialog')

    var table = dialog.find('table')

    table.data('transaction_key', $(element).data('transaction_key'))
    table.data('original_metadata', $(element).data('metadata'))





    var metadata=$(element).data('metadata').item_problems



    table.find('.item_problems_title').html($(element).data('title'))
    dialog.removeClass('hide').offset({
        'top': $(element).closest('tr').offset().top + 25, 'left': $(element).offset().left - dialog.width() + 20
    })


    table.find('i.checkbox').removeClass('fa-check-square').addClass('fa-square')
    table.find('input').val('').addClass('invisible')
    table.find('tr').removeClass('super_discreet')


    table.find('.note').val('')


    for (var problem in metadata.problems){
        if(metadata.problems[problem].selected){

            select_item_problem( $('#item_problems_dialog .problem_'+problem+' i'))

            $('#item_problems_dialog .problem_'+problem+' input').val(metadata.problems[problem].note)
        }

    }
    table.find('.note').val(metadata.note)


}

function get_item_problem_metadata() {


    var table = $('#item_problems_dialog').find('table');


    var data = {}

    var number_problems = 0;

    $('tr.problem', table).each(function (i, obj) {

        var icon = $(obj).find('i')
        data[icon.data('type')] = {}

        if (icon.hasClass('fa-check-square')) {
            data[icon.data('type')]['selected'] = true;
            number_problems++;
        } else {
            data[icon.data('type')]['selected'] = false;
        }
        if ($(obj).find('input').length) {
            data[icon.data('type')]['note'] = $(obj).find('input').val();
        } else {
            data[icon.data('type')]['note'] = '';
        }


    });

    var problems_data = {}

    problems_data.problems = data
    problems_data.note = table.find('.note').val()
    problems_data.number_problem = number_problems
    return JSON.stringify(problems_data)
}

function validate_item_problem_changes() {

    var new_metadata = get_item_problem_metadata()

    var table = $('#item_problems_dialog').find('table');


    if (table.data('metadata') != new_metadata) {
        console.log(new_metadata)
        table.find('.save').addClass('changed valid')
    } else {
        table.find('.save').removeClass('changed valid')
    }


}