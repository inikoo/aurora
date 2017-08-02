{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 August 2017 at 22:10:58 CEST, Tranava, Slovalie
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

{if $smarty.server.SERVER_NAME!='ecom.bali'}

<script>
    (function(i,s,o,g,r,a,m){
        i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', '{$account->get('Account Analytics ID')}', 'auto');
    ga('send', 'pageview');

</script>
{/if}