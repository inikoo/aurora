{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 June 2017 at 16:15:46 GMT+7, Phuket, Thailand
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



{include file="theme_1/_head.theme_1.tpl"}


<body>

<div class="wrapper_boxed">

    <div class="site_wrapper">



    <div class="content_fullwidth">


        <div id="show_welcome"   class="show_div {if !$content.show_welcome   }hide{/if}">


        <div class="container">

            <div class="one_half">


                    <form id="change_image" method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                        <input style="display:none" type="file" name="image_upload" id="file_upload" class="input_file" multiple/>
                        <label for="file_upload">
                            <img style="max-width:562px " id="_welcome_image" class="button" image_key="{$content._welcome_image_key}"  src="{if $content._welcome_image!=''}{$content._welcome_image}{else}/art/image_562x280.png{/if}" alt="" class="rimg">

                        </label>
                    </form>



            </div><!-- end section -->

            <div class="one_half last">

                <div class="stcode_title5">
                    <h3 class="nmb"><strong id="_welcome_title" contenteditable="true">{$content._welcome_title}</strong></h3>
                </div>

                <h5 class="gray" id="_welcome_subtitle" contenteditable="true">{$content._welcome_subtitle}</h5>

                <p id="_welcome_text" contenteditable="true">{$content._welcome_text}</p>

                <div class="clearfix marb12"></div>

            </div><!-- end section -->

        </div><!-- end all sections -->


        <div class="clearfix"></div>

        </div>

        <div id="show_about"   class="show_div {if !$content.show_about   }hide{/if}">


        <div class="page_title4">
            <div class="container">
                <div class="title"><h1 id="_about_title" contenteditable="true">{$content._about_title}<span class="line"></span></h1></div>
                <h6 id="_about_text" contenteditable="true">{$content._about_text}</h6>
            </div>
        </div><!-- end page title -->


        <div class="clearfix"></div>
        </div>


        <div id="show_telephone"   class="show_div {if !$content.show_telephone   }hide{/if}">

        <div class="features_sec49 two">
            <div class="container">

                <h2 id="_telephone_title" contenteditable="true">{$content._telephone_title}</h2>

                <strong id="_telephone" contenteditable="true">{if $content._telephone=='#tel'}{$store->get('Telephone')}{else}{$content._telephone}{/if}</strong> <em id="_telephone_msg" contenteditable="true">{$content._telephone_msg}</em>

            </div>
        </div><!-- end features section 49 -->
        </div>

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
            content_data[$(obj).attr('id')] = ($(obj).hasClass('hide')?false:true)
        })


        if($('#_welcome_image').attr('image_key')==''){
            content_data['_welcome_image'] = ''
            content_data['_welcome_image_key'] = ''
        }else{
            content_data['_welcome_image'] = $('#_welcome_image').attr('src')
            content_data['_welcome_image_key'] = $('#_welcome_image').attr('image_key')
        }



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

                    $('#_welcome_image').attr('src', data.image_src).attr('image_key', data.img_key)


                } else if (data.state == '400') {

                }


            }, error: function () {

            }
        });


    });


    function change_webpage_element_visibility(id, value) {


        if(value=='hide'){
            $('#'+id).addClass('hide')
        }else{
            $('#'+id).removeClass('hide')
        }
        $('#save_button', window.parent.document).addClass('save button changed valid')



    }


</script>

</body>

</html>


