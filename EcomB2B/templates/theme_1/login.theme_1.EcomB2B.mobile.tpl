{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 18:45:19 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.mobile.tpl"}
<body>{include file="analytics.tpl"}
{include file="analytics.tpl"}
<div id="page-transitions">
    {include file="theme_1/header.theme_1.EcomB2B.mobile.tpl"}
    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->

            <div id="login_form_container" class="login_form" >
                <form action="" id="login_form" class="sky-form">
                    <header>{$content._title}</header>

                    <fieldset>
                        <section>
                            <div class="row">
                                <label class="label col col-4">{$content._email_label}</label>
                                <div class="col col-8">
                                    <label class="input">
                                        <i class="icon-append icon-user"></i>
                                        <input id="handle" type="email" name="email">
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-4">{$content._password_label}</label>
                                <div class="col col-8">
                                    <label class="input">
                                        <i class="icon-append icon-lock"></i>
                                        <input id="pwd" type="password" name="password">
                                    </label>
                                    <div><span id="open_recovery" class="like_link">{$content._forgot_password_label}</span></div>
                                </div>
                            </div>
                        </section >

                        <section class="hide">
                            <div class="row">
                                <div class="col col-4"></div>
                                <div class="col col-8">
                                    <label class="checkbox"><input id="keep_logged" type="checkbox" name="remember" ><i></i>{$content._keep_logged_in_label}</label>
                                </div>
                            </div>
                        </section>
                    </fieldset>
                    <footer>
                        <button id="login_button" type="submit" class="button">{$content._log_in_label}  <i  class="fa fa-fw  fa-arrow-right" aria-hidden="true"></i> </button>
                        <a href="/register.sys" class="button button-secondary">{$content._register_label}</a>
                    </footer>
                </form>

            </div>
            <div id="recovery_form_container" class="login_form hide" >
                <form action="" id="password_recovery_form" class="sky-form "  >
                    <header>{$content._title_recovery}</header>

                    <fieldset>
                        <section>
                            <label class="label"{$content._email_recovery_label}</label>
                            <label class="input">
                                <i class="icon-append fa fa-envelope"></i>
                                <input type="email" name="email" id="recovery_email">
                            </label>
                        </section>
                    </fieldset>

                    <footer>
                        <button id="recovery_button" type="submit" name="submit" class="button">{$content._submit_label} <i  class="fa fa-fw  fa-arrow-right" aria-hidden="true"></i> </button>
                        <button id="close_recovery" class="button button-secondary modal-closer">{$content._close_label}</button>
                    </footer>

                    <div class="message" >
                        <i class="fa fa-check"></i>
                        <span class="password_recovery_msg hide" id="password_recovery_success_msg"  >{$content._password_recovery_success_msg}</span>
                        <span class="password_recovery_msg error hide" id="password_recovery_email_not_register_error_msg"  >{$content._password_recovery_email_not_register_error_msg}</span>
                        <span class="password_recovery_msg error hide" id="password_recovery_unknown_error_msg" >{$content._password_recovery_unknown_error_msg}</span>

                        <br>
                        <a href="login"  class="modal-closer" id="password_recovery_go_back" >{$content._password_recovery_go_back}</a>


                    </div>
                </form>
            </div>

            <div class="decoration decoration-margins"></div>
            {include file="theme_1/footer.theme_1.EcomB2B.mobile.tpl"}
        </div>
    </div>

    <a href="#" class="back-to-top-badge"><i class="fas fa-arrow-circle-up"></i></a>

    <div class="share-bottom share-light">
        <h3>Share Page</h3>
        <div class="share-socials-bottom">
            <a href="https://www.facebook.com/sharer/sharer.php?u=http://www.themeforest.net/">
                <i class="ion-social-facebook facebook-bg"></i>
                Facebook
            </a>
            <a href="https://twitter.com/home?status=Check%20out%20ThemeForest%20http://www.themeforest.net">
                <i class="ion-social-twitter twitter-bg"></i>
                Twitter
            </a>
            <a href="https://plus.google.com/share?url=http://www.themeforest.net">
                <i class="ion-social-googleplus google-bg"></i>
                Google
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=http://www.themeforest.net/&media=https://0.s3.envato.com/files/63790821/profile-image.jpg&description=Themes%20and%20Templates">
                <i class="ion-social-pinterest-outline pinterest-bg"></i>
                Pinterest
            </a>
            <a href="sms:">
                <i class="ion-ios-chatboxes-outline sms-bg"></i>
                Text
            </a>
            <a href="mailto:?&subject=Check this page out!&body=http://www.themeforest.net">
                <i class="ion-ios-email-outline mail-bg"></i>
                Email
            </a>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>



<script>

    {if $display=='forgot_password'}
    open_recovery()
    {/if}



    console.log('x')

    $('#open_recovery').on('click', function (e) {


        open_recovery()

    });

    function open_recovery() {
        $('#login_form_container').addClass('hide')
        $('#recovery_form_container').removeClass('hide')
        $('#recovery_email').val($('#handle').val())

    }

    $('#password_recovery_go_back').on('click', function (e) {

        e.preventDefault();
        $("#password_recovery_form").removeClass('submited')





    });






    $('#close_recovery').on('click', function (e) {

        $('#login_form_container').removeClass('hide')
        $('#recovery_form_container').addClass('hide')

    });




    $("#login_form").validate({

            submitHandler: function(form)
            {





                var button=$('#login_button');

                if(button.hasClass('wait')){
                    return;
                }

                button.addClass('wait')
                button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')




                var ajaxData = new FormData();

                ajaxData.append("tipo", 'login')
                ajaxData.append("website_key", '{$website->id}')
                ajaxData.append("handle", $('#handle').val())
                ajaxData.append("pwd", sha256_digest($('#pwd').val()))
                ajaxData.append("keep_logged", $('#keep_logged').is(':checked'))




                $.ajax({
                    url: "/ar_web_login.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    }, success: function (data) {







                        if (data.state == '200') {



                                {if $redirect==''}
                                window.location.replace("index.php");

                                {else}
                                window.location.replace("{$redirect}");

                                {/if}



                        } else if (data.state == '400') {
                            swal("{t}Error{/t}!", data.msg, "error")
                        }


                        button.removeClass('wait')
                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                    }, error: function () {
                        button.removeClass('wait')
                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin f')

                    }
                });

            },

            // Rules for form validation
            rules:
                {

                    email:
                        {
                            required: true,
                            email: true,


                        },
                    password:
                        {
                            required: true,



                        }


                },

            // Messages for form validation
            messages:
                {

                    email:
                        {
                            required: '{if empty($labels._validation_handle_missing)}{t}Please enter your registered email address{/t}{else}{$labels._validation_handle_missing|escape}{/if}',
                        email: '{if empty($labels._validation_email_invalid)}{t}Please enter a valid email address{/t}{else}{$labels._validation_email_invalid|escape}{/if}',
                        },
                    password:
                        {
                            required: '{if empty($labels._validation_password_missing)}{t}Please enter your password{/t}{else}{$labels._validation_password_missing|escape}{/if}',

                        }



                },

            // Do not change code below
            errorPlacement: function(error, element)
            {
                error.insertAfter(element.parent());
            }
        });

    $("#password_recovery_form").validate({

        submitHandler: function(form)
        {


            if($('#recovery_button').hasClass('wait')){
                return;
            }

            $('#recovery_button').addClass('wait')
            $('#recovery_button i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')


            var ajaxData = new FormData();

            ajaxData.append("tipo", 'recover_password')
            ajaxData.append("website_key", '{$website->id}')
            ajaxData.append("webpage_key", '{$webpage->id}')

            ajaxData.append("recovery_email", $('#recovery_email').val())



            $.ajax({
                url: "/ar_web_recover_password.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                complete: function () {
                }, success: function (data) {



                    $("#password_recovery_form").addClass('submited')

                    if (data.state == '200') {


                        $('.password_recovery_msg').addClass('hide')
                        $('#password_recovery_success_msg').removeClass('hide').prev('i').addClass('fa-check').removeClass('error fa-exclamation')
                        $('#password_recovery_form').find('.message').removeClass('error')
                        $('#password_recovery_go_back').addClass('hide')

                    } else if (data.state == '400') {

                        console.log('#password_recovery_'+data.error_code+'_error_msg')

                        $('.password_recovery_msg').addClass('hide').prev('i').removeClass('fa-check').addClass('error fa-exclamation')
                        $('#password_recovery_'+data.error_code+'_error_msg').removeClass('hide')
                        $('#password_recovery_form').find('.message').addClass('error')
                        $('#password_recovery_go_back').removeClass('hide')
                    }

                    $('#recovery_button').removeClass('wait')
                    $('#recovery_button i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')


                }, error: function () {

                    $('#recovery_button').removeClass('wait')
                    $('#recovery_button i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                }
            });


        },

        // Rules for form validation
        rules:
            {

                email:
                    {
                        required: true,
                        email: true,


                    }


            },

        // Messages for form validation
        messages:
            {

                email:
                    {
                        required: '{if empty($labels._validation_handle_missing)}{t}Please enter your registered email address{/t}{else}{$labels._validation_handle_missing}{/if}',
                        email: '{if empty($labels._validation_email_invalid)}{t}Please enter a valid email address{/t}{else}{$labels._validation_email_invalid}{/if}',
                    }



            },

        // Do not change code below
        errorPlacement: function(error, element)
        {
            error.insertAfter(element.parent());
        }
    });




</script>

</body>

</html>

