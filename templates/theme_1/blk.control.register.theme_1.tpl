{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 April 2018 at 16:37:39 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}






<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div id="edit_mode_main_{$key}" class="main" style="float:left;margin-right:20px;min-width: 200px;">


        <i class="toggle_view_items fa-fw fal fa-cogs   button hide" title="{t}Backstage view{/t}" title_alt="{t}Display view{/t}" style="position: relative;left:-12px;bottom:1.05px"></i>


        <div>

        <span >{t}Margin{/t}:</span>
        <input data-margin="top" class="edit_block_margin edit_block_input top" value="{if isset($block.top_margin)}{$block.top_margin}{else}0{/if}" placeholder="0"><input data-margin="bottom" class="edit_block_margin edit_block_input bottom"
                                                                                                                                                      value="{if isset($block.bottom_margin)}{$block.bottom_margin}{else}0{/if}"
                                                                                                                                                      placeholder="0">

        </div>

        {if empty($block.registration_type) }{assign type 'simple'}{else}{assign type $block.registration_type}{/if}




        <div style="margin-top: 10px">
            {t}Company/Sole trader fork{/t} <i onclick="toggle_form_type(this)"  class="button fa {if $type=='company_fork'}fa-toggle-on{else}fa-toggle-off{/if}"></i>

        </div>



    </div>


    <div style="clear: both"></div>
</div>

<script>

    function toggle_form_type(element) {

        let icon = $(element);
        let value;
        if (icon.hasClass('fa-toggle-on')) {
            icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')
             value = 'simple';
        } else {
            icon.removeClass('fa-toggle-off').addClass('fa-toggle-on')
             value = 'company_fork';

        }

        console.log(value)

        $('#preview')[0].contentWindow.update_toggle_form_type(value)
        $('#save_button').addClass('save button changed valid')


    }

</script>
