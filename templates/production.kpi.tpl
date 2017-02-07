{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 February 2017 at 18:19:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{assign "kpi" $supplier_production->get_kpi('Month To Day')}
<div style="border-bottom:1px solid #ccc;padding:20px">
<div style="border:1px solid #eee;padding:10px;font-size:150%;width:200px" title="{$kpi.formatted_amount} /{$kpi.formatted_hrs} ">{$kpi.formatted_kpi}</div>
</div>