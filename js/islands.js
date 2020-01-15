/*Author: Raul Perusquia <raul@inikoo.com>
 Created:   14 January 2020  15:04::34  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/




$(function () {


    $('#tab').on('click', '.islands_container .date_chooser > div', function () {


        if($(this).hasClass('selected')){
            return;
        }

        $('.date_chooser > div').removeClass('selected');


        $(this).addClass('selected')

        const ar_file = $('.islands_container').data('ar')

        const period= $(this).data('period')

        $('.islands_container .islands table').each(function (i, obj) {


            const args={ period: period,tipo:$(obj).attr('id'), args:$(obj).data('args')}

            $.getJSON('/'+ar_file, {
                args


            }, function (data) {

                $(obj).find('tbody.res').html(data.html)

            });

        });


    })


});