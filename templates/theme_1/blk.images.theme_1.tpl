{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 December 2017 at 09:20:58 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>

    #image_control_panel{
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        padding: 10px 10px 10px 10px;
        z-index: 3000;
    }
    #image_control_panel td{
        padding-bottom: 10px;
    }

    div.blk_images figure {
        margin:0px

    }

    div.blk_images {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    div.blk_images figcaption{
        font-family: "Ubuntu", Helvetica, Arial, sans-serif;
        color:#999

    }

    .label{
        padding-right: 20px;
    }

    .caption_align i{
        padding-right: 10px;cursor: pointer;
    }

    figcaption.caption_left{
        text-align: left;

    }
    figcaption.caption_right{
        text-align: right;
    }
    figcaption.caption_center{
        text-align: center;

    }
    figcaption.caption_hide{
        visibility: hidden;
    }

    .success{
        color:#26A65B;
    }


</style>


<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} " style="Width:100%" >


    <div class="blk_images ">

{if  $data.images|@count==0}

    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250" alt="" display_type="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250" alt="" display_type="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">
        <figure>
            <img class="button" src="https://placehold.it/300x250" alt="" display_type="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>
    <span class=" image">

        <figure>
            <img class="button" src="https://placehold.it/300x250" alt="" display_type="caption_left">
            <figcaption contenteditable="true" class="caption_left" >{t}Caption{/t}</figcaption>
        </figure>
    </span>

    {/if}




    </div>




    <div class="clearfix"></div>
</div>



<div id="image_control_panel" class="hide">
    <div style="text-align: right;margin-bottom: 10px;padding-right: 5px">
        <i class="fa fa-window-close button" onclick="close_image_control_panel()"></i>
    </div>

    <table>
        <tr>
            <td class="label">{t}Image{/t}</td><td>

                <input style="display:none" type="file" block_key="{$key}" name="{$data.type}" id="update_image_{$key}" class="image_upload" data-options='{ }'/>
                <label style="font-weight: normal;cursor: pointer;width:100%"  for="update_image_{$key}">
                {t}Click to upload image{/t} <i class="hide fa fa-check success" aria-hidden="true"></i>
                </label>
            </td>
        </tr>
        <tr>
            <td class="label">{t}Tooltip{/t}</td><td><input class="image_tooltip" style="width: 200px" placeholder="tooltip"></td>
        </tr>
        <tr>
            <td class="label">{t}Link{/t}</td><td><input class="image_link" style="width: 200px" placeholder="https://"></td>
        </tr>
        <tr>
            <td class="label">{t}Caption{/t}</td><td class="caption_align">
                <i class="fa fa-align-left super_discreet caption_left" display_type="caption_left" aria-hidden="true"></i>
                <i class="fa fa-align-center super_discreet caption_center" display_type="caption_center" aria-hidden="true"></i>
                <i class="fa fa-align-right super_discreet caption_right" display_type="caption_right" aria-hidden="true"></i>
                <i class="fa fa-ban error super_discreet caption_hide" display_type="caption_hide" aria-hidden="true"></i>
            </td>
        </tr>
    </table>

    <div style="text-align: right;margin-bottom: 10px;padding-right: 5px">
        <span class="button"  onclick="update_image()"><i class="fa fa-check-square "></i> {t}OK{/t}</span>
    </div>

</div>



<script>
    $('.blk_images').on('click', '.image img', function (e) {

        open_image_control_panel(this);

     




    })

    $('.caption_align').on('click', 'i', function (e) {

        $('#image_control_panel').find('.caption_align i').addClass('super_discreet').removeClass('selected')
        $(this).removeClass('super_discreet').addClass('selected')



    })

    
    function open_image_control_panel(element){


        if(! $('#image_control_panel').hasClass('hide')){
            return
        }

        var image_index= $('span.image').index($(element).closest('.image') )+1

        $('#image_control_panel').removeClass('hide').offset({ top: .25*( $(element).offset().top +  $(element).height() )/2, left: $(element).offset().left }).attr('image_index', image_index).addClass('in_use')


        $('#image_control_panel').find('.image_tooltip').val($(element).attr('alt'))
        $('#image_control_panel').find('.image_link').val($(element).attr('link'))
        $('#image_control_panel').attr('old_image_src',$(element).attr('src'))

        $('#image_control_panel').find('.caption_align i').addClass('super_discreet').removeClass('selected')
        $('#image_control_panel').find('.caption_align i.'+$(element).attr('display_type')).removeClass('super_discreet').addClass('selected')

        $('#image_control_panel').find('.image_upload').attr('image_index',image_index)

    }

    function close_image_control_panel(){



        var   image=  $('.blk_images .image:nth-child('+$('#image_control_panel').attr('image_index')+') img')

        image.attr('src', $('#image_control_panel').attr('old_image_src'))




        $('#image_control_panel').addClass('hide')

    }

    function update_image(){

        var   image=  $('.blk_images .image:nth-child('+$('#image_control_panel').attr('image_index')+') img')

        image.attr('alt',$('#image_control_panel').find('.image_tooltip').val())
        image.attr('link',$('#image_control_panel').find('.image_link').val())

        var caption_class=$('#image_control_panel').find('.caption_align i.selected').attr('display_type')
        image.attr('display_type',caption_class)


        image.closest('figure').find('figcaption').removeClass('caption_left caption_right caption_center caption_hide').addClass(caption_class)

        $('#image_control_panel').addClass('hide')
    }

</script>
