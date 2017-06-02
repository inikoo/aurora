{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 May 2017 at 07:52:08 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



{include file="theme_1/_head.theme_1.tpl"}


<body>

<div class="wrapper_boxed">

    <div class="site_wrapper">

        <div class="content_fullwidth less2">


            <div id="show_thanks" class="show_div {if !$content.show_thanks   }hide{/if}">


                <div class="page_title1">
                    <div class="container">
                        <div class="title"><h1 id="_thanks_title" contenteditable="true">{$content._thanks_title}<span class="line"></span></h1></div>
                        <h6 id="_thanks_text" contenteditable="true">{$content._thanks_text}</h6>
                    </div>
                    <div class="divider_line4"><i class="fa fa-heart"></i></div>

                </div><!-- end page title -->
                <div class="clearfix"></div>

            </div>


            <div id="show_order" class="show_div {if !$content.show_order   }hide{/if}">


                <div class="container order">


               {include file="theme_1/_order.theme_1.tpl"}


                </div>

                <div class="clearfix "></div>

            </div>


            <div id="show_telephone" class="show_div {if !$content.show_telephone   }hide{/if}">

                <div class="features_sec49 two">
                    <div class="container">

                        <h2 id="_telephone_title" contenteditable="true">{$content._telephone_title}</h2>

                        <strong id="_telephone" contenteditable="true">{$content._telephone}</strong> <em id="_telephone_msg" contenteditable="true">{$content._telephone_msg}</em>

                    </div>
                </div><!-- end features section 49 -->
            </div>


            <div class="clearfix marb12"></div>


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


        content_data = {}

            $('[contenteditable=true]').each(function (i, obj) {


                content_data[$(obj).attr('id')] = $(obj).html()
            })

        $('.show_div').each(function (i, obj) {
            content_data[$(obj).attr('id')] = ($(obj).hasClass('hide') ? false : true)
        })



        var request = '/ar_edit_website.php?tipo=save_webpage_content&key={$webpage->id}&content_data=' + encodeURIComponent(btoa(JSON.stringify(content_data)));


        console.log(request)


        $.getJSON(request, function (data) {


            $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

        })


    }


    var droppedFiles = false;

    $('#file_upload').on('change', function (e) {


        var ajaxData = new FormData();

        //var ajaxData = new FormData( );
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                ajaxData.append('files', file);
            });
        }


        $.each($('#file_upload').prop("files"), function (i, file) {
            ajaxData.append("files[" + i + "]", file);

        });


        ajaxData.append("tipo", 'upload_images')
        ajaxData.append("parent", 'webpage')
        ajaxData.append("parent_key", '{$webpage->id}')

        ajaxData.append("options", JSON.stringify({
            max_width: 350

        }))

        ajaxData.append("response_type", 'webpage')


        //   var image = $('#' + $('#image_edit_toolbar').attr('block') + ' img')


        $.ajax({
            url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {

            }, success: function (data) {

                console.log(data)

                if (data.state == '200') {

                    console.log(data)

                    $('#_thanks_image').attr('src', data.image_src).attr('image_key', data.img_key)


                } else if (data.state == '400') {

                }


            }, error: function () {

            }
        });


    });


    function change_webpage_element_visibility(id, value) {


        if (value == 'hide') {
            $('#' + id).addClass('hide')
        } else {
            $('#' + id).removeClass('hide')
        }
        $('#save_button', window.parent.document).addClass('save button changed valid')


    }


</script>

</body>

</html>


