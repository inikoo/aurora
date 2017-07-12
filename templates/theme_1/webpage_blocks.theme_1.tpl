{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 July 2017 at 03:08:39 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    #simple_line_icons_control_center{
        z-index:3000;position: absolute;background-color: #fff;border:1px solid #ccc;padding: 10px 20px 20px 20px
    }

    #simple_line_icons_control_center i{
        padding:2px;font-size: 110%;cursor:pointer
    }
</style>

{include file="theme_1/_head.theme_1.tpl"}

{assign "iframe_resize" false}




<div id="simple_line_icons_control_center" class="input_container  hide   " style="">

    <div style="margin-bottom:5px">  <i  onClick="$(this).closest('div').addClass('hide')" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i>  </div>


    <div>{t}Contact{/t}</div>

    <div>

        <i icon="icon-phone" aria-hidden="true" class="icon-phone"></i>
        <i icon="icon-call-in" aria-hidden="true" class="icon-call-in"></i>
        <i icon="icon-speech" aria-hidden="true" class="icon-speech"></i>
        <i icon="icon-bubbles" aria-hidden="true" class="icon-bubbles"></i>
        <i icon="icon-printer" aria-hidden="true" class="icon-printer"></i>
        <i icon="icon-microphone" aria-hidden="true" class="icon-microphone"></i>
        <i icon="icon-earphones" aria-hidden="true" class="icon-earphones"></i>
        <i icon="icon-earphones-alt" aria-hidden="true" class="icon-earphones-alt"></i>
        <i icon="icon-social-facebook" aria-hidden="true" class="icon-social-facebook"></i>
        <i icon="icon-question" aria-hidden="true" class="icon-question"></i>
        <i icon="icon-info" aria-hidden="true" class="icon-info"></i>
        <i icon="icon-envelope" aria-hidden="true" class="icon-envelope"></i>
        <i icon="icon-support" aria-hidden="true" class="icon-support"></i>
        <i icon="icon-volume-1" aria-hidden="true" class="icon-volume-1"></i>


    </div>

    <div>{t}Store{/t}</div>
    <div>
    <i icon="icon-wallet" aria-hidden="true" class="icon-wallet"></i>
    <i icon="icon-calculator" aria-hidden="true" class="icon-calculator"></i>
    <i icon="icon-home" aria-hidden="true" class="icon-home"></i>
    <i icon="icon-login" aria-hidden="true" class="icon-login"></i>
    <i icon="icon-logout" aria-hidden="true" class="icon-logout"></i>
    <i icon="icon-directions" aria-hidden="true" class="icon-directions"></i>
    <i icon="icon-map" aria-hidden="true" class="icon-map"></i>
    <i icon="icon-compass" aria-hidden="true" class="icon-compass"></i>
    <i icon="icon-cursor" aria-hidden="true" class="icon-cursor"></i>
    <i icon="icon-trophy" aria-hidden="true" class="icon-trophy"></i>
    <i icon="icon-tag" aria-hidden="true" class="icon-tag"></i>
    <i icon="icon-bulb" aria-hidden="true" class="icon-bulb"></i>

    <i icon="icon-present" aria-hidden="true" class="icon-present"></i>
    <i icon="icon-handbag" aria-hidden="true" class="icon-handbag"></i>
    <i icon="icon-globe" aria-hidden="true" class="icon-globe"></i>
    <i icon="icon-drawer" aria-hidden="true" class="icon-drawer"></i>
    <i icon="icon-basket" aria-hidden="true" class="icon-basket"></i>
    <i icon="icon-bag" aria-hidden="true" class="icon-bag"></i>
    <i icon="icon-credit-card" aria-hidden="true" class="icon-credit-card"></i>
    <i icon="icon-paypal" aria-hidden="true" class="icon-paypal"></i>
    <i icon="icon-social-dropbox" aria-hidden="true" class="icon-social-dropbox"></i>

    </div>
        <div>{t}Other{/t}</div>
    <div>
        <i icon="icon-cup" aria-hidden="true" class="icon-cup"></i>
        <i icon="icon-emotsmile" aria-hidden="true" class="icon-emotsmile"></i>
        <i icon="icon-layers" aria-hidden="true" class="icon-layers"></i>
        <i icon="icon-plus" aria-hidden="true" class="icon-plus"></i>
        <i icon="icon-minus" aria-hidden="true" class="icon-minus"></i>
        <i icon="icon-close" aria-hidden="true" class="icon-close"></i>
        <i icon="icon-exclamation" aria-hidden="true" class="icon-exclamation"></i>
        <i icon="icon-event" aria-hidden="true" class="icon-event"></i>
        <i icon="icon-plane" aria-hidden="true" class="icon-plane"></i>
        <i icon="icon-mustache" aria-hidden="true" class="icon-mustache"></i>
        <i icon="icon-chemistry" aria-hidden="true" class="icon-chemistry"></i>
        <i icon="icon-speedometer" aria-hidden="true" class="icon-speedometer"></i>
        <i icon="icon-pin" aria-hidden="true" class="icon-pin"></i>
        <i icon="icon-umbrella" aria-hidden="true" class="icon-umbrella"></i>
        <i icon="icon-rocket" aria-hidden="true" class="icon-rocket"></i>
        <i icon="icon-graph" aria-hidden="true" class="icon-graph"></i>
        <i icon="icon-like" aria-hidden="true" class="icon-like"></i>
        <i icon="icon-settings" aria-hidden="true" class="icon-settings"></i>
        <i icon="icon-lock" aria-hidden="true" class="icon-lock"></i>
        <i icon="icon-star" aria-hidden="true" class="icon-star"></i>
        <i icon="icon-heart" aria-hidden="true" class="icon-heart"></i>

        </div>

    </div>

