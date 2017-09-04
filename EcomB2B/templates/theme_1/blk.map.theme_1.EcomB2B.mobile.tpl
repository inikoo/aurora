{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 September 2017 at 16:51:17 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  " style="Width:100%;" >

  <div class="content-fullscreen">
                <iframe class="responsive-image maps no-bottom" src="{$store->get('Store Google Map URL')}"></iframe>
                <a href="pageapp-map.html" class="button button-red button-s button-full uppercase bold">FullScreen Map</a>
            </div>
            <div class="content ">


                <div class="one-half-responsive contact-information last-column">
                    <div class="container no-bottom">
                        <h4>Contact Information</h4>


                        <p class="contact-information">
                            <a href="tel:{$store->get('Telephone')}" class="contact-call"><i class="ion-ios-telephone"></i>{$store->get('Telephone')}</a>
                            <a href="mailto:{$store->get('Email')}" class="contact-mail"><i class="ion-email"></i>{$store->get('Email')}</a>

                        </p>
                        <p class="contact-information">
                            <strong>{t}Address{/t}</strong><br>
                            {$store->get('Address')}
                        </p>
                    </div>
                </div>

            </div>
</div>
