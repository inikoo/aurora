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


<div id="handle_input_editor" class="editor hide" style="z-index:100;position:absolute;padding:10px;border:1px solid #ccc;background-color: #fff;width:560px">
<table style="width:100%;">
    <tr>
        <td style="width:150px"><i class="fa fa-exclamation-triangle warning" aria-hidden="true"></i> {t}Missing email{/t}
        </td>
        <td><input id="validation_handle_missing" value="{if isset($labels.validation_handle_missing) and $labels.validation_handle_missing!=''}{$labels.validation_handle_missing}{else}{t}Please enter your registered email address{/t}{/if}" style="width:100%"/>
        </td>

    </tr>
    <tr>
        <td ><i class="fa fa-exclamation-triangle warning" aria-hidden="true"></i> {t}Invalid email{/t}
        </td>
        <td><input id="validation_email_invalid" value="{if isset($labels.validation_email_invalid) and $labels.validation_email_invalid!=''}{$labels.validation_email_invalid}{else}{t}Please enter a valid email address{/t}{/if}" style="width:100%"/>
        </td>

    </tr>
    <tr>
        <td></td>
        <td style="padding-right:10px;text-align: right"><span style="cursor:pointer" onclick="save_input_editor(this)" ><i class="fa fa-check "></i>&nbsp; {t}Ok{/t}</span>
        </td>
    </tr>
</table>
</div>


<div id="password_input_editor" class="editor hide" style="z-index:100;position:absolute;padding:10px;border:1px solid #ccc;background-color: #fff;width:560px">
    <table style="width:100%;">
        <tr>
            <td ><i class="fa fa-exclamation-triangle warning" aria-hidden="true"></i> {t}Missing password{/t}
            </td>
            <td><input id="validation_password_missing" value="{if isset($labels.validation_password_missing) and $labels.validation_password_missing!=''}{$labels.validation_password_missing}{else}{t}Please enter your password"{/t}{/if}" style="width:100%"/>
            </td>

        </tr>
      
        <tr>
            <td></td>
            <td style="padding-right:10px;text-align: right"><span style="cursor:pointer" onclick="save_input_editor(this)" ><i class="fa fa-check "></i>&nbsp; {t}Ok{/t}</span>
            </td>
        </tr>
    </table>
</div>

