{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 May 2018 at 21:55:54 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div id="control_panel_desktop" style="padding:10px  20px  0px 20px  ;min-height: 30px;border-bottom:1px solid #ccc " class="control_panel ">

    <i onclick="" class="fa selected  fa-desktop fa-fw "></i>

    <i onclick="toggle_device(this)" class="far very_discreet  fa-mobile fa-fw button"></i>

    <i class="far hide fa-expand-alt button" onclick="toggle_showcase(this)"></i>

    <input style="display:none" type="file" name="favicon" id="update_image_favicon" class="image_upload"   data-parent="Website"  data-parent_key="{$website->id}"  data-parent_object_scope="Favicon"  data-metadata=''    data-options=""  data-response_type="website" />
    <label style="cursor: pointer" for="update_image_favicon">

    <img id="favicon"     style="margin-left:30px;height: 20px;width: 20px;vertical-align:middle" src="{if empty($settings['favicon'])}/art/favicon_empty.png{else}{$settings['favicon']}{/if}">  <span style=";margin-left: 5px;margin-right:30px;">Favicon</span>
    </label>



    <span onclick="add_header_text()" class="button" style="margin-left: 30px"><i class="fa fa-plus"></i> {t}Text in main header{/t}</span>
    <span onclick="add_search_text()" class="button" style="margin-left: 30px"><i class="fa fa-plus"></i> {t}Text in search area{/t}</span>



    <span id="save_button" class="" style="float:right" onClick="$('#preview')[0].contentWindow.save_header()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>


</div>


<div id="control_panel_mobile" style="padding:10px  20px  0px 20px  ;min-height: 30px;border-bottom:1px solid #ccc " class="control_panel hide">

    <i onclick="toggle_device(this)" class="far very_discreet fa-desktop fa-fw button"></i>

    <i onclick="" class="fa fa-mobile fa-fw "></i>


    <i class="far hide fa-expand-alt button" onclick="toggle_showcase(this)"></i>


    <span id="save_button_mobile" class="" style="float:right" onClick="$('#preview_mobile')[0].contentWindow.save_mobile_header()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>


</div>


<iframe id="preview" class="" style="width:100%;height: 1000px" frameBorder="0" src="/website.header.php?&website_key={$website->id}&theme={$theme}"></iframe>


<div id="mobile_preview_container" style="position: relative;" class="hide">
    <iframe id="preview_mobile" class="" style="width:400px;height: 700px;margin: 20px 50px !important" frameBorder="0" src="/website.header.mobile.php?&website_key={$website->id}&theme={$theme}"></iframe>


    <table id="main_settings" data-website_key="{$website->id}" style="position: absolute;left:540px;top:40px">



        <tr class="hide">


            <td colspan="2" class="label">




            </td>



        </tr>

        <tr class="hide">


            <td id="" class="label">{t}Header background{/t}
                <span data-scope="header_background-color" class="fa-stack color_picker scope_header_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>


            </td>

            <td id="" class="label">{t}Text color{/t}
                <span data-scope="header_color" class="fa-stack color_picker scope_header_color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>


            </td>

        </tr>
        <tr>


            <td id="" class="label">{t}Header image{/t}


            </td>

            <td id="" class="label">


                <input style="display:none" type="file" name="logo_mobile" id="update_image_logo_mobile" class="image_upload" data-options='{ "parent_object_scope":"logo_website_mobile"}'/>
                <label style="cursor: pointer" for="update_image_logo_mobile">
                    <img id="website_logo_mobile" style="height: 54px" src="{if !empty($mobile_style_values['header_background_image'])}{$mobile_style_values['header_background_image']}{else}/EcomB2b/art/nopic.png{/if}"/>
                </label>





            </td>

        </tr>
        <tr>


            <td  class="label">{t}Left margin{/t}


            </td>


            <td><span class="margins_container unselectable  " data-scope="header_text_padding">
                    <i class="fa fa-minus-circle down_margins button"></i> <input class="x edit_margin" value="{if !empty($mobile_style_values['header_text_padding'])}{$mobile_style_values['header_text_padding']}{else}80{/if}">
                    <i class="fa fa-plus-circle up_margins button"></i></span>
            </td>



            </td>

        </tr>
        <tr>
            <td  class="label">{t}Header text{/t}

            </td>

            <td >
              <input id="website_header_text_mobile" value="{if isset($settings['header_text_mobile_website'])}{$settings['header_text_mobile_website']}{/if}">



            </td>

        </tr>






    </table>
</div>

<script>



    droppedFiles = false;

    $(document).on('change', '.image_upload_mobile_XXXXX', function (e) {



        var ajaxData = new FormData();

        //var ajaxData = new FormData( );
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                ajaxData.append('files', file);
                return false;
            });
        }


        $.each($(this).prop("files"), function (i, file) {
            ajaxData.append("files[" + i + "]", file);
            return false;
        });


        ajaxData.append("tipo", 'upload_images')
        ajaxData.append("parent", 'website')
        ajaxData.append("parent_key", $('#main_settings').data('website_key'))
        ajaxData.append("options", JSON.stringify($(this).data('options')))
        ajaxData.append("response_type", 'website')

        var element = $(this)

        $.ajax({
            url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {

            }, success: function (data) {


                console.log(element.attr('name'))

                if (data.state == '200') {

                    $('#save_button_mobile').addClass('save button changed valid')


                    switch (element.attr('name')){
                        case 'logo_mobile':


                            console.log(data)
                            $('#website_logo_mobile').attr('src',data.image_src);

                            $('#preview_mobile').contents().find('.header-logo').css('background-image','url(/image.php?id='+data.img_key+')');

                            $('#preview_mobile').contents().find('.header-logo').attr('background-image','url(/image.php?id='+data.img_key+')')




                    }


                    $('#save_button_mobile').addClass('save button changed valid')



                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "{t}OK{/t}"
                    });
                }

                element.val('')

            }, error: function () {

            }
        });


    });



    $(document).on('input propertychange', '#website_header_text_mobile', function () {

        $('#save_button_mobile').addClass('save button changed valid')


        $('#preview_mobile').contents().find('.header-logo').html($(this).val())

    });


    $('.up_margins').on( "click", function() {
        $('input',$(this).closest('.margins_container')).each(function( index,input ) {



            value=parseInt($(input).val())

            if(isNaN(value)){
                value=0;
            }

            //  console.log(value)

            $(input).val( value+1)

            //  console.log($(input))

            change_margins(input)
        })

    });


    $('.down_margins').on( "click", function() {
        $('input',$(this).closest('.margins_container')).each(function( index,input ) {


            value=parseInt($(input).val())

            if(isNaN(value)){
                value=0;
            }

            console.log(value)

            value=value-1
            if(value<0){
                value=0;
            }


            $(input).val( value)
            change_margins(input)
        })

    });

    function change_margins(input) {

        if (!validate_signed_integer($(input).val(), 1000)) {
            $(input).removeClass('error')
            var value = $(input).val()

        } else {
            value = 0;

            $(input).addClass('error')
        }

        var element = $(input).closest('.element_for_margins').data('element')
        var scope = $(input).closest('.margins_container').data('scope')

        console.log(scope)

        switch (scope) {

            case 'header_text_padding':


                var max_width=$('#preview_mobile').contents().find('.header-logo').width()+40

                 console.log(max_width)
                console.log(value)
                // console.log($('#'+key).width())

                if(max_width<value){
                    $(input).addClass('error')

                }else{
                    $(input).removeClass('error')
                    var left = value + 'px'

                    $('#preview_mobile').contents().find('.header-logo').css('padding-left',left)
                }

                break;
                break


            default:
        }


        // element.css(scope+'-'+$(input).data('margin'), value + "px")


        $('#save_button_mobile').addClass('save button changed valid')

    }


</script>


<script>


    function add_header_text() {

        $('#preview')[0].contentWindow.add_header_text()

    }

    function add_search_text() {

        $('#preview')[0].contentWindow.add_search_text()

    }

    function toggle_device(element) {
        if (!$(element).hasClass('fa-desktop')) {

            $('#control_panel_mobile').removeClass('hide')
            $('#control_panel_desktop').addClass('hide')

            $('#preview').addClass('hide')
            $('#mobile_preview_container').removeClass('hide')

        } else {
            $('#control_panel_mobile').addClass('hide')
            $('#control_panel_desktop').removeClass('hide')
            $('#preview').removeClass('hide')
            $('#mobile_preview_container').addClass('hide')

        }


    }


    function toggle_showcase(element) {


        if ($(element).hasClass('fa-expand-alt')) {
            $(element).removeClass('fa-expand-alt').addClass('fa-compress-alt')
            $('#object_showcase').addClass('hide')
        } else {
            $(element).addClass('fa-expand-alt').removeClass('fa-compress-alt')
            $('#object_showcase').removeClass('hide')
        }

    }


</script>