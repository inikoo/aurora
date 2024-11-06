



<script>
function getStyles(properties) {
    if (!properties) return {

    };

    return {
        height: (properties.dimension && properties.dimension.height && properties.dimension.height.value || undefined) + 
                 (properties.dimension && properties.dimension.height && properties.dimension.height.unit || ''),
        width: (properties.dimension && properties.dimension.width && properties.dimension.width.value || undefined) + 
                (properties.dimension && properties.dimension.width && properties.dimension.width.unit || ''),

        color: properties.text && properties.text.color,
        fontFamily: properties.text && properties.text.fontFamily,

        paddingTop: (properties.padding && properties.padding.top && properties.padding.top.value || 0) + 
                    (properties.padding && properties.padding.unit || ''),
        paddingBottom: (properties.padding && properties.padding.bottom && properties.padding.bottom.value || 0) + 
                       (properties.padding && properties.padding.unit || ''),
        paddingRight: (properties.padding && properties.padding.right && properties.padding.right.value || 0) + 
                      (properties.padding && properties.padding.unit || ''),
        paddingLeft: (properties.padding && properties.padding.left && properties.padding.left.value || 0) + 
                     (properties.padding && properties.padding.unit || ''),

        marginTop: (properties.margin && properties.margin.top && properties.margin.top.value || 0) + 
                   (properties.margin && properties.margin.unit || ''),
        marginBottom: (properties.margin && properties.margin.bottom && properties.margin.bottom.value || 0) + 
                      (properties.margin && properties.margin.unit || ''),
        marginRight: (properties.margin && properties.margin.right && properties.margin.right.value || 0) + 
                     (properties.margin && properties.margin.unit || ''),
        marginLeft: (properties.margin && properties.margin.left && properties.margin.left.value || 0) + 
                    (properties.margin && properties.margin.unit || ''),

        background: (properties.background && properties.background.type === 'color') 
            ? properties.background.color 
            : 'url(' + (properties.background && properties.background.image && properties.background.image.source && properties.background.image.source.original || '') + ')',

        backgroundPosition: (properties.background && properties.background.type === 'image') ? 'center' : '',
        backgroundSize: (properties.background && properties.background.type === 'image') ? 'cover' : '',

        borderTop: (properties.border && properties.border.top && properties.border.top.value || 0) + 
                   (properties.border && properties.border.unit || '') + ' solid ' + 
                   (properties.border && properties.border.color || ''),
        borderBottom: (properties.border && properties.border.bottom && properties.border.bottom.value || 0) + 
                      (properties.border && properties.border.unit || '') + ' solid ' + 
                      (properties.border && properties.border.color || ''),
        borderRight: (properties.border && properties.border.right && properties.border.right.value || 0) + 
                     (properties.border && properties.border.unit || '') + ' solid ' + 
                     (properties.border && properties.border.color || ''),
        borderLeft: (properties.border && properties.border.left && properties.border.left.value || 0) + 
                    (properties.border && properties.border.unit || '') + ' solid ' + 
                    (properties.border && properties.border.color || ''),

        borderTopRightRadius: (properties.border && properties.border.rounded && properties.border.rounded.topright && properties.border.rounded.topright.value || 0) + 
                              (properties.border && properties.border.rounded && properties.border.rounded.unit || ''),
        borderBottomRightRadius: (properties.border && properties.border.rounded && properties.border.rounded.bottomright && properties.border.rounded.bottomright.value || 0) + 
                                 (properties.border && properties.border.rounded && properties.border.rounded.unit || ''),
        borderBottomLeftRadius: (properties.border && properties.border.rounded && properties.border.rounded.bottomleft && properties.border.rounded.bottomleft.value || 0) + 
                                (properties.border && properties.border.rounded && properties.border.rounded.unit || ''),
        borderTopLeftRadius: (properties.border && properties.border.rounded && properties.border.rounded.topleft && properties.border.rounded.topleft.value || 0) + 
                             (properties.border && properties.border.rounded && properties.border.rounded.unit || ''),
    };
}


