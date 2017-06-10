{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2017 at 00:09:52 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


{include file="theme_1/_head.theme_1.tpl"}


<body>


<div id="input_editor" class="hide" style="z-index:100;position:absolute;padding:10px;border:1px solid #ccc;background-color: #fff;width:560px">
    <table style="width:100%;padding:30px">
        <tr>
            <td style="padding:5px 10px;padding-top:20px">{t}Placeholder{/t}
            </td>
            <td><input id="input_editor_placeholder" style="width:100%"/>
            </td>

        </tr>
        <tr>
            <td style="padding:5px 10px">{t}Tooltip{/t}
            </td>
            <td><input id="input_editor_tooltip" style="width:100%"/>
            </td>

        </tr>
        <tr>
            <td></td>
            <td style="padding:20px"><a onclick="save_edit_input()" class="but_minus"><i class="fa fa-check fa-lg"></i>&nbsp; {t}Done{/t}</a>
            </td>

        </tr>

    </table>


</div>


<div class="wrapper_boxed">

    <div class="site_wrapper">

        <div class="content_fullwidth less2">
            <div class="container">

                <div class="reg_form">
                    <form id="sky-form" class="sky-form">
                        <header id="_title" contenteditable="true">{$content._title}</header>

                        <fieldset>


                            <section>
                                <label class="input">
                                    <i id="_email" onclick="show_edit_input(this)" class="icon-append icon-envelope-alt"></i>
                                    <input class="register_field" type="email" name="email" id="_email_placeholder" placeholder="{$content._email_placeholder}">
                                    <b id="_email_tooltip" class="tooltip tooltip-bottom-right">{$content._email_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i id="_password" onclick="show_edit_input(this)" class="icon-append icon-lock"></i>
                                    <input class="register_field" type="password" name="password" id="_password_placeholder" placeholder="{$content._password_placeholder}" >
                                    <b id="_password_tooltip"  class="tooltip tooltip-bottom-right">{$content._password_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i id="_password_conform" onclick="show_edit_input(this)" class="icon-append icon-lock"></i>
                                    <input class="register_field" type="password" name="password" id="_password_confirm_placeholder" placeholder="{$content._password_confirm_placeholder}" >
                                    <b id="_password_conform_tooltip"  class="tooltip tooltip-bottom-right">{$content._password_conform_tooltip}</b>
                                </label>
                            </section>
                        </fieldset>

                        <fieldset>

                            <section>
                                <label class="input">
                                    <i class="icon-append icon-mobile-phone"></i>
                                    <input class="register_field" type="text" name="mobile" id="_mobile_placeholder" placeholder="{$content._mobile_placeholder}">
                                    <b id="_mobile_tooltip"  class="tooltip tooltip-bottom-right">{$content._mobile_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append icon-user"></i>
                                    <input class="register_field" type="text" name="contact_name" id="_contact_name_placeholder" placeholder="{$content._contact_name_placeholder}">
                                    <b id="_contact_name_tooltip"  class="tooltip tooltip-bottom-right">{$content._contact_name_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append icon-briefcase"></i>
                                    <input class="register_field" type="text" name="company" id="_company_placeholder" placeholder="{$content._company_placeholder}">
                                    <b id="_company_tooltip"  class="tooltip tooltip-bottom-right">{$content._company_tooltip}</b>
                                </label>
                            </section>


                            <section>
                                <label class="checkbox"><input type="checkbox" name="subscription" id="subscription"><i></i> </label>
                                <span style="margin-left:27px;	font: 14px/1.55 'Open Sans', Helvetica, Arial, sans-serif;position:relative;top:1px;font-size:15px;color:#404040


" id="_subscription" contenteditable="true">{$content._subscription}</span>
                                <label class="checkbox"><input type="checkbox" name="terms" id="terms"><i></i> </label>
                                <span style="margin-left:27px;	font: 14px/1.55 'Open Sans', Helvetica, Arial, sans-serif;position:relative;top:-1px;font-size:15px;color:#404040


" id="_terms" contenteditable="true">{$content._terms}</span>


                            </section>
                        </fieldset>
                        <footer>
                            <button type="submit" class="button" id="_submit_label" contenteditable="true">{$content._submit_label}</button>
                        </footer>
                    </form>
                </div>


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


        var request = '/ar_edit_website.php?tipo=save_webpage_content&key={$webpage->id}&content_data=' + encodeURIComponent(Base64.encode(JSON.stringify(content_data)));


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

