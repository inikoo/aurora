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


<div class="wrapper_boxed">

    <div class="site_wrapper">

        <div class="content_fullwidth less2">

            <div class="container">



                <div class="login_form">

                    <form id="sky-form" class="sky-form">

                        <header id="_title" contenteditable="true">{$content._title}</header>



                        <fieldset>

                            <section>

                                <div class="row">

                                    <label class="label col col-4" id="_email_label" contenteditable="true">{$content._email_label}</label>

                                    <div class="col col-8">

                                        <label class="input">

                                            <i class="icon-append icon-user"></i>

                                            <input type="email" name="email">

                                        </label>

                                    </div>

                                </div>

                            </section>



                            <section>

                                <div class="row">

                                    <label class="label col col-4" id="_password_label" contenteditable="true">{$content._password_label}</label>

                                    <div class="col col-8">

                                        <label class="input">

                                            <i class="icon-append icon-lock"></i>

                                            <input type="password" name="password">

                                        </label>

                                        <div class="note"><a href=""  id="_forgot_password_label" contenteditable="true">{$content._forgot_password_label}</a></div>

                                    </div>

                                </div>

                            </section>



                            <section>

                                <div class="row">

                                    <div class="col col-4"></div>

                                    <div class="col col-8">

                                        <label class="checkbox"><input type="checkbox" name="remember" checked><i></i></label> <span id="_keep_logged_in_label" style="margin-left:22px;top:-3px" class="fake_form_checkbox" contenteditable="true">{$content._keep_logged_in_label}</span>

                                    </div>

                                </div>

                            </section>

                        </fieldset>

                        <footer>

                            <div class="fright">

                                <a href="register.html" class="button button-secondary" id="_register_label" contenteditable="true">{$content._register_label}</a>

                                <button type="submit" class="button" id="_log_in_label" contenteditable="true">{$content._log_in_label}</button>

                            </div>



                        </footer>

                    </form>

                </div>



                <form action="demo-recovery.php" id="sky-form2" class="sky-form sky-form-modal">

                    <header>Password recovery</header>



                    <fieldset>

                        <section>

                            <label class="label">E-mail</label>

                            <label class="input">

                                <i class="icon-append icon-user"></i>

                                <input type="email" name="email" id="email">

                            </label>

                        </section>

                    </fieldset>



                    <footer>

                        <button type="submit" name="submit" class="button">Submit</button>

                        <a href="#" class="button button-secondary modal-closer">Close</a>

                    </footer>



                    <div class="message">

                        <i class="icon-ok"></i>

                        <p>Your request successfully sent!<br><a href="#" class="modal-closer">Close window</a></p>

                    </div>

                </form>





            </div>

        </div><!-- end content area -->


        <div class="clearfix marb12"></div>
        <div class="clearfix marb12"></div>

    </div>

</div>
<script>

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


        var request = '/ar_edit_website.php?tipo=save_webpage_content&key={$webpage->id}&content_data=' + encodeURIComponent(btoa(JSON.stringify(content_data)));


        console.log(request)


        $.getJSON(request, function (data) {


            $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

        })


    }

    $(document).delegate('a', 'click', function (e) {

        return false
    })


    $("form").on('submit', function (e) {

        e.preventDefault();
        e.returnValue = false;

// do things
    });


    function show_edit_input(element) {

        console.log($(element).attr('id'))

        offset = $(element).closest('section').offset();
        $('#input_editor').removeClass('hide').offset({
            top: offset.top, left: offset.left - 40}).attr('element_id', $(element).attr('id'));
        $('#input_editor_placeholder').val($(element).next('input').attr('placeholder'))
        $('#input_editor_tooltip').val($(element).closest('section').find('b').html())


    }

    function save_edit_input() {
        $('#input_editor').addClass('hide')

        var element = $('#' + $('#input_editor').attr('element_id'))
        element.next('input').attr('placeholder', $('#input_editor_placeholder').val())
        element.closest('section').find('b').html($('#input_editor_tooltip').val())

        console.log($('#input_editor').attr('id'))

        $('#save_button', window.parent.document).addClass('save button changed valid')


    }

</script>

</body>

</html>

