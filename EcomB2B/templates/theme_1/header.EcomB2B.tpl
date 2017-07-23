{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 March 2017 at 17:45:30 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<style>


    #topHeader {

        background-color: {$header_data.color.header_background};
    {if $header_data.background_image!=''}
        background-image: url({$header_data.background_image});
    {/if}

        color: {$header_data.color.header};


    }

    #trueHeader{
        background-color: {$header_data.color.menu_background};
        border-bottom-color:  {$header_data.color.menu_background_highlight};
        color: {$header_data.color.menu};
    }



    #trueHeader a {
        color: {$header_data.color.menu};
        cursor: pointer;

    }
    #trueHeader a:hover {
        color: {$header_data.color.menu_text_background_highlight};

    }

    #_columns  .dropdown a:hover {
        background-color: transparent;
    }

    #_columns  .dropdown li.item_li:hover > a * {
        color:{$header_data.color.items_title};
    }


    #trueHeader .dropdown-menu{

        color: {$header_data.color.items};
    }

    #trueHeader .dropdown-menu a{

        color: {$header_data.color.items};
    }

    #trueHeader .dropdown-menu a:hover{

        color: {$header_data.color.items_title};
    }



    #menu_control_panel .button {
        background-color:  {$header_data.color.menu_background_highlight};
    }

    #logo {
        background-image: url({$header_data.logo});


    }

    .yamm .dropdown-menu {
        background: {$header_data.color.items_background};
    }



    .dropdown-menu li a:hover{
        background:{$header_data.color.items};
        color: {$header_data.color.items_background};
    }




    .list-unstyled span.link,.list-unstyled a.link {
        color: {$header_data.color.items};

    }

    .list-unstyled li p{
        color: {$header_data.color.items}
    }

    .dart {
        color: {$header_data.color.items}

    }
    .list-unstyled li i {
        color: {$header_data.color.items}
    }

    .list-unstyled li span {
        color: {$header_data.color.items}
    }





</style>

<span id="webpage_data" style="display:none" webpage_key="{$webpage->id}"  ></span>


