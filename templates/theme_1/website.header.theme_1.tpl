{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2018 at 13:29:26 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

{include file="theme_1/_head.theme_1.tpl"}

<style>


    .text_styles{
        width:auto;position:absolute;background: white;border:1px solid #ccc;
    }


    .text_styles td{
        padding: 5px 10px;
        border-bottom: 1px solid #ccc;cursor: pointer;
    }

    .header_text div,h1,h2,h3,h4,h5{
        display: inline;padding: 0px;margin: 0px;
    }

    .texts_list{
        margin-top:30px;
        margin-left: 20px;
        width: auto;
        border-bottom:1px solid #ccc


    }
    .texts_list tr{
        min-height: 32px;
    }

    .texts_list td{
        padding:4px 20px;
        border-top:1px solid #ccc;
        min-height: 32px;
        line-height: 34px;



    }

    .texts_list td.text{
        min-width: 300px;
    }


    .texts_list .text div,h1,h2,h3,h4,h5{
        display: inline;padding: 0px;margin: 0px;
    }

    .object_control_panel td{
        padding:0px 4px;
    }


    #main_settings{
        width: auto;margin-left: 20px;margin-top: 10px
    }



    #main_settings td{
        padding:4px 20px;




    }
    #aux{
        color:#555
    }

    input.edit_margin{
        width:100px}

    </style>

<script src="js/website_header.js?v=3"></script>
<script src="js/edit_webpage_upload_images_from_iframe.js?v2"></script>


<body xmlns="http://www.w3.org/1999/html">

