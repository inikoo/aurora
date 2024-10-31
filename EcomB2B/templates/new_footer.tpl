<script>
function getStyles(properties) {
    if (!properties) return {};
    console.log('properties')
}


const footerTheme1 = {
                    text: {
                        color: "#FFFFFF",
                        fontFamily: null,
                    },
                    border: {
                        top: {
                            value: 0,
                        },
                        left: {
                            value: 0,
                        },
                        unit: "px",
                        color: "#000000",
                        right: {
                            value: 0,
                        },
                        bottom: {
                            value: 0,
                        },
                        rounded: {
                            unit: "px",
                            topleft: {
                                value: 0,
                            },
                            topright: {
                                value: 0,
                            },
                            bottomleft: {
                                value: 0,
                            },
                            bottomright: {
                                value: 0,
                            },
                        },
                    },
                    margin: {
                        top: {
                            value: 0,
                        },
                        left: {
                            value: 0,
                        },
                        unit: "px",
                        right: {
                            value: 0,
                        },
                        bottom: {
                            value: 0,
                        },
                    },
                    padding: {
                        top: {
                            value: 96,
                        },
                        left: {
                            value: 28,
                        },
                        unit: "px",
                        right: {
                            value: 28,
                        },
                        bottom: {
                            value: 96,
                        },
                    },
                    background: {
                        type: "color",
                        color: "#000000",
                        image: {
                            original: null,
                        },
                    },
                }

console.log('zzzz', footerTheme1)

const element = document.getElementById('footer_container');
console.log('element xx', element)

</script>

<div>
    <div id="footer_container" class="tw-py-24 md:tw-px-7" style="">
        <div class="">
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-4 tw-gap-3 md:tw-gap-8">
                Hello guys
            </div>
        </div>
    </div>
</div>