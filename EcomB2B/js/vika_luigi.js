function stringToBoolean(str) {
    if (str.toLowerCase() === 'true') {
        return true;
    } else {
        return false;
    }
}

// Init: Auto Complete
const LBInitAutocomplete = async (luigiTrackerId, fieldsRemoved, attributesList) => {
    await import("https://cdn.luigisbox.com/autocomplete.js")
    await AutoComplete(
        {
            Layout: "heromobile",
            TrackerId: luigiTrackerId,
            Locale: 'en',
            Translations: {
                en: {
                    showBuyTitle: 'Shop Today', // Top Product: Button label
                    // priceFilter: {
                    //     minimumFractionDigits: 0,
                    //     maximumFractionDigits: 2,
                    //     locale: 'en',
                    //     prefixed: true,
                    //     symbol: '£'
                    // }
                }
            },
            RemoveFields: fieldsRemoved,
            Types: [
                {
                    name: "Item",
                    type: "item",
                    size: 7,
                    attributes: attributesList,
                },
                {
                    name: "Query",
                    type: "query",
                },
                {
                    name: "Category",
                    type: "category",
                },
            ],
            ShowAllCallback: () => {  // Called when 'Show All Product' clicked
                onSearchQuery(document.querySelector('#inputLuigi')?.value)
            },
            Actions: [  // Action for Top Product 'Add To Basket'
                {
                    forRow: function(row) {
                        return row['data-autocomplete-id'] == 1 && row.type === 'item'  // Top product
                    },
                    // iconUrl: 'https://cdn-icons-png.freepik.com/256/275/275790.png',
                    title: "Visit product's page",
                    // action: function(e, result) {
                    //     console.log(e, result)
                    //     e.preventDefault();
                    //     alert("Product added to cart");
                    // }
                }
            ]
        },
        "#inputLuigi"
    )

    console.log("Init autocomplete")
}

// Init: Search result
const LBInitSearchResult = async (luigiTrackerId, fieldsRemoved, facetsSearch) => {
    await import("https://cdn.luigisbox.com/search.js")
    await Luigis.Search(
        {
            TrackerId: luigiTrackerId,
            Locale: 'en',
            PriceFilter: {
                decimals: 2,
                prefixed: true,
                symbol: '£'
            },
            Theme: "boo",
            Size: 10,
            Facets: facetsSearch,
            DefaultFilters: {
                type: 'item'
            },
            UrlParamName: {
                QUERY: "q",
            },
            RemoveFields: fieldsRemoved
        },
        "#inputLuigi",
        "#luigi_result_search"
    )

    console.log("Init Search")
}


// Import Luigi CSS style
const importStyleCSS = () => {
    const link = document.createElement("link")
    link.rel = "stylesheet"
    link.href = "https://cdn.luigisbox.com/autocomplete.css"
    document.head.appendChild(link)
}

// Method: add 'hide' class to component
const setComponentHide = (selector) => {
    const selectedComponent = document.querySelector(selector)
    if (selectedComponent) {
        selectedComponent.classList.add("hide")
    }
}

// Method: visit Page: Search with the query
const onSearchQuery = (stringQuery) => {
    if (stringQuery) {
        console.log('query:', stringQuery)
        window.location.href = `/search.sys?q=${encodeURIComponent(stringQuery)}`;
    } else {
        console.log('The query must be filled.',)
    }
}


document.addEventListener("DOMContentLoaded", () => {
    (async () => {
        if (window.location.hostname === "www.aw-indonesia.com") {
            console.log("Hello Indonesia!")

            const luigiTrackerId = "483878-588294"
            let attributesRemoved  // To remove data
            let attributesList = ['product_code'] // To show attribute shown in product list
            let deviceType
            let facetsSearch


            // Get the props
            console.log('import meta url: ', import.meta?.url)
            const scriptSrc = new URL(import.meta.url);

            // Read the parameter
            if (scriptSrc?.searchParams) {
                const color1 = scriptSrc.searchParams.get('color1');
                const color2 = scriptSrc.searchParams.get('color2');
                const color3 = scriptSrc.searchParams.get('color3');
                const isLogin = scriptSrc.searchParams.get('logged_in')
                deviceType = scriptSrc.searchParams.get('device_type')

                if(stringToBoolean(isLogin)) {
                    attributesRemoved = null
                    facetsSearch = ['price_amount', 'brand', 'category', 'color']
                } else {
                    attributesRemoved = ['price', 'formatted_price', 'price_amount']                
                    facetsSearch = ['brand', 'category', 'color']

                }

                // attributesList = stringToBoolean(isLogin) ? [...attributesList, 'formatted_price'] : [...attributesList]                
        
                console.log('Script paramter:', scriptSrc.searchParams);
                console.log('list fields removed:', attributesRemoved)
        
                // Apply these colors to some elements or use them in your logic
                document.documentElement.style.setProperty('--luigiColor1', '#' + color1);
                document.documentElement.style.setProperty('--luigiColor2', '#' + color2);
                document.documentElement.style.setProperty('--luigiColor3', '#' + color3);
            }

            // Show: Luigi input autocomplete
            const showInputAutocomplete = async () => {
                setComponentHide('#header_search_input')

                // Show: Input for autocomplete
                const inputLuigi = document.getElementById("inputLuigi")
                if (inputLuigi) {
                    inputLuigi.classList.remove("hide")
                } else {
                    console.log('Element #inputLuigi is not exist.')
                }
            }


            if(deviceType === 'desktop') {
                importStyleCSS()
                showInputAutocomplete()
                setComponentHide('#legacy_search')  // Hide original input in header
                LBInitAutocomplete(luigiTrackerId, attributesRemoved, attributesList)
                LBInitSearchResult(luigiTrackerId, attributesRemoved, facetsSearch)
            }
            else if (deviceType === 'mobile') {
                importStyleCSS()
                LBInitAutocomplete(luigiTrackerId, attributesRemoved, attributesList)

                const groupInputLuigi = document.getElementById('groupInputLuigi')
                if (groupInputLuigi) groupInputLuigi.style.display = 'flex'

                setComponentHide('.sidebar-left .menu-search')  // Hide original input in left sidebar

                LBInitSearchResult(luigiTrackerId, attributesRemoved, facetsSearch)
                setComponentHide('.content .input-icon')  // Hide original input in Page: Result
                setComponentHide('.page-content .content')
            }

        } else {
            console.log("Hello world!!")
        }
    })()
})