</div>




<body xmlns="http://www.w3.org/1999/html">
<div class="wrapper_boxed">






    <div id="blocks" class="site_wrapper">

        {foreach from=$content.blocks item=$block key=key}

            {include file="{$theme}/blk.{$block.type}.{$theme}.tpl" data=$block key=$key  }


        {/foreach}


    </div>

</div>


<script>

    $( document ).ready(function() {
        resize_banners();
    });

    $(window).resize(function() {
        resize_banners();

    });

    function resize_banners(){
        $('.iframe').each(function(i, obj) {
            $(this).css({ height: $(this).width()*$(this).attr('h')/$(this).attr('w') })
        });
    }



    $(document).on('click', '.simple_line_item_icon', function (e) {



        $('#simple_line_icons_control_center').removeClass('hide').offset({
            top:$(this).offset().top-69 ,
            left:$(this).offset().left+$(this).width()    }).data('item',$(this))


    })

    $('#simple_line_icons_control_center').on('click', 'i', function (e) {

        //console.log($('#icons_control_center').data('item'))


        var input_container=$('#simple_line_icons_control_center')
        var icon= input_container.data('item')

        icon.removeClass (function (index, className) {


            return (className.match (/\bicon-\S+/g) || []).join(' ');
        }).addClass($(this).attr('icon'))



        icon.attr('icon',$(this).attr('icon'))



        input_container.addClass('hide')


        $('#save_button', window.parent.document).addClass('save button changed valid')


    })



    function move_block(pre, post) {

        if (post > pre) {
            $('#blocks ._block:eq(' + pre + ')').insertAfter('#blocks ._block:eq(' + post + ')');
        } else {
            $('#blocks ._block:eq(' + pre + ')').insertBefore('#blocks ._block:eq(' + post + ')');
        }
    }


    function save() {

        if (!$('#save_button', window.parent.document).hasClass('save')) {
            return;
        }

        $('#save_button', window.parent.document).find('i').addClass('fa-spinner fa-spin')


        content_data = { };

        var blocks=[]

        $('._block').each(function (i, obj) {

            switch ($(obj).attr('block')) {
                case 'iframe':


                    blocks.push({
                        type: 'iframe',
                        label: 'iFrame',
                        icon: 'fa-window-restore',
                        show: ($(obj).hasClass('hide') ? 0 : 1 ),
                        height: $(obj).attr('h'),
                        src:$(obj).find('iframe').attr('src').replace(/(^\w+:|^)\/\//, '')
                    })

                    break;
                case 'image':


                    blocks.push({
                        type: 'image',
                        label: '{t}Image{/t}',
                        icon: 'fa-image',
                        show: ($(obj).hasClass('hide') ? 0 : 1 ),
                        tooltip: $(obj).find('img').attr('title'),
                        link: $(obj).find('img').attr('link'),
                        src:$(obj).find('img').attr('src')
                    })

                    break;

                case 'six_pack':

                    var columns=[]




                    $('._col', obj).each(function(i, col) {


                        var _col=[]
                        $('._row', col).each(function(j, row) {

                            var _row={
                                icon:$(row).find('.six_pack_icon').attr('icon'),
                                title:$(row).find('.six_pack_title').html(),
                                text:$(row).find('.six_pack_text').html(),

                            }

                            _col.push(_row)


                        });

                        columns.push(_col)

                    });



                    blocks.push({
                        type: 'six_pack',
                        label: '{t}Siz-Pack{/t}',
                        icon: 'fa-th-large',
                        show: ($(obj).hasClass('hide') ? 0 : 1 ),
                        columns:columns

                    })

                    break;
            }

        });

        content_data.blocks=blocks

        console.log(content_data)
//return;

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

