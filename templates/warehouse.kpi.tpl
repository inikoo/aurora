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

    <div class="unselectable button " onclick="change_view('inventory/stock_history')" style="border:1px solid #eee;padding:10px;font-size:150%;float:left;margin-right:20px"  ">{t}Total stock{/t} <i class="fa  hide fa-play error fa-rotate-90" aria-hidden="true"></i> {$kpi.stock.stock_amount}</div>
    {if $kpi.wpm.wpm_formatted_hrs>0}<div style="border:1px solid #eee;padding:10px;font-size:150%;width:200px;float:left;margin-right: 20px" title="{$kpi.wpm.wpm_formatted_amount} /{$kpi.wpm.wpm_formatted_hrs} ">{$kpi.wpm.wpm_formatted_kpi}</div>{/if}
    <div class="hide unselectable button " onclick="change_view('warehouse/{$warehouse->id}/leakages')" style="border:1px solid #eee;padding:10px;font-size:150%;float:left;"  ">{t}Missing stock{/t} <i class="fa  fa-play error fa-rotate-90" aria-hidden="true"></i> {$kpi.stock_leakage.stock_leakage_down_amount}</div>
    <div style="clear: both"></div>

</div>
</div>