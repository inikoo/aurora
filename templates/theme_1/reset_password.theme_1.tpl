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

<div class="wrapper_boxed">

    <div class="site_wrapper">
        <div class="clearfix marb12"></div>


        <div class="container">


            <div class="login_form">

                <form action="" id="reset_password-form" class="sky-form">

                    <header id="_title" contenteditable="true">{$content._title}</header>


                    <fieldset>

                        <section>

                            <label class="label" id="_email_label" contenteditable="true">{$content._email_label}</label>

                            <label class="input">

                                <i class="icon-append icon-user"></i>

                                <input type="email" name="email" id="email">

                            </label>

                        </section>

                    </fieldset>


                    <footer>


                        <a href="#" class="button button-secondary"> <span class="fa fa-arrow-circle-left fa-lg"></span>&nbsp; <span id="_go_back_label" contenteditable="true">{$content._go_back_label}</span></a>


                        <button type="submit" name="submit" class="button" id="_submit_label" contenteditable="true">{$content._submit_label}</button>


                    </footer>


                    <div class="message">

                        <i class="icon-ok"></i>

                        <p id="_success_msg" contenteditable="true">{$content._success_msg}<br>
                            <a href="#" class="button button-secondary"> <span class="fa fa-arrow-circle-left fa-lg"></span>&nbsp; <span class="_go_back_label">{$content._go_back_label}</span></a>
                        </p>

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


        content_data = {}

            $('[contenteditable=true]').each(function (i, obj) {


                content_data[$(obj).attr('id')] = $(obj).html()
            })


        var request = '/ar_edit_website.php?tipo=save_webpage_content&key={$webpage->id}&content_data=' + encodeURIComponent(btoa(JSON.stringify(content_data)));


        console.log(request)


        $.getJSON(request, function (data) {


            $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

        })


    }

    $(document).delegate('a', 'click', function(e) {

        return false
    })


    $("form").on('submit', function(e) {

        e.preventDefault();
        e.returnValue = false;

// do things
    });

</script>

</body>

</html>

