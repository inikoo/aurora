/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  22 February 2019 at 18:28:09 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo
 Version 3.0*/





function set_voucher_code_as_auto(){

    var icon=$('#Deal_Voucher_Auto_Code_container').find('i')
    icon.removeClass('fa-toggle-off').addClass('fa-toggle-on')

    $('#Deal_Voucher_Auto_Code_field').removeClass('hide')
    $('#Deal_Voucher_Code_field').addClass('hide')


    // var form_validation = get_form_validation_state()
    // process_form_validation(form_validation)
    new_object_init();
}



function toggle_voucher_auto_code(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-toggle-on')){
        icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')

        $('#Deal_Voucher_Auto_Code_field').addClass('hide')
        $('#Deal_Voucher_Code_field').removeClass('hide')
        $('#Deal_Voucher_Code').removeClass('hide')

        update_field({field:'Deal_Voucher_Code',render:true,required:true})

    }
    new_object_init();
   // var form_validation = get_form_validation_state()
   // process_form_validation(form_validation)

}



function toggle_voucher_deal_type(element){

    if(!$(element).hasClass('selected')){


        $('#Type_field').addClass('change valid')

        update_field({field:'Type',render:true,required:true})


        $(element).closest('.button_radio_options').find('.button').removeClass('selected')
        $(element).addClass('selected')
        //console.log($(element).attr('id'))

        $('#Percentage_container').removeClass('value')
        $('#Amount_Off_container').removeClass('value')
        $('#Get_Item_Free_Product_container').removeClass('value')
        $('#Get_Item_Free_Quantity_container').removeClass('value')


        if ($(element).attr('id') == 'Deal_Type_Percentage_Off_field') {

            $('#Percentage').removeClass('hide')
            $('#Percentage_container').addClass('value')

            update_field({field:'Percentage',render:true,required:true})
            update_field({field:'Amount_Off',render:false,required:false})
            update_field({field:'Get_Item_Free_Quantity',render:false,required:false})
            update_field({field:'Get_Item_Free_Product',render:false,required:false})





        } else if ($(element).attr('id') == 'Deal_Type_Amount_Off_field') {

            $('#Amount_Off_container').addClass('value')
            $('#Amount_Off').removeClass('hide')
            update_field({field:'Percentage',render:false,required:false})
            update_field({field:'Amount_Off',render:true,required:true})
            update_field({field:'Get_Item_Free_Quantity',render:false,required:false})
            update_field({field:'Get_Item_Free_Product',render:false,required:false})


        }else if ($(element).attr('id') == 'Deal_Type_Get_Item_Free_field') {

            $('#Get_Item_Free_Product_container').addClass('value')
            $('#Get_Item_Free_Quantity_container').addClass('value')

            $('#Get_Item_Free_Product_dropdown_select_label').removeClass('hide')
            $('#Get_Item_Free_Quantity').removeClass('hide')
            update_field({field:'Percentage',render:false,required:false})
            update_field({field:'Amount_Off',render:false,required:false})
            update_field({field:'Get_Item_Free_Product',render:true,required:true})
            update_field({field:'Get_Item_Free_Quantity',render:true,required:true})

        }else{
            update_field({field:'Percentage',render:false,required:false})
            update_field({field:'Amount_Off',render:false,required:false})
            update_field({field:'Get_Item_Free_Product',render:false,required:false})
            update_field({field:'Get_Item_Free_Quantity',render:false,required:false})
        }


        new_object_init();



    }

    $('#Deal_controls').removeClass('hide')


}