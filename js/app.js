$(document).ready(function() {
    $('a').click(function(event) {


        change_inikoo_content(this.href)



        event.preventDefault();

    });
});


function change_inikoo_content(url) {

    var $iframe = $('#inikoo_content');
    if ($iframe.length) {


        $iframe.attr('src', url);

    }

    window.history.replaceState(null, 'caca', url.replace('.php', '.app.php'));
}
