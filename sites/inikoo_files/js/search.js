var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

function submit_on_enter(e) {
    var key;
    if (window.event) Key = window.event.keyCode; //IE
    else Key = e.which; //firefox     
    if (Key == 13) {
        search();
    }
}

function search() {
    window.location.href = 'search.php?q=' + Dom.get('search').value;
}

function init_search() {
Dom.get('inikoo_search').value=Dom.get('query').value
    Event.addListener('search', "keydown", submit_on_enter);
}
Event.onDOMReady(init_search);