const apikey = "bb31f66d0fb046989baa4de55fe544c4"
function clearlist(ele) {
    while (ele.firstChild) {
        ele.removeChild(ele.firstChild);
    }
    ele.style.display = 'none';
}

var timer;
function resetTimer() {
    console.warn("waiting again");
    clearTimeout(timer);
    timer = setTimeout(function() {
        console.warn("done waiting");
        const liste = document.querySelector(".adresse_postale_suggestion");
        clearlist(liste);
        fetch(`https://api.geoapify.com/v1/geocode/autocomplete?text=${input.value}&lang=fr&limit=5&type=amenity&filter=countrycode:fr&format=json&apiKey=${apikey}`)
        .then(response => response.json())
        .then(result => {
            const liste_sugg = new Array();
            if (result.results) {
                result.results.forEach(addr => {
                    const postalCode = addr.postcode;
                    const houseNumber = addr.housenumber;
                    const streetName = addr.street;
                    const city = addr.city;
                    if (postalCode !== undefined && houseNumber !== undefined && streetName !== undefined && city !== undefined) {
                        liste_sugg.push(`${houseNumber} ${streetName}, ${postalCode} ${city}`);
                    }
                });
            }
            if (liste_sugg[0]!=undefined) {
                liste_sugg.forEach(suggestion => {
                    const sugg = document.createElement("div");
                    sugg.addEventListener('click', function () {
                        input.value = sugg.textContent;
                        clearlist(liste);
                    });
                    sugg.addEventListener('mouseover', function () {
                        sugg.style.cursor = 'pointer';
                    });
                    sugg.textContent = suggestion;
                    liste.appendChild(sugg);
                });
                liste.style.display = 'flex';
            }
        })
        .catch(error => console.log('error', error));
    }, 1000)
}

const input = document.querySelector('.adresse_postale');
input.addEventListener('input', function () {
    resetTimer();
});