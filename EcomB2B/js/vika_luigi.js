(async () => {
if (window.location.hostname === "www.aw-indonesia.com") {
  console.log("Hello world!1!")

  // Import Luigi
  try {
    await import("https://cdn.luigisbox.com/autocomplete.js")
    console.log("Autocomplete library loaded")
  }
  catch (err) {
    console.error("Failed to load the autocomplete library:", err);
  }; // For autocomplete

  const luigiTrackerId = "483878-588294"
  const LBInitAutocomplete = () => {
    AutoComplete(
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
  }

  // Import Luigi CSS
  const loadCSS = () => {
    const link = document.createElement("link")
    link.rel = "stylesheet"
    link.href = "https://cdn.luigisbox.com/autocomplete.css"
    document.head.appendChild(link)
  }

  // Create Luigi Input
  const loadInputLuigi = () => {

    // Hide the original input Aurora
    const originalInput = document.getElementById("header_search_input")
    originalInput.classList.add("hide");

    // Show input for Luigi
    const inputLuigi = document.getElementById("inputLuigi")
    inputLuigi.classList.remove("hide");
  }

  document.addEventListener("DOMContentLoaded", async () => {
    console.log('Loaded.')
    loadCSS()
    loadInputLuigi()
    LBInitAutocomplete()
  })
} else {
  console.log("Hello world!!")
}
})()