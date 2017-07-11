{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 July 2017 at 03:08:39 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{include file="theme_1/_head.theme_1.tpl"}

{assign "iframe_resize" false}

<body xmlns="http://www.w3.org/1999/html">
<div class="wrapper_boxed">

    <div class="site_wrapper">

        {foreach from=$content.blocks item=$block key=key}

            {include file="{$theme}/blk.{$block.type}.{$theme}.tpl" data=$block key=$key  }

            {if $block.type=='iframe'}{assign "iframe_resize" true}{/if}

        {/foreach}


    </div>

</div>


<script>
    {if $iframe_resize}
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
    {/if}


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
            }

        });

        content_data.blocks=blocks

        console.log(content_data)


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

