{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2017 at 12:06:26 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


{include file="theme_1/_head.theme_1.tpl"}


<body>

<div id="password_input_editor" class="editor hide" style="z-index:100;position:absolute;padding:10px;border:1px solid #ccc;background-color: #fff;width:560px">
    <table style="width:100%;">

        <tr>
            <td>{t}Placeholder{/t}
            </td>
            <td><input id="_password_placeholder" class="label_field input_editor_placeholder" value="{$content._password_placeholder}" style="width:100%"/>
            </td>

        </tr>
        <tr>
            <td>{t}Tooltip{/t}
            </td>
            <td><input id="_password_tooltip" class="label_field input_editor_tooltip"  value="{$content._password_tooltip}" style="width:100%"/>
            </td>

        </tr>


        <tr>
            <td class="error"><i class="fa fa-exclamation-triangle " aria-hidden="true"></i> {t}Missing password{/t}
            </td>
            <td><input id="validation_password_missing"  class="website_localized_label"
                       value="{if isset($labels.validation_new_password_missing) and $labels.validation_new_password_missing!=''}{$labels.validation_new_password_missing}{else}{t}Please enter your new password"{/t}{/if}"
                style="width:100%"/>
            </td>

        </tr>
        <tr>
            <td class="error"><i class="fa fa-exclamation-triangle " aria-hidden="true"></i> {t}Password min length{/t}
            </td>
            <td><input id="validation_minlength_password" class="website_localized_label"
                       value="{if isset($labels.validation_minlength_password) and $labels.validation_minlength_password!=''}{$labels.validation_minlength_password}{else}{t}Enter at least 8 characters"{/t}{/if}"
                style="width:100%"/>
            </td>

        </tr>
        <tr>
            <td></td>
            <td style="padding-right:10px;text-align: right"><span style="cursor:pointer" onclick="close_input_editor(this)"><i class="fa fa-window-close "></i>&nbsp</span>
            </td>
        </tr>
    </table>
</div>

<div id="password_confirm_input_editor" class="editor hide" style="z-index:100;position:absolute;padding:10px;border:1px solid #ccc;background-color: #fff;width:560px">
    <table style="width:100%;">

        <tr>
            <td>{t}Placeholder{/t}
            </td>
            <td><input  class="label_field input_editor_placeholder"  id="_password_confirm_placeholder" value="{$content._password_confirm_placeholder}" style="width:100%"/>
            </td>

        </tr>
        <tr>
            <td>{t}Tooltip{/t}
            </td>
            <td><input class="label_field input_editor_tooltip"  id="_password_confirm_tooltip" value="{$content._password_confirm_tooltip}" style="width:100%"/>
            </td>

        </tr>


        <tr>
            <td class="error"><i class="fa fa-exclamation-triangle " aria-hidden="true"></i> {t}Same password{/t}
            </td>
            <td><input id="validation_same_password" class="website_localized_label"
                       value="{if isset($labels.validation_same_password) and $labels.validation_same_password!=''}{$labels.validation_same_password}{else}{t}Enter the same password as above"{/t}{/if}"
                style="width:100%"/>
            </td>

        </tr>

        <tr>
            <td></td>
            <td style="padding-right:10px;text-align: right"><span style="cursor:pointer" onclick="close_input_editor(this)"><i class="fa fa-window-close "></i>&nbsp</span>
            </td>
        </tr>
    </table>
</div>

<div class="wrapper_boxed">

    <div class="site_wrapper">
        <div class="clearfix marb12"></div>


        <div class="container">


            <div class="password_reset_form" style="position: relative">

                <div id="show_password_reset_form" class="like_button hide" style="position:absolute;left:470px;width:300px;top:0px" onclick="show_password_reset_form()">
                        <span class=" " style="color:#333">
                        <i class="fa fa-language " style="margin-right: 5px" aria-hidden="true"></i> {t}Password reset form{/t}
                        </span><br>


                </div>

                <div style="position:absolute;left:470px;width:300px;top:200px">
                        <span class=" " style="color:#333">
                        <i class="fa fa-language " style="margin-right: 5px" aria-hidden="true"></i> {t}Messages{/t}
                        </span><br>


                    <div style="font-size: 80%">
                        <span class="like_button " style="color:#333" onclick="show_password_reset_success()">
                            <i class="fa fa-check  ok" aria-hidden="true"></i>  <span class="ok">{t}Password changed{/t}</span>
                        </span><br>
                        <span class="like_button " style="color:#333" onclick="show_password_reset_expired_token_error()">
                         <i class="fa fa-exclamation-triangle error" aria-hidden="true"></i> <span class="error">{t}Token expired{/t}</span>
                        </span><br>
                        <span class="like_button " style="color:#333" onclick="show_password_reset_error()">
                          <i class="fa fa-exclamation-triangle error" aria-hidden="true"></i> <span class="error">{t}Incorrect/Used token{/t}</span>
                        </span><br>
                        <span class="like_button " style="color:#333" onclick="show_password_reset_logged_in_error()" >
                          <i class="fa fa-exclamation-triangle error" aria-hidden="true"></i> <span class="error">{t}User already log in{/t}</span>
                        </span>
                    </div>
                </div>


                <form action="" id="password_reset_form" class="sky-form">

                    <header id="_title" contenteditable="true">{$content._title}</header>


                    <fieldset>

                        <section>
                            <label id="_password" class="input " editor="password_input_editor" style="cursor:pointer" onclick="show_edit_input(this)">
                                <i class="icon-append icon-lock" style="cursor:pointer"></i>
                                <input class="register_field" readonly type="password" name="password" placeholder="{$content._password_placeholder}">
                                <b class="tooltip tooltip-bottom-right">{$content._password_tooltip}</b>
                            </label>
                        </section>

                        <section>
                            <label id="_password_confirm" class="input " editor="password_confirm_input_editor" style="cursor:pointer" onclick="show_edit_input(this)">
                                <i class="icon-append icon-lock" style="cursor:pointer"></i>
                                <input class="register_field" readonly type="password" name="password_confirm" placeholder="{$content._password_confirm_placeholder}">
                                <b class="tooltip tooltip-bottom-right">{$content._password_confirm_tooltip}</b>
                            </label>
                        </section>

                    </fieldset>


                    <footer>


                        <button type="submit" name="submit" class="button" id="_submit_label" contenteditable="true">{$content._submit_label}</button>


                    </footer>


                    <div class="message">


                        <i class="fa fa-check"></i>


                        <span class="password_reset_msg " id="password_reset_success_msg" contenteditable="true">{$content.password_reset_success_msg}</span>
                        <span class="password_reset_msg error" id="password_reset_expired_token_error_msg" contenteditable="true">{$content.password_reset_expired_token_error_msg}</span>
                        <span class="password_reset_msg error" id="password_reset_error_msg" contenteditable="true">{$content.password_reset_error_msg}</span>
                        <span class="password_reset_msg error" id="password_reset_logged_in_error_msg"  contenteditable="true">{$content.password_reset_logged_in_error_msg}</span>

                        <br>
                        <a id="password_reset_go_back" class="marked_link" contenteditable="true">{$content.password_reset_go_back}</a>
                        <a id="password_reset_go_home" class="marked_link" contenteditable="true">{$content.password_reset_go_home}</a>


                    </div>

                </form>

            </div>


        </div>

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


        content_data = {

        }
        labels= { };

        $('[contenteditable=true]').each(function (i, obj) {


            content_data[$(obj).attr('id')] = $(obj).html()
        })

        $('.website_localized_label').each(function (i, obj) {
            if($(obj).val()!=''){
                labels[$(obj).attr('id')] = $(obj).val()


            }

        })

        $('.label_field').each(function (i, obj) {

                content_data[$(obj).attr('id')] = $(obj).val()




        })


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'save_webpage_content')
        ajaxData.append("key", '{$webpage->id}')
        ajaxData.append("content_data", JSON.stringify(content_data))
        ajaxData.append("labels", JSON.stringify(labels))


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

