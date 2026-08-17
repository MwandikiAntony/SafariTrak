document.addEventListener('DOMContentLoaded', function () {

    const map =
        L.map('placesMap').setView(
            [-1.286389, 36.817223],
            12
        );

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution:
                '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);

    L.control.zoom({
        position: 'topright'
    }).addTo(map);

    const searchInput =
        document.getElementById('placeSearch');

    const searchButton =
        document.getElementById('searchPlaceBtn');

    const resultsContainer =
        document.getElementById('placesResults');

    let markers = [];

    async function searchPlaces() {

        const query =
            searchInput.value.trim();

        if (!query) {
            return;
        }

        searchButton.disabled =
            true;

        searchButton.innerHTML =
            '<i class="fas fa-spinner fa-spin"></i> Searching';

        resultsContainer.innerHTML =
            '<div class="result">Searching OpenStreetMap...</div>';

        try {

            const url =
                'https://nominatim.openstreetmap.org/search?' +
                'format=json' +
                '&q=' +
                encodeURIComponent(query) +
                '&limit=10' +
                '&countrycodes=ke' +
                '&addressdetails=1';

            const response =
                await fetch(
                    url,
                    {
                        headers: {
                            'Accept':
                                'application/json',
                            'Accept-Language':
                                'en'
                        }
                    }
                );

            if (!response.ok) {
                throw new Error(
                    'Search failed'
                );
            }

            const results =
                await response.json();

            markers.forEach(
                function(marker) {
                    marker.remove();
                }
            );

            markers = [];

            resultsContainer.innerHTML = '';

            if (!results.length) {

                resultsContainer.innerHTML =
                    '<div class="result">' +
                    'No places were found.' +
                    '</div>';

                return;
            }

            results.forEach(
                function(place) {

                    const lat =
                        parseFloat(place.lat);

                    const lng =
                        parseFloat(place.lon);

                    const marker =
                        L.marker(
                            [lat, lng]
                        ).addTo(map);

                    marker.bindPopup(
                        '<strong>' +
                        place.display_name +
                        '</strong>'
                    );

                    markers.push(marker);

                    const result =
                        document.createElement(
                            'div'
                        );

                    result.className =
                        'result';

                    const strong =
                        document.createElement(
                            'strong'
                        );

                    strong.textContent =
                        place.name ||
                        place.display_name;

                    const span =
                        document.createElement(
                            'span'
                        );

                    span.textContent =
                        place.display_name;

                    result.appendChild(
                        strong
                    );

                    result.appendChild(
                        span
                    );

                    result.addEventListener(
                        'click',
                        function() {

                            map.setView(
                                [lat, lng],
                                16
                            );

                            marker.openPopup();

                        }
                    );

                    resultsContainer.appendChild(
                        result
                    );

                }
            );

            if (markers.length) {

                const group =
                    L.featureGroup(
                        markers
                    );

                map.fitBounds(
                    group.getBounds().pad(.2)
                );
            }

        } catch (error) {

            resultsContainer.innerHTML =
                '<div class="result">' +
                'Unable to search for places. Check your internet connection.' +
                '</div>';

        } finally {

            searchButton.disabled =
                false;

            searchButton.innerHTML =
                '<i class="fas fa-search"></i> Search';
        }
    }

    searchButton.addEventListener(
        'click',
        searchPlaces
    );

    searchInput.addEventListener(
        'keydown',
        function(event) {

            if (event.key === 'Enter') {

                event.preventDefault();

                searchPlaces();
            }
        }
    );

});