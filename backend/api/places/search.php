<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/geo.php';

st_start_session();
st_require_method('GET');
$userId = st_require_login();

/* ---------------- Input ---------------- */

$allowedCategories = ['all', 'hospital', 'police', 'fuel', 'hotel', 'restaurant'];
$category = $_GET['category'] ?? 'all';
if (!in_array($category, $allowedCategories, true)) {
    $category = 'all';
}

$query = trim((string) ($_GET['q'] ?? ''));

// Default to Nairobi CBD if the browser could not provide a location.
$lat = isset($_GET['lat']) && $_GET['lat'] !== '' ? (float) $_GET['lat'] : -1.2833;
$lng = isset($_GET['lng']) && $_GET['lng'] !== '' ? (float) $_GET['lng'] : 36.8167;

$radius = isset($_GET['radius']) ? (int) $_GET['radius'] : 20000;
$radius = max(1000, min(100000, $radius));

/* ---------------- Helpers ---------------- */

function st_places_category_from_tags(array $tags): string {
    if (($tags['amenity'] ?? null) === 'hospital') return 'hospital';
    if (($tags['amenity'] ?? null) === 'police') return 'police';
    if (($tags['amenity'] ?? null) === 'fuel') return 'fuel';
    if (($tags['amenity'] ?? null) === 'restaurant') return 'restaurant';
    if (($tags['tourism'] ?? null) === 'hotel') return 'hotel';
    return 'other';
}

function st_places_hours_label(array $tags): array {
    $hours = $tags['opening_hours'] ?? null;
    if ($hours === '24/7') {
        return ['Open 24 hrs', true];
    }
    if ($hours) {
        return [$hours, false];
    }
    return ['Hours not listed', false];
}

// st_distance_km() is nullable by contract (it accepts optional coordinates
// elsewhere in the app, e.g. journeys with no GPS fix yet). Every call site
// in this file always has four real floats, so null is unreachable here —
// but the wrapper makes that guarantee explicit instead of assuming callers
// remember it, and keeps a null from ever reaching the JSON response.
function st_places_safe_distance_km(float $lat1, float $lng1, float $lat2, float $lng2): float {
    return st_distance_km($lat1, $lng1, $lat2, $lng2) ?? 0.0;
}

function st_places_address(array $tags): string {
    $parts = array_filter([
        $tags['addr:housenumber'] ?? null,
        $tags['addr:street'] ?? null,
        $tags['addr:city'] ?? $tags['addr:suburb'] ?? null,
    ]);
    return $parts ? implode(', ', $parts) : 'Address not available';
}

// FIX: this used to swallow every failure silently (bad HTTP status,
// timeout, TLS error — all just became a plain `null`), so a 502 from
// this file gave no way to tell *why* the upstream call failed. It now
// logs the curl error / HTTP code to the PHP error log so the real
// cause is actually visible. $timeoutSeconds is now a parameter (was
// hardcoded to 12) because Overpass category queries legitimately need
// longer than a simple Nominatim text search.
function st_places_curl(string $url, array $headers = [], ?string $postBody = null, int $timeoutSeconds = 20): ?array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => $timeoutSeconds,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    if ($postBody !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['data' => $postBody]));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErrno = curl_errno($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false || $httpCode !== 200) {
        error_log(sprintf(
            'SafariTrak places lookup failed: url=%s http_code=%s curl_errno=%s curl_error=%s',
            $url,
            $httpCode,
            $curlErrno,
            $curlError ?: 'none'
        ));
        return null;
    }

    $decoded = json_decode($response, true);
    if (!is_array($decoded)) {
        error_log('SafariTrak places lookup returned invalid JSON from: ' . $url);
        return null;
    }

    return $decoded;
}

// Replace with a real contact address/domain before shipping — Nominatim's
// usage policy requires a genuine identifying User-Agent, not a placeholder.
$userAgent = 'SafariTrak/1.0 (contact: support@safaritrak.app)';

/* ---------------- Free-text search (Nominatim) ---------------- */