// do things
    });

    function show_edit_input(element) {

        $('.editor').addClass('hide')

        offset = $(element).closest('section').offset();
        $('#' + $(element).attr('editor')).removeClass('hide').offset({
            top: offset.top, left: offset.left - 105
        }).attr('element_id', $(element).attr('id'));

    }

    function close_input_editor(element) {
        $(element).closest('.editor').addClass('hide')



        var _element = $('#' +  $(element).closest('.editor').attr('element_id'))



        _element.find('input').attr('placeholder', $(element).closest('.editor').find('.input_editor_placeholder').val())
        _element.find('b').html($(element).closest('.editor').find('.input_editor_tooltip').val())

        $('#save_button', window.parent.document).addClass('save button changed valid')
    }


    function show_password_reset_form() {
        $('#show_password_reset_form').addClass('hide')

        $('#password_reset_form').removeClass('submited')
    }

    function show_password_reset_success() {

        $('#show_password_reset_form').removeClass('hide')

        $('#password_reset_go_back').addClass('hide')
        $('#password_reset_go_home').removeClass('hide')

        $('.password_reset_msg').addClass('hide')

        $('#password_reset_success_msg').removeClass('hide').closest('div').find('i').addClass('fa-check').removeClass('error fa-exclamation')

        $('#password_reset_form').addClass('submited')
        $('#password_reset_form').find('.message').removeClass('error')

    }

    function show_password_reset_expired_token_error() {

        $('#show_password_reset_form').removeClass('hide')
        $('#password_reset_go_home').addClass('hide')


        $('#password_reset_go_back').removeClass('hide')
        $('.password_reset_msg').addClass('hide')



        $('#password_reset_expired_token_error_msg').removeClass('hide').closest('div').find('i').removeClass('fa-check').addClass('error fa-exclamation')

        $('#password_reset_form').addClass('submited ')
        $('#password_reset_form').find('.message').addClass('error')


    }

    function show_password_reset_error() {

        $('#show_password_reset_form').removeClass('hide')

        $('#password_reset_go_back').removeClass('hide')
        $('#password_reset_go_home').addClass('hide')

        $('.password_reset_msg').addClass('hide')
        $('#password_reset_error_msg').removeClass('hide').closest('div').find('i').removeClass('fa-check').addClass('error fa-exclamation')
        $('#password_reset_form').addClass('submited')
        $('#password_reset_form').find('.message').addClass('error')

    }

    function show_password_reset_logged_in_error() {

        $('#show_password_reset_form').removeClass('hide')
        $('#password_reset_go_back').addClass('hide')
        $('#password_reset_go_home').removeClass('hide')

        $('.password_reset_msg').addClass('hide')
        $('#password_reset_logged_in_error_msg').removeClass('hide').closest('div').find('i').removeClass('fa-check').addClass('error fa-exclamation')
        $('#password_reset_form').addClass('submited')
        $('#password_reset_form').find('.message').addClass('error')

    }

</script>

</body>

</html>

