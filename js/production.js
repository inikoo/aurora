/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 1:22 pm Wednesday, 17 June 2020 (MYT), Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function job_order_item_action(element){

    const row=$(element).closest('tr')
    const cell=$(element).closest('td')

    cell.find('.action_container').addClass('hide')
    cell.find('.follow_on').removeClass('hide')

    const action=row.find('.delivery_quantity_item_container')
    action.removeClass('invisible')
    validate_job_order_item_action(action)
}


function validate_job_order_item_action(element){

    const save_icon=element.find('.save')
    const input=element.find('input')
    const qty=input.val()

    if($.isNumeric(qty)  && qty>0  ){
        save_icon.addClass('valid').removeClass('invalid')

    }else{
        save_icon.removeClass('valid').addClass('invalid')

    }



}


function save_job_order_action(element){

    const row=$(element).closest('tr')
    const cell=$(element).closest('td')


    const action_container_trigger=row.find('.action_container_trigger')
    const input=cell.find('input')


    $(element).addClass('fa-spin fa-spinner')


    var ajaxData = new FormData();

    ajaxData.append("tipo", 'update_job_order_item');
    ajaxData.append("key", action_container_trigger.data('key'));
    ajaxData.append("action", action_container_trigger.data('action'));
    ajaxData.append("qty", input.val());
    ajaxData.append("qty_type", input.data('qty_type'));


    $.ajax({
        url: '/ar_edit_production.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

        complete: function () {

        }, success: function (data) {
            save.removeClass('fa-spin fa-spinner wait')




        }, error: function () {
            save.removeClass('fa-spin fa-spinner wait')
        }
    });



}