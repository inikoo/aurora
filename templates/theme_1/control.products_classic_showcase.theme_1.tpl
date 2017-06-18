{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 June 2017 at 12:35:27 GMT+8 Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    .web_block_layer {
        border: 1px solid #ccc;
        padding: 5px 10px;
        cursor: pointer;
        color: darkgray;
        background: #eee
    }

    .web_block_layer.selected {

        color: black;
        border: 1px solid #777;
        background: white;
    }

    .edit_blocks .edit_block_buttons{

        margin-left:20px
    }
    .edit_blocks .edit_block_buttons:first-child{

        margin-left:0px
    }

</style>

<span class=" edit_blocks  sortable">


    {foreach from=$content.blocks item=show key=block}

        {if $block=='intro'}
            {assign 'can_control' 1}
            {assign 'icon' 'fa-smile-o'}
            {assign 'label' "{t}Intro{/t}"}
        {elseif $block=='catalogue'}
            {assign 'can_control' 0}
            {assign 'icon' 'fa-shopping-bag'}
            {assign 'label' "{t}Catalogue{/t}"}
        {/if}

        <span class="edit_block_buttons" block_key="{$block}">
        <span id="edit_{$block}" class="block_label_labels  {if !$show==1}very_discreet{/if}">
            <i class="icon fa fa-fw {$icon} discreet"  aria-hidden="true" style="cursor:move" ></i><i class="editing_icon fa fa-fw fa-pencil-square-o hide"  aria-hidden="true"></i>
            <span class="block_label {if $can_control==1 }button{/if}">{$label}</span>
        </span>
            <i id="show_slider" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $show==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
        </span>

    {/foreach}

</span>


<script>






    $(".edit_block").click(function () {
        if (!$(this).hasClass('editing')) {
            $('.edit_block_buttons').addClass('hide')

            $(this).closest('.edit_block_buttons').removeClass('hide')

            $('#edit_slider_buttons').removeClass('hide')

            $(this).addClass('editing').find('i.icon').addClass("hide ")
            $(this).find('i.editing_icon').removeClass("hide ")


        } else {
            $('.edit_block_buttons').removeClass('hide')


            $('#edit_slider_buttons').addClass('hide')


            $(this).removeClass('editing').find('i.icon').removeClass("hide")
            $(this).find('i.editing_icon').addClass("hide")

            // $('#preview')[0].contentWindow.close_edit_slider()

        }

    });


    function change_webpage_element_visibility(element) {


        if ($(element).hasClass('success')) {

            $(element).removeClass('success').addClass('very_discreet')
            $(element).closest('.edit_block_buttons').find('.block_label_labels').addClass('very_discreet')


            $('#preview')[0].contentWindow.change_webpage_section_visibility('webpage_section_'+$(element).closest('.edit_block_buttons').attr('block_key'), 'hide')


        } else {
            $(element).addClass('success').removeClass('very_discreet')
            $(element).closest('.edit_block_buttons').find('.block_label_labels').removeClass('very_discreet')


            $('#preview')[0].contentWindow.change_section_visibility('webpage_section_'+$(element).closest('.edit_block_buttons').attr('block_key'), 'show')


        }


    }

    $( ".sortable" ).sortable(
        {
            placeholder: "ui-state-highlight",
            handle: ".icon",
            helper: "clone",
            start: function (event, ui) {
                pre = ui.item.index();
            }, stop: function (event, ui) {

            post = ui.item.index();




;
            $('#preview')[0].contentWindow.change_section_order(pre,post)






        }
        }

    );

</script>