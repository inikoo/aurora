{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 June 2017 at 13:14:58 GMT+7, Phuket, Thailand
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



{include file="theme_1/_head.theme_1.tpl"}


<body>

<div class="wrapper_boxed">

    <div class="site_wrapper">

        <div class="content_fullwidth less2">





            <div class="clearfix"></div>


            <div class="content_fullwidth less">

                <div class="clearfix marb7"></div>

                <div id="filters-container" class="cbp-l-filters-alignCenter">
                    <div data-filter="*" class="cbp-filter-item-active cbp-filter-item">All</div>
                    <div data-filter=".identity" class="cbp-filter-item">Identity</div>
                    <div data-filter=".web-design" class="cbp-filter-item">Web Design</div>
                    <div data-filter=".graphic" class="cbp-filter-item">Graphic</div>
                    <div data-filter=".video" class="cbp-filter-item">Video</div>
                </div>

                <div class="clearfix"></div>




            </div><!-- end content area -->

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



        var request = '/ar_edit_website.php?tipo=save_webpage_content&key={$webpage->id}&content_data=' + encodeURIComponent(Base64.encode(JSON.stringify(content_data)));


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

    <!-- cubeportfolio -->
    <script type="text/javascript" src="/theme_1/cubeportfolio/js/jquery.cubeportfolio.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready( function() {
            jQuery('#grid-container').cubeportfolio({
                filters: '#filters-container',
            });
        });
    </script>


</body>

</html>


