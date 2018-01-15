{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 August 2017 at 00:18:17 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}"  class="{$data.type} _block  ">





        <div class="notification-small bg-blue-light tap-hide  ">
            <strong class="bg-blue-dark"><i class="ion-information-circled"></i></strong>
            <p>
                {if isset($content._no_products_ordered_yet) and $labels._no_products_ordered_yet!=''}{$labels._no_products_ordered_yet}{else}{t}No products has been ordered{/t}{/if}
            </p>
        </div>



    <div class="landing-homepage">
        <div class="ios_style_buttons-page ">
            <div class="landing-wrapper">


                <div class="content no-bottom"><div class="deco"></div></div>
                <!-- Left Top Menu -->
                <ul>
                    <li>
                        <a href="/">
                            <i class="ion-ios-home bg-red-dark"></i>
                            <em>{t}Home{/t}</em>
                        </a>
                    </li>
                    <li class="hide">
                        <a href="catalogue.sys">
                            <i class="ion-ios-grid-view-outline bg-green-dark"></i>
                            <em>{t}Catalogue{/t}</em>
                        </a>
                    </li>
                    <li class="hide">
                        <a href="favourites.sys">
                            <i class="ion-heart bg-red-light"></i>
                            <em>{t}Favourites{/t}</em>
                        </a>
                    </li>

                </ul>


            </div>

        </div>
    </div>

    <div class="coverpage-clear"></div>

          
</div>

