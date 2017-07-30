
{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 May 2017 at 18:46:27 GMT-5, CsMx, Mexico
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

                <div class="error_pagenotfound">

                    <strong id="_strong_title" contenteditable="true">{$content._strong_title}</strong>
                    <br/>
                    <b id="_title"  contenteditable="true">{$content._title}</b>

                    <em id="_text"  contenteditable="true">{$content._text}</em>

                    <p id="_home_guide"  contenteditable="true">{$content._home_guide}</p>

                    <div class="clearfix margin_top3"></div>

                    <a href="index.php" class="but_medium1"><span class="fa fa-home fa-lg"></span>&nbsp; <span id="_home_label"  contenteditable="true">{$content._home_label}</span></a>

                </div><!-- end error page notfound -->

            </div>






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


    $('a').click(function(e) {
        e.preventDefault();
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

</script>

</body>

</html>

