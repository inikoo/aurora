{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2018 at 20:50:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

{include file="theme_1/_head.theme_1.mobile.tpl"}
<body>
<div id="page-transitions">
    {include file="theme_1/header.theme_1.mobile.tpl"}
    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->



            {assign "with_iframe" false}
            {assign "with_login" false}
            {assign "with_register" false}
            {assign "with_basket" false}
            {assign "with_checkout" false}
            {assign "with_profile" false}
            {assign "with_favourites" false}
            {assign "with_search" false}
            {assign "with_thanks" false}
            {assign "with_gallery" false}
            {assign "with_product_order_input" false}
            {assign "with_product" false}
            {assign "with_blackboard" false}
            {assign "with_reset_password" false}


            <div style="height: 300px">

            </div>


            {include file="theme_1/footer.theme_1.mobile.tpl"}
        </div>
    </div>

    <a href="#" class="hide back-to-top-badge"><i class="fas fa-arrow-circle-up"></i></a>


</div>

<script>


    function getScript(url, success) {


        var script = document.createElement('script');
        script.src = url;
        var head = document.getElementsByTagName('head')[0], done = false;
        script.onload = script.onreadystatechange = function () {
            if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
                done = true;
                success();
                script.onload = script.onreadystatechange = null;
                head.removeChild(script);
            }
        };
        head.appendChild(script);
    }


    getScript('/theme_1/js/mobile.min.js', function () {


        {if $website->get('Website Text Font')!=''  and !$logged_in}

        WebFontConfig = {
            google: {
                families: ['{$website->get('Website Text Font')}:400,700']}
        };

        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);

        {/if}







        getScript("EcomB2B/assets/mobile_custom.min.js", function () {



            {if $logged_in}



            getScript("/EcomB2B/assets/mobile.logged_in.min.js", function () {
                $('#logout i').removeClass('fa-spinner fa-spin').addClass('fa-sign-out')

            })

            {/if}


        })


    })


    function save_mobile_header() {


        if (!$('#save_button_mobile', window.parent.document).hasClass('save')) {
            return;
        }




        settings = {
            'header_text_mobile_website':$('.header-logo').html()

        }

        mobile_styles = {
           '.header-logo padding-left' : ['.header-logo','padding-left',$('.header-logo').css('padding-left')],
           '.header-logo background-image':  ['.header-logo','background-image',$('.header-logo').attr('background-image')]
    };


        console.log($('.header-logo').attr('background-image'))
        // return;

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'update_website_styles')
        ajaxData.append("mobile_styles", JSON.stringify(mobile_styles))
        ajaxData.append("settings", JSON.stringify(settings))
        ajaxData.append("key", {$website->id})


        $.ajax({
            url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {

                if (data.state == '200') {

                    $('#save_button_mobile', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "OK"
                    });
                }


            }, error: function () {

            }
        });


    }


    function save_mobile_menu() {


        if (!$('#save_button_mobile', window.parent.document).hasClass('save')) {
            return;
        }




        settings = {
            'left_menu_text':$('.sidebar-header-image .sidebar-logo strong').html()

        }

        mobile_styles = {
            '.sidebar-header-image .sidebar-logo strong padding-left' : ['.sidebar-header-image .sidebar-logo strong','padding-left',$('.sidebar-header-image .sidebar-logo strong').css('padding-left')],
            '.sidebar-header-image.bg-1 background-image':  ['.sidebar-header-image.bg-1','background-image',$('.sidebar-header-image.bg-1').attr('background-image')],
            '.sidebar-header-image .sidebar-logo background-image':  ['.sidebar-header-image .sidebar-logo','background-image',$('.sidebar-header-image .sidebar-logo').attr('background-image')]

        };



        console.log($('.sidebar-header-image.bg-1').attr('background-image'))
        console.log($('.sidebar-header-image .sidebar-logo').attr('background-image'))
        // return;

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'update_website_styles')
        ajaxData.append("mobile_styles", JSON.stringify(mobile_styles))
        ajaxData.append("settings", JSON.stringify(settings))
        ajaxData.append("key", {$website->id})


        $.ajax({
            url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {

                if (data.state == '200') {

                    $('#save_button_mobile', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "OK"
                    });
                }


            }, error: function () {

            }
        });


    }


    function delay_open_menu(){
        setTimeout(
            open_menu , 400);

    }
    function open_menu(){


        console.log($('.sidebar.sidebar-left'))

        if(!$('.sidebar.sidebar-left').hasClass('sidebar-left-active')){
            $( ".open-sidebar-left" ).trigger("click");
        }


    }


</script>

</body></html>
