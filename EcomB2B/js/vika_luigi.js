function stringToBoolean(str) {
    if (str.toLowerCase() === 'true') {
        return true;
    } else {
        return false;
    }
}

// Init: Auto Complete
const LBInitAutocomplete = async (luigiTrackerId, fieldsRemoved, autoCompleteAttributes, localeList, deviceType) => {
    await import("https://cdn.luigisbox.com/autocomplete.js")
    await AutoComplete(
        {
            Layout: "heromobile",
            TrackerId: luigiTrackerId,
            Locale: localeList.language,
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
                    attributes: autoCompleteAttributes,
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
                        if (deviceType === 'desktop') {
                            return row['data-autocomplete-id'] == 1 && row.type === 'item'  // Top product
                        } else {
                            return false
                        }                        
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


// Listening enter on inputLuigi
const replaceSearchIcon = async () => {
    var inputElement = document.getElementById('inputLuigi');
    inputElement.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            window.location.href = '/search.sys?q=' + encodeURIComponent(inputElement.value);
        }
    });

    // Create the new <i> element
    // const newIconSearch = document.createElement('i');
    // newIconSearch.id = 'luigi_search_icon';
    // newIconSearch.className = 'fal fa-search';
    // newIconSearch.style.position = 'absolute';
    // newIconSearch.style.right = '10px';
    // newIconSearch.style.top = '50%';
    // newIconSearch.style.transform = 'translateY(-50%)';
    // newIconSearch.style.fontSize = '20px';
    // inputElement.insertAdjacentElement('afterend', newIconSearch);
    const luigiSearchIcon = document.querySelector('#luigi_search_icon')
    if (luigiSearchIcon) {
        luigiSearchIcon.classList.remove("hide")
        setComponentHide('#header_search_icon')
    }

    console.log('Hello icon.')
}


// Init: Search result
const LBInitSearchResult = async (luigiTrackerId, fieldsRemoved, searchFacets, localeList) => {
    console.log('v-40')
    await import("https://cdn.luigisbox.com/search.js")
    await Luigis.Search(
        {
            TrackerId: luigiTrackerId,
            Locale: localeList.language,
            PriceFilter: {
                decimals: 2,
                prefixed: true,
                symbol: localeList.currencySymbol
            },
            Theme: "boo",
            Size: 12,
            Facets: searchFacets,
            QuicksearchTypes: searchFacets,
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
        // console.log('query:', stringQuery)
        window.location.href = `/search.sys?q=${encodeURIComponent(stringQuery)}`;
    } else {
        console.log('The query must be filled.',)
    }
}


document.addEventListener("DOMContentLoaded", () => {
    (async () => {
        const scriptSrc = new URL(import.meta.url);

        // if (window.location.hostname === "www.aw-indonesia.com" ||
        //     window.location.hostname === "www.ancientwisdom.biz" ||
        //     (window.location.hostname === "www.aw-dropship.com" && scriptSrc.searchParams.get('device_type') === 'tablet')
        // ) {

            console.log("Hello, world L!")

            let luigiTrackerId
            let autoCompleteAttributesRemoved  // To remove data
            let searchAttributesRemoved  // To attribute for Search
            let autoCompleteAttributes = ['product_code'] // To show attribute shown in product list
            let deviceType
            let searchFacets
            let localeList = {
                language: 'en',
                currencySymbol: '£'
            }


            // Get the props
            // console.log('import meta url: ', import.meta?.url)

            // Read the parameter
            if (scriptSrc?.searchParams) {
                const isLogin = scriptSrc.searchParams.get('logged_in')
                deviceType = scriptSrc.searchParams.get('device_type')
                luigiTrackerId = scriptSrc.searchParams.get('trackerId')

                // Set Locale language
                if (scriptSrc.searchParams.get('language')) {
                    localeList.language = scriptSrc.searchParams.get('language')
                }

                // Set Locale: currency
                if (scriptSrc.searchParams.get('language')) {
                    localeList.currencySymbol = scriptSrc.searchParams.get('currency_symbol')
                }

                if(stringToBoolean(isLogin)) {
                    autoCompleteAttributes = ['product_code', 'formatted_price']
                    autoCompleteAttributesRemoved = ['price']

                    searchAttributesRemoved = ['price', 'price_amount']
                    // searchFacets = ['price_amount', 'brand', 'category', 'color']
                    searchFacets = ['brand', 'category', 'color']
                } else {
                    autoCompleteAttributes = ['product_code']
                    autoCompleteAttributesRemoved = ['price', 'formatted_price', 'price_amount']         
                    
                    searchAttributesRemoved = ['price', 'formatted_price', 'price_amount']
                    searchFacets = ['brand', 'category', 'color']

                }
            
        
                // console.log('Script paramter:', scriptSrc.searchParams);
                // console.log('list fields removed:', autoCompleteAttributesRemoved)
        
                // Set CSS variable
                document.documentElement.style.setProperty('--luigiColor1', '#' + scriptSrc.searchParams.get('color1'));
                document.documentElement.style.setProperty('--luigiColor2', '#' + scriptSrc.searchParams.get('color2'));
                document.documentElement.style.setProperty('--luigiColor3', '#' + scriptSrc.searchParams.get('color3'));
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
                LBInitAutocomplete(luigiTrackerId, autoCompleteAttributesRemoved, autoCompleteAttributes, localeList, deviceType)
                replaceSearchIcon()  // Listening the input field and replace icon search
                LBInitSearchResult(luigiTrackerId, searchAttributesRemoved, searchFacets, localeList)
            }
            else if (deviceType === 'mobile' || deviceType === 'tablet') {
                importStyleCSS()
                LBInitAutocomplete(luigiTrackerId, autoCompleteAttributesRemoved, autoCompleteAttributes, localeList, deviceType)

                const groupInputLuigi = document.getElementById('groupInputLuigi')
                if (groupInputLuigi) groupInputLuigi.style.display = 'flex'

                setComponentHide('.sidebar-left .menu-search')  // Hide original input in left sidebar

                // Conditional: Search result
                if (window.location.pathname.includes('/search.sys')) {
                    console.log('Hello!2')
                    LBInitSearchResult(luigiTrackerId, searchAttributesRemoved, searchFacets, localeList)
                    // setComponentHide('.content .input-icon')  // Hide original input in Page: Result
                    setComponentHide('.page-content .content')
                }
            }

        // } else {
        //     console.log("Hello world!!")
        // }
    })()
})