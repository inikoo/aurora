{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 September 2016 at 13:58:35 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<span class="hide" id="email_template_data" data-email_template_key="{$email_template->id}" data-send_email_to="{$send_email_to}"></span>


<div style="position: relative">


    <div id="send_email_dialog" style="border:1px solid #ccc;background-color: #fff;position: absolute;;padding:20px;top:170px;left:210px;z-index: 2000" class="popup_dialog hide">

        <i onclick="$(this).closest('div').addClass('hide')" style="position:relative;left:-10px;top:-10px" class="fa fa-window-close button" aria-hidden="true"></i>




            <div style="margin-bottom: 15px"   class="toggle_send_test_email_with_tracking_code {if $email_template->get('Email Template Role')!='Delivery Confirmation'}hide{/if}"  >  <span class="button "    onclick="toggle_send_test_email_with_tracking_code()" ><i class="fa fa-toggle-on"></i> {t}Tracking code{/t}</span></div>




        {t}Email{/t} <input id="send_test_email_to" value="{$send_email_to}" style="width:300px"> <i style="margin-left:5px" id="send_email" onclick="send_test_email()"
                                                                                                class=" fa fa-paper-plane save {if $send_email_to!=''}changed{/if}" aria-hidden=" true"></i>

    </div>

    <div id="send_email_ok" style="border:1px solid #ccc;background-color: #fff;position: absolute;;padding:20px;top:170px;left:210px;z-index: 2000" class="popup_dialog hide">
        <i onclick="$(this).closest('div').addClass('hide')" style="position:relative;left:-10px;top:-10px" class="fa fa-window-close button" aria-hidden="true"></i>

        {t}Email send{/t} <i class="fa fa-thumbs-up padding_left_5 padding_right_10" aria-hidden="true"></i>

    </div>


    <div id="save_as_blueprint_dialog" style="border:1px solid #ccc;background-color: #fff;position: absolute;;padding:20px;top:200px;left:210px;z-index: 2000" class="save_as_blueprint_dialog hide">

        <i onclick="$(this).closest('div').addClass('hide')" style="position:relative;left:-10px;top:-10px" class="fa fa-window-close button" aria-hidden="true"></i>

        {t}Template name{/t} <input class="template_name" value="" style="width:300px"> <i onclick="save_as_blueprint(this)" style="margin-left:5px" class="save_template fa fa-cloud save " aria-hidden="true"></i>

    </div>


</div>


{if isset($control_template)}
    {include file=$control_template}

{/if}


<div id="email_template_text_controls" style="padding:15px 20px;border-bottom:1px solid #ccc;position: relative" class="{if $email_template->get('Email Template Type')=='HTML'}hide{/if}  control_panel">



     <span id="change_template" onclick="open_send_test_email_dialog('')" class="button" style="float:left;border:1px solid #ccc;padding:5px 10px;margin-right:40px">
      {t}Send test email{/t}
    </span>


    {if $email_template->get('Email Template Scope')=='EmailCampaignType'}
        <span id="publish_email_template_from_text_controls" onclick="publish_webpage_email_template()"
              class=" {if $email_template->get('Email Template Editing Checksum')==$email_template->get('Email Template Published Checksum')}super_discreet{else}button{/if}  "
              style="float:right;border:1px solid #ccc;padding:5px 10px;margin-right:0px">
      {t}Publish email{/t}
    </span>
    {else}
        <span id="publish_email_template_from_text_controls" onclick="publish_email_template({$email_template->id})"
              class=" {if $email_template->get('Email Template Editing Checksum')==$email_template->get('Email Template Published Checksum')}super_discreet{else}button{/if}  "
              style="float:right;border:1px solid #ccc;padding:5px 10px;margin-right:0px">
      {t}Set as ready for sending{/t}
    </span>
    {/if}


    <div style="clear: both"></div>

</div>

<div id="email_template_html_container" style="height:1000px" class="{if $email_template->get('Email Template Type')=='Text'}hide{/if}"></div>

<div id="email_template_text_container" style="height:1000px;position:relative" class="{if $email_template->get('Email Template Type')=='HTML'}hide{/if}">


    <span id="email_template_set_as_text" class="small marked_link hide {if $email_template->get('Email Template Type')=='Text'}hide{/if}" style="position: absolute;top:7px;right:30px;"
          onclick="update_email_template_type('Text')">{t}Set as text only email{/t}</span>

    <textarea id="email_template_text" style="width:1155px;min-height:600px;resize: vertical;padding:5px 20px;;margin:25px 20px 20px 20px">{$email_template->get('Email Template Text')}</textarea>

</div>

<script>

    validate_send_test_email_to()

    function toggle_send_test_email_with_tracking_code(){

        var icon= $('.toggle_send_test_email_with_tracking_code').find('i')

       if(icon.hasClass('fa-toggle-on')){
           icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')
       }else{
           icon.removeClass('fa-toggle-off').addClass('fa-toggle-on')

       }

    }

    var mergeTags = [

        {if empty($overwrite_merge_tags)}
        {
        name: '{t}Greetings{/t}', value: '[Greetings]'
    }, {
        name: '{t}Customer name{/t}', value: '[Customer Name]'
    }, {
        name: '{t}Contact name, Company{/t}', value: '[Name,Company]'
    }, {
        name: '{t}Contact name{/t}', value: '[Name]'
    }, {
        name: '{t}Signature{/t}', value: '[Signature]'
    }
    {/if}
        {$merge_tags}

    ];

    var mergeContents = [
        {$merge_contents}
    ]

    var specialLinks= [
        {if isset($special_links)}{$special_links}{/if}
    ];

    var beeConfig = {
        uid: 'CmsUserName', // [mandatory] identifies the set of resources to load
        container: 'email_template_html_container', // [mandatory] the id of div element that contains BEE Plugin
        //autosave: 15, // [optional, default:false] in seconds, allowed min-value: 15
        //language: 'en-US', // [optional, default:'en-US'] if language is not supported the default language is loaded (value must follow ISO 639-1  format)
        specialLinks: specialLinks, // [optional, default:[]] Array of Object to specify special links
        mergeTags: mergeTags, // [optional, default:[]] Array of Object to specify special merge Tags
        mergeContents: mergeContents, // [optional, default:[]] Array of Object to specify merge content
        preventClose: false, // [optional, default:false] if true an alert is shown before browser closure
        onSave: save_email_template,
        onAutoSave: auto_save_email_template,
        onSaveAsTemplate: open_save_as_blueprint_dialog,
        onSend: open_send_test_email_dialog, //onError: function(errorMessage) { /* Implements function to handle error messages */ } // [optional]
    };

    console.log('--- Bee plg in ---')
    $.getJSON('/ar_edit_email_template.php?tipo=bee_token', function (data) {

      console.log(data)

    var beeBody = {
        token : data.token,
        v2: true
    }
        BeePlugin.create(beeBody, beeConfig, function (beePluginInstance) {

            $.ajax({
                url: '/ar_email_template.php?tipo=template_data&field=json&key={$email_template_key}', success: function (data) {


                    var templateString = data;
                    var template = JSON.parse(templateString);
                     console.log(data)
                    beePluginInstance.start(template);

                }
            });

        });

    });





</script>