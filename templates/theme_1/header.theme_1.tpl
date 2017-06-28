


{include file="theme_1/_head.theme_1.tpl"}

<style>

    .sys{
        font-size: 10px;
        color: #555;
        font-family: "Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, sans-serif;
    }

    .handle{
        cursor:move
    }


    .single_column .item_link{
        margin-left:5px
    }


    input.input_file {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }


    .button {
        cursor: pointer

    }

    .link {
        cursor: pointer

    }

    .link:hover {
      color:#000

    }

    .input_container{
        position:absolute;top:60px;left:10px;z-index: 100;border:1px solid #ccc;background-color: white;padding:10px 10px 10px 5px

    }


    .input_container input{
        width:400px
    }


    .list-unstyled span.link {
        color: #272727;
        padding: 4px 8px;
        width: 100%;
        transition-property: margin-left, background-color;
        transition-duration: 0.3s;
        transition-timing-function: ease-out;
    }



    .list-unstyled span.link:before {
        font-size: 12px;
        margin-right: 5px;
    }

    .submenu_expand,.item_link{
        margin-right: 5px; margin-left: 5px;
    }

    .item_delete{
        margin-left: 0px;
    }


        #logo_edit {
        background-image: url({$header_data.logo});
        background-repeat:no-repeat;
        background-origin: left top;

    }

    #menu_active_edit{
        background-color: {$header_data.color.menu_background_highlight};
        color:{$header_data.color.menu_text_background_highlight};
    }

    #topHeader_edit {

        background-color: {$header_data.color.header_background};
    {if $header_data.background_image!=''}
        background-image: url({$header_data.background_image});
    {/if}

    }

    #items_color_edit{
        color:{$header_data.color.items};
        background: {$header_data.color.items_background};
        border:1px solid {$header_data.color.items_background_border};
        border-bottom-color:{$header_data.color.items_background_border};
    }

#items_color_edit_title{
    color:{$header_data.color.items_title}
}


</style>

<style>



    .colorPicker
    {

        box-sizing: content-box;

        width:          30px;
        height:         30px;
        position: absolute;
        clear: both;

    }


    .colorPicker .track {
        background:     #EFEFEF url(../art/palettes/text-color.png) no-repeat 50% 50%;
        height:         150px;
        width:          150px;
        padding:        10px;
        position:       absolute;
        cursor:         crosshair;
        float:          left;
        left:           -71px;
        top:            -71px;
        display:        none;
        border:         1px solid #ccc;
        z-index:        10;
        -webkit-border-radius: 150px;
        -moz-border-radius: 150px;
        border-radius: 150px;
        box-sizing: content-box;

    }

    .colorPicker .color {
        width:          25px;
        height:         25px;
        padding:        1px;
        border:         1px solid #ccc;
        display:        block;
        position:       relative;
        z-index:        11;
        background-color: #EFEFEF;
        -webkit-border-radius: 27px;
        -moz-border-radius: 27px;
        border-radius: 27px;
        cursor: pointer;
        box-sizing: content-box;
    }

    .colorPicker .colorInner {
        width:          25px;
        height:         25px;
        -webkit-border-radius: 27px;
        -moz-border-radius: 27px;
        border-radius: 27px;
        box-sizing: content-box;
    }

    .colorPicker .dropdown {
        list-style: none;
        display: none;
        width: 27px;
        position: absolute;
        top: 28px;
        border: 1px solid #ccc;
        left: 0;
        z-index: 1000;
        box-sizing: content-box;
    }

    .colorPicker .dropdown li{
        height: 25px;
        cursor: pointer;
        box-sizing: content-box;
    }

</style>


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

   #_columns  .dropdown a:hover {
        background-color: {$header_data.color.menu_background_highlight};
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



<div id="style_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white; padding:0px 20px 20px 10px;z-index: 200;top:10px;left:10px;line-height: normal">


    <i class="fa fa-window-close button" aria-hidden="true"  onclick="$('#style_dialog').addClass('hide')"></i>

    <div style="margin-left:20px">






        <div style=";margin:0px;padding:0px">
        <span style="font-size: 11px">{t}Logo{/t}: ideal 300x50, W max 300, H must be 50 <i id="logo_visibility" class="fa background_visibility button {if $header_data.logo==''}hide{else} {if $header_data.logo_show}fa-eye{else}fa-eye-slash{/if}{/if}" aria-hidden="true" style="margin-left:5px"></i>
            <input style="display:none" type="file" name="update_image" id="upload_logo" class="image_upload" data-options='{ "height":"50", "max_width":"300"}'/>
                <label for="upload_logo" style="float:right;margin-left:10px;font-weight: normal;cursor: pointer" ><i class="fa fa-upload" aria-hidden="true"></i>  {t}Upload logo{/t}</label>
        </div>

        <div style="clear:both;margin:0px 0px 5px 0px">
        <span style="font-size: 11px">{t}Background Image{/t}: <i id="header_background_visibility" class="fa background_visibility button {if $header_data.background_image==''}hide{else} {if $header_data.background_image_show}fa-eye{else}fa-eye-slash{/if}{/if}" aria-hidden="true" style="margin-left:5px"></i>

            <input style="display:none" type="file" name="update_image" id="upload_header_background" class="image_upload" data-options=''/>
                <label for="upload_header_background" style="float:right;margin-left:10px;font-weight: normal;cursor: pointer" ><i class="fa fa-upload" aria-hidden="true"></i>  {t}Upload background{/t}</label>
        </div>


        <div id="topHeader_edit" style="height: 104px;width: 600px;position:relative;">
            <div id="logo_edit" style="position:relative;left:30px;top:30px;height: 50px;width:300px" >

            </div>

            <div id="colorPicker_header" class="colorPicker" style="right:10px;top:10px">
                <a class="color" style=" background-color: {$header_data.color.header_background};"><div class="colorInner"></div></a>
                <div class="track"></div>
                <ul class="dropdown"><li></li></ul>
                <input type="hidden" class="colorInput"/>
            </div>
        </div>
        <div id="trueHeader_edit" style="height: 42px;width: 600px;color:{$header_data.color.menu};background: {$header_data.color.menu_background};position: relative">

            <span style="position: relative;top:7px;left:20px">{t}Column{/t}</span>

            <span id="menu_active_edit" style=";padding:7px 20px 14px 20px ; position: relative;top:7px;left:40px">{t}Column{/t}</span>

            <span style="position: relative;top:7px;left:70px">{t}Column{/t}</span>


            <div id="colorPicker_menu" class="colorPicker" style="right:10px;top:5px">
                <a class="color" style=" background-color: {$header_data.color.menu_background};"><div class="colorInner"></div></a>
                <div class="track"></div>
                <ul class="dropdown"><li></li></ul>
                <input type="hidden" class="colorInput"/>
            </div>




        </div>
        <div id="items_color_edit" style="height: 42px;width: 560px;;position:relative;top:-3px;left:20px;">
            <div id="colorPicker_items" class="colorPicker" style="right:10px;top:5px">
                <a class="color" style=" background-color: {$header_data.color.items_background};"><div class="colorInner"></div></a>
                <div class="track"></div>
                <ul class="dropdown"><li></li></ul>
                <input type="hidden" class="colorInput"/>
            </div>
            <span style="position: relative;top:10px;left:20px"><i id="items_color_edit_title" class="fa fa-caret-right" aria-hidden="true"></i> {t}Item{/t}</span>
        </div>



    </div>

