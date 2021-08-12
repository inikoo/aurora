/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 11 Aug 2021 21:31:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


$(document).on('click', "#product_type_options .product_type_option", function () {

    let container=$(this).closest('div');
    let icon = $(this).find('i');

    if(icon.hasClass('fa-dot-circle')){
        return;
    }

    $(container).find('i').removeClass('fa-dot-circle').addClass('fa-circle button discreet_on_hover');
    icon.addClass('fa-dot-circle').removeClass('button discreet_on_hover');

    if($(this).data('value')==='Product'){
        $('.product_field').removeClass('hide');
        $('.service_field').addClass('hide');
        $('.product_field .new_field_container ').removeClass('skip');
        $('.service_field .new_field_container ').addClass('skip');
    }else{
        $('.product_field').addClass('hide');
        $('.service_field').removeClass('hide');

        $('.product_field .new_field_container ').addClass('skip');
        $('.service_field .new_field_container ').removeClass('skip');


    }


    $('#Product_Type').val($(this).data('value'));


});