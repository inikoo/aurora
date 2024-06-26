document.addEventListener("DOMContentLoaded", () => {
    (async () => {
        if (window.location.hostname === "www.aw-indonesia.com") {
            console.log("Hello Indonesia!")
            // Get the current script element
            console.log('import meta url: ', import.meta?.url)
            const scriptSrc = new URL(import.meta.url);
            console.log('scriptSrc: ', scriptSrc)

            if (scriptSrc?.searchParams) {
                // Extract the src attribute value and parse the URL
                // const urlParams = new URL(scriptElement?.src || '').searchParams;
        
                // Extract the color parameters
                const color1 = scriptSrc.searchParams.get('color1');
                const color2 = scriptSrc.searchParams.get('color2');
                const color3 = scriptSrc.searchParams.get('color3');
                const isLogin = scriptSrc.searchParams.get('logged_in') || 'xx'
                
        
                // Now you can use the color1, color2, and color3 variables in your script
                console.log('color1:', color1);
                console.log('color2:', color2);
                console.log('color3:', color3);
                console.log('isLogin:', isLogin);
        
                // Apply these colors to some elements or use them in your logic
                document.documentElement.style.setProperty('--luigiColor1', '#' + color1);
                document.documentElement.style.setProperty('--luigiColor2', '#' + color2);
                document.documentElement.style.setProperty('--luigiColor3', '#' + color3);
            }

            console.log("===============")
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
                        RemoveFields: ['price', 'formatted_price', 'price_amount'],
                        attributes: ['product_code'],
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
                        Facets: ['brand', 'category', 'color'],
                        DefaultFilters: {
                            type: 'item'
                        },
                        UrlParamName: {
                            QUERY: "q",
                        },
                        RemoveFields: ['price', 'formatted_price', 'price_amount']
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

            // const deleteElement = async (selector) => {
            //     const elements = document.querySelectorAll(selector);
            //     console.log('list element', selector, ':', elements)
            //     elements.forEach(element => {
            //         element.remove();
            //     });
            // }

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