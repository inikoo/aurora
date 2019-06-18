<div class="subject_profile" style="padding-bottom: 20px">
    <div style="float:left;width:600px">




        <div>
            <span style="padding-top: 0px;margin-top: 0px"><span class=" padding_right_10 Status_Icon">{$deal->get('Status Icon')}</span> <span class="Duration" >{$deal->get('Duration')}</span> </span>  <b class="strong">{$deal->get('Status')}</b>
        </div>
        {if $campaign->get('Deal Campaign Code')=='CU'}

            {assign 'customer' $deal->get('Trigger Object') }

            <div class="strong" style="clear:both;margin-top: 20px">
                Customer discount: <span class="link">{$customer->get('Name')}</span>
            </div>
        {/if}

            <h1 style="margin: 20px 0px">{$deal->get('Deal Term Allowances Label')}</h1>

        <div class="offer_text_banner">
            <span class="name Deal_Name_Label">{$deal->get('Deal Name Label')}</span> <span class="term Deal_Term_Label">{$deal->get('Deal Term Label')}</span> <span class="allowance Deal_Component_Allowance_Label">{$deal->get('Deal Allowance Label')}</span>
        </div>








    </div>

    <div class="showcase" style="float:right;width:400px">


            <table border=0 style="width: 100%">
                {if $campaign->get('Deal Campaign Code')=='CA'}
                <tr class="top">
                    <td class="label">{t}Category{/t}</td>
                    <td class="aright"> <span class="link" onclick="change_view('products/{$category->get('Store Key')}/category/{$category->id}',{ 'tab':'category.deal_components'  })">{$category->get('Code')}</td>
                </tr>
                {/if}
                {if $campaign->get('Deal Campaign Code')!='CU'}

                <tr class="top">
                    <td class="label">{t}Customers{/t}</td>
                    <td class="aright"> {$deal->get('Used Customers')}</td>
                </tr>
                {/if}
                <tr>
                    <td class="label">{t}Orders{/t}</td>
                    <td class="aright"> {$deal->get('Used Orders')}</td>
                </tr>
            </table>



    </div>

    <div style="clear: both;padding-bottom: 20px"></div>

</div>



