if (window.location.hostname === "www.aw-indonesia.com") {
  console.log("Hello world!1!")
  import("https://cdn.luigisbox.com/autocomplete.js") // For autocomplete

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

  const loadCSS = (href) => {
    const link = document.createElement("link")
    link.rel = "stylesheet"
    link.href = href
    document.head.appendChild(link)
  }

  const loadInputLuigi = () => {
    // const input = document.createElement("input")
    // input.id = "inputSearchLuigi"

    // Hide the original input Aurora
    const originalInput = document.getElementById("header_search_input")
    originalInput.classList.add("hide");

    // Show input for Luigi
    const inputLuigi = document.getElementById("inputLuigi")
    inputLuigi.classList.remove("hide");
  }

  document.addEventListener("DOMContentLoaded", async () => {
    loadCSS("https://cdn.luigisbox.com/autocomplete.css")
    loadInputLuigi()
    LBInitAutocomplete()
  })
} else {
  console.log("Hello world!!")
}
