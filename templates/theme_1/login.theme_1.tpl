{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2017 at 01:35:43 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


{include file="theme_1/_head.theme_1.tpl"}


<body>

<style>
    [contenteditable="true"]:empty {
        background-color: #faa9a9;
    }
</style>





<script>


    document.addEventListener("paste", function(e) {
        e.preventDefault();
        var text = e.clipboardData.getData("text/plain");
        document.execCommand("insertHTML", false, text);
    });


    $('[contenteditable=true]').on('input paste', function (event) {
        $('#save_button', window.parent.document).addClass('save button changed valid')
    });


    function save() {

        if (!$('#save_button', window.parent.document).hasClass('save')) {
            return;
        }

        $('#save_button', window.parent.document).find('i').addClass('fa-spinner fa-spin')


        content_data = { };

        $('[contenteditable=true]').each(function (i, obj) {
            content_data[$(obj).attr('id')] = $(obj).html()
        })


        $('.register_field').each(function (i, obj) {
            content_data[$(obj).attr('id')] = $(obj).attr('placeholder')
        })


        $('.tooltip').each(function (i, obj) {
            content_data[$(obj).attr('id')] = $(obj).html()
        })


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'save_webpage_content')
        ajaxData.append("key", '{$webpage->id}')
        ajaxData.append("content_data", JSON.stringify(content_data))


        $.ajax({
            url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {

                if (data.state == '200') {

                    $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "OK"
                    });
                }



            }, error: function () {

            }
        });





    }

    $(document).delegate('a', 'click', function (e) {

        return false
    })


    $("form").on('submit', function (e) {
        e.preventDefault();
        e.returnValue = false;
    });

    function show_edit_input(element) {


        offset = $(element).closest('section').offset();
        $('#'+$(element).attr('editor')).removeClass('hide').offset({
            top: offset.top, left: offset.left - 105})

         }

    function save_input_editor(element) {
        $(element).closest('.editor').addClass('hide')

        $('#save_button', window.parent.document).addClass('save button changed valid')
    }



    function show_password_recovery_success(){

        $('#show_login_form').addClass('hide')
        $('#show_password_recovery').removeClass('hide')

        $('#password_recovery_go_back').addClass('hide')
        $('.password_recovery_msg').addClass('hide')
        $('#_password_recovery_success_msg').removeClass('hide').prev('i').addClass('fa-check').removeClass('error fa-exclamation')
        $('#password_recovery_form').addClass('submited')
        $('#password_recovery_form').find('.message').removeClass('error')

    }

    function show_password_recovery_email_not_register_error(){

        $('#show_login_form').addClass('hide')
        $('#show_password_recovery').removeClass('hide')

        $('#password_recovery_go_back').removeClass('hide')
        $('.password_recovery_msg').addClass('hide').prev('i').removeClass('fa-check').addClass('error fa-exclamation')
        $('#_password_recovery_email_not_register_error_msg').removeClass('hide')

        $('#password_recovery_form').addClass('submited ')
        $('#password_recovery_form').find('.message').addClass('error')


    }

    function show_password_recovery_unknown_error(){

        $('#show_login_form').addClass('hide')
        $('#show_password_recovery').removeClass('hide')

        $('#password_recovery_go_back').removeClass('hide')

        $('.password_recovery_msg').addClass('hide')
        $('#_password_recovery_unknown_error_msg').removeClass('hide').prev('i').removeClass('fa-check').addClass('error fa-exclamation')
        $('#password_recovery_form').addClass('submited ')
        $('#password_recovery_form').find('.message').addClass('error')

    }


    function  show_password_recovery(){
        $('#show_login_form').removeClass('hide')
        $('#show_password_recovery').addClass('hide')


        $('#login_form_container').addClass('hide')
        $('#recovery_form_container').removeClass('hide')
        $('#password_recovery_form').removeClass('submited ')

    }

    function hide_password_recovery(){
        $('#login_form_container').removeClass('hide')
        $('#recovery_form_container').addClass('hide')

    }


</script>

</body>

</html>

