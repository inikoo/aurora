{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2017 at 14:15:23 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}



<footer>


    {foreach from=$footer_data.rows item=row}

        {if $row.type=='main_4'}
            <div class="text_blocks  top_header text_template_4  ">


                {foreach from=$row.columns item=column name=main_4}


                    {if $column.type=='address'}
                        <div class="footer_block">


                            <ul class="address " style="">
                                {foreach from=$column.items item=item }
                                    {if $item.type=='logo'}
                                        <li class="item _logo"><img src="{$item.src}" alt="" title="{$item.title}"/></li>
                                    {elseif $item.type=='text'}
                                        <li class="item _text"><i class="fa-fw {$item.icon}"></i> <span>
                                          {if $item.text=='#tel' and  $store->get('Telephone')!=''}{$store->get('Telephone')}{elseif $item.text=='#email' and  $store->get('Email')!=''}{$store->get('Email')}{elseif $item.text=='#address' and  $store->get('Address')!=''}{$store->get('Address')}{else}{$item.text|strip_tags|trim}{/if}
                                      </span></li>
                                    {elseif $item.type=='email'}
                                        <li class="item _email"><i class="fa fa-fw fa-envelope"></i> <a href="mailto:{if $item.text=='#email' and  $store->get('Email')!=''}{$store->get('Email')}{else}{$item.text}{/if}">
                                                {if $item.text=='#email' and  $store->get('Email')!=''}{$store->get('Email')}{else}{$item.text}{/if}

                                            </a></li>
                                    {/if}
                                {/foreach}


                            </ul>


                        </div>
                    {elseif $column.type=='links'}
                        <div class="footer_block">



                            <h5 >{$column.header}</h5>

                            <ul class="links_list">
                                {foreach from=$column.items item=item }
                                    <li class="item"><a href="{$item.url}"><i class="fa fa-fw fa-angle-right link_icon"></i><span class="item_label">{$item.label}</span></a></li>
                                {/foreach}


                            </ul>


                        </div>
                    {elseif $column.type=='text'}
                        <div class="footer_block">



                            <h5 class="for_text">{$column.header}</h5>

                            <div  class="footer_text" >
                                {$column.text}
                            </div>

                        </div>
                    {elseif $column.type=='nothing'}
                <div class="footer_block">

                </div>

                    {/if}


                {/foreach}

            </div>
        {elseif $row.type=='copyright'}
            <div class="text_blocks  text_template_2 bottom_header copyright">
                {foreach from=$row.columns item=column name=copyright_info}

                    {if $column.type=='text'}
                        <div class="footer_block {if $smarty.foreach.copyright_info.last}last{/if} ">

                            <div class="text">
                                {$column.text}
                            </div>
                        </div>
                    {elseif $column.type=='nothing'}
                        <div class="footer_block "></div>
                    {elseif $column.type=='copyright_bundle'}
                        <div class="footer_block {if $smarty.foreach.copyright_info.last}last{/if} ">

                            <div class="copyright_bundle ">
                                <small>

                                    {t}Copyright{/t} © {"%Y"|strftime} <span class="copyright_bundle_owner">{$column.owner}</span>. {t}All rights reserved{/t}. <span
                                            class="copyright_bundle_links">{foreach  from=$column.links item=item name=copyright_links}<a class="copyright_bundle_link"
                                                                                                                                          href="{$item.url}">{$item.label}</a>{if !$smarty.foreach.copyright_links.last} | {/if}{/foreach}</span>
                                </small>
                            </div>

                        </div>
                    {elseif $column.type=='social_links'}
                        <div class="footer_block {if $smarty.foreach.copyright_info.last}last{/if} ">


                            <ul class="footer_social_links">
                                {foreach from=$column.items item=item}
                                    <li class="social_link"><a href="{$item.url}"><i class="fab {$item.icon}"></i></a></li>
                                {/foreach}
                            </ul>

                        </div>
                    {/if}


                {/foreach}

            </div>
        {/if}


    {/foreach}


</footer>