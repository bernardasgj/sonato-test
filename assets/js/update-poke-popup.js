function loadAllPokes() {
    var pokePopup = document.querySelector('[data-pokes-popup-content]'),
        pokePopupContent = document.querySelector('[data-pokes-popup-content] .container')
        loaderMask = document.createElement('div')

        pokePopup.classList.remove('no-height')
    loaderMask.className = 'component-loader-mask'
    pokePopupContent.appendChild(loaderMask)

    setTimeout(function () {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var response = JSON.parse(this.responseText)
                pokePopup.classList.add('no-height')
                pokePopup.innerHTML = response.tableContent
            }
        };

        xmlhttp.open("GET", "update_poke_popup", true)
        xmlhttp.send()
    }, 1000)
}


document.addEventListener('pokesPopup', (event) => {
    loadAllPokes()
});