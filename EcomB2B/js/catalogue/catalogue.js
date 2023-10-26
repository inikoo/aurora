/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  08 March 2020  12:28::43  +0800, Kuala Lumpur  Malaysia
 Copyright (c) 2020, Inikoo
 Version 3.0*/


$(function () {

    $(document).on('click', '.catalogue_tabs .tab', function () {

        var parent=  $('.catalogue_table').data('parent');

        $('.catalogue_table').data('scope', $(this).data('scope'));
        $('.catalogue_tabs .tab').removeClass('selected')
        $(this).addClass('selected')

        const request_data = {"tipo": 'catalogue', "parent": parent, "parent_key": $('.catalogue_table').data('parent_key'), "scope": $('.catalogue_table').data('scope')}
        $.ajax({

            url: $(this).closest('div.catalogue_tabs').data('ar_url'), type: 'GET', dataType: 'json', data: request_data, success: function (data) {
                if (data.state == 200) {

                    state = data.app_state;

                    $('.portfolio_data_feeds .images_zip').attr('href', data.images_zip_url);
                    $('.portfolio_data_feeds .csv').attr('href', data.csv_url);
                    $('.portfolio_data_feeds .xls').attr('href', data.xls_url);
                    $('.portfolio_data_feeds .json').attr('href', data.json_url);

                    $('.portfolio_data_feeds').removeClass('hide')

                    $('#table_container').html(data.html)

                    $('.table_top .title').html(data.title)
                }

            }
        });

    });
})

function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

var scope = getUrlParameter('scope');
var parent = getUrlParameter('parent');
var parent_key = getUrlParameter('parent_key');



if (parent === 'department' && isNumeric(parent_key) && parent_key > 0) {
    $('.catalogue_tabs .departments').addClass('hide');
    $('.catalogue_tabs .families').removeClass('hide');
    $('.catalogue_tabs .products').removeClass('hide');

    if (scope === 'families') {

    } else {
        scope = 'products';

    }
    $('.breadcrumbs .arrows_3').addClass('hide')



} else if (parent === 'family' && isNumeric(parent_key) && parent_key > 0) {
    $('.catalogue_tabs .departments').addClass('hide')
    $('.catalogue_tabs .families').addClass('hide')
    $('.catalogue_tabs .products').removeClass('hide')

    scope = 'products';

   // $('.breadcrumbs .arrows_3').addClass('hide')


} else {
    parent = 'store';
    if(parent_key==undefined){
        parent_key = '';
    }

    $('.catalogue_tabs .departments').removeClass('hide')
    $('.catalogue_tabs .families').removeClass('hide')
    $('.catalogue_tabs .products').removeClass('hide')

    if (scope === 'products') {

    } else if (scope === 'families') {

    } else {
        scope = 'departments';
    }

    $('.breadcrumbs .arrows_2').addClass('hide')
    $('.breadcrumbs .arrows_3').addClass('hide')


}
$('.catalogue_tabs span').removeClass('selected')
$('.catalogue_tabs .' + scope).addClass('selected')


$('.catalogue_table').data('scope', scope).data('parent', parent).data('parent_key', parent_key)

