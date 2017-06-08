{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 15:12:20 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div style="padding:20px;" class="control_panel">
    <span class="hide"><i class="fa fa-toggle-on" aria-hidden="true"></i> {t}Logged in{/t}</span>


    <i id="exit_edit_column" onClick="exit_edit_column()" class="fa button fa-sign-out fa-flip-horizontal hide" aria-hidden="true"></i>


    {foreach from=$header_data.menu.columns item=column key=key}
        <span id="edit_mode" class="button edit_modes" onClick="edit_column(this)" type="{$column.type}" key="{$key}">
            <i class="fa fa-fw {if $column.icon==''}} fa-circle-thin very_discreet {else}{$column.icon}{/if}" style="margin-left:10px" aria-hidden="true" title="{$column.label}"></i> <span
                    class="label hide">{$column.label}</span>

            <span class="column_controls hide">
            {if $column.type=='three_columns'}

                {foreach from=$column.sub_columns item=sub_column key=sub_column_key}

                <span id="column_{$sub_column_key}" style="border:1px solid #ccc;padding:4px 8px;margin-left:10px;">
                        {if $sub_column.type=='catalogue'}
                            {if $sub_column.scope=='departments_1_25'}
                                {t}Departments{/t} 1-25
                            {elseif $sub_column.scope=='departments_26_50'}
                                {t}Departments{/t} 26-50
                            {elseif $sub_column.scope=='departments_51_75'}
                                {t}Departments{/t} 51-75
                            {elseif $sub_column.scope=='departments_1_10'}
                                {t}Departments{/t} 1-10
                            {elseif $sub_column.scope=='departments_11_20'}
                                {t}Departments{/t} 11-20
                            {elseif $sub_column.scope=='departments_21_30'}
                                {t}Departments{/t} 21-30
                            {elseif $sub_column.scope=='departments_1_15'}
                                {t}Departments{/t} 1-15
                            {elseif $sub_column.scope=='departments_16_30'}
                                {t}Departments{/t} 16-30
                            {elseif $sub_column.scope=='departments_31_45'}
                                {t}Departments{/t} 31-45
                            {elseif $sub_column.scope=='families_1_25'}
                                {t}Families{/t} 1-25
                            {elseif $sub_column.scope=='families_26_50'}
                                {t}Families{/t} 26-50
                            {elseif $sub_column.scope=='families_51_75'}
                                {t}Families{/t} 51-75
                            {elseif $sub_column.scope=='families_1_10'}
                                {t}Families{/t} 1-10
                            {elseif $sub_column.scope=='families_11_20'}
                                {t}Families{/t} 11-20
                            {elseif $sub_column.scope=='families_21_30'}
                                {t}Families{/t} 21-30
                            {elseif $sub_column.scope=='families_1_15'}
                                {t}Families{/t} 1-15
                            {elseif $sub_column.scope=='families_16_30'}
                                {t}Families{/t} 16-30
                            {elseif $sub_column.scope=='families_31_45'}
                                {t}Families{/t} 31-45
                            {elseif $sub_column.scope=='web_families_1_25'}
                                {t}Web families{/t} 1-25
                            {elseif $sub_column.scope=='web_families_26_50'}
                                {t}Web families{/t} 26-50
                            {elseif $sub_column.scope=='web_families_51_75'}
                                {t}Web families{/t} 51-75
                            {elseif $sub_column.scope=='web_families_1_10'}
                                {t}Web families{/t} 1-10
                            {elseif $sub_column.scope=='web_families_11_20'}
                                {t}Web families{/t} 11-20
                            {elseif $sub_column.scope=='web_families_21_30'}
                                {t}Web families{/t} 21-30
                            {elseif $sub_column.scope=='web_families_1_15'}
                                {t}Web families{/t} 1-15
                            {elseif $sub_column.scope=='web_families_16_30'}
                                {t}Web families{/t} 16-30
                            {elseif $sub_column.scope=='web_families_31_45'}
                                {t}Web families{/t} 31-45
                            {elseif $sub_column.scope=='web_departments_1_25'}
                                {t}Web departments{/t} 1-25
                            {elseif $sub_column.scope=='web_departments_26_50'}
                                {t}Web departments{/t} 26-50
                            {elseif $sub_column.scope=='web_departments_51_75'}
                                {t}Web departments{/t} 51-75
                            {elseif $sub_column.scope=='web_departments_1_10'}
                                {t}Web departments{/t} 1-10
                            {elseif $sub_column.scope=='web_departments_11_20'}
                                {t}Web departments{/t} 11-20
                            {elseif $sub_column.scope=='web_departments_21_30'}
                                {t}Web departments{/t} 21-30
                            {elseif $sub_column.scope=='web_departments_1_15'}
                                {t}Web departments{/t} 1-15
                            {elseif $sub_column.scope=='web_departments_16_30'}
                                {t}Web departments{/t} 16-30
                            {elseif $sub_column.scope=='web_departments_31_45'}
                                {t}Web departments{/t} 31-45   
                                
                            {/if}
                        {/if}

                    </span>
{/foreach}
                    
                    
{elseif $column.type=='single_column'}
                
                <span style="border:1px solid #ccc;padding:4px;;margin-left:10px">1C</span>
            {/if}
            </span>
        </span>
    {/foreach}


    <span id="save_button" class="" style="float:right" onClick="$('#preview')[0].contentWindow.save_footer()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>


</div>


<iframe id="preview" style="width:100%;height: 750px" frameBorder="0" src="/webpage.header.php?website_key={$website->id}&theme={$theme}"></iframe>


<div style="padding:20px">

    <i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i>
    <span data-data='{ "object": "website_header", "key":"{$website->id}"}' onClick="reset_object(this)" class="delete_object disabled "> {t}Reset header{/t} <i class="fa fa-recycle  "></i></span>

</div>


<script>

    function edit_column(element) {


        $('.edit_modes').addClass('hide')
        $('#exit_edit_column').removeClass('hide')

        $(element).removeClass('hide')

        $(element).find('.label').removeClass('hide')
        $(element).find('.column_controls').removeClass('hide')


        $('#preview')[0].contentWindow.trigger_click($(element).attr('key'));

        switch ($(element).attr('type')) {

            case 'three_columns':
                break;
            case 'single_columns':
                break;

        }


    }

    function exit_edit_column() {

        $('.edit_modes').removeClass('hide')
        $('#exit_edit_column').addClass('hide')
        $('.label').addClass('hide')
        $('.column_controls').addClass('hide')

    }

</script>
