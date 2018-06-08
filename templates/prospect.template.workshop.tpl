{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 June 2018 at 16:05:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 September 2016 at 13:58:35 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<span class="hide" id="email_template_data"  data-email_template_key="{$email_template->id}"  ></span>

<div style="position: relative">



    <div id="send_email_dialog" style="border:1px solid #ccc;background-color: #fff;position: absolute;;padding:20px;top:170px;left:210px;z-index: 2000" class="popup_dialog hide">

        <i onclick="$(this).closest('div').addClass('hide')" style="position:relative;left:-10px;top:-10px" class="fa fa-window-close button" aria-hidden="true"></i>

        {t}Email{/t} <input id="send_email_to" value="{$send_email_to}" style="width:300px"  > <i  style="margin-left:5px" id="send_email" onclick="send_test_email()"  class=" fa fa-paper-plane save {if $send_email_to!=''}valid changed{/if} aria-hidden="true"></i>

    </div>

    <div id="send_email_ok" style="border:1px solid #ccc;background-color: #fff;position: absolute;;padding:20px;top:170px;left:210px;z-index: 2000" class="popup_dialog hide">
        <i onclick="$(this).closest('div').addClass('hide')" style="position:relative;left:-10px;top:-10px" class="fa fa-window-close button" aria-hidden="true"></i>

        {t}Email send{/t} <i class="fa fa-thumbs-up padding_left_5 padding_right_10" aria-hidden="true"></i>

    </div>



    <div id="save_as_another_template_dialog" style="border:1px solid #ccc;background-color: #fff;position: absolute;;padding:20px;top:200px;left:210px;z-index: 2000" class="save_as_another_template_dialog hide">

        <i onclick="$(this).closest('div').addClass('hide')" style="position:relative;left:-10px;top:-10px" class="fa fa-window-close button" aria-hidden="true"></i>


        <table>
            <tr><td>{t}Save as another prospect's invitation template{/t}</td></tr>
            <tr><td>{t}Template name{/t} <input class="template_name" value="" style="width:300px"  > <i  style="margin-left:5px"   onclick="save_as_another_template()" class="save_template fa fa-cloud save " aria-hidden="true"></i></td></tr>
        </table>

    </div>

   


</div>



{if isset($control_template)}
    {include file=$control_template}

{/if}




<div id="email_template_text_controls"  style="padding:15px 20px;border-bottom:1px solid #ccc;position: relative" class="{if $email_template->get('Email Template Type')=='HTML'}hide{/if}  control_panel">



     <span id="change_template" onclick="open_send_test_email_dialog('')" class="button" style="float:left;border:1px solid #ccc;padding:5px 10px;margin-right:40px">
      {t}Send test email{/t}
    </span>



    {if $email_template->get('Email Template Scope')=='Webpage'}
        <span id="publish_email_template_from_text_controls" onclick="publish_webpage_email_template()" class=" {if $email_template->get('Email Template Editing Checksum')==$email_template->get('Email Template Published Checksum')}super_discreet{else}button{/if}  " style="float:right;border:1px solid #ccc;padding:5px 10px;margin-right:0px">
      {t}Publish email{/t}
    </span>    {else}

        <span id="publish_email_template_from_text_controls" onclick="publish_email_template({$email_template->id})" class=" {if $email_template->get('Email Template Editing Checksum')==$email_template->get('Email Template Published Checksum')}super_discreet{else}button{/if}  " style="float:right;border:1px solid #ccc;padding:5px 10px;margin-right:0px">
      {t}Set as ready for sending{/t}
    </span>


    {/if}



    <div style="clear: both"></div>

</div>



<div id="email_template_html_container" style="height:1000px"  class="{if $email_template->get('Email Template Type')=='Text'}hide{/if}"></div>

<div id="email_template_text_container" style="height:1000px;position:relative" class="{if $email_template->get('Email Template Type')=='HTML'}hide{/if}">


    <span id="email_template_set_as_text" class="small marked_link  {if $email_template->get('Email Template Type')=='Text'}hide{/if}" style="position: absolute;top:7px;right:30px;" onclick="update_email_template_type('Text')" >{t}Set as text only email{/t}</span>

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
        onSave: save_email_template,
        onSaveAsTemplate:open_save_as_another_template_dialog,
        onAutoSave:autosave_email_template,
        onSend: open_send_test_email_dialog,
        //onError: function(errorMessage) { /* Implements function to handle error messages */ } // [optional]
    };

    $.getJSON('/ar_edit_email_template.php?tipo=bee_token', function( data ) {


        BeePlugin.create(
            data.token, beeConfig,
            function (beePluginInstance) {

                $.ajax({
                    url: '/ar_email_template.php?tipo=template_data&field=json&key={$email_template->id}',
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










    function open_save_as_another_template_dialog(jsonFile){



        $('#save_as_another_template_dialog').removeClass('hide')
        $('#save_as_another_template_dialog').find('input').val('').focus().data('jsonFile',jsonFile).data('htmlFile','')

    }



    function save_as_another_template() {

        var ajaxData = new FormData();


        var input=$('#save_as_another_template_dialog').find('.template_name')


        ajaxData.append("tipo", 'save_blueprint')
        ajaxData.append("json", input.data('jsonFile'))
        ajaxData.append("html", input.data('htmlFile'))

        ajaxData.append("name", input.val())

        // element.closest('div').addClass('hide')

        $.ajax({
            url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {

                if (data.state == '200') {

                   change_view(data.redirect);



                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "OK"
                    });
                }



            }, error: function () {

            }
        });

    }



    function save_email_template(json,html) {



        var ajaxData = new FormData();

        ajaxData.append("tipo", 'publish_email_template')
        ajaxData.append("email_template_key", $('#email_template_data').data('email_template_key'))
        ajaxData.append("json", json)
        ajaxData.append("html",html)

        //$('#save_email_template_dialog').closest('div').addClass('hide')



        $.ajax({
            url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {

                if (data.state == '200') {

                    $('#email_template_info').html(data.email_template_info)


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