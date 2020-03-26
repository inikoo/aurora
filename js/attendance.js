/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  26 March 2020  16:03::37  +0800 Kuala Lumpur,  Malaysia
 Copyright (c) 2020, Inikoo
 Version 3.0*/




$(function () {


    $('#tab').on('click', '#attendance_container .attendance_button', function () {

        if($(this).hasClass('wait')){
            return;
        }

        let icon=$(this).find('i')



        $('#attendance_container .attendance_button').addClass('wait')

        icon.addClass('fa-spin fa-spinner')



        var ajaxData = new FormData();



        ajaxData.append("tipo", $(this).data('type'))
        ajaxData.append("source", $(this).data('source'))
        ajaxData.append("staff_key",  $('#attendance_container').data('staff_key'))


        $.ajax({
            url: 'ar_edit_attendance.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

            complete: function () {

            }, success: function (data) {

                $('#attendance_container .attendance_button').removeClass('wait')



                if(data.state==200){


                    $('.attendance_button').addClass('hide')




                    switch (data.attendance_status) {
                        case 'Off':
                        case 'Finish':
                            $('.attendance_button.WorkHome').removeClass('hide')
                            $('.attendance_button.WorkOutside').removeClass('hide')

                            break;
                        case 'Home':
                        case 'Outside':
                            $('.attendance_button.BreakOut').removeClass('hide')
                            $('.attendance_button.Checkout').removeClass('hide')

                            break;
                        case 'Break':
                            $('.attendance_button.BreakIn').removeClass('hide')
                            $('.attendance_button.Checkout').removeClass('hide')

                            break;



                    }

                    rows.fetch({
                        reset: true
                    });

                }


            }, error: function () {
                $('#attendance_container .attendance_button').removeClass('wait')

            }
        });

    });




})