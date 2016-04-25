/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  22 April 2016 at 15:51:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo
 Version 3.0*/

function post_update_related_fields(country_data) {


if ($('#Supplier_Products_Origin_Country_Code').attr('has_been_changed') == 0) {
    $('#Supplier_Products_Origin_Country_Code').countrySelect("selectCountry", country_data.iso2);
    }
   // console.log(country_data)
  if ($('#Supplier_Default_Currency_Code').attr('has_been_changed') == 0) {
  
    $('#Supplier_Default_Currency_Code').countrySelect("selectCountryfromCode", country_data.currency);
}
}
