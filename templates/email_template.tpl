{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 September 2016 at 13:58:35 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<div>
    <span onclick="save_email()"  >xxx</span>

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
                    url: 'https://rsrc.getbee.io/api/templates/m-bee',
                    success: function (data) {
                        var templateString = data;
                        var template = JSON.parse(templateString);
                        beePluginInstance.start(template);

                    }
                });

            }
    );


    function save_email(){
        console.log(BeePlugin)


    }

</script>