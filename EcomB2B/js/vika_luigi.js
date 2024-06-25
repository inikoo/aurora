document.addEventListener("DOMContentLoaded", () => {
    (async () => {
        if (window.location.hostname === "www.aw-indonesia.com") {
            console.log("Hello Indonesia!")
            // Get the current script element
            console.log('import meta url: ', import.meta?.url)
            const scriptSrc = new URL(import.meta.url);
            console.log('scriptSrc: ', scriptSrc)
            const scriptElement = document.currentScript;

            console.log('Script element', scriptElement)
            if (scriptElement) {
                // Extract the src attribute value and parse the URL
                const urlParams = new URL(scriptElement?.src || '').searchParams;
        
                // Extract the color parameters
                const color1 = urlParams.get('color1');
                const color2 = urlParams.get('color2');
                const color3 = urlParams.get('color3');
        
                // Now you can use the color1, color2, and color3 variables in your script
                console.log('color1:', color1);
                console.log('color2:', color2);
                console.log('color3:', color3);
        
                // Apply these colors to some elements or use them in your logic
                document.documentElement.style.setProperty('--luigiColor1', color1);
                document.documentElement.style.setProperty('--luigiColor2', color2);
                document.documentElement.style.setProperty('--luigiColor3', color3);
            }


            // Import Luigi
            try {
                await import("https://cdn.luigisbox.com/autocomplete.js")
                console.log("Import: Autocomplete")
                await import("https://cdn.luigisbox.com/search.js")
                console.log("Import: Search")
            } catch (err) {
                console.error("Failed to load Luigis:", err)
                return
            } // For autocomplete

            const luigiTrackerId = "483878-588294"

            // Init: Auto Complete
            const LBInitAutocomplete = async () => {
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
                    },
                    "#inputLuigi"
                )

                console.log("Init autocomplete")
            }

            // Init: Search
            const LBInitSearch = async () => {
                await Luigis.Search(
                    {
                        TrackerId: luigiTrackerId,
                        Locale: "en",
                        Theme: "boo",
                        Size: 10,
                        Facets: ['brand', 'category', 'color', 'price_amount'],
                        DefaultFilters: {
                            type: 'item'
                        },
                        UrlParamName: {
                            QUERY: "q",
                        },
                    },
                    "#inputLuigi",
                    "#new_search"
                )
                console.log("Init autocomplete")
            }

            // Import Luigi CSS
            const loadCSS = () => {
                const link = document.createElement("link")
                link.rel = "stylesheet"
                link.href = "https://cdn.luigisbox.com/autocomplete.css"
                document.head.appendChild(link)
            }

            // Show: Luigi Input
            const loadInputLuigi = () => {
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

            // Page: Result
            const showSearchResult = () => {
                const originalInput = document.getElementById("legacy_search")
                if (originalInput) {
                    console.log('Original Input: ', originalInput)
                    originalInput?.classList.add("hide")
                }
            }

            loadCSS()
            loadInputLuigi()
            showSearchResult()
            LBInitAutocomplete()
            LBInitSearch()
        } else {
            console.log("Hello world!!")
        }
    })()
})