<header id="header">
        <div id="topHeader">
            <div class="wrapper">
                <div class="top_nav">
                    <div class="container">
                        <div class="left">
                            <a href="index.php" id="logo">  </a>

                        </div>



                        <div class="right ">


                                <div style="float:right;background-color: black;height:30px;width: 30px ;text-align: center">
                                    <i class="fa fa-search" style="color:#fff;font-size:20px;position: relative;top:4px" aria-hidden="true"></i></div>
                                <input style="width: 250px;float:right;border: 1px solid black;padding:2px"/>



                        </div>

                    </div>
                </div>

            </div>

        </div>

        <div id="trueHeader">

            <div class="wrapper">

                <div class="container">

                    <nav class="menu_main2">

                        <div class="navbar yamm navbar-default">

                            <div class="navbar-header">
                                <div class="navbar-toggle .navbar-collapse .pull-right " data-toggle="collapse" data-target="#navbar-collapse-1"  > <span>Menu</span>
                                    <button type="button" > <i class="fa fa-bars"></i></button>
                                </div>
                            </div>

                            <div id="navbar-collapse-1" class="navbar-collapse collapse">

                                <ul id="_columns" class="nav navbar-nav three">
                                    {foreach from=$header_data.menu.columns item=column key=key}
                                    <li  id="menu_column_{$key}" class="dropdown {if !$column.show}hide{/if} on _column {if $column.type=='three_columns'}yamm-fw  3_columns{else}single_column{/if}  " >
                                        <a  href="" data-toggle="dropdown" class="dropdown-toggle ">
                                            <i class="fa _column_label_icon {if $column.icon==''}fa-ban {else}{$column.icon}{/if} item_icon padding_right_5  " icon="{$column.icon}" aria-hidden="true"></i>  <span>{$column.label}</span>
                                        </a>


                                        {if $column.type=='three_columns'}


                                        <ul class="dropdown-menu">
                                            <li>
                                                <div class="yamm-content">
                                                    <div class="row">
                                                        {foreach from=$column.sub_columns item=sub_column}
                                                            {if $sub_column.type=='items'}

                                                                <ul class="col-sm-6 col-md-4 list-unstyled two">
                                                                    {foreach from=$sub_column.items item=item}


                                                                    <li class="item_li">
                                                                        <a href="{$item.url}" ><i class="fa item_icon fa-fw {$item.icon}" icon="{$item.icon}" ></i> <span class="_item_label" >{$item.label}</span></a>
                                                                    </li >
                                                                    {/foreach}

                                                                </ul>
                                                            {elseif $sub_column.type=='text'}
                                                                <ul class="col-sm-6 col-md-4 list-unstyled two">
                                                                    <li>
                                                                        <p  >{$sub_column.title}</p>
                                                                    </li>
                                                                    <li class="dart">
                                                                        <img src="{if $sub_column.image==''}http://placehold.it/230x80{else}{$sub_column.image}{/if}" alt="" class="rimg marb1" />
                                                                        <span >{$sub_column.text}</span>
                                                                    </li>
                                                                </ul>
                                                            {elseif $sub_column.type=='image'}
                                                                <ul class="col-sm-6 col-md-4 list-unstyled two">
                                                                    <li>
                                                                        <p  >{$sub_column.title}</p>
                                                                    </li>
                                                                    <li class="dart">
                                                                        <img src="{if $sub_column.image==''}http://placehold.it/230x160{else}{$sub_column.image}{/if}" alt="" class="rimg marb1" />

                                                                    </li>
                                                                </ul>
                                                            {elseif $sub_column.type=='departments' or   $sub_column.type=='families' or  $sub_column.type=='web_departments' or   $sub_column.type=='web_families'}
                                                                <ul id="_3c_col{$key}_{$col_key}" class="col-sm-6 col-md-4 list-unstyled two _3c_col {$sub_column.type}" type="{$sub_column.type}" page="{$sub_column.page}" page_label="{$sub_column.page_label}"  >
                                                                    <li class="title">
                                                                        <p >{$sub_column.label}</p>
                                                                    </li>
                                                                    {foreach from=$store->get_categories({$sub_column.type},{$sub_column.page},'menu') item=item}
                                                                        <li class="item">
                                                                            <a href="{$item['url']}"><i class="fa fa-caret-right" style="margin-right:5px" ></i>{$item['label']} {if $item['new']}<b class="mitemnew">{t}New{/t}</b>{/if}</a>
                                                                        </li>
                                                                    {/foreach}

                                                                </ul>
                                                            {elseif $sub_column.type=='empty'}
                                                                <ul id="_3c_col{$key}_{$col_key}" class="col-sm-6 col-md-4 list-unstyled two _3c_col {$sub_column.type} ">
                                                                </ul>

                                                            {/if}




                                                        {/foreach}

                                                    </div>
                                                </div>
                                            </li>
                                        </ul>




                                        {elseif $column.type=='single_column'}
                                            <ul class="dropdown-menu multilevel sortable" role="menu">


                                                {foreach from=$column.items item=item}
                                                    {if $item.type=='item'}
                                                        <li><a href="{$item['url']}">{$item['label']}</a></li>
                                                    {elseif $item.type=='submenu'}
                                                        <li class="dropdown-submenu mul"> <a tabindex="-1" href="#">{$item['label']}</a>
                                                            <ul class="dropdown-menu sortable">
                                                                {foreach from=$item.sub_items item=sub_item}
                                                                    <li><a href="{$sub_item.url}">{$sub_item.label}</a></li>
                                                                {/foreach}


                                                            </ul>
                                                        </li>
                                                    {/if}
                                                {/foreach}


                                            </ul>

                                        {/if}
                                    </li>




                                    {/foreach}











                                </ul>

                            </div>

                        </div>

                    </nav>

                    <div id="menu_control_panel" >
                        {if $logged_in}
                            <p>
                                <i id="logout" class="fa fa-sign-out fa-flip-horizontal button " style="cursor:pointer;margin-right:20px"   title="{t}Log out{/t}"  aria-hidden="true"></i>

                                <i class="fa fa-user fa-flip-horizontal button " style="cursor:pointer;margin-right:10px"   title="{t}Profile{/t}"  aria-hidden="true"></i>
                                <i class="fa fa-heart fa-flip-horizontal button " style="cursor:pointer;margin-right:20px"   title="{t}My favorites{/t}"  aria-hidden="true"></i>
                                <a href="basket.sys" class="button" >
                                    <span  id="header_order_products">{$order->get('Products')}</span>
                                    <i style="padding-right:5px;padding-left:5px" class="fa fa-shopping-cart fa-flip-horizontal  " style="cursor:pointer"   title="{t}Basket{/t}"  aria-hidden="true"></i>
                                    <span id="header_order_total_amount" style="padding-right:10px" id="basket_total">{$order->get('Total')}</span>
                                </a>


                            </p>

                        {else}
                            <p>
                            <a href="login.sys" class="button" ><i class="fa fa-sign-in" aria-hidden="true"></i> {t}Login{/t}</a>
                            <a href="register.sys"class="button" ><i class="fa fa-user-plus" aria-hidden="true"></i> {t}Register{/t}</a>
                            </p>
                        {/if}

                    </div>

                </div>

            </div>

        </div>

    </header>

