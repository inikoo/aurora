{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2017 at 13:57:59 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div style="padding:20px;border-bottom:1px solid #ccc" class="control_panel">



<span id="registration_form" class="button"  style="border:1px solid #ccc;padding:5px 10px"   >
        <i class="fa fa-registered" aria-hidden="true" title="{t}Registration form{/t}"></i>
</span>


    <span id="save_button" class="" style="float:right" onClick="$('#preview')[0].contentWindow.save()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>

    <div style="clear:both"></div>

</div>




<div id="bee_plugin_container" style="height:2000px"></div>
<script>

    var beeConfig = {
        uid: 'CmsUserName', // [mandatory] identifies the set of resources to load
        container: 'bee_plugin_container', // [mandatory] the id of div element that contains BEE Plugin
//autosave: 30, // [optional, default:false] in seconds, allowed min-value: 15
//language: 'en-US', // [optional, default:'en-US'] if language is not supported the default language is loaded (value must follow ISO 639-1  format)
//specialLinks: specialLinks, // [optional, default:[]] Array of Object to specify special links
//mergeTags: mergeTags, // [optional, default:[]] Array of Object to specify special merge Tags
//mergeContents: mergeContents, // [optional, default:[]] Array of Object to specify merge content
//preventClose: false, // [optional, default:false] if true an alert is shown before browser closure
onSave: function(jsonFile, htmlFile) { console.log('caca') }, // [optional]
//onSaveAsTemplate: function(jsonFile) { /* Implements function for save as template */}, // [optional]
//onAutoSave: function(jsonFile) { /* Implements function for auto save */ }, // [optional]
//onSend: function(htmlFile) { /* Implements function to send message */ }, // [optional]
//onError: function(errorMessage) { /* Implements function to handle error messages */ } // [optional]
    };

    BeePlugin.create({$bee_token}, beeConfig,
            function (beePluginInstance) {

                $.ajax({
                    url: '/etemplates/minimalistic.json',
                    success: function (data) {


                        //var templateString = data;
                        //var template = JSON.parse(templateString);
                        console.log(data)
                        beePluginInstance.start(data);

                    }
                });

            }
    );



</script>