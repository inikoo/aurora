<!-- 
About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2018 at 14:29:01 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
-->



<div class="item">
    <div class="question">
        <i class="fa fa-caret-right bullet fw"></i> How supplier's sales are calculated?
    </div>
    <div class="answer hide">
        <p>
            Each supplier's product has a <em>part</em> associated the sales are calculated over that <em>part</em>.

        </p>
        <p class="think">
            <i class="far fa-fw fa-brain"></i> A <em>part</em> can have more than one supplier.

        </p>

    </div>
</div>

<div class="item">
    <div class="question">
        <i class="fa fa-caret-right bullet fw"></i> When the sales are calculated?
    </div>
    <div class="answer hide">
        <p>
            When an associated part is <b>picked</b> the status of that transaction (ITF) is updated from  <em>Order in progress (OIP)</em> to  <em>Sale</em>.

        </p>
        <p>
            <em>ITFs</em> with status <em>Sale</em> are used to calculate <em>part</em> sales.
        </p>
        <p>
            Part sales are updated in the background when the <em>Delivery note DN</em> state is set as <em>Packed and sealed</em>, it can take few seconds for the figures to update.
        </p>

    </div>
</div>

