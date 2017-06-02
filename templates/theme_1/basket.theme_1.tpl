{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 May 2017 at 19:40:24 GMT+8, Cyberjaya, Malaysia
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



                <div class="one_third">
                    <h5 id="_invoice_address_label" contenteditable="true">{$content._invoice_address_label}</h5>
                    <p>
                        The Business Centre </br>
                        61 Wellfield Road</br>
                        Roath</br>
                        Cardiff</br>
                        CF24 3DG</br>
                    </p>
                </div><!-- end section -->


                <div class="one_third last">
                    <h5 id="_delivery_address_label" contenteditable="true">{$content._delivery_address_label}</h5>
                    <p>
                        The Business Centre</br>
                        61 Wellfield Road</br>
                        Roath</br>
                        Cardiff</br>
                        CF24 3DG </br>                   </p>
                </div><!-- end section -->

                <div class="one_third text-right" style="padding-left:20px">
                    <h5 id="_totals_label" contenteditable="true">{$content._totals_label}</h5>



                    <table class="table">

                        <tbody>
                        <tr>
                            <td>-ABB1</td>

                            <td class="text-right">£10.00</td>
                        </tr>
                        <tr>
                            <td>HHT-04</td>

                            <td class="text-right">£6.00</td>
                        </tr>
                        <tr>
                            <td>LLX-10a</td>

                            <td class="text-right">£1.99</td>
                        </tr>
                        </tbody>
                    </table>

                </div><!-- end section -->


                <div class="clearfix margin_top10"></div>



                <div class="container order">


               {include file="theme_1/_order.theme_1.tpl"}


                </div>

                <div class="clearfix "></div>







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