if ($query !== '') {
    $deltaDeg = $radius / 111000; // rough metres-to-degrees conversion
    $viewbox = ($lng - $deltaDeg) . ',' . ($lat + $deltaDeg) . ',' . ($lng + $deltaDeg) . ',' . ($lat - $deltaDeg);

    $url = 'https://nominatim.openstreetmap.org/search'
        . '?format=jsonv2&addressdetails=1&extratags=1&limit=25'
        . '&bounded=1&viewbox=' . urlencode($viewbox)
        . '&q=' . urlencode($query);

    $results = st_places_curl($url, ['User-Agent: ' . $userAgent], null, 20);

    if ($results === null) {
        st_json_error('Could not reach the places service right now. Please try again.', 502);
        exit;
    }

    $places = [];
    foreach ($results as $r) {
        $placeLat = (float) ($r['lat'] ?? 0);
        $placeLng = (float) ($r['lon'] ?? 0);
        if (!$placeLat || !$placeLng) {
            continue;
        }

        // FIX: "Undefined array key 'class'" — Nominatim doesn't include
        // a `class` field on every result (e.g. some boundary/place
        // results omit it entirely), so this has to be read with `??`
        // instead of assumed present.
        $tags = $r['extratags'] ?? [];
        $rClass = $r['class'] ?? null;
        $rType = $r['type'] ?? null;
        $inferredCategory = st_places_category_from_tags([
            'amenity' => $rClass === 'amenity' ? $rType : null,
            'tourism' => $rClass === 'tourism' ? $rType : null,
        ]);
        [$hoursLabel, $is24hr] = st_places_hours_label($tags);

        $places[] = [
            'name' => $r['name'] ?? explode(',', $r['display_name'])[0],
            'category' => $inferredCategory,
            'lat' => $placeLat,
            'lng' => $placeLng,
            'distance_km' => st_places_safe_distance_km($lat, $lng, $placeLat, $placeLng),
            'address' => $r['display_name'] ?? 'Address not available',
            'hours' => $hoursLabel,
            'is_24hr' => $is24hr,
        ];
    }

    if ($category !== 'all') {
        $filtered = array_values(array_filter($places, fn($p) => $p['category'] === $category));
        // If filtering wipes out every text match, show the unfiltered set
        // rather than leaving the user with nothing for a place they named.
        if (!empty($filtered)) {
            $places = $filtered;
        }
    }

    usort($places, fn($a, $b) => $a['distance_km'] <=> $b['distance_km']);

    st_json_ok(['places' => array_slice($places, 0, 30), 'mode' => 'search']);
    exit;
}

/* ---------------- Category browse (Overpass) ---------------- */

$categoryTags = [
    'hospital' => ['amenity', 'hospital'],
    'police' => ['amenity', 'police'],
    'fuel' => ['amenity', 'fuel'],
    'hotel' => ['tourism', 'hotel'],
    'restaurant' => ['amenity', 'restaurant'],
];

$targets = $category === 'all' ? $categoryTags : [$category => $categoryTags[$category]];

// FIX: the 504s in the log are Overpass's own proxy giving up on the
// query server-side — not a client timeout we can raise our way out
// of. `category=all` fans out into 10 clauses (5 amenity types x
// node+way), and Overpass has to scan the *entire* radius for all 10
// tag combinations before `out center 80;` can even limit anything.
// A single category is already 5x cheaper, so only "all" needs its
// radius reined in to keep the scan finishing in time.
$effectiveRadius = $radius;
if ($category === 'all') {
    $effectiveRadius = min($radius, 7000);
}

$clauses = [];
foreach ($targets as [$key, $value]) {
    $clauses[] = 'node["' . $key . '"="' . $value . '"](around:' . $effectiveRadius . ',' . $lat . ',' . $lng . ');';
    $clauses[] = 'way["' . $key . '"="' . $value . '"](around:' . $effectiveRadius . ',' . $lat . ',' . $lng . ');';
}

// FIX: this used to be `out center;` with no cap, so a category=all
// query (5 amenity types × 20km radius near a dense city center) could
// pull back hundreds of elements and hundreds of KB — the log showed
// curl timing out at 20s with 365KB *still incoming*. We only ever use
// the nearest 30 anyway (sliced below), so capping Overpass's own
// output keeps the response small and fast regardless of category
// count or how dense the area is.
$ql = '[out:json][timeout:25];(' . implode('', $clauses) . ');out center 80;';

// FIX: overpass-api.de alone is a single point of failure — it's
// frequently overloaded and returns 502/504 on its own. Try it, then
// fall back to the kumi.systems mirror before giving up. Overpass gets
// a longer client-side timeout than Nominatim since category queries
// are heavier and the public server itself can be slow (~7s was seen
// for even a single trivial node lookup).
$overpassEndpoints = [
    'https://overpass-api.de/api/interpreter',
    'https://overpass.kumi.systems/api/interpreter',
];

$result = null;
foreach ($overpassEndpoints as $endpoint) {
    $result = st_places_curl($endpoint, ['User-Agent: ' . $userAgent], $ql, 35);
    if ($result !== null && isset($result['elements'])) {
        break;
    }
}

if ($result === null || !isset($result['elements'])) {
    st_json_error('Could not reach the places service right now. Please try again.', 502);
    exit;
}

$places = [];
foreach ($result['elements'] as $el) {
    $tags = $el['tags'] ?? [];
    $placeLat = $el['lat'] ?? ($el['center']['lat'] ?? null);
    $placeLng = $el['lon'] ?? ($el['center']['lon'] ?? null);
    if ($placeLat === null || $placeLng === null) {
        continue;
    }

    $placeCategory = st_places_category_from_tags($tags);
    $name = $tags['name'] ?? ($tags['brand'] ?? ucfirst($placeCategory));
    [$hoursLabel, $is24hr] = st_places_hours_label($tags);

    $places[] = [
        'name' => $name,
        'category' => $placeCategory,
        'lat' => (float) $placeLat,
        'lng' => (float) $placeLng,
        'distance_km' => st_places_safe_distance_km($lat, $lng, (float) $placeLat, (float) $placeLng),
        'address' => st_places_address($tags),
        'hours' => $hoursLabel,
        'is_24hr' => $is24hr,
    ];
}

usort($places, fn($a, $b) => $a['distance_km'] <=> $b['distance_km']);

st_json_ok(['places' => array_slice($places, 0, 30), 'mode' => 'nearby']);
exit;