</div>

<ul class="hide">
    <li id="link_stem_cell">
    <i class="handle aux hide fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
    <span class="link"><i class="fa item_icon fa-fw  fa-circle" icon="fa-circle"></i> <span class="_item_label" contenteditable="true">{t}New link{/t}</span></span>
    <i url="" class="fa item_link hide aux fa-chain button very_discreet" aria-hidden="true"></i>
    <i class="fa item_delete hide aux fa-trash-o button very_discreet" aria-hidden="true"></i>
</li>
    <li id="single_column_link_stem_cell" class="_item" type="item">
        <a href="">
            <i class="handle aux hide fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
            <span class="_label" contenteditable="true">{t}Link{/t}</span>
            <i url="" class="fa item_link hide aux fa-chain button very_discreet" aria-hidden="true"></i>
            <i class="fa item_delete hide aux fa-trash-o button very_discreet" aria-hidden="true"></i>
        </a>
    </li>

    <li id="single_column_submenu_link_stem_cell" class="_sub_item">
        <a href="">
            <i class="handle aux hide fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
            <span class="_label" contenteditable="true">{t}Link{/t}</span>
            <i url="" class="fa item_link hide aux fa-chain button very_discreet" aria-hidden="true"></i>
            <i class="fa item_delete hide aux fa-trash-o button very_discreet" aria-hidden="true"></i>
        </a>
    </li>
    <li id="single_column_submenu_stem_cell" type="submenu" class="_item _submenu dropdown-submenu mul">
        <a tabindex="-1" href="">
            <i class="handle aux hide fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
            <span class="_submenu_label" contenteditable="true">{t}Submenu{/t}</span>
            <i class="fa submenu_expand  fa-caret-right button" aria-hidden="true"></i>
            <i class="fa item_delete hide aux fa-trash-o button very_discreet" aria-hidden="true"></i>
        </a>
        <ul class="dropdown-menu submenu sortable">

                <li class="_sub_item">
                    <a href="">
                        <i class="handle aux hide fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
                        <span class="_label" contenteditable="true">{t}Link{/t}</span>
                        <i url="" class="fa item_link hide aux fa-chain button very_discreet" aria-hidden="true"></i>
                        <i class="fa item_delete hide aux fa-trash-o button very_discreet" aria-hidden="true"></i>
                    </a>

                </li>

            <li>
                <span  class="aux hide discreet">
                    <span class="link add_single_column_link" style="margin-left:10px">{t}Add link{/t}</span>
                </span>
            </li>
        </ul>
    </li>




</ul>

<div class="hide">

    <ul id="items_stem_cell" >
        <li >
            <i class="handle aux hide fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
            <span class="link"><i class="fa item_icon fa-fw fa-caret-right"></i> <span class="_item_label"  contenteditable="true">{t}Link{/t}</span></span>
            <i url="" class="fa item_link hide aux fa-chain button very_discreet" aria-hidden="true"></i>
            <i class="fa item_delete hide aux fa-trash-o button very_discreet" aria-hidden="true"></i>
        </li>
    <span style="margin-left: 30px" class="discreet aux hide  button add_link"><i class="fa fa-plus" style="margin-right: 5px" aria-hidden="true"></i> {t}Add link{/t}</span>

</ul>
    <ul id="empty_stem_cell"></ul>

    <ul id="text_stem_cell">
        <li>
            <p class="_title"  contenteditable="true">{t}Title{/t}</p>
        </li>
        <li class="dart" style="position: relative">
            <div class="aux sys hide" style="position:absolute;top:-12px;width:360px;">
                <span>{t}Ideal{/t} 360x120, W must be 360, H max 150</span>
                <input style="display:none" type="file" name="update_image"  class="image_upload" data-options='{ "width":"360", "max_height":"150"}'/>
                <label style="float:right;margin-left:10px;font-weight: normal;cursor: pointer" ><i class="fa fa-upload" aria-hidden="true"></i>  {t}Upload{/t}</label>
                <div style="float:right" class="button image_link" url=""><i class="fa fa-link" aria-hidden="true"></i> {t}link{/t}</div>
            </div>


            <img src="http://placehold.it/360x120" alt="" class="rimg marb1" image_key="" data-src=""  />


            <span class="_text" contenteditable="true">There are many variations passages available the majority have alteration in some form, by injected humour on randomised words if you are going to use a passage of lorem anything.</span>
        </li>
    </ul>

    <ul id="image_stem_cell">
        <li>
            <p  class="_title" contenteditable="true">{t}Title{/t}</p>
        </li>
        <li class="dart" style="position: relative">
            <div class="aux sys hide" style="position:absolute;top:-12px;width:360px;">
                <span>{t}Must be{/t} 360x240</span>
                <input style="display:none" type="file" name="update_image"  class="image_upload" data-options='{ "width":"360", "height":"240"}'/>
                <label style="float:right;margin-left:10px;font-weight: normal;cursor: pointer"  ><i class="fa fa-upload" aria-hidden="true"></i>  {t}Upload{/t}</label>
                <div style="float:right" class="button image_link" url=""><i class="fa fa-link" aria-hidden="true"></i> {t}link{/t}</div>
            </div>

            <img src="http://placehold.it/360x240" alt="" class="rimg marb1" image_key="" data-src=""  />

        </li>
    </ul>

    <ul id="catalogue_stem_cell">
        <li class="title">
            <p contenteditable="true">{t}Title{/t}</p>
        </li>


    </ul>

    <ul id="three_columns_stem_cell" >
        <li>
            <div class="yamm-content">
                <div  class="row">


                    <ul class="col-sm-6 col-md-4 list-unstyled two _3c_col empty ">
                    </ul>
                    <ul class="col-sm-6 col-md-4 list-unstyled two _3c_col empty ">
                    </ul>
                    <ul class="col-sm-6 col-md-4 list-unstyled two _3c_col empty ">
                    </ul>


                </div>
            </div>
        </li>
    </ul>

    <ul id="single_column_stem_cell" >

        <li  class="_item" type="item" >
            <a href="">
                <i class="handle aux  fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
                <span class="_label"  contenteditable="true">{t}Link{/t}</span>
                <i url="" class="fa item_link  aux fa-chain button very_discreet" aria-hidden="true"></i>
                <i class="fa item_delete  aux fa-trash-o button very_discreet" aria-hidden="true"></i>
            </a>
        </li>
        <li  class="_item" type="item" >
            <a href="">
                <i class="handle aux  fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
                <span class="_label"  contenteditable="true">{t}Link{/t}</span>
                <i url="" class="fa item_link  aux fa-chain button very_discreet" aria-hidden="true"></i>
                <i class="fa item_delete  aux fa-trash-o button very_discreet" aria-hidden="true"></i>
            </a>
        </li>
        <li  class="_item" type="item" >
            <a href="">
                <i class="handle aux  fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
                <span class="_label"  contenteditable="true">{t}Link{/t}</span>
                <i url="" class="fa item_link  aux fa-chain button very_discreet" aria-hidden="true"></i>
                <i class="fa item_delete  aux fa-trash-o button very_discreet" aria-hidden="true"></i>
            </a>
        </li>
        <li>
                                                                <span  class="aux  discreet">
                                                                    <span class="link add_single_column_link" style="margin-left:10px">{t}Add link{/t}</span>
                                                                    <span class="link add_single_submenu_link" style="float:right;margin-right:10px">{t}Add submenu{/t}</span>
                                                                </span>
        </li>

    </ul>



