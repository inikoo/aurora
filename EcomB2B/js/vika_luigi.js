(async () => {
    if (window.location.hostname === "www.aw-indonesia.com") {
        console.log("Hello Indonesia!")

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
                            name: "Product",
                            type: "product",
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
            // Hide the original input Aurora
            const originalInput = document.getElementById("header_search_input")
            originalInput.classList.add("hide")

            // Show input for Luigi
            const inputLuigi = document.getElementById("inputLuigi")
            inputLuigi.classList.remove("hide")
        }

        const showSearchResult = () => {
            const originalInput = document.getElementById("legacy_search")
            originalInput.classList.add("hide")
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
