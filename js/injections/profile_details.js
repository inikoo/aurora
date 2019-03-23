$('ul#theme_options_ul li').on("click", function () {
    $('body').removeClass().addClass($('ul#theme_options_ul li.selected').data("value"));
});