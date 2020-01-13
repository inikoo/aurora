{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  13 January 2020  16:28::09  +0800, Kuala Lumpir Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

<style>
    .island{
        flex-grow: 1;
        margin:30px;
        padding:20px 40px;
        border:1px solid #ccc;
    }
    .island td,th{
        padding:5px 10px;
        border-bottom: #DDDDDD;
    }


</style>
<div style="display: flex">
{foreach  from=$tables item=table}
<table class="island" id="{$table.id}">
    <tr>
    <th colspan="3">
    {$table.title}
    </th>
    </tr>
    <tbody>
    {foreach  from=$table.assets item=asset}
        <tr>
            <td>{$asset.icons}</td>
            <td>{$asset.code}</td>
            <td>{$asset.name}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
{/foreach}
</div>