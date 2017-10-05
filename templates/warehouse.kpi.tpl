{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 February 2017 at 21:13:29 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{assign "kpi" $warehouse->get_kpi('Month To Day')}
<div style="margin-top: 10px">
<span style="position: absolute;margin-left: 20px" class="small discreet">{t}Month to day{/t}</span>
<div style="border-bottom:1px solid #ccc;padding:20px">

    {if $kpi.formatted_hrs>0}<div style="border:1px solid #eee;padding:10px;font-size:150%;width:200px;float:left;margin-right: 20px" title="{$kpi.formatted_amount} /{$kpi.formatted_hrs} ">{$kpi.formatted_kpi}</div>{/if}
    <div class="unselectable button" onclick="change_view('warehouse/{$warehouse->id}/leakages')" style="border:1px solid #eee;padding:10px;font-size:150%;width:200px;float:left;"  ">{t}Lost stock{/t} <i class="fa hide fa-play error fa-rotate-90" aria-hidden="true"></i> {$kpi.stock_leakage.down.amount}</div>
    <div style="clear: both"></div>

</div>
</div>