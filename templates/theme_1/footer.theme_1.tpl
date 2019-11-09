{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2017 at 17:35:41 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

{include file="theme_1/_head.theme_1.tpl"}
<style>

    .handle{
        cursor: move;
    }

    .footer_block .handle{
        position:absolute;top:-23px;left:20px
    }

    .footer_block.last .handle{
        left:94%;
    }

    .footer_block .recycler{
        position:absolute;top:-23px;left:20px
    }

    .footer_block.last .recycler{
       left:94%;
    }

    .button{
        cursor: pointer;
    }

    .invisible {
        visibility: hidden;

    }

    input.input_file {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .save {
        color: #0EBFE9;

    }

    .italic {
        font-style: italic
    }

    .control_panel {

        color: #444
    }

    .button {
        cursor: pointer

    }

    .editables_block {
        border: 1px solid transparent;
    }

    .editables_block:hover {
        border: 1px solid yellow;
    }

    .input_container {
        position: absolute;
        top: 60px;
        left: 10px;
        z-index: 100;
        border: 1px solid #ccc;
        background-color: white;
        padding: 10px 10px 10px 5px

    }

    .input_container input {
        width: 400px
    }

    .editing {
        color: yellow;
    }

    .add_link, .add_item {
        opacity: .7;cursor: pointer;
    }

    a:hover{
        text-decoration: none;
    }

    .qlinks:hover .add_link, .address:hover .add_item {
        opacity: 1;
        -webkit-transition-duration: 500ms;
        transition-duration: 500ms;
    }

    .drag_mode, .block_mode {
        opacity: .7;
    }

    .drag_mode.on, .block_mode.on {
        opacity: 1;
    }

    #item_types div {

        padding: 5px 5px;
        cursor: pointer;
        text-align: center;
        float: left;
        width: 30px
    }

    #item_types div:hover i {
        color: #000
    }

    .block_type {
        padding: 10px 20px

    }

    .block_type div {

        opacity: .5;
        cursor: pointer;

    }

    .block_type div:hover {

        opacity: 1;

    }

    .block_type div.selected {

        opacity: 1;
        color: #333

    }

    #social_links_control_center div {

        margin-bottom: 2px;

    }

    .discreet_links_control_panel input {
        width: 250px

    }

</style>


