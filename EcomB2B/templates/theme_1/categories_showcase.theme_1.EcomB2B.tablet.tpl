{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2018 at 13:11:04 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tablet.tpl"}
<body>{include file="analytics.tpl"}
<div id="page-transitions">
    {include file="theme_1/header.theme_1.EcomB2B.tablet.tpl"}
    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->
            <div class="menu-bar" style="margin:0px;height:50px;position: relative;top:-5px;border-bottom:1px solid #ccc">

                <em class="menu-bar-text-1   ">
                    <a href="/" style="color:#1f2f1f"> <i class="fa fa-home" aria-hidden="true"></i></a>
                    <i class="fa fa-angle-double-right padding_left_5 padding_right_5" aria-hidden="true"></i>
                </em>

                <em class="menu-bar-text-2   " >

                    {$category->get('Code')}
                    {*
                    {if $prev_family}<a href="{$prev_family.webpage_code}" class="color-black " style="margin-right: 10px"><i class="fa fa-arrow-left"></i></a>{/if}

                    {if $next_family}<a href="{$next_family.webpage_code}" class="color-black" style="margin-left: 10px"><i class="fa fa-arrow-right"></i></a>{/if}
*}
                </em>

                <div class="menu-bar-title" style="position: relative;"></div>
            </div>

            <div class="content">
                <div class="asset_description   fr-view" style="margin-bottom:30px">

                        {foreach from=$content_data.description_block.blocks key=id item=data name=foo}


                            {if $data.type=='text' and $data.content!=''}
                                <p>{$data.content}</p>
                            {elseif $data.type=='image'}

                                {if $smarty.foreach.foo.iteration==1}
                                    <img src="{$data.image_src}" style="width:100%;padding-top:15px" title="{if isset($data.caption)}{$data.caption}{/if}"/>
                                {else}
                                    <img src="{$data.image_src}" style="width:40%;;{if $smarty.foreach.foo.iteration%2} float:left;margin-right:15px;{else}float:right;margin-left:15px;{/if}"
                                         title="{if isset($data.caption)}{$data.caption}{/if}"/>
                                {/if}





                            {/if}
                        {/foreach}



                </div>


                <div style="clear: both"></div>

                {foreach from=$sections item=section_data key=section_key}
                    <div id="section_{$section_data.key}_container">


                        {if $section_data.type!='anchor'}
                            <div>
                                <h2 class="single_line_height">{$section_data.title}</h2>
                                <div class="decoration deco-7 decoration-margins" style="margin: 0px;margin-top: 4px"></div>

                                <div class="single_line_height" style="margin-top:4px;margin-bottom: 10px">{$section_data.subtitle}</div>
                            </div>
                        {/if}


                        <div class="store-items clear" style="margin-top:20px;clear: both">
                            {counter assign=i start=0 print=false}

                            {foreach from=$section_data.items item=category_data key=key name=families}
                                {if $category_data.type=='category'}
                                    {counter}
                                    <div class="store-item"><a href="/{$category_data.webpage_code|lower}"><img src="{$category_data.image_mobile_website}" alt="{$category_data.header_text|strip_tags|escape}"></a>
                                        <div class="single_line_height center-text " style="min-height: 32px;margin-top: 10px">{$category_data.header_text|strip_tags}</div>
                                    </div>
                                {/if}
                            {/foreach}
                            {if $i%2==1}
                                <div class="store-item invisible"></div>
                            {/if}
                            <div class="clear"></div>
                        </div>

                    </div>
                {/foreach}

            </div>


            {include file="theme_1/footer.theme_1.EcomB2B.tablet.tpl"}
        </div>
    </div>

    <a href="#" class="back-to-top-badge"><i class="fas fa-arrow-circle-up"></i></a>


</div>
</body>{include file="theme_1/bottom_scripts.theme_1.EcomB2B.mobile.tpl"}</body></html>
