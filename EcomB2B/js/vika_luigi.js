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
            Locale: "en",
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
            RemoveFields: fieldsRemoved,
            ShowAllCallback: () => {  // Called when 'Show All Product' clicked
                onSearchQuery(document.querySelector('#inputLuigi')?.value)
            }
        },
        "#inputLuigi"
    )

    console.log("Init autocomplete")
}

// Init: Search result
const LBInitSearchResult = async (luigiTrackerId, fieldsRemoved) => {
    await import("https://cdn.luigisbox.com/search.js")
    await Luigis.Search(
        {
            TrackerId: luigiTrackerId,
            Locale: "en",
            Theme: "boo",
            Size: 10,
            Facets: ['brand', 'category', 'color'],
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
            let listFieldsRemoved  // To remove data
            let attributesList  // To show attribute shown in product list
            let deviceType


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

                listFieldsRemoved = stringToBoolean(isLogin) ? null : ['price', 'formatted_price', 'price_amount']                
                attributesList = stringToBoolean(isLogin) ? ['product_code', 'formatted_price'] : ['product_code']                
        
                console.log('Script paramter:', scriptSrc.searchParams);
                console.log('list fields removed:', listFieldsRemoved)
        
                // Apply these colors to some elements or use them in your logic
                document.documentElement.style.setProperty('--luigiColor1', '#' + color1);
                document.documentElement.style.setProperty('--luigiColor2', '#' + color2);
                document.documentElement.style.setProperty('--luigiColor3', '#' + color3);
            }


            // Show: Luigi input autocomplete
            const showInputAutocomplete = async () => {
                // Input: Original
                // const originalInput = document.getElementById("header_search_input")
                // if (originalInput) {
                //     originalInput.classList.add("hide")
                // }
                setComponentHide('#header_search_input')


                // Show: Input for auto complete
                const inputLuigi = document.getElementById("inputLuigi")
                if (inputLuigi) {
                    inputLuigi.classList.remove("hide")
                    inputLuigi.addEventListener('keypress', function(event) {
                        if (event.key === 'Enter') {
                            onSearchQuery(inputLuigi.value)
                        }
                    });
                }
            }


            if(deviceType === 'desktop') {
                importStyleCSS()
                showInputAutocomplete()
                setComponentHide('#legacy_search')  // Hide original input in header
                LBInitAutocomplete(luigiTrackerId, listFieldsRemoved, attributesList)
                LBInitSearchResult(luigiTrackerId, listFieldsRemoved)
            } else if (deviceType === 'mobile') {
                importStyleCSS()
                LBInitAutocomplete(luigiTrackerId, listFieldsRemoved, attributesList)

                const groupInputLuigi = document.getElementById('groupInputLuigi')
                if (groupInputLuigi) groupInputLuigi.style.display = 'flex'

                setComponentHide('.sidebar-left .menu-search')  // Hide original input in left sidebar

                LBInitSearchResult(luigiTrackerId, listFieldsRemoved)
                setComponentHide('.content .input-icon')  // Hide original input in Page: Result

                // Add 'enter' listener to the Input Autocomplete (left sidebar)
                const inputAutoComplete = document.querySelector('.luigi-ac-heromobile-input')
                console.log('Input auto complete', inputAutoComplete)
                if (inputAutoComplete) {
                    inputAutoComplete.addEventListener('keypress', function(event) {
                        if (event.key === 'Enter') {
                            onSearchQuery(inputAutoComplete.value)
                            
                        }
                    });
                }
            }

        } else {
            console.log("Hello world!!")
        }
    })()
})