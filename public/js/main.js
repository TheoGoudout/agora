/* Wait for the DOM content to be loaded */
document.addEventListener("DOMContentLoaded", function(event) {
    var hiddables = document.getElementsByClassName("hiddable");

    var hider = document.createElement('div');
    hider.innerText = '-';
    hider.className = 'left';
    hider.onclick = function() {
        this.nextElementSibling.classList.toggle('hidden');
        this.innerText = this.innerText == '-' ? '+' : '-';
    };

    for (var i = 0; i < hiddables.length; i++) {
        hiddables[i].parentNode.insertBefore(hider, hiddables[i]);
    }
});
