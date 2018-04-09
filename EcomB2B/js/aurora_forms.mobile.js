/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 April 2018 at 20:55:34 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/




$(function() {



    $("form").on('submit', function (e) {

        e.preventDefault();
        e.returnValue = false;

    });

   
   $('.modal-opener').on('click', function()
    {
        if( !$('#sky-form-modal-overlay').length )
        {
            $('body').append('<div id="sky-form-modal-overlay" class="sky-form-modal-overlay"></div>');
        }

        $('#sky-form-modal-overlay').on('click', function()
        {
            $('#sky-form-modal-overlay').fadeOut();
            $('.sky-form-modal').fadeOut();
        });

        form = $($(this).attr('href'));
        $('#sky-form-modal-overlay').fadeIn();

        $('#page-transitions').addClass('hide')

        form.css('top', '50%').css('left', '50%').css('margin-top', -form.outerHeight()/2).css('margin-left', -form.outerWidth()/2).fadeIn();

        return false;
    });

    $('.modal-closer').on('click', function()
    {

        $('#page-transitions').removeClass('hide')
        $('#sky-form-modal-overlay').fadeOut();
        $('.sky-form-modal').fadeOut();

        return false;
    });



});

