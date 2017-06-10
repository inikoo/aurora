<!doctype html><!--[if IE 7 ]>
<html lang="en-gb" class="isie ie7 oldie no-js"> <![endif]--><!--[if IE 8 ]>
<html lang="en-gb" class="isie ie8 oldie no-js"> <![endif]--><!--[if IE 9 ]>
<html lang="en-gb" class="isie ie9 no-js"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en-gb" class="no-js"> <!--<![endif]-->

<head>
    <title>Aaika - Multipurpose HTML5 Template</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/favicon.ico">

    <!-- this styles only adds some repairs on idevices  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google fonts - witch you want to use - (rest you can just remove) -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script:700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- ######### CSS STYLES ######### -->

    <link rel="stylesheet" href="/theme_1/css/reset.css" type="text/css"/>
    <link rel="stylesheet" href="/theme_1/css/style_full.css" type="text/css"/>

    <link rel="stylesheet" href="/theme_1/local/font-awesome/css/font-awesome.min.css">

    <!-- animations -->
    <link href="/theme_1/animations/css/animations.min.css" rel="stylesheet" type="text/css" media="all"/>

    <!-- responsive devices styles -->
    <link rel="stylesheet" media="screen" href="/theme_1/css/responsive-layouts.css" type="text/css"/>

    <!-- shortcodes -->
    <link rel="stylesheet" media="screen" href="/theme_1/css/shortcodes.css" type="text/css"/>


    <link rel="stylesheet" media="screen" href="/theme_1/comingsoon/flipTimer.css" type="text/css"/>





    <link rel="stylesheet" href="/css/webpage_preview.css" type="text/css"/>


    <link rel="stylesheet" href="/theme_1/flipclock/flipclock.css">
    <link rel="stylesheet" media="screen" href="/theme_1/comingsoon/homepage_to_launch.css" type="text/css"/>


    <script src="/theme_1/local/jquery.js" type="text/javascript"></script>
    <script src="/theme_1/local/moment.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/libs/base64.js"></script>


</head>


<div id="bg-body"></div><!--end -->


<div class="site_wrapper">

    <div class="comingsoon_page">
        <div class="container" style=""  >

            <div class="topcontsoon">


                <div id="show_img" class="show_div   {if !$content.show_img}hide{/if}">
                    <form id="change_image" method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                        <input style="display:none" type="file" name="image_upload" id="file_upload" class="input_file" multiple/>
                        <label for="file_upload">
                            <img id="_img" class="button" img_key="{$content._img_key}" src="{if $content._img!=''}{$content._img}{else}/art/image_350x150.png{/if}" alt=""/>
                        </label>
                    </form>
                </div>

                <div class="clearfix"></div>

                <h5 contenteditable="true" id="_title" >{$content._title}</h5>



            </div><!-- end section -->
            <div class="clearfix"></div>



                <div id="show_countdown" launch_date="{$content._launch_date}"  class="show_div {if !$content.show_countdown  or $content._launch_date=='' }hide{/if}">


                    <div class="countdown_dashboard" style="" >

                        <div class="clock" ></div>



                </div>

            </div>

            <div class="clearfix"></div>



            <div class="text_email">

                <p contenteditable="true" id="_text">{$content._text}</p>
                <div id="show_email_form" class="show_div {if !$content.show_email_form}hide{/if}">

                <div class="clearfix marb4"></div>
                <span class="newslesoon" style="text-align: left;opacity:.7" contenteditable="true" id="_email_placeholder">{$content._email_placeholder}</span>
                <span class="newslesubmit" contenteditable="true" id="_email_submit_label">{$content._email_submit_label}</span>
                </div>
                <div class="clearfix"></div>


            </div><!-- end section -->



        </div>
    </div>

</div>

<script>

    $("form").submit(function (e) {

        e.preventDefault();
        e.returnValue = false;

        // do things
    });


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


        content_data['_img'] = $('#_img').attr('src')
        content_data['_img_key'] = $('#_img').attr('img_key')

        content_data['_launch_date'] = $('#show_countdown').attr('launch_date')




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

                    $('#_img').attr('src', data.image_src).attr('img_key', data.img_key)


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

<!-- animations -->



<script src="/theme_1/animations/js/animations.min.js" type="text/javascript"></script>


<script src="/theme_1/flipclock/flipclock.min.js"></script>


<script type="text/javascript">




    var seconds = -1*moment().diff("{$content._launch_date}", 'seconds');


    console.log(seconds)

    var clock = $('.clock').FlipClock(seconds, {
        clockFace: 'DailyCounter',
        countdown: true
    });
</script>


</body>
</html>
