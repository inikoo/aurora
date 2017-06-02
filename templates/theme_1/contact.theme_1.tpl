{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2017 at 08:39:38 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{include file="theme_1/_head.theme_1.tpl"}


<body>



<div class="wrapper_boxed">

    <div class="site_wrapper">

        <div class="content_fullwidth less2">

            <div class="one_full">
                <iframe     class="google-map2" src="{$store->get('Store Google Map URL')}" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" allowfullscreen></iframe>

            </div>




            <div class="clearfix marb10"></div>

            <div class="container">

                <div class="two_third" id="_text" >{$content._text}</div>
                <div class="one_third last">
                    <div class="address_info two">


                        <h4 class="light"  id="_address_label" contenteditable="true"  >{$content._address_label}</h4>


                        <p>

                            {if $store->get('Company Name')!=''}<strong>{$store->get('Company Name')}</strong><br/>{/if}
                            {if $store->get('Company Address')!=''}{$store->get('Company Address')}<br/><br/>{/if}




                            {if $store->get('Telephone')!=''}<span id="_telephone_label" contenteditable="true" >{$content._telephone_label}</span>: <strong>{$store->get('Telephone')}</strong><br/>{/if}
                            {if $store->get('FAX')!=''}<span id="_fax_label" contenteditable="true" >{$content._fax_label}</span>: <strong>{$store->get('FAX')}</strong><br/>{/if}

                            {if $store->get('Email')!=''}<span id="_email_label" contenteditable="true" >{$content._email_label}</span>: <a href="#">{$store->get('Email')}</a><br/>{/if}





                        </p>


                    </div><!-- end section -->


                </div>


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


        content_data = { };

        $('[contenteditable=true]').each(function (i, obj) {
            content_data[$(obj).attr('id')] = $(obj).html()
        })



            content_data['_text'] = $('#_text').html()



console.log(content_data)


        var request = '/ar_edit_website.php?tipo=save_webpage_content&key={$webpage->id}&content_data=' + encodeURIComponent(btoa(JSON.stringify(content_data)));


        console.log(request)


        $.getJSON(request, function (data) {


            $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

        })


    }

    $(document).delegate('a', 'click', function (e) {

        return false
    })


    $("form").on('submit', function (e) {

        e.preventDefault();
        e.returnValue = false;

// do things
    });






    $(function() {
        $('div#_text').froalaEditor({
            toolbarInline: true,
            charCounterCount: false,
            toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', 'color', 'emoticons', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'insertImage', 'insertLink', 'insertFile', 'insertVideo', 'undo', 'redo']
        })
    });


    $('div#_text').on('froalaEditor.contentChanged', function (e, editor, keyupEvent) {
        $('#save_button', window.parent.document).addClass('save button changed valid')
    });

</script>

</body>

</html>