<body>
<div class="wrapper_boxed">
    <div class="site_wrapper">
        <div class="clear "></div>


        <div id="aux">


            <div id="input_container_link" class="input_container link_url hide  " style="">
                <input value="" placeholder="{t}https://... or webpage code{/t}">
            </div>

            <div id="copyright_bundle_control_center" class="input_container link_url  hide " style="">

                <div style="margin-bottom:5px"><span onClick="update_copyright_bundle_from_dialog()" class="button"  style="position:relative;top:-5px"><i  class="button fa fa-fw fa-check" aria-hidden="true"></i> {t}Apply changes{/t}</span></div>


                <div><span>{t}Copyright owner{/t}</span> <input id="copyright_bundle_control_center_owner" value="" placeholder="{t}name{/t}"></div>

                <div style="border-bottom:1px solid #ccc;margin-bottom:5px">
                    {t}Links{/t}
                </div>

                <div class="discreet_links_control_panel">
                    <div class="copyright_link"><input class="label" value="" placeholder="{t}Link label{/t}"> <input class="url" value="" placeholder="{t}https://... or page code{/t}"></div>
                    <div class="copyright_link"><input class="label" value="" placeholder="{t}Link label{/t}"> <input class="url" value="" placeholder="{t}https://... or page code{/t}"></div>
                    <div class="copyright_link"><input class="label" value="" placeholder="{t}Link label{/t}"> <input class="url" value="" placeholder="{t}https://... or page code{/t}"></div>
                    <div class="copyright_link"><input class="label" value="" placeholder="{t}Link label{/t}"> <input class="url" value="" placeholder="{t}https://... or page code{/t}"></div>
                    <div class="copyright_link"><input class="label" value="" placeholder="{t}Link label{/t}"> <input class="url" value="" placeholder="{t}https://... or page code{/t}"></div>

                </div>
            </div>

            <div id="social_links_control_center" class="input_container link_url hide  " style="">

                <div style="margin-bottom:5px"><span onClick="update_social_links_from_dialog()" class="button"  style="position:relative;top:-5px"><i  class="button fa fa-fw fa-check" aria-hidden="true"></i> {t}Apply changes{/t}</span></div>

                <div><i icon="fa-facebook" class="button social_link fab fa-fw fa-facebook" aria-hidden="true"></i> <input value="" placeholder="https://... Facebook"></div>
                <div><i icon="fa-google-plus" class="button social_link fab fa-fw fa-google-plus" aria-hidden="true"></i> <input value="" placeholder="https://... Google +"></div>
                <div><i icon="fa-instagram" class="button social_link fab fa-fw fa-instagram" aria-hidden="true"></i> <input value="" placeholder="https://... Instagram"></div>
                <div><i icon="fa-linkedin" class="button social_link fab fa-fw fa-linkedin" aria-hidden="true"></i> <input value="" placeholder="https://... Linkedin"></div>
                <div><i icon="fa-pinterest" class="button social_link fab fa-fw fa-pinterest" aria-hidden="true"></i> <input value="" placeholder="https://... Pinterest"></div>
                <div><i icon="fa-snapchat" class="button social_link fab fa-fw fa-snapchat" aria-hidden="true"></i> <input value="" placeholder="https://... Snapchat"></div>
                <div><i icon="fa-twitter" class="button social_link fab fa-fw fa-twitter" aria-hidden="true"></i> <input value="" placeholder="https://... Twitter"></div>
                <div><i icon="fa-vk" class="button social_link fab fa-fw fa-vk" aria-hidden="true"></i> <input value="" placeholder="https://... VK"></div>
                <div><i icon="fa-xing" class="button social_link fab fa-fw fa-xing" aria-hidden="true"></i> <input value="" placeholder="https://... Xing"></div>
                <div><i icon="fa-youtube" class="button social_link fab fa-fw fa-youtube" aria-hidden="true"></i> <input value="" placeholder="https://... Youtube"></div>


            </div>

            <div id="block_type_1" class="input_container block_type  hide" style="">


                <div onClick="change_block_type(this)" data-type="address" class="type_address"><span>{t}Items{/t} <span class="italic">({t}Contact info{/t})</span></span></div>
                <div onClick="change_block_type(this)" data-type="text" class="type_text"><span>{t}Text{/t} <span class="italic">({t}About us{/t})</span></span></div>
                <div onClick="change_block_type(this)" data-type="links" class="type_links"><span>{t}Links{/t}</span></div>
                <div onClick="change_block_type(this)" data-type="nothing" class="type_nothing"><span>{t}Nothing{/t}</span></div>

            </div>

            <div id="block_type_2" class="input_container block_type  hide" style="">
                <div onClick="change_block_type(this)" data-type="copyright_bundle" class="type_copyright_bundle"><span>{t}Copyright{/t}</span></div>
                <div onClick="change_block_type(this)" data-type="social_links" class="type_social_links"><span>{t}Social icons{/t}</span></div>
                <div onClick="change_block_type(this)" data-type="text" class="type_low_text"><span>{t}Text{/t}</span></div>
                <div onClick="change_block_type(this)" data-type="nothing" class="type_low_nothing"><span>{t}Nothing{/t}</span></div>

            </div>

            <div id="item_types" class="input_container  hide  " style="">
                <div icon="fa fa-map" onClick="add_item_type(this)"><i class="button fa fa-fw fa-map" aria-hidden="true" label="{t}My address{/t}"></i></div>
                <div icon="fa fa-map-marker" onClick="add_item_type(this)"><i class="button fa fa-fw fa-map-marker" aria-hidden="true" label="{t}My address{/t}"></i></div>

                <div icon="fa fa-building" onClick="add_item_type(this)"><i class="button fa fa-fw fa-building" aria-hidden="true" label="{t}My company name{/t}"></i></div>
                <div icon="fa fa-industry" onClick="add_item_type(this)"><i class="button fa fa-fw fa-industry" aria-hidden="true" label="{t}My company name{/t}"></i></div>
                <div icon="fa fa-balance-scale" onClick="add_item_type(this)"><i class="button fa fa-fw fa-balance-scale" aria-hidden="true" label="{t}Tax number{/t}"></i></div>

                <div icon="fa fa-phone" onClick="add_item_type(this)"><i class="button fa fa-fw  fa-phone" aria-hidden="true" label="+1-541-754-3010"></i></div>
                <div icon="fa fa-mobile" onClick="add_item_type(this)"><i class="button fa fa-fw fa-mobile" aria-hidden="true" label="+1-541-754-3010"></i></div>
                <div icon="fab fa-whatsapp" onClick="add_item_type(this)"><i class="button fab fa-fw fa-whatsapp" aria-hidden="true" label="+1-541-754-3010"></i></div>
                <div icon="fab fa-skype" onClick="add_item_type(this)"><i class="button fab fa-fw  fa-skype" aria-hidden="true" label="{t}Skype username{/t}"></i></div>
                <div icon="fa fa-envelope" onClick="add_item_type(this)"><i class="button fa fa-fw  fa-envelope" aria-hidden="true" label="info@yourdomain.com"></i></div>
                <div icon="fa fa-image" onClick="add_item_type(this)"><i class="button fa fa-fw  fa-image" aria-hidden="true" label=""></i></div>
                <div icon="fa fa-star" onClick="add_item_type(this)"><i class="button fa fa-fw fa-star" aria-hidden="true" label="{t}Custom text{/t}"></i></div>
                <div icon="fa fa-circle" onClick="add_item_type(this)"><i class="button fa fa-fw fa-circle" aria-hidden="true" label="{t}Custom text{/t}"></i></div>
                <div icon="far fa-circle" onClick="add_item_type(this)"><i class="button far fa-fw fa-circle" aria-hidden="true" label="{t}Custom text{/t}"></i></div>


            </div>


            <i id="delete_link" class="far fa-trash-alt hide editing button" aria-hidden="true" onClick="delete_link(this)" style="z-index:3000;position:absolute" title="{t}Remove link{/t}"></i>

            <i id="delete_item" class="far fa-trash-alt hide editing button"  aria-hidden="true" onClick="delete_item(this)" style="position:absolute;cursor: pointer;z-index: 10000" title="{t}Remove item{/t}"></i>




            <input style="display:none" type="file" name="footer" id="footer_image" class="image_upload_from_iframe"
                   data-parent="Website"  data-parent_key="{$website->id}"  data-parent_object_scope="Footer"  data-metadata='{ "footer_key":"{$footer_key}"}'  data-options=""  data-response_type="website"
            />
            <label id="change_image" class="hide" style="z-index:5000;position:absolute;top:0;left:0;"  for="footer_image">
                <i style="cursor:pointer;font-weight: normal;" class=" fa fa-image fa-fw button editing" aria-hidden="true" title="{t}Change image{/t}"></i>
            </label>



            <form id="change_imagex" class="hide" style="z-index:5000;position:absolute;top:0;left:0" method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                <input type="file" name="image_upload" id="file_upload" class="input_file" multiple/>
                <label for="file_upload">
                    <i style="cursor:pointer" class=" fa fa-image fa-fw button editing" aria-hidden="true" title="{t}Change image{/t}"></i>
                </label>
            </form>


            <ul class="hide">

                <li id="link_stem_cell" class="item"><a href="/"><i class="fa fa-fw fa-angle-right link_icon" onClick="update_link(this)"></i> <span ondrop="return false;" contenteditable="true"
                                                                                                                                                     class="item_label">{t}New link{/t}<span></span></a></li>

                <li id="item_email_stem_cell"><i class="fa fa-fw fa-envelope"></i> <span contenteditable="true">info@yourdomain.com</span></li>
                <li id="item_stem_cell"><i class="fa-fw "></i> <span contenteditable="true"></span></li>
                <li id="item_image_stem_cell"><img onclick="edit_item_image(this)" src="theme_1/images/footer-wmap.png" alt="" title=""/></li>


            </ul>


            <div id="block_copyright_bundle_stem_cell" class="hide">


                <i class="far fa-hand-rock editing hide handle" aria-hidden="true" ></i>
                <i onclick="open_block_type_options(this,'block_type_2','block_type_2')" class="fa fa-recycle editing  button recycler"  ></i>


                <div onClick="edit_copyright_bundle(this)" class="copyright_bundle ">
                    <small  >
                    {t}Copyright{/t} © {"%Y"|strftime} <span class="copyright_bundle_owner">Aurora</span>. {t}All rights reserved{/t}. <span class="copyright_bundle_links"> <a class="copyright_bundle_link" href="/"> Terms of Use</a> | <a
                                class="copyright_bundle_link" href="/"> Privacy Policy</a></span>
                    </small>
                </div>
            </div>
            <div id="block_low_text_stem_cell" class="hide">

                <i class="far fa-hand-rock editing hide handle" aria-hidden="true" ></i>
                <i onclick="open_block_type_options(this,'block_type_2','block_type_2')" class="fa fa-recycle editing  button recycler"  ></i>

                <div class="text">
                <span  class="lower_footer_text" contenteditable="true">{t}text{/t}</span>
                </div>
            </div>

            <div id="block_social_links_stem_cell" class="hide">


                <i class="far fa-hand-rock editing hide handle" aria-hidden="true" ></i>
                <i onclick="open_block_type_options(this,'block_type_2','block_type_2')" class="fa fa-recycle editing  button recycler"  ></i>


                <ul onClick="edit_social_links(this)" class="footer_social_links">

                    <li class="social_link" icon="fa-facebook"><a href="/"><i class="fab fa-facebook"></i></a></li>
                    <li class="social_link" icon="fa-twitter"><a href="/"><i class="fab fa-twitter"></i></a></li>
                    <li class="social_link" icon="fa-linkedin"><a href="/"><i class="fab fa-linkedin"></i></a></li>


                </ul>
            </div>

            <div id="block_text_stem_cell" class="hide">

                <div class="footer_block about_us ui-sortable-handle"  data-type="text" style="position: relative">


                    <i class="far fa-hand-rock editing hide handle" aria-hidden="true" style="position:absolute;top:-5px;left:30px"></i>
                    <i onclick="open_block_type_options(this,'block_type_1','text')" class="fa fa-recycle editing  button recycler" aria-hidden="true" style="position:absolute;top:-23px;left:0px"></i>

                    <h5  class="for_text" contenteditable="true">{t}About us{/t}</h5>

                    <div class="footer_text" contenteditable="true">
                        <p>
                            All the Lorem Ipsum generators on the Internet tend to repeat predefined </p><br/>
                        <p>
                            An chunks as necessary, making this the first true generator on the Internet. Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover desktop publishing packages
                            many purpose web sites. </p>
                    </div>
                </div>
            </div>


            <div id="block_links_stem_cell" class="hide">
                <div class="footer_block ui-sortable-handle"  data-type="links" style="position: relative">

                    <i class="far fa-hand-rock editing hide handle" aria-hidden="true" style="position:absolute;top:-5px;left:30px"></i>
                    <i onclick="open_block_type_options(this,'block_type_1','text')" class="fa fa-recycle editing  button recycler" aria-hidden="true" style="position:absolute;top:-23px;left:40px"></i>


                    <h5  contenteditable="true">{t}Useful Links{/t}</h5>
                    <ul class="links_list">
                        <li class="item"><a href="/"><i class="fa fa-fw fa-angle-right link_icon" onClick="update_link(this)"></i> <span class="item_label" ondrop="return false;"
                                                                                                                                         contenteditable="true">{t}Home Page Variations{/t}<span></span></a></li>
                        <li class="item"><a href="/"><i class="fa fa-fw fa-angle-right link_icon" onClick="update_link(this)"></i> <span class="item_label" ondrop="return false;" contenteditable>{t}Awesome Products{/t}
                                    <span></span></a></li>
                        <li class="item"><a href="/"><i class="fa fa-fw fa-angle-right link_icon" onClick="update_link(this)"></i> <span class="item_label" ondrop="return false;"
                                                                                                                                         contenteditable="true">{t}Features and Benefits{/t}<span></span></a></li>
                        <li onClick="add_link(this)" class="ui-state-disabled add_link"><a href="/"><i class="fa fa-fw fa-plus editing link_icon" )"></i> <span class="editing" ondrop="return false;">{t}Add link{/t}
                                    <span></span></a></li>
                    </ul>

                </div>


            </div>

            <div id="block_nothing_stem_cell" class="hide">


                <div class="footer_block ui-sortable-handle"  data-type="nothing" style="position: relative">
                    <i class="far fa-hand-rock editing hide handle" aria-hidden="true" style="position:absolute;top:-5px;left:30px"></i>
                    <i onclick="open_block_type_options(this,'block_type_1','nothing')" class="fa fa-recycle editing  button recycler" aria-hidden="true" style="position:absolute;top:-23px;left:0px"></i>


                </div>
            </div>
            <div id="block_low_nothing_stem_cell" class="hide">

                <i class="far fa-hand-rock editing hide handle" aria-hidden="true" style="position: relative"></i>
                <i onclick="open_block_type_options(this,'block_type_2','block_type_2')" class="fa fa-recycle editing  button recycler"  ></i>


            </div>



            <div id="block_items_stem_cell" class="hide">


                <div class="footer_block ui-sortable-handle"  data-type="address" style="position: relative">

                    <i class="far fa-hand-rock editing hide handle" aria-hidden="true" style="position:absolute;top:-5px;left:30px"></i>
                    <i onclick="open_block_type_options(this,'block_type_1','text')" class="fa fa-recycle editing  button recycler" aria-hidden="true" style="position:absolute;top:-23px;left:20px"></i>


                    <ul class="address">



                    <li class="item _logo"><img onclick="edit_item_image(this)" src="theme_1/images/footer-logo.png" alt=""/></li>

                    <li class="item"><i onclick="edit_item(this)" class="fa fa-fw fa-map-marker"></i> <span contenteditable="true">10 London Road, Oxford,  OX2 6RB, UK</span></li>

                    <li class="item"><i onclick="edit_item(this)" class="fa fa-fw fa-phone"></i> <span contenteditable="true">+1-541-754-3010</span></li>

                    <li class="item"><i onclick="edit_item(this)" class="fa fa-fw fa-envelope"></i> <span contenteditable="true">info@yourdomain.com</span></li>
                    <li class="item"><img onclick="edit_item_image(this)" src="theme_1/images/footer-wmap.png" alt=""/></li>


                    <li onClick="add_item(this)" class="button add_item">
                        <i class="fa fa-fw fa-plus editing "></i> <span class="editing" ondrop="return false;">{t}Add item{/t}<span></span>
                    </li>
                </ul>
            </div>

        </div>

        </div>

        <footer>


            {foreach from=$footer_data.rows item=row}

                {if $row.type=='main_4'}
                    <div class="text_blocks  top_header text_template_4 sortable_container " >


                        {foreach from=$row.columns item=column name=main_4}





                            {if $column.type=='address'}
                                <div class="footer_block" data-type="{$column.type}" style="position: relative">


                                    <i class="far fa-hand-rock editing hide handle" aria-hidden="true"></i>
                                    <i onclick="open_block_type_options(this,'block_type_1','{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" ></i>



                                    <ul class="address " style="">
                                        {foreach from=$column.items item=item }
                                            {if $item.type=='logo'}
                                                <li class="item _logo"><img  onclick="edit_item_image(this)" src="{$item.src}" alt="" title="{$item.title}"/></li>
                                            {elseif $item.type=='text'}
                                                <li class="item _text"  icon="{$item.icon}"><i onclick="edit_item(this)" class="fa-fw {$item.icon}"></i> <span  contenteditable="true">
                                          {if $item.text=='#tel' and  $store->get('Telephone')!=''}{$store->get('Telephone')}
                                          {elseif $item.text=='#email' and  $store->get('Email')!=''}{$store->get('Email')}
                                          {elseif $item.text=='#address' and  $store->get('Address')!=''}{$store->get('Address')}
                                          {else}{$item.text|strip_tags}{/if}
                                      </span></li>
                                            {elseif $item.type=='email'}
                                                <li class="item _email"><i onclick="edit_item(this)" class="fa fa-fw fa-envelope"></i> <span contenteditable="true">{if $item.text=='#email' and  $store->get('Email')!=''}{$store->get('Email')}{else}{$item.text|strip_tags}{/if}</span>



                                            {/if}
                                        {/foreach}
                                        <li onClick="add_item(this)"  class="button add_item"   >
                                            <i class="fa fa-fw fa-plus editing " ></i> <span class="editing" ondrop="return false;" >{t}Add item{/t}<span></span>
                                        </li>

                                    </ul>


                                </div>
                            {elseif $column.type=='links'}
                                <div class="footer_block" data-type="{$column.type}" style="position: relative">

                                    <i class="far fa-hand-rock editing hide handle" aria-hidden="true"></i>
                                    <i onclick="open_block_type_options(this,'block_type_1','{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" ></i>


                                    <h5  contenteditable="true">{$column.header}</h5>

                                    <ul class="links_list">
                                        {foreach from=$column.items item=item }
                                            <li class="item"><a href="{$item.url}"><i class="fa fa-fw fa-angle-right link_icon" onClick="update_link(this)" ></i><span class="item_label" contenteditable="true">{$item.label|strip_tags}</span></a></li>
                                        {/foreach}
                                        <li onClick="add_link(this)"  class="add_link "><a  href=""><i class="fa fa-fw fa-plus editing link_icon" "></i> <span class="editing" ondrop="return false;" >{t}Add link{/t}<span></span></a></li>


                                    </ul>


                                </div>
                            {elseif $column.type=='text'}
                                <div class=" footer_block  " data-type="{$column.type}" style="position: relative">
                                    <i class="far fa-hand-rock editing hide handle" aria-hidden="true" ></i>
                                    <i onclick="open_block_type_options(this,'block_type_1','{$column.type}')" class="fa fa-recycle editing  hide button recycler" aria-hidden="true" ></i>


                                    <h5 class="for_text" contenteditable="true">{$column.header}</h5>

                                    <div class="footer_text" contenteditable="true">
                                        {$column.text}
                                    </div>

                                </div>
                            {elseif $column.type=='nothing'}
                                <div class="footer_block" data-type="{$column.type}" style="position: relative">
                                    <i class="far fa-hand-rock editing hide handle" aria-hidden="true" ></i>
                                    <i onclick="open_block_type_options(this,'block_type_1','{$column.type}')" class="fa fa-recycle editing  hide button recycler" aria-hidden="true" ></i>


                                </div>

                            {/if}


                        {/foreach}

                    </div>
                {elseif $row.type=='copyright'}
                    <div class="text_blocks  bottom_header text_template_2 copyright sortable_container2" >
                        {foreach from=$row.columns item=column name=copyright_info}



                            {if $column.type=='text'}

                                    <div class="footer_block {if $smarty.foreach.copyright_info.last}last{/if} " data-type="text"  style="position: relative" >

                                        <i class="far fa-hand-rock editing hide handle" aria-hidden="true" ></i>
                                        <i onclick="open_block_type_options(this,'block_type_2','{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" ></i>


                                        <div class="text">
                                            <span  class="lower_footer_text" contenteditable="true">{$column.text}</span>
                                        </div>
                                    </div>

                            {elseif $column.type=='nothing'}

                                    <div class="footer_block {if $smarty.foreach.copyright_info.last}last{/if}"  data-type="nothing" style="position: relative">
                                        <i class="far fa-hand-rock editing hide handle" aria-hidden="true" ></i>
                                        <i onclick="open_block_type_options(this,'block_type_2','{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" ></i>


                                    </div>
                            {elseif $column.type=='copyright_bundle'}
                                <div class="footer_block {if $smarty.foreach.copyright_info.last}last{/if} " data-type="copyright_bundle" style="position: relative" >


                                    <i class="far fa-hand-rock editing hide handle" aria-hidden="true" ></i>
                                    <i onclick="open_block_type_options(this,'block_type_2','{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" ></i>

                                    <div onClick="edit_copyright_bundle(this)" class="copyright_bundle ">
                                    <small  >

                                        {t}Copyright{/t} © {"%Y"|strftime} <span class="copyright_bundle_owner" >{$column.owner}</span> {t}All rights reserved{/t}.
                                        <span class="copyright_bundle_links">{foreach  from=$column.links item=item name=copyright_links}<a class="copyright_bundle_link" href="{$item.url}">{$item.label}</a>{if !$smarty.foreach.copyright_links.last} | {/if}{/foreach}</span>
                                    </small>
                                    </div>

                                </div>
                            {elseif $column.type=='social_links'}



                                    <div class="footer_block {if $smarty.foreach.copyright_info.last}last{/if}"   data-type="social_links" style="position: relative" >

                                        <i class="far fa-hand-rock editing hide handle" aria-hidden="true" ></i>
                                        <i onclick="open_block_type_options(this,'block_type_2','{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" ></i>


                                        <ul onClick="edit_social_links(this)" class="footer_social_links">
                                            {foreach from=$column.items item=item}
                                                <li class="social_link" icon="{$item.icon}"><a href="{$item.url}"><i class="fab {$item.icon}"></i></a></li>
                                            {/foreach}
                                        </ul>
                                    </div>

                            {/if}


                        {/foreach}

                    </div>
                {/if}


            {/foreach}


        </footer>




    </div>
