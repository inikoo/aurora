/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  1 March 2018 at 14:32:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/




function toggle_list_subscription(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-toggle-on')){
        icon.removeClass('fa-toggle-on').addClass('fa-toggle-off').next('span').addClass('discreet')

    }else if(icon.hasClass('fa-toggle-off')){
        icon.removeClass('fa-toggle-off').addClass('fa-toggle-on').next('span').removeClass('discreet')
    }

}



function toggle_list_customer_status(element){

    var icon=$(element).find('i')



    if(icon.hasClass('fa-check-square-o')){

        if( $("#Customer_Status_container i.fa-check-square-o").length<5){
            return
        }


        icon.removeClass('fa-check-square-o').addClass('fa-square-o').next('span').addClass('discreet')

    }else if(icon.hasClass('fa-square-o')){
        icon.removeClass('fa-square-o').addClass('fa-check-square-o').next('span').removeClass('discreet')
    }

}




function toggle_list_with(element){

    var icon=$(element).find('i')

    if(icon.hasClass('fa-random')){

        icon.removeClass('fa-random').addClass('fa-toggle-on')

    }else if(icon.hasClass('fa-toggle-on')){
        icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')

    }else if(icon.hasClass('fa-toggle-off')){

        icon.removeClass('fa-toggle-off').addClass('fa-random')
    }

}



