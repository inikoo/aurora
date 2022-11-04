{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 June 2018 at 15:39:47 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<div style="position: relative">



    <div id="send_email_dialog" style="border:1px solid #ccc;background-color: #fff;position: absolute;;padding:20px;top:170px;left:210px;z-index: 2000" class="popup_dialog hide">

        <i onclick="$(this).closest('div').addClass('hide')" style="position:relative;left:-10px;top:-10px" class="fa fa-window-close button" aria-hidden="true"></i>

        {t}Email{/t} <input id="send_test_email_to" value="{$send_email_to}" style="width:300px"  > <i  style="margin-left:5px" id="send_email" onclick="send_test_email()"  class=" fa fa-paper-plane save {if $send_email_to!=''}valid changed{/if} aria-hidden="true"></i>

    </div>

    <div id="send_email_ok" style="border:1px solid #ccc;background-color: #fff;position: absolute;;padding:20px;top:170px;left:210px;z-index: 2000" class="popup_dialog hide">
        <i onclick="$(this).closest('div').addClass('hide')" style="position:relative;left:-10px;top:-10px" class="fa fa-window-close button" aria-hidden="true"></i>

        {t}Email send{/t} <i class="fa fa-thumbs-up padding_left_5 padding_right_10" aria-hidden="true"></i>

    </div>



    <div id="save_as_another_template_dialog" style="border:1px solid #ccc;background-color: #fff;position: absolute;;padding:20px;top:200px;left:210px;z-index: 2000" class="save_as_another_template_dialog hide">

    <i onclick="$(this).closest('div').addClass('hide')" style="position:relative;left:-10px;top:-10px" class="fa fa-window-close button" aria-hidden="true"></i>

    {t}Template name{/t} <input class="template_name" value="" style="width:300px"  > <i  style="margin-left:5px" class="save_as_another_template fa fa-cloud save " aria-hidden="true"></i>

</div>



</div>




{if isset($control_template)}
    {include file=$control_template}

{/if}




<div id="email_template_html_container" style="height:1000px"  class="{if $email_template->get('Email Template Type')=='Text'}hide{/if}"></div>

<div id="email_template_text_container" style="height:1000px;position:relative" class="{if $email_template->get('Email Template Type')=='HTML'}hide{/if}">


    <span id="email_template_set_as_text" class="small marked_link hide {if $email_template->get('Email Template Type')=='Text'}hide{/if}" style="position: absolute;top:7px;right:30px;" onclick="update_email_template_type('Text')" >{t}Set as text only email{/t}</span>

    <textarea id="email_template_text" style="width:1155px;min-height:600px;resize: vertical;padding:5px 20px;;margin:25px 20px 20px 20px">{$email_template->get('Email Template Text')}</textarea>

</div>

<script>


    var mergeTags = [{
        name: '{t}Greetings{/t}',
        value: '[Greetings]'
    },{
        name: '{t}Contact name, Company{/t}',
        value: '[Name,Company]'
    }, {
        name: '{t}Customer name{/t}',
        value: '[Customer Name]'
    },{
        name: '{t}Contact name{/t}',
        value: '[Name]'
    }
    {$merge_tags}

    ];

    var mergeContents = [
        {$merge_contents}
    ]

    var beeConfig = {
        uid: 'CmsUserName', // [mandatory] identifies the set of resources to load
        container: 'email_template_html_container', // [mandatory] the id of div element that contains BEE Plugin
        //autosave: 15, // [optional, default:false] in seconds, allowed min-value: 15
        //language: 'en-US', // [optional, default:'en-US'] if language is not supported the default language is loaded (value must follow ISO 639-1  format)
        //specialLinks: specialLinks, // [optional, default:[]] Array of Object to specify special links
        mergeTags: mergeTags, // [optional, default:[]] Array of Object to specify special merge Tags
        mergeContents: mergeContents, // [optional, default:[]] Array of Object to specify merge content
        //preventClose: false, // [optional, default:false] if true an alert is shown before browser closure
        onSave: open_save_email_template_dialog,
        onSaveAsTemplate:open_save_as_template_dialog,
      //  onAutoSave:autosave_email_template,
        onSend: open_send_test_email_dialog,
        //onError: function(errorMessage) { /* Implements function to handle error messages */ } // [optional]
    };

    $.getJSON('/ar_edit_email_template.php?tipo=bee_token', function( data ) {


        BeePlugin.create(
            data.token, beeConfig,
            function (beePluginInstance) {

                $.ajax({
                    url: '/ar_email_template.php?tipo=template_data&field=json&key={$email_template_key}',
                    success: function (data) {


                        var templateString = data;
                        var template = JSON.parse(templateString);
                        // console.log(data)
                        beePluginInstance.start(template);

                    }
                });

            }
        );

    });



    $("#email_template_text_button,#email_template_html_button").on('click', function(){


        if($('#email_template_html_container').hasClass('hide')){
            $('#email_template_html_container').removeClass('hide')
            $('#email_template_text_container').addClass('hide')
            $("#email_template_text_button").removeClass('hide')
            $("#email_template_html_button").addClass('hide')

        }else{
            $('#email_template_html_container').addClass('hide')
            $('#email_template_text_container').removeClass('hide')
            $("#email_template_text_button").addClass('hide')
            $("#email_template_html_button").removeClass('hide')

        }

    });






    function open_save_as_template_dialog(jsonFile){



        $('#save_as_another_template_dialog').removeClass('hide')
        $('#save_as_another_template_dialog').find('input').val('').focus().data('jsonFile',jsonFile).data('htmlFile','')

    }





    function save_template_email(){

        $('#save_email_template_dialog').addClass('hide')
       // autosave($('#template_name2').data('jsonFile'))

    }



    $(".template_name").on('input propertychange', function(){

        if($(this).val()!=''){

            $(this).next('i').addClass('changed valid')

        }else{
            $(this).next('i').removeClass('changed valid')
        }

    });

    $(".save_as_another_template").on('click', function(){

        if($(this).hasClass('valid')){

            element=$(this).prev('input');

            var ajaxData = new FormData();

            ajaxData.append("tipo", 'save_as_another_template')
            ajaxData.append("json", element.data('jsonFile'))
            ajaxData.append("html", element.data('htmlFile'))

            ajaxData.append("name", element.val())

            // element.closest('div').addClass('hide')

            $.ajax({
                url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                complete: function () {
                }, success: function (data) {

                    if (data.state == '200') {


                        change_view(data.rediect)


                    } else if (data.state == '400') {
                        swal({
                            title: data.title, text: data.msg, confirmButtonText: "OK"
                        });
                    }



                }, error: function () {

                }
            });

        }

    });





    $("#show_save_as_another_template_dialog_from_save").on('click', function(){

       $('#save_email_template_dialog').addClass('hide')


        $('#save_as_another_template_dialog2').removeClass('hide')
        $('#save_as_another_template_dialog2').find('input').val('').focus()


    });



    function update_email_template_type(value){

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'set_email_template_type')
        ajaxData.append("email_template_key", '{$email_template_key}')
        ajaxData.append("value",value)





        $.ajax({
            url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {

                if (data.state == '200') {


                    $('.popup_dialog').addClass('hide');

                if(value=='HTML'){

                    if(data.has_html_json){
                        $('#email_template_add_html_section').addClass('hide')
                        $('#email_template_text_container').addClass('hide')
                        $('#email_template_text_controls').addClass('hide')

                        $('#email_template_set_as_text').removeClass('hide')
                        $('#email_template_html_container').removeClass('hide')
                        $('#change_template').removeClass('hide')



                        $('#email_template_text_button').removeClass('hide')
                        $('#email_template_html_button').addClass('hide')

                    }else{
                        change_view(state.request + '{$email_template_redirect}')
                    }







                }else{
                    $('#email_template_add_html_section').removeClass('hide')
                    $('#email_template_text_container').removeClass('hide')
                    $('#email_template_text_controls').removeClass('hide')

                    $('#email_template_set_as_text').addClass('hide')
                    $('#email_template_html_container').addClass('hide')
                    $('#change_template').addClass('hide')

                    $('#email_template_text_button').addClass('hide')
                    $('#email_template_html_button').addClass('hide')


                }


                } else if (data.state == '400') {

                }



            }, error: function () {

            }
        });




    }


</script>