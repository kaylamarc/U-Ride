// Google Places API autocomplete
let autocomplete;

function initAutocomplete() {

    // origin text box
    autocompleteOrigin = new google.maps.places.Autocomplete(
        document.getElementById('origin'),
        {
            types: ['establishment'],
            componentRestrictions: {'country': ['USA']},
            fields: ['place_id', 'geometry', 'name']
        }
    );

    // destination text box
    autocompleteDest = new google.maps.places.Autocomplete(
        document.getElementById('destination'),
        {
            types: ['establishment'],
            componentRestrictions: {'country': ['USA']},
            fields: ['place_id', 'geometry', 'name']
        }
    );

    // Listenters
    autocompleteOrigin.addListener('place_changed', onPlaceChanged);
    autocompleteDest.addListener('place_changed', onPlaceChanged);
}

function onPlaceChanged() {
    var place1 = autocompleteOrigin.getPlace();
    var place2 = autocompleteDest.getPlace();

    if (!place1.geometry) {
        // user did not select a predition; reset the input field
        document.getElementById('origin').placeholder = 'origin';
    }
    else {
        // display details about the valid place
        document.getElementById('details').innerHTML = place.name;
    }

    if (!place2.geometry) {
        // user did not select a predition; reset the input field
        document.getElementById('destination').placeholder = 'destination';
    }
    else {
        // display details about the valid place
        document.getElementById('details').innerHTML = place.name;
    }
}