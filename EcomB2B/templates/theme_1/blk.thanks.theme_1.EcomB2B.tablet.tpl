{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2018 at 22:57:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div   class="{if !$data.show}hide{/if}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">
        <div  class="content">{$data.text}</div>
</div>

<script>

        var d = new Date();
        var time_diff= d.getTime()-{$timestamp};



        if( (time_diff<300000 && time_diff>=0) || {if isset($skip_timestamp_check)}true{else}false{/if} ){


                if(getCookie('au_pu_{$order_key}')!=''  && getCookie('au_pu_done_{$order_key}')==''   ){
                        submit_auTracker()
                }


        }
        ga('auTracker.send', 'pageview');


        document.cookie = "au_pu_{$order_key}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";


        if (typeof(window.history.pushState) == 'function') {
                window.history.pushState(null, '', "/thanks.sys?order_key={$order_key}");
        }


        function submit_auTracker(){

                {if isset($adwords_tag_manager_data)}
                window.dataLayer = window.dataLayer || [];
                function gtag(){
                        dataLayer.push(arguments);}

                gtag('event', 'conversion', {
                        'send_to': '{$adwords_tag_manager_data}',
                        'value': '{$adwords_conversion_data['value']}',
                        'currency': '{$adwords_conversion_data['currency']}',
                        'transaction_id': '{$adwords_conversion_data['transaction_id']}'
                });
                {/if}

                {foreach from=$analytics_items item="item" }
                ga('auTracker.ec:addProduct',{$item} );
                {/foreach}

                ga('auTracker.ec:setAction', 'purchase', {$analytics_data});

                //ga('auTracker.send', 'event', 'Order', 'purchase',data.analytics_data.affiliation, data.analytics_data.gbp_revenue);

                var d = new Date();
                var timestamp=d.getTime()
                d.setTime(timestamp + 1000*60*30);
                var expires = "expires="+ d.toUTCString();
                document.cookie = "au_pu_done_{$order_key}={$order_key};" + expires + ";path=/";


        }


        function getCookie(cname) {
                var name = cname + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(';');


                for(var i = 0; i <ca.length; i++) {
                        var c = ca[i];


                        while (c.charAt(0) == ' ') {
                                c = c.substring(1);
                        }

                        if (c.indexOf(name) == 0) {
                                return c.substring(name.length, c.length);
                        }
                }
                return "";
        }


</script>