<div class="wrapper_boxed">


    <div id="aux" >



    <table id="text_styles_main_header" class="text_styles hide" >
        <tr><td>H1++</td></tr>
        <tr><td>H1+</td></tr>
        <tr><td>H1</td></tr>
        <tr><td>H2</td></tr>
        <tr><td>H3</td></tr>
        <tr><td>N++</td></tr>
        <tr><td>N</td></tr>
        <tr><td>N-</td></tr>
    </table>

        <table id="text_styles_search_header" class="text_styles hide" >

            <tr><td>N</td></tr>
            <tr><td>N b</td></tr>
            <tr><td>N-</td></tr>
            <tr><td>N- b</td></tr>
        </table>




        <div id="color_picker_dialog" style="position:absolute;z-index: 6000" class="hide">
            <input type='text'  class="hide"  />

        </div>

    </div>



    <div class="site_wrapper ">


        <span id="webpage_data" style="display:none" data-website_key="{$website->id}"></span>


        <div id="top_header" style="width: 100%; display: flex;"  class="{$website->get('header_background_type')}" >
            <div id="header_logo" style="flex-grow:1;flex-grow: 0;flex-shrink: 0; border-right:1px dashed #ccc;flex-grow: 0;flex-shrink: 0; ;text-align: center">


                <input style="display:none" type="file" name="logo" id="update_image_logo" class="image_upload_from_iframe"
                       data-parent="Website"  data-parent_key="{$website->id}"  data-parent_object_scope="Logo"  data-metadata=""  data-options=""  data-response_type="website" />

                <label style="cursor: pointer" for="update_image_logo">
                    <img id="website_logo" style="max-height: 100%;max-width:  100%;vertical-align: middle;" src="{if empty($settings['logo_website'])}http://via.placeholder.com/60x60{else}{$settings['logo_website']}{/if}"/>
                </label>


            </div>

            <div id="main_header" style="flex-grow:2;position: relative">
                {if isset($settings.search_texts)}
                {foreach from=$settings.header_texts key=key item=header_text}
                {assign 'key'  "u_id_`$key`" }
                    {if $header_text.type=='H1++'}
                    <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                        <h1 class="huge" type="{$header_text.type}">{$header_text.text}</h1>
                    </div>
                    {elseif $header_text.type=='H1+'}
                        <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                            <h1 class="big" type="{$header_text.type}">{$header_text.text}</h1>
                        </div>
                    {elseif $header_text.type=='H1'}
                        <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                            <h1 type="{$header_text.type}">{$header_text.text}</h1>
                        </div>
                    {elseif $header_text.type=='H2'}
                        <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                            <h2 type="{$header_text.type}">{$header_text.text}</h2>
                        </div>
                    {elseif $header_text.type=='H3'}
                        <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                            <h3  type="{$header_text.type}">{$header_text.text}</h3>
                        </div>
                    {elseif $header_text.type=='N++'}
                        <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                            <span  type="{$header_text.type}">{$header_text.text}</span>
                        </div>
                    {elseif $header_text.type=='N'}
                        <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                            <span  type="{$header_text.type}">{$header_text.text}</span>
                        </div>
                    {elseif $header_text.type=='N-'}
                        <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                            <small  type="{$header_text.type}">{$header_text.text}</small>
                        </div>
                    {/if}
                {/foreach}
                {/if}

            </div>

            <div id="buffer_zone" style="flex-grow:1;text-align: right;flex-grow: 0;flex-shrink: 0; flex-basis:100px;border-left:1px dashed #ccc;border-right:1px dashed #ccc;color:#ccc;opacity:.5;text-align: center" >
                <div style="padding-top:20px">{t}Buffer zone{/t}</div>
                </div>


            <div id="search_header" style="padding-top:5px;flex-grow:1;text-align: right;flex-grow: 0;flex-shrink: 0; flex-basis:350px;position: relative" >


                <div id="search_hanger" style="position: absolute;left:10px;top:{if isset($settings.search_top)}{$settings.search_top}{else}0{/if}px"><input /> <i class="button fa fa-search"></i></div>

                {if isset($settings.search_texts)}
                    {foreach from=$settings.search_texts key=key item=header_text}
                        {assign 'key'  "su_id_`$key`" }
                        {if $header_text.type=='N b'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <span class="bold" type="{$header_text.type}">{$header_text.text}</span>
                            </div>
                        {elseif $header_text.type=='N- b'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <small  class="bold" type="{$header_text.type}">{$header_text.text}</small>
                            </div>
                        {elseif $header_text.type=='N'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <span type="{$header_text.type}">{$header_text.text}</span>
                            </div>
                        {elseif $header_text.type=='N-'}
                            <div id="{$key}" class="header_text" data-link="{$header_text.link}" style="position: absolute;left:{$header_text.left}px;top:{$header_text.top}px;color:{$header_text.color}">
                                <small type="{$header_text.type}">{$header_text.text}</small>
                            </div>
                        {/if}
                    {/foreach}
                {/if}


            </div>

        </div>


        <div id="bottom_header" >




            <div class="control_panel" onclick="open_menu_style()">

                <a id="header_order_totals" href="basket.sys" class="button" style="opacity: .2">
                    <span class="ordered_products_number">0</span>
                    <i style="padding-right:5px;padding-left:5px" class="fa fa-shopping-cart fa-flip-horizontal  " title="{if empty($labels._Basket)}{t}Basket{/t}{else}{$labels._Basket}{/if}" aria-hidden="true"></i>
                    <span class="order_amount" style="padding-right:10px" title="">{$zero_money}</span>
                </a>

                <a id="favorites_button" href="favourites.sys" class="button" style="opacity: .2">
                    <i class=" far fa-heart fa-flip-horizontal  " title="{if empty($labels._Favourites)}{t}My favourites{/t}{else}{$labels._Favourites}{/if}" aria-hidden="true"></i>
                </a>

                <a id="profile_button" href="profile.sys" class="button" style="opacity: .2"><i class="far fa-user fa-flip-horizontal  " title="{t}Profile{/t}" aria-hidden="true"></i>
                    <span>{if empty($labels._Profile)}{t}Profile{/t}{else}{$labels._Profile}{/if}</span></a>

                <a href="#" id="logout" class="button" style="opacity: .2">
                    <i class="far fa-sign-out-alt fa-flip-horizontal  " title="{t}Log out{/t}" aria-hidden="true"></i>
                    <span>{if empty($labels._Logout)}{t}Log out{/t}{else}{$labels._Logout}{/if}</span>
                </a>


            </div>



        </div>


        <div id=body" style="min-height: 500px;border-top:1px solid #ccc"  >


            <table id="main_settings" >


                <tr>


                    <td class="label">{t}Logo width{/t}
                        <span class="padding_left_10 margins_container unselectable border border-width" data-scope="logo_width">
                            <i class="fa fa-minus-circle  like_button down_margins"></i>
                        <input   class="logo_width edit_margin " value=""  placeholder="0">
                        <i class="fa fa-plus-circle  like_button up_margins"></i>



                    </span>
                    </td>

                    <td class="label">{t}Header height{/t}
                    <span class="padding_left_10 margins_container unselectable border border-width" data-scope="header_height">

                        <i class="fa fa-minus-circle  like_button down_margins"></i>
                        <input   class="header_height edit_margin " value=""  placeholder="0">
                        <i class="fa fa-plus-circle  like_button up_margins"></i>



                    </span>
                    </td>

                    <td id="" class="label">{t}Header background{/t}
                    <span data-scope="header_background-color" class="fa-stack color_picker scope_header_background-color like_button">
                         <i class="fas fa-circle fa-stack-1x "></i>
                         <i class="fas fa-circle fa-stack-1x "></i>
                    </span>




                    </td>
                    <td>

                        <div  class="background_editor header_background">
                            <input style="display:none" type="file" name="header_background" id="update_header_background" class="image_upload_from_iframe"
                                   data-parent="Website"  data-parent_key="{$website->id}"  data-parent_object_scope="header_background"  data-metadata=""  data-options=""  data-response_type="website" />

                            <label style="cursor: pointer" for="update_header_background">
                                <i class="add_background fa fa-image {if $styles['#top_header background-image'][2]=='none'}very_discreet{/if}"></i>
                            </label>

                            <i style="margin-left:10px;padding: 0px 10px" onclick="change_background_type(this)" data-element="#top_header" data-type="{$website->get('header_background_type')}" class="unselectable button fa-fw background_type {$website->settings('header_background_type')}  {if $styles['#top_header background-image'][2]=='none'}hide{/if}  fal {$website->get('header_background_icon')} "></i>
                            <i style="margin-left:10px;padding: 0px 10px" onclick="delete_background(this)" data-element="#top_header" class="button background_delete {if $styles['#top_header background-image'][2]=='none'}hide{/if}  fal fa-trash-alt "></i>
                        </div>




                    </td>


                </tr>






            </table>



            <table  id="header_texts_list"  class="texts_list" >
                {if isset($settings.header_texts)}
                {foreach from=$settings.header_texts key=key item=header_text}
                   {assign 'key'  "u_id_`$key`" }
                    <tr id="header_text_{$key}" data-key="{$key}">
                        <td><i onclick="delete_header_text(this)" class="fa fa-trash-alt like_button"></i></td>
                        <td><span class="margins_container unselectable  " data-scope="position_x"><i class="fa fa-minus-circle down_margins"></i> <input class="x edit_margin" value="{$header_text.left}"> <i class="fa fa-plus-circle up_margins"></i></span></td>
                        <td><span class="margins_container unselectable  " data-scope="position_y"><i class="fa fa-minus-circle down_margins"></i> <input class="y edit_margin" value="{$header_text.top}" > <i class="fa fa-plus-circle up_margins"></i></span></td>
                        <td class="style" ><i   onclick="open_header_text_edit_link(this)" class="link like_button fa {if !empty($header_text.link)} purple strong bold{else}very_discreet{/if}    fa-link"></i></td>
                        <td class="link_input hide"><i  onclick="open_header_text_edit_link(this)" class="like_button fa fa-window-close padding_right_10"></i> <input style="width: 300px" value="{$header_text.link}" placeholder="https://"/></td>
                        <td class="style"><span onclick="open_text_styles_main_header(this)" class="like_button type">{$header_text.type}</span></td>
                        <td class="style"><span id="header_text_color_{$key}" data-key="{$key}" data-type="header_text" data-scope="header_text_color_{$key}" class="fa-stack color_picker scope_header_color like_button"> <i class="fas fa-circle fa-stack-1x "></i> <i data-color="{$header_text.color}" style="color:{$header_text.color}" class="fas fa-circle fa-stack-1x "></i> </span></td>
                        <td class="style text"><span class="header_text_edit" contenteditable="true">{$header_text.text}</span></td>
                    </tr>
                {/foreach}
                {/if}
             <table>




                 <table  id="search_texts_list"  class="texts_list" >
                     {if isset($settings.search_texts)}
                     {foreach from=$settings.search_texts key=key item=header_text}
                         {assign 'key'  "su_id_`$key`" }
                         <tr id="header_text_{$key}" data-key="{$key}">
                             <td><i onclick="delete_header_text(this)" class="fa fa-trash-alt like_button"></i></td>
                             <td><span class="margins_container unselectable  " data-scope="position_x"><i class="fa fa-minus-circle down_margins"></i> <input class="x edit_margin" value="{$header_text.left}"> <i class="fa fa-plus-circle up_margins"></i></span></td>
                             <td><span class="margins_container unselectable  " data-scope="position_y"><i class="fa fa-minus-circle down_margins"></i> <input class="y edit_margin" value="{$header_text.top}" > <i class="fa fa-plus-circle up_margins"></i></span></td>
                             <td class="style" ><i onclick="open_header_text_edit_link(this)" class="link like_button fa {if !empty($header_text.link)} purple strong bold {else}very_discreet{/if}  fa-link"></i></td>
                             <td class="link_input hide"><i  onclick="open_header_text_edit_link(this)" class="like_button fa fa-window-close padding_right_10"></i> <input style="width: 300px" value="{$header_text.link}" placeholder="https://"/></td>
                             <td class="style"><span onclick="open_text_styles_search_header(this)" class="like_button type">{$header_text.type}</span></td>
                             <td class="style"><span id="header_text_color_{$key}" data-key="{$key}" data-type="header_text" data-scope="header_text_color_{$key}" class="fa-stack color_picker scope_header_color like_button"> <i class="fas fa-circle fa-stack-1x "></i> <i data-color="{$header_text.color}" style="color:{$header_text.color}" class="fas fa-circle fa-stack-1x "></i> </span></td>
                             <td class="style text"><span class="header_text_edit" contenteditable="true">{$header_text.text}</span></td>
                         </tr>
                     {/foreach}
                     {/if}

                     <table>


            <div class="clear" style="margin-bottom: 40px"></div>

        </div>




    </div>
</div>


<script>






    var pinned = false;

    var menu_open = false;
    var mouse_over_menu = false;
    var mouse_over_menu_link = false;


    $( "#bottom_header a" ).on( 'mouseenter', menu_in ).on( 'mouseleave', menu_out );
    $( "#bottom_header a" ).on( 'mouseenter', menu_in_fast ).on( 'mouseleave', menu_out_fast );
    $( "#_menu_blocks ._menu_block" ).on( 'mouseenter', menu_block_in ).on( 'mouseleave', menu_block_out );



    $('#_menu_blocks').width($('#top_header').width())


    function show_column(key) {

        pinned = true;

        $('._menu_block').addClass('hide')
        $('#menu_block_menu_' + key).removeClass('hide')

    }


    var styles={
    {foreach from=$website->style  item=style  }
            "{$style[0]} {$style[1]}":['{$style[0]}','{$style[1]}','{$style[2]}'],
    {/foreach}

    }


    console.log(styles['#top_header background-image'])

    function set_logo_position(){
        var height=$('#header_logo').height()
        var image_height=$('#header_logo img').height()


        if(height>image_height){
            $('#header_logo img').css('margin-top', 0.5*(height-image_height) + 'px')
        }else{
            $('#header_logo img').css('margin-top',   '0px')
        }


    }


</script>


</body>

</html>

