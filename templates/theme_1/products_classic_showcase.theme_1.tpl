{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 June 2017 at 12:32:53 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



{include file="theme_1/_head.theme_1.tpl"}


<body>
<div class="wrapper_boxed">
    <div class="site_wrapper">
        <div id="sections" class="content_fullwidth less2   ">


            {foreach from=$content['blocks'] item=show_block key=block_name}
                {include file="$theme/blk.$block_name.$theme.tpl"  }
            {/foreach}


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

           content_data.blocks = { }

            $('.webpage_section').each(function (i, obj) {

                var tmp = {

                }
                    tmp[$(obj).attr('section')] = ($(obj).hasClass('hide') ? 0 : 1)

                content_data.blocks[$(obj).attr('section')]=($(obj).hasClass('hide') ? 0 : 1)
            });


            content_data.intro = get_intro_section_data()
            content_data.catalogue = get_catalogue_section_data()


            console.log(content_data)


            //var request = '/ar_edit_website.php?tipo=save_webpage_content&key={$webpage->id}&content_data=' + encodeURIComponent(Base64.encode(JSON.stringify(content_data)));
            //console.log(request)

            var ajaxData = new FormData();

            ajaxData.append("tipo", 'save_webpage_content')
            ajaxData.append("key", '{$webpage->id}')
            //ajaxData.append("content_data", encodeURIComponent(Base64.encode(JSON.stringify(content_data))))
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


