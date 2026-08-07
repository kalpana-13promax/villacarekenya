<?php

$properties = [];

$fallback_images = [
    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1200&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1200&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=1200&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=1200&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1560448204-603b3fc33ddc?q=80&w=1200&auto=format&fit=crop'
];

if (!isset($boj)) {
    require_once (__DIR__ . '/../includes/config.php');
}

/*
 * ----------------------------------------------------
 * Amenities lookup
 * ----------------------------------------------------
 */

$amenities = [];

$rows = $boj->getQuery('
SELECT id,amenity_name
FROM amenity
');

if ($rows) {
    foreach ($rows as $r) {
        $amenities[$r->id] = $r->amenity_name;
    }
}

/*
 * ----------------------------------------------------
 * Main Property Query
 * ----------------------------------------------------
 */

$sql = "

SELECT

p.*,

pt.type as property_type_name,

c.city as city_name,

l.location as location_name

FROM property_listing p

LEFT JOIN property_type pt
ON pt.id=p.category

LEFT JOIN city c
ON c.id=p.city

LEFT JOIN locations l
ON l.id=p.location

WHERE p.status!='7'

ORDER BY p.id DESC

";

$data = $boj->getQuery($sql);

if ($data) {
    foreach ($data as $row) {
        $category = !empty($row->property_type_name)
            ? $row->property_type_name
            : 'Property';

        $location = '';

        if (!empty($row->location_name))
            $location = $row->location_name;

        if (!empty($row->city_name)) {
            if ($location != '')
                $location .= ', ' . $row->city_name;
            else
                $location = $row->city_name;
        }

        if (empty($location))
            $location = $row->address;

        if (empty($location))
            $location = 'Nairobi';

        /*
         * -----------------------
         * Sale / Rent
         * -----------------------
         */

        $badge = 'FOR SALE';

        $rent = false;

        if (stripos($row->available_for, 'rent') !== false ||
                stripos($row->available_for, 'lease') !== false) {
            $badge = 'FOR RENT';

            $rent = true;
        }

        /*
         * -----------------------
         * Price
         * -----------------------
         */

        $price = floatval($row->property_price);

        $price_display = CURRENCY . number_format($price);

        if ($rent) {
            $price_display .= '/Month';
        }

        /*
         * -----------------------
         * Amenities
         * -----------------------
         */

        $propertyAmenities = [];

        if (!empty($row->property_amenities)) {
            $ids = explode(',', $row->property_amenities);

            foreach ($ids as $id) {
                $id = trim($id);

                if (isset($amenities[$id])) {
                    $propertyAmenities[] = $amenities[$id];
                }
            }
        }

        /*
         * -----------------------
         * Beds Bath
         * -----------------------
         */

        $beds = 0;

        $baths = 0;

        if (!empty($row->property_attribute)) {
            $json = json_decode($row->property_attribute, true);

            if (is_array($json)) {
                foreach ($json as $a) {
                    if ($a['field_id'] == 3) {
                        $beds = intval($a['field_type_value']);
                    }

                    if ($a['field_id'] == 13) {
                        $baths = intval($a['field_type_value']);
                    }
                }
            }
        }

        /*
         * -----------------------
         * Size
         * -----------------------
         */

        $size = '';

        if ($row->size != '' && $row->size != '0') {
            $unit = !empty($row->measurement)
                ? $row->measurement
                : 'sq.ft';

            $size = $row->size . ' ' . $unit;
        }

        /*
         * -----------------------
         * Featured Image
         * -----------------------
         */

        if (!empty($row->property_image) &&
                file_exists(__DIR__ . '/../uploads/' . $row->property_image)) {
            $image = 'uploads/' . $row->property_image;
        } else {
            $image = $fallback_images[$row->id % count($fallback_images)];
        }

        /*
         * -----------------------
         * Gallery
         * -----------------------
         */

        $gallery = [];

        if (!empty($row->gallery)) {
            $g = json_decode($row->gallery, true);

            if (is_array($g)) {
                foreach ($g as $img) {
                    if (file_exists(__DIR__ . '/../uploads/' . $img)) {
                        $gallery[] = 'uploads/' . $img;
                    }
                }
            }
        }

        if (empty($gallery)) {
            $gallery[] = $image;
        }

        /*
         * -----------------------
         * Title
         * -----------------------
         */

        $title = trim($row->property_title);

        if (empty($title)) {
            $title = $category . ' Property';
        }

        /*
         * -----------------------
         * Description
         * -----------------------
         */

        $description = '';

        if (!empty($row->property_description)) {
            $description = strip_tags($row->property_description);
        }

        /*
         * -----------------------
         * Final Array
         * -----------------------
         */

        $properties[$row->id] = [
            'id' => $row->id,
            'title' => $title,
            'location' => $location,
            'price' => number_format($price),
            'price_display' => $price_display,
            'category' => !empty($row->property_type) ? strtolower($row->property_type) : '',
            'type' => strtoupper($category),
            'badge' => $badge,
            'beds' => $beds,
            'baths' => $baths,
            'size' => $size,
            'featured_image' => $image,
            'gallery' => $gallery,
            'description' => $description,
            'amenities' => $propertyAmenities,
            'agent' => !empty($row->uploader)
                ? $row->uploader
                : 'VillaCare Kenya',
            'posted' => date(
                'M d, Y',
                strtotime($row->created_at)
            )
        ];
    }
}

?>