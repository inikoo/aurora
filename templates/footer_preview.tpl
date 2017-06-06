{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 13:54:58 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}



<div style="padding:20px;" class="control_panel">
    <span class="hide"><i class="fa fa-toggle-on" aria-hidden="true"></i> {t}Logged in{/t}</span>


    <span id="edit_mode" class="button edit_modes" onClick="change_edit_modes(this)">
        <i class="fa fa-pencil discreet" style="margin-left:15px" aria-hidden="true"></i> {t}Edit{/t}
    </span>

    <span id="drag_mode" class="button edit_modes very_discreet" onClick="change_edit_modes(this)">
        <i class="fa fa-hand-rock-o discreet" style="margin-left:15px" aria-hidden="true"></i> {t}Drag{/t}
    </span>

    <span id="block_edit_mode" class="button edit_modes very_discreet" onClick="change_edit_modes(this)">
        <i class="fa fa-recycle discreet" style="margin-left:15px" aria-hidden="true"></i> {t}Block edit{/t}
    </span>



    <span id="save_button" class="" style="float:right" onClick="$('#preview')[0].contentWindow.save_footer()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>


</div>



<iframe id="preview" style="width:100%;height: 750px" frameBorder="0" src="/webpage.footer.php?website_key={$website->id}&theme={$theme}"></iframe>


<div style="padding:20px">

    <i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i>
    <span data-data='{ "object": "website_footer", "key":"{$website->id}"}' onClick="reset_object(this)" class="delete_object disabled "> {t}Reset footer{/t} <i class="fa fa-recycle  "></i></span>

</div>


<script>


    function change_edit_modes(element){


        $('.edit_modes').addClass('very_discreet')
        $(element).removeClass('very_discreet')


        if($(element).attr('id')=='edit_mode'){
            $('#preview')[0].contentWindow.edit_mode_on();
        }else if($(element).attr('id')=='drag_mode'){
            $('#preview')[0].contentWindow.drag_mode_on();
        }else if($(element).attr('id')=='block_edit_mode'){
            $('#preview')[0].contentWindow.block_edit_mode_on();
        }
    }


</script>