</div>

<div id="input_container_link" class="input_container link_url hide  " style="z-index:3001">
    <input  value="" placeholder="{t}http://... or webpage code{/t}"> <i onclick="close_item_edit_link()" class="fa fa-check-square button" aria-hidden="true"></i>

</div>



<div id="icons_control_center" class="input_container link_url hide  " style="z-index:3000">

    <div style="margin-bottom:5px">  <i  onClick="$('#icons_control_center').addClass('hide')" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i>  </div>


    <div>{t}Bullet points{/t}</div>

    <div>
        <i icon="fa-circle" class="button  fa fa-fw fa-circle" aria-hidden="true"></i>
        <i icon="fa-circle-o" class="button  fa fa-fw fa-circle-o" aria-hidden="true"></i>
        <i icon="fa-circle-thin" class="button  fa fa-fw fa-circle-thin" aria-hidden="true"></i>
        <i icon="fa-dot-circle-o" class="button  fa fa-fw fa-dot-circle-o" aria-hidden="true"></i>
        <i icon="fa-square" class="button  fa fa-fw fa-square" aria-hidden="true"></i>
        <i icon="fa-square-o" class="button  fa fa-fw fa-square-o" aria-hidden="true"></i>
        <i icon="fa-caret-square-o-right" class="button  fa fa-fw fa-caret-square-o-right" aria-hidden="true"></i>
        <i icon="fa-caret-right" class="button  fa fa-fw fa-caret-right" aria-hidden="true"></i>
        <i icon="fa-asterisk" class="button  fa fa-fw fa-asterisk" aria-hidden="true"></i>
        <i icon="fa-adjust" class="button  fa fa-fw fa-adjust" aria-hidden="true"></i>
        <i icon="fa-bullseye" class="button  fa fa-fw fa-bullseye" aria-hidden="true"></i>
        <i icon="fa-certificate" class="button  fa fa-fw fa-certificate" aria-hidden="true"></i>
        <i icon="fa-star" class="button  fa fa-fw fa-star" aria-hidden="true"></i>
        <i icon="fa-star-o" class="button  fa fa-fw fa-star-o" aria-hidden="true"></i>
    </div>

    <div>{t}Store{/t}</div>

    <div>
        <i icon="fa-tag" class="button  fa fa-fw fa-tag" aria-hidden="true"></i>
        <i icon="fa-tags" class="button  fa fa-fw fa-tags" aria-hidden="true"></i>
        <i icon="fa-lightbulb-o" class="button  fa fa-fw fa-lightbulb-o" aria-hidden="true"></i>

        <i icon="fa-stop" class="button  fa fa-fw fa-stop" aria-hidden="true"></i>
        <i icon="fa-th-large" class="button  fa fa-fw fa-th-large" aria-hidden="true"></i>
        <i icon="fa-th" class="button  fa fa-fw fa-th" aria-hidden="true"></i>
        <i icon="fa-plus" class="button  fa fa-fw fa-plus" aria-hidden="true"></i>
        <i icon="fa-percent" class="button  fa fa-fw fa-percent" aria-hidden="true"></i>
        <i icon="fa-gift" class="button  fa fa-fw fa-gift" aria-hidden="true"></i>

        <i icon="fa-shopping-basket" class="button  fa fa-fw fa-shopping-basket" aria-hidden="true"></i>
        <i icon="fa-shopping-bag" class="button  fa fa-fw fa-shopping-bag" aria-hidden="true"></i>
        <i icon="fa-shopping-cart" class="button  fa fa-fw fa-shopping-cart" aria-hidden="true"></i>

        <i icon="fa-money" class="button  fa fa-fw fa-money" aria-hidden="true"></i>
        <i icon="fa-credit-card" class="button  fa fa-fw fa-credit-card" aria-hidden="true"></i>
        <i icon="fa-paypal" class="button  fa fa-fw fa-paypal" aria-hidden="true"></i>

    </div>
    <div>

        <i icon="fa-university" class="button  fa fa-fw fa-university" aria-hidden="true"></i>
        <i icon="fa-usd" class="button  fa fa-fw fa-usd" aria-hidden="true"></i>
        <i icon="fa-euro" class="button  fa fa-fw fa-euro" aria-hidden="true"></i>
        <i icon="fa-gbp" class="button  fa fa-fw fa-gbp" aria-hidden="true"></i>


        <i icon="fa-handshake-o" class="button  fa fa-fw fa-handshake-o" aria-hidden="true"></i>
        <i icon="fa-truck" class="button  fa fa-fw fa-truck" aria-hidden="true"></i>
        <i icon="fa-ship" class="button  fa fa-fw fa-ship" aria-hidden="true"></i>
        <i icon="fa-paper-plane" class="button  fa fa-fw fa-paper-plane" aria-hidden="true"></i>
        <i icon="fa-paper-plane-o" class="button  fa fa-fw fa-paper-plane-o" aria-hidden="true"></i>

        <i icon="fa-plane" class="button  fa fa-fw fa-plane" aria-hidden="true"></i>
        <i icon="fa-fighter-jet" class="button  fa fa-fw fa-fighter-jet" aria-hidden="true"></i>

        <i icon="fa-info" class="button  fa fa-fw fa-info" aria-hidden="true"></i>
        <i icon="fa-info-circle" class="button  fa fa-fw fa-info-circle" aria-hidden="true"></i>
        <i icon="fa-question" class="button  fa fa-fw fa-question" aria-hidden="true"></i>
        <i icon="fa-question-circle" class="button  fa fa-fw fa-question-circle" aria-hidden="true"></i>


    </div>

    <div>{t}Office{/t}</div>

    <div>
        <i icon="fa-user" class="button  fa fa-fw fa-user" aria-hidden="true"></i>
        <i icon="fa-user-o" class="button  fa fa-fw fa-user-o" aria-hidden="true"></i>
        <i icon="fa-user-circle-o" class="button  fa fa-fw fa-user-circle-o" aria-hidden="true"></i>
        <i icon="fa-at" class="button  fa fa-fw fa-at" aria-hidden="true"></i>
        <i icon="fa-envelope" class="button  fa fa-fw fa-envelope" aria-hidden="true"></i>
        <i icon="fa-envelope-o" class="button  fa fa-fw fa-envelope-o" aria-hidden="true"></i>
        <i icon="fa-commenting-o" class="button  fa fa-fw fa-commenting-o" aria-hidden="true"></i>
        <i icon="fa-phone" class="button  fa fa-fw fa-phone" aria-hidden="true"></i>
        <i icon="fa-phone-square" class="button  fa fa-fw fa-phone-square" aria-hidden="true"></i>
        <i icon="fa-mobile" class="button  fa fa-fw fa-mobile" aria-hidden="mobile"></i>
        <i icon="fa-bell" class="button  fa fa-fw fa-bell" aria-hidden="true"></i>
        <i icon="fa-building" class="button  fa fa-fw fa-building" aria-hidden="true"></i>
    </div>
    <div>
        <i icon="fa-clock-o" class="button  fa fa-fw fa-clock-o" aria-hidden="true"></i>
        <i icon="fa-coffee" class="button  fa fa-fw fa-coffee" aria-hidden="true"></i>
        <i icon="fa-cutlery" class="button  fa fa-fw fa-cutlery" aria-hidden="true"></i>
        <i icon="fa-copyright" class="button  fa fa-fw fa-copyright" aria-hidden="true"></i>
        <i icon="fa-black-tie" class="button  fa fa-fw fa-black-tie" aria-hidden="true"></i>
        <i icon="fa-briefcase" class="button  fa fa-fw fa-briefcase" aria-hidden="true"></i>
    </div>

    <div>{t}Nature{/t}</div>

    <div>
        <i icon="fa-tree" class="button  fa fa-fw fa-tree" aria-hidden="true"></i>
        <i icon="fa-pagelines" class="button  fa fa-fw fa-pagelines" aria-hidden="true"></i>
        <i icon="fa-leaf" class="button  fa fa-fw fa-leaf" aria-hidden="true"></i>
        <i icon="fa-lemon-o" class="button  fa fa-fw fa-lemon-o" aria-hidden="true"></i>
        <i icon="fa-apple" class="button  fa fa-fw fa-apple" aria-hidden="true"></i>
        <i icon="fa-sun-o" class="button  fa fa-fw fa-sun-o" aria-hidden="true"></i>
        <i icon="fa-moon-o" class="button  fa fa-fw fa-moon-o" aria-hidden="true"></i>
        <i icon="fa-star" class="button  fa fa-fw fa-star" aria-hidden="true"></i>
        <i icon="fa-snowflake-o" class="button  fa fa-fw fa-snowflake-o" aria-hidden="true"></i>
        <i icon="fa-fire" class="button  fa fa-fw fa-fire" aria-hidden="true"></i>
        <i icon="fa-cloud" class="button  fa fa-fw fa-cloud" aria-hidden="true"></i>
        <i icon="fa-bolt" class="button  fa fa-fw fa-bolt" aria-hidden="true"></i>
        <i icon="fa-tint" class="button  fa fa-fw fa-tint" aria-hidden="mobile"></i>
        <i icon="fa-thermometer" class="button  fa fa-fw fa-thermometer" aria-hidden="true"></i>
        <i icon="fa-paw" class="button  fa fa-fw fa-paw" aria-hidden="true"></i>
    </div>

    <div>{t}Humanoid{/t}</div>

    <div>
        <i icon="fa-male" class="button  fa fa-fw fa-male" aria-hidden="true"></i>
        <i icon="fa-female" class="button  fa fa-fw fa-female" aria-hidden="true"></i>
        <i icon="fa-child" class="button  fa fa-fw fa-child" aria-hidden="true"></i>
        <i icon="fa-blind" class="button  fa fa-fw fa-blind" aria-hidden="true"></i>
        <i icon="fa-smile-o" class="button  fa fa-fw fa-smile-o" aria-hidden="true"></i>
        <i icon="fa-meh-o" class="button  fa fa-fw fa-meh-o" aria-hidden="true"></i>
        <i icon="fa-frown-o" class="button  fa fa-fw fa-frown-o" aria-hidden="true"></i>
        <i icon="fa-hand-spock-o" class="button  fa fa-fw fa-hand-spock-o" aria-hidden="true"></i>
        <i icon="fa-hand-rock-o" class="button  fa fa-fw fa-hand-rock-o" aria-hidden="true"></i>
        <i icon="fa-thumbs-up" class="button  fa fa-fw fa-thumbs-up" aria-hidden="true"></i>
        <i icon="fa-thumbs-o-up" class="button  fa fa-fw fa-thumbs-o-up" aria-hidden="true"></i>
        <i icon="fa-heart" class="button  fa fa-fw fa-heart" aria-hidden="true"></i>
        <i icon="fa-heart-o" class="button  fa fa-fw fa-heart-o" aria-hidden="mobile"></i>

    </div>

    <div>{t}No icon{/t}</div>

    <div>
        <i icon="fa-ban" class="button  fa fa-fw fa-ban discreet" aria-hidden="true"></i>


    </div>
