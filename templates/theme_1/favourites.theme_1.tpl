{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 November 2017 at 14:17:21 GMT+8, Plane Kuala Lumpur - Bali 
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{include file="theme_1/_head.theme_1.tpl"}


<body>

<div class="wrapper_boxed">

    <div class="site_wrapper">

        <div class="content_fullwidth less2" >

            <div class="container">
                <div class="title"><h1 ><span id="_title" class="_title" contenteditable="true">{$content._title}</span><span class="line"></span></h1></div>

                <div id="_with_items_div">{$content._text}</div>
                <div id="_no_items_div" class="hide">{$content._text_empty}</div>

            </div>


            <div class="clearfix marb12"></div>





        </div>
    </div>


    <script>

        document.addEventListener("paste", function(e) {
            // cancel paste
            e.preventDefault();

            // get text representation of clipboard
            var text = e.clipboardData.getData("text/plain");

            // insert text manually
            document.execCommand("insertHTML", false, text);
        });


        $('[contenteditable=true]').on('input paste', function (event) {
            $('#save_button', window.parent.document).addClass('save button changed valid')
        });



        $('#_with_items_div').froalaEditor({


            toolbarInline: true,
            charCounterCount: false,
            toolbarButtons:['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsMD:['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsSM:['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsXS:['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],

            defaultImageDisplay: 'inline',


            zIndex: 1000,

            pastePlain: true

        })




        $('#_with_items_div').on('froalaEditor.contentChanged', function (e, editor, keyupEvent) {
            $('#save_button', window.parent.document).addClass('save button changed valid')
        })

        $('#_no_items_div').froalaEditor({


            toolbarInline: true,
            charCounterCount: false,
            toolbarButtons:['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsMD:['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsSM:['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],
            toolbarButtonsXS:['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print', 'spellChecker', 'help', 'html', '|', 'undo', 'redo'],

            defaultImageDisplay: 'inline',


            zIndex: 1000,

            pastePlain: true

        })




        $('#_no_items_div').on('froalaEditor.contentChanged', function (e, editor, keyupEvent) {
            $('#save_button', window.parent.document).addClass('save button changed valid')
        });


        function save() {

            if (!$('#save_button', window.parent.document).hasClass('save')) {
                return;
            }

            $('#save_button', window.parent.document).find('i').addClass('fa-spinner fa-spin')


            content_data = {

            }

                $('[contenteditable=true]').each(function (i, obj) {


                    content_data[$(obj).attr('id')] = $(obj).html()
                })


            content_data['_text']=$('#_with_items_div').froalaEditor('html.get')
            content_data['_text_empty']=$('#_no_items_div').froalaEditor('html.get')





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

        $(document).delegate('a', 'click', function (e) {

            return false
        })

        function change_webpage_favourites_view(view) {




            if (view=='with_items') {

                $('#_with_items_div').removeClass('hide')
                $('#_no_items_div').addClass('hide')




            } else {
                $('#_with_items_div').addClass('hide')
                $('#_no_items_div').removeClass('hide')



            }



        }

        $('a').on('click',function(e) {
            e.preventDefault();
        });

    </script>

</body>

</html>

