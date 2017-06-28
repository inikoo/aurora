{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2017 at 14:15:23 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<footer class="footer">




    <div class="top_footer empty"></div><!-- end footer top section -->
    <div class="clearfix"></div>

    {foreach from=$footer_data.rows item=row}

        {if $row.type=='main_4'}

            <div class="container sortable_container ">




                {foreach from=$row.columns item=column name=main_4}


                    {if $column.type=='address'}



                        <div class="one_fourth   editable_block {if $smarty.foreach.main_4.last}last{/if}" >





                            <i class="fa fa-hand-rock-o editing hide dragger" aria-hidden="true" style="position:absolute;top:-25px"></i>
                            <i onclick="open_block_type_options(this,'block_type_1','{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" style="position:absolute;top:-23px;left:20px"></i>



                            <ul class="footer_block faddress">
                                {foreach from=$column.items item=item }
                                    {if $item.type=='logo'}
                                        <li  class="item _logo"><img  onclick="edit_item_image(this)" src="{$item.src}" title=" {$item.title}" /></li>
                                    {elseif $item.type=='text'}
                                        <li   class="item _text" icon="{$item.icon}"><i onclick="edit_item(this)"  class="fa fa-fw {$item.icon}"></i> <span contenteditable>
                                          {if $item.text=='#tel' and  $store->get('Telephone')!=''}{$store->get('Telephone')}
                                          {elseif $item.text=='#email' and  $store->get('Email')!=''}{$store->get('Email')}
                                          {elseif $item.text=='#address' and  $store->get('Address')!=''}{$store->get('Address')}
                                          {else}{$item.text}{/if}
                                      </span></li>
                                    {elseif $item.type=='email'}
                                        <li  class="item _email"><i onclick="edit_item(this)" class="fa fa-fw fa-envelope"></i> <span contenteditable>
                                          {if $item.text=='#email' and  $store->get('Email')!=''}{$store->get('Email')}
                                          {else}{$item.text}{/if}

                                      </span></li>
                                    {/if}
                                {/foreach}
                                <li onClick="add_item(this)"  class="button add_item"   >
                                    <i class="fa fa-fw fa-plus editing " ></i> <span class="editing" ondrop="return false;" >{t}Add item{/t}<span></span></li>



                            </ul>



                        </div>
                    {elseif $column.type=='links'}
                        <div class="one_fourth links  editable_block {if $smarty.foreach.main_4.last}last{/if}" >
                            <i class="fa fa-hand-rock-o editing hide dragger" aria-hidden="true" style="position:absolute;top:-25px"></i>
                            <i onclick="open_block_type_options(this,'block_type_1','{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" style="position:absolute;top:-23px;left:20px"></i>

                            <div class="footer_block qlinks">

                                <h4 class="lmb" contenteditable>{$column.header}</h4>

                                <ul class="links_list">
                                    {foreach from=$column.items item=item }
                                        <li class="item"><a href="{$item.url}">
                                                <i class="fa fa-fw fa-angle-right link_icon" onClick="update_link(this)"></i>
                                                <span class="item_label" ondrop="return false;" contenteditable>{$item.label}</span></a></li>

                                    {/foreach}

                                    <li onClick="add_link(this)"  class="add_link"><a href="{$item.url}"><i class="fa fa-fw fa-plus editing link_icon" onClick="update_link(this)"></i> <span class="editing" ondrop="return false;" >{t}Add link{/t}<span></span></a></li>

                                </ul>

                            </div>
                        </div>
                    {elseif $column.type=='text'}
                        <div class="one_fourth  editable_block {if $smarty.foreach.main_4.last}last{/if}" >
                            <i class="fa fa-hand-rock-o editing hide dragger" aria-hidden="true" style="position:absolute;top:-25px"></i>
                            <i onclick="open_block_type_options(this,'block_type_1','{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" style="position:absolute;top:-23px;left:20px"></i>

                            <div class="footer_block siteinfo">

                                <h4 class="lmb" contenteditable>{$column.header}</h4>

                                <div  contenteditable>
                                    {$column.text}
                                </div>
                            </div>
                        </div>
                    {elseif $column.type=='nothing'}
                        <div class="one_fourth  editable_block {if $smarty.foreach.main_4.last}last{/if}">
                            <div class="footer_block nothing">

                            </div>

                        </div>
                    {/if}


                {/foreach}

            </div>



        {elseif $row.type=='copyright'}
            <div class="clearfix"></div>




            <div class="copyright_info">
                <div class="container sortable_container">

                    <div class="clearfix divider_dashed10"></div>



                    {foreach from=$row.columns item=column name=copyright_info}

                        {if $column.type=='text'}
                            <div class="one_half  {if $smarty.foreach.copyright_info.last}last{/if}" >
                                <i class="fa fa-hand-rock-o editing hide dragger" aria-hidden="true" style="position:absolute;top:-25px"></i>
                                <i onclick="open_block_type_options(this,'block_type_2','low_{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" style="position:absolute;top:-23px;left:20px"></i>
                                <div class="footer_block _copyright_text">
                                    {$column.text}
                                </div>
                            </div>

                        {elseif $column.type=='nothing'}
                            <div class="one_half  {if $smarty.foreach.copyright_info.last}last{/if}" >
                                <i class="fa fa-hand-rock-o editing hide dragger" aria-hidden="true" style="position:absolute;top:-25px"></i>
                                <i onclick="open_block_type_options(this,'block_type_2','low_{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" style="position:absolute;top:-23px;left:20px"></i>
                                <div class="footer_block _copyright_nothing">
                                </div>
                            </div>

                        {elseif $column.type=='copyright_bundle'}
                            <div class="one_half  {if $smarty.foreach.copyright_info.last}last{/if}"  >

                                <i class="fa fa-hand-rock-o editing hide dragger" aria-hidden="true" style="position:absolute;top:-25px"></i>
                                <i onclick="open_block_type_options(this,'block_type_2','{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" style="position:absolute;top:-23px;left:20px"></i>

                                <div class="footer_block _copyright_bundle">
                                    <div onClick="edit_copyright_bundle(this)"  class="footer_copyright_bundle">
                                        {t}Copyright{/t} © {"%Y"|strftime} <span class="copyright_bundle_owner">{$column.owner}</span>. {t}All rights reserved{/t}. <span class="copyright_bundle_links">{foreach  from=$column.links item=item name=copyright_links}<a class="copyright_bundle_link" href="{$item.url}">{$item.label}</a>{if !$smarty.foreach.copyright_links.last} | {/if}{/foreach}</span>
                                    </div>
                                </div>
                            </div>
                        {elseif $column.type=='social_links'}



                            <div class="one_half  {if $smarty.foreach.copyright_info.last}last{/if}">

                                <i class="fa fa-hand-rock-o editing hide dragger" aria-hidden="true" style="position:absolute;top:-25px"></i>
                                <i onclick="open_block_type_options(this,'block_type_2','{$column.type}')" class="fa fa-recycle editing hide button recycler" aria-hidden="true" style="position:absolute;top:-23px;left:20px"></i>

                                <div class="footer_block _social_links">

                                    <ul  onClick="edit_social_links(this)"  class="footer_social_links">
                                        {foreach from=$column.items item=item}
                                            <li class="social_link" icon="{$item.icon}"  ><a href="{$item.url}"><i class="fa {$item.icon}"></i></a></li>

                                        {/foreach}
                                    </ul>
                                </div>
                            </div>
                        {/if}



                    {/foreach}



                </div>
            </div>
        {/if}


    {/foreach}




    <div class="clearfix"></div>

</footer>