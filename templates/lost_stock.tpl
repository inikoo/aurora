{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 17:48:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div style="border-bottom: 1px solid #ccc">


<table border=0 class="filter" style="margin:20px 20px">
    <tr class="strong">
        <td></td>
        <td >{t}Parts{/t}</td>
        <td class="aright">{t}Amount{/t}</td>

    </tr>
    <tr>

        <td>{t}Lost{/t}</td>
        <td class="aright Lost_Parts">{$elements_data['Lost']['Parts']}</td>

        <td class="aright Lost_Amount">{$elements_data['Lost']['Amount']}</td>
    </tr>

    <tr>
        <td >{t}Damaged{/t}</td>
        <td class="aright Broken_Parts">{$elements_data['Broken']['Parts']}</td>

        <td class="aright Broken_Amount">{$elements_data['Broken']['Amount']}</td>
    </tr>
    <tr>
        <td >{t}Errors{/t}</td>
        <td class="aright Error_Parts">{$elements_data['Error']['Parts']}</td>

        <td class="aright Error_Amount">{$elements_data['Error']['Amount']}</td>
    </tr>
</table>

</div>

<script>



</script>