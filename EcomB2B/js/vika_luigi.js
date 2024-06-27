function stringToBoolean(str) {
    if (str.toLowerCase() === 'true') {
        return true;
    } else {
        return false;
    }
}

// Init: Auto Complete
const LBInitAutocomplete = async (luigiTrackerId, fieldsRemoved) => {
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
            attributes: ['product_code'],
        },
        "#inputLuigi"
    )

    console.log("Init autocomplete")
}

// Init: Search
const LBInitSearch = async (luigiTrackerId, fieldsRemoved) => {
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
        "#new_search"
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



document.addEventListener("DOMContentLoaded", () => {
    (async () => {
        if (window.location.hostname === "www.aw-indonesia.com") {
            console.log("Hello Indonesia!")

            const luigiTrackerId = "483878-588294"
            let listFieldsRemoved
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
                const originalInput = document.getElementById("header_search_input")
                if (originalInput) {
                    originalInput.classList.add("hide")
                }

                // Input: Luigi
                const inputLuigi = document.getElementById("inputLuigi")
                if (inputLuigi) {
                    inputLuigi.classList.remove("hide")
                    inputLuigi.addEventListener('keypress', function(event) {
                        if (event.key === 'Enter') {
                            const query = inputLuigi.value;
                            if (query) {
                                console.log('query:', query)
                                window.location.href = `/search.sys?q=${encodeURIComponent(query)}`;
                            }
                        }
                    });
                }
            }

            // Page: Result the search
            const showSearchResult = () => {
                const originalInput = document.getElementById("legacy_search")
                if (originalInput) {
                    console.log('Original Input: ', originalInput)
                    originalInput?.classList.add("hide")
                }
            }
            

            if(deviceType === 'desktop') {
                importStyleCSS()
                showInputAutocomplete()
                showSearchResult()
                LBInitAutocomplete(luigiTrackerId, listFieldsRemoved)
                LBInitSearch(luigiTrackerId, listFieldsRemoved)
            } else if (deviceType === 'mobile') {
                importStyleCSS()
                showSearchResult()
                LBInitAutocomplete(luigiTrackerId, listFieldsRemoved)

                const groupInputLuigi = document.getElementById('groupInputLuigi')
                if (groupInputLuigi) groupInputLuigi.style.display = 'flex'

                LBInitSearch(luigiTrackerId, listFieldsRemoved)
            }

        } else {
            console.log("Hello world!!")
        }
    })()
})