<div class="wrapper_boxed">

    <div class="site_wrapper">

        <div class="content_fullwidth less2">

            <div class="container">



                <div id="login_form_container" class="login_form" style="position:relative">

                     <div class="like_button " style="color:#333;position: absolute;left:470px;top:200px;width: 200px;" onclick="show_password_recovery()" >
                        <i class="fa fa-language " style="margin-right: 5px;" aria-hidden="true"></i>   {t}forgot password{/t}
                    </div>

                    <form id="sky-form" class="sky-form">

                        <header id="_title" contenteditable="true">{$content._title}</header>



                        <fieldset>

                            <section>

                                <div class="row">

                                    <label class="label col col-4" id="_email_label" contenteditable="true">{$content._email_label}</label>

                                    <div class="col col-8">

                                        <label class="input" editor="handle_input_editor" style="cursor:pointer" onclick="show_edit_input(this)">

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

                                        <label class="input" editor="password_input_editor" style="cursor:pointer" onclick="show_edit_input(this)">

                                            <i class="icon-append icon-lock"></i>

                                            <input type="password" name="password">

                                        </label>

                                        <div ><span href=""  id="_forgot_password_label" contenteditable="true">{$content._forgot_password_label}</span>
                                        </div>

                                    </div>

                                </div>

                            </section>



                            <section class="hide">

                                <div class="row">

                                    <div class="col col-4"></div>

                                    <div class="col col-8">

                                        <label class="checkbox"><input type="checkbox" name="remember" checked><i></i></label> <span id="_keep_logged_in_label" style="margin-left:22px;top:-22px" class="fake_form_checkbox" contenteditable="true">{$content._keep_logged_in_label}</span>

                                    </div>

                                </div>

                            </section>

                        </fieldset>

                        <footer>

                            <div class="fright">
                                <button type="submit" class="button" id="_log_in_label" contenteditable="true">{$content._log_in_label}</button>


                                <a href="register.html" class="button button-secondary" id="_register_label" contenteditable="true">{$content._register_label}</a>


                            </div>



                        </footer>

                    </form>

                </div>


                <div id="recovery_form_container" class="login_form hide" style="position:relative">


                    <div id="show_login_form" class="like_button" style="position:absolute;left:470px;width:300px;top:0px" onclick="hide_password_recovery()">
                        <span class=" " style="color:#333"  >
                        <i class="fa fa-language " style="margin-right: 5px" aria-hidden="true"></i>   {t}login form{/t}
                        </span><br>



                    </div>

                    <div id="show_password_recovery" class="like_button hide" style="position:absolute;left:470px;width:300px;top:0px" onclick="show_password_recovery()">
                        <span class=" " style="color:#333"  >
                        <i class="fa fa-language " style="margin-right: 5px" aria-hidden="true"></i>   {t}forgot password{/t}
                        </span><br>



                    </div>


                    <div style="position:absolute;left:470px;width:300px;top:160px">
                        <span class=" " style="color:#333"  >
                        <i class="fa fa-language " style="margin-right: 5px" aria-hidden="true"></i>   {t}Messages{/t}
                        </span><br>


                        <div style="font-size: 80%">
                        <span class="like_button " style="color:#333" onclick="show_password_recovery_success()" >
                       <i class="fa fa-check  ok" aria-hidden="true"></i>  {t}Recovery email send{/t}
                        </span><br>
                            <span class="like_button " style="color:#333" onclick="show_password_recovery_email_not_register_error()" >
                         <i class="fa fa-exclamation-triangle error" aria-hidden="true"></i> {t}Email not registered{/t}
                        </span><br>
                            <span class="like_button " style="color:#333" onclick="show_password_recovery_unknown_error()" >
                          <i class="fa fa-exclamation-triangle error" aria-hidden="true"></i> {t}Unknown error{/t}
                        </span>
                        </div>
                    </div>


                <form  id="password_recovery_form" style="display:block"  class="sky-form ">


                    <header id="_title_recovery" contenteditable="true">{$content._title_recovery}</header>


                    <fieldset>

                        <section>

                            <label class="label " id="_email_recovery_label" contenteditable="true">{$content._email_recovery_label}</label>

                            <label class="input">

                                <i class="icon-append icon-user"></i>

                                <input type="email" name="email" id="email">

                            </label>

                        </section>

                    </fieldset>



                    <footer>

                        <button type="submit" class="button" id="_submit_label" contenteditable="true">{$content._submit_label}</button>

                        <a href="#" id="_close_label" class="button button-secondary modal-closer" contenteditable="true">{$content._close_label}</a>



                    </footer>



                    <div class="message" >



                        <i class="fa fa-check"></i>


                        <span class="password_recovery_msg " id="_password_recovery_success_msg"  contenteditable="true">{$content._password_recovery_success_msg}</span>
                        <span class="password_recovery_msg error" id="_password_recovery_email_not_register_error_msg"  contenteditable="true">{$content._password_recovery_email_not_register_error_msg}</span>
                        <span class="password_recovery_msg error" id="_password_recovery_unknown_error_msg"  contenteditable="true">{$content._password_recovery_unknown_error_msg}</span>

                        <br>
                            <a id="_password_recovery_go_back" class="marked_link" contenteditable="true">{$content._password_recovery_go_back}</a>


                    </div>



                </form>

                </div>



            </div>

        </div><!-- end content area -->


        <div class="clearfix marb12"></div>
        <div class="clearfix marb12"></div>

    </div>

</div>
<script>


    document.addEventListener("paste", function(e) {
        // cancel paste
        e.preventDefault();

        // get text representation of clipboard
        var text = e.clipboardData.getData("text/plain");

        // insert text manually
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

