{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 June 2018 at 18:15:11 GMT+8, Kuala Lumpur, Malaysia
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



    <div id="save_as_blueprint_dialog" style="border:1px solid #ccc;background-color: #fff;position: absolute;;padding:20px;top:200px;left:210px;z-index: 2000" class="save_as_blueprint_dialog hide">

    <i onclick="$(this).closest('div').addClass('hide')" style="position:relative;left:-10px;top:-10px" class="fa fa-window-close button" aria-hidden="true"></i>

    {t}No available{/t}

</div>




    <div id="save_email_template_dialog" style="border:1px solid #ccc;background-color: #fff;position: absolute;;padding:20px;top:55px;right:0px;z-index: 2000" class="hide">
        <i onclick="$(this).closest('div').addClass('hide')" style="position:relative;left:-10px;top:-10px" class="fa fa-window-close button" aria-hidden="true"></i>






            <span  onclick="send_prospect_personalized_email(this)"  class="button"  style="border:1px solid #ccc;padding:5px 5px 5px 10px;margin-left:10px">
                {t}Send email{/t} <i class="fa fa-paper-plane fa-fw"></i> 	&nbsp;
            </span>







    </div>

</div>




{if isset($control_template)}
    {include file=$control_template}

{/if}


<div id="email_template_text_controls"  style="padding:15px 20px;border-bottom:1px solid #ccc;position: relative" class="{if $email_template->get('Email Template Type')=='HTML'}hide{/if}  control_panel">



     <span id="change_template" onclick="open_send_test_email_dialog('')" class="button" style="float:left;border:1px solid #ccc;padding:5px 10px;margin-right:40px">
      {t}Send test email{/t}
    </span>







    <div style="clear: both"></div>

</div>

<div id="email_template_html_container" style="height:1000px"  class="{if $email_template->get('Email Template Type')=='Text'}hide{/if}"></div>

<div id="email_template_text_container" style="height:1000px;position:relative" class="{if $email_template->get('Email Template Type')=='HTML'}hide{/if}">


    <span id="email_template_set_as_text" class="small marked_link hide {if $email_template->get('Email Template Type')=='Text'}hide{/if}" style="position: absolute;top:7px;right:30px;" onclick="update_email_template_type('Text')" >{t}Set as text only email{/t}</span>

    <textarea id="composing_email_text" style="width:1155px;min-height:600px;resize: vertical;padding:5px 20px;;margin:25px 20px 20px 20px">{$email_template->get('Email Template Text')}</textarea>

</div>

<script>


    var mergeTags = [{
        name: '{t}Greetings{/t}',
        value: '[Greetings]'
    },{
        name: '{t}Contact name, Company{/t}',
        value: '[Name,Company]'
    }, {
        name: '{t}Prospect name{/t}',
        value: '[Prospect Name]'
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
        onSaveAsTemplate:open_save_as_blueprint_dialog,
        //onAutoSave:autosave_email_template,
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







    function open_save_as_blueprint_dialog(jsonFile){



        $('#save_as_blueprint_dialog').removeClass('hide')
        $('#save_as_blueprint_dialog').find('input').val('').focus().data('jsonFile',jsonFile).data('htmlFile','')

    }

    function open_save_email_template_dialog(jsonFile,htmlFile){

        $('#save_email_template_dialog').removeClass('hide')
        $('#save_email_template_dialog').data('jsonFile',jsonFile).data('htmlFile',htmlFile)


    }








    function send_prospect_personalized_email(element) {

       // $('#save_email_template_dialog').addClass('hide')

        if(   $(element).hasClass('wait')){
            return
        }

        $(element).addClass('wait')
        $(element).find('i').removeClass('fa-paper-plane').addClass('fa-spin fa-spinner')


        var ajaxData = new FormData();


        ajaxData.append("tipo", 'send_email')
        ajaxData.append("recipient", '{$recipient}')
        ajaxData.append("recipient_key", '{$recipient_key}')

        ajaxData.append("html", $('#save_email_template_dialog').data('htmlFile'))
        ajaxData.append("json", $('#save_email_template_dialog').data('jsonFile'))
        ajaxData.append("text", $('#composing_email_text').val())
        ajaxData.append("subject", $('#compose_email_subject').val())



        //$('#save_email_template_dialog').closest('div').addClass('hide')



        $.ajax({
            url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {

                if (data.state == '200') {



                    change_view(data.redirect)





                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "OK"
                    });
                }



            }, error: function () {

            }
        });

    }












    $(document).on('input propertychange', "#composing_email_text", function() {




        if (window.event && event.type == "propertychange" && event.propertyName != "value")
            return;

        window.clearTimeout($(this).data("timeout"));
        $(this).data("timeout", setTimeout(function () {

            if($("#composing_email_text").val()==''){
                $('#email_template_text_button').addClass('error very_discreet')
            }else{
                $('#email_template_text_button').removeClass('error very_discreet')
            }




        }, 200));
    });



</script>