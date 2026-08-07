<?php
// Capture Filter Parameters
$f_search   = isset($_GET['search'])   ? trim($_GET['search'])   : '';
$f_category = isset($_GET['category']) ? trim($_GET['category']) : '';
$f_type     = isset($_GET['type'])     ? trim($_GET['type'])     : '';
$f_location = isset($_GET['location']) ? trim($_GET['location']) : '';
$f_price    = isset($_GET['price'])    ? trim($_GET['price'])    : '';

// Helper to convert price strings to numbers
if (!function_exists('parsePriceValue')) {
    function parsePriceValue($priceStr) {
        if (empty($priceStr)) return 0;
        $val = str_ireplace(['KES. ', 'Ksh.', ',', '/Month'], '', $priceStr);
        $val = trim($val);
        if (stripos($val, 'M') !== false) {
            return (float)str_ireplace('M', '', $val) * 1000000;
        }
        return (float)$val;
    }
}

// Filter properties based on URL query parameters
$filtered_properties = array_filter($properties, function($p) use ($f_search, $f_category, $f_type, $f_location, $f_price) {

    // Keyword search
    if ($f_search) {
        $match = stripos($p['title'], $f_search) !== false ||
                 stripos($p['description'], $f_search) !== false ||
                 stripos($p['location'], $f_search) !== false;
        if (!$match) return false;
    }

    // Category match (compared with property_listing.property_type main category)
    if ($f_category && strtolower($p['category']) !== strtolower($f_category)) {
        return false;
    }

    // Property Type match (compared with property_type sub category name)
    if ($f_type && strtolower($p['type']) !== strtolower($f_type)) {
        return false;
    }

    // Location match (by location name or ID)
    if ($f_location) {
        // Check by location name (stripos for partial match)
        if (stripos($p['location'], $f_location) === false) {
            return false;
        }
    }

    // Price range match
    if ($f_price) {
        $price = parsePriceValue($p['price_display']);
        if ($f_price == '0-5m' && $price > 5000000) return false;
        if ($f_price == '5m-10m' && ($price < 5000000 || $price > 10000000)) return false;
        if ($f_price == '10m-20m' && ($price < 10000000 || $price > 20000000)) return false;
        if ($f_price == '20m-50m' && ($price < 20000000 || $price > 50000000)) return false;
        if ($f_price == '50m+' && $price < 50000000) return false;
    }

    return true;
});
?>
