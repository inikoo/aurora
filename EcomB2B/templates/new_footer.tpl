



<script>
function getStyles(properties) {
    if (!properties) return {};

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

const footerTheme1 = {
    code: "FooterTheme1",
    name: "Footer 1",
    data: {
        fieldValue: {
            container: {
                properties: {
                    text: {
                        color: "#FFFFFF",
                        fontFamily: null
                    },
                    border: {
                        top: {
                            value: 0
                        },
                        left: {
                            value: 0
                        },
                        unit: "px",
                        color: "#000000",
                        right: {
                            value: 0
                        },
                        bottom: {
                            value: 0
                        },
                        rounded: {
                            unit: "px",
                            topleft: {
                                value: 0
                            },
                            topright: {
                                value: 0
                            },
                            bottomleft: {
                                value: 0
                            },
                            bottomright: {
                                value: 0
                            }
                        }
                    },
                    margin: {
                        top: {
                            value: 0
                        },
                        left: {
                            value: 0
                        },
                        unit: "px",
                        right: {
                            value: 0
                        },
                        bottom: {
                            value: 0
                        }
                    },
                    padding: {
                        top: {
                            value: 96
                        },
                        left: {
                            value: 28
                        },
                        unit: "px",
                        right: {
                            value: 28
                        },
                        bottom: {
                            value: 96
                        }
                    },
                    background: {
                        type: "color",
                        color: "rgba(0, 0, 0, 1)", // Corrected color syntax here
                        image: {
                            original: null
                        }
                    }
                }
            },
        }
    },
};


document.addEventListener("DOMContentLoaded", function() {
    console.log('mounted')
    const properties = footerTheme1 && footerTheme1.data && footerTheme1.data.fieldValue && footerTheme1.data.fieldValue.container && footerTheme1.data.fieldValue.container.properties;
    const element = document.getElementById('footer_container');
    console.log('zzzz', getStyles(properties));
    
    if (element) {
        Object.assign(element.style, getStyles(properties));
    } else {
        console.warn('Element with id "footer_container" not found');
    }
});

</script>

<pre>
    hello
</pre>

<div>
    <div id="footer_container" class="tw-py-24 md:tw-px-7">
        <div class="">
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-4 tw-gap-3 md:tw-gap-8">
                <div class=" tw-px-4 md:tw-px-0 tw-grid tw-gap-y-2 md:tw-gap-y-6 tw-h-fit">
                    <div class="tw-px-4 md:tw-px-0 tw-grid tw-grid-cols-1 tw-gap-y-2 md:tw-gap-y-6 tw-h-fit">
                        <!-- v-for="(item, index) in modelValue.column['column_1']['data']" -->
                        <div>
                            <div>
                                <div
                                    class="tw-hidden md:tw-block tw-grid tw-grid-cols-1 md:tw-cursor-default tw-space-y-1 tw-border-b tw-pb-2 md:tw-border-none">
                                    <div class="tw-flex">
                                        <div class="tw-w-fit">
                                            <span class="tw-text-xl tw-font-semibold tw-w-fit tw-leading-6">
                                                <div>
                                                    <p>Help</p>
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <!-- v-for="(sub, subIndex) in item.data" -->
                                        <div>
                                            <ul class="tw-hidden md:tw-block tw-space-y-1">
                                                <li>
                                                    <div class="tw-flex tw-items-center tw-gap-2">
                                                        <div class="tw-w-full">
                                                            <span class="tw-text-sm tw-block">
                                                                <div>
                                                                    <p><a target="_blank" rel="noopener noreferrer nofollow" href="https://www.ancientwisdom.biz/contact.sys">Contact Us</a></p>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="tw-block md:tw-hidden">
                                    <div >
                                        <div
                                            class="tw-grid tw-grid-cols-1 md:tw-cursor-default tw-space-y-1 tw-border-b tw-pb-2 md:tw-border-none tw-w-full">
                                            <div class="tw-flex tw-justify-between">
                                                <div class="tw-flex">
                                                    <div class="tw-w-fit">
                                                        <span class="tw-text-xl tw-font-semibold tw-leading-6">
                                                            <div>
                                                                <p>Help</p>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div icon="open ? faAngleDown : faAngleUp" class="tw-w-3 tw-h-3"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <div>
                                                <div>
                                                    <!-- v-for="(sub, subIndex) in item.data" -->
                                                    <div>
                                                        <ul class="tw-block tw-space-y-1">
                                                            <li>
                                                                <div class="tw-flex tw-items-center">
                                                                    <div class="tw-w-full">
                                                                        <span class="tw-text-sm tw-block">
                                                                            <div>
                                                                                <p><a target="_blank" rel="noopener noreferrer nofollow" href="https://www.ancientwisdom.biz/contact.sys">Contact Us</a></p>
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="md:tw-hidden tw-mb-6 md:tw-mb-5 tw-bg-[#9c7c64] md:tw-bg-transparent tw-text-center md:tw-text-left tw-pt-4 tw-pb-6 tw-space-y-4 md:tw-py-0 md:tw-space-y-0">
                    <h2 class="tw-text-xl tw-tracking-wider tw-font-semibold md:tw-mt-8 md:tw-mb-4">Get Social with Us!</h2>
                    <div class="tw-flex md:tw-space-x-6 md:tw-mb-4 tw-justify-around md:tw-justify-start">
                        <!-- v-for="item of modelValue.socialData" -->
                        <a target="_blank">
                            <div icon="item.icon" class="tw-text-2xl"></div>
                        </a>
                    </div>
                </div>

                <div class="tw-flex tw-flex-col tw-flex-col-reverse tw-gap-y-6 md:tw-block">
                    <div>
                        <div class="tw-flex tw-flex-wrap -tw-mx-4">
                            <!-- v-for="payment in modelValue.PaymentData.data" -->
                            <div class="tw-w-full md:tw-w-1/3 tw-px-4 tw-mb-8">
                                <div class="tw-flex tw-items-center tw-justify-center md:tw-justify-start tw-space-x-4">
                                    <img src="payment.image" alt="payment.name" class="tw-px-1 tw-h-4">
                                </div>
                            </div>
                        </div>
                        <address
                            class="tw-mt-10 md:tw-mt-0 tw-not-italic tw-mb-4 tw-text-center md:tw-text-left tw-text-xs md:tw-text-sm tw-text-gray-300">
                            <div>
                                Ancient Wisdom Marketing Ltd. Affinity Park, Europa Drive Sheffield, S9 1XT
                            </div>
                        </address>

                        <div class="tw-flex tw-justify-center tw-gap-x-8 tw-text-gray-300 md:tw-block">
                            <div>
                                <p>Vat No: GB764298589 </br> Reg. No: 04108870</p>
                            </div>
                        </div>
                        <div
                            class="tw-hidden md:tw-block tw-mb-6 md:tw-mb-5 tw-bg-[#9c7c64] md:tw-bg-transparent tw-text-center md:tw-text-left tw-pt-4 tw-pb-6 tw-space-y-4 md:tw-py-0 md:tw-space-y-0">
                            <h2 class="tw-text-xl tw-tracking-wider tw-font-semibold md:tw-mt-8 md:tw-mb-4">Get Social with Us!</h2>
                            <div class="tw-flex md:tw-space-x-6 md:tw-mb-4 tw-justify-around md:tw-justify-start">
                                <!-- v-for="item of modelValue.socialData" -->
                                <a target="_blank" href="item.link"><div icon="item.icon" class="tw-text-2xl"></div></a>
                            </div>
                        </div>
                    </div>
                    <div
                        class="tw-border-b tw-border-gray-500 md:tw-border-none tw-flex tw-items-center tw-space-x-2 tw-px-5 tw-pb-4 md:tw-pb-0 md:tw-px-0">
                        <i class="tw-text-4xl md:tw-text-3xl fab fa-whatsapp tw-text-green-500"></i>
                        <span class="tw-w-10/12 md:tw-w-full md:tw-text-sm">
                            <div>
                                "Subscribe to the WhatsApp messages and benefit from exclusive discounts."
                            </div>
                        </span>
                    </div>
                </div>
            </div>


            <div class="tw-text-[10px] md:tw-text-base tw-border-t tw-mt-8 tw-pb-2 tw-pt-2 md:tw-pb-0 md:tw-pt-4 tw-text-center">
                <div>
                    Copyright c 2024 Aurora. All rights reserved. Terms of Use Privacy Policy
                </div>
            </div>
        </div>
    </div>
</div>