document.addEventListener("DOMContentLoaded", function() {
    console.log('new_footer mounted 10')
    const element = document.getElementById('footer_container');
    console.log('zzzz', getStyles({$wowsbar_footer_data['data']['fieldValue']['container']['properties']|json_encode}));
    
    if (element) {
        Object.assign(element.style, getStyles({$wowsbar_footer_data['data']['fieldValue']['container']['properties']|json_encode}));
    } else {
        console.error('Element with id "footer_container" not found');
    }
});

console.log('wowsbar Data:', {$wowsbar_footer_data|json_encode})

</script>

<pre style="display: none">
    hello    >>{$wowsbar_footer_data|@print_r}<<  xV6
</pre>

<style>
    #footer_container a {
        color: inherit;
    }

    #footer_container p {
        margin: 0;
        line-height: 1.3rem;
        font-size: 1rem;
    }

    #footer_container p strong {
        color: inherit;
    }

    #footer_container li {
        list-style-type: none;
    }

    // 
    #footer_container details {
        width: 100%;
        margin: 0 auto;
        margin-bottom: .5rem;
        box-shadow: 0 .1rem 1rem -.5rem rgba(0, 0, 0, .4);
        border-radius: 5px;
        overflow: hidden;
    }

    #footer_container summary {
        // padding: 1rem;
        display: block;
        position: relative;
        cursor: pointer;
        user-select: none;
    }

    #footer_container summary:before {
        content: '';
        border-width: .4rem;
        border-style: solid;
        border-color: #fff transparent transparent transparent ;
        position: absolute;
        top: 30%;
        right: 1rem;
        // transform: translateY(-50%);
        transform-origin: 50% 25%;
        transition: .2s transform ease;
    }

    #footer_container details[open] {
        background: rgba(240,240,240,0.15)
    }

    /* THE MAGIC 🧙‍♀️ */
    #footer_container details[open]>summary:before {
        transform: rotate(180deg);
    }


    #footer_container details summary::-webkit-details-marker {
        display: none;
    }

    #footer_container details>ul {
        margin-bottom: 0;
    }
    
