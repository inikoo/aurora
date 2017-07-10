{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 July 2017 at 18:34:39 GMT+8, Cyberjaya, Malaydsia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div class="container" style="min-height:200px;padding:5px 20px">

    <h4>{$webpage->get('Webpage URL')}</h4>

    <i class="fa fa-sitemap" aria-hidden="true" title="{t}Webpage for category{/t}"></i> <span onclick="change_view('products/{$category->get('Store Key')}/category/{$category->id}')" class="link">{$category->get('Code')}</span> {$category->get('Label')}



</div>