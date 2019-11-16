{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 July 2017 at 21:08:48 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if !isset($data.src_mobile)}
    {assign var="src_mobile" value=""}
{else}
    {assign var="src_mobile" value=$data.src_mobile}
{/if}

{if !isset($data.height_mobile)}
    {assign var="height_mobile" value="250"}
{else}
    {assign var="height_mobile" value=$data.height_mobile}
{/if}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} " style="Width:100%;" h="{$data.height}" h_mobile="{$height_mobile}"  src_mobile="{$src_mobile}"  w="1240"  >
<iframe src="https://{$data.src}" width="100%" height="100%" scrolling="no" frameallowtransparency="true" allowfullscreen="true"></iframe>
</div>
