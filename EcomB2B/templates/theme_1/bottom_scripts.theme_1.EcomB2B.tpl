{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2017 at 08:42:15 CEST, Tranava Slovakia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}



{if $detected_device=='mobile' }
     <script src="/theme_1/mobile_menu/js/classie.js"></script>
		<script src="/theme_1/mobile_menu/js/gnmenu.js"></script>
		<script>
			new gnMenu( document.getElementById( 'gn-menu' ) );
		</script>
{/if}