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


        <div>
            <span style="padding-top: 0px;margin-top: 0px"><span class=" padding_right_10 Status_Icon">{$deal_component->get('Status Icon')}</span> <span class="Duration" >{$deal_component->get('Duration')}</span> </span>
        </div>

        <h1 style="margin: 20px 0px">

            <span class="term">{$deal_component->get_formatted_terms()}</span> <i class="fa fa-arrow-right"></i> <span class="allowance">{$deal_component->get_formatted_allowances()} </span></h1>

        <div class="offer_text_banner"><span class="name Deal_Name_Label">{$deal_component->get('Deal Name Label')}</span> <span class="term Deal_Term_Label">{$deal_component->get('Deal Term Label')}</span> <span class="allowance Deal_Component_Allowance_Label">{$deal_component->get('Deal Component Allowance Label')}</span></div>


    </div>

    <div class="showcase" style="float:right;width:400px">


        <table border=0 style="width: 100%">
            {if $campaign->get('Deal Campaign Code')=='CA'}
                <tr class="top">
                    <td class="label">{t}Category{/t}</td>
                    <td class="aright"> <span class="link" onclick="change_view('products/{$category->get('Store Key')}/category/{$category->id}',{ 'tab':'category.deal_components'  })">{$category->get('Code')}</td>
                </tr>
            {/if}

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



