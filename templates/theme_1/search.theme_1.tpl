{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 May 2017 at 14:49:54 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



{include file="theme_1/_head.theme_1.tpl"}


<body>

<div class="wrapper_boxed">

    <div class="site_wrapper">


        <div class="content_fullwidth">

            <div class="content text-center mx-auto">

                <input placeholder="search" style="width:80%;padding:5px 10px;font-size:140%"/> <i class="fa fa-search" style="margin-left:10px;font-size:140%;cursor:pointer" aria-hidden="true"></i>
                <div class="clearfix divider_line9 lessm"></div>

            </div>


            <div class="container">

                <div class="">

                    <div class="blog_post">
                        <div class="blog_postcontent">

                            <h4><a href="blog-post.html">Many web sites still in their infancy</a></h4>
                            <ul class="post_meta_links">
                                <li><a href="#" class="date">27 December 2014</a></li>
                                <li class="post_by"><i>code:</i> <a href="#">Adam Harrison</a></li>
                                <li class="post_categoty"><i>in:</i> <a href="#">Web tutorials</a></li>
                                <li class="post_comments"><i>products:</i> <a href="#">18 Comments</a></li>
                            </ul>
                            <div class="clearfix"></div>
                            <div class="margin_top1"></div>
                            <p>Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing
                                packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have
                                evolved over the years <a href="#">read more...</a></p>
                        </div>
                    </div><!-- /# end post -->

                    <div class="clearfix divider_line9 lessm"></div>


                    <div class="blog_post">
                        <div class="blog_postcontent">

                            <h4><a href="blog-post.html">Product description</a></h4>
                            <ul class="post_meta_links">
                                <li><a href="#" class="date">ABB-01</a></li>
                                <li class="post_by"><i>by:</i> <a href="#">Adam Harrison</a></li>
                                <li class="post_categoty"><i>in:</i> <a href="#">Web tutorials</a></li>
                                <li class="post_comments"><i>tags:</i> <a href="#">tag1</a><a href="#">tag2</a></li>
                            </ul>
                            <div class="clearfix"></div>
                            <div class="margin_top1"></div>

                            <div class="three_fourth">
                                <img src="http://placehold.it/150x150" alt="" class="pull-left "  style="margin-right: 10px;"  />
                                <p>Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing
                                    packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have
                                    evolved over the years <a href="#">read more...</a></p>
                            </div>
                            <div class="one_fourth last">
                                {t}Price{/t} £1.00 <br>
                                <input> <button>Buy</button>

                            </div>
                        </div>
                    </div><!-- /# end post -->

                    <div class="clearfix divider_line9 lessm"></div>

                    <div class="clearfix"></div>

                    <h5 class="light">About the Author</h5>
                    <div class="about_author">

                        <img src="http://placehold.it/80x80" alt=""/>

                        <a href="http://themeforest.net/user/gsrthemes9/portfolio" target="_blank">GSR Themes</a><br/>
                        I'm a freelance designer with satisfied clients worldwide. I design simple, clean websites and develop easy-to-use applications. Web Design is not just my job it's my passion. You need
                        professional web designer you are welcome.
                    </div><!-- end about author -->

                    <div class="clearfix margin_top7"></div>

                    <div class="one_half">
                        <div class="popular-posts-area">
                            <h5 class="light">{t}Related results{/t}</h5>
                            <div class="clearfix marb2"></div>

                            <ul class="recent_posts_list">
                                <li>
                                    <span><a href="#"><img src="http://placehold.it/50x50" alt=""/></a></span>
                                    <a href="#">Many desktop uncure publish package webpages simple on internet</a>
                                    <i>December 18, 2014</i>
                                </li>

                                <li>
                                    <span><a href="#"><img src="http://placehold.it/50x50" alt=""/></a></span>
                                    <a href="#">Many desktop uncure publish package webpages simple on internet</a>
                                    <i>December 17, 2014</i>
                                </li>

                                <li>
                                    <span><a href="#"><img src="http://placehold.it/50x50" alt=""/></a></span>
                                    <a href="#">Many desktop uncure publish package webpages simple on internet</a>
                                    <i>December 16, 2014</i>
                                </li>
                            </ul>

                        </div>
                    </div><!-- end recent posts -->


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


                if ($('#_welcome_image').attr('image_key') == '') {
                    content_data['_welcome_image'] = ''
                    content_data['_welcome_image_key'] = ''
                } else {
                    content_data['_welcome_image'] = $('#_welcome_image').attr('src')
                    content_data['_welcome_image_key'] = $('#_welcome_image').attr('image_key')
                }


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

                            $('#_welcome_image').attr('src', data.image_src).attr('image_key', data.img_key)


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


