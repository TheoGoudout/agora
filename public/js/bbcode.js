// Converts BBCode to HTML code
// See http://stackoverflow.com/questions/24200048/show-bbcode-in-html-page-with-php
function bbc2html(string) {
    return '<p>' + string
        .replace(/(\[b\])([\s\S]*?)(\[\/b\])/g            , '<strong>$2</strong>')
        .replace(/(\[i\])([\s\S]*?)(\[\/i\])/g            , '<em>$2</em>')
        .replace(/(\[u\])([\s\S]*?)(\[\/u\])/g            , '<u>$2</u>')
        .replace(/(\[ul\])([\s\S]*?)(\[\/ul\])/g          , '<ul>$2</ul>')
        .replace(/(\[li\])([\s\S]*?)(\[\/li\])/g          , '<li>$2</li>')
        .replace(/(\[url=)([\s\S]*?)(\])(.*?)(\[\/url\])/g, '<a href="$2" target="_blank">$4</a>')
        .replace(/(\[url\])([\s\S]*?)(\[\/url\])/g        , '<a href="$2" target="_blank">$2</a>')
        .replace(/(\[br\])/g                              , '</p><p>') + '</p>';
}

/* Wait for the DOM content to be loaded */
document.addEventListener("DOMContentLoaded", function(event) {
    var bbcodes = document.getElementsByClassName("bbcode");

    for (var i = 0; i < bbcodes.length; i++) {
        bbcodes[i].innerHTML = bbc2html(bbcodes[i].innerHTML);
    }
});
