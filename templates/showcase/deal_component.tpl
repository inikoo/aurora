{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:26 February 2019 at 13:52:42 GMT+88, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}

<div class="subject_profile" style="padding-bottom: 20px">
    <div style="float:left;width:600px">


        <span style="padding-top: 0px;margin-top: 0px"><span class="big padding_right_10 Status_Icon">{$deal_component->get('Status Icon')}</span> <span class="Duration" style="position: relative;top:-5px">{$deal_component->get('Duration')}</span> </span>
        <h1 style="margin: 20px 0px">{$deal_component->get_formatted_terms()} <i class="fa fa-arrow-right"></i> {$deal_component->get_formatted_allowances()} </h1>

        <div style="border:1px solid #ccc;padding:10px 20px;margin-bottom: 20px;line-height: 170%">{$deal_component->get('Deal Component Icon')} {$deal_component->get('Deal Component Name Label')} {$deal_component->get('Deal Component Term Label')} {$deal_component->get('Deal Component Allowance Label')}</div>


    </div>

    <div class="showcase" style="float:right;width:400px">


        <table border=0 style="width: 100%">


            <tr class="top">
                <td class="label">{t}Customers{/t}</td>
                <td class="aright"> {$deal_component->get('Used Customers')}</td>
            </tr>
            <tr>
                <td class="label">{t}Orders{/t}</td>
                <td class="aright"> {$deal_component->get('Used Orders')}</td>
            </tr>
        </table>


    </div>

    <div style="clear: both;padding-bottom: 20px"></div>

</div>



