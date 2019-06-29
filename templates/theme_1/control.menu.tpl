{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 15:12:20 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<div id="aux">
<div id="three_columns_options" class="hide options_dialog" style="">

    <i class="fa fa-window-close button" onclick="$(this).closest('div').addClass('hide')" aria-hidden="true" style="margin-bottom: 5px"></i>


    <table  class="options">
        <tr><td data-type="departments" >{t}Departments{/t}</td></tr>
        <tr class="hide"><td data-type="families" >{t}Families{/t}</td></tr>
        <tr  class="hide"><td data-type="web_departments" >{t}Special departments{/t}</td></tr>
        <tr  class="hide"><td data-type="web_families" >{t}Special Families{/t}</td></tr>
        <tr><td data-type="items" >{t}Items{/t}</td></tr>
        <tr><td data-type="text" >{t}Text{/t}</td></tr>
        <tr><td data-type="image" >{t}Image{/t}</td></tr>
        <tr><td data-type="empty" class="disabled">{t}Nothing{/t}</td></tr>
    </table>

</div>

<div id="cataloge_page_options" class="hide options_dialog" style="">

    <i class="fa fa-window-close button" onclick="$(this).closest('div').addClass('hide')" aria-hidden="true" style="margin-bottom: 5px"></i>


    <table  class="options">
        <tr><td data-type="0-5" >&nbsp;&nbsp;1-5</td></tr>
        <tr><td data-type="5-5" >&nbsp;&nbsp;6-10</td></tr>
        <tr><td data-type="10-5" >11-15</td></tr>
        <tr><td ></td></tr>
        <tr><td data-type="0-10" >&nbsp;&nbsp;1-10</td></tr>
        <tr><td data-type="10-10" >11-20</td></tr>
        <tr><td data-type="20-10" >21-30</td></tr>
        <tr><td ></td></tr>
        <tr><td data-type="0-15" >&nbsp;&nbsp;1-15</td></tr>
        <tr><td data-type="15-15" >16-30</td></tr>
        <tr><td data-type="30-15" >31-45</td></tr>

    </table>

</div>

<div id="column_type_options" class="hide options_dialog" style="">

    <i class="fa fa-window-close button" onclick="$(this).closest('div').addClass('hide')" aria-hidden="true" style="margin-bottom: 5px"></i>


    <table  class="options">

        <tr><td class="column_type_three_columns" data-type="three_columns" >{t}Three columns{/t}</td></tr>
        <tr><td class="column_type_single_column" data-type="single_column" >{t}Single column{/t}</td></tr>
        <tr><td class="column_type_nothing" data-type="nothing" >{t}Nothing{/t}</td></tr>
    </table>

</div>
</div>

<div id="control_panel_desktop" style="padding:0px;min-height: 30px" class="control_panel ">


    <table border="0" style="width: 100%">
        <tr>
            <td style="width: 50px;padding-left: 10px">


                <i onclick="" class="fa selected  fa-desktop fa-fw "></i>

                <i onclick="toggle_device(this)" class="far very_discreet  fa-mobile fa-fw button"></i>


            </td>
            <td style="padding-top: 10px">
                <ul id="columns" class="sortable2 columns" >

                    {foreach from=$header_data.menu.columns item=column key=key}


                        <li id="_column_{$key}" class="column" style="" class="button unselectable {if !$column.show}very_discreet hidden_menu{/if}" key="{$key}">
                    <span class="button open_edit">
                    <i class="fa-fw  {if $column.icon==''}} fa fa-ban error very_discreet {else}{$column.icon}{/if}"  aria-hidden="true" title="{$column.label}"></i>
                    <span class="label ">{$column.label}</span>
                     </span>
                            <i class="far {if $column.show}fa-eye{else}fa-eye-slash{/if} column_show button" aria-hidden="true"></i>
                            <i class="far fa-hand-rock handle2"  aria-hidden="true"></i>
                        </li>


                    {/foreach}
                    <li id="add_column" style="width: 20px;min-width:20px;padding:4px 5px" class="button unselectable" ">
                    <i class="fa fa-fw  button fa-plus"  onclick="add_header_column()" aria-hidden="true" title="{t}Add column{/t}"></i>



                    </li>

                </ul>

                <div id="edit_columns" class="hide">

                    <div style="float:left">
                        <i id="edit_prev_column" onClick="edit_prev_column(this)" key="" class="edit_column_button fa button fa-arrow-left " aria-hidden="true"></i>
                        <i id="exit_edit_column" style="margin-left:5px;margin-right: 5px"  onClick="exit_edit_column(this)" key="" class="edit_column_button fa button fa-window-close fa-flip-horizontal " aria-hidden="true"></i>
                        <i id="edit_next_column" style="margin-right: 10px" onClick="edit_next_column(this)" key="" class="edit_column_button fa button fa-arrow-right " aria-hidden="true"></i>
                    </div>

                    {counter start=-1 print=false}
                    <div id="edit_mode_container">
                        {foreach from=$header_data.menu.columns item=column key=key}
                            <div id="edit_mode_{$key}" class=" edit_mode hide "  type="{$column.type}" key="{$key}">
                                <div style="float:left;margin-right:20px;min-width: 200px;">
                                    <span class="column_type button {$column.type}" data-column_type="{$column.type}" style="border:1px solid #ccc;padding:4px;;margin-left:5px"><span class="column_type_label">{if $column.type=='three_columns'}3{elseif $column.type=='single_column'}1{else}0{/if}</span> <i class="fa fa-bars button  " aria-hidden="true"></i> &nbsp; </span>
                                    <span class="button delete_column" title="{t}Delete this column{/t}" style="border:1px solid #ccc;padding:4px;border-left: none"><i class="far fa-trash-alt button  " style="padding-left: 5px;padding-right: 7px" aria-hidden="true"></i></span>


                                </div>



                                <span class="column_controls">
            {if $column.type=='three_columns'}
                <ul class="sortable unselectable columns" style="position: relative;top:-4px">
                    {foreach from=$column.sub_columns item=sub_column key=sub_column_key}
                        <li class="column_label"  key="{$sub_column_key}" ><i class="far fa-hand-rock handle" style="margin-right:10px" aria-hidden="true"></i>

                            {if $sub_column.type=='departments'}
                                <span class="column_type_label">{t}Departments{/t}</span> <span class="page button">{if isset($sub_column.page_label)}{$sub_column.page_label}{/if}</span>
                            {elseif $sub_column.type=='families'}
                                <span class="column_type_label">{t}Families{/t}</span> <span class="page button">{if isset($sub_column.page_label)}{$sub_column.page_label}{/if}</span>
                            {elseif $sub_column.type=='web_departments'}
                                <span class="column_type_label">{t}Special departments{/t}</span> <span class="page button">{if isset($sub_column.page_label)}{$sub_column.page_label}{/if}</span>
                            {elseif $sub_column.type=='web_families'}
                                <span class="column_type_label"> {t}Special families{/t}</span> <span  class="page button">{if isset($sub_column.page_label)}{$sub_column.page_label}{/if}</span>
                            {elseif $sub_column.type=='items'}
                                <span class="column_type_label">{t}Items{/t}</span>
                             {elseif $sub_column.type=='text'}
                                <span class="column_type_label">{t}Text{/t}</span>
                            {elseif $sub_column.type=='image'}
                                <span class="column_type_label">{t}Image{/t}</span>
                            {elseif $sub_column.type=='empty'}
                                <span class="column_type_label">{t}Empty{/t}</span>
                            {/if}
                            <i class="fa fa-recycle button change_type" style="margin-left:10px" aria-hidden="true"></i>
                            </li>
                    {/foreach}
                </ul>

            {elseif $column.type=='single_column'}

            {/if}
            </span>
                            </div>
                            {counter print=false}
                        {/foreach}

                        <div id="add_column_control_anchor" class="hide" data-key="{counter}"></div>
                    </div>
                </div>
            </td>
            <td style="width: 80px">

                <span id="save_button" class="" onClick="$('#preview')[0].contentWindow.save_header()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>



            </td>
        </tr>
    </table>









</div>

<div id="control_panel_mobile" style="padding:0px;min-height: 35px;padding-top:10px;border-bottom: 1px solid #ccc" class="control_panel hide">


    <table border="0" style="width: 100%">
        <tr>
            <td style="width: 50px;padding-left: 10px">


                <i onclick="toggle_device(this)" class="far button  very_discreet fa-desktop fa-fw "></i>

                <i  class="fa  selected fa-mobile fa-fw "></i>


            </td>
            <td style="padding-top: 10px">

            </td>
            <td style="text-align: right;padding-right: 20px">
                <span id="save_button_mobile" class=""  onClick="$('#preview_mobile')[0].contentWindow.save_mobile_menu()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>
            </td>
        </tr>
    </table>







</div>

<div id="preview_container" style="position: relative;" class="">

<iframe id="preview"  style="width:100%;height: 750px" frameBorder="0" src="/website.menu.php?&website_key={$website->id}&theme={$theme}"></iframe>



    <div   style="padding:20px">

        <i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i>
        <span data-data='{ "object": "website_header", "key":"{$website->id}"}' onClick="reset_object(this)" class="delete_object disabled "> {t}Reset header{/t} <i class="fa fa-recycle  "></i></span>

    </div>

</div>







<div id="mobile_preview_container" style="position: relative;" class="hide">
    <iframe id="preview_mobile" class="" style="width:400px;height: 700px;margin: 20px 50px !important" frameBorder="0" src="/website.header.mobile.php?&website_key={$website->id}&theme={$theme}"></iframe>


    <table id="main_settings" data-website_key="{$website->id}" style="position: absolute;left:540px;top:40px">



        <tr class="">


            <td colspan="2" class="label strong" style="border-bottom: 1px solid #ccc">


                {t}Left menu{/t}

            </td>



        </tr>



        <tr>
            <td  class="label padding_right_20" style="vertical-align: top;">{t}Background image{/t}


            </td>

            <td  class="label" style="padding-top: 5px;">


                <input style="display:none" type="file" name="left_menu_background" id="update_image_left_menu_background_mobile" class="image_upload"  data-parent="Website" data-parent_key="{$website->id}" data-parent_object_scope="Mobile Left Flap Background" data-metadata=""  data-options=""  data-response_type="website"  />
                <label style="cursor: pointer" for="update_image_left_menu_background_mobile">
                    <img id="website_left_menu_background_mobile" style="height: 120px" src="{if !empty($mobile_style_values['left_menu_background'])}{$mobile_style_values['left_menu_background']}{else}/EcomB2b/art/nopic.png{/if}"/>
                </label>





            </td>

        </tr>


        <tr>
            <td  class="label" style="vertical-align: top;">{t}Logo{/t}


            </td>

            <td  class="label">


                <input style="display:none" type="file" name="left_menu_logo_mobile" id="update_image_logo_mobile" class="image_upload" data-parent="Website" data-parent_key="{$website->id}" data-parent_object_scope="Mobile Left Flap Logo " data-metadata=""  data-options=""  data-response_type="website" />
                <label style="cursor: pointer" for="update_image_logo_mobile">
                    <img id="left_menu_logo_mobile" style="height: 54px" src="{if !empty($mobile_style_values['left_menu_logo'])}{$mobile_style_values['left_menu_logo']}{else}/EcomB2b/art/nopic.png{/if}"/>
                </label>





            </td>

        </tr>
        <tr>


            <td  class="label">{t}Left margin{/t}


            </td>


            <td><span class="margins_container unselectable  " data-scope="left_menu_text_padding">
                    <i class="fa fa-minus-circle down_margins button"></i> <input class="x edit_margin" value="{if !empty($mobile_style_values['left_menu_text_padding'])}{$mobile_style_values['left_menu_text_padding']}{else}75{/if}">
                    <i class="fa fa-plus-circle up_margins button"></i></span>
            </td>



            </td>

        </tr>
        <tr>


            <td  class="label">{t}Text{/t}


            </td>

            <td >
                <input id="left_menu_text" value="{if !empty($settings['left_menu_text'])}{$settings['left_menu_text']}{else}{$website->get('Website Name')}{/if}">



            </td>

        </tr>






    </table>
</div>



<script>

    function toggle_device(element) {
        if (!$(element).hasClass('fa-desktop')) {

            $('#control_panel_mobile').removeClass('hide')
            $('#control_panel_desktop').addClass('hide')

            $('#preview_container').addClass('hide')
            $('#mobile_preview_container').removeClass('hide')

            $('#preview_mobile')[0].contentWindow.delay_open_menu()


        } else {
            $('#control_panel_mobile').addClass('hide')
            $('#control_panel_desktop').removeClass('hide')
            $('#preview_container').removeClass('hide')
            $('#mobile_preview_container').addClass('hide')

        }


    }



    $(document).on('input propertychange', '#left_menu_text', function () {

        $('#save_button_mobile').addClass('save button changed valid')


        $('#preview_mobile').contents().find('.sidebar-header-image .sidebar-logo strong').html($(this).val())

    });


    $('.up_margins').on( "click", function() {
        $('input',$(this).closest('.margins_container')).each(function( index,input ) {



            value=parseInt($(input).val())

            if(isNaN(value)){
                value=0;
            }

            //  console.log(value)

            $(input).val( value+1)

            //  console.log($(input))

            change_margins(input)
        })

    });


    $('.down_margins').on( "click", function() {
        $('input',$(this).closest('.margins_container')).each(function( index,input ) {


            value=parseInt($(input).val())

            if(isNaN(value)){
                value=0;
            }

            console.log(value)

            value=value-1
            if(value<0){
                value=0;
            }


            $(input).val( value)
            change_margins(input)
        })

    });

    function change_margins(input) {

        if (!validate_signed_integer($(input).val(), 1000)) {
            $(input).removeClass('error')
            var value = $(input).val()

        } else {
            value = 0;

            $(input).addClass('error')
        }

        var element = $(input).closest('.element_for_margins').data('element')
        var scope = $(input).closest('.margins_container').data('scope')

        console.log(scope)

        switch (scope) {

            case 'left_menu_text_padding':


                var max_width=$('#preview_mobile').contents().find('.sidebar-header-image .sidebar-logo').width()

                console.log(max_width)
                console.log(value)
                // console.log($('#'+key).width())

                if(max_width<value){
                    $(input).addClass('error')

                }else{
                    $(input).removeClass('error')
                    var left = value + 'px'

                    $('#preview_mobile').contents().find('.sidebar-header-image .sidebar-logo strong').css('padding-left',left)
                }

                break;
                break


            default:
        }


        // element.css(scope+'-'+$(input).data('margin'), value + "px")


        $('#save_button_mobile').addClass('save button changed valid')

    }


</script>


<script>


    function edit_next_column(element){





        var next_key=$('#edit_mode_'+$('#exit_edit_column').attr('key')).next().attr('key');

      //  var next_key=parseFloat($('#exit_edit_column').attr('key'))+1
        exit_edit_column($('#exit_edit_column'))
        var next= $('#edit_mode_'+next_key)

        console.log(next_key)



        if(next.length){
            edit_column(next_key)
        }else{
            edit_column( $('#edit_columns .edit_mode:first').attr('key'))
        }
    }

    function edit_prev_column(element){

        //var prev_key=parseFloat($('#exit_edit_column').attr('key'))-1

        var prev_key=$('#edit_mode_'+$('#exit_edit_column').attr('key')).prev().attr('key');

        exit_edit_column($('#exit_edit_column'))

        var prev= $('#edit_mode_'+prev_key)
        if(prev.length){


            edit_column(prev_key)
        }else{

//console.log( $('#edit_columns .edit_mode:last'))

            edit_column( $('#edit_columns .edit_mode:last').attr('key'))

        }


    }




    $(document).on('click', '.column_show', function (e) {

        if($(this).hasClass('fa-eye')){
            var key=$(this).removeClass('fa-eye').addClass('fa-eye-slash').closest('li').addClass('very_discreet hidden_menu').attr('key')





            $('#preview')[0].contentWindow.hide_column_label(key);

            $('#preview')[0].contentWindow.hide_column(key);

        }else{
            var key=$(this).addClass('fa-eye').removeClass('fa-eye-slash').closest('li').removeClass('very_discreet hidden_menu').attr('key')
            $('#preview')[0].contentWindow.show_column_label(key);





           // $('#preview')[0].contentWindow.show_column(key);
        }


        $('#save_button').addClass('save button changed valid')


    })

    $(document).on('click', '.open_edit', function (e) {

        var key=$(this).closest('li').attr('key')

        if($(this).closest('li').find('.column_show').hasClass('fa-eye')){
            edit_column(key)
        }



    })

    $(document).on('click', '.change_type', function (e) {


        if( $('#three_columns_options').hasClass('hide')){
            var key=$(this).closest('li').attr('key')

            $('#three_columns_options').removeClass('hide').offset({
                top:$(this).offset().top-40 ,
                left:$(this).offset().left+$(this).width()+5    }).attr('subkey',$(this).closest('li').attr('key')).attr('key',$(this).closest('.edit_mode').attr('key')).data('element',$(this))
        }else{
            $('#three_columns_options').addClass('hide')
        }

    })

    $(document).on('click', '.page', function (e) {


        if( $('#cataloge_page_options').hasClass('hide')){
            var key=$(this).closest('li').attr('key')

            $('#cataloge_page_options').removeClass('hide').offset({
                top:$(this).offset().top-40 ,
                left:$(this).offset().left+$(this).width()+5    }).attr('subkey',$(this).closest('li').attr('key')).attr('key',$(this).closest('.edit_mode').attr('key')).data('element',$(this))
        }else{
            $('#cataloge_page_options').addClass('hide')
        }

    })

    $(document).on('click', '.column_type', function (e) {




        if( $('#column_type_options').hasClass('hide')){
            var key=$(this).closest('li').attr('key')


            $('#column_type_options ')


            $('#column_type_options').removeClass('hide').offset({
                top:$(this).offset().top-40 ,
                left:$(this).offset().left+$(this).width()+5    }).attr('key',$(this).closest('.edit_mode').attr('key'))

            $('#column_type_options td').removeClass('hide')
            $('#column_type_options td.column_type_'+$(this).data('column_type')).addClass('hide')





        }else{
            $('#column_type_options').addClass('hide')
        }

    })

    $(document).on('click', '#three_columns_options td', function (e) {

        if($(this).data('type')!=undefined) {

            $('#preview')[0].contentWindow.change_column($(this).data('type'), $('#three_columns_options').attr('key'), $('#three_columns_options').attr('subkey'));

            var li=$('#three_columns_options').data('element').closest('li')
            li.find('.column_type_label').html($(this).html())

            if($(this).data('type')=='departments' || $(this).data('type')=='families' || $(this).data('type')=='web_departments' || $(this).data('type')=='web_families'    ){

                if(li.find('.page').length==0){
                    li.find('.column_type_label').append('<span class="page button">1-10</span>')
                }else{
                    li.find('.page').html('1-10')
                }

            }else{
                li.find('.page').remove()
            }


            $('#three_columns_options').addClass('hide')
        }

    })

    $(document).on('click', '#cataloge_page_options td', function (e) {

        if($(this).data('type')!=undefined) {

            $('#preview')[0].contentWindow.edit_catalogue_paginator($(this).data('type'),$(this).html(), $('#cataloge_page_options').attr('key'), $('#cataloge_page_options').attr('subkey'));
            $('#cataloge_page_options').addClass('hide')
            $('#cataloge_page_options').data('element').html($(this).html())
        }

    })

    $(document).on('click', '#column_type_options td', function (e) {





        var col_type_element=$('#edit_mode_'+$('#column_type_options').attr('key')).find('.column_type')

        if(!col_type_element.hasClass($(this).data('type'))){

            if($(this).data('type')=='single_column') {
                var html='';

                col_type_element.removeClass('three_columns nothing').addClass('single_column')
                $('#edit_mode_'+$('#column_type_options').attr('key')).find('.column_type_label').html('1')
            }else if($(this).data('type')=='nothing') {
                var html='';

                col_type_element.removeClass('three_columns single_column').addClass('nothing')
                $('#edit_mode_'+$('#column_type_options').attr('key')).find('.column_type_label').html('0')
            }else if($(this).data('type')=='three_columns') {
                var html='<ul class="sortable unselectable columns">' +
                    '<li class="column_label"  key="0" ><i class="far fa-hand-rock handle" style="margin-right:10px" aria-hidden="true"></i>  <span class="column_type_label">{t}Empty{/t}</span> <i class="fa fa-recycle button change_type" style="margin-left:10px" aria-hidden="true"></i></li>' +
                    '<li class="column_label"  key="1" ><i class="far fa-hand-rock handle" style="margin-right:10px" aria-hidden="true"></i>  <span class="column_type_label">{t}Empty{/t}</span> <i class="fa fa-recycle button change_type" style="margin-left:10px" aria-hidden="true"></i></li>' +
                    '<li class="column_label"  key="2" ><i class="far fa-hand-rock handle" style="margin-right:10px" aria-hidden="true"></i>  <span class="column_type_label">{t}Empty{/t}</span> <i class="fa fa-recycle button change_type" style="margin-left:10px" aria-hidden="true"></i></li>' +
                    '</ul>';
                col_type_element.removeClass('single_column nothing').addClass('three_columns')
                $('#edit_mode_'+$('#column_type_options').attr('key')).find('.column_type_label').html('3')

            }



            $('#preview')[0].contentWindow.edit_column_type($(this).data('type'), $('#column_type_options').attr('key'));

            $('#column_type_options').addClass('hide')


            $('#edit_mode_'+$('#column_type_options').attr('key')).find('.column_controls').html(html)


            $('#edit_mode_'+$('#column_type_options').attr('key')).find('.column_type').data('column_type',$(this).data('type'))


            $('.sortable').sortable({
                handle:'.handle',
                start: function (event, ui) {
                    pre = ui.item.index();
                }, stop: function (event, ui) {

                    post = ui.item.index();
                    $('#preview')[0].contentWindow.move_column($('#exit_edit_column').attr('key'),pre,post);
                }
            });

        }



    })





    function edit_column(key) {




        $('#columns').addClass('hide')
        $('#edit_columns').removeClass('hide')

        $('.edit_mode').addClass('hide')
        $('#edit_mode_'+key).removeClass('hide')


        $('#exit_edit_column').attr('key',key)

        $('#preview')[0].contentWindow.show_column(key);




        $('.options_dialog').addClass('hide')




    }

    function exit_edit_column(element) {
        $('#columns').removeClass('hide')
        $('#edit_columns').addClass('hide')


        $('.options_dialog').addClass('hide')


        $('#preview')[0].contentWindow.hide_column($(element).attr('key'));


    }

    $('.sortable').sortable({
        handle:'.handle',
        start: function (event, ui) {
            pre = ui.item.index();
        }, stop: function (event, ui) {

            post = ui.item.index();
            $('#preview')[0].contentWindow.move_column($('#exit_edit_column').attr('key'),pre,post);
        }
    });

    $('.sortable2').sortable({
        handle:'.handle2',
        start: function (event, ui) {
            pre = ui.item.index();
        }, stop: function (event, ui) {

            post = ui.item.index();

            console.log(pre+' '+post)


            if (post > pre) {


                $('#edit_mode_container .edit_mode:eq(' + pre + ')').insertAfter('#edit_mode_container .edit_mode:eq(' + post + ')');
            } else {


                $('#edit_mode_container .edit_mode:eq(' + pre + ')').insertBefore('#edit_mode_container .edit_mode:eq(' + post + ')');
            }


            $('#preview')[0].contentWindow.move_column_label(pre,post);
        }


    });

    function add_header_column(){


        var key=$('#add_column_control_anchor').data('key')

      var label='{t}Column{/t} '+key

        $('<li  id="_column_'+key+'" class="column" style="" class="button unselectable " key="'+key+'">\n' + '<span class="button open_edit">\n' + '<i class="fa fa-fw fa-ban error very_discreet "  aria-hidden="true" title="'+label+'"></i>\n' + '<span class="label ">'+label+'</span>\n' + '            </span>\n' + '            <i class="fa fa-eye column_show" style="margin-left:10px;" aria-hidden="true"></i>\n' + '            <i class="fa fa-arrows handle2"  aria-hidden="true"></i>\n' + '            </li>').insertBefore( "#add_column" );
        $('<div id="edit_mode_'+key+'" class=" edit_mode hide"  type="single_column" key="'+key+'"> <div style="float:left;margin-right:20px;min-width: 200px;"> <span class="column_type button single_column" data-column_type="single_column"  style="border:1px solid #ccc;padding:4px;;margin-left:5px"><span class="column_type_label">1</span> <i class="fa fa-bars button  " aria-hidden="true"></i> &nbsp; </span> </div> <span class="column_controls"> </span> </div>').insertBefore( "#add_column_control_anchor" );

        $('#preview')[0].contentWindow.add_column(key,label);

    }


    $(document).on('click', '.delete_column', function (e) {
        var key= $(this).closest('.edit_mode').attr('key')



       // edit_next_column(key)

        $('#edit_mode_'+key).remove();
        $('#_column_'+key).remove();



        $('#preview')[0].contentWindow.delete_column(key);



    })


    function change_menu_label(key,label){
        $('#_column_'+key).find('.label').html(label)
        $('#_column_'+key).find('.open_edit i').attr('title',label)
    }


    function change_menu_icon(key, icon) {
        element = $('#_column_' + key + ' .open_edit i')
        element.removeClass(function (index, className) {
            return (className.match(/\bfa-\S+/g) || []).join(' ');
        }).removeClass('fa fab fas far fal').addClass('fa-fw').addClass(icon)


        if(icon=='fa fa-ban'){
            element.addClass('error very_discreet')
        }else{
            element.removeClass('error very_discreet')
        }

    }

</script>
