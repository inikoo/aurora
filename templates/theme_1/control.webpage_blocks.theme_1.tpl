{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2017 at 08:35:49 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>



</style>


<div id="blocks_showcase" class="hide" style="z-index: 2000;background-color: #fff;padding:20px;border:1px solid #ccc;width: 300px;position: absolute;"  webpage_key="{$webpage->id}" >
    <div style="margin-bottom:5px">  <i  onClick="$('#blocks_showcase').addClass('hide')" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i>  </div>

    <table style="width:100%;border-bottom: 1px solid #ccc;margin-top: 10px">
        {foreach from=$blocks item=$block key=key}
        <tr><td style="border-top: 1px solid #ccc"  class="add_webpage_block button" block="{$key}" ><span  ><i class="fa {$block.icon} fa-fw " style="margin-right: 50px " aria-hidden="true"></i>  {$block.label}</span> </td></tr>
        {/foreach}
    </table>

</div>


<div class=" edit_block_buttons  "  >
    <ul id="columns" class="sortable_webpage_blocks columns " style="width:1100px;" >


    {foreach from=$content.blocks item=$block key=key}
        {assign var="block_type" value=$block['type']}
        {include file="theme_1/blk.control_label.theme_1.tpl" }
    {/foreach}
        <li class="column  unselectable button  new_block "  style="min-width:auto;padding:4px 16px 4px 16px;" ><i class="fa fa-plus" aria-hidden="true"></i></li>

    </ul>

    <div id="edit_columns" class="hide" style="height: 27px;margin-bottom:10px"  current_key=""  >

        <i style="float:right"  class="fa button delete_block  fa-trash-o" aria-hidden="true"></i>


        <div style="float:left;position: relative;top:2.5px">
            <i id="edit_prev_column" onClick="edit_prev_webpage_blocks_column(this)" key="" class="edit_column_button fa button fa-arrow-left " aria-hidden="true"></i>
            <i id="exit_edit_column" style="margin-left:5px;margin-right: 5px"  onClick="exit_edit_webpage_block_column()" key="" class="edit_column_button fa button fa-window-close fa-flip-horizontal " aria-hidden="true"></i>
            <i id="edit_next_column" style="margin-right: 10px" onClick="edit_next_webpage_blocks_column(this)" key="" class="edit_column_button fa button fa-arrow-right " aria-hidden="true"></i>
        </div>

        <div id="blk_control_container">
        {foreach from=$content.blocks item=$block key=key}


            {assign var="block_type" value=$block['type']}
            {include file="theme_1/blk.control.$block_type.theme_1.tpl" }
        {/foreach}





    </div>


    <div style="clear:both"></div>

</div>


<script>




    $('.sortable_webpage_blocks').sortable({
        handle:'.handle2',
        start: function (event, ui) {
            pre = ui.item.index();
        }, stop: function (event, ui) {

            post = ui.item.index();
            $('#preview')[0].contentWindow.move_block(pre,post);
            $('#save_button').addClass('save button changed valid')
        }


    });







</script>