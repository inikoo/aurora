{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 May 2017 at 12:35:09 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

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

                    <strong contenteditable="true" id="_strong_title">{$content._strong_title}</strong>
                    <br/>
                    <b contenteditable="true" id="_title">{$content._title}</b>

                    <em contenteditable="true" id="_text">{$content._text}</em>


                    <div id="_link_div" class="hide">
                            <p contenteditable="true" id="_link_guide">{$content._link_guide}</p>

                            <div class="clearfix margin_top3"></div>

                            <a href="" class="but_medium1"><span style="font-style:italic">{t}Webpage name{/t}</span> <span style="margin-left:5px" class="fa fa-share fa-lg"></span></a>

                        </div>

                        <div id="_home_div">
                            <p contenteditable="true" id="_home_guide">{$content._home_guide}</p>

                            <div class="clearfix margin_top3"></div>

                            <a href="" class="but_medium1"><span class="fa fa-home fa-lg"></span>&nbsp; <span contenteditable="true" id="_home_label">{$content._home_label}</span></a>
                        </div>


                </div><!-- end error page notfound -->

            </div>


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

        $(document).delegate('a', 'click', function (e) {

            return false
        })

        function change_webpage_offline_view(view) {




            if (view=='home') {

                $('#_link_div').addClass('hide')
                $('#_home_div').removeClass('hide')




            } else {
                $('#_link_div').removeClass('hide')
                $('#_home_div').addClass('hide')



            }



        }



    </script>

</body>

</html>

