/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  25-05-2019 22:05:14 CEST Sheffield, UK
 Copyright (c) 2019, Inikoo
 Version 3.0*/





function toggle_deal_store_deal_type(element){

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