</div>


<body>
<div class="wrapper_boxed">
    <div class="site_wrapper">
        <div class="clearfix "></div>

            <header id="header"  >

                <div id="topHeader" bg="{$header_data.background_image}" onclick="$('#style_dialog').removeClass('hide')"  >

                    <div class="wrapper">

                        <div class="top_nav">
                            <div class="container">

                                <div class="left">


                                    <a href="index.html" id="logo" class="logo" bg="{$header_data.logo}"  >  </a>

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

                                        <ul id="_columns" class="nav navbar-nav three   ">


                                            {foreach from=$header_data.menu.columns item=column key=key}

                                            <li  id="menu_column_{$key}" class="dropdown {if !$column.show}hide{/if} on _column {if $column.type=='three_columns'}yamm-fw  3_columns{else}single_column{/if}  " >
                                                <a  href="" data-toggle="dropdown" class="dropdown-toggle ">
                                                    <i class="fa _column_label_icon {if $column.icon==''}fa-ban {else}{$column.icon}{/if} item_icon padding_right_5  " icon="{$column.icon}" aria-hidden="true"></i>  <span class="_column_label" contenteditable="true">{$column.label}</span>
                                                </a>




                                                {if $column.type=='three_columns'}


                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <div class="yamm-content">
                                                                    <div id="_3col_{$key}" class="row">
                                                                        {foreach from=$column.sub_columns item=sub_column key=col_key}




                                                                            {if $sub_column.type=='items'}

                                                                                <ul id="_3c_col{$key}_{$col_key}" class="sortable col-sm-6 col-md-4 list-unstyled two _3c_col {$sub_column.type}" type="{$sub_column.type}">
                                                                                    {foreach from=$sub_column.items item=item}
                                                                                        <li >
                                                                                            <i class="handle aux hide fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
                                                                                            <span class="link"><i class="fa item_icon fa-fw {$item.icon}" icon="{$item.icon}" ></i> <span class="_item_label" contenteditable="true">{$item.label}</span></span>
                                                                                            <i url="{$item.url}" class="fa item_link hide aux fa-chain button very_discreet" aria-hidden="true"></i>
                                                                                            <i class="fa item_delete hide aux fa-trash-o button very_discreet" aria-hidden="true"></i>
                                                                                        </li>


                                                                                    {/foreach}

                                                                                    <span style="margin-left: 30px" class="discreet aux hide  button add_link"><i class="fa fa-plus" style="margin-right: 5px" aria-hidden="true"></i> {t}Add link{/t}</span>

                                                                                </ul>
                                                                            {elseif $sub_column.type=='text'}
                                                                                <ul id="_3c_col{$key}_{$col_key}" class="col-sm-6 col-md-4 list-unstyled two _3c_col {$sub_column.type}" type="{$sub_column.type}">
                                                                                    <li>
                                                                                        <p class="_title" contenteditable="true">{$sub_column.title}</p>
                                                                                    </li>
                                                                                    <li class="dart" style="position: relative">
                                                                                        <div class="aux sys hide" style="position:absolute;top:-12px;width:360px;">
                                                                                            <span>{t}Ideal{/t} 360x120, W must be 360, H max 150</span>
                                                                                            <input style="display:none" type="file" name="update_image" id="update_image_{$key}_{$col_key}" class="image_upload" data-options='{ "width":"360", "max_height":"150"}'/>
                                                                                            <label style="float:right;margin-left:10px;font-weight: normal;cursor: pointer"  for="update_image_{$key}_{$col_key}"><i class="fa fa-upload" aria-hidden="true"></i>  {t}Upload{/t}</label>
                                                                                            <div style="float:right" class="button image_link" url="{$sub_column.url}"><i class="fa fa-link" aria-hidden="true"></i> {t}link{/t}</div>
                                                                                        </div>


                                                                                        <img src="{if $sub_column.image==''}http://placehold.it/360x120{else}{$sub_column.image}{/if}" alt="" class="rimg marb1" image_key="" data-src="{$sub_column.image}"  />


                                                                                        <span class="_text" contenteditable="true">{$sub_column.text}</span>
                                                                                    </li>
                                                                                </ul>
                                                                            {elseif $sub_column.type=='image'}
                                                                                <ul id="_3c_col{$key}_{$col_key}" class="col-sm-6 col-md-4 list-unstyled two _3c_col {$sub_column.type} " type="{$sub_column.type}">
                                                                                    <li>
                                                                                        <p  class="_title"  contenteditable="true">{$sub_column.title}</p>
                                                                                    </li>
                                                                                    <li class="dart" style="position: relative">
                                                                                        <div class="aux sys hide" style="position:absolute;top:-12px;width:360px;">
                                                                                            <span>{t}Must be{/t} 360x240</span>
                                                                                            <input style="display:none" type="file" name="update_image" id="update_image_{$key}_{$col_key}" class="image_upload" data-options='{ "width":"360", "height":"240"}'/>
                                                                                            <label style="float:right;margin-left:10px;font-weight: normal;cursor: pointer"  for="update_image_{$key}_{$col_key}"><i class="fa fa-upload" aria-hidden="true"></i>  {t}Upload{/t}</label>
                                                                                            <div style="float:right" class="button image_link" url="{$sub_column.url}"><i class="fa fa-link" aria-hidden="true"></i> {t}link{/t}</div>
                                                                                        </div>

                                                                                        <img src="{if $sub_column.image==''}http://placehold.it/360x240{else}{$sub_column.image}{/if}" alt="" class="rimg marb1" image_key="" data-src="{$sub_column.image}"  />

                                                                                    </li>
                                                                                </ul>
                                                                            {elseif $sub_column.type=='departments' or   $sub_column.type=='families' or  $sub_column.type=='web_departments' or   $sub_column.type=='web_families'}
                                                                                <ul id="_3c_col{$key}_{$col_key}" class="col-sm-6 col-md-4 list-unstyled two _3c_col {$sub_column.type}" type="{$sub_column.type}" page="{$sub_column.page}" page_label="{$sub_column.page_label}"  >
                                                                                    <li class="title">
                                                                                        <p contenteditable="true">{$sub_column.label}</p>
                                                                                    </li>
                                                                                    {foreach from=$store->get_categories({$sub_column.type},{$sub_column.page},'menu') item=item}
                                                                                        <li class="item">
                                                                                            <span><i class="fa fa-caret-right" style="margin-right:5px" ></i> <span>{$item['label']}</span>
                                                                                            {if $item['new']}<b class="mitemnew">{t}New{/t}</b>{/if}</li>
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


                                                            {foreach from=$column.items item=item key=item_key}
                                                                {if $item.type=='item'}
                                                                    <li class="_item" type="item" >
                                                                        <a href="">
                                                                            <i class="handle aux hide fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
                                                                            <span class="_label" contenteditable="true">{$item['label']}</span>
                                                                            <i url="{$item.url}" class="fa item_link hide aux fa-chain button very_discreet" aria-hidden="true"></i>
                                                                            <i class="fa item_delete hide aux fa-trash-o button very_discreet" aria-hidden="true"></i>
                                                                        </a>
                                                                    </li>

                                                                {elseif $item.type=='submenu'}
                                                                    <li id="_item_submenu_{$key}_{$item_key}" type="submenu"  class="_item _submenu dropdown-submenu mul">
                                                                        <a tabindex="-1" href="">
                                                                            <i class="handle aux hide fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
                                                                            <span  class="_submenu_label" contenteditable="true">{$item['label']}</span>
                                                                            <i class="fa submenu_expand  fa-caret-right button" aria-hidden="true"></i>
                                                                            <i class="fa item_delete hide aux fa-trash-o button very_discreet" aria-hidden="true"></i>
                                                                        </a>
                                                                        <ul class="dropdown-menu submenu sortable">
                                                                            {foreach from=$item.sub_items item=sub_item}
                                                                                <li class="_sub_item">
                                                                                    <a href="">
                                                                                        <i class="handle aux hide fa fa-arrows very_discreet fa-fw" aria-hidden="true"></i>
                                                                                        <span class="_label" contenteditable="true">{$sub_item.label}</span>
                                                                                        <i url="{$sub_item.url}" class="fa item_link hide aux fa-chain button very_discreet" aria-hidden="true"></i>
                                                                                        <i class="fa item_delete hide aux fa-trash-o button very_discreet" aria-hidden="true"></i>
                                                                                    </a>

                                                                                </li>
                                                                            {/foreach}
                                                                            <li>
                                                                                <span  class="aux hide discreet">
                                                                                    <span class="link add_single_column_submenu_link" style="margin-left:10px">{t}Add link{/t}</span>
                                                                                </span>
                                                                            </li>
                                                                        </ul>
                                                                    </li>
                                                                {/if}
                                                            {/foreach}
                                                            <li>
                                                                <span  class="aux hide discreet">
                                                                    <span class="link add_single_column_link" style="margin-left:10px">{t}Add link{/t}</span>
                                                                    <span class="link add_single_column_submenu" style="float:right;margin-right:10px">{t}Add submenu{/t}</span>
                                                                </span>
                                                            </li>


                                                        </ul>

                                                {/if}

                                            </li>




                                            {/foreach}











                                        </ul>

                                    </div>

                                </div>

                            </nav><!-- end Navigation Menu -->

                            <div class="menu_right2">
                                <div class="search_hwrap two">


                                </div>
                            </div><!-- end search bar -->

                        </div>

                    </div>

                </div>

            </header>



          


