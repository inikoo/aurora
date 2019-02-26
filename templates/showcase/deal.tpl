<div class="subject_profile" style="padding-bottom: 20px">
    <div style="float:left;width:600px">


        <span style="padding-top: 0px;margin-top: 0px"><span class="big padding_right_10 Status_Icon">{$deal->get('Status Icon')}</span> <span class="Duration" style="position: relative;top:-5px">{$deal->get('Duration')}</span> </span>
            <h1 style="margin: 20px 0px">{$deal->get('Deal Term Allowances Label')}</h1>

            <div style="border:1px solid #ccc;padding:10px 20px;margin-bottom: 20px;line-height: 170%">{$deal->get('Deal Icon')} {$deal->get('Deal Name Label')} {$deal->get('Deal Term Label')} {$deal->get('Deal Allowance Label')}</div>








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



