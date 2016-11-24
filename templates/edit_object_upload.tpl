{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 November 2016 at 11:45:01 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

        <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
            {$upload_data.label}

            <input type="file" name="image_upload" id="file_upload" class="inputfile" multiple/>
            <label for="file_upload"><i class="fa fa-upload fa-fw button"></i></label>
        </form>

    <span id="file_upload_msg" style="float:right;padding-right:10px"></span>
    <script>
        var droppedFiles = false;

        $('#file_upload').on('change', function (e) {
            upload_file()
        });

        function upload_file() {
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


            ajaxData.append("tipo", '{$upload_data.tipo}')
            ajaxData.append("parent", '{$upload_data.parent}')
            ajaxData.append("parent_key", '{$upload_data.parent_key}')
            ajaxData.append("objects", '{$upload_data.object}')
            ajaxData.append("parent_object_scope", '{$upload_data.parent_object_scope}')

            $.ajax({
                url: "/ar_upload.php",
                type: 'POST',
                data: ajaxData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,


                complete: function () {

                },
                success: function (data) {


                    if (data.state == '200') {
                        console.log(data.thumbnail)

                        $('.{$field}').html(data.thumbnail)
                        close_edit_field('{$field}')

                    } else if (data.state == '400') {
                        $('#file_upload_msg').html(data.msg).addClass('error')
                    }


                },
                error: function () {

                }
            });
        }


    </script>