</style>
<div>
    <div id="footer_container" class="-tw-mx-2 md:tw-mx-0 tw-pb-24 tw-pt-4 md:tw-pt-8 md:tw-px-16">
        <div class="tw-w-full tw-flex tw-flex-col md:tw-flex-row tw-gap-4 md:tw-gap-8 tw-pt-2 tw-pb-4 md:tw-pb-6 tw-mb-4 md:tw-mb-10 tw-border-0 tw-border-b tw-border-solid tw-border-gray-700">
            <div class="tw-flex-1 tw-flex tw-justify-center md:tw-justify-start ">
                {if $wowsbar_footer_data.data.fieldValue.logo}
                    <img src="{$wowsbar_footer_data.data.fieldValue.logo.source}?v=3" alt="{$wowsbar_footer_data.data.fieldValue.logo.alt}" class="tw-h-auto tw-max-h-20 tw-w-auto tw-min-w-16">
                {/if}
            </div>

            <div class="tw-hidden tw-flex-1 md:tw-flex tw-justify-center ">
                
            </div>

            <div class="tw-flex-1 tw-flex tw-justify-center md:tw-justify-start tw-items-center">
                <a href="mailto:{$wowsbar_footer_data.data.fieldValue.email}" style="font-size: 17px">{$wowsbar_footer_data.data.fieldValue.email}</a>
            </div>
            
            <div class="tw-flex-1 tw-flex tw-flex-col tw-items-center md:tw-items-end tw-justify-center">
                <a href="tel:{$wowsbar_footer_data.data.fieldValue.phone.number}" style="font-size: 17px">{$wowsbar_footer_data.data.fieldValue.phone.number}</a>
                <span class="" style="font-size: 15px">{$wowsbar_footer_data.data.fieldValue.phone.openHours}</span>
            </div>
        </div>

        <div class="">
            <div id="wowsbar_footer_top_part" class=" tw-grid tw-grid-cols-1 md:tw-grid-cols-4 tw-gap-3 md:tw-gap-8">

                {* column_1 *}
                <div class="md:tw-px-0 tw-grid tw-gap-y-3 md:tw-gap-y-6 tw-h-fit">
                    <div class="md:tw-px-0 tw-grid tw-grid-cols-1 tw-gap-y-2 md:tw-gap-y-6 tw-h-fit">
                        {foreach from=$wowsbar_footer_data.data.fieldValue.columns.column_1.data item=block}
                            {* Desktop *}
                            <div class="tw-hidden md:tw-block tw-grid tw-grid-cols-1 md:tw-cursor-default tw-space-y-1 tw-border-b tw-pb-2 md:tw-border-none">
                                <div class="tw-flex tw-text-xl tw-font-semibold tw-w-fit tw-leading-6">
                                    {$block.name}
                                </div>

                                <div>
                                    <!-- v-for="(sub, subIndex) in item.data" -->
                                    <ul class="tw-hidden md:tw-block tw-space-y-3">
                                        {foreach from=$block.data item=link}
                                            <li class="tw-flex tw-w-full tw-items-center tw-gap-2">
                                                <div class="tw-text-sm tw-block">
                                                    {$link.name}
                                                </div>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            </div>

                            {* Mobile *}
                            <div class="tw-block md:tw-hidden">
                                <details class="tw-p-2 md:tw-p-0 tw-transition-all tw-rounded tw-flex tw-justify-between tw-cursor-default tw-border-b tw-border-none tw-w-full">
                                    <summary class="tw-mb-2 md:tw-mb-0 tw-pl-0 md:tw-pl-[2.2rem] tw-text-xl tw-font-semibold tw-leading-6">
                                        {$block.name}
                                    </summary>

                                    <ul class="tw-block tw-space-y-4 tw-pl-0 md:tw-pl-[2.2rem]">
                                        {foreach from=$block.data item=link}
                                            <li class="tw-flex tw-items-center tw-text-sm">
                                                {$link.name}
                                            </li>
                                        {/foreach}
                                    </ul>
                                </details>

                                {*
                                <div>
                                    <div>
                                        <div>
                                            <!-- v-for="(sub, subIndex) in item.data" -->
                                            <div>
                                                <ul class="tw-block tw-space-y-1">
                                                    {foreach from=$block.data item=link}
                                                    <li>
                                                        <div class="tw-flex tw-items-center">
                                                            <div class="tw-w-full">
                                                                    <div class="tw-text-sm tw-block">
                                                                        {$link.name}
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                *}
                            </div>
                        {/foreach}
                    </div>
                </div>

                {* column_2 *}
                <div class="md:tw-px-0 tw-grid tw-gap-y-3 md:tw-gap-y-6 tw-h-fit">
                    <div class="md:tw-px-0 tw-grid tw-grid-cols-1 tw-gap-y-2 md:tw-gap-y-6 tw-h-fit">
                        {foreach from=$wowsbar_footer_data.data.fieldValue.columns.column_2.data item=block}
                            {* Desktop *}
                            <div class="tw-hidden md:tw-block tw-grid tw-grid-cols-1 md:tw-cursor-default tw-space-y-1 tw-border-b tw-pb-2 md:tw-border-none">
                                <div class="tw-flex tw-text-xl tw-font-semibold tw-w-fit tw-leading-6">
                                    {$block.name}
                                </div>

                                <div>
                                    <!-- v-for="(sub, subIndex) in item.data" -->
                                    <ul class="tw-hidden md:tw-block tw-space-y-3">
                                        {foreach from=$block.data item=link}
                                            <li class="tw-flex tw-w-full tw-items-center tw-gap-2">
                                                <div class="tw-text-sm tw-block">
                                                    {$link.name}
                                                </div>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            </div>

                            {* Mobile *}
                            <div class="tw-block md:tw-hidden">
                                <details class="tw-p-2 md:tw-p-0 tw-transition-all tw-rounded tw-flex tw-justify-between tw-cursor-default tw-border-b tw-border-none tw-w-full">
                                    <summary class="tw-mb-2 md:tw-mb-0 tw-pl-0 md:tw-pl-[2.2rem] tw-text-xl tw-font-semibold tw-leading-6">
                                        {$block.name}
                                    </summary>

                                    <ul class="tw-block tw-space-y-4 tw-pl-0 md:tw-pl-[2.2rem]">
                                        {foreach from=$block.data item=link}
                                            <li class="tw-flex tw-items-center tw-text-sm">
                                                {$link.name}
                                            </li>
                                        {/foreach}
                                    </ul>
                                </details>

                                {*
                                <div>
                                    <div>
                                        <div>
                                            <!-- v-for="(sub, subIndex) in item.data" -->
                                            <div>
                                                <ul class="tw-block tw-space-y-1">
                                                    {foreach from=$block.data item=link}
                                                    <li>
                                                        <div class="tw-flex tw-items-center">
                                                            <div class="tw-w-full">
                                                                    <div class="tw-text-sm tw-block">
                                                                        {$link.name}
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                *}
                            </div>
                        {/foreach}
                    </div>
                </div>

                {* column_3 *}
                <div class="md:tw-px-0 tw-grid tw-gap-y-3 md:tw-gap-y-6 tw-h-fit">
                    <div class="md:tw-px-0 tw-grid tw-grid-cols-1 tw-gap-y-2 md:tw-gap-y-6 tw-h-fit">
                        {foreach from=$wowsbar_footer_data.data.fieldValue.columns.column_3.data item=block}
                            {* Desktop *}
                            <div class="tw-hidden md:tw-block tw-grid tw-grid-cols-1 md:tw-cursor-default tw-space-y-1 tw-border-b tw-pb-2 md:tw-border-none">
                                <div class="tw-flex tw-text-xl tw-font-semibold tw-w-fit tw-leading-6">
                                    {$block.name}
                                </div>

                                <div>
                                    <!-- v-for="(sub, subIndex) in item.data" -->
                                    <ul class="tw-hidden md:tw-block tw-space-y-3">
                                        {foreach from=$block.data item=link}
                                            <li class="tw-flex tw-w-full tw-items-center tw-gap-2">
                                                <div class="tw-text-sm tw-block">
                                                    {$link.name}
                                                </div>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            </div>

                            {* Mobile *}
                            <div class="tw-block md:tw-hidden">
                                <details class="tw-p-2 md:tw-p-0 tw-transition-all tw-rounded tw-flex tw-justify-between tw-cursor-default tw-border-b tw-border-none tw-w-full">
                                    <summary class="tw-mb-2 md:tw-mb-0 tw-pl-0 md:tw-pl-[2.2rem] tw-text-xl tw-font-semibold tw-leading-6">
                                        {$block.name}
                                    </summary>

                                    <ul class="tw-block tw-space-y-4 tw-pl-0 md:tw-pl-[2.2rem]">
                                        {foreach from=$block.data item=link}
                                            <li class="tw-flex tw-items-center tw-text-sm">
                                                {$link.name}
                                            </li>
                                        {/foreach}
                                    </ul>
                                </details>

                                {*
                                <div>
                                    <div>
                                        <div>
                                            <!-- v-for="(sub, subIndex) in item.data" -->
                                            <div>
                                                <ul class="tw-block tw-space-y-1">
                                                    {foreach from=$block.data item=link}
                                                    <li>
                                                        <div class="tw-flex tw-items-center">
                                                            <div class="tw-w-full">
                                                                    <div class="tw-text-sm tw-block">
                                                                        {$link.name}
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                *}
                            </div>
                        {/foreach}
                    </div>
                </div>


                {* Social Media Mobile *}
                {*
                    <div class="md:tw-hidden tw-mb-6 md:tw-mb-5 tw-bg-[#9c7c64] md:tw-bg-transparent tw-text-center md:tw-text-left tw-pt-4 tw-pb-6 tw-space-y-4 md:tw-py-0 md:tw-space-y-0">
                        <h2 class="tw-text-xl tw-tracking-wider tw-font-semibold md:tw-mt-8 md:tw-mb-4">Get Social with Us!</h2>
                        <div class="tw-flex md:tw-space-x-6 md:tw-mb-4 tw-justify-around md:tw-justify-start">
                            {foreach from=$wowsbar_footer_data.data.fieldValue.socialMedia item=socmed}
                                <a target="_blank" href="{$socmed.link}">
                                    <i class="{$socmed.icon} tw-text-2xl"></i>
                                </a>
                            {/foreach}
                        </div>
                    </div>
                *}




                <div class="tw-flex tw-flex-col tw-flex-col-reverse tw-gap-y-6 md:tw-block">
                    <div>
                        <address class="tw-mt-10 md:tw-mt-0 tw-not-italic tw-mb-4 tw-text-center md:tw-text-left tw-text-xs md:tw-text-sm tw-text-gray-300">
                            <div>
                                {$wowsbar_footer_data.data.fieldValue.columns.column_4.data.textBox1}
                            </div>
                        </address>

                        <div class="tw-flex tw-justify-center tw-gap-x-8 tw-text-gray-300 md:tw-block">
                            {$wowsbar_footer_data.data.fieldValue.columns.column_4.data.textBox2}
                        </div>

                        
                        {* Payment Data: Paypal, Pastpay, etc *}
                        <div class="tw-flex tw-flex-col tw-items-center tw-gap-y-6 tw-mt-12">
                            {* v-for="payment in modelValue.PaymentData.data" *}
                            {foreach from=$wowsbar_footer_data.data.fieldValue.paymentData.data item=block}
                                <img src="{$block.image}" alt="{$block.name}"   class="tw-h-auto tw-max-h-7 md:tw-max-h-8 tw-max-w-full tw-w-fit">
                            {/foreach}
                        </div>
                    </div>
                {*
                    <div
                        class="tw-border-b tw-border-gray-500 md:tw-border-none tw-flex tw-items-center tw-space-x-2 tw-px-5 tw-pb-4 md:tw-pb-0 md:tw-px-0">
                        <i class="tw-text-4xl md:tw-text-3xl fab fa-whatsapp tw-text-green-500"></i>
                        <span class="tw-w-10/12 md:tw-w-full md:tw-text-sm">
                            <div>
                                "Subscribe to the WhatsApp messages and benefit from exclusive discounts."
                            </div>
                        </span>
                    </div>
                *}
                </div>
            </div>

            
            {* Social Media Desktop *}
            <div class="tw-mb-6 md:tw-mb-5 tw-text-center md:tw-text-left tw-pt-4 tw-pb-6 tw-space-y-4 md:tw-py-0 md:tw-space-y-0">
                <h2 class="tw-text-xl tw-text-center tw-tracking-wider tw-font-semibold md:tw-mt-8 md:tw-mb-4">Get Social with Us!</h2>
                <div class="tw-flex md:tw-gap-x-6 md:tw-mb-4 tw-justify-center">
                    {foreach from=$wowsbar_footer_data.data.fieldValue.socialMedia item=socmed}
                        <a target="_blank" href="{$socmed.link}">
                            <i class="{$socmed.icon} tw-text-2xl"></i>
                        </a>
                    {/foreach}
                </div>
            </div>


            {* Copyright *}
            <div id="wowsbar_footer_top_copyright"  class="tw-text-[10px] md:tw-text-base tw-border-t tw-mt-8 tw-pb-2 tw-pt-2 md:tw-pb-0 md:tw-pt-4 tw-text-center">
                <div>
                    {$wowsbar_footer_data.data.fieldValue.copyright}
                </div>
            </div>
        </div>
    </div>
</div>