</div>

<script>


    $(document).on('click', 'a', function (e) {
        if (e.which == 1 && !e.metaKey && !e.shiftKey) {

            return false
        }
    })


    document.addEventListener("paste", function (e) {
        e.preventDefault();
        var text = e.clipboardData.getData("text/plain");
        document.execCommand("insertHTML", false, text);
    });




    $(document).on('input paste', '[contenteditable=true]', function (e) {
        $('#save_button', window.parent.document).addClass('save button changed valid')
    });




    var image = false;

    var current_editing_link_id = false;
    var current_editing_item_id = false

    function open_block_type_options(element, option_id, current_block_type) {


        var option_dialog = $('#' + option_id)

        var block = $(element).closest('.footer_block')

        block.uniqueId()
        var id = block.attr('id')




        if (!option_dialog.hasClass('hide') && option_dialog.attr('block_id') == id) {

            option_dialog.addClass('hide')
        } else {



            if( $(element).closest('.text_blocks').hasClass('copyright')  && $(element).closest('.footer_block').hasClass('last') ){



                option_dialog.removeClass('hide').offset({
                    top: $(element).offset().top - 5, left: $(element).offset().left - 20 -option_dialog.width()
                }).attr('block_id', id)

            }else{
                option_dialog.removeClass('hide').offset({
                    top: $(element).offset().top - 5, left: $(element).offset().left + 20
                }).attr('block_id', id)

            }



            $('#' + option_id + ' div').addClass('selected')


            option_dialog.find('.type_' + current_block_type).removeClass('selected')

        }

    }


    function change_block_type(element) {


        var block_type = $(element).closest('.block_type');

        console.log($(element))

        //$('#' + block_type.attr('block_id')).data('type',$(element).data('type'))

        if ($(element).hasClass('type_text')) {
            $('#' + block_type.attr('block_id')).replaceWith($('#block_text_stem_cell').html())
        } else if ($(element).hasClass('type_low_text')) {
            $('#' + block_type.attr('block_id')).html($('#block_low_text_stem_cell').html())
            $('#' + block_type.attr('block_id')).data('type','low_text')
            $('#' + block_type.attr('block_id')).attr('data-type','low_text')
        } else if ($(element).hasClass('type_social_links')) {
            $('#' + block_type.attr('block_id')).html($('#block_social_links_stem_cell').html())
            $('#' + block_type.attr('block_id')).data('type','social_links')
            $('#' + block_type.attr('block_id')).attr('data-type','social_links')



        } else if ($(element).hasClass('type_copyright_bundle')) {
            $('#' + block_type.attr('block_id')).html($('#block_copyright_bundle_stem_cell').html())

            $('#' + block_type.attr('block_id')).data('type','copyright_bundle')
            $('#' + block_type.attr('block_id')).attr('data-type','copyright_bundle')

        } else if ($(element).hasClass('type_links')) {
            $('#' + block_type.attr('block_id')).replaceWith($('#block_links_stem_cell').html())
        } else if ($(element).hasClass('type_address')) {
            $('#' + block_type.attr('block_id')).replaceWith($('#block_items_stem_cell').html())

            $('.address').sortable({
                disabled: false, items: "li:not(.ui-state-disabled)", connectWith: ".address"
            });


        } else if ($(element).hasClass('type_nothing')) {
            $('#' + block_type.attr('block_id')).replaceWith($('#block_nothing_stem_cell').html())
        } else if ($(element).hasClass('type_low_nothing')) {
            $('#' + block_type.attr('block_id')).html($('#block_low_nothing_stem_cell').html())
            $('#' + block_type.attr('block_id')).data('type','nothing')
            $('#' + block_type.attr('block_id')).attr('data-type','nothing')
        }


        $('.sortable_container').sortable({
            disabled: false, update: function (event, ui) {
                $(this).children().removeClass('last')
                $(this).children().last().addClass('last')


            }

        });

        block_type.addClass('hide')

        $('#save_button', window.parent.document).addClass('save button changed valid')
    }


    function add_item_type(element) {


        var icon = $(element).find('i')
        $('#item_types').addClass('hide')


        if (icon.hasClass('fa-image')) {

            var new_item = $("#item_image_stem_cell").clone()

            new_item.removeAttr("id")
            new_item.addClass('item _logo')


        } else {


            var new_item = $("#item_stem_cell").clone()


            new_item.removeAttr("id")
            new_item.addClass('item _text')

            new_item.attr("icon", $(element).attr('icon'))

            new_item.attr('onClick', 'edit_item(this)');


            new_item.find('span').html(icon.attr('label'));
            new_item.find('i').attr('class', icon.attr('class'))


        }


        new_item.insertBefore($('#' + $('#item_types').attr('anchor')));
        console.log('add_item_type')

    }

    function add_item(element) {


        if ($('#item_types').hasClass('hide')) {
            $(element).uniqueId()
            $('#item_types').removeClass('hide').offset({
                top: $(element).offset().top - 55, left: $(element).offset().left + 20
            }).attr('anchor', $(element).attr('id'))
        } else {
            $('#item_types').addClass('hide')

        }

    }


    function edit_item(element) {


        $(element).uniqueId()
        var id = $(element).attr('id')

        $('#change_image').addClass('hide')


        if ($('#delete_item').hasClass('hide')) {
            current_editing_item_id = id


            $('#delete_item').removeClass('hide').offset(
                { top: $(element).offset().top, left: $(element).offset().left - 20}).data('element', element)
        } else {


            if (current_editing_item_id == id) {
                $('#delete_item').addClass('hide')
            } else {
                current_editing_item_id = id
                $('#delete_item').removeClass('hide').offset({
                    top: $(element).offset().top, left: $(element).offset().left - 20}).data('element', element)
            }

        }

    }



    function edit_item_image(element) {
        $(element).uniqueId()
        var id = $(element).attr('id')

        if ($('#delete_item').hasClass('hide')) {
            current_editing_item_id = id

            $('#delete_item').removeClass('hide').offset({
                top: $(element).offset().top, left: $(element).offset().left - 20}).data('element', $(element))
            $('#change_image').removeClass('hide').offset({
                top: $(element).offset().top + 20, left: $(element).offset().left - 20}).data('element', $(element))

            //   $('#change_image').removeClass('hide')

        } else {
            if (current_editing_item_id == id) {
                $('#delete_item').addClass('hide')
                $('#change_image').addClass('hide')

            } else {
                current_editing_item_id = id
                $('#delete_item').removeClass('hide').offset({
                    top: $(element).offset().top, left: $(element).offset().left - 20}).attr('item_id', id)
                $('#change_image').removeClass('hide').offset({
                    top: $(element).offset().top + 20, left: $(element).offset().left - 20}).attr('item_id', id)

            }

        }

    }


    function update_link(element) {


        $(element).uniqueId()
        var id = $(element).attr('id')


        if ($('#input_container_link').hasClass('hide')) {
            current_editing_link_id = id

            $('#input_container_link').removeClass('hide').offset({ top: $(element).offset().top - 55, left: $(element).offset().left + 20}).find('input').val($(element).closest('a').attr("href"))
            $('#delete_link').removeClass('hide').offset({ top: $(element).offset().top, left: $(element).offset().left - 20}).attr('link_id', id).data('element', $(element))
            $(element).removeClass('fa-angle-right').addClass('editing fa-check-circle').next('span').addClass('editing')


        } else {


            $('#delete_link').data('element').closest('a').attr("href", $('#input_container_link').find('input').val())


            if (current_editing_link_id == id) {
                $('#input_container_link').addClass('hide')
                $('#delete_link').addClass('hide')
                $(element).addClass('fa-angle-right').removeClass('editing fa-check-circle').next('span').removeClass('editing')


            } else {


                $('#' + current_editing_link_id).addClass('fa-angle-right').removeClass('editing fa-check-circle').next('span').removeClass('editing')
                current_editing_link_id = id

                $('#input_container_link').removeClass('hide').offset({ top: $(element).offset().top - 55, left: $(element).offset().left + 20}).find('input').val($(element).closest('a').attr("href"))
                $('#delete_link').removeClass('hide').offset({ top: $(element).offset().top, left: $(element).offset().left - 15}).attr('link_id', id).data('element', $(element))
                $(element).removeClass('fa-angle-right').addClass('editing fa-check-circle').next('span').addClass('editing')

            }


        }


    }


    function drag_mode_on(element) {


        $('#delete_item').addClass('hide')
        $('#change_image').addClass('hide')


        $('.links_list').sortable({
            disabled: false, items: "li:not(.ui-state-disabled)", connectWith: ".links_list"
        });

        $('.address').sortable({
            disabled: false, items: "li:not(.ui-state-disabled)", connectWith: ".address"
        });

        $('.sortable_container').sortable({
            disabled: false,
            handle: '.handle',
            update: function (event, ui) {

                $('#save_button', window.parent.document).addClass('save button changed valid')

            }

        });


        $('.sortable_container2').sortable({
            disabled: false,
            handle: '.handle',
            update: function (event, ui) {
                $(this).children().removeClass('last')
                $(this).children().last().addClass('last')

                $('#save_button', window.parent.document).addClass('save button changed valid')
            }

        });



        $('.handle').removeClass('hide')


        $('.add_item').addClass('invisible')
        $('.add_link').addClass('invisible')
        $('.recycler').addClass('hide')


    }

    function block_edit_mode_on(element) {

        $('#delete_item').addClass('hide')
        $('#change_image').addClass('hide')

        $('.links_list').sortable({
            disabled: true
        });

        $('.address').sortable({
            disabled: true
        });

        $('.sortable_container').sortable({
            disabled: true

        });
        $('.handle').addClass('hide')
        $('.recycler').removeClass('hide')

        $('.add_item').addClass('invisible')
        $('.add_link').addClass('invisible')

    }


    function edit_mode_on(element) {


        $('.links_list').sortable({
            disabled: true
        });

        $('.address').sortable({
            disabled: true
        });

        $('.sortable_container').sortable({
            disabled: true

        });
        $('.handle').addClass('hide')
        $('.recycler').addClass('hide')

        $('.add_item').removeClass('invisible')
        $('.add_link').removeClass('invisible')

    }


    function add_link(element) {

        console.log(element)
        var ul = $(element).closest('ul');

        var new_data = $("#link_stem_cell").clone();

        console.log(new_data)

        new_data.insertBefore($(element));
    }


    function delete_link(element) {

        console.log(element)

        $('#' + $(element).attr('link_id')).closest('li').remove()
        $('#input_container_link').addClass('hide')
        $('#delete_link').addClass('hide')
    }

    function delete_item(element) {


        console.log('caca')

        $($(element).data('element')).closest('li').remove()
        $('#delete_item').addClass('hide')
        $('#change_image').addClass('hide')



    }




    function edit_social_links(element) {




        var block = $(element)
        block.uniqueId()
        var id = block.attr('id')

        block.find('li').each(function (i, obj) {
            $('#social_links_control_center').find('.' + $(obj).attr('icon')).next('input').val($(obj).find('a').attr('href'))
        });


        if ($(element).closest('.footer_block').hasClass('last')) {
            $('#social_links_control_center').attr('block_id', id).removeClass('hide').offset({
                top: 10,
                left: block.offset().left + block.width() - $('#social_links_control_center').width()
            })

        } else {
            $('#social_links_control_center').attr('block_id', id).removeClass('hide').offset({ top: 10, left: block.offset().left})

        }


    }


    function update_social_links_from_dialog() {

        var block = $('#' + $('#social_links_control_center').attr('block_id'))
        $('#social_links_control_center').addClass('hide')
        social_links = ''

        $('#social_links_control_center .social_link').each(function (i, obj) {
            if ($(obj).next('input').val() != '') {
                social_links += ' <li class="social_link" icon="' + $(obj).attr('icon') + '"  ><a href="' + $(obj).next('input').val() + '"><i class="fab ' + $(obj).attr('icon') + '"></i></a></li>'
            }
        })

        if (social_links == '') {
            social_links = '<i class="fa fa-plus editing" title="{t}Add social media link{/t}" aria-hidden="true"></i>  <span style="margin-left:5px" class="editing">{t}Add social media link{/t}</span>';
        }

        block.html(social_links)


        $('#save_button', window.parent.document).addClass('save button changed valid')


    }


    function edit_copyright_bundle(element) {


        if ($('#drag_mode').hasClass('on')) {
            return;
        }


        if (!$('#copyright_bundle_control_center').hasClass('hide')) {
            return
        }

        var block = $(element)
        block.uniqueId()
        var id = block.attr('id')

        block.find('.copyright_bundle_link').each(function (i, obj) {

            var link = $("#copyright_bundle_control_center .discreet_links_control_panel div:nth-child(" + (i + 1) + ")")

            // console.log( "#copyright_bundle_control_center .social_links_control_center:nth-child("+i+")")
            //  console.log( $("#copyright_bundle_control_center .discreet_links_control_panel div:nth-child(1)").html())
            console.log(link.html())
            link.find('.label').val($(obj).html())
            link.find('.url').val($(obj).attr('href'))

            //      $('#social_links_control_center').find('.'+$(obj).attr('icon')).next('input').val($(obj).find('a').attr('href')   )
        });

        $('#copyright_bundle_control_center_owner').val(block.find('.copyright_bundle_owner').html())
        $('#copyright_bundle_control_center').attr('block_id', id).removeClass('hide').offset({
            top: block.offset().top - 30 - $('#copyright_bundle_control_center').height(),
            left: block.offset().left + block.width() - $('#copyright_bundle_control_center').width()
        })


    }


    function update_copyright_bundle_from_dialog() {

        var block = $('#' + $('#copyright_bundle_control_center').attr('block_id'))
        $('#copyright_bundle_control_center').addClass('hide')
        copyright_links = ''


        block.find('.copyright_bundle_owner').html($('#copyright_bundle_control_center_owner').val())

        $('#copyright_bundle_control_center .copyright_link').each(function (i, obj) {
            if ($(obj).find('.label').val() != '' && $(obj).find('.url').val() != '') {
                copyright_links += '<a class="copyright_bundle_link" href="' + $(obj).find('.url').val() + '">' + $(obj).find('.label').val() + '</a>  | '
            }
        })

        copyright_links = copyright_links.replace(/ \| $/g, "");

        block.find('.copyright_bundle_links').html(copyright_links)

        $('#save_button', window.parent.document).addClass('save button changed valid')

    }



    function save_footer() {



        if (!$('#save_button', window.parent.document).hasClass('save')) {
            return;
        }

        $('#save_button', window.parent.document).find('i').addClass('fa-spinner fa-spin')


        var cols_main_4 = [];
        var cols_copyright = [];

        $('footer .text_blocks').each(function (i, obj) {


            if ($(obj).hasClass('top_header')) {

                $('.footer_block', obj).each(function (i, obj2) {

                    console.log($(obj2))

                    switch ($(obj2).data('type')) {

                        case 'address':

                            items = []

                            $('.item', obj2).each(function (i, obj3) {

                                if ($(obj3).hasClass('_logo')) {
                                    var img = $(obj3).find('img')
                                    items.push({
                                        type: "logo", src: img.attr('src'), title: $.trim(img.attr('title'))
                                    });

                                } else if ($(obj3).hasClass('_text')) {


                                    items.push({
                                        type: "text",
                                        icon: $(obj3).attr('icon'),
                                        text: $.trim($(obj3).find('span').html()),
                                    });

                                } else if ($(obj3).hasClass('_email')) {


                                    items.push({
                                        type: "email", text: $.trim($(obj3).find('span').html()),
                                    });

                                }


                            })

                            cols_main_4.push({
                                'type': 'address', 'items': items

                            })

                            break;

                        case 'links':
                            var items = []
                            $('.links_list .item', obj2).each(function (i, obj3) {

                                items.push({
                                    url: $(obj3).find('a').attr('href'), label: $(obj3).find('.item_label').html(),
                                });

                                // console.log($(obj2).find('a').attr('href'))
                                // console.log($(obj2).find('.item_label').html())
                            });


                            cols_main_4.push({
                                'type': 'links', 'header': $(obj2).find('h5').html(), 'items': items
                            })

                            break;

                        case 'text':


                            cols_main_4.push({
                                'type': 'text', 'header': $(obj2).find('h5').html(), 'text': $(obj2).find('.footer_text').html()
                            })
                            break;

                        case 'nothing':
                            cols_main_4.push({
                                'type': 'nothing'

                            })

                            break;

                    }

                })


            }


            if ($(obj).hasClass('bottom_header')) {

                $('.footer_block', obj).each(function (i, obj2) {

                    switch ($(obj2).data('type')) {

                        case 'low_text':
                            cols_copyright.push({
                                'type': 'text', 'text': $(obj2).find('.lower_footer_text').html()
                            })

                            break;
                        case 'nothing':
                            cols_copyright.push({
                                'type': 'nothing'
                            })

                            break;
                        case 'social_links':
                            items = []
                            $('.social_link', obj2).each(function (i, obj3) {
                                items.push({
                                    url: $(obj3).find('a').attr('href'), icon: $(obj3).attr('icon'),
                                });
                            })
                            cols_copyright.push({
                                'type': 'social_links', 'items': items
                            })

                            break;
                        case 'copyright_bundle':
                            var links = []
                            $(obj2).find('.copyright_bundle_link').each(function (j, obj3) {

                                links.push({
                                    url: $(obj3).attr('href'), label: $(obj3).html(),
                                });


                            });

                            cols_copyright.push({
                                'type': 'copyright_bundle', 'owner': $(obj).find('.copyright_bundle_owner').html(), 'links': links
                            })
                            break;


                    }

                })

            }


        })

        footer_data = {
            rows: []
        }


        footer_data.rows.push({
            'type': 'main_4', 'columns': cols_main_4
        })
        footer_data.rows.push({
            'type': 'copyright', 'columns': cols_copyright
        })


        console.log(footer_data)
        // return;

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'save_footer')
        ajaxData.append("footer_key", '{$footer_key}')
        ajaxData.append("footer_data", JSON.stringify(footer_data))


        $.ajax({
            url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {

                if (data.state == '200') {

                    $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')
                } else if (data.state == '400') {
                    swal(data.msg);
                }


            }, error: function () {

            }
        });


    }



</script>
<script src="js/edit_webpage_upload_images_from_iframe.js?v2"></script>

</body></html>



