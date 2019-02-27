<div class="subject_profile" style="padding-bottom: 20px">
    <div style="float:left;width:600px">

        <div>
            <span style="padding-top: 0px;margin-top: 0px"><span class=" padding_right_10 Status_Icon">{$deal->get('Status Icon')}</span> <span class="Duration" >{$deal->get('Duration')}</span> </span>
        </div>
            <h1 style="margin: 20px 0px">{$deal->get('Deal Term Allowances Label')}</h1>

        <div class="offer_text_banner">{$deal->get('Deal Icon')} <span class="name">{$deal->get('Deal Name Label')}</span> <span class="term">{$deal->get('Deal Term Label')}</span> <span class="allowance">{$deal->get('Deal Allowance Label')}</span></div>








    </div>

    <div class="showcase" style="float:right;width:400px">


            <table border=0 style="width: 100%">


                <tr class="top">
                    <td class="label">{t}Customers{/t}</td>
                    <td class="aright"> {$deal->get('Used Customers')}</td>
                </tr>
                <tr>
                    <td class="label">{t}Orders{/t}</td>
                    <td class="aright"> {$deal->get('Used Orders')}</td>
                </tr>
            </table>



    </div>

    <div style="clear: both;padding-bottom: 20px"></div>

</div>



