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
</style>

<span class=" edit_block_buttons  ">
<span id="edit_slider" class="webpage_block_label active_label  {if !$content.show_slider==1}very_discreet{else}button{/if}">       <i class="fa fa-fw fa-smile-o discreet" style="margin-left:20px"
                                                                                                                                       aria-hidden="true"></i> {t}Intro{/t} </span>
<i id="show_slider" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_slider==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
</span>

<span id="edit_slider_buttons" class="hide" style="margin-left:20px;cursor:default">


    {foreach from=$content.sliders key=key  item=slider name=sliders}
        <span onclick="change_slider(this)"  key="{$key}"  class=" {if $smarty.foreach.sliders.first}selected{/if} web_block_layer">{$key+1}</span>
    {/foreach}


    <span class="button" style="margin-left:50px"><i class="fa fa-television" aria-hidden="true"></i> {t}Background{/t}</span>

    <i class="fa fa-align-center" aria-hidden="true" style="margin-left:20px"></i>
    <i class="fa fa-link" aria-hidden="true" style="margin-left:20px"></i>
        <input value="" style="width:400px"/>
    <i class="fa fa-youtube-play" aria-hidden="true" title="{t}Button{/t}"></i>
   <i class="fa fa-arrows-alt hide" aria-hidden="true" title="{t}Click anywhere{/t}"></i>


</span>

<span class="edit_features_buttons edit_block_buttons  ">
        <i class="fa fa-th-large discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Features{/t}
    <i id="show_features" onClick="change_webpage_element_visibility(this)" class=" fa button fa-check {if $content.show_features==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_counter_buttons edit_block_buttons  ">
        <i class="fa fa-sort-numeric-asc   discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Counter{/t}
    <i id="show_counter" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_counter==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_catalogue_buttons edit_block_buttons  ">
        <i class="fa fa-shopping-bag   discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Catalogue{/t}
    <i id="show_catalogue" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_counter==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_what_we_do_buttons edit_block_buttons  ">
        <i class="fa fa-diamond discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Why us{/t}
    <i id="show_what_we_do" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_counter==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_image_buttons edit_block_buttons  ">
        <i class="fa fa-picture-o discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Image{/t}
    <i id="show_image" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_image==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_register_buttons edit_block_buttons  ">
        <i class="fa fa-sign-in  discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Register{/t}
    <i id="show_register" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_register==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span class="edit_products_buttons edit_block_buttons  ">
        <i class="fa fa-cube  discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Products{/t}
    <i id="show_products" onClick="change_webpage_element_visibility(this)" class="fa button fa-check {if $content.show_products==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<script>


    function change_slider(element){


        if(!$(element).hasClass('selected')){
            $('.web_block_layer').removeClass('selected')
            $(element).addClass('selected')
            $('#preview')[0].contentWindow.change_slider($(element).attr('key'))

        }





    }

    $("#edit_slider").hover(function () {
        if (!$(this).hasClass('editing')) {
            $(this).find('i').removeClass("fa-smile-o").addClass('fa-pencil-square-o')
        }
    }, function () {

        if (!$(this).hasClass('editing')) {
            $(this).find('i').removeClass("fa-pencil-square-o discreet").addClass('fa-smile-o')
        }


    });


    $("#edit_slider").click(function () {
        if (!$(this).hasClass('editing')) {
            $('.edit_block_buttons').addClass('hide')

            $(this).closest('.edit_block_buttons').removeClass('hide')

            $('#edit_slider_buttons').removeClass('hide')

            $(this).addClass('editing').find('i').removeClass("discreet fa-smile-o ").addClass('fa-pencil-square-o')
            $('#preview')[0].contentWindow.edit_slider()


        } else {
            $('.edit_block_buttons').removeClass('hide')


            $('#edit_slider_buttons').addClass('hide')



            $(this).removeClass('editing').find('i').removeClass("discreet fa-pencil-square-o ").addClass('fa-smile-o')
            $('#preview')[0].contentWindow.close_edit_slider()

        }

    });


    function change_webpage_element_visibility(element) {


        if ($(element).hasClass('success')) {
            $(element).removeClass('success').addClass('very_discreet')

            $(element).prev('.webpage_block_label').addClass('very_discreet')

            $('#preview')[0].contentWindow.change_webpage_element_visibility($(element).attr('id'), 'hide')


        } else {
            $(element).addClass('success').removeClass('very_discreet')
            $('#preview')[0].contentWindow.change_webpage_element_visibility($(element).attr('id'), 'show')
            $(element).prev('.webpage_block_label').removeClass('very_discreet')


        }


    }

</script>