/*Author: Sasi
 Created:  22 March 2019 at 12:16:20 GMT+8 Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo
 Version 3.0*/

$('ul#theme_options_ul li').on("click", function () {
    $('body').removeClass().addClass($('ul#theme_options_ul li.selected').data("value"));
});