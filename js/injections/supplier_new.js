/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  22 April 2016 at 15:51:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/


function post_process_new_supplier_form_validation(){


    $('.Supplier_minimum_order_amount_currency').html( $('#Supplier_Default_Currency_Code').countrySelect("getSelectedCountryData").code)





}