</div>



    <script>




        $(document).on('click', '.item_delete', function (e) {

            $(this).closest('li').remove()
        })


        $(document).on('click', '.item_link,.image_link', function (e) {

            $('#input_container_link').removeClass('hide').offset({
                top:$(this).offset().top-20 ,
                left:$(this).offset().left+$(this).width()+5    }).data('item',$(this))
                ,$('#input_container_link').find('input').val($(this).attr('url')).focus()


            if($('#input_container_link').offset().left+$('#input_container_link').width()>$(window).width()){
                $('#input_container_link').offset({
                    left:$(window).width()-$('#input_container_link').width()-40
                })
           }


        })






        $(document).on('click', '.add_link', function (e) {

             $("#link_stem_cell").clone().attr('id','').insertBefore($(this))
        })

        $(document).on('click', '.add_single_column_link', function (e) {

            $("#single_column_link_stem_cell").clone().attr('id','').insertBefore($(this).closest('li'))
        })

        $(document).on('click', '.add_single_column_submenu_link', function (e) {

            $("#single_column_submenu_link_stem_cell").clone().attr('id','').insertBefore($(this).closest('li'))
        })

        $(document).on('click', '.add_single_column_submenu', function (e) {

            $("#single_column_submenu_stem_cell").clone().attr('id','').insertBefore($(this).closest('li'))
        })




        function close_item_edit_link() {
            $('#input_container_link').addClass('hide')

            $('#input_container_link').data('item').attr('url',$('#input_container_link').find('input').val())

        }





        $(document).on('click', 'a', function (e) {
            if (e.which == 1 && !e.metaKey && !e.shiftKey) {

                return false
            }
        })



        $(document).on('click', '.submenu_expand', function (e) {

            $('.dropdown-menu.submenu').css('display', '')


            $(this).closest('.dropdown-submenu').find('.dropdown-menu').css('display', 'block')



        })

        $(document).on('click', '.background_visibility', function (e) {






            if($(this).attr('id')=='logo_visibility'){

                    var tags='#logo,#logo_edit';
                var tag='#logo';


            }else if($(this).attr('id')=='header_background_visibility'){

                var tags='#topHeader,#topHeader_edit';
                var tag='#topHeader';

            }

            //console.log(tag)

            if($(this).hasClass('fa-eye')){
                $(this).removeClass('fa-eye').addClass('fa-eye-slash')



                $(tags).css('background-image','none')

            }else{
                $(this).addClass('fa-eye').removeClass('fa-eye-slash')

                //console.log($(tag).attr('bg'))

                $(tags).css('background-image', 'url('+$(tag).attr('bg')+')')

            }


        })





        $(document).on('click', '.item_icon', function (e) {

            
            
            $('#icons_control_center').removeClass('hide').offset({
                top:$(this).offset().top-69 ,
                left:$(this).offset().left+$(this).width()    }).data('item',$(this))


        })

        $('#icons_control_center').on('click', 'i', function (e) {

            //console.log($('#icons_control_center').data('item'))

            $('#icons_control_center').data('item').removeClass (function (index, className) {

                //console.log(className)

                //console.log((className.match (/\bfa-\S+/g) || []).join(' '))

                return (className.match (/\bfa-\S+/g) || []).join(' ');
            }).addClass('fa-fw').addClass($(this).attr('icon'))


            if($(this).attr('icon')=='fa-ban'){
                $('#icons_control_center').data('item').attr('icon','')
            }else{
                $('#icons_control_center').data('item').attr('icon',$(this).attr('icon'))
            }


            $('#icons_control_center').addClass('hide')


            $('#save_button', window.parent.document).addClass('save button changed valid')


        })




        $("body").on('DOMSubtreeModified', ".header", function() {
            $('#save_button').addClass('save button')
        });

        function save_header(){

            if(! $('#save_button', window.parent.document).hasClass('save')){
                 return;
            }

            $('#save_button', window.parent.document).find('i').addClass('fa-spinner fa-spin')


            color={
            }

            header_data={
                color:[],

            }

            header_data.logo=$('#logo').attr('bg')
            header_data.background_image=$('#topHeader').attr('bg')

            header_data.background_repeat=''
            header_data.logo_show=($('#logo_visibility').hasClass('fa-eye-slash')?false:true)
            header_data.background_image_show=($('#header_background_visibility').hasClass('fa-eye-slash')?false:true)


            color['header']=rgb2hex($('#topHeader').css('color'))
            color['header_background']=rgb2hex($('#topHeader').css('background-color'))

            color['menu']=rgb2hex($('#trueHeader').css('color'))
            color['menu_background']=rgb2hex($('#trueHeader').css('background-color'))
            color['menu_background_highlight']=rgb2hex($('#trueHeader').css('border-bottom-color'))
            color['menu_text_background_highlight']=rgb2hex($('#menu_active_edit').css('color'))




            color['items']=rgb2hex($('#items_color_edit').css('color'))
            color['items_color_edit_title']=rgb2hex($('#items_color_edit_title').css('color'))
            color['items_background']=rgb2hex($('#items_color_edit').css('background-color'))
            color['items_background_border']=rgb2hex($('#items_color_edit').css('border-color'))


            header_data.color=color

            header_data.menu={ columns: []}


            $('li._column').each(function(i, obj) {

                _sub_columns=[]
                _items=[]

                if($(obj).hasClass('3_columns')){

                    $('#'+$(obj).attr('id')+'  ._3c_col').each(function(i, obj2) {

                        _3c_col = { }

                        var type=$(obj2).attr('type')

                        if(type=='departments' || type=='families' || type=='web_departments' || type=='web_families' ){
                            _3c_col.type=$(obj2).attr('type')
                            _3c_col.page=$(obj2).attr('page')
                            _3c_col.page_label=$(obj2).attr('page_label')
                            _3c_col.label=$(obj2).find('.title p').html()


                        }else if(type=='items' ){

                            _3c_col.type=$(obj2).attr('type')
                            _3c_col.label=''
                            _3c_col.items=[]

                            //console.log($(obj2))


                            $('#'+$(obj2).attr('id')+'  li' ).each(function(i, obj3) {

                                _3c_col.items.push({
                                    label:$(obj3).find('._item_label').html(),
                                    icon:$(obj3).find('.item_icon').attr('icon'),
                                    url:$(obj3).find('.item_link').attr('url')
                                })

                            })


                        }else if(type=='text' ){
                            _3c_col.type=$(obj2).attr('type')
                            _3c_col.title=$(obj2).find('._title').html()
                            _3c_col.image=$(obj2).find('img.rimg').data('src')
                            _3c_col.url=$(obj2).find('.image_link').attr('url')
                            _3c_col.text=$(obj2).find('._text').html()
                        }else if(type=='image' ){
                            _3c_col.type=$(obj2).attr('type')
                            _3c_col.title=$(obj2).find('._title').html()
                            _3c_col.image=$(obj2).find('img.rimg').data('src')
                            _3c_col.url=$(obj2).find('.image_link').attr('url')
                        }

                        _sub_columns.push(_3c_col)
                    })

                }else if($(obj).hasClass('single_column')){


                    $('#'+$(obj).attr('id')+'  ._item').each(function(i, obj2) {

                        _item = { }


                        var type=$(obj2).attr('type')


                        if(type=='submenu' ){

                            _item.type=$(obj2).attr('type')
                            _item.label=$(obj2).find('._submenu_label').html()

                            _sub_items=[]

                            $('#'+$(obj2).attr('id')+' ul.submenu li._sub_item  ' ).each(function(i, obj3) {

                                _sub_items.push({
                                    label:$(obj3).find('._label').html(),
                                    url:$(obj3).find('.item_link').attr('url')
                                })

                            })

                            _item.sub_items=_sub_items

                        }else if(type=='item' ){
                            _item.type=$(obj2).attr('type')
                            _item.label=$(obj2).find('._label').html()
                            _item.url=$(obj2).find('.item_link').attr('url')
                        }

                        _items.push(_item)
                    })

                }

                //console.log($(obj))


                header_data.menu.columns.push({
                    type:($(obj).hasClass('3_columns')?'three_columns':'single_column'),
                    show:($(obj).hasClass('hide')?false:true),
                    label:$(obj).find('._column_label').html(),
                    icon:$(obj).find('._column_label_icon').attr('icon'),
                    sub_columns:_sub_columns,
                    items:_items,

                })



            })





            var request = '/ar_edit_website.php?tipo=save_header&header_key={$header_key}&header_data=' +encodeURIComponent(Base64.encode(JSON.stringify(header_data)));


            $.getJSON(request, function (data) {


                $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

            })


        }


        var droppedFiles = false;


        $(document).on('change', '.image_upload', function (e) {



            var ajaxData = new FormData();

            //var ajaxData = new FormData( );
            if (droppedFiles) {
                $.each(droppedFiles, function (i, file) {
                    ajaxData.append('files', file);
                    return false;
                });
            }


            $.each($(this).prop("files"), function (i, file) {
                ajaxData.append("files[" + i + "]", file);
                return false;
            });


            ajaxData.append("tipo", 'upload_images')
            ajaxData.append("parent", 'header')
            ajaxData.append("parent_key", '{$header_key}')
            ajaxData.append("options", JSON.stringify($(this).data('options')))
            ajaxData.append("response_type", 'webpage')

            var element=$(this)

           $.ajax({
                url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


                complete: function () {

                }, success: function (data) {

                   // console.log(data)

                    if (data.state == '200') {

                        //console.log(element)

                        if(element.attr('id')=='upload_logo'){
                            $('#logo,#logo_edit').css('background-image','url('+data.image_src+')')
                            $('#logo_visibility').addClass('fa-eye').removeClass('hide')
                            $('#logo').attr('bg',data.image_src)

                        }else if(element.attr('id')=='upload_header_background'){
                            $('#topHeader,#topHeader_edit').css('background-image','url('+data.image_src+')')
                            $('#topHeader').attr('bg',data.image_src)
                            $('#header_background_visibility').addClass('fa-eye').removeClass('hide')

                        }else{
                            element.closest('.dart').find('img.rimg').attr('src', data.image_src ).attr('image_key', data.img_key).data('src', data.image_src )

                        }

                        $('#save_button', window.parent.document).addClass('save button changed valid')

                    } else if (data.state == '400') {
                        swal({
                            title: data.title, text: data.msg, confirmButtonText: "{t}OK{/t}"
                        });
                    }

                   element.val('')

                }, error: function () {

                }
            });


        });


        function move_column_label(pre, post) {

            //console.log(pre+' '+post)

            if (post > pre) {


                $('#_columns ._column:eq(' + pre + ')').insertAfter('#_columns ._column:eq(' + post + ')');
            } else {


                $('#_columns ._column:eq(' + pre + ')').insertBefore('#_columns ._column:eq(' + post + ')');
            }

        }

        function move_column(key,pre, post) {

            //console.log(key+' '+pre+' '+post)


            if (post > pre) {


                $('#_3col_'+key+' ._3c_col:eq(' + pre + ')').insertAfter('#_3col_'+key+' ._3c_col:eq(' + post + ')');
            } else {


                $('#_3col_'+key+' ._3c_col:eq(' + pre + ')').insertBefore('#_3col_'+key+' ._3c_col:eq(' + post + ')');
            }

        }


        function show_column(key) {

            $("#menu_column_" + key).find('.dropdown-menu:not(.submenu)').css('display', 'block')
            $("#menu_column_" + key).find('.dropdown-menu.submenu:first').css('display', 'block')

            $('.yamm ul.nav li.dropdown').removeClass('on')

            $('.aux').removeClass('hide')

            $('.sortable').sortable({
                handle: '.handle' });



        }

        function hide_column(key) {

            $("#menu_column_" + key).find('.dropdown-menu').css('display', '')
            $('.yamm ul.nav li.dropdown').addClass('on')
            $('.aux').addClass('hide')

            $('#input_container_link').addClass('hide')

        }

        function hide_column_label(key){

            //console.log('#menu_column_'+key)
            $('#menu_column_'+key).addClass('hide')
        }
        function show_column_label(key){
            $('#menu_column_'+key).removeClass('hide')
        }

        function change_column(type,key,subkey){
            //console.log(type+' x '+key+' y '+subkey)


            var ul=$('#_3col_'+key+' ._3c_col:eq(' + subkey + ')')

            if(ul.hasClass(type)){
                return;
            }

            if(type=='departments' || type=='families' ||  type=='web_departments' ||  type=='web_families' ){
                var clone=$('#catalogue_stem_cell').clone()
            }else{
                var clone=$('#'+type+'_stem_cell').clone()
            }



            if(type=='text' || type=='image'){
                var id='update_image_'+key+'_'+subkey
                clone.find('input').attr('id',id)
                clone.find('label').attr('for',id)

            }




            ul.removeClass('departments families web_departments web_families items text image')
            ul.addClass(type)
            ul.html(  clone.html() )

            ul.attr('type',type)

            if(type=='departments' || type=='families' ||  type=='web_departments' ||  type=='web_families' ){

                var page='0-10'
                var page_label='1-10'

                ul.attr('page',page)
                ul.attr('page_label',page_label)

                $.getJSON( "ar_products.php?tipo=store_categories&key={$store->id}&type="+type+"&page="+page, function( data ) {

                    //console.log(data.items)

                    for (i = 0; i <data.items.length; i++) {
                        //console.log(data.items[i])


                        var html='<li class="item"><a href="'+data.items[i].url+'">' +
                        '<i class="fa fa-caret-right"></i> ' +
                        '<span contenteditable="true">'+data.items[i].label+'</span></a></li>';


                        ul.append(html)


                    }



                });

            }else{
                ul.removeAttr('page')
                ul.removeAttr('page_label')
            }



        }

        function edit_catalogue_paginator(page,page_label,key,subkey){



            var ul=$('#_3col_'+key+' ._3c_col:eq(' + subkey + ')')



                ul.attr('page',page)
            ul.attr('page_label',page_label)


            //console.log('#_3col_'+key+' ._3c_col:eq(' + subkey + ')')


                ul.find('.item').remove()

                $.getJSON( "ar_products.php?tipo=store_categories&key={$store->id}&type="+ul.attr('type')+"&page="+page, function( data ) {

                    //console.log(data.items)

                    for (i = 0; i <data.items.length; i++) {
                        //console.log(data.items[i])





                        var html='<li class="item"><a href="'+data.items[i].url+'">' +
                            '<i class="fa fa-caret-right"></i> ' +
                            '<span contenteditable="true">'+data.items[i].label+'</span></a></li>';


                        ul.append(html)


                    }



                });




        }

        function edit_column_type(type,key){



            var li=$('#menu_column_'+key)
            var ul=$('#menu_column_'+key+' > ul.dropdown-menu')

            if(type=='three_columns'){

                li.removeClass('single_column').addClass('yamm-fw  3_columns')

                var clone=$('#three_columns_stem_cell').clone();
                clone.find('div.row').attr('id','_3col_'+key)

                ul.removeAttr('role').removeClass('multilevel sortable').html(clone.html())

            }else{
                li.addClass('single_column').removeClass('yamm-fw  3_columns')
                ul.attr('role','menu').addClass('multilevel sortable').html($('#single_column_stem_cell').clone().html())
            }



        }


      
        $('#colorPicker_header').tinycolorpicker();
        $('#colorPicker_menu').tinycolorpicker();
        $('#colorPicker_items').tinycolorpicker();

        function rgb2hex(rgb){
            rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
            return (rgb && rgb.length === 4) ? "#" +
                ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
                ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
                ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
        }

        function ColorLuminance(hex, lum) {
            // validate hex string
            hex = String(hex).replace(/[^0-9a-f]/gi, '');
            if (hex.length < 6) {
                hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
            }
            lum = lum || 0;
            // convert to decimal and change luminosity
            var rgb = "#", c, i;
            for (i = 0; i < 3; i++) {
                c = parseInt(hex.substr(i*2,2), 16);
                c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
                rgb += ("00"+c).substr(c.length);
            }
            return rgb;
        }

        function getContrastYIQ(hexcolor){
            //console.log(hexcolor)


            var r = parseInt(hexcolor.substr(1,2),16);
            var g = parseInt(hexcolor.substr(3,2),16);
            var b = parseInt(hexcolor.substr(5,2),16);



            var yiq = ((r*299)+(g*587)+(b*114))/1000;

            //console.log(yiq)

            return (yiq >= 128) ? '#555' : '#ddd';
        }



        $('.colorPicker').on("change", function () {
            var color = rgb2hex($(this).find('.colorInner').css('backgroundColor'))

            //console.log($(this).attr('id'))
            if ($(this).attr('id') == 'colorPicker_header') {


                $('#topHeader').css("background-color", color)
                $('#topHeader_edit').css("background-color", color)

                $('#topHeader').css("color", getContrastYIQ(color))
                $('#topHeader_edit').css("color", getContrastYIQ(color))


            } else if ($(this).attr('id') == 'colorPicker_menu') {


                var menu_color=getContrastYIQ(color)
                var menu_background=color
                var menu_background_highlight= ColorLuminance(color,-.25)
                var menu_text_background_highlight=getContrastYIQ(menu_background_highlight)

                $('#trueHeader').css("background-color", menu_background)
                $('#trueHeader').css("border-bottom-color", menu_background_highlight)
                $('#trueHeader').css("color", menu_color)

                $('#trueHeader_edit').css("background-color", menu_background)
                $('#trueHeader_edit').css("color", menu_color)

                $('#trueHeader_edit').css("border-bottom-color", menu_background_highlight)


                $('#menu_active_edit').css("background-color", menu_background_highlight)
                $('#menu_active_edit').css("color", menu_text_background_highlight)



            }else if ($(this).attr('id') == 'colorPicker_items') {



                var color_border=ColorLuminance(color,-.25)
                var text_color=getContrastYIQ(color)
                var text_color_title= ColorLuminance(text_color,-.3)

                console.log(text_color)


                $('.yamm .dropdown-menu').css("background-color", color)

                $('.dropdown-menu li a').hover(function(e) {


                    $(this).css("background-color",e.type === "mouseenter"?text_color:color)
                    $(this).css("color",e.type === "mouseenter"?color:text_color)
                    $(this).find('span').css("color",e.type === "mouseenter"?color:text_color)


                });



                $('  .dart').css("color", text_color)
                $('.list-unstyled li p').css("color", text_color_title)
                $('.list-unstyled li i').css("color", text_color)
                $('.list-unstyled li span').css("color", text_color)
                $('.list-unstyled span.link').css("color", text_color)
                $('.list-unstyled a.link').css("color", text_color)

                $('.dropdown-menu a').css("color", text_color)
                $('.dropdown-menu a span').css("color", text_color)


                $('#items_color_edit').css("background-color", color)
                $('#items_color_edit').css("color", text_color)
                $('#items_color_edit').css("border-color", color_border)
                $('#items_color_edit_title').css("color", text_color_title)

                $('#items_color_edit').css("border-bottom-color", color_border)

            }


            $('#save_button', window.parent.document).addClass('save button changed valid')

        });


        $("body").on('DOMSubtreeModified', "#header", function() {
            $('#save_button', window.parent.document).addClass('save button changed valid')
        });

    </script>